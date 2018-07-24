<?php
/**
 * Font Weight VC param
 *
 * @package Total WordPress Theme
 * @subpackage VC Functions
 * @version 4.3.2
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function vcex_button_styles_shortcode_param( $settings, $value ) {

	$output = '<select name="'
		. $settings['param_name']
		. '" class="wpb_vc_param_value wpb-input wpb-select '
		. $settings['param_name']
		. ' ' . $settings['type'] .'">';

	$excluded = isset( $settings['exclude_choices'] ) ? $settings['exclude_choices'] : array();
	
	if ( function_exists( 'wpex_button_styles' ) ) {

		$options = wpex_button_styles();

		foreach ( $options as $key => $name ) {

			if ( in_array( $key, $excluded ) ) {
				continue;
			}

			$output .= '<option value="'. esc_attr( $key )  .'" '. selected( $value, $key, false ) .'>'. esc_attr( $name ) .'</option>';

		}

	}

	$output .= '</select>';

	// Return output
	return $output;

}
vc_add_shortcode_param( 'vcex_button_styles', 'vcex_button_styles_shortcode_param' );