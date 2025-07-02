<?php

/**
 * Plugin Name: Social Publisher
 * Plugin URI: https://www.enfants.de/social-publisher-wordpress-plugin-fuer-linkedin-social-media-auto-posting/?utm_source=wordpress.org&utm_medium=plugin&utm_campaign=social_publisher
 * Description: Automatically share WordPress posts to social platforms – starting with LinkedIn. Includes custom text and image options for personal profiles and company pages. Facebook and Instagram support is planned.
 * Version: 1.4.2
 * Author: Enfants Terribles digital GmbH
 * Author URI: https://www.enfants.de/?utm_source=wordpress.org&utm_medium=plugin&utm_campaign=social_publisher
 * License:     GPL2
 * Text Domain: social-publisher
 *
 * @package Social_Publisher
 */

 if ( ! defined( 'ABSPATH' ) ) {
	exit; // Direktzugriff verhindern.
}

/**
 * Initialize the Social Publisher plugin.
 *
 * This function includes all required files for the plugin.
 *
 * @return void
 */
function social_publisher_init() {
	add_action( 'init', function () {
		require_once plugin_dir_path( __FILE__ ) . 'includes/admin-settings.php';
		require_once plugin_dir_path( __FILE__ ) . 'includes/linkedin-api.php';
		require_once plugin_dir_path( __FILE__ ) . 'includes/user-token-handler.php';
		require_once plugin_dir_path( __FILE__ ) . 'includes/early-output-detector.php';
	}, 1 );

	require_once plugin_dir_path(__FILE__) . 'includes/linkedin-callback.php';
}
add_action( 'plugins_loaded', 'social_publisher_init' );

// Avoid duplicate includes.
require_once plugin_dir_path( __FILE__ ) . 'includes/linkedin-post.php'; // Use require_once here as well.

/**
 * Schedule a LinkedIn posting event after a post is saved.
 *
 * This function ensures that LinkedIn posts are only scheduled
 * for published posts and avoids infinite loops.
 *
 * @param int     $post_id The ID of the post being saved.
 * @param WP_Post $post    The post object.
 *
 * @return void
 */
function socipu_schedule_linkedin_post( $post_id, $post ) {
    // Verhindere, dass Autosaves oder Revisions den Prozess starten
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
        return;
    }
    if ( wp_is_post_revision( $post_id ) ) {
        return;
    }

    // Stelle sicher, dass es sich um einen neuen Beitrag handelt (noch nicht veröffentlicht)
    if ( 'publish' !== get_post_status( $post_id ) ) {
        return;
    }

    // Falls der Beitrag bereits auf LinkedIn veröffentlicht wurde, breche ab
    if ( get_post_meta( $post_id, '_linkedin_published', true ) ) {
        return;
    }

    // Plane die Veröffentlichung auf LinkedIn nur für neue Posts
    wp_schedule_single_event( time() + 5, 'socipu_post_to_linkedin_event', array( $post_id ) );
}
add_action( 'save_post', 'socipu_schedule_linkedin_post', 10, 2 );

/**
 * Hook for LinkedIn Posting Event.
 *
 * @param int $post_id The ID of the post being published to LinkedIn.
 */
