<?php
/**
 * Understrap Child Theme functions and definitions
 *
 * @package UnderstrapChild
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;



/**
 * Removes the parent themes stylesheet and scripts from inc/enqueue.php
 */
function understrap_remove_scripts() {
	wp_dequeue_style( 'understrap-styles' );
	wp_deregister_style( 'understrap-styles' );

	wp_dequeue_script( 'understrap-scripts' );
	wp_deregister_script( 'understrap-scripts' );
}
add_action( 'wp_enqueue_scripts', 'understrap_remove_scripts', 20 );

function theme_gsap_script(){
  wp_enqueue_script( 'gsap-js', 'https://cdnjs.cloudflare.com/ajax/libs/gsap/3.11.4/gsap.min.js', array(), false, true );
  wp_enqueue_script( 'gsap-st', 'https://cdnjs.cloudflare.com/ajax/libs/gsap/3.11.4/ScrollTrigger.min.js', array('gsap-js'), false, true );
}
add_action( 'wp_enqueue_scripts', 'theme_gsap_script' );

function theme_masonry_script() {
  wp_enqueue_script( 'masonry', 'https://cdn.jsdelivr.net/npm/masonry-layout@4.2.2/dist/masonry.pkgd.min.js', array(), true );
}
add_action( 'wp_enqueue_scripts', 'theme_masonry_script' );

function theme_imgloaded_script() {
  wp_enqueue_script( 'imgloaded', 'https://cdnjs.cloudflare.com/ajax/libs/jquery.imagesloaded/4.1.4/imagesloaded.pkgd.min.js', array(), true );
}
add_action( 'wp_enqueue_scripts', 'theme_imgloaded_script' );

function theme_bodymovin_script() {
  wp_enqueue_script( 'bodymovin', 'https://cdnjs.cloudflare.com/ajax/libs/bodymovin/5.6.6/lottie.min.js', array(), true );
}
add_action( 'wp_enqueue_scripts', 'theme_bodymovin_script' );

function enqueue_swiper_styles(){ 
	wp_enqueue_style('swiper_css', 'https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.css');
}
add_action( 'wp_enqueue_scripts', 'enqueue_swiper_styles' );

function theme_swiper_script(){
  wp_enqueue_script( 'swiper-js', 'https://cdn.jsdelivr.net/npm/swiper/swiper-bundle.min.js', array(), true );
}
add_action( 'wp_enqueue_scripts', 'theme_swiper_script' );

function add_defer_attribute($tag, $handle) {
	$scripts_to_defer = array('gsap-js', 'gsap-st', 'masonry', 'imgloaded', 'bodymovin', 'swiper-js', 'child-understrap-scripts');

	foreach($scripts_to_defer as $defer_script) {
			if ($defer_script === $handle) {
					return str_replace(' src', ' defer src', $tag);
			}
	}

	return $tag;
}
add_filter('script_loader_tag', 'add_defer_attribute', 10, 2);




/**
 * Enqueue our stylesheet and javascript file
 */
function theme_enqueue_styles() {

	// Get the theme data.
	$the_theme = wp_get_theme();

	$suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';
	// Grab asset urls.
	$theme_styles  = "/css/child-theme{$suffix}.css";
	$theme_scripts = "/js/child-theme{$suffix}.js";

	wp_enqueue_style( 'child-understrap-styles', get_stylesheet_directory_uri() . $theme_styles, array(), $the_theme->get( 'Version' ) );
	//wp_enqueue_script( 'jquery' );
	wp_enqueue_script('child-understrap-scripts', get_stylesheet_directory_uri() . $theme_scripts, array('gsap-js', 'gsap-st'), $the_theme->get('Version'), true);

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'theme_enqueue_styles' );



/**
 * Load the child theme's text domain
 */
function add_child_theme_textdomain() {
	load_child_theme_textdomain( 'understrap-child', get_stylesheet_directory() . '/languages' );
}
add_action( 'after_setup_theme', 'add_child_theme_textdomain' );



/**
 * Overrides the theme_mod to default to Bootstrap 5
 *
 * This function uses the `theme_mod_{$name}` hook and
 * can be duplicated to override other theme settings.
 *
 * @param string $current_mod The current value of the theme_mod.
 * @return string
 */
function understrap_default_bootstrap_version( $current_mod ) {
	return 'bootstrap5';
}
add_filter( 'theme_mod_understrap_bootstrap_version', 'understrap_default_bootstrap_version', 20 );

/**
 * Loads javascript for showing customizer warning dialog.
 */
