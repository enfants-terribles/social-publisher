<?php
/**
 * Template Name: ET: Leistungen
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

    <div class="container-fluid gx-4">
        <div class="row">
            <div class="col"><?php if ( function_exists( 'wp_breadcrumb' ) ) { wp_breadcrumb(); } ?></div>
        </div>
    </div>

    <div class="container-fluid gx-4">

        <?php if( have_rows('l_rep') ): ?>
            <?php while( have_rows('l_rep') ): the_row(); ?>
                <div class="row rep">
                    <div class="col-lg-1"></div>
                    <div class="col-lg-4 slug slug-<?php echo get_row_index(); ?>">
                        <div class="position-relative">
                            <?php echo wp_kses_post( get_sub_field('l_slug') ); ?>                           
                            <svg class="arrow-<?php echo get_row_index(); ?>" version="1.1" xmlns="http://www.w3.org/2000/svg" width="1078" height="1024" viewBox="0 0 1078 1024">
                                <path fill="" d="M235.789 1.867v101.053h636.126l-838.232 838.232 71.242 71.242 838.232-838.232v636.126h101.053v-808.421z"></path>
                            </svg>
                        </div>
                        <span><?php the_sub_field('l_subline'); ?></span>
                    </div>
                    <div class="col-lg-5 offset-lg-1 content content-<?php echo get_row_index(); ?>">
                        <?php the_sub_field('l_content'); ?>
                        <?php 
                        $link = get_sub_field('l_link');
                        if( $link ): 
                            $link_url = $link['url'];
                            $link_title = $link['title'];
                            $link_target = $link['target'] ? $link['target'] : '_self';
                            ?>
                            <a class="btn btn-primary transparent mt-3" href="<?php echo esc_url( $link_url ); ?>" target="<?php echo esc_attr( $link_target ); ?>"><?php echo esc_html( $link_title ); ?></a>
                        <?php endif; ?>
                    </div>
                    <div class="col-lg-1"></div>
                </div>
                <style>
                    .content-<?php echo get_row_index(); ?> h1,
                    .content-<?php echo get_row_index(); ?> h2,
                    .content-<?php echo get_row_index(); ?> h3,
                    .slug-<?php echo get_row_index(); ?> span {
                        color: <?php the_sub_field('l_color'); ?>;
                    }
                    svg.arrow-<?php echo get_row_index(); ?> {
                        fill: <?php the_sub_field('l_color'); ?>;
                    }
                    .content-<?php echo get_row_index(); ?> .btn-primary {
                        background: <?php the_sub_field('l_color'); ?>;
                        border: 0;
                    }
                </style>
            <?php endwhile; ?>
        <?php endif; ?>
            
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


