<?php

/**
 * Custom post type for Portfolio
 */
function portfolio_post_type() {

	$labels = array(
		'name' => 'Portfolio',
		'singular_name' => 'Portfolio',
		'menu_name' => 'Portfolio',
		'parent_item_colon' => '',
		'all_items' => 'Alle Einträge',
		'view_item' => 'Eintrag ansehen',
		'add_new_item' => 'Neuer Eintrag',
		'add_new' => 'Hinzufügen',
		'edit_item' => 'Eintrag bearbeiten',
		'update_item' => 'Update Eintrag',
		'search_items' => '',
		'not_found' => '',
		'not_found_in_trash' => '',
	);
	$rewrite = array(
		'slug' => 'portfolio',
		'with_front' => true,
		'pages' => true,
		'feeds' => true,
	);
	$args = array(
		'labels' => $labels,
		'supports' => array( 'title', 'editor', 'excerpt', 'thumbnail','class' ),
		'taxonomies' => array( 'maerkte' ),
		'hierarchical' => false,
		'public' => true,
		'show_ui' => true,
		'show_in_menu' => true,
		'show_in_nav_menus' => true,
		'show_in_admin_bar' => true,
		'menu_position' => 31,
		'can_export' => false,
		'has_archive' => false,
		'exclude_from_search' => false,
		'publicly_queryable' => true,
		'menu_icon' => 'dashicons-admin-generic',
		'rewrite' => $rewrite,
		'capability_type' => 'page',
		'show_in_rest' => true,
	);
	register_post_type( 'portfolio', $args );

}

add_action( 'init', 'portfolio_post_type', 0 );