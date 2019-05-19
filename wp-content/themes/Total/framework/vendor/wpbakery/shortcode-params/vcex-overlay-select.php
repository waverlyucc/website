<?php
/**
 * Overlay VC param
 *
 * @package Total WordPress Theme
 * @subpackage VC Functions
 * @version 4.5
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function vcex_overlay_shortcode_param( $settings, $value ) {

	$output = '<select name="'
			. $settings['param_name']
			. '" class="wpb_vc_param_value wpb-input wpb-select '
			. $settings['param_name']
			. ' ' . $settings['type'] .'">';
	
	if ( function_exists( 'wpex_overlay_styles_array' ) ) {

		$options = wpex_overlay_styles_array();

		$excluded = isset( $settings['exclude_choices'] ) ? $settings['exclude_choices'] : array();

		foreach ( $options as $key => $name ) {

			if ( in_array( $key, $excluded ) ) {
				continue;
			}

			$output .= '<option value="'. esc_attr( $key )  .'" '. selected( $value, $key, false ) .'>'. esc_attr( $name ) .'</option>';

		}

	}

	$output .= '</select>';

	return $output;

}
vc_add_shortcode_param( 'vcex_overlay', 'vcex_overlay_shortcode_param' );