function understrap_child_customize_controls_js() {
	wp_enqueue_script(
		'understrap_child_customizer',
		get_stylesheet_directory_uri() . '/js/customizer-controls.js',
		array( 'customize-preview' ),
		'20130508',
		true
	);
}
add_action( 'customize_controls_enqueue_scripts', 'understrap_child_customize_controls_js' );

/**
 * Custom Post Types
 */
include_once 'inc/custom-post-types/portfolio.php';

/**
 * Custom footer menus
 */
function register_footer_menus() {
	register_nav_menus(
		array(
			'footer-menu-one' => __( 'Footer Menü' ),
			'seo-menu' => __( 'SEO Menü' )
		)
	);
}
add_action( 'init', 'register_footer_menus' );

/**
 * Options Pages
 */

 if( function_exists('acf_add_options_page') ) {

	acf_add_options_page();
	acf_add_options_sub_page('Allgemein');
}

/**
 * Custom ET Dashboard
 */
include_once 'inc/et-dashboard.php';

/**
 * Portfolio taxonomy
 */

function portfolio_taxonomy() {  
	register_taxonomy(  
		'portfolio_format_taxonomy',
		'portfolio',
		array(
			'hierarchical' => true,
			'label' => 'Bereiche',
			'show_in_rest' => true,
			'show_ui'           => true,
			'show_admin_column' => true,
		)
	);  
}  
add_action( 'init', 'portfolio_taxonomy');

 /**
  * Breadcrumb
  */

	function wp_breadcrumb() {
    global $post;

    // Check if WordPress is installed in a subfolder
    $home_url = home_url();
    $site_url = site_url();
    if ( $home_url != $site_url ) {
        $subfolder = str_replace( $home_url, '', $site_url );
    } else {
        $subfolder = '';
    }

    // Put the home URL in the SVG link
    echo '<div class="breadcrumb"><a class="home-link" href="' . $home_url . $subfolder . '"><svg width="40" height="40" viewBox="0 0 40 40" xmlns="http://www.w3.org/2000/svg">
    <g fill="" fill-rule="evenodd">
            <path fill="" fill-rule="nonzero" d="m15.69 13.333 14.345 14.345-2.357 2.357L13.333 15.69v12.643H10V10h18.333v3.333z"/>
    </g>
    </svg></a>';

    if ( is_single() ) {
        if ( has_category( 'blog', $post->ID ) ) {
            // If the post belongs to the 'blog' category, link to a custom "blog" page
            echo '<a href="' . $home_url . $subfolder . '/blog/">Blog</a> ';
        } else {
            $post_type = get_post_type();
            echo '<a href="' . $home_url . $subfolder . '/' . $post_type . '">' . ucwords( $post_type ) . '</a> ';
        }
    }
    echo get_the_title();
    echo '</div>';
}

	
	
// Render current page title and breadcrump on mobile offcanvas menu
function get_current_page_info() {
	$title = get_the_title();
	$url = get_permalink();
	return array('title' => $title, 'url' => $url);
}




function add_active_class_to_menu_item( $classes, $item ) {
	global $post;

	// Check if we have a valid $post object
	if ( $post && $post->post_type == 'portfolio' ) {

			// Add the class "active" to the menu item
			if ( $item->title == 'Portfolio' ) {
					$classes[] = 'active';
			}
	}

	return $classes;
}
add_filter( 'nav_menu_css_class', 'add_active_class_to_menu_item', 10, 2 );

function add_active_class_to_menu_item_blog( $classes, $item ) {
	global $post;

	// Check if we have a valid $post object
	if ( $post && $post->post_type == 'post' ) {

			// Add the class "active" to the menu item
			if ( $item->title == 'Blog' ) {
					$classes[] = 'current-menu-item';
			}
	}

	return $classes;
}
add_filter( 'nav_menu_css_class', 'add_active_class_to_menu_item_blog', 10, 2 );

function allow_json_upload( $existing_mimes ) {
	// Add JSON to the list of allowed mime types
	$existing_mimes['json'] = 'application/json';

	return $existing_mimes;
}
add_filter( 'upload_mimes', 'allow_json_upload' );

function enfantste_remove_version() {
	return '';
	}
add_filter('the_generator', 'enfantste_remove_version');

add_filter( 'excerpt_length', 'understrap_custom_excerpt_length', 999 );

function understrap_custom_excerpt_length( $length ) {
	return 15;
}

add_filter( 'excerpt_more', 'understrap_custom_excerpt_more' );

