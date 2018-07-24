<?php
/**
 * Add meta viewport tag to header
 *
 * @package Total WordPress Theme
 * @subpackage Framework
 * @version 4.6
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function wpex_meta_viewport() {

	$viewport = '';
	
	// Responsive viewport viewport
	if ( wpex_is_layout_responsive() ) {
		$viewport = '<meta name="viewport" content="width=device-width, initial-scale=1">';
	}

	// Non responsive meta viewport
	else {
		$width = wpex_get_mod( 'main_container_width', '980' );
		if ( false == strpos( $width, '%' ) ) {
			$width    = $width ? intval( $width ) : '980';
			$viewport = '<meta name="viewport" content="width=' . $width . '" />'; // can only be added if not a percentage
		}
	}

	// Apply filters to the meta viewport for child theme tweaking
	if ( $viewport ) {
		echo apply_filters( 'wpex_meta_viewport', $viewport );
		echo "\r\n";

	}
	
}
add_action( 'wp_head', 'wpex_meta_viewport', 1 );