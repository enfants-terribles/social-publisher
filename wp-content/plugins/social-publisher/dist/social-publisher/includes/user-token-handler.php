<?php
if ( ! defined( 'ABSPATH' ) ) exit;
function social_publisher_get_user_access_token($user_id = null) {
    $user_id = $user_id ?: get_current_user_id();
    $token = get_user_meta($user_id, '_linkedin_access_token', true);
    $expires = get_user_meta($user_id, '_linkedin_expires', true);

    if ($expires && time() > $expires) {
        return false; // Token abgelaufen
    }

    return $token;
}

function social_publisher_store_user_token($user_id, $data) {
    update_user_meta($user_id, '_linkedin_access_token', $data['access_token']);
    update_user_meta($user_id, '_linkedin_expires', time() + intval($data['expires_in']));
    if (!empty($data['refresh_token'])) {
        update_user_meta($user_id, '_linkedin_refresh_token', $data['refresh_token']);
    }
}