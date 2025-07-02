<?php
// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) exit;
// Einstellungsseite hinzufügen
function social_publisher_add_settings_page() {
    add_options_page(
        __( 'Social Publisher Settings', 'social-publisher' ),
        __( 'Social Publisher', 'social-publisher' ),
        'manage_options',
        'social-publisher-settings',
        'social_publisher_render_settings_page'
    );
    // Add submenu under "Beiträge" (Posts) to the existing settings page
    add_submenu_page(
        'edit.php',
        __( 'LinkedIn Publishing', 'social-publisher' ),
        __( 'LinkedIn Publishing', 'social-publisher' ),
        'manage_options',
        'options-general.php?page=social-publisher-settings'
    );
}
add_action('admin_menu', 'social_publisher_add_settings_page');

// Einstellungsseite rendern
function social_publisher_render_settings_page() {
    ?>
    <div class="wrap">
        <h1><?php esc_html_e( 'Social Publisher Settings', 'social-publisher' ); ?></h1>
        <?php
        $access_token = get_option( 'social_publisher_linkedin_access_token' );
        $profiles     = get_option( 'social_publisher_available_profiles' );
        ?>
        <div id="linkedin-loading-indicator" style="margin: 20px 0; display: none;">
            <span class="spinner is-active" style="float: none; visibility: visible;"></span>
            <p style="margin-top: 8px;"><?php esc_html_e( 'Loading LinkedIn profiles…', 'social-publisher' ); ?></p>
        </div>
        <?php

        if ( $access_token && ! empty( $profiles ) ) : ?>
            <?php
            // Ensure the preview field has a populated value for previously published posts where ACF doesn’t auto-populate defaultValue.
            $post_id = get_the_ID();
            $custom_text = $post_id ? get_post_meta( $post_id, 'linkedin_custom_text', true ) : '';
            ?>
            <form method="post" action="options.php">
                <?php
                settings_fields('social_publisher_options');
                do_settings_sections('social-publisher-settings');
                submit_button( __( 'Save Changes', 'social-publisher' ) );
                ?>
            </form>
            <form method="post" action="">
                <?php wp_nonce_field( 'social_publisher_disconnect' ); ?>
                <input type="submit" name="social_publisher_disconnect" class="button" value="<?php esc_attr_e( 'Disconnect from LinkedIn', 'social-publisher' ); ?>">
            </form>
        <?php else : ?>
            <p style="margin: 20px 0; font-style: italic;">
                <?php esc_html_e( 'To get started, connect your LinkedIn account first.', 'social-publisher' ); ?>
            </p>
            <?php do_action('social_publisher_after_settings'); ?>
        <?php endif; ?>
        <?php if ( ! $access_token || empty( $profiles ) ) : ?>
            <p style="margin-top: 20px; font-size: 14px;">
                <?php esc_html_e( 'Authentication and LinkedIn authorization are securely handled via our trusted proxy domain social-publisher.enfants.de. This is why you will see this domain during LinkedIn authorization. No data is stored there permanently, and all data transmission is encrypted and compliant with GDPR (DSGVO).', 'social-publisher' ); ?>
            </p>
        <?php endif; ?>
        <p style="margin-top: 0px; font-size: 14px; padding: 10px 0;">
            🔗 <?php esc_html_e( 'More information about the plugin:', 'social-publisher' ); ?>
            <a href="https://www.enfants.de/social-publisher-wordpress-plugin-fuer-linkedin-social-media-auto-posting/" target="_blank" rel="noopener noreferrer">
                www.enfants.de
            </a>
        </p>
    </div>
    <?php

    if (
        isset($_GET['linkedin_connected'], $_GET['_wpnonce']) &&
        $_GET['linkedin_connected'] === '1' &&
        wp_verify_nonce(sanitize_text_field(wp_unslash($_GET['_wpnonce'])), 'social_publisher_connect_status')
    ) {
        wp_enqueue_script(
            'social-publisher-reload-after-connect',
            plugin_dir_url(__FILE__) . '../assets/js/reload-after-connect.js',
            [],
            filemtime(plugin_dir_path(__FILE__) . '../assets/js/reload-after-connect.js'),
            true
        );
    }
}

