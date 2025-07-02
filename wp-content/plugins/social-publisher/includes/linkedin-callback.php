<?php
error_reporting(E_ALL & ~E_DEPRECATED);
@ini_set('display_errors', '0');

if ( headers_sent($file, $line) ) {
    error_log("⚠️ Headers already sent in $file on line $line");
}


add_action('rest_api_init', function () {
    register_rest_route('social-publisher/v1', '/linkedin/callback', [
        'methods'  => 'GET',
        'callback' => 'handle_linkedin_callback',
        'args'     => [
            'code'  => ['required' => true],
            'state' => ['required' => true],
        ],
        'permission_callback' => '__return_true',
    ]);
});

function handle_linkedin_callback($request) {
    // Nur starten, wenn nichts gesendet wurde
    if ( ! headers_sent() ) {
        if ( ob_get_length() ) {
            ob_end_clean();
        }
        ob_start();
    }

    $code = sanitize_text_field($request->get_param('code'));
    $state = sanitize_text_field($request->get_param('state'));

    $client_id     = defined('SOCIAL_PUBLISHER_CLIENT_ID') ? SOCIAL_PUBLISHER_CLIENT_ID : '';
    $client_secret = defined('SOCIAL_PUBLISHER_CLIENT_SECRET') ? SOCIAL_PUBLISHER_CLIENT_SECRET : '';
    
    $redirect_uri  = 'https://social-publisher.enfants.de/wp-json/social-publisher/v1/linkedin/callback';

    $response = wp_remote_post('https://www.linkedin.com/oauth/v2/accessToken', [
        'body' => [
            'grant_type'    => 'authorization_code',
            'code'          => $code,
            'redirect_uri'  => $redirect_uri,
            'client_id'     => $client_id,
            'client_secret' => $client_secret,
        ]
    ]);

    if (is_wp_error($response)) {
        echo '<script>alert("Token request failed"); window.close();</script>';
        exit;
    }

    $data = json_decode(wp_remote_retrieve_body($response), true);
    // Debug: log LinkedIn token response body and full response before checking access token
    error_log('[DEBUG] LinkedIn token response body: ' . wp_remote_retrieve_body($response));
    error_log('[DEBUG] LinkedIn token response full: ' . print_r($response, true));
    if (empty($data['access_token'])) {
        echo '<script>alert("No access token returned"); window.close();</script>';
        exit;
    }

    $access_token = $data['access_token'];
    error_log('[LinkedIn Callback] Access Token: ' . $access_token);

    // Persönliches Profil holen
    $user_response = wp_remote_get('https://api.linkedin.com/v2/userinfo', [
        'headers' => [
            'Authorization' => 'Bearer ' . $access_token,
        ]
    ]);
    $user_data = json_decode(wp_remote_retrieve_body($user_response), true);

    // Unternehmensseiten holen
    $org_response = wp_remote_get('https://api.linkedin.com/v2/organizationalEntityAcls?q=roleAssignee&projection=(elements*(*,organizationalTarget~(localizedName)))&count=100', [
        'headers' => [
            'Authorization' => 'Bearer ' . $access_token,
            'X-Restli-Protocol-Version' => '2.0.0',
        ]
    ]);
    $org_data = json_decode(wp_remote_retrieve_body($org_response), true);

    $profiles = [];

    if (!empty($user_data['sub'])) {
        $profiles[] = [
            'urn'  => 'urn:li:person:' . $user_data['sub'],
            'name' => $user_data['email'] ?? 'Persönliches Profil',
            'type' => 'Personal',
        ];
    }

    if (!empty($org_data['elements'])) {
        foreach ($org_data['elements'] as $element) {
            if (!empty($element['organizationalTarget~']['localizedName']) && !empty($element['organizationalTarget'])) {
                $profiles[] = [
                    'urn'  => $element['organizationalTarget'],
                    'name' => $element['organizationalTarget~']['localizedName'],
                    'type' => 'Company',
                ];
            }
        }
    }

    // Token und Profile zwischenspeichern
    update_option('social_publisher_linkedin_access_token', $access_token);
    update_option('social_publisher_available_profiles', $profiles);
    error_log('[LinkedIn Callback] Profiles: ' . print_r($profiles, true));

    // Optional: Automatisch erstes Profil als Zielprofil speichern
    if (!empty($profiles[0]['urn'])) {
        update_option('social_publisher_linkedin_target_profile', $profiles[0]['urn']);
    }

    // Store the access token for the current user as well (per-user meta)
    if (function_exists('social_publisher_store_user_token')) {
        social_publisher_store_user_token(
            get_current_user_id(),
            [
                'access_token' => $access_token,
                'expires_in' => $data['expires_in'] ?? 3600
            ]
        );
    }

    // Weiterleitung ins Backend, State auswerten
    // $state enthält 'linkedin_auth_' . base64_encode($return_url)
    $decoded_state = '';
    $return_url = admin_url('options-general.php?page=social-publisher-settings');

    if ( strpos($state, 'linkedin_auth_') === 0 ) {
        $encoded_url = substr($state, strlen('linkedin_auth_'));
        $maybe_decoded = urldecode(base64_decode($encoded_url));
        if ( filter_var($maybe_decoded, FILTER_VALIDATE_URL) ) {
            $decoded_state = $maybe_decoded;
            $return_url = $decoded_state;
        }
    }

    $return_url = add_query_arg([
        'linkedin_connected' => '1',
        'token'    => $access_token,
        'profiles' => base64_encode(json_encode($profiles)),
    ], $return_url);
    error_log('[LinkedIn Callback] State: ' . $state);
    error_log('[LinkedIn Callback] Decoded return URL: ' . $decoded_state);
    error_log('[LinkedIn Callback] Final redirect URL: ' . $return_url);

    // --- DEBUG: Check buffer before redirect ---
    if ( ob_get_length() ) {
        error_log('[DEBUG] Buffer length before final redirect: ' . ob_get_length());
        ob_end_clean();
    } else {
        error_log('[DEBUG] No buffer content before redirect.');
    }

    // --- DEBUG: Klassische Weiterleitung ---
    wp_redirect( $return_url );
    exit;
}
// Erlaube Weiterleitung zur lokalen Entwicklungsumgebung
add_filter( 'allowed_redirect_hosts', function( $hosts ) {
    $hosts[] = 'etwebsite.local';
    return $hosts;
} );

/**
 * Prüft, ob ein gültiger LinkedIn Access Token vorhanden ist.
 *
 * @return bool
 */
function social_publisher_has_valid_linkedin_token() {
    $token = get_option('social_publisher_linkedin_access_token');
    return !empty($token);
}