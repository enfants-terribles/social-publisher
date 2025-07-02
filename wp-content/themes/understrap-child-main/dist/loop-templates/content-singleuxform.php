<?php
/**
 * Single post partial template
 *
 * @package Understrap
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;
?>
<div class="col-lg-1"></div>

<div class="col-lg-6">
	<article <?php post_class(); ?> id="post-<?php the_ID(); ?>">

		<header class="entry-header mt-5">

			<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>


		</header><!-- .entry-header -->


		<div class="entry-content">

			<?php
			the_content();
			understrap_link_pages();
			?>

		</div>

	</article><!-- #post-<?php the_ID(); ?> -->
</div>
<div class="col-lg-1"></div>
<div class="col-lg-3 sidebar">
	<div class="sticky-lg-top">

			<?php if( get_field('single_blog_author')) { ?>
				<div class="wrapper">
					<div class="headline">
						Sie wollen mehr zu diesem Thema erfahren?
					</div>
					<div class="person">
						<?php 
							if( get_field('single_blog_author') == 'alex' ) { ?>
								<?php
								if( get_field('mit_bild') ) { ?>
									<img src="<?php echo get_stylesheet_directory_uri() ?>/assets/img/alex.png" alt="">
									<div class="name">Alex Stotz</div>
										<div class="title">Design Lead @ Enfants Terribles</div>
								<?php } ?>
									<a href="tel:+491773165739" class="btn btn-blue icon call">Anrufen</a>
									<a href="<?php echo get_home_url(); ?>/termin-buchen/" class="btn btn-blue icon appointment">Termin</a>
							<?php
							} else if( get_field('single_blog_author') == 'steffen' )  { ?>
								<?php
								if( get_field('mit_bild') ) { ?>
										<img src="https://etwebsite.local/wp-content/uploads/2022/03/Nachtigall.jpg" alt="">
										<div class="name">Steffen Müller</div>
										<div class="title">Tech Lead @ Enfants Terribles</div>
								<?php } ?>
								<a href="tel:+491773165739" class="btn btn-blue icon call">Anrufen</a>
								<a href="<?php echo get_home_url(); ?>/termin-buchen/" class="btn btn-blue icon appointment">Termin</a>
							<?php
							} else {
								echo ('');
							}
						?>
						<?php } ?>
					</div>	
				</div>
			</div>
		</div>
	</div>
</div>
<div class="col-lg-1"></div>


