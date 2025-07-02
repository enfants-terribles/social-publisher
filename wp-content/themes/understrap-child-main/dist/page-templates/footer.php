<div class="container-fluid section_contact bg-blue">
    <div class="row max-1360">
        <div class="col-md-1"></div>
        <div class="col-md-11">
            <div class="headline h2 noe">Kontakt</h2>
        </div>
    </div>
    <div class="row max-1360">
        <div class="col-lg-1"></div>
        <div class="col-lg-3 offset-md-5">
            <?php the_field('adresse', 'option') ?>
        </div>
        <div class="col-lg-3 text-end mt-5 mt-lg-0 channels">
            
        <?php if( have_rows('channels', 'option') ): ?>
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
        <?php endif; ?>

            
            <?php wp_nav_menu( array( 'theme_location' => 'footer-menu-one' ) ); ?>

        </div>
    </div>
</div>