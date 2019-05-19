<?php
/**
 * Menu Select VC param
 *
 * @package Total WordPress Theme
 * @subpackage VC Functions
 * @version 4.3
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function vcex_menu_select_shortcode_param( $settings, $value ) {

	// Begin output
	$output = '<select name="'
			. $settings['param_name']
			. '" class="wpb_vc_param_value wpb-input wpb-select '
			. $settings['param_name']
			. ' ' . $settings['type'] . '">';

	$output .= '<option value="" ' . selected( $value, '', false ) . '>' . esc_html( 'Select', 'total' ) . '</option>';
	
	$menus_array = array();
	$menus = get_terms( 'nav_menu', array( 'hide_empty' => true ) );
	if ( $menus && is_array( $menus ) ) {
		foreach ( $menus as $menu ) {
			$output .= '<option value="' . $menu->term_id . '" ' . selected( $value, $menu->term_id, false ) . '>' . esc_attr( $menu->name ) . '</option>';
		}
	}

	$output .= '</select>';

	// Return output
	return $output;

}
vc_add_shortcode_param( 'vcex_menus_select', 'vcex_menu_select_shortcode_param' );