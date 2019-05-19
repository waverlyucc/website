<?php
/**
 * Functions that run on widgets init
 *
 * @package Total WordPress Theme
 * @subpackage Framework
 * @version 4.8
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register custom widgets
 *
 * @since 4.0
 */
function wpex_register_custom_widgets() {

	// Include Widget Builder Class
	require_once WPEX_FRAMEWORK_DIR . 'classes/WidgetBuilder.php';

	// Get array of custom widgets
	$widgets = wpex_custom_widgets_list();

	// Loop through array and register the custom widgets
	if ( $widgets && is_array( $widgets ) ) {
		foreach ( $widgets as $widget ) {
			$file = WPEX_ClASSES_DIR . 'Widgets/' . $widget . '.php';
			if ( file_exists ( $file ) ) {
				require_once $file;
			}
		}
	}

}
add_action( 'widgets_init', 'wpex_register_custom_widgets' );

/**
 * Register sidebar widget areas
 *
 * @since 4.0
 */
function wpex_register_sidebar_widget_areas() {

	// Define sidebars array
	$sidebars = array(
		'sidebar' => __( 'Main Sidebar', 'total' ),
	);

	// Pages Sidebar
	if ( wpex_get_mod( 'pages_custom_sidebar', true ) ) {
		$sidebars['pages_sidebar'] = __( 'Pages Sidebar', 'total' );
	}

	// Blog Sidebar
	if ( wpex_get_mod( 'blog_custom_sidebar', false ) ) {
		$sidebars['blog_sidebar'] = __( 'Blog Sidebar', 'total' );
	}

	// Search Results Sidebar
	if ( wpex_get_mod( 'search_custom_sidebar', true ) ) {
		$sidebars['search_sidebar'] = __( 'Search Results Sidebar', 'total' );
	}

	// WooCommerce
	if ( WPEX_WOOCOMMERCE_ACTIVE && wpex_get_mod( 'woo_custom_sidebar', true ) ) {
		$sidebars['woo_sidebar'] = __( 'WooCommerce Sidebar', 'total' );
	}

	// Apply filters - makes it easier to register new sidebars
	$sidebars = apply_filters( 'wpex_register_sidebars_array', $sidebars );

	// If there are no sidebars then return
	if ( ! $sidebars ) {
		return;
	}

	// Sidebar tags
	$tag = wpex_get_mod( 'sidebar_headings' );
	$tag = $tag ? $tag : 'div';

	// Loop through sidebars and register them
	foreach ( $sidebars as $k => $v ) {

		$args = array(
			'id'            => $k,
			'before_widget' => '<div id="%1$s" class="sidebar-box widget %2$s clr">',
			'after_widget'  => '</div>',
			'before_title'  => '<' . $tag . ' class="widget-title">',
			'after_title'   => '</' . $tag . '>',
		);

		if ( is_array( $v ) ) {
			$args = wp_parse_args( $v, $args );
		} else {
			$args['name'] = $v;
		}

		register_sidebar( $args );

	}

}
add_action( 'widgets_init', 'wpex_register_sidebar_widget_areas' );

/**
 * Register footer widget areas
 *
 * @since 4.0
 */
function wpex_register_footer_widget_areas() {

	if ( wpex_has_custom_footer() ) {
		$has_footer_widgets = wpex_get_mod( 'footer_builder_footer_widgets', false );
	} else {
		$has_footer_widgets = wpex_get_mod( 'footer_widgets', true );
	}

	// Check if footer widgets are enabled
	// @todo rename this filter? Doesn't really make sense...should be "wpex_register_footer_widget_areas"
	$has_footer_widgets = apply_filters( 'wpex_register_footer_sidebars', $has_footer_widgets );

	// Return if disabled
	if ( ! $has_footer_widgets ) {
		return;
	}

	// Footer tag
	$tag = wpex_get_mod( 'footer_headings' );
	$tag = $tag ? $tag : 'div';

	// Footer widget columns
	$footer_columns = wpex_get_mod( 'footer_widgets_columns', '4' );

	// Footer 1
	register_sidebar( array(
		'name'          => __( 'Footer Column 1', 'total' ),
		'id'            => 'footer_one',
		'before_widget' => '<div id="%1$s" class="footer-widget widget %2$s clr">',
		'after_widget'  => '</div>',
		'before_title'  => '<' . $tag . ' class="widget-title">',
		'after_title'   => '</' . $tag . '>',
	) );

	// Footer 2
	if ( $footer_columns > '1' ) {

		register_sidebar( array(
			'name'          => __( 'Footer Column 2', 'total' ),
			'id'            => 'footer_two',
			'before_widget' => '<div id="%1$s" class="footer-widget widget %2$s clr">',
			'after_widget'  => '</div>',
			'before_title'  => '<' . $tag . ' class="widget-title">',
			'after_title'   => '</' . $tag . '>'
		) );

	}

	// Footer 3
	if ( $footer_columns > '2' ) {

		register_sidebar( array(
			'name'          => __( 'Footer Column 3', 'total' ),
			'id'            => 'footer_three',
			'before_widget' => '<div id="%1$s" class="footer-widget widget %2$s clr">',
			'after_widget'  => '</div>',
			'before_title'  => '<' . $tag . ' class="widget-title">',
			'after_title'   => '</' . $tag . '>',
		) );

	}

	// Footer 4
	if ( $footer_columns > '3' ) {

		register_sidebar( array(
			'name'          => __( 'Footer Column 4', 'total' ),
			'id'            => 'footer_four',
			'before_widget' => '<div id="%1$s" class="footer-widget widget %2$s clr">',
			'after_widget'  => '</div>',
			'before_title'  => '<' . $tag . ' class="widget-title">',
			'after_title'   => '</' . $tag . '>',
		) );

	}

	// Footer 5
	if ( $footer_columns > '4' ) {

		register_sidebar( array(
			'name'          => __( 'Footer Column 5', 'total' ),
			'id'            => 'footer_five',
			'before_widget' => '<div id="%1$s" class="footer-widget widget %2$s clr">',
			'after_widget'  => '</div>',
			'before_title'  => '<' . $tag . ' class="widget-title">',
			'after_title'   => '</' . $tag . '>',
		) );

	}

}
add_action( 'widgets_init', 'wpex_register_footer_widget_areas', 40 );