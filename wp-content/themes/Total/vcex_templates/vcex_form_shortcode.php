<?php
/**
 * Visual Composer Form Shortcode
 *
 * Allows you to enter any form shortcode and apply custom styles
 *
 * @package Total WordPress Theme
 * @subpackage VC Templates
 * @version 4.5.1
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
if ( ! function_exists( 'vc_map_get_attributes' ) || ! function_exists( 'vc_shortcode_custom_css_class' ) ) {
	vcex_function_needed_notice();
	return;
}

// Get and extract shortcode attributes
$atts = vc_map_get_attributes( 'vcex_form_shortcode', $atts );

if ( ! empty( $atts['cf7_id'] ) ) {
	$content = '[contact-form-7 id="' . intval( $atts['cf7_id'] ) . '"]';
}

// Return if no content (shortcode needed)
if ( empty( $content ) ) {
	return;
}

// Add classes
$classes = 'vcex-module vcex-form-shortcode wpex-form';
if ( $atts['style'] ) {
	if ( 'white' == $atts['style'] ) {
		$classes .= ' light-form';
	} else {
		$classes .= ' wpex-form-'.$atts['style'];
	}
}
if ( 'true' == $atts['full_width'] ) {
	$classes .= ' full-width-input';
}
if ( $atts['css'] ) {
	$classes .= ' '. vc_shortcode_custom_css_class( $atts['css'] );
}
if ( $atts['css_animation'] && 'none' != $atts['css_animation'] ) {
	$classes .= ' '. vcex_get_css_animation( $atts['css_animation'] );
}
$classes = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, $classes, 'vcex_form_shortcode', $atts );

// Inline CSS
$inline_style = vcex_inline_style( array(
	'font_size' => $atts['font_size'],
	'width'     => $atts['width'],
) );

// Output
echo '<div class="'. esc_attr( $classes ) .'"'. $inline_style .'>'. do_shortcode( $content ) .'</div>';