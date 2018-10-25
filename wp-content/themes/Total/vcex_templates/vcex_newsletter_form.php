<?php
/**
 * Visual Composer Newsletter Form
 *
 * @package Total WordPress Theme
 * @subpackage VC Templates
 * @version 4.5.5
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

// Define output var
$output = '';

// Get and extract shortcode attributes
extract( vc_map_get_attributes( 'vcex_newsletter_form', $atts ) );

// Vars
$provider = 'mailchimp';

// Wrapper classes
$wrap_classes = array( 'vcex-module', 'vcex-newsletter-form clr' );
if ( 'true' == $fullwidth_mobile ) {
	$wrap_classes[] = 'vcex-fullwidth-mobile';
}
if ( $classes ) {
	$wrap_classes[] = vcex_get_extra_class( $classes );
}
if ( $visibility ) {
	$wrap_classes[] = $visibility;
}
if ( $css_animation && 'none' != $css_animation ) {
	$wrap_classes[] = vcex_get_css_animation( $css_animation );
}

// Turn to string and add filter to classes
$wrap_classes = implode( ' ', $wrap_classes );
$wrap_classes = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, $wrap_classes, 'vcex_newsletter_form', $atts );

// Mailchimp
if ( $provider == 'mailchimp' ) :

	$output .= '<div class="' . esc_attr( $wrap_classes ) . '"' . vcex_get_unique_id( $unique_id ) . '>';

		$input_width = $input_width ? ' style="width:' . $input_width . '"' : '';
		$input_align = $input_align ? ' float' . $input_align : '';

		$output .= '<div id="mc_embed_signup" class="vcex-newsletter-form-wrap' . $input_align . '"' . $input_width . '>';
			
			$output .= '<form action="' . $mailchimp_form_action . '" method="post" id="mc-embedded-subscribe-form" name="mc-embedded-subscribe-form" class="validate" target="_blank" novalidate>';
				
				/*
				 * Input
				 */

				$input_style = vcex_inline_style( array(
					'border'         => $input_border,
					'border_radius'  => $input_border_radius,
					'padding'        => $input_padding,
					'letter_spacing' => $input_letter_spacing,
					'height'         => $input_height,
					'background'     => $input_bg,
					'color'          => $input_color,
					'font_size'      => $input_font_size,
					'font_weight'    => $input_weight,
				) );

				$input_style = $input_style ? ' ' . $input_style : '';

				$output .= "<input type=\"email\" value=\"". $placeholder_text ."\" onfocus=\"if(this.value==this.defaultValue)this.value='';\" onblur=\"if(this.value=='')this.value=this.defaultValue;\" name=\"EMAIL\" class=\"required email\" id=\"mce-EMAIL\"". $input_style .">";

				/*
				 * Submit Button
				 */
				if ( $submit_text ) {

					$attrs = array(
						'type'  => 'submit',
						'value' => '',
						'name'  => 'subscribe',
						'id'    => 'mc-embedded-subscribe',
						'class' => 'vcex-newsletter-form-button',
						'style' => vcex_inline_style( array(
							'height'         => $submit_height,
							'margin_top'     => $submit_height ? '-' . intval( $submit_height ) / 2 : '',
							'right'          => $submit_position_right,
							'border'         => $submit_border,
							'letter_spacing' => $submit_letter_spacing,
							'padding'        => $submit_padding,
							'background'     => $submit_bg,
							'color'          => $submit_color,
							'font_size'      => $submit_font_size,
							'font_weight'    => $submit_weight,
							'border_radius'  => $submit_border_radius,
						), false ),
					);

					// Add hover data
					$hover_data = array();
					if ( $submit_hover_bg ) {
						$hover_data['background'] = esc_attr( $submit_hover_bg );
					}
					if ( $submit_hover_color ) {
						$hover_data['color'] = esc_attr( $submit_hover_color );
					}
					if ( $hover_data ) {
						$attrs['data-wpex-hover'] = json_encode( $hover_data );
					}

					$output .= wpex_parse_html( 'button', $attrs, wp_kses_post( $submit_text ) );

				}

			$output .= '</form>';

		$output .= '</div>';

	$output .= '</div>';
	
endif;

echo $output;