<?php
/**
 * Visual Composer Multi-Buttons
 *
 * @package Total WordPress Theme
 * @subpackage VC Templates
 * @version 4.6.1
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

// Get shortcode attributes
$atts = vcex_vc_map_get_attributes( 'vcex_multi_buttons', $atts );

// Get buttons
$buttons = (array) vc_param_group_parse_atts( $atts['buttons'] );

// Buttons are required
if ( ! $buttons ) {
	return;
}

// Inline styles
$wrap_inline_style = array(
	'font_size'      => $atts['font_size'],
	'letter_spacing' => $atts['letter_spacing'],
	'font_family'    => $atts['font_family'],
	'font_weight'    => $atts['font_weight'],
	'text_align'     => $atts['align'],
	'border_radius'  => $atts['border_radius'],
);

// Load custom Google font if needed
wpex_enqueue_google_font( $atts['font_family'] );

// Define wrap attributes
$wrap_attrs = array(
	'class' => 'vcex-multi-buttons vcex-clr',
	'style' => vcex_inline_style( $wrap_inline_style, false ),
);

// Get responsive data
if ( $responsive_data = vcex_get_module_responsive_data( $atts ) ) {
	$wrap_attrs['data-wpex-rcss'] = $responsive_data;
}

// Visibility
if ( $atts['visibility'] ) {
	$wrap_attrs['class'] .= ' ' . $atts['visibility'];
}

// Extra classname
if ( $atts['el_class'] ) {
	$wrap_attrs['class'] .= ' ' . vcex_get_extra_class( $atts['el_class'] );
}

// Full width buttons on mobile
if ( 'true' == $atts['small_screen_full_width'] ) {
	$wrap_attrs['class'] .= ' vcex-small-screen-full-width';
}

// Define output
$output = '<div ' . wpex_parse_attrs( $wrap_attrs ) . '>';

	// Count number of buttons
	$buttons_count = count( $buttons );

	// Loop through buttons
	$count = 0;
	foreach ( $buttons as $button ) {
		$count ++;

		// Button url is required
		if ( ! isset( $button['link'] ) ) {
			continue;
		}

		// Get link data
		$link_data = vcex_build_link( $button['link'] );

		// Link is required
		if ( ! isset( $link_data['url'] ) ) {
			continue;
		}

		// Sanitize text
		$text = isset( $button['text'] ) ? $button['text'] : __( 'Button', 'total' );

		// Get button style
		$style        = isset( $button['style'] ) ? $button['style'] : '';
		$color        = isset( $button['color'] ) ? $button['color'] : '';
		$custom_color = isset( $button['custom_color'] ) ? $button['custom_color'] : '';
		$hover_color  = isset( $button['custom_color_hover'] ) ? $button['custom_color_hover'] : '';

		// Fallback from original release to include only styles that make sense!
		if ( 'minimal-border' == $style ) {
			$style = 'outline';
		} elseif ( 'three-d' == $style || 'graphical' == $style ) {
			$style = 'flat';
		} elseif ( 'clean' == $style ) {
			$style = 'flat';
			$color = 'white';
		}

		// Button css
		$button_css = vcex_inline_style( array(
			'padding'      => $atts['padding'],
			'border_width' => $atts['border_width'] ? absint( $atts['border_width'] ) . 'px' : '',
			'line_height'  => $atts['line_height'] ? absint( $atts['line_height']  ) . 'px' : '',
			'width'        => $atts['width'] ? absint( $atts['width'] ) . 'px' : '', // Must use width because min-width ignores max width.
		), false );
		if ( $atts['spacing'] ) {
			$margin = absint( $atts['spacing'] ) / 2 . 'px';
			$button_css .= 'margin:0 ' . $margin . ' ' . $margin . ';';
		}
		// Custom color
		if ( $custom_color
			&& $custom_color_css = wpex_get_button_custom_color_css( $style, $custom_color )
		) {
			$button_css .= ' ' . $custom_color_css;
		}

		// Define button classes
		$button_classes = wpex_get_button_classes( $style, $color );
		if ( isset( $button['local_scroll'] ) && 'true' == $button['local_scroll'] ) {
			$button_classes .= ' local-scroll-link';
		}

		// Define button attributes
		$attrs = array(
			'href'   => esc_url( do_shortcode( $link_data['url'] ) ),
			'title'  => isset( $link_data['title'] ) ? do_shortcode( $link_data['title'] ) : '',
			'class'  => $button_classes,
			'target' => isset( $link_data['target'] ) ? $link_data['target'] : '',
			'rel'    => isset( $link_data['rel'] ) ? esc_attr( $link_data['rel'] ) : '',
			'style'  => $button_css,
		);

		// Add animation to button classes
		if ( isset( $button['css_animation'] ) && 'none' != $button['css_animation'] ) {
			$attrs['class'] .= ' ' . vcex_get_css_animation( $button['css_animation'] );
		}

		// Add counter to button class => Useful for custom styling purposes
		$attrs['class'] .= ' vcex-count-' . $count;

		// Hover data/class
		if ( $custom_color || $hover_color ) {

			$hover_data = array();

			if ( 'outline' == $style && ! $hover_color ) {
				$hover_color = $custom_color;
			}

			if ( $hover_color ) {

				// Color
				if ( 'plain-text' == $style ) {
					$hover_data['color'] = esc_attr( $hover_color );
				}

				// Backgrounds
				else {
					$hover_data['background'] = esc_attr( $hover_color );
				}

			}

			if ( $hover_data ) {
				$attrs['data-wpex-hover'] = json_encode( $hover_data );
			}

		}

		// Output button
		$output .= wpex_parse_html( 'a', $attrs, do_shortcode( $text ) );

	}

$output .= '</div>';

echo $output;