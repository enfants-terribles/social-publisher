<?php
/**
 * Template Name: ET: Blog Category
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

    <?php
    // Fetch posts
    $blog_posts_args = array(
        'post_type' => 'post',
        'post_status' => 'publish',
        'category_name' => 'blog',
        'posts_per_page' => 100,
        'orderby' => array('date' => 'DESC')
    );
    $blog_posts_query = new WP_Query($blog_posts_args);

    // Set default filter ID
    $filter_id = 1073;

    if (defined('WP_ENV')) {
        switch (WP_ENV) {
            case 'local':
                $filter_id = 1073;
                break;
            case 'staging':
                $filter_id = 1038;
                break;
            case 'production':
                $filter_id = 1073;
                break;
        }
    }

    $filter_response_args = array(
        'search_filter_id' => $filter_id,
        'orderby' => array('date' => 'DESC')
    );
    $filter_response_query = new WP_Query($filter_response_args);
    ?>

    <div class="container-fluid bg-white filter">
        <div class="row">
            <div class="col-md-12 text-right ps-0">
                <?php 
                global $searchandfilter;
                $__sf_current_query = $searchandfilter->get($filter_id)->current_query();
                echo do_shortcode('[searchandfilter id=' . $filter_id . ']');
                ?>
            </div>
        </div>
    </div>

    <div class="container-fluid text_cont bg-white pt-5">
        <div class="row max-1800" id="filter-response">
            <?php
            if ($filter_response_query->have_posts()) :
                while ($filter_response_query->have_posts()) : $filter_response_query->the_post();
                    $backgroundImg = wp_get_attachment_url(get_post_thumbnail_id($post->ID), 'full'); 
            ?>
                    <div class="col-lg-4 mb-5">
                        <a class="position-relative" href="<?php the_permalink(); ?>">
                            <div class="wrap">
                                <img src="<?php echo esc_url($backgroundImg); ?>" alt="">
                                <div class="headline mt-3"><?php the_title(); ?></div>
                                <?php the_excerpt(); ?>
                            </div>  
                        </a>
                    </div>
            <?php
                endwhile;
                wp_reset_postdata();
            endif;
            ?>
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

    <?php
    if( get_field('show_contact_footer') ) { 
        include('page-templates/footer.php');	
    } elseif ( get_field('show_contact_footer_small') ) { 
        include('page-templates/footer-small.php');	
    }
    ?>

	<?php get_footer(); ?>

</div>
