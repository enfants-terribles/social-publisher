<?php
/**
 * Template Name: ET: Portfolio Post - short
 * Template Post Type: portfolio
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
    <div class="container-fluid gx-4">
        <div class="row">
            <div class="col"><?php if ( function_exists( 'wp_breadcrumb' ) ) { wp_breadcrumb(); } ?></div>
        </div>
    </div>

    <div class="container-fluid section_one">
        <div class="row max-1360">
            <div class="col-12 col-lg-1"></div>
            <div class="col-12 col-lg-3 order-1 order-lg-0 text_col" style="margin-bottom: 0px">
            <div class="sticky-llg-top">
                <div class="bold mt-5 creative-title mb-3">
                    <?php the_field('custom_title_portfolio_pdp'); ?>
                </div>
                <?php the_content(); ?>
                </div>
            </div>  
            <div class="col-lg-6 lottie offset-lg-1 text-end order-0 order-lg-1">
                <!-- <div id="animationWindow" class="pt-5"></div> -->
                <div id="animationWindow" class="pt-5" style="display: none;"></div>
<div id="loading" class="loading-icon">
<img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/img/Loading_icon.gif" width="320" alt="" />				

</div> <!-- You can replace this with a spinner image or whatever you prefer -->

            </div>
            
        </div>
    </div>

    <div class="container-fluid section_four gx-lg-0 <?php if( get_field('design') ) { ?>bg-cyan text-dark<?php }; ?>">
        <div class="row">
            <div class="col-12 col-lg-8 offset-lg-4 text-end">
            <?php
                $image = get_field('portfolio_pdp_big_img5');
                if (isset($image) && !empty($image)) {
                    $alt_text = get_post_meta($image['id'], '_wp_attachment_image_alt', true);
                    ?>
                    <img src="<?php echo $image['url']; ?>" alt="<?php echo esc_attr($alt_text); ?>">
                <?php } ?>

            </div>  
        </div>
    </div>

    <div class="container-fluid section_customer bg-gray-fb">
        <div class="row max-1360">
            <div class="col-lg-1"></div>
            <div class="col">
                <div class="headline">Kunde</div>
                <div class="subline"><?php the_field('short_lottie_kunde'); ?></div>
            </div>  
            <div class="col-txt col-lg-3 offset-lg-1">
                <?php the_sub_field('text'); ?>
            </div>  
            <div class="col-lg-1"></div>
        </div>
    </div>

    <div class="container-fluid section_leistungen bg-gray-fb">
        <div class="row max-1360">
            <div class="col-lg-1"></div>
            <div class="col">
                <div class="headline">Unsere Leistungen</div>
            </div>  
            <div class="col-lg-1"></div>
        </div>
    </div>

    <div class="container-fluid section_leistungen list bg-gray-fb">
        <div class="row max-1360">
            <div class="col-lg-1"></div>
            <div class="col-lg-8">
                <ul>
                    <?php if( have_rows('portfolio_pdp_leistungen') ): ?>
                        <?php while( have_rows('portfolio_pdp_leistungen') ): the_row(); ?>
                            <li><?php the_sub_field('leistung') ?></li>
                        <?php endwhile; ?>
                    <?php endif; ?>
                </ul>
            </div>  
            <div class="col-lg-2 text-end mb-5">
                <a href="<?php the_field('portfolio_pdp_livelink'); ?>" class="btn blue icon link-external" target="_blank">zur Webseite</a>
            </div>
            <div class="col-lg-1"></div>
        </div>
    </div>

    <?php if( get_field('zitat') ): ?>
        <div class="container-fluid zitat">
            <div class="row max-1360 position-relative">
                <div class="col-lg-1"></div>
                <div class="col-lg-1 d-flex align-items-start pe-lg-0 justify-content-lg-end">
                    <span class="ql">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" clip-rule="evenodd" d="M10.7255 7.50972C11.1155 13.2618 9.79937 16.6252 3.70611 19.0138L3.2674 18.0876C6.3384 17.1127 9.31191 13.798 8.92194 10.4832C8.142 10.9219 7.16708 11.0194 6.3384 11.0194C3.51113 11.0194 2 9.55705 2 7.50972C2 5.46238 3.60862 4 6.3384 4C8.97069 4 10.5793 5.36489 10.7255 7.50972ZM21.937 7.50972C22.3269 13.2618 21.0108 16.6252 14.9175 19.0138L14.4788 18.0876C17.5498 17.1127 20.5233 13.798 20.1334 10.4832C19.3534 10.9219 18.3785 11.0194 17.5498 11.0194C14.7226 11.0194 13.2114 9.55705 13.2114 7.50972C13.2114 5.46238 14.82 4 17.5498 4C20.1821 4 21.7907 5.36489 21.937 7.50972Z" fill="#04273E"/>
                        </svg>
                    </span>
                </div>
                <div class="col-lg-5 p-lg-0">
                    <blockquote class="blockquote">
                        <?php the_field('zitat'); ?>
                    </blockquote>
                </div>
                <div class="col-lg-1 d-flex align-items-end ps-lg-0 justify-content-end justify-content-lg-center">
                    <span class="qr">&ldquo;</span>
                </div>
                <div class="col-lg-3 text-center">
                <?php
                $image = get_field('zitat_bild');
                $alt_text = get_post_meta($image['id'], '_wp_attachment_image_alt', true);
                ?>
                    <div class="image mb-3"><img src="<?php echo $image['url']; ?>" alt="<?php echo esc_attr($alt_text); ?>"></div>
                    <div class="name"><?php the_field('zitat_name'); ?></div>
                    <div class="firma"><?php the_field('zitat_firma'); ?></div>
                </div>
                <div class="col-lg-1"></div>
            </div>
        </div>
    <?php endif; ?>   
    
    <div class="container-fluid section_breadcrumb bg-blue">
        <div class="row max-1360">
            <div class="col">
                <nav>
                    <h2 class="screen-reader-text"><?php esc_html_e( 'Post navigation', 'understrap' ); ?></h2>
                    <?php
                        $term = 'static'; // specify the term slug to exclude
                        $post_id = get_the_ID(); // get current post ID

                        $args = array(
                            'post_type' => 'portfolio',
                            'posts_per_page' => -1, // Get all posts
                            'orderby' => 'menu_order',
                            'order' => 'ASC',
                            'tax_query' => array(
                                array(
                                    'taxonomy' => 'portfolio_format_taxonomy',
                                    'field'    => 'slug',
                                    'terms'    => $term,
                                    'operator' => 'NOT IN'
                                ),
                            ),
                        );

                        $query = new WP_Query($args);

                        $ids = wp_list_pluck( $query->posts, 'ID' );
                        $thisindex = array_search($post_id, $ids);
                        $previd = (isset($ids[$thisindex-1])) ? $ids[$thisindex-1] : '';
                        $nextid = (isset($ids[$thisindex+1])) ? $ids[$thisindex+1] : '';

                        if (!empty($previd) && !empty($nextid)) {
                            echo '<div class="d-flex nav-links justify-content-between">';
                        } elseif (!empty($nextid)) {
                            echo '<div class="d-flex nav-links justify-content-end">';
                        } elseif (!empty($previd)) {
                            echo '<div class="d-flex nav-links justify-content-start">';
                        }

                        if (!empty($previd)) {
                            echo '<span class="nav-previous"><a href="' . get_the_permalink($previd) . '"><svg width="25" height="24" viewBox="0 0 25 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M8.32843 10.9999H20.5V12.9999H8.32843L13.6924 18.3638L12.2782 19.778L4.5 11.9999L12.2782 4.22168L13.6924 5.63589L8.32843 10.9999Z" fill=""/>
                            </svg>' . _x( 'Voriges Projekt', 'Previous post link', 'understrap' ) . '</a></span>';
                        }

                        if (!empty($nextid)) {
                            echo '<span class="nav-next"><a href="' . get_the_permalink($nextid) . '">' . _x( 'Nächstes Projekt', 'Next post link', 'understrap' ) . '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M16.1716 10.9999L10.8076 5.63589L12.2218 4.22168L20 11.9999L12.2218 19.778L10.8076 18.3638L16.1716 12.9999H4V10.9999H16.1716Z" fill=""/>
                            </svg></a></span>';
                        }

                        if (!empty($previd) || !empty($nextid)) {
                            echo '</div>';
                        }

                        wp_reset_postdata(); // reset the query
                    ?>
                </nav>
            </div>  
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

    <?php if( get_field('path_to_lottiefiles') ): ?>
        <script>
            window.onload = function() {
    if (typeof gsap !== 'undefined') {
        LottieScrollTrigger({
        target: "#animationWindow",
        path: "<?php echo get_site_url(); ?>/wp-content/uploads/lottie-animations/<?php the_field('path_to_lottiefiles'); ?>",
        speed: "3",
        markers: <?php the_field('lottiefiles_markers'); ?>,
        scrub: 0 // seconds it takes for the playhead to "catch up"
            // you can also add ANY ScrollTrigger values here too, like trigger, start, end, onEnter, onLeave, onUpdate, etc. See https://greensock.com/docs/v3/Plugins/ScrollTrigger
        });

        function LottieScrollTrigger(vars) {
            let playhead = { frame: 0 },
                target = gsap.utils.toArray(vars.target)[0],
                speeds = { 
                    slow: "+=2000", 
                    medium: "+=1000", 
                    fast: "+=500" },
                st = { 
                    trigger: target, 
                    pin: true, 
                    start: "-=0 top", 
                    end: speeds[vars.speed] || "+=<?php the_field('lottiefiles_distance'); ?>", 
                    scrub: 0 },
                ctx = gsap.context && gsap.context(),
                animation = lottie.loadAnimation({
                    container: target,
                    renderer: vars.renderer || "svg",
                    loop: false,
                    autoplay: false,
                    path: vars.path,
                    rendererSettings: vars.rendererSettings || { preserveAspectRatio: 'xMidYMid slice' }
                });
            for (let p in vars) { // let users override the ScrollTrigger defaults
                st[p] = vars[p];
            }
            animation.addEventListener("DOMLoaded", function() {
    // Hide the loading spinner and show the animation when loaded
    document.getElementById('loading').style.display = 'none';
    document.getElementById('animationWindow').style.display = 'block';

    let createTween = function() {
        animation.frameTween = gsap.to(playhead, {
        frame: animation.totalFrames - 1,
        ease: "none",
        onUpdate: () => animation.goToAndStop(playhead.frame, true),
        scrollTrigger: st
        });
        return () => animation.destroy && animation.destroy();
    };
    ctx && ctx.add ? ctx.add(createTween) : createTween();
    ScrollTrigger.sort();
    ScrollTrigger.refresh();
            });
            return animation;
            }
    } else {
        // GSAP has not loaded yet, try again after a delay
        setTimeout(window.onload, 100);
    }
};

        </script>
    <?php endif; ?>
