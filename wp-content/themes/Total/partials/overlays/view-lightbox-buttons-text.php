<?php
/**
 * Lightbox Buttons + Text Overlay
 *
 * @package Total WordPress Theme
 * @subpackage Partials
 * @version 4.5
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Only used for outside position
if ( 'outside_link' != $position ) {
	return;
}

// Load lightbox skin stylesheet
wpex_enqueue_ilightbox_skin();

// Lightbox
$lightbox_link = ! empty( $args['lightbox_link'] ) ? $args['lightbox_link'] : wpex_get_lightbox_image();
$lightbox_data = '';
if ( ! empty( $args['lightbox_data'] ) && is_array( $args['lightbox_data'] ) ) {
	$lightbox_data = ' '. implode( ' ', $args['lightbox_data'] );
}
$lightbox_class = ! empty( $args['lightbox_class'] ) ? $args['lightbox_class'] : 'wpex-lightbox';

// Custom Link
$link = isset( $args['overlay_link'] ) ? $args['overlay_link'] : wpex_get_permalink();

// Define link target
$target = '';
if ( isset( $args['link_target'] ) && ( 'blank' == $args['link_target'] || '_blank' == $args['link_target'] ) ) {
    $target = 'blank';
}

// Apply filters
$link   = apply_filters( 'wpex_lightbox_buttons_button_overlay_link', $link, $args );
$target = apply_filters( 'wpex_button_overlay_target', $target, $args );

// Sanitize Data
$link          = esc_url( $link );
$target        = 'blank' == $target ? ' target="_blank"' : '';
$lightbox_link = esc_url( $lightbox_link );

$output = '<div class="overlay-view-lightbox-text overlay-hide theme-overlay textcenter">';

	$output .= '<div class="overlay-table clr">';

		$output .= '<div class="overlay-table-cell clr">';

			$output .= '<a href="'. $lightbox_link .'" class="' . $lightbox_class . '"' . $lightbox_data . '>' .  esc_html__( 'Zoom', 'total' ) . '<span class="fa fa-search" aria-hidden="true"></span></a>';
			
			$output .= '<a href="' . $link . '" class="view-post"' . $target . '>' . esc_html__( 'View', 'total' ) . '<span class="fa fa-arrow-right" aria-hidden="true"></span></a>';
		
		$output .= '</div>';
	
	$output .= '</div>';
	
$output .= '</div>';

echo $output;