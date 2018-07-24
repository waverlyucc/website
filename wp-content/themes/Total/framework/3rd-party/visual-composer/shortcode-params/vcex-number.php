<?php
/**
 * Number VC param
 *
 * @package Total WordPress Theme
 * @subpackage VC Functions
 * @version 4.3
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function vcex_number_shortcode_param( $settings, $value ) {

	$value = $value ? floatval( $value ) : '';
	$min   = isset( $settings['min'] ) ? $settings['min'] : '1';
	$max   = isset( $settings['max'] ) ? $settings['max'] : '100';
	$step  = isset( $settings['step'] ) ? $settings['step'] : '1';

	// Begin output
	$output = '<input';
		$output .= ' name="'. $settings['param_name'] .'"';
		$output .= ' class="wpb_vc_param_value wpb-input wpb-select ' . $settings['param_name'] . ' ' . $settings['type'] . '_field"';
		$output .= 'type="number"';
		$output .= 'value="' . $value . '"';
		$output .= 'min="' . $min . '"';
		$output .= 'max="' . $max . '"';
		$output .= 'step="' . $step . '"';
	$output .= '>';

	// Return output
	return $output;

}
vc_add_shortcode_param( 'vcex_number', 'vcex_number_shortcode_param' );