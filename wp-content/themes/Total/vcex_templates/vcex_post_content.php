<?php
/**
 * Visual Composer Post Content
 *
 * @package Total WordPress Theme
 * @subpackage VC Templates
 * @version 4.5.4.2
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Helps speed up rendering in backend of VC
if ( is_admin() && ! wp_doing_ajax() ) {
	return;
}

// Required VC functions
if ( ! function_exists( 'vc_map_get_attributes' ) ) {
	vcex_function_needed_notice();
	return;
}

// Get post content
$post_content = get_the_content( wpex_get_current_post_id() );

// Return if the current post has this shortcode inside it to prevent infinite loop
if ( ! $post_content || strpos( $post_content, 'vcex_post_content' ) !== false ) {
	return;
}

// Get shortcode attributes based on vc_lean_map => This makes sure no attributes are empty
$atts = vc_map_get_attributes( 'vcex_post_content', $atts );

// Wrap inline style
$wrap_inline_style = array(
	'font_size'   => $atts['font_size'],
	'font_family' => $atts['font_family'],
);

// Load custom Google font if needed
wpex_enqueue_google_font( $atts['font_family'] );

// Define wrap attributes
$wrap_attrs = array(
	'class' => 'vcex-post-content vcex-clr',
	'style' => vcex_inline_style( $wrap_inline_style, false ),
);

// Add CSS class
if ( $atts['css'] ) {
	$wrap_attrs['class'] .= ' ' . vc_shortcode_custom_css_class( $atts['css'] );
}

// Get responsive data
if ( $responsive_data = vcex_get_module_responsive_data( $atts ) ) {
	$wrap_attrs['data-wpex-rcss'] = $responsive_data;
}

// Output post content
echo wpex_parse_html( 'div', $wrap_attrs, apply_filters( 'the_content', $post_content ) );