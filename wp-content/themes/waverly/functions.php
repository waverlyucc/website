<?php
/**
 * Child theme functions
 *
 * When using a child theme (see http://codex.wordpress.org/Theme_Development
 * and http://codex.wordpress.org/Child_Themes), you can override certain
 * functions (those wrapped in a function_exists() call) by defining them first
 * in your child theme's functions.php file. The child theme's functions.php
 * file is included before the parent theme's file, so the child theme
 * functions would be used.
 *
 * Text Domain: wpex
 * @link http://codex.wordpress.org/Plugin_API
 *
 */

/**
 * Load the parent style.css file
 *
 * @link http://codex.wordpress.org/Child_Themes
 */
function total_child_enqueue_parent_theme_style() {
	wp_enqueue_style( 'parent-style', get_template_directory_uri().'/style.css', array(), WPEX_THEME_VERSION );
}
add_action( 'wp_enqueue_scripts', 'total_child_enqueue_parent_theme_style' );

/**
 * Automatically display featured image at the top of pages (not just blog
 * entries).
 *
 * @link https://wpexplorer-themes.com/total/snippets/auto-page-featured-image/
 */

function my_page_featured_image() {
	if ( is_page() && has_post_thumbnail() ) {
		echo '<div class="my-page-featured-image wp-caption aligncenter clr">'. get_the_post_thumbnail() .'</div>';
	}
}
add_action( 'wpex_hook_content_top', 'my_page_featured_image', 10 );