// Einstellungen registrieren
function social_publisher_register_settings() {
    // Only register the profile selector
    add_settings_section('social_publisher_linkedin_section', __( 'LinkedIn API Settings', 'social-publisher' ), null, 'social-publisher-settings');

    register_setting(
        'social_publisher_options',
        'social_publisher_linkedin_target_profile',
        array(
            'type' => 'string',
            'sanitize_callback' => 'sanitize_text_field',
            'default' => '',
        )
    );

    add_settings_field(
        'social_publisher_linkedin_target_profile',
        __( 'Target profile (company page or personal profile)', 'social-publisher' ),
        function () {
            $selected = get_option('social_publisher_linkedin_target_profile');
            $profiles = get_option('social_publisher_available_profiles', []);

            if (!is_array($profiles) || empty($profiles)) {
                echo '<p>' . esc_html__( 'No available profiles. Please connect to LinkedIn first.', 'social-publisher' ) . '</p>';
                return;
            }

            echo '<select name="social_publisher_linkedin_target_profile">';
            foreach ($profiles as $profile) {
                if (!is_array($profile) || !isset($profile['urn'], $profile['name'], $profile['type'])) {
                    continue;
                }

                $value = esc_attr($profile['urn']);
                $icon = ($profile['type'] === 'Company') ? '🏢' : '👤';
                $label = $icon . ' ' . $profile['name'] . ' (' . $profile['type'] . ')';
                printf(
                    '<option value="%s"%s>%s</option>',
                    esc_attr($value),
                    selected($selected, $value, false) ? ' selected' : '',
                    esc_html($label)
                );
            }
            echo '</select>';
        },
        'social-publisher-settings',
        'social_publisher_linkedin_section'
    );
}
add_action('admin_init', 'social_publisher_register_settings');


// LinkedIn OAuth hinzufügen (zentraler OAuth-Proxy)
function social_publisher_add_linkedin_auth_button() {
    $central_proxy_url = 'https://social-publisher.enfants.de/oauth/start';

    $nonce = wp_create_nonce('social_publisher_connect_status');

    // Save the current admin settings page URL as the return URL, including the nonce
    $return_url = add_query_arg(
        array(
            'page' => 'social-publisher-settings',
            '_wpnonce' => $nonce,
            'linkedin_connected' => '1'
        ),
        admin_url('options-general.php')
    );

    $state = 'linkedin_auth_' . base64_encode($return_url);
    $auth_url = $central_proxy_url . '?state=' . $state;

    $token = get_option( 'social_publisher_linkedin_access_token' );
    if (
        empty( $token ) ||
        ! is_string( $token ) ||
        strlen( trim( $token ) ) < 10 ||
        empty( get_option( 'social_publisher_available_profiles' ) )
    ) {
        echo '<a href="' . esc_url($auth_url) . '" class="button button-primary">' . esc_html__( 'Connect with LinkedIn', 'social-publisher' ) . '</a>';
    } else {
    }
}
add_action('social_publisher_after_settings', 'social_publisher_add_linkedin_auth_button');

// Die Funktion social_publisher_handle_linkedin_callback wurde entfernt, da das Handling künftig zentral erfolgt.

// LinkedIn-Verbindungsstatus anzeigen
function social_publisher_display_linkedin_status() {
    // Show disconnect notice if redirected after disconnect
    if (
        isset($_GET['linkedin_disconnected']) &&
        $_GET['linkedin_disconnected'] === '1' &&
        isset($_GET['_wpnonce']) &&
        wp_verify_nonce(sanitize_text_field(wp_unslash($_GET['_wpnonce'])), 'social_publisher_disconnect_status')
    ) {
        echo '<p style="color: orange;">' . esc_html__( '⚠️ Disconnected from LinkedIn.', 'social-publisher' ) . '</p>';
        return;
    }

    $access_token = get_option('social_publisher_linkedin_access_token');
    $profiles = get_option('social_publisher_available_profiles');

    if (isset($_GET['linkedin_connected']) && $_GET['linkedin_connected'] === '1') {
        if (
            isset($_GET['token'], $_GET['profiles'], $_GET['_wpnonce']) &&
            wp_verify_nonce(sanitize_text_field(wp_unslash($_GET['_wpnonce'])), 'social_publisher_connect_status') &&
            current_user_can('manage_options')
        ) {
            $decoded_profiles = json_decode(base64_decode(sanitize_text_field(wp_unslash($_GET['profiles']))), true);

            if (!empty($_GET['token'])) {
                update_option('social_publisher_linkedin_access_token', sanitize_text_field(wp_unslash($_GET['token'])));
            }

            if (is_array($decoded_profiles)) {
                update_option('social_publisher_available_profiles', $decoded_profiles);

                // Wähle optional das erste Profil automatisch
                if (!empty($decoded_profiles[0]['urn'])) {
                    update_option('social_publisher_linkedin_target_profile', $decoded_profiles[0]['urn']);
                }
            }

            echo '<p style="color: green;">' . esc_html__( '✅ Successfully connected to LinkedIn!', 'social-publisher' ) . '</p>';
            return;
        }
        // Show error message if token was revoked
        if (
            isset($_GET['error']) &&
            $_GET['error'] === 'revoked_token'
        ) {
            echo '<p style="color: #cc0000; font-weight: bold;">' . esc_html__( '❌ Your LinkedIn token has been revoked. Please reconnect your account to continue publishing.', 'social-publisher' ) . '</p>';
        }
    }

    if ($access_token && !empty($profiles)) {
        echo '<p style="color: green;">' . esc_html__( '✅ Successfully connected to LinkedIn!', 'social-publisher' ) . '</p>';
    } else {
        echo '<p style="color: #a35959;">' . esc_html__( 'Not connected to LinkedIn.', 'social-publisher' ) . '</p>';
    }
}
add_action('social_publisher_after_settings', 'social_publisher_display_linkedin_status');


