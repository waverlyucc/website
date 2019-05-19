<?php
/**
 * Text Transforms VC param
 *
 * @package Total WordPress Theme
 * @subpackage VC Functions
 * @version 4.8
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function vcex_text_alignments_shortcode_param( $settings, $value ) {

	$options = array( 'default', 'left', 'center', 'right' );

	$output = '<div class="vcex-alignments-param vcex-noselect clr">';

	$excluded = isset( $settings['exclude_choices'] ) ? $settings['exclude_choices'] : array();

	foreach ( $options as $option ) {

		if ( in_array( $option, $excluded ) ) {
			continue;
		}

		$option = ( 'default' == $option ) ? '' : $option; // Set default option value to empty

		if ( $option ) {

			$active = $value === $option ? ' vcex-active' : '';

			$output .= '<div class="vcex-alignment-opt' . $active . '" data-value="'. esc_attr( $option )  .'"><span class="ticon ticon-align-' . $option . '"></span></div>';

		} else {

			$active = ! $value ? ' vcex-active' : '';

			$output .= '<div class="vcex-alignment-opt vcex-default' . $active . '" data-value="'. esc_attr( $option )  .'">' . esc_html( 'Default', 'total' ) . '</div>';

		}

	}

	$output .= '<input name="' . $settings['param_name'] . '" class="vcex-hidden-input wpb-input wpb_vc_param_value  ' . $settings['param_name'] . ' ' . $settings['type'] . '_field" type="hidden" value="' . esc_attr( $value ) . '" />';

	$output .= '</div>';

	return $output;

}
vc_add_shortcode_param( 'vcex_text_alignments', 'vcex_text_alignments_shortcode_param' );