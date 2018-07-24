<?php
/**
 * Notice VC param
 *
 * @package Total WordPress Theme
 * @subpackage VC Functions
 * @version 4.3
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function vcex_notice_shortcode_param( $settings, $value ) {

	// Begin output
	$output = '<div style="color: #9d8967;border: 1px solid #ffeccc;background-color:#fff4e2;padding:1em;">' . esc_html( $settings['text'] ) . '</div>';

	// Return output
	return $output;

}
vc_add_shortcode_param( 'vcex_notice', 'vcex_notice_shortcode_param' );