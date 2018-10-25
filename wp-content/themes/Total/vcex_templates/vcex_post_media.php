<?php
/**
 * Visual Composer Post Media
 *
 * @package Total WordPress Theme
 * @subpackage VC Templates
 * @version 4.6
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

// Get and extract shortcode attributes
extract( vc_map_get_attributes( 'vcex_post_media', $atts ) );

$post_id = wpex_get_current_post_id();

$thumbnail_args = array(
	'attachment' => get_post_thumbnail_id( $post_id ),
	'size'       => $img_size,
	'crop'       => $img_crop,
	'width'      => $img_width,
	'height'     => $img_height,
);

$wrap_class = 'vcex-post-media clr';

if ( $css ) {
	$wrap_class .= ' ' . vc_shortcode_custom_css_class( $css );
}
if ( $css_animation && 'none' != $css_animation ) {
	$wrap_class .= ' ' . vcex_get_css_animation( $css_animation );
}
if ( $classes ) {
	$wrap_class .= ' ' . vcex_get_extra_class( $classes );
}
if ( $visibility ) {
	$wrap_class .= ' ' . $visibility;
}

$output = '<div class="' . esc_attr( $wrap_class ) . '">';

	$output .= wpex_get_post_media( $post_id, array(
		'thumbnail_args' => $thumbnail_args,
		'lightbox'       => ( $lightbox == true ) ? true : false,
	) );

$output .= '</div>';

echo $output;