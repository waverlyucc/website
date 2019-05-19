<?php
/**
 * Theme filters
 *
 * @package Total WordPress Theme
 * @subpackage Framework
 * @version 4.8.4
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Templatera widget
 *
 * @since 4.8.4
 * @see wp-includes/default-filters.php
 */

if ( function_exists( 'templatera_init' ) ) {
	add_filter( 'wpex_templatera_widget_content', 'wptexturize'                       );
	add_filter( 'wpex_templatera_widget_content', 'convert_smilies', 20               );
	add_filter( 'wpex_templatera_widget_content', 'convert_chars'                     );
	add_filter( 'wpex_templatera_widget_content', 'wpautop'                           );
	add_filter( 'wpex_templatera_widget_content', 'shortcode_unautop'                 );
	add_filter( 'wpex_templatera_widget_content', 'do_shortcode', 11                  ); // runs after wpautop and shortcode_unautop
	add_filter( 'wpex_templatera_widget_content', 'prepend_attachment'                );
	add_filter( 'wpex_templatera_widget_content', 'wp_make_content_images_responsive' );
}