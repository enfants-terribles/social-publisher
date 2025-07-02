<div class="col-sm-6 col-lg-4 col-xl-3 pb-3 teaser rectangle text-center ps-2 pe-2">
    <?php if( get_field('show_link_portfolio_kachel') ) { ?>
        <?php 
        $link = get_field('link_portfolio_kachel');
        if( $link ): 
            $link_url = $link['url'];
            $link_title = $link['title'];
            $link_target = $link['target'] ? $link['target'] : '_self';
            ?>
            <a class="white" href="<?php echo esc_url( $link_url ); ?>" target="<?php echo esc_attr( $link_target ); ?>"><?php echo esc_html( $link_title ); ?>
        <?php endif; ?>
            <?php if (has_post_thumbnail( $post->ID ) ): ?>
                <?php 
                $image_id = get_post_thumbnail_id( $post->ID );
                $image = wp_get_attachment_image_src( $image_id, 'single-post-thumbnail' );
                $alt_text = get_post_meta($image_id, '_wp_attachment_image_alt', true);
                ?>
                <img src="<?php echo $image[0]; ?>" alt="<?php echo esc_attr($alt_text); ?>">
            <?php endif; ?>
            <div class="hover_content">
                <svg class="arrow-1" version="1.1" xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 1078 1024">
                    <path fill="#ff2e48" d="M235.789 1.867v101.053h636.126l-838.232 838.232 71.242 71.242 838.232-838.232v636.126h101.053v-808.421z"></path>
                </svg>
            </div>
        </a>
    <?php } elseif( get_field('keine_funktion_nur_bild') ) { ?>
            <?php if (has_post_thumbnail( $post->ID ) ): ?>
                <?php $image_id = get_post_thumbnail_id( $post->ID );
                $image = wp_get_attachment_image_src( $image_id, 'single-post-thumbnail' ); ?>
                <img src="<?php echo $image[0]; ?>" alt="<?php echo get_post_meta($image_id, '_wp_attachment_image_alt', true); ?>">
            <?php endif; ?>
    <?php } else { ?>   
        <a class="white" href="<?php the_permalink(); ?>">
            <?php if (has_post_thumbnail( $post->ID ) ): ?>
                <?php $image_id = get_post_thumbnail_id( $post->ID );
                $image = wp_get_attachment_image_src( $image_id, 'single-post-thumbnail' ); ?>
                <img src="<?php echo $image[0]; ?>" alt="<?php echo get_post_meta($image_id, '_wp_attachment_image_alt', true); ?>">
            <?php endif; ?>
            <div class="hover_content">
                <svg class="arrow-1" version="1.1" xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 1078 1024">
                    <path fill="#ff2e48" d="M235.789 1.867v101.053h636.126l-838.232 838.232 71.242 71.242 838.232-838.232v636.126h101.053v-808.421z"></path>
                </svg>
            </div>
        </a>
    <?php }; wp_reset_query(); ?>   
</div>
