<?php
/**
 * Visual Composer List Item
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
$atts = vcex_vc_map_get_attributes( 'vcex_list_item', $atts );
extract( $atts );

// Sanitize content/text
if ( 'custom_field' == $text_source ) {
	$content = $text_custom_field ? get_post_meta( wpex_get_dynamic_post_id(), $text_custom_field, true ) : '';
} elseif( 'callback_function' == $text_source ) {
	$content = ( $text_callback_function && function_exists( $text_callback_function ) ) ? call_user_func( $text_callback_function ) : '';
}

// Content is required
if ( empty( $content ) ) {
	return;
}

// Output var
$output = '';

// Load custom font
if ( $font_family ) {
	wpex_enqueue_google_font( $font_family );
}

// Get link
$url = isset( $atts['url'] ) ? $atts['url'] : '';
if ( $link ) {
	$link_url_temp  = $link;
	$link_url       = vcex_get_link_data( 'url', $link_url_temp );
	if ( $link_url ) {
		$url         = $link_url;
		$link_title  = isset( $atts['link_title'] ) ? $atts['link_title'] : '';
		$link_target = isset( $atts['link_target'] ) ? $atts['link_target'] : '';
	}
}

// Classes & data
$wrap_attrs = array(
	'id' => vcex_get_unique_id( $unique_id ),
);

// Wrap classes
$wrap_class = array( 'vcex-module', 'vcex-list_item', 'clr' );
if ( $classes ) {
	$wrap_class[] = vcex_get_extra_class( $classes );
}
if ( $css_animation && 'none' != $css_animation ) {
	$wrap_class[] = vcex_get_css_animation( $css_animation );
}
if ( $visibility ) {
	$wrap_class[] = $visibility;
}
if ( $css ) {
	$wrap_class[] = vc_shortcode_custom_css_class( $css );
}
if ( $text_align ) {
	$wrap_class[] = 'text' . $text_align;
}

if ( 'true' == $responsive_font_size ) {

	if ( $font_size && $min_font_size ) {

		// Convert em font size to pixels
		if ( strpos( $font_size, 'em' ) !== false ) {
			$font_size = str_replace( 'em', '', $font_size );
			$font_size = $font_size * wpex_get_body_font_size();
		}

		// Convert em min-font size to pixels
		if ( strpos( $min_font_size, 'em' ) !== false ) {
			$min_font_size = str_replace( 'em', '', $min_font_size );
			$min_font_size = $min_font_size * wpex_get_body_font_size();
		}

		// Add inline Jsv
		vcex_inline_js( 'responsive_font_size' );

		// Add wrap classes and data
		$wrap_class[] = 'wpex-responsive-txt';
		$wrap_attrs['data-max-font-size'] = absint( $font_size );
		$wrap_attrs['data-min-font-size'] = absint( $min_font_size );

	}

} else {

	// Get responsive font-size
	if ( $responsive_data = vcex_get_module_responsive_data( $font_size, 'font_size' ) ) {
		$wrap_attrs['data-wpex-rcss'] = $responsive_data;
	}

}

// Add wrapper styles
$wrap_attrs['style'] = vcex_inline_style( array(
	'font_family' => $font_family,
	'font_size'   => $font_size,
	'color'       => $font_color,
	'font_weight' => $font_weight,
	'font_style'  => $font_style,
) );

// Apply filters
$wrap_attrs['class'] = esc_attr( apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, implode( ' ', $wrap_class ), 'vcex_list_item', $atts ) );

// Begin output
$output .= '<div ' . wpex_parse_attrs( $wrap_attrs ) . '>';

	if ( $url ) {

		$link_attrs = array(
			'href'   => esc_url( do_shortcode( $url ) ),
			'title'  => do_shortcode( vcex_get_link_data( 'title', $link_url_temp, $link_title ) ),
			'target' => vcex_get_link_data( 'target', $link_url_temp, $link_target ),
			'rel'    => vcex_get_link_data( 'rel', $link_url_temp ),
			'style'  => vcex_inline_style( array(
				'color' => $font_color,
			), false ),
		);

		$output .= '<a '. wpex_parse_attrs( $link_attrs ) . '>';

	}

	if ( $icon || $icon_alternative_classes ) {

		if ( ! $icon_height && $icon_size ) {
			$icon_height = $icon_size;
		}

		$style_args = array(
			'background'    => $icon_background,
			'width'         => $icon_width,
			'border_radius' => $icon_border_radius,
			'height'        => $icon_height,
			'line_height'   => wpex_sanitize_data( $icon_height, 'px' ),
			'font_size'     => $icon_size,
			'color'         => $color,
		);

		if ( is_rtl() ) {
			$style_args['margin_left'] = $margin_right;
		} else {
			$style_args['margin_right'] = $margin_right;
		}

		$icon_style = vcex_inline_style( $style_args );

		$output .= '<div class="vcex-icon-wrap"' . $icon_style . '>';

		if ( $icon_alternative_classes ) {

			$output .= '<span class="'. esc_attr( do_shortcode( $icon_alternative_classes ) ) .'"></span>';

		} else {

			// Enqueue needed icon font
			if ( $icon && 'fontawesome' != $icon_type ) {
				vcex_enqueue_icon_font( $icon_type );
			}

			$output .= '<span class="' . vcex_get_icon_class( $atts, 'icon' ) . '"></span>';

		}

		$output .= '</div>';

	}

	$output .= '<div class="vcex-content">' . do_shortcode( wp_kses_post( $content ) ) . '</div>';

	if ( $url ) {

		$output .= '</a>';

	}

$output .= '</div>';

echo $output;