function socipu_post_to_linkedin_event( $post_id ) {
    // Hole den Beitrag
    $post = get_post( $post_id );

    // Stelle sicher, dass der Beitrag existiert
    if ( ! $post ) {
        return;
    }

    // Verhindere doppelte Veröffentlichungen
    if ( get_post_meta( $post_id, '_linkedin_published', true ) ) {
        return;
    }

    // Stelle sicher, dass der Beitrag wirklich veröffentlicht ist
    if ( 'publish' !== get_post_status( $post_id ) ) {
        return;
    }

    // Prüfe, ob die LinkedIn-Checkbox gesetzt ist
    $share_on_linkedin = get_field( 'linkedin_share', $post_id );
    if ( empty( $share_on_linkedin ) ) {
        return;
    }

    // LinkedIn-Zugangsdaten abrufen
    $access_token = get_option( 'social_publisher_linkedin_access_token' );
    if ( empty( $access_token ) ) {
        return;
    }

    // Zielprofil (author) aus den Optionen holen
    $target_urn = get_option( 'social_publisher_linkedin_target_profile' );
    if ( empty( $target_urn ) ) {
        return;
    }

    // Nur ACF-Text verwenden, kein Fallback
    $custom_text = get_field( 'linkedin_custom_text', $post_id );
    $content     = ! empty( $custom_text ) ? wp_strip_all_tags( $custom_text ) : '';

    // Nur ACF-Bild verwenden, kein Fallback
    $image_url = '';
    $acf_image = get_field('linkedin_image', $post_id);
    if (!empty($acf_image)) {
        if (is_numeric($acf_image)) {
            $image_url = wp_get_attachment_url($acf_image);
        } elseif (filter_var($acf_image, FILTER_VALIDATE_URL)) {
            $image_url = $acf_image;
        }
    }

    // Post an LinkedIn senden (immer author aus Option, nur ACF-Text/Bild)
    $response = socipu_social_publisher_post_to_linkedin($access_token, $target_urn, $post_id, $content, $image_url);
    if ($response) {
        update_post_meta($post_id, '_linkedin_published', true);
        update_post_meta($post_id, '_linkedin_profile_urn', get_option('social_publisher_linkedin_target_profile')); // ➔ Profil speichern
    } else {
    }
}

add_action( 'socipu_post_to_linkedin_event', 'socipu_post_to_linkedin_event' );

add_action(
	'rest_api_init',
	function () {
		register_rest_field(
			'post',
			'linkedin_image_url',
			array(
				'get_callback' => function ( $post_arr ) {
					$image_id = get_field( 'linkedin_image', $post_arr['id'] );
					if ( is_numeric( $image_id ) ) {
						return wp_get_attachment_url( $image_id ); // Return URL if it's an ID.
					}
					return $image_id; // Return the URL directly if it's already a URL.
				},
				'schema'       => null,
			)
		);
	}
);

add_filter( 'acf/rest_api/field_settings/show_in_rest', '__return_true' );


add_action(
	'acf/save_post',
	function ( $post_id ) {
		// Prevent running on autosaves, revisions, or other post types.
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}

		if ( wp_is_post_revision( $post_id ) ) {
			return;
		}

		if ( get_post_type( $post_id ) !== 'post' ) {
			return;
		}

		// Use a transient to prevent duplicate runs for the same post save.
		if ( get_transient( "acf_save_lock_{$post_id}" ) ) {
			return;
		}

		// Set a transient lock to ensure this hook runs only once per save.
		set_transient( "acf_save_lock_{$post_id}", true, 10 ); // 10-second lock.

		// Retrieve the ACF field and log its value.
		$acf_value = get_field( 'linkedin_image', $post_id );

		// Schedule LinkedIn post logic if necessary.
		wp_schedule_single_event( time() + 5, 'post_to_linkedin_event', array( $post_id ) );
	},
	20
); // Priority 20 ensures it runs after all ACF fields are saved.

