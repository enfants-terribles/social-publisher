<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
// Add a meta box for manual LinkedIn publishing (Pro feature)
function socipu_add_pro_meta_box() {
    if (function_exists('socipu_is_linkedin_connected') && socipu_is_linkedin_connected()) {
        add_meta_box(
            'social_publisher_manual_publish',
            __('Publish to LinkedIn (Pro)', 'social-publisher'),
            'socipu_render_pro_meta_box',
            'post',
            'side'
        );
    }
}
add_action('add_meta_boxes', 'socipu_add_pro_meta_box');

function socipu_render_pro_meta_box($post) {
    $already_published = get_post_meta($post->ID, '_linkedin_published', true);
    if ($already_published) {
        echo '<div class="socipu-meta-box">';
        echo '<div class="socipu-meta-success">';
        echo '<span class="dashicons dashicons-yes-alt socipu-icon"></span>' . esc_html__('Already published on LinkedIn', 'social-publisher');
        echo '</div>';
        echo '<label class="socipu-meta-republish">';
        echo '<input type="checkbox" name="linkedin_republish" /> ' . esc_html__('Re-publish this post on update', 'social-publisher');
        echo '</label>';
        echo '<label class="socipu-meta-backlink">';
        echo '<input type="checkbox" name="linkedin_enable_backlink" ' . (get_post_meta($post->ID, '_linkedin_enable_backlink', true) ? 'checked' : '') . ' /> ' . esc_html__('Include backlink in LinkedIn post', 'social-publisher');
        echo '</label>';
        echo '</div>';
    } else {
        echo '<p class="socipu-meta-warning">' . esc_html__('⚠️ Not published on LinkedIn yet.', 'social-publisher') . '</p>';
    }
    wp_nonce_field('social_publisher_manual_action', 'social_publisher_manual_nonce');
}

function socipu_handle_pro_manual_republish($post_id) {
    if (
        defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ||
        wp_is_post_revision($post_id) ||
        !current_user_can('edit_post', $post_id)
    ) {
        return;
    }

    if (
        !isset($_POST['social_publisher_manual_nonce']) ||
        !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['social_publisher_manual_nonce'])), 'social_publisher_manual_action')
    ) {
        return;
    }

    if (isset($_POST['linkedin_republish'])) {
        delete_post_meta($post_id, '_linkedin_published'); // Remove the published flag
        wp_schedule_single_event(time() + 5, 'socipu_post_to_linkedin_event', [$post_id]); // Schedule LinkedIn post again
    }

    if (isset($_POST['linkedin_enable_backlink'])) {
        update_post_meta($post_id, '_linkedin_enable_backlink', 1);
    } else {
        delete_post_meta($post_id, '_linkedin_enable_backlink');
    }
}
add_action('save_post', 'socipu_handle_pro_manual_republish');