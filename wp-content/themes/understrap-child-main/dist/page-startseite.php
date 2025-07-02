<?php
/**
 * Template Name: ET: Startseite
 *
 */

// Exit if accessed directly.
defined('ABSPATH') || exit;

get_header();
$container = get_theme_mod('understrap_container_type');
?>
<script src="https://cdn.unicorn.studio/v1.3.1/unicornStudio.umd.js"></script>
<?php if( get_field('loading_animation_homepage' ,'option') ) { ?>
    <div class="preloader">
      <div class="prl-logo">
        <h1 class="hide">
            <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/img/enfants-terribles_logo2.svg" alt="" />
        </h1>
      </div>
      <div class="lightCyan-slider"></div>
      <div class="persianGreen-slider"></div>
      <div class="white-slider"></div>
    </div>
<?php } ?>
<?php if (is_front_page()) : ?>
    <?php get_template_part('global-templates/hero'); ?>
<?php endif; ?>

<div class="wrapper" id="full-width-page-wrapper">
    <div class="container-fluid section_one ps-0 pe-0 overflow-hidden">
        <div class="row">
            <div class="col-12">
            <div
            class="unicorn-embed"
            data-us-project-src="<?php echo get_site_url(); ?>/wp-content/uploads/lottie-animations/unicorn/<?php the_field('path_to_unicornfiles'); ?>"
            data-us-scale="1"
            data-us-dpi="1.5"
            data-us-lazyload="true"
            data-us-disableMobile="true"
            data-us-alttext="Welcome to Enfants"
            data-us-arialabel="This is a canvas scene"
        ></div>

        <script defer>
        document.addEventListener('DOMContentLoaded', function() {
            // Function to fix ARIA role for the dynamically generated canvas
            function fixCanvasAccessibility() {
                const canvas = document.querySelector('.unicorn-embed canvas');
                if (canvas) {
                    canvas.setAttribute('role', 'img'); // Set correct ARIA role
                }
            }

            // Check if UnicornStudio is available
            if (typeof UnicornStudio !== 'undefined') {
                UnicornStudio.init()
                    .then((scenes) => {
                        fixCanvasAccessibility(); // Apply fix after UnicornStudio initializes
                    })
                    .catch((err) => {
                        console.error(err);
                    });
            } else {
                // Wait for UnicornStudio to load if not available
                window.addEventListener('load', function() {
                    UnicornStudio.init()
                        .then((scenes) => {
                            fixCanvasAccessibility(); // Apply fix after UnicornStudio initializes
                        })
                        .catch((err) => {
                            console.error(err);
                        });
                });
            }
        });
        </script>
            </div>  
        </div>
    </div>

    <div class="container-fluid intro_row">
        <div class="row">
            <div class="col-md-1"></div>
            <div class="col-md-7">
                <?php the_content(); ?>
            </div>
        </div>
    </div>

    <div class="container-fluid section_work">
    <?php
    $featured_post = get_field('portfolios_auswahlen');
    if ($featured_post) : ?>
    <?php endif; ?>

    <?php if (have_rows('layouts_work')) : ?>
        <?php while (have_rows('layouts_work')) : the_row(); ?>
            <?php if (get_row_layout() == 'layout_work1') : ?>

                <?php
                $featured_post = get_sub_field('portfolios_auswahlen');
                if ($featured_post) :
                    $image = get_field('work_bild', $featured_post->ID);
                    $headline = get_field('headline', $featured_post->ID);
                    $type = get_field('type', $featured_post->ID);
                    $text = get_field('text', $featured_post->ID);
                    $link = get_field('link', $featured_post->ID);
                    $alt_text = get_post_meta($image['id'], '_wp_attachment_image_alt', true);
                    $srcset = wp_get_attachment_image_srcset($image['id']);
                    $sizes = wp_get_attachment_image_sizes($image['id'], 'full');
                ?>
                    <div class="row max-1800 pt-5 pb-3 layout_1">
                        <div class="col-12 col-xxl-8 d-flex justify-content-center image_col">
                            <img class="img-fluid" 
                                src="<?php echo esc_url($image['url']); ?>" 
                                alt="<?php echo esc_attr($alt_text); ?>" 
                                srcset="<?php echo esc_attr($srcset); ?>"
                                sizes="<?php echo esc_attr($sizes); ?>"
                                width="<?php echo esc_attr($image['width']); ?>" 
                                height="<?php echo esc_attr($image['height']); ?>">
                            </div>
                        <div class="col-12 col-xxl-4 pt-4 max-380 text_col">
                            <span class="type"><?php echo $type; ?></span>
                            <h3 class="headline font-29-32 pb-2"><?php echo $headline; ?></h3>
                            <p><?php echo $text; ?></p>
                            <a class="btn icon white-brd-blue white arrow-right track-matomo"
                               href="<?php echo get_permalink($featured_post->ID); ?>"
                               data-matomo-event-category="CTA"
                               data-matomo-event-action="Click"
                               data-matomo-event-name="Projektdetails">Projektdetails</a>
                        </div>
                    </div>
                    <?php
                    wp_reset_postdata();
                endif;
                ?>

            <?php elseif (get_row_layout() == 'layout_work2') : ?>
                <?php
                $featured_post = get_sub_field('portfolios_auswahlen2');
                if ($featured_post) :
                    $image = get_field('work_bild', $featured_post->ID);
                    $headline = get_field('headline', $featured_post->ID);
                    $type = get_field('type', $featured_post->ID);
                    $text = get_field('text', $featured_post->ID);
                    $link = get_field('link', $featured_post->ID);
                    $alt_text = get_post_meta($image['id'], '_wp_attachment_image_alt', true);
                    $srcset = wp_get_attachment_image_srcset($image['id']);
                    $sizes = wp_get_attachment_image_sizes($image['id'], 'full');
                ?>
                    <div class="row max-1800 pt-5 pb-3 layout_2">
                        <div class="col-12 col-md-7 col-xxl-6 order-xxl-1 image_col d-flex justify-content-start">
                            <img class="img-fluid" 
                                src="<?php echo esc_url($image['url']); ?>" 
                                alt="<?php echo esc_attr($alt_text); ?>" 
                                srcset="<?php echo esc_attr($srcset); ?>"
                                sizes="<?php echo esc_attr($sizes); ?>"
                                width="<?php echo esc_attr($image['width']); ?>" 
                                height="<?php echo esc_attr($image['height']); ?>">
                        </div>
                        <div class="col-12 col-md-5 col-xxl-6 order-xxl-0 max-380 text_col animate" data-animate="fadeIn" data-duration="1.0s" data-delay="0.3s">
                            <span class="type"><?php echo $type; ?></span>
                            <h3 class="headline font-29-32 pb-2"><?php echo $headline; ?></h3>
                            <p><?php echo $text; ?></p>
                            <a class="btn icon white-brd-blue white arrow-right track-matomo"
                               href="<?php echo get_permalink($featured_post->ID); ?>"
                               data-matomo-event-category="CTA"
                               data-matomo-event-action="Click"
                               data-matomo-event-name="Projektdetails">Projektdetails</a>
                        </div>
                    </div>
                    <?php
                    wp_reset_postdata();
                endif;
                ?>

            <?php endif; ?>
        <?php endwhile; ?>
    <?php endif; ?>