// Additional Debugging for Token Retrieval in Main Function
add_action('admin_init', function() {
    $access_token = get_option('social_publisher_linkedin_access_token');
    if (!$access_token) {
    } else {
    }
});

// Validate LinkedIn Token on admin_init
add_action('admin_init', function () {
    $token = get_option('social_publisher_linkedin_access_token');
    if (! $token) {
        return;
    }

    $response = wp_remote_get(
        'https://api.linkedin.com/v2/me',
        array(
            'headers' => array(
                'Authorization' => 'Bearer ' . $token,
            ),
            'timeout' => 10,
        )
    );

    if (is_wp_error($response)) {
        return;
    }

    $code = wp_remote_retrieve_response_code($response);
    if ($code === 401) {
        delete_option('social_publisher_linkedin_access_token');
        delete_option('social_publisher_linkedin_target_profile');
        delete_option('social_publisher_available_profiles');
    } else {
    }
});

// LinkedIn Disconnect Formular-Handling
add_action('admin_init', function () {
    if ( isset( $_POST['social_publisher_disconnect'] ) ) {
        check_admin_referer( 'social_publisher_disconnect' );
        delete_option( 'social_publisher_linkedin_access_token' );
        delete_option( 'social_publisher_linkedin_target_profile' );
        delete_option( 'social_publisher_available_profiles' );
        // Auch benutzerspezifische Tokens löschen
        delete_user_meta( get_current_user_id(), '_linkedin_access_token' );
        delete_user_meta( get_current_user_id(), '_linkedin_expires' );
        delete_user_meta( get_current_user_id(), '_linkedin_refresh_token' );
        // Redirect after successful disconnect, include nonce in URL
        wp_redirect(
            add_query_arg(
                array(
                    'page' => 'social-publisher-settings',
                    'linkedin_disconnected' => '1',
                    '_wpnonce' => wp_create_nonce('social_publisher_disconnect_status'),
                ),
                admin_url('options-general.php')
            )
        );
        exit;
    }
});

add_action('admin_enqueue_scripts', function($hook) {
    // Laden auf Settings-Seite und Beitragserstellung/-bearbeitung
    if (
        $hook === 'settings_page_social-publisher-settings' ||
        $hook === 'post.php' ||
        $hook === 'post-new.php'
    ) {
        wp_enqueue_style(
            'social-publisher-settings-css',
            plugin_dir_url(__FILE__) . '../assets/css/app.css',
            array(),
            filemtime(plugin_dir_path(__FILE__) . '../assets/css/app.css')
        );

        wp_enqueue_script(
            'social-publisher-settings-js',
            plugin_dir_url(__FILE__) . '../assets/js/social-publisher-settings.js',
            array(),
            filemtime(plugin_dir_path(__FILE__) . '../assets/js/social-publisher-settings.js'),
            true
        );

         // Neue Datei einbinden (z. B. wenn ein Fehler-Flag in der URL ist)
        if (
            isset($_GET['page']) &&
            $_GET['page'] === 'social-publisher-settings' &&
            isset($_GET['_wpnonce']) &&
            wp_verify_nonce(sanitize_text_field(wp_unslash($_GET['_wpnonce'])), 'social_publisher_connect_status')
        ) {
            wp_enqueue_script(
                'linkedin-feedback',
                plugin_dir_url(__FILE__) . '../assets/js/linkedin-feedback.js',
                [],
                filemtime(plugin_dir_path(__FILE__) . '../assets/js/linkedin-feedback.js'),
                true
            );
        }
    }
});

function socipu_is_linkedin_connected() {
    $access_token = get_option('social_publisher_linkedin_access_token');
    $profiles     = get_option('social_publisher_available_profiles');
    return ! empty($access_token) && is_array($profiles) && ! empty($profiles);
}

// Inject LinkedIn connection status as data attribute on body tag
add_filter('admin_body_class', function($classes) {
    if ( socipu_is_linkedin_connected() ) {
        return $classes . ' linkedin-connected';
    } else {
        return $classes . ' linkedin-not-connected';
    }
});