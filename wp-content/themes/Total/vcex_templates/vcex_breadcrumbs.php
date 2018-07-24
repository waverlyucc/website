<?php
/**
 * Visual Composer Breadcrumbs
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

// Return if crumbs class doesn't exist
if ( ! class_exists( 'WPEX_Breadcrumbs' ) ) {
	return;
}

// Required VC functions
if ( ! function_exists( 'vc_map_get_attributes' ) ) {
	vcex_function_needed_notice();
	return;
}

// Get and extract shortcode attributes
$atts = vc_map_get_attributes( 'vcex_breadcrumbs', $atts );

// Custom breadcrumbs
if ( $custom_breadcrumbs = apply_filters( 'wpex_custom_breadcrumbs', null ) ) {
	echo wp_kses_post( $custom_breadcrumbs );
	return;
}

// Generate breadcrumbs (stores trail in $ouput var)
$crumbs = new WPEX_Breadcrumbs();
$crumbs = $crumbs->generate_crumbs();

// Get inline styles
$inline_style = vcex_inline_style( array(
	'color'       => $atts['color'],
	'font_size'   => $atts['font_size'],
	'font_family' => $atts['font_family'],
	'text_align'  => $atts['align'],
), false );

// Load custom font
if ( $atts['font_family'] ) {
	wpex_enqueue_google_font( $atts['font_family'] );
}

// Define wrapper attributes
$wrap_attrs = array(
	'class' => 'vcex-breadcrumbs',
	'style' => $inline_style
);

// Extra classname
if ( $atts['el_class'] ) {
	$wrap_attrs['class'] .= ' ' . vcex_get_extra_class( $atts['el_class'] );
}

// Visibility
if ( $atts['visibility'] ) {
	$wrap_attrs['class'] .= ' ' . $atts['visibility'];
}

// Responsive settings
if ( $responsive_data = vcex_get_module_responsive_data( $atts['font_size'], 'font_size' ) ) {
	$wrap_attrs['data-wpex-rcss'] = $responsive_data;
}

// Echo breadcrumbs
echo '<nav ' . wpex_parse_attrs( $wrap_attrs ) . '>' . $crumbs . '</nav>';