</div>


    <?php include('page-templates/home-customer-logos.php'); ?>

    <div class="container-fluid section_customers pb-5 bg-gray-fb">
        <div class="row max-1360">
            <div class="col-md-1"></div>
            <div class="col-md-7 offset-md-3 col-lg-5 offset-lg-5 pull-up">
                <h2 class="noe mt-5"><?php the_field('customers_rep_headline'); ?></h2>
                <p><?php the_field('customers_rep_text'); ?></p>
                <?php
                if ( defined( 'WP_ENV' ) ) {
                    if ( WP_ENV === 'staging' ) {
                        echo '<a class="btn icon blue arrow-right track-matomo"
                                href="/stage/portfolio"
                                data-matomo-event-category="CTA"
                                data-matomo-event-action="Click"
                                data-matomo-event-name="Portfolio">Portfolio</a>';
                    } 
                    else { 
                        echo '<a class="btn icon blue arrow-right track-matomo"
                                href="/portfolio"
                                data-matomo-event-category="CTA"
                                data-matomo-event-action="Click"
                                data-matomo-event-name="Portfolio">Portfolio</a>';
                    }
                }
                ?>
            </div>
            <div class="col-md-1"></div>
        </div>
    </div>

    <div class="container-fluid section_marken bg-blue image_col">
        <div class="row max-1360">
            <div class="col-md-1"></div>
            <div class="col-6 col-md-2 col-xl-1 p-0">
                <div class="ux">
                    <div class="item">
                        Ux
                        <div class="descr">User Experience<br>Design</div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-2 col-xl-1 p-0">
                <div class="ux">
                    <div class="item">
                        Ui
                        <div class="descr">User Interface<br>Design</div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-2 col-xl-1 p-0">
                <div class="ux">
                    <div class="item">
                        Cx
                        <div class="descr">Customer<br>Experience</div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-2 col-xl-1 p-0 d-none d-md-flex">
                <div class="ux">
                    <div class="item plus">
                        <span class="one"></span>
                        <span class="two"></span>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-2 col-xl-1 p-0">
                <div class="ux">
                    <div class="item">
                        Dev
                        <div class="descr">Back- & FrontEnd Development</div>
                    </div>
                </div>
            </div>


        </div>
        <div class="row max-1360">
            <div class="col-md-1"></div>
            <div class="col-lg-5 p-lg-0">
                <h2 class="font-24-32"><?php the_field('home_marken_headline'); ?></h2>
                <p><?php the_field('home_marken_text'); ?></p>
                <?php
                if ( defined( 'WP_ENV' ) ) {
                    if ( WP_ENV === 'staging' ) {
                        echo '<a class="btn icon arrow-right transparent mt-2 track-matomo"
                                href="/stage/leistungen"
                                data-matomo-event-category="CTA"
                                data-matomo-event-action="Click"
                                data-matomo-event-name="Leistungen">Leistungen</a>';
                    } 
                    else { 
                        echo '<a class="btn icon arrow-right transparent mt-2 track-matomo"
                                href="/leistungen"
                                data-matomo-event-category="CTA"
                                data-matomo-event-action="Click"
                                data-matomo-event-name="Leistungen">Leistungen</a>';
                    }
                }
                ?>
            </div>

            <div class="col-md-6 ps-5 image_col">
                <?php
                $image = get_field('home_marken_bild');
                $alt_text = get_post_meta($image['id'], '_wp_attachment_image_alt', true);
                $srcset = wp_get_attachment_image_srcset($image['id']);
                $sizes = wp_get_attachment_image_sizes($image['id'], 'full');
                ?>
                <img class="img-fluid" 
                    src="<?php echo esc_url($image['url']); ?>" 
                    alt="<?php echo esc_attr($alt_text); ?>" 
                    srcset="<?php echo esc_attr($srcset); ?>"
                    sizes="<?php echo esc_attr($sizes); ?>"
                    width="<?php echo esc_attr($image['width']); ?>" 
                    height="<?php echo esc_attr($image['height']); ?>">
            </div>
        </div>
    </div>

    <div class="container-fluid section_about xxxstagger">
        <div class="row max-1360">
            <div class="col-md-1"></div>
            <div class="col-12 col-md-7 pe-lg-5">
                <h2 class="h2 white noe"><?php the_field('text_headline'); ?></h2>
                <p class="white"><?php the_field('text_text'); ?></p>
                <?php 
                $link = get_field('text_link');
                if( $link ):
                    $link_url = $link['url'];
                    $link_title = $link['title'];
                    $link_target = $link['target'] ? $link['target'] : '_self';
                    ?>
                    <a 
                      class="btn icon blue arrow-right track-matomo"
                      href="<?php echo esc_url( $link_url ); ?>" 
                      target="<?php echo esc_attr( $link_target ); ?>" 
                      data-matomo-event-category="CTA" 
                      data-matomo-event-action="Click" 
                      data-matomo-event-name="<?php echo esc_html( $link_title ); ?>"
                    >
                      <?php echo esc_html( $link_title ); ?>
                    </a>
                <?php endif; ?>
            </div>
        </div> 
        <div class="row">
            <div class="col-md-7 offset-md-5">
                <?php
                $image = get_field('text_bild');
                $alt_text = get_post_meta($image['id'], '_wp_attachment_image_alt', true);
                $srcset = wp_get_attachment_image_srcset($image['id']);
                $sizes = wp_get_attachment_image_sizes($image['id'], 'full');
                ?>
                    <img class="img-fluid" 
                        src="<?php echo esc_url($image['url']); ?>" 
                        alt="<?php echo esc_attr($alt_text); ?>" 
                        srcset="<?php echo esc_attr($srcset); ?>"
                        sizes="<?php echo esc_attr($sizes); ?>"
                        width="<?php echo esc_attr($image['width']); ?>" 
                        height="<?php echo esc_attr($image['height']); ?>">
            </div>
        </div>
    </div>

    <?php
        if( get_field('show_contact_footer') ) { 

            include('page-templates/footer.php');		

        } 
    ?>

	<script src="<?php echo get_stylesheet_directory_uri(); ?>/assets/js/jquery.scrolla.min.js"></script>

    <script>
        jQuery('.animate').scrolla();
    </script>
    <link
		rel="stylesheet"
		href="https://cdn.jsdelivr.net/npm/swiper/swiper-bundle.min.css"
	/>
	<script src="https://cdn.jsdelivr.net/npm/swiper/swiper-bundle.min.js"></script>

</div>

<script>
  document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.track-matomo').forEach(function (el) {
      el.addEventListener('click', function () {
        if (typeof _paq !== 'undefined') {
          _paq.push(['trackEvent',
            el.getAttribute('data-matomo-event-category'),
            el.getAttribute('data-matomo-event-action'),
            el.getAttribute('data-matomo-event-name')
          ]);
        }
      });
    });
  });
</script>

<?php get_footer(); ?>
</div>