if ( ! function_exists( 'socipu_add_acf_field' ) ) {
	/**
	 * Registers ACF fields for LinkedIn sharing functionality.
	 *
	 * This function adds custom fields for LinkedIn sharing, including a message,
	 * a checkbox to enable sharing, a custom image field, and a text field for LinkedIn posts.
	 *
	 * @return void
	 */
	function socipu_add_acf_field() {
	    if ( ! socipu_is_linkedin_connected() ) {
	        return;
	    }

	    if ( function_exists( 'acf_add_local_field_group' ) ) {
	        acf_add_local_field_group(
	            array(
	                'key'                   => 'group_linkedin_sharing',
	                'title'                 => 'LinkedIn Sharing',
	                'fields'                => array(
	                    array(
	                        'key'     => 'field_linkedin_mitteilung',
	                        'label'   => 'Notice',
	                        'name'    => 'linkedin_mitteilung',
	                        'type'    => 'message',
	                        'message' => '<div style="font-weight: bold; color: #333; padding: 10px; background: #f3f4f6; border: 1px solid #ddd;">' . esc_html__( 'Posts that have already been published cannot be edited here. Changes must be made directly on LinkedIn.', 'social-publisher' ) . '</div>',
	                        'wrapper' => array(
	                            'width' => '100',
	                        ),
	                    ),
	                    array(
	                        'key'           => 'field_linkedin_share',
	                        'label'         => 'Share on LinkedIn',
	                        'name'          => 'linkedin_share',
	                        'type'          => 'true_false',
	                        'instructions'  => 'Enable to share this post on LinkedIn.',
	                        'default_value' => 0,
	                        'ui'            => 1,
	                        'wrapper'       => array(
	                            'width' => '10',
	                            'class' => '',
	                            'id'    => '',
	                        ),
	                    ),
	                    array(
	                        'key'           => 'field_linkedin_image',
	                        'label'         => 'LinkedIn Image',
	                        'name'          => 'linkedin_image',
	                        'type'          => 'image',
	                        'instructions'  => 'Please select an image for the LinkedIn post.<br>SVG is not supported.',
	                        'return_format' => 'url',
	                        'preview_size'  => 'thumbnail',
	                        'library'       => 'all',
	                        'mime_types'    => 'jpg,jpeg,png,webp',
	                        'wrapper'       => array(
	                            'width' => '20',
	                            'class' => '',
	                            'id'    => '',
	                        ),
	                    ),
	                    array(
	                        'key'          => 'field_linkedin_custom_text',
	                        'label'        => 'LinkedIn Custom Text',
	                        'name'         => 'linkedin_custom_text',
	                        'type'         => 'textarea',
	                        'instructions' => 'This text will be used for the LinkedIn post. If left blank, the regular post content will be used.',
	                        'rows'         => 5,
	                        'placeholder'  => 'Enter the text for LinkedIn...',
	                        'wrapper'      => array(
	                            'width' => '35',
	                            'class' => '',
	                            'id'    => '',
	                        ),
	                    ),
	                    array(
	                        'key'     => 'field_linkedin_preview',
	                        'label'   => 'LinkedIn Preview',
	                        'name'    => 'linkedin_preview',
	                        'type'    => 'message',
	                        'message' => '<div id="linkedin-preview" style="max-width: 555px; border: 1px solid #ccc; background: #f9f9f9;" data-image-url="' . esc_url( socipu_resolve_linkedin_image_url( get_the_ID() ) ) . '"></div>',
	                        'wrapper' => array(
	                            'width' => '35',
	                            'class' => '',
	                            'id'    => '',
	                        ),
	                    ),
	                ),
	                'location'              => array(
	                    array(
	                        array(
	                            'param'    => 'post_type',
	                            'operator' => '==',
	                            'value'    => 'post',
	                        ),
	                    ),
	                ),
	                'menu_order'            => 0,
	                'position'              => 'normal',
	                'style'                 => 'default',
	                'label_placement'       => 'top',
	                'instruction_placement' => 'label',
	                'hide_on_screen'        => '',
	            )
	        );
	    }
	}
}



/**
 * Determines whether the Pro features should be enabled.
 *
 * Uses WP_ENVIRONMENT_TYPE or a defined constant to allow local/dev usage.
 *
 * @return bool
 */
function socipu_is_social_publisher_pro_active() {
    if (
        (defined('WP_ENVIRONMENT_TYPE') && WP_ENVIRONMENT_TYPE === 'local') ||
        (defined('SOCIAL_PUBLISHER_DEV') && SOCIAL_PUBLISHER_DEV === true)
    ) {
        return true;
    }

    return false;
}

/**
 * Load Pro features if available.
 */
function socipu_load_pro_features() {
    $pro_file = plugin_dir_path(__FILE__) . 'includes/social-publisher-pro.php';
    if ( file_exists( $pro_file ) ) {
        include_once $pro_file;
    }
}

if ( socipu_is_social_publisher_pro_active() ) {
    add_action('plugins_loaded', 'socipu_load_pro_features');
}

add_action( 'acf/init', 'socipu_add_acf_field' );

if ( ! function_exists( 'socipu_check_acf_dependency' ) ) {
	/**
	 * Checks if Advanced Custom Fields (ACF) is installed and activated.
	 *
	 * If ACF is not available, an admin notice is displayed to inform the user.
	 *
	 * @return void
	 */
	function socipu_check_acf_dependency() {
		include_once( ABSPATH . 'wp-admin/includes/plugin.php' );

		$acf_installed = file_exists( WP_PLUGIN_DIR . '/advanced-custom-fields/acf.php' );
		$acf_active    = class_exists( 'ACF' );

		if ( ! $acf_active ) {
			add_action(
				'admin_notices',
				function () use ( $acf_installed ) {
					echo '<div class="notice notice-error"><p>';
					if ( $acf_installed ) {
						// translators: 1: Plugin name "Social Publisher", 2: Plugin name "Advanced Custom Fields (ACF)", 3: URL to plugins page.
						echo wp_kses_post( sprintf(
							// translators: 1: Plugin name "Social Publisher", 2: Plugin name "Advanced Custom Fields (ACF)", 3: URL to plugins page.
							__( 'The %1$s plugin requires the %2$s plugin. Please <a href="%3$s" rel="noopener noreferrer">activate ACF here</a> to continue.', 'social-publisher' ),
							'<strong>Social Publisher</strong>',
							'<strong>Advanced Custom Fields (ACF)</strong>',
							esc_url( admin_url( 'plugins.php' ) )
						) );
					} else {
						// translators: 1: Plugin name "Social Publisher", 2: Plugin name "Advanced Custom Fields (ACF)", 3: URL to plugin install search.
						echo wp_kses_post( sprintf(
							// translators: 1: Plugin name "Social Publisher", 2: Plugin name "Advanced Custom Fields (ACF)", 3: URL to plugin install search.
							__( 'The %1$s plugin requires the %2$s plugin. Please <a href="%3$s" rel="noopener noreferrer">install ACF here</a> to continue.', 'social-publisher' ),
							'<strong>Social Publisher</strong>',
							'<strong>Advanced Custom Fields (ACF)</strong>',
							esc_url( admin_url( 'plugin-install.php?s=acf&tab=search&type=term' ) )
						) );
					}
					echo '</p></div>';
				}
			);
		}
	}
}
add_action( 'admin_init', 'socipu_check_acf_dependency' );

register_activation_hook(
	__FILE__,
	function () {
		if ( ! class_exists( 'ACF' ) ) {
			deactivate_plugins( plugin_basename( __FILE__ ) );

			if ( file_exists( WP_PLUGIN_DIR . '/advanced-custom-fields/acf.php' ) ) {
				// Plugin is installed but not active
				wp_die(
					'The <strong>Social Publisher</strong> plugin requires <strong>Advanced Custom Fields (ACF)</strong> to be activated. ' .
					'Please <a href="/wp-admin/plugins.php">activate this plugin</a> before using Social Publisher.',
					'Plugin dependency check',
					array( 'back_link' => true )
				);
			} else {
				// Plugin is not installed
				wp_die(
					'The <strong>Social Publisher</strong> plugin requires <strong>Advanced Custom Fields (ACF)</strong> to be installed and activated. ' .
					'Please <a href="/wp-admin/plugin-install.php?s=acf&tab=search&type=term" rel="noopener noreferrer">install ACF here</a> before activating this plugin.',
					'Plugin dependency check',
					array( 'back_link' => true )
				);
			}
		}
	}
);

add_action(
	'save_post',
	function ( $post_id ) {
		// Sicherheit: Prüfe Autosaves, Revisionen und Berechtigungen.
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}
		if ( wp_is_post_revision( $post_id ) ) {
			return;
		}
		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return;
		}

		// Sicherheit: Nonce prüfen (CSRF-Schutz).
		if ( ! isset( $_POST['social_publisher_nonce'] ) ) {
			return;
		}

		$nonce = sanitize_text_field( wp_unslash( $_POST['social_publisher_nonce'] ) );

		if ( ! wp_verify_nonce( $nonce, 'social_publisher_meta_box' ) ) {
			return;
		}

		// Checkbox-Wert speichern.
		$is_checked = isset( $_POST['linkedin_share'] ) ? '1' : '0';
		update_post_meta( $post_id, '_linkedin_share', $is_checked );
	},
	10
);

