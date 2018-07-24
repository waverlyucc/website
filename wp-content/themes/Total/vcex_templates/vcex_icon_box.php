<?php
/**
 * Visual Composer Icon Box
 *
 * @package Total WordPress Theme
 * @subpackage VC Templates
 * @version 4.6.5
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

// FALLBACK VARS => NEVER REMOVE!!
$padding          = isset( $atts['padding'] ) ? $atts['padding'] : '';
$background       = isset( $atts['background'] ) ? $atts['background'] : '';
$background_image = isset( $atts['background_image'] ) ? $atts['background_image'] : '';
$margin_bottom    = isset( $atts['margin_bottom'] ) ? $atts['margin_bottom'] : '';
$border_color     = isset( $atts['border_color'] ) ? $atts['border_color'] : '';

// Get and extract shortcode attributes
$atts = vcex_vc_map_get_attributes( 'vcex_icon_box', $atts );
extract( $atts );

// Sanitize data & declare main vars
$output                   = '';
$url                      = do_shortcode( $url );
$outer_wrap_class         = array( 'vcex-icon-box-css-wrap' );
$clickable_boxes          = array( 'four', 'five', 'six' );
$url_wrap                 = in_array( $style, $clickable_boxes ) ? 'true' : $url_wrap;
$image                    = wpex_attachment_exists( $image ) ? $image : '';
$icon                     = ( $image || $icon_alternative_classes ) ? '' : vcex_get_icon_class( $atts, 'icon' );
$heading                  = $heading ? do_shortcode( $heading ) : '';

// Define main wrap attributes
$wrap_attrs = array(
	'id'    => vcex_get_unique_id( $unique_id ),
	'class' => array( 'vcex-module', 'vcex-icon-box', 'clr' ),
);

// Check if we should have an outer wrap
$has_outer_wrap = ( $width || ( $css && in_array( $style, array( 'one', 'seven' ) ) ) ) ? true : false;

// Add extra wrap classes based on settings
if ( $style ) {
	$wrap_attrs['class'][] = 'vcex-icon-box-' . $style;
}
if ( ! $icon && ! $image && ! $icon_alternative_classes ) {
	$wrap_attrs['class'][] = 'vcex-icon-box-wo-icon';
}
if ( $url && 'true' == $url_wrap ) {
	$wrap_attrs['class'][] = 'vcex-icon-box-link-wrap';
}
if ( $alignment ) {
	$wrap_attrs['class'][] = 'text' . $alignment;
}
if ( $icon_background ) {
	$wrap_attrs['class'][] = 'vcex-icon-box-w-bg';
}
if ( 'true' == $hover_white_text ) {
	$wrap_attrs['class']['wpex-hover-white-text'] = 'wpex-hover-white-text';
	$outer_wrap_class[] = 'wpex-hover-white-text';
}
if ( $hover_animation ) {
	if ( $css && in_array( $style, array( 'one', 'seven' ) ) ) {
		$outer_wrap_class[] = wpex_hover_animation_class( $hover_animation );
	} else {
		$wrap_attrs['class'][] = wpex_hover_animation_class( $hover_animation );
	}
	vcex_enque_style( 'hover-animations' );
}
if ( ! $hover_animation && $hover_background ) {
	$wrap_attrs['class'][] = 'animate-all-hover';
	$outer_wrap_class[] = 'animate-bg-hover';
}
if ( $css_animation ) {
	if ( $css && in_array( $style, array( 'one', 'seven' ) ) ) {
		$outer_wrap_class[] = vcex_get_css_animation( $css_animation );
	} else {
		$wrap_attrs['class'][] = vcex_get_css_animation( $css_animation );
	}
}
if ( $classes ) {
	$wrap_attrs['class'][] = vcex_get_extra_class( $classes );
}
if ( $visibility ) {
	$wrap_attrs['class'][] = $visibility;
}
if ( $css ) {
	$css_class = vc_shortcode_custom_css_class( $css );
	if ( in_array( $style, array( 'one', 'seven' ) ) ) {
		$outer_wrap_class[] = $css_class;
	} else {
		$wrap_attrs['class'][] = $css_class;
	}
}

// Wrap Style
$wrap_style = array();
if ( $border_radius && ! $has_outer_wrap ) {
	$wrap_style['border_radius'] = $border_radius;
}
if ( 'six' == $style && $icon_color ) {
	$wrap_style['color'] = $icon_color;
}
if ( 'one' == $style && $container_left_padding ) {
	$wrap_style['padding_left'] = $container_left_padding;
}
if ( 'seven' == $style && $container_right_padding ) {
	$wrap_style['padding_right'] = $container_right_padding;
}

// Fallback styles if $css is empty
if ( ! $css ) {
	if ( $padding ) {
		$wrap_style['padding'] = $padding;
	}
	if ( 'four' == $style && $border_color ) {
		$wrap_style['border_color'] = $border_color;
	}
	if ( 'six' == $style && $icon_background && '' === $background ) {
		$wrap_style['background_color'] = $icon_background;
	}
	if ( $background && in_array( $style, $clickable_boxes ) ) {
		$wrap_style['background_color'] = $background;
	}
	if ( $background_image && in_array( $style, $clickable_boxes ) ) {
		$background_image = wp_get_attachment_url( $background_image );
		$wrap_style['background_image'] = $background_image;
		$wrap_attrs['class'][] = 'vcex-background-' . $background_image_style;
	}
	if ( $margin_bottom ) {
		$wrap_style['margin_bottom'] = $margin_bottom;
	}
}

// Hover Background
if ( $hover_background ) {
	$wrap_attrs['data-wpex-hover'] = json_encode( array( 'background' => $hover_background ) );
}

// Link data
if ( $url ) {
	$url_classes = array();
	if ( 'true' != $url_wrap ) {
		$url_classes[] = 'vcex-icon-box-link';
	}
	if ( 'local' == $url_target ) {
		$wrap_attrs['class'][] = ' local-scroll-link';
		$url_classes[] = 'local-scroll-link';
	}
}

// Open outer wrap
if ( $has_outer_wrap ) {

	$outer_wrap_attrs = array(
		'class' => $outer_wrap_class,
		'style' => vcex_inline_style( array(
			'width'         => $width,
			'border_radius' => $border_radius,
		), false ),
	);

	if ( in_array( $style, array( 'one', 'seven' ) ) ) {

		unset( $wrap_attrs['data-wpex-hover'] );
		unset( $wrap_attrs['class']['wpex-hover-white-text'] );

		if ( $hover_background ) {
			$outer_wrap_attrs['data-wpex-hover'] = json_encode( array( 'background' => $hover_background ) );
		}

	}

	$output .= '<div ' . wpex_parse_attrs( $outer_wrap_attrs ) . '>';

}

// Add style to wrap_attrs
$wrap_attrs['style'] = vcex_inline_style( $wrap_style );

// Apply filters to wrap class and add to wrap_attrs
$wrap_attrs['class'] = trim( apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, implode( ' ', $wrap_attrs['class'] ), 'vcex_icon_box', $atts ) );

// Open link tag if url and url_wrap are defined
if ( $url && 'true' == $url_wrap ) {

	$url_wrap_attrs = array(
		'href'   => esc_url( $url ),
		'class'  => $wrap_attrs['class'],
		'id'     => vcex_get_unique_id( $unique_id ),
		'target' => $url_target,
		'rel'    => $url_rel,
	);

	if ( ! empty( $wrap_attrs['data-wpex-hover'] ) ) {
		$url_wrap_attrs['data-wpex-hover'] = $wrap_attrs['data-wpex-hover'];
	}

	$output .= '<a ' . wpex_parse_attrs( $url_wrap_attrs ) . '>';

// Open icon box with standard div
} else {

	$output .= '<div ' . wpex_parse_attrs( $wrap_attrs ) . '>';

}

	// Open link if url is defined and the entire wrapper isn't a link
	if ( $url && 'true' != $url_wrap ) {

		$output .= '<a ' . wpex_parse_attrs( array(
			'href'   => esc_url( $url ),
			'class'  => $url_classes,
			'target' => $url_target,
			'rel'    => $url_rel,
		) ) . '>';

	}
	
	/**
	 * Display Image
	 */
	if ( $image ) {

		$image_style = vcex_inline_style( array(
			'width'         => $image_width,
			'margin_bottom' => $image_bottom_margin,
		), false );

		$output .= wpex_get_post_thumbnail( array(
			'size'       => 'wpex-custom',
			'attachment' => $image,
			'alt'        => $heading,
			'width'      => ( 'true' == $resize_image ) ? $image_width : '',
			'height'     => ( 'true' == $resize_image ) ? $image_height : '',
			'crop'       => 'center-center',
			'style'      => $image_style,
			'class'      => 'vcex-icon-box-image',
		) );

	}

	/**
	 * Display Icon
	 */
	elseif ( $icon || $icon_alternative_classes ) {

		// Load icon family CSS
		vcex_enqueue_icon_font( $icon_type );

		// Define icon attributes
		$icon_attrs = array(
			'class' => 'vcex-icon-box-icon',
		);

		// Add extra icon classes
		if ( $icon_background && ! $icon_height ) {
			$icon_attrs['class'] .= ' vcex-icon-box-w-bg';
		}
		if ( $icon_width || $icon_height ) {
			$icon_attrs['class'] .= ' no-padding';
		}

		// Icon Style
		$icon_style = array(
			'color'         => $icon_color,
			'width'         => $icon_width,
			'font_size'     => $icon_size,
			'border_radius' => $icon_border_radius,
			'background'    => $icon_background,
			'height'        => $icon_height,
			'line_height'   => $icon_height ? intval( $icon_height ) . 'px' : '',
			'margin_bottom' => ( $icon_bottom_margin && in_array( $style, array( 'two', 'three', 'four', 'five', 'six' ) ) ) ? $icon_bottom_margin : '',
		);

		// Convert icon style array to inline style
		$icon_attrs['style'] = vcex_inline_style( $icon_style, false );

		$output .= '<div ' . wpex_parse_attrs( $icon_attrs ) . '>';

			if ( $icon_alternative_classes ) {

				$output .= '<span class="' . esc_attr( do_shortcode( $icon_alternative_classes ) ) . '" aria-hidden="true"></span>';

			} else {

				$output .= '<span class="' . esc_attr( $icon ) . '" aria-hidden="true"></span>';

			}

		$output .= '</div>';

	}
	
	/**
	 * Display Heading
	 */
	if ( $heading ) {

		$heading_type = $heading_type ? esc_attr( $heading_type ) : 'h2';

		$heading_attrs = array(
			'class' => 'vcex-icon-box-heading',
		);

		if ( $heading_font_family ) {
			wpex_enqueue_google_font( $heading_font_family );
		}

		$heading_attrs['style'] = vcex_inline_style( array(
			'font_family'    => $heading_font_family,
			'font_weight'    => $heading_weight,
			'color'          => $heading_color,
			'font_size'      => $heading_size,
			'letter_spacing' => $heading_letter_spacing,
			'margin_bottom'  => $heading_bottom_margin,
			'text_transform' => $heading_transform,
		), false );

		if ( $heading_responsive_font_size = vcex_get_module_responsive_data( $heading_size, 'font_size' ) ) {
			$heading_attrs['data-wpex-rcss'] = $heading_responsive_font_size;
		}

		$output .= wpex_parse_html( $heading_type, $heading_attrs, wp_kses_post( $heading ) );

	} // End heading

	// Close link around heading and icon
	if ( $url && 'true' != $url_wrap ) {
		$output .= '</a>';
	}

	/**
	 * Display Content
	 */
	if ( $content ) {

		$content_attrs = array(
			'class' => 'vcex-icon-box-content clr',
		);

		$content_attrs['style'] = vcex_inline_style( array(
			'color'     => $font_color,
			'font_size' => $font_size,
		), false );

		// Get responsive data
		if ( $content_responsive_font_size = vcex_get_module_responsive_data( $font_size, 'font_size' ) ) {
			$content_attrs['data-wpex-rcss'] = $content_responsive_font_size;
		}

		$output .= wpex_parse_html( 'div', $content_attrs, wpex_the_content( $content ) );

	}

// Close outer link wrap
if ( $url && 'true' == $url_wrap ) :

	$output .= '</a>';

// Close outer div wrap
else :

	$output .= '</div>';

endif;

// Close css wrapper for icon style one
if ( $has_outer_wrap ) {
	$output .= '</div>';
}

echo $output;