<?php
/**
 * Template Name: ET: Portfolio Post
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
    <div class="container-fluid">
        <div class="row">
            <div class="col"><?php if ( function_exists( 'wp_breadcrumb' ) ) { wp_breadcrumb(); } ?>
        </div>
    </div>
    <div class="container-fluid section_one">
        <div class="row">
            <div class="col-12 col-lg-1"></div>
            <div class="col-12 col-lg-4 order-1 order-lg-0 txt">
                <h1 class="h1 f-19 bold home">
                    <?php the_field('custom_title_portfolio_pdp'); ?>
                </h1>
                <?php the_content(); ?>
            </div>  
            <?php $backgroundImg = get_the_post_thumbnail_url();?>
            <div class="col-lg-7 order-0 order-lg-1 image" style="background-image: url('<?php echo $backgroundImg?>')">
                <div class="d-lg-none">
                    <img src="<?php echo $backgroundImg?>'" alt="">
                </div>
            </div>
        </div>
    </div>

    <?php if( have_rows('porfolio_pdp_rows') ): ?>
        <?php while( have_rows('porfolio_pdp_rows') ): the_row(); 
            $image = get_sub_field('image');
            ?>
            <div class="container-fluid section_rep_rows">
                <div class="row max-1800">
                    <div class="col-lg-1"></div>
                    <div class=" col-img col-lg-6">
                        <img src="<?php echo $image; ?>" alt="">
                    </div>  
                    <div class="col-txt col-lg-3 offset-lg-1">
                        <?php the_sub_field('text'); ?>
                    </div>  
                    <div class="col-lg-1"></div>
                </div>
            </div>
        <?php endwhile; ?>
    <?php endif; ?>

    <nav class="container navigation post-navigation">
        <h2 class="screen-reader-text"><?php esc_html_e( 'Post navigation', 'understrap' ); ?></h2>
        <div class="d-flex nav-links justify-content-between">
            <?php
                if ( get_previous_post_link() ) {
                    previous_post_link( '<span class="nav-previous">%link</span>', _x( '<svg height="15" viewBox="0 0 12 20" width="12" xmlns="http://www.w3.org/2000/svg"><path d="m17.0011615 3-10.0011615 9.0021033 10.0011615 9.0021034" fill="none" stroke="#000" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" transform="translate(-6 -2)"/></svg>&nbsp;%title', 'Previous post link', 'understrap' ) );
                }
                if ( get_next_post_link() ) {
                    next_post_link( '<span class="nav-next">%link</span>', _x( '%title&nbsp;<svg height="15" viewBox="0 0 12 20" width="12" xmlns="http://www.w3.org/2000/svg"><path d="m7 3 10.0011615 9.0021033-10.0011615 9.0021034" fill="none" stroke="#000" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" transform="translate(-6 -2)"/></svg>', 'Next post link', 'understrap' ) );
                }
            ?>
        </div>
    </nav>

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

	<script>
		var swiper = new Swiper(".swiperCustomers", {
			loop: false,
			autoplay: true,
			spaceBetween: 0,        
			pagination: {
				el: ".swiper-pagination",
				clickable: true,
			},
			keyboard: {
				enabled: true,
			},
			slidesPerView: 1,
		});

        var swiperLogos = new Swiper(".swiperCustomersLogo", {
			loop: false,
			autoplay: true,
			spaceBetween: 0,   
			pagination: {
				el: ".swiper-pagination-logos",
				clickable: true,
			},
			keyboard: {
				enabled: true,
			},
			slidesPerView: 2,
            breakpoints: {
                640: {
                    slidesPerView: 4,
                },
                1200: {
                    slidesPerView: 7,
                }
            }
		});
	</script>
	<?php get_footer(); ?>
</div>


