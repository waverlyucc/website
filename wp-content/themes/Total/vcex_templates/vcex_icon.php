<?php
/**
 * Visual Composer Icon
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

// FALLBACK VARS => NEVER REMOVE !!!
$padding     = isset( $atts['padding'] ) ? $atts['padding'] : '';
$style       = isset( $atts['style'] ) ? $atts['style'] : '';
$link_title  = isset( $atts['link_title'] ) ? $atts['link_title'] : '';
$link_target = isset( $atts['link_target'] ) ? $atts['link_target'] : '';

// Get and extract shortcode attributes
$atts = vcex_vc_map_get_attributes( 'vcex_icon', $atts );
extract( $atts );

// Sanitize data & declare vars
$output = '';
$icon = vcex_get_icon_class( $atts, 'icon' );
$data_attributes = '';

// Enqueue needed icon font
if ( $icon && 'fontawesome' != $icon_type ) {
	vcex_enqueue_icon_font( $icon_type );
}

// Icon Classes 
$wrap_classes = array( 'vcex-module', 'vcex-icon', 'clr' );
if ( $style ) {
	$wrap_classes[] = 'vcex-icon-'. $style;
}
if ( $size ) {
	$wrap_classes[] = 'vcex-icon-'. $size;
}
if ( $float ) {
	$wrap_classes[] = 'vcex-icon-float-'. $float;
}
if ( $custom_size ) {
	$wrap_classes[] = 'custom-size';
}
if ( $background ) {
	$wrap_classes[] = 'has-bg';
}
if ( ! $background ) {
	$wrap_classes[] = 'remove-dimensions';
}
if ( $height || $width ) {
	$wrap_classes[] = 'remove-padding';
	$wrap_classes[] = 'remove-dimensions';
}
if ( $css_animation && 'none' != $css_animation ) {
	$wrap_classes[] = vcex_get_css_animation( $css_animation );
}
if ( $el_class ) {
	$wrap_classes[] = vcex_get_extra_class( $el_class );
}

// Apply core VC filter to classes
$wrap_classes = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, implode( ' ', $wrap_classes ), 'vcex_icon', $atts );

// Open link wrapper
$output .= '<div class="'. esc_attr( $wrap_classes ) .'"'. vcex_get_unique_id( $unique_id ) .'>';
	
	// Open custom link
	if ( $link_url ) {

		$link_data = vcex_build_link( $link_url );
		$link_url  = isset( $link_data['url'] ) ? $link_data['url'] : $link_url;
		$link_url  = esc_url( do_shortcode( $link_url ) );

		if ( $link_url ) {

			$link_attrs  = array(
				'href'  => $link_url,
				'class' => array( 'vcex-icon-link' ),
			);
			$link_attrs['title']  = isset( $link_data['title'] ) ? $link_data['title'] : '';
			$link_attrs['target'] = isset( $link_data['target'] ) ? $link_data['target'] : '';
			$link_attrs['rel']    = isset( $link_data['rel'] ) ? $link_data['rel'] : '';

			if ( 'true' == $link_local_scroll || 'local' == $link_target ) {
				unset( $link_attrs['target'] );
				unset( $link_attrs['rel'] );
				$link_attrs['class'][] = 'local-scroll-link';
			}

			$output .= '<a ' . wpex_parse_attrs( $link_attrs ) . '>';

		}

	}

	// Icon classes
	$icon_attrs = array(
		'class' => array( 'vcex-icon-wrap' ),
	);
	if ( $hover_animation ) {
		$icon_attrs['class'][] = wpex_hover_animation_class( $hover_animation );
		vcex_enque_style( 'hover-animations' );
	}

	// Icon hovers
	$hover_data = array();
	if ( $background_hover ) {
		$hover_data['background'] = esc_attr( $background_hover );
	}
	if ( $color_hover ) {
		$hover_data['color'] = esc_attr( $color_hover );
	}
	if ( $hover_data ) {
		$icon_attrs['data-wpex-hover'] = json_encode( $hover_data );
	}

	// Add Style
	$icon_attrs['style'] = vcex_inline_style( array(
		'font_size'        => $custom_size,
		'color'            => $color,
		'padding'          => $padding,
		'background_color' => $background,
		'border_radius'    => $border_radius,
		'height'           => $height,
		'line_height'      => wpex_sanitize_data( $height, 'px' ),
		'width'            => $width,
		'border'           => $border,
	), false );

	// Open Icon div
	$output .= '<div ' . wpex_parse_attrs( $icon_attrs ) . '>';

		// Display alternative icon
		if ( $icon_alternative_classes ) {

			$output .= '<span class="'. esc_attr( do_shortcode( $icon_alternative_classes ) ) .'"></span>';

		// Display theme supported icon
		} else {

			$output .= '<span class="'. esc_attr( $icon ) .'"></span>';

		}

	// Close Icon Div
	$output .= '</div>';

	// Close link tag
	if ( $link_url ) {

		$output .= '</a>';
		
	}

$output .= '</div>';

echo $output;