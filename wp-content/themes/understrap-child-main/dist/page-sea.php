<?php
/**
 * Template Name: ET: SEA Landingpage
 *
 */

// Exit if accessed directly.
defined('ABSPATH') || exit;

get_header();
$container = get_theme_mod('understrap_container_type');
?>

<?php if (is_front_page()) : ?>
    <?php get_template_part('global-templates/hero'); ?>
<?php endif; ?>

<div class="wrapper bg-blue" id="full-width-page-wrapper">

    <div class="container-fluid hero_cont gx-4 bg-blue">
        <div class="row">
            <div class="col"><?php if ( function_exists( 'wp_breadcrumb' ) ) { wp_breadcrumb(); } ?></div>
        </div>
        <div class="row max-1800 hero">
            <div class="col-md-9">
                <h1 class="headline"><?php echo wp_kses_post( get_field('hero_headline') ); ?></h1>
            </div>
            <div class="col-md-6 offset-md-6">
                <div class="subline"><?php the_field('hero_subline'); ?></div>
                <div class="subline_two"><?php the_field('hero_subline_2'); ?></div>
            </div>
        </div>
    </div>

    <div class="container-fluid swipe_cont ps-0 pe-0">
        <div class="row">
            <div class="col">
                <div class="swiper">
                    <div class="swiper-wrapper">
                        <?php if( have_rows('sea_slider_rep') ): ?>
                            <?php while( have_rows('sea_slider_rep') ): the_row(); ?>
                                <div class="swiper-slide">
                                    <?php $slide = get_sub_field('image'); ?>
                                    <img class="img-fluid" src="<?php echo esc_url( $slide['url'] ); ?>" alt="<?php echo esc_attr( $slide['alt'] ); ?>">
                                </div>
                            <?php endwhile; ?>
                        <?php endif; ?>
                    </div>
                    <div class="controls text-center d-md-none">
                        <svg height="32px" version="1.1" viewBox="0 0 32 32" width="32px" xmlns="http://www.w3.org/2000/svg" xmlns:sketch="http://www.bohemiancoding.com/sketch/ns" xmlns:xlink="http://www.w3.org/1999/xlink"><title/><desc/><defs/><g fill="none" fill-rule="evenodd" id="Page-1" stroke="none" stroke-width="1"><g fill="#04273e" id="icon-43-one-finger-swipe-horizontally"><path d="M20.9719734,14 C20.7206438,11.7496631 18.8100349,10 16.5,10 C14.185047,10 12.2783671,11.7470269 12.0278925,14 L12.0278925,14 L5.950001,14 L9.200001,10.75 L8.450001,10 L3.950001,14.5 L8.450001,19 L9.200001,18.25 L5.950001,15 L5.950001,15 L12,15 L12,31 L21,31 L21,15 L27.049999,15 L23.799999,18.25 L24.549999,19 L29.049999,14.5 L24.549999,10 L23.799999,10.75 L27.049999,14 L20.9719734,14 L20.9719734,14 Z M16.5,12 C15.1192881,12 14,13.1285541 14,14.5097752 L14,18 L19,18 L19,14.5097752 C19,13.1236646 17.8903379,12 16.5,12 L16.5,12 Z M14,21 L14,22 L19,22 L19,21 L14,21 L14,21 Z M14,23 L14,24 L19,24 L19,23 L14,23 L14,23 Z" id="one-finger-swipe-horizontally"/></g></g></svg>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid text_cont bg-white">
        <div class="row max-1800">
            <div class="col-lg-2"></div>
            <div class="col-lg-8">
                <h2 class="headline-main"><?php the_field('sea_text_main_headline'); ?></h2>
                <?php if( have_rows('text_blocks') ): 
                    $count = 0; // Initialize a counter.
                    while( have_rows('text_blocks') ): the_row(); 
                        // Get the content from the ACF field.
                        $icon = wp_kses_post( get_sub_field('icon') );
                        $headline = wp_kses_post( get_sub_field('headline') );
                        $text_block = wp_kses_post( get_sub_field('text_block') ); ?>
                    <?php if($count % 2 == 0): // If the counter is even, render in the left column. ?>
                        <div class="row mb-4">
                            <div class="col-12 col-md-6">
                                <div class="d-flex align-items-start">
                                    <img src="<?php echo $icon; ?>" alt="">
                                    <div class="copy">
                                        <h3 class="headline"><?php echo $headline; ?></h3>
                                        <p><?php echo $text_block; ?></p>
                                    </div>
                                </div>
                            </div>
                    <?php else: // If the counter is odd, render in the right column and close the row. ?>
                            <div class="col-12 col-md-6">
                                <div class="d-flex align-items-start">
                                    <img src="<?php echo $icon; ?>" alt="">
                                    <div class="copy">
                                        <h3 class="headline"><?php echo $headline; ?></h3>
                                        <p><?php echo $text_block; ?></p>
                                    </div>
                                </div>
                            </div>
                        </div> <!-- Closing the row -->
                    <?php endif; ?>
                <?php 
                    $count++; // Increment the counter.
                    endwhile; 
                endif; 

                // If the text_blocks ends with an odd number, close the last opened row.
                if($count % 2 != 0) echo '</div>'; 
                ?>
            </div>
            <div class="col-lg-2"></div>
        </div>
    </div>

    <div class="container-fluid teaser_cont value bg-gray-fb">
        <div class="row max-1800">
            <div class="col-lg-2"></div>
            <div class="col-lg-8">
                <h4 class="headline">
                    <?php the_field('teaser_value_headline'); ?>
                </h4>
                <div class="d-md-flex">
                    <div class="d-flex keyword_wrap">
                        <?php
                            $value = get_field('keyword');
                            if( $value ): ?>
                            <div class="copy">
                                <div class="content">
                                    <div class="headline"><?php echo $value['headline']; ?></div>
                                    <div class="subline"><?php echo $value['subline']; ?></div>
                                </div>
                            </div>
                            <img src="<?php echo esc_url( $value['image']['url'] ); ?>" alt="<?php echo esc_attr( $value['image']['alt'] ); ?>" />
                        <?php endif; ?>
                    </div>
                    <div class="text_blocks">
                        <?php the_field('teaser_value_text'); ?>
                    </div>
                </div>
            </div>
            <div class="col-lg-2"></div>
        </div>
    </div>

    <div class="container-fluid contact_section">
        <div class="row max-1800">
            <div class="col-lg-2"></div>
            <div class="col-lg-5">
                <div class="preline"><?php the_field('form_preline'); ?></div>
                <div class="headline"><?php the_field('form_headline'); ?></div>
                <a href="tel:+491773165739" class="btn btn-cyan icon call me-3 mb-3 mb-sm-0">Anruf</a>
                <a href="<?php echo get_home_url(); ?>/termin-buchen/" class="btn btn-cyan icon appointment">Termin</a>
            </div>
            <div class="col-lg-2"></div>
        </div>
        <div class="row max-1800">
            <div class="col-lg-2"></div>
            <div class="col-lg-8">
                <?php
                    if ( defined( 'WP_ENV' ) ) {
                        if ( WP_ENV === 'local' ) {
                            echo do_shortcode( '[contact-form-7 id="926" title="SEA WordPress"]' );
                        } 
                        else if ( WP_ENV === 'staging' ) {
                            echo do_shortcode( '[contact-form-7 id="938" title="SEA WordPress"]' );
                        } 
                        else if ( WP_ENV === 'production' ) {
                            echo do_shortcode( '[contact-form-7 id="944" title="SEA WordPress"]' );
                        } 
                    } else {
                        echo do_shortcode( '[contact-form-7 id="944" title="SEA WordPress"]' );
                    }
                ?>
            </div>
            <div class="col-lg-2"></div>
        </div>
    </div>

    <?php
    if( get_field('show_contact_footer') ) { 
        include('page-templates/footer.php');	
    } elseif ( get_field('show_contact_footer_small') ) { 
        include('page-templates/footer-small.php');	
    }
    ?>

	<?php get_footer(); ?>

</div>