if ( ! function_exists( 'understrap_custom_excerpt_more' ) ) {
	function understrap_custom_excerpt_more( $more ) {
		if ( ! is_admin() ) {
			$more = ' ...'; // you can change this if you want a different ellipsis or end character
		}
		return $more;
	}
}

add_filter( 'wp_trim_excerpt', 'understrap_all_excerpts_get_more_link' );

if ( ! function_exists( 'understrap_all_excerpts_get_more_link' ) ) {
	function understrap_all_excerpts_get_more_link( $post_excerpt ) {
		if ( is_admin() || ! get_the_ID() ) {
			return $post_excerpt;
		}

		$permalink = esc_url( get_permalink( (int) get_the_ID() ) );

		return $post_excerpt . '<p><a class="btn icon blue arrow-right" href="' . $permalink . '">' . __(
			'Weiterlesen',
			'understrap'
		) . '<span class="screen-reader-text"> from ' . get_the_title( get_the_ID() ) . '</span></a></p>';
	}
}


function exclude_category_from_post_nav( $where ) {
	global $post, $wpdb;

	// Replace '123' with the ID of the 'not-in-listing' category
	$excluded_category_id = 36;

	if ( $post->post_type == 'post' ) {
			$where .= $wpdb->prepare( " AND ID NOT IN (SELECT object_id FROM $wpdb->term_relationships WHERE term_taxonomy_id = %d)", $excluded_category_id );
	}

	return $where;
}
add_filter( 'get_next_post_where', 'exclude_category_from_post_nav' );
add_filter( 'get_previous_post_where', 'exclude_category_from_post_nav' );


if ( ! function_exists( 'understrap_post_nav' ) ) {
	/**
	 * Display navigation to next/previous post when applicable.
	 *
	 * @global WP_Post|null $post The current post.
	 */
	function understrap_post_nav() {
		global $post;
		if ( ! $post ) {
			return;
		}

		// Don't print empty markup if there's nowhere to navigate.
		$previous = ( is_attachment() ) ? get_post( $post->post_parent ) : get_adjacent_post( false, '', true );
		$next     = get_adjacent_post( false, '', false );
		if ( ! $next && ! $previous ) {
			return;
		}
		?>
		<nav class="container-fluid navigation post-navigation d-none d-md-block">
			<h2 class="screen-reader-text"><?php esc_html_e( 'Post navigation', 'understrap' ); ?></h2>
			<div class="d-flex nav-links justify-content-between">
				<?php
				if ( get_previous_post_link() ) {
					previous_post_link( '<span class="nav-previous">%link</span>', _x( '&nbsp;%title', 'Previous post link', 'understrap' ) );
				}
				if ( get_next_post_link() ) {
					next_post_link( '<span class="nav-next">%link</span>', _x( '%title&nbsp;', 'Next post link', 'understrap' ) );
				}
				?>
			</div>
		</nav>
		<nav class="container-fluid navigation post-navigation d-md-none">
			<h2 class="screen-reader-text"><?php esc_html_e( 'Post navigation', 'understrap' ); ?></h2>
			<div class="d-flex nav-links justify-content-between">
				<?php
				if ( get_previous_post_link() ) {
					previous_post_link( '<span class="nav-previous">%link</span>', _x( '', 'Previous post link', 'understrap' ) );
				}
				if ( get_next_post_link() ) {
					next_post_link( '<span class="nav-next">%link</span>', _x( '', 'Next post link', 'understrap' ) );
				}
				?>
			</div>
		</nav>
		<?php
	}
}

// 1. Add the new column to the posts list
function featured_image_column_head($defaults) {
	$defaults['featured_image'] = 'Featured Image';  // 'Featured Image' is the column title, you can change this as per your needs
	return $defaults;
}
add_filter('manage_posts_columns', 'featured_image_column_head');

// 2. Populate the new column with data
function featured_image_column_content($column_name, $post_id) {
	if ($column_name == 'featured_image') {
			$post_featured_image = get_the_post_thumbnail($post_id, 'thumbnail');
			if ($post_featured_image) {
					echo 'Yes';  // Display 'Yes' if the post has a featured image
			} else {
					echo 'No';   // Display 'No' if the post doesn't have a featured image
			}
	}
}
add_action('manage_posts_custom_column', 'featured_image_column_content', 10, 2);

// 3. (Optional) Make the column sortable
function featured_image_column_sortable($columns) {
	$columns['featured_image'] = 'Featured Image';
	return $columns;
}
add_filter('manage_edit-post_sortable_columns', 'featured_image_column_sortable');
