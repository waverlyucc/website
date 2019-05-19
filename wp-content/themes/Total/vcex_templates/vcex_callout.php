<?php
/**
 * Visual Composer Callout
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
extract( vcex_vc_map_get_attributes( 'vcex_callout', $atts ) );

// Add Classes
$wrap_classes = array( 'vcex-module', 'vcex-callout', 'clr' );
if ( $button_url ) {
	$wrap_classes[] = 'with-button';
}
if ( $visibility ) {
	$wrap_classes[] = $visibility;
}
if ( $css_animation && 'none' != $css_animation ) {
	$wrap_classes[] = vcex_get_css_animation( $css_animation );
}
if ( $classes ) {
	$wrap_classes[] = vcex_get_extra_class( $classes );
}
$wrap_classes[] = vc_shortcode_custom_css_class( $css );
$wrap_classes   = implode( ' ', $wrap_classes );
$wrap_classes   = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, $wrap_classes, 'vcex_callout', $atts );

$output = '';

$output .= '<div class="' . $wrap_classes . '"' . vcex_get_unique_id( $unique_id ) . '>';

	// Display content
	if ( $content ) {

		$content_inline_style = vcex_inline_style( array(
			'color'          => $content_color,
			'font_size'      => $content_font_size,
			'letter_spacing' => $content_letter_spacing,
			'font_family'    => $content_font_family,
			'width'          => $content_width,
		) );

		if ( $content_font_family ) {
			wpex_enqueue_google_font( $content_font_family );
		}

		$output .= '<div class="vcex-callout-caption clr"' . $content_inline_style . '>';

			$output .= wpex_the_content( $content );

		$output .= '</div>';

	}

	// Display button
	if ( $button_url && $button_text ) {

		$button_inline_style = vcex_inline_style( array(
			'color'          => $button_custom_color,
			'background'     => $button_custom_background,
			'padding'        => $button_padding,
			'border_radius'  => $button_border_radius,
			'font_size'      => $button_font_size,
			'letter_spacing' => $button_letter_spacing,
			'font_family'    => $button_font_family,
		), false );

		if ( $button_font_family ) {
			wpex_enqueue_google_font( $button_font_family );
		}

		$output .= '<div class="vcex-callout-button"' . vcex_inline_style( array( 'width' => $button_width, 'text_align' => $button_align ) ) . '>';

			$button_attrs = array(
				'href'   => esc_url( do_shortcode( $button_url ) ),
				'title'  => esc_attr( do_shortcode( $button_text ) ),
				'class'  => wpex_get_button_classes( $button_style, $button_color ),
				'target' => $button_target,
				'rel'    => $button_rel,
				'style'  => $button_inline_style,
			);

			if ( 'local' == $button_target ) {
				$button_attrs['class'] .= ' local-scroll-link';
			}

			if ( 'true' == $button_full_width ) {
				$button_attrs['class'] .= ' full-width';
			}

			$button_hover_data = array();
			if ( $button_custom_hover_background ) {
				$button_hover_data['background'] = esc_attr( $button_custom_hover_background );
			}
			if ( $button_custom_hover_color ) {
				$button_hover_data['color'] = esc_attr( $button_custom_hover_color );
			}
			if ( $button_hover_data ) {
				$button_attrs['data-wpex-hover'] = json_encode( $button_hover_data );
			}

			$output .= '<a ' . wpex_parse_attrs( $button_attrs ) . '>';

				$icon_left  = vcex_get_icon_class( $atts, 'button_icon_left' );
				$icon_right = vcex_get_icon_class( $atts, 'button_icon_right' );

				if ( $icon_left || $icon_right ) {
					vcex_enqueue_icon_font( $icon_type );
				}

				if ( $icon_left ) {
					$output .= '<span class="theme-button-icon-left ' . $icon_left . '"></span>';
				}

				$output .= wp_kses_post( do_shortcode( $button_text ) );

				if ( $icon_right ) {
					$output .= '<span class="theme-button-icon-right ' . $icon_right . '"></span>';
				}

			$output .= '</a>';

		$output .= '</div>';

	}

$output .= '</div>';

echo $output;