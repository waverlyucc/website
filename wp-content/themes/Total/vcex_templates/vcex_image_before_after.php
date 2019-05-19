<?php
/**
 * Visual Composer Image Before/After
 *
 * @package Total WordPress Theme
 * @subpackage VC Templates
 * @version 4.8
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Helps speed up rendering in backend of VC
if ( is_admin() && ! wp_doing_ajax() ) {
	return;
}

// Get and extract shortcode attributes
$atts = vc_map_get_attributes( 'vcex_image_ba', $atts );

// Output var
$output = '';

// Primary and secondary imags required
if ( empty( $atts['before_img'] ) || empty( $atts['after_img'] ) ) {
	return;
}

// Load scripts
self::enqueue_scripts();

$wrap_attrs = array(
	'class' => 'vcex-image-ba-wrap',
);

if ( $css = vc_shortcode_custom_css_class( $atts['css'] ) ) {
	$wrap_attrs['class'] .= ' ' . $css;
}

if ( $atts['width'] ) {
	$wrap_attrs['style'] = vcex_inline_style( array(
		'width' => $atts['width'],
	), false );
}

if ( $atts['align'] ) {
	$wrap_attrs['class'] .= ' align' . $atts['align'];
}

$output .= '<div ' . wpex_parse_attrs( $wrap_attrs ) . '>';

	$figure_classes = array( 'vcex-module', 'vcex-image-ba', 'twentytwenty-container' );
	if ( $atts['el_class'] ) {
		$figure_classes[] = vcex_get_extra_class( $atts['el_class'] );
	}
	if ( $atts['css_animation'] && 'none' != $atts['css_animation'] ) {
		$figure_classes[] = vcex_get_css_animation( $atts['css_animation'] );
	}
	$figure_classes = implode( ' ', $figure_classes );
	$figure_classes = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, $figure_classes, 'vcex_image_swap', $atts );

	$data = htmlentities( json_encode( array(
		'orientation'        => $atts['orientation'],
		'default_offset_pct' => ! empty( $atts['default_offset_pct'] ) ? $atts['default_offset_pct'] : '0.5',
		'no_overlay'         => ( 'false' == $atts['overlay'] ) ? true : null,
		'before_label'       => ! empty( $atts['before_label'] ) ? esc_attr( $atts['before_label'] ) : esc_attr__( 'Before', 'total' ),
		'after_label'        => ! empty( $atts['after_label'] ) ? esc_attr( $atts['after_label'] ) : esc_attr__( 'After', 'total' ),
	) ) );

	$figure_attrs = array(
		'class'        => esc_attr( $figure_classes ),
		'data-options' => $data,
	);

	$output .= '<figure ' . wpex_parse_attrs( $figure_attrs ) . '">';

		// Before image
		$output .= wpex_get_post_thumbnail( array(
			'attachment' => $atts['before_img'],
			'size'       => $atts['img_size'],
			'crop'       => $atts['img_crop'],
			'width'      => $atts['img_width'],
			'height'     => $atts['img_height'],
			'class'      => 'vcex-before',
		) );

		// After image
		$output .= wpex_get_post_thumbnail( array(
			'attachment' => $atts['after_img'],
			'size'       => $atts['img_size'],
			'crop'       => $atts['img_crop'],
			'width'      => $atts['img_width'],
			'height'     => $atts['img_height'],
			'class'      => 'vcex-after',
		) );

	$output .= '</figure>';

$output .= '</div>';

echo $output;