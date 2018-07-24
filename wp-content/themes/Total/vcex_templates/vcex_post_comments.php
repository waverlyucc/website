<?php
/**
 * Visual Composer Comments
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

// Required functions
if ( ! function_exists( 'vc_map_get_attributes' ) || ! function_exists( 'comments_template' ) ) {
	vcex_function_needed_notice();
	return;
}

// Get and extract shortcode attributes
$atts = vc_map_get_attributes( 'vcex_post_comments', $atts );

// Define wrapper attributes
$wrap_attrs = array(
	'class' => 'vcex-comments clr',
);

// Extra classname
if ( $atts['el_class'] ) {
	$wrap_attrs['class'] .= ' ' . vcex_get_extra_class( $atts['el_class'] );
}

// Visibility
if ( $atts['visibility'] ) {
	$wrap_attrs['class'] .= ' ' . $atts['visibility'];
}

// Output comments
echo '<div ' . wpex_parse_attrs( $wrap_attrs ) . '>';

	comments_template();

echo '</div>';