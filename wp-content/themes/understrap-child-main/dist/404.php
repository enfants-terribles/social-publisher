<?php
/**
 * The template for displaying 404 pages (not found)
 *
 * @package Understrap
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

get_header();

$container = get_theme_mod( 'understrap_container_type' );
?>

<div class="wrapper" id="error-404-wrapper">

	<div class="container not_found">
		<h1 class="first-four">4</h1>
		<div class="cog-wheel1">
				<div class="cog1">
					<div class="top"></div>
					<div class="down"></div>
					<div class="left-top"></div>
					<div class="left-down"></div>
					<div class="right-top"></div>
					<div class="right-down"></div>
					<div class="left"></div>
					<div class="right"></div>
			</div>
		</div>
		
		<div class="cog-wheel2"> 
			<div class="cog2">
					<div class="top"></div>
					<div class="down"></div>
					<div class="left-top"></div>
					<div class="left-down"></div>
					<div class="right-top"></div>
					<div class="right-down"></div>
					<div class="left"></div>
					<div class="right"></div>
			</div>
		</div>
	<h1 class="second-four">4</h1>
		<p class="wrong-para">Uh Oh! Seite nicht gefunden!</p>
	</div>

</div>

<?php
get_footer();
