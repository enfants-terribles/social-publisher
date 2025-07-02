<div class="container-fluid section_contact small <?php if( get_field('show_contact_footer_small_blue') ) { echo 'bg-blue'; } else {echo 'bg-gray-fb';} ?>">
    <div class="row max-1360">
        <div class="col-lg-12 d-lg-flex justify-content-between align-items-center">
            <div class="left mb-3 mb-lg-0">
                <?php the_field('adresse_small', 'option') ?>
            </div>
            <div class="right d-lg-flex align-items-center">
                <?php if( have_rows('channels', 'option') ): ?>
                    <div class="icons lg-3 mb-lg-0 me-3">
                    <?php while( have_rows('channels', 'option') ): the_row(); ?>
                    <a href="<?php the_sub_field('link'); ?>" aria-label="Social Media Link" class="linkedin" target="_blank">
                    <?php 
                        $allowed_tags = array(
                            'svg' => array(
                                'width' => true,
                                'height' => true,
                                'viewBox' => true,
                                'version' => true,
                                'xmlns' => true,
                                'xmlns:xlink' => true,
                            ),
                            'path' => array(
                                'd' => true,
                                'id' => true,
                            ),
                        );

                        echo wp_kses( get_sub_field('icon'), $allowed_tags ); 
                    ?>
                </a>
                    <?php endwhile; ?>
                    </div>
                <?php endif; ?>
                            
                <?php wp_nav_menu( array( 'theme_location' => 'footer-menu-one' ) ); ?>

            </div>
        </div>
    </div>
    <!-- <div class="row max-1360">
        <div class="col-lg-12 d-lg-flex justify-content-between align-items-center">
            <?php wp_nav_menu( array( 'theme_location' => 'seo-menu' ) ); ?>
        </div>
    </div> -->
</div>