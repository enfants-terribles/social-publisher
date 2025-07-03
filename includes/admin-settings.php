<?php
// Einstellungsseite hinzufügen
function social_publisher_add_settings_page() {
    add_options_page(
        'Social Publisher Einstellungen',
        'Social Publisher',
        'manage_options',
        'social-publisher-settings',
        'social_publisher_render_settings_page'
    );
}
add_action('admin_menu', 'social_publisher_add_settings_page');

// Einstellungsseite rendern
function social_publisher_render_settings_page() {
    ?>
    <div class="wrap">
        <h1>Social Publisher Einstellungen</h1>
        <form method="post" action="options.php">
            <?php
            settings_fields('social_publisher_options');
            do_settings_sections('social-publisher-settings');
            submit_button();
            ?>
        </form>
        <?php do_action('social_publisher_after_settings'); ?>
        <?php settings_errors( 'social_publisher_messages' ); ?>
        <form method="post" action="">
            <?php wp_nonce_field( 'social_publisher_disconnect' ); ?>
            <input type="submit" name="social_publisher_disconnect" class="button" value="Verbindung zu LinkedIn trennen">
        </form>
    </div>
    <?php
}

// Einstellungen registrieren
function social_publisher_register_settings() {
    // Only register the profile selector
    add_settings_section('social_publisher_linkedin_section', 'LinkedIn API-Einstellungen', null, 'social-publisher-settings');

    register_setting('social_publisher_options', 'social_publisher_linkedin_target_profile');

    add_settings_field(
        'social_publisher_linkedin_target_profile',
        'Zielprofil (Unternehmensseite oder persönliches Profil)',
        function () {
            $selected = get_option('social_publisher_linkedin_target_profile');
            $profiles = get_option('social_publisher_available_profiles', []);

            if (empty($profiles)) {
                echo '<p>Keine verfügbaren Profile. Bitte zuerst mit LinkedIn verbinden.</p>';
                return;
            }

            echo '<select name="social_publisher_linkedin_target_profile">';
            foreach ($profiles as $profile) {
                $value = esc_attr($profile['urn']);
                $icon = ($profile['type'] === 'Company') ? '🏢' : '👤';
                $label = esc_html($icon . ' ' . $profile['name'] . ' (' . $profile['type'] . ')');
                $is_selected = selected($selected, $value, false);
                echo "<option value=\"$value\" $is_selected>$label</option>";
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

    // Save the current admin settings page URL as the return URL
    $return_url = urlencode(admin_url('options-general.php?page=social-publisher-settings'));
    $state = 'linkedin_auth_' . base64_encode($return_url);

    $auth_url = $central_proxy_url . '?state=' . $state;

    if ( ! get_option( 'social_publisher_linkedin_access_token' ) ) {
        echo '<a href="' . esc_url($auth_url) . '" class="button button-primary">Mit LinkedIn verbinden</a>';
    }
}
add_action('social_publisher_after_settings', 'social_publisher_add_linkedin_auth_button');

// Die Funktion social_publisher_handle_linkedin_callback wurde entfernt, da das Handling künftig zentral erfolgt.

// LinkedIn-Verbindungsstatus anzeigen
function social_publisher_display_linkedin_status() {
    // Show disconnect notice if redirected after disconnect
    if (isset($_GET['linkedin_disconnected']) && $_GET['linkedin_disconnected'] === '1') {
        echo '<p style="color: orange;">⚠️ Verbindung zu LinkedIn wurde getrennt.</p>';
        return;
    }

    $access_token = get_option('social_publisher_linkedin_access_token');
    $profiles = get_option('social_publisher_available_profiles');

    if (isset($_GET['linkedin_connected']) && $_GET['linkedin_connected'] === '1') {
        if (isset($_GET['token']) && isset($_GET['profiles'])) {
            $decoded_profiles = json_decode(base64_decode($_GET['profiles']), true);

            if (!empty($_GET['token'])) {
                update_option('social_publisher_linkedin_access_token', sanitize_text_field($_GET['token']));
            }

            if (is_array($decoded_profiles)) {
                update_option('social_publisher_available_profiles', $decoded_profiles);

                // Wähle optional das erste Profil automatisch
                if (!empty($decoded_profiles[0]['urn'])) {
                    update_option('social_publisher_linkedin_target_profile', $decoded_profiles[0]['urn']);
                }
            }

            echo '<p style="color: green;">✅ Erfolgreich mit LinkedIn verbunden!</p>';
            // Nach 1,5 Sekunden reload, damit das Dropdown sichtbar wird und URL-Parameter entfernt werden
            echo '<script>
                // Entferne URL-Parameter aus der Adresszeile nach erfolgreicher Verbindung
                setTimeout(function() {
                    const url = new URL(window.location.href);
                    url.searchParams.delete("linkedin_connected");
                    url.searchParams.delete("token");
                    url.searchParams.delete("profiles");
                    window.history.replaceState({}, document.title, url.pathname + url.search);
                    location.reload();
                }, 1500);
            </script>';
            return;
        }
    }

    if ($access_token && !empty($profiles)) {
        echo '<p style="color: green;">✅ Erfolgreich mit LinkedIn verbunden!</p>';
    } else {
        echo '<p style="color: red;">❌ Nicht mit LinkedIn verbunden.</p>';
    }
}
add_action('social_publisher_after_settings', 'social_publisher_display_linkedin_status');


// Additional Debugging for Token Retrieval in Main Function
add_action('admin_init', function() {
    $access_token = get_option('social_publisher_linkedin_access_token');
    if (!$access_token) {
        //error_log('[DEBUG] No LinkedIn Access Token Found in Options Table.');
    } else {
        //error_log('[DEBUG] LinkedIn Access Token Exists: ' . $access_token);
    }
});

// LinkedIn Disconnect Formular-Handling
add_action('admin_init', function () {
    if ( isset( $_POST['social_publisher_disconnect'] ) && check_admin_referer( 'social_publisher_disconnect' ) ) {
        delete_option( 'social_publisher_linkedin_access_token' );
        delete_option( 'social_publisher_linkedin_target_profile' );
        delete_option( 'social_publisher_available_profiles' );
        // Auch benutzerspezifische Tokens löschen
        delete_user_meta( get_current_user_id(), '_linkedin_access_token' );
        delete_user_meta( get_current_user_id(), '_linkedin_expires' );
        delete_user_meta( get_current_user_id(), '_linkedin_refresh_token' );
        // Redirect after successful disconnect
        wp_redirect(admin_url('options-general.php?page=social-publisher-settings&linkedin_disconnected=1'));
        exit;
    }
});
