<?php
function social_publisher_add_metabox() {
    add_meta_box(
        'social_publisher_metabox',
        'Social Publisher',
        'social_publisher_render_metabox',
        'post',
        'side'
    );
}
add_action( 'add_meta_boxes', 'social_publisher_add_metabox' );

function social_publisher_render_metabox( $post ) {
    wp_nonce_field( 'social_publisher_metabox_nonce', 'social_publisher_nonce' );

    $linkedin_image = get_post_meta( $post->ID, '_social_publisher_linkedin_image', true );
    echo '<label for="social_publisher_linkedin_image">LinkedIn Bild:</label>';
    echo '<input type="text" id="social_publisher_linkedin_image" name="social_publisher_linkedin_image" value="' . esc_url( $linkedin_image ) . '" />';
    echo '<button class="button upload-button">Bild hochladen</button>';
}