// Add a custom column to the posts table.
add_filter(
	'manage_posts_columns',
	function ( $columns ) {
		// Add the LinkedIn column after the title column.
		$columns['linkedin_status'] = __( 'LinkedIn', 'social-publisher' );
		$columns['linkedin_profile'] = __( 'LinkedIn Profil', 'social-publisher' );
		return $columns;
	}
);

// Populate the custom LinkedIn column with data.
add_action(
    'manage_posts_custom_column',
    function ( $column_name, $post_id ) {
        if ( 'linkedin_status' === $column_name ) {
            $is_published = get_post_meta( $post_id, '_linkedin_published', true );
            $is_scheduled = wp_next_scheduled( 'socipu_post_to_linkedin_event', array( $post_id ) );

            if ( $is_published ) {
                echo '<span style="color: green;">' . esc_html__( 'Yes', 'social-publisher' ) . '</span>';
            } elseif ( $is_scheduled ) {
                echo '<span class="sp-loading" style="color: #888;">⏳ ' . esc_html__( 'Publishing ...', 'social-publisher' ) . '</span>';
            } else {
                echo '<span style="color: red;">' . esc_html__( 'No', 'social-publisher' ) . '</span>';
            }
        }

        if ( 'linkedin_profile' === $column_name ) {
            $profile_urn = get_post_meta( $post_id, '_linkedin_profile_urn', true );
            $profiles = get_option('social_publisher_available_profiles', []);
            $name = null;

            foreach ($profiles as $profile) {
                if ($profile['urn'] === $profile_urn) {
                    $name = $profile['name'];
                    break;
                }
            }

            if ( $name ) {
                echo '<span style="opacity: 1;">' . esc_html( $name ) . '</span>';
            } else {
                // Custom fallback: show email if personal profile, else N/A
                if ( strpos( $profile_urn, 'person:' ) !== false ) {
                    $access_token = get_option( 'social_publisher_linkedin_access_token' );
                    $user_info = wp_remote_get( 'https://api.linkedin.com/v2/userinfo', [
                        'headers' => [
                            'Authorization' => 'Bearer ' . $access_token,
                        ],
                    ]);
                    $email = '';
                    if ( ! is_wp_error( $user_info ) ) {
                        $user_body = json_decode( wp_remote_retrieve_body( $user_info ), true );
                        $email = $user_body['email'] ?? '';
                    }
                    echo '<span style="opacity: 1;">' . esc_html( $email ?: esc_html__( 'Personal Profile', 'social-publisher' ) ) . '</span>';
                } else {
                    echo '<span style="color: gray; opacity: 1;">' . esc_html__( 'N/A', 'social-publisher' ) . '</span>';
                }
            }
        }
    },
    10,
    2
);

// Make the LinkedIn column sortable.
add_filter(
	'manage_edit-post_sortable_columns',
	function ( $columns ) {
		$columns['linkedin_status'] = 'linkedin_status';
		$columns['linkedin_profile'] = 'linkedin_profile';
		return $columns;
	}
);

// Handle sorting for the LinkedIn column.
add_action(
	'pre_get_posts',
	function ( $query ) {
		if ( ! is_admin() || ! $query->is_main_query() ) {
			return;
		}

		$orderby = $query->get( 'orderby' ); // ✅ Assign first, then check

		if ( 'linkedin_status' === $orderby ) {
			$query->set( 'meta_key', '_linkedin_published' );
			$query->set( 'orderby', 'meta_value' );
		} else if ( 'linkedin_profile' === $orderby ) {
			$query->set( 'meta_key', '_linkedin_profile_urn' );
			$query->set( 'orderby', 'meta_value' );
		}
	}
);

