<?php
/**
 * Plugin Name: Social Publisher
 * Plugin URI: https://www.enfants.de
 * Description: Veröffentliche WordPress-Beiträge auf Wunsch auf LinkedIn mit eigenem Text und Bild (und in Zukunft weitere soziale Netzwerke).
 * Version: 1.1.0
 * Author: Enfants Terribles digital GmbH
 * Author URI: https://www.enfants.de
 * License:     GPL2
 * Text Domain: social-publisher
 *
 * @package Social_Publisher
 */

if ( ! defined( 'SOCIAL_PUBLISHER_CLIENT_ID' ) ) {
	define( 'SOCIAL_PUBLISHER_CLIENT_ID', '78qxcozlhwgid3' );
}
if ( ! defined( 'SOCIAL_PUBLISHER_CLIENT_SECRET' ) ) {
	define( 'SOCIAL_PUBLISHER_CLIENT_SECRET', 'WPL_AP1.cfsNgKn5j7oLKsgL.3OoqUA==' );
}

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
function schedule_linkedin_post( $post_id, $post ) {
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
    wp_schedule_single_event( time() + 5, 'post_to_linkedin_event', array( $post_id ) );
}
add_action( 'save_post', 'schedule_linkedin_post', 10, 2 );

/**
 * Hook for LinkedIn Posting Event.
 *
 * @param int $post_id The ID of the post being published to LinkedIn.
 */
function post_to_linkedin_event( $post_id ) {
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
    $response = social_publisher_post_to_linkedin($access_token, $target_urn, $post_id, $content, $image_url);
	if ($response) {
		update_post_meta($post_id, '_linkedin_published', true);
		update_post_meta($post_id, '_linkedin_profile_urn', get_option('social_publisher_linkedin_target_profile')); // ➔ Profil speichern
	} else {
        error_log('[ERROR] LinkedIn post failed.');
    }
}

add_action( 'post_to_linkedin_event', 'post_to_linkedin_event' );

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

if ( ! function_exists( 'add_acf_field' ) ) {
	/**
	 * Registers ACF fields for LinkedIn sharing functionality.
	 *
	 * This function adds custom fields for LinkedIn sharing, including a message,
	 * a checkbox to enable sharing, a custom image field, and a text field for LinkedIn posts.
	 *
	 * @return void
	 */
	function add_acf_field() {
		if ( function_exists( 'acf_add_local_field_group' ) ) {
			acf_add_local_field_group(
				array(
					'key'                   => 'group_linkedin_sharing',
					'title'                 => 'LinkedIn Sharing',
					'fields'                => array(
						array(
							'key'     => 'field_linkedin_mitteilung',
							'label'   => 'Mitteilung',
							'name'    => 'linkedin_mitteilung',
							'type'    => 'message',
							'message' => '<div style="font-weight: bold; color: #333; padding: 10px; background: #f3f4f6; border: 1px solid #ddd;">Bereits veröffentlichte Beiträge können hier nicht bearbeitet werden. Änderungen müssen direkt auf LinkedIn vorgenommen werden.</div>',
							'wrapper' => array(
								'width' => '100',
							),
						),
						array(
							'key'           => 'field_linkedin_share',
							'label'         => 'Share on LinkedIn',
							'name'          => 'linkedin_share',
							'type'          => 'true_false',
							'instructions'  => 'Aktivieren, um diesen Beitrag auf LinkedIn zu teilen.',
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
							'instructions'  => 'Bitte Bild für den LinkedIn-Post auswählen.<br>SVG wird nicht unterstützt.',
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
							'instructions' => 'Dieser Text wird für den LinkedIn-Post verwendet. Wenn leer, wird der normale Beitragstext übernommen.',
							'rows'         => 5,
							'placeholder'  => 'Geben Sie den Text für LinkedIn ein...',
							'wrapper'      => array(
								'width' => '35',
								'class' => '',
								'id'    => '',
							),
						),
						array(
							'key'     => 'field_linkedin_preview',
							'label'   => 'LinkedIn Vorschau',
							'name'    => 'linkedin_preview',
							'type'    => 'message',
							'message' => '<div id="linkedin-preview" style="border: 1px solid #ccc; padding: 15px; background: #f9f9f9;" data-image-url="' . esc_url( resolve_linkedin_image_url( get_the_ID() ) ) . '"></div>',
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

add_action( 'acf/init', 'add_acf_field' );

if ( ! function_exists( 'check_acf_dependency' ) ) {
	/**
	 * Checks if Advanced Custom Fields (ACF) is installed and activated.
	 *
	 * If ACF is not available, an admin notice is displayed to inform the user.
	 *
	 * @return void
	 */
	function check_acf_dependency() {
		if ( ! class_exists( 'ACF' ) ) {
			add_action(
				'admin_notices',
				function () {
					echo '<div class="notice notice-error"><p>';
					echo 'The <strong>Social Publisher</strong> plugin requires <strong>Advanced Custom Fields (ACF)</strong> to be installed and activated.';
					echo '</p></div>';
				}
			);
		}
	}
}
add_action( 'admin_init', 'check_acf_dependency' );

register_activation_hook(
	__FILE__,
	function () {
		if ( ! class_exists( 'ACF' ) ) {
			deactivate_plugins( plugin_basename( __FILE__ ) );
			wp_die(
				'The <strong>Social Publisher</strong> plugin requires <strong>Advanced Custom Fields (ACF)</strong> to be installed and activated. Please install and activate ACF before activating this plugin.',
				'Plugin dependency check',
				array( 'back_link' => true )
			);
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
            if ( $is_published ) {
                echo '<span style="color: green;">' . esc_html__( 'Yes', 'social-publisher' ) . '</span>';
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
                echo esc_html( $name );
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
                    echo esc_html( $email ?: 'Persönliches Profil' );
                } else {
                    echo '<span style="color: gray;">' . esc_html__( 'N/A', 'social-publisher' ) . '</span>';
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
		}
	}
);

add_action( 'wp_ajax_get_image_url', 'get_image_url_callback' );
add_action( 'wp_ajax_nopriv_get_image_url', 'get_image_url_callback' );

/**
 * Retrieves the URL of an image attachment based on the provided image ID.
 *
 * This function is used in an AJAX request to fetch the image URL from a given image ID.
 * It verifies the presence of the ID, sanitizes it, and returns the attachment URL.
 *
 * @return void Outputs a JSON response with the image URL or an error message.
 */
/**
 * Retrieves the URL of an image attachment based on the provided image ID.
 *
 * This function is used in an AJAX request to fetch the image URL from a given image ID.
 * It verifies the presence of the ID, sanitizes it, and returns the attachment URL.
 *
 * @return void Outputs a JSON response with the image URL or an error message.
 */
function get_image_url_callback() {
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
			error_log("⚠️ Early output detected in admin_enqueue_scripts at $file:$line");
			return;
		}

		wp_enqueue_script(
			'linkedin-preview-script',
			plugin_dir_url( __FILE__ ) . 'js/linkedin-preview.js',
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
    $settings_link = '<a href="options-general.php?page=social-publisher-settings">Einstellungen</a>';
    array_unshift($links, $settings_link);
    return $links;
}
add_filter('plugin_action_links_' . plugin_basename(__FILE__), 'social_publisher_add_settings_link');
