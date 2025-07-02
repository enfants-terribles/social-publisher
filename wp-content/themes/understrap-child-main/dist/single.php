<?php
/**
 * The template for displaying all single posts
 *
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

get_header();
$container = get_theme_mod( 'understrap_container_type' );
?>

<div class="wrapper" id="full-width-page-wrapper">

	<div class="<?php echo esc_attr( $container ); ?> ps-0 pe-0 bg-white" id="content" tabindex="-1">

	<div class="row gx-0">
    <?php  
$thumbnail_id = get_post_thumbnail_id( $post->ID );  
$thumbnail_alt = get_post_meta( $thumbnail_id, '_wp_attachment_image_alt', true );  
$thumbnail_title = get_the_title( $thumbnail_id );  

if ( has_post_thumbnail() ) {  
    echo '<img src="' . get_the_post_thumbnail_url( $post->ID, 'full' ) . '"  
    alt="' . esc_attr( $thumbnail_alt ) . '"  
    title="' . esc_attr( $thumbnail_title ) . '"  
    loading="lazy"  
    width="1200" height="675">';  
}  
?>	</div>

    <div class="row gx-0">
        <div class="col"><?php if ( function_exists( 'wp_breadcrumb' ) ) { wp_breadcrumb(); } ?></div>
    </div>

		<div class="row max-1800">

				<?php
				while ( have_posts() ) {
					the_post();
					get_template_part( 'loop-templates/content', 'single' );
					understrap_post_nav();

                    // If comments are open or we have at least one comment, load up the comment template.
					// if ( comments_open() || get_comments_number() ) {
					// 	comments_template();
					// }
				}
				?>


		</div>

	</div>

</div>

<div class="container-fluid section_contact small bg-blue">
    <div class="row max-1360">
        <div class="col-lg-12 d-lg-flex justify-content-between align-items-center">
            <div class="left mb-3 mb-lg-0">
                <?php the_field('adresse_small', 'option') ?>
            </div>
            <div class="right d-lg-flex align-items-center">
                <?php if( have_rows('channels', 'option') ): ?>
                    <div class="icons lg-3 mb-lg-0 me-3">
                    <?php while( have_rows('channels', 'option') ): the_row(); ?>
                        <a href="<?php the_sub_field('link', 'option'); ?>" class="linkedin" target="_blank" aria-label="Social Media Link">
                        <?php echo wp_kses_post( get_sub_field('icon', 'option') ); ?>
                        </a>
                    <?php endwhile; ?>
                    </div>
                <?php endif; ?>
                            
                <?php wp_nav_menu( array( 'theme_location' => 'footer-menu-one' ) ); ?>
                <?php include_once(ABSPATH . 'wp-admin/includes/plugin.php');  ?>  
            </div>
        </div>
    </div>
</div>

<?php get_footer(); ?>