if ( ! defined( 'ABSPATH' ) ) exit;
add_action( 'wp_ajax_get_image_url', 'socipu_get_image_url_callback' );
add_action( 'wp_ajax_nopriv_get_image_url', 'socipu_get_image_url_callback' );

/**
 * Retrieves the URL of an image attachment based on the provided image ID.
 *
 * This function is used in an AJAX request to fetch the image URL from a given image ID.
 * It verifies the presence of the ID, sanitizes it, and returns the attachment URL.
 *
 * @return void Outputs a JSON response with the image URL or an error message.
 */
function socipu_get_image_url_callback() {
	// ✅ Nonce überprüfen, um CSRF-Angriffe zu verhindern.
	if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['nonce'] ) ), 'get_image_url_nonce' ) ) {
		wp_send_json_error( array( 'message' => __( 'Invalid request.', 'social-publisher' ) ) );
		wp_die();
	}

	// ✅ Image-ID validieren
	if ( empty( $_POST['image_id'] ) ) {
		wp_send_json_error( array( 'message' => __( 'Image ID is missing.', 'social-publisher' ) ) );
		wp_die();
	}

	$image_id = absint( $_POST['image_id'] );

	// ✅ Image-URL abrufen
	$image_url = wp_get_attachment_url( $image_id );

	if ( ! $image_url ) {
		wp_send_json_error( array( 'message' => __( 'Image not found.', 'social-publisher' ) ) );
		wp_die();
	}

	wp_send_json_success( array( 'url' => esc_url( $image_url ) ) );
	wp_die();
}

add_action(
	'acf/input/admin_enqueue_scripts',
	function () {
		// Frühzeitige Ausgabe verhindern
		if ( headers_sent( $file, $line ) ) {
		}

		$script_url = plugin_dir_url( __FILE__ ) . 'js/linkedin-preview.js';

		wp_enqueue_script(
			'linkedin-preview-script',
			$script_url,
			array( 'jquery' ),
			'1.0',
			true
		);

		wp_localize_script(
			'linkedin-preview-script',
			'linkedinPreview',
			array(
				'ajaxurl' => admin_url( 'admin-ajax.php' ),
				'nonce'   => wp_create_nonce( 'get_image_url_nonce' ),
			)
		);
	}
);

add_action(
	'wp_ajax_get_acf_image_url',
	function () {
		// ✅ Nonce überprüfen, um CSRF-Angriffe zu verhindern.
		if ( ! isset( $_GET['nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_GET['nonce'] ) ), 'get_acf_image_nonce' ) ) {
			wp_send_json_error( array( 'message' => esc_html__( 'Invalid request.', 'social-publisher' ) ) );
			wp_die();
		}

		// Ensure `image_id` is set, unslash it, then sanitize it.
		$image_id = isset( $_GET['image_id'] ) ? absint( wp_unslash( $_GET['image_id'] ) ) : null;

		if ( $image_id ) {
			$url = wp_get_attachment_url( $image_id );
			if ( $url ) {
				wp_send_json_success( array( 'url' => esc_url( $url ) ) );
			}
		}

		wp_send_json_error( array( 'message' => esc_html__( 'Invalid image ID', 'social-publisher' ) ) );
		wp_die();
	}
);

add_action(
	'acf/input/admin_head',
	function () {
		global $post;
	}
);

add_action(
	'acf/input/admin_head',
	function () {
		global $post;

		if ( $post && $post->ID && get_post_status( $post->ID ) !== 'auto-draft' ) {

		}
	}
);

// Füge einen "Einstellungen"-Link zur Plugin-Übersicht hinzu
function social_publisher_add_settings_link($links) {
    $settings_link = '<a href="options-general.php?page=social-publisher-settings">' . esc_html__( 'Einstellungen', 'social-publisher' ) . '</a>';
    array_unshift($links, $settings_link);
    return $links;
}
add_filter('plugin_action_links_' . plugin_basename(__FILE__), 'social_publisher_add_settings_link');
