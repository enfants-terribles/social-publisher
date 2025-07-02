<?php
/**
 * Template Name: ET: Portfolio
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

    <div class="container filter">
		<div class="row">
            <div class="col-md-12 text-right ps-0">
                <?php 
                global $searchandfilter;
                $__sf_current_query = $searchandfilter->get(489)->current_query();
                    
                echo do_shortcode('[searchandfilter id=489]');
                ?>
            </div>
		</div>
    </div>
    <div class="container">
        <div class="row">
            <div class="col text-center">
                <?php the_content(); ?>
            </div>
        </div>
    </div>
    <div class="container-fluid result bg-blue pt-3 gx-4 mb-5">
		<div class="row" data-masonry='{"percentPosition": true }' id="works-response">
        
        <?php
        $args = array(
            'search_filter_id' => 489,
            'orderby' => 'menu_order',
            'order' => 'ASC'
        );

        $query = new WP_Query($args);

        if ( $query->have_posts() ) :
            while ( $query->have_posts() ) : $query->the_post();
 
               include('loop-templates/portfolio-filter.php');
                
            endwhile;
        endif;
        wp_reset_query();
        ?>

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


