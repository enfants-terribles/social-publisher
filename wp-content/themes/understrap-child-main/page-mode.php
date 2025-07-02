<?php
/**
 * Template Name: ET: Mode Landingpage
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

<div class="wrapper" id="full-width-page-wrapper">

    <div class="container-fluid">
        <div class="row hero">
            <div class="col-md-12 p-0">
                <!-- <div class="ratio ratio-16x9"> -->
                    <video playsinline muted autoplay loop preload="auto" class="img-fluid" src="<?php the_field('lp_mode_url') ?>""></video>
                <!-- </div> -->
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var video = document.getElementById('video');
            if (video) {
                video.muted = true;
                video.play().catch(function(error) {
                    console.log('Autoplay prevented:', error);
                });
            }
        });
    </script>

    <div class="container-fluid logos bg-gray-fb ps-0 pe-0">
        <div class="row max-1800">
            <div class="col">
                <div class="d-flex justify-content-center gap-4">
                <?php if( have_rows('lp_mode_logos') ): ?>
                    <?php while( have_rows('lp_mode_logos') ): the_row(); ?>
                        <div class="logo"><img src="<?php echo acf_esc_html( get_sub_field('logo') ); ?>" alt=""></div>
                    <?php endwhile; ?>
                <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid content bg-gray-fb ps-0 pe-0">
        <div class="row max-1800">
            <div class="col-md-2"></div>
            <div class="col-md-8 text-center">
                <p><?php echo acf_esc_html( the_field('lp_mode_content_text') ); ?></p>
                <?php 
                    $link = get_field('lp_mode_content_link');
                    if( $link ): 
                        $link_url = $link['url'];
                        $link_title = $link['title'];
                        $link_target = $link['target'] ? $link['target'] : '_self';
                        ?>
                        <a class="btn icon cyan arrow-right" href="<?php echo esc_url( $link_url ); ?>" target="<?php echo esc_attr( $link_target ); ?>"><?php echo esc_html( $link_title ); ?></a>
                    <?php endif; ?>
                <div class="image">
                    <img src="<?php echo acf_esc_html( the_field('lp_mode_content_image') ); ?>" alt="">
                </div>
            </div>
            <div class="col-md-2"></div>
        </div>
        
            
        <?php if( have_rows('mode_landing_solve_losungen') ): ?>
            <?php while( have_rows('mode_landing_solve_losungen') ): the_row(); ?>
            <div class="row solutions max-1800">
                <div class="col-md-1"></div>
                <div class="col-md-5 slug">
                    <div class="position-relative">
                        <h2 class="headline"><?php echo acf_esc_html( get_sub_field('headline') ); ?> </h2>                     
                            <svg class="arrow-<?php echo get_row_index(); ?>" version="1.1" xmlns="http://www.w3.org/2000/svg" width="1078" height="1024" viewBox="0 0 1078 1024">
                                <path fill="#76d8e5" d="M235.789 1.867v101.053h636.126l-838.232 838.232 71.242 71.242 838.232-838.232v636.126h101.053v-808.421z"></path>
                            </svg>
                        </div>
                </div>
                <div class="col-md-5">
                    <div class="text"><?php echo acf_esc_html( get_sub_field('text') ); ?></div>
                </div>
                <div class="col-md-1"></div>
            </div>
            <?php endwhile; ?>
        <?php endif; ?>
    </div>

    <div class="container-fluid systems bg-cyan">
        <div class="row max-1800">
            <div class="col-md-12">
                <div class="d-flex justify-content-center gap-5 align-items-center">
                    <?php if( have_rows('lp_mode_syystem_logos') ): ?>
                        <?php while( have_rows('lp_mode_syystem_logos') ): the_row(); ?>
                            <div class="logo"><img src="<?php echo acf_esc_html( get_sub_field('logo') ); ?>" alt=""></div>
                        <?php endwhile; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid teaser bg-blue">
        <div class="row max-1800">
            <div class="col-md-1"></div>
            <div class="col-md-10">
                <h1><?php the_field('lp_mode_teaser_headline'); ?></h1>
            </div>
            <div class="col-md-1"></div>
        </div>
        <div class="row max-1800">
            <div class="col-lg-1"></div>
            <div class="col-lg-5 slug slug-<?php echo get_row_index(); ?>">
                <div class="position-relative">
                    <div class="headline"><?php echo acf_esc_html( the_field('lp_mode_teaser_angebot') ); ?></div>                          
                    <svg class="arrow-<?php echo get_row_index(); ?>" version="1.1" xmlns="http://www.w3.org/2000/svg" width="1078" height="1024" viewBox="0 0 1078 1024">
                        <path fill="#76d8e5" d="M235.789 1.867v101.053h636.126l-838.232 838.232 71.242 71.242 838.232-838.232v636.126h101.053v-808.421z"></path>
                    </svg>
                </div>
                <span><?php echo acf_esc_html( the_field('lp_mode_teaser_subline') ); ?></span>
            </div>
            <div class="col-lg-5 content content-<?php echo get_row_index(); ?>">
                <div class="text"><?php echo acf_esc_html( the_field('lp_mode_teaser_text') ); ?></div>
            </div>
            <div class="col-lg-1"></div>
        </div>
    </div>

    <div class="container-fluid contact_section bg-gray-fb" id="contact">
        <div class="row max-1800">
            <div class="col-lg-2"></div>
            <div class="col-lg-7">
                <div class="preline"><?php the_field('form_preline'); ?></div>
                <div class="headline"><?php the_field('form_headline'); ?></div>
                <a href="tel:+491773165739" class="btn btn-cyan icon call me-3 mb-3 mb-sm-0">Anruf</a>
                <!-- <a href="<?php echo get_home_url(); ?>/termin-buchen/" class="btn btn-cyan icon appointment">Termin</a> -->
            </div>
            <div class="col-lg-2"></div>
        </div>
        <div class="row max-1800">
            <div class="col-lg-2"></div>
            <div class="col-lg-6">
                <?php
                    if ( defined( 'WP_ENV' ) ) {
                        if ( WP_ENV === 'local' ) {
                            echo do_shortcode( '[contact-form-7 id="2cc9f2d" title="LP Mode"]' );
                        } 
                        else if ( WP_ENV === 'staging' ) {
                            echo do_shortcode( '[contact-form-7 id="9388cc7" title="LP Mode"]' );
                        } 
                        else if ( WP_ENV === 'production' ) {
                            echo do_shortcode( '[contact-form-7 id="6c27747" title="LP Mode"]' );
                        } 
                    } else {
                        echo do_shortcode( '[contact-form-7 id="6c27747" title="LP Mode"]' );
                    }
                ?>
            </div>
            <div class="col-lg-2"></div>
        </div>
    </div>

    <div class="container-fluid zitat">
        <div class="row max-1360 position-relative">
            <div class="col-lg-1"></div>
            <div class="col-lg-1 d-flex align-items-start pe-lg-0 justify-content-lg-end">
                <span class="ql">
                    <svg width="76" height="57" viewBox="0 0 76 57" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M33.035 13.104C34.491 34.58 29.577 47.138 6.82703 56.056L5.18903 52.598C16.655 48.958 27.757 36.582 26.301 24.206C23.389 25.844 19.749 26.208 16.655 26.208C6.09903 26.208 0.457031 20.748 0.457031 13.104C0.457031 5.46 6.46303 0 16.655 0C26.483 0 32.489 5.096 33.035 13.104ZM74.895 13.104C76.351 34.58 71.437 47.138 48.687 56.056L47.049 52.598C58.515 48.958 69.617 36.582 68.161 24.206C65.249 25.844 61.609 26.208 58.515 26.208C47.959 26.208 42.317 20.748 42.317 13.104C42.317 5.46 48.323 0 58.515 0C68.343 0 74.349 5.096 74.895 13.104Z" fill="#04273E"/>
                    </svg>
                </span>                
            </div>
            <div class="col-lg-5 p-lg-0">
                <blockquote class="blockquote">
                    <?php the_field('lp_mode_zitat'); ?>
                </blockquote>
            </div>
            <div class="col-lg-1 d-flex align-items-end ps-lg-0 justify-content-end justify-content-lg-center">
                <span class="qr">
                    <svg width="74" height="49" viewBox="0 0 74 49" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M0.217562 35.308C-1.23844 14.196 4.40356 7.098 23.5136 0L24.4236 3.276C17.6896 5.278 5.13156 12.922 6.95156 24.206C9.86356 22.568 13.5036 22.204 16.5976 22.204C27.1536 22.204 32.7956 27.664 32.7956 35.308C32.7956 42.952 26.7896 48.412 16.5976 48.412C6.76956 48.412 0.763562 43.316 0.217562 35.308ZM40.7176 35.308C39.2616 14.196 44.9036 7.098 64.0136 0L64.9236 3.276C58.1896 5.278 45.6316 12.922 47.4516 24.206C50.3636 22.568 54.0036 22.204 57.0976 22.204C67.6536 22.204 73.2956 27.664 73.2956 35.308C73.2956 42.952 67.2896 48.412 57.0976 48.412C47.2696 48.412 41.2636 43.316 40.7176 35.308Z" fill="#04273E"/>
                    </svg>
                </span>
            </div>
            <div class="col-lg-3 text-center">
            <?php
            $image = get_field('lp_mode_zitat_bild');
            $alt_text = get_post_meta($image['id'], '_wp_attachment_image_alt', true);
            ?>
                <div class="image mb-3"><img src="<?php echo $image['url']; ?>" alt="<?php echo esc_attr($alt_text); ?>"></div>
                <div class="name"><?php the_field('lp_mode_zitat_name'); ?></div>
                <div class="firma"><?php the_field('lp_mode_zitat_firma'); ?></div>
            </div>
            <div class="col-lg-1"></div>
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
