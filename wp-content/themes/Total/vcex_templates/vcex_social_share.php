<?php
/**
 * Visual Composer Social Share
 *
 * @package Total WordPress Theme
 * @subpackage VC Templates
 * @version 4.5.5.1
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Helps speed up rendering in backend of VC
if ( is_admin() && ! wp_doing_ajax() ) {
	return;
}

if ( ! function_exists( 'vc_map_get_attributes' ) || ! function_exists( 'vc_param_group_parse_atts' ) ) {
	vcex_function_needed_notice();
	return;
}

$atts = vc_map_get_attributes( 'vcex_social_share', $atts );

$sites = ! empty( $atts['sites'] ) ? (array) vc_param_group_parse_atts( $atts['sites'] ) : '';

if ( ! $sites ) {
	return;
}

$wrap_attrs = array(
	'class' => 'wpex-social-share position-horizontal',
);

$wrap_attrs['class'] .= ' style-' . esc_attr( $atts['style'] );
if ( isset( $atts['visibility'] ) ) {
	$wrap_attrs['class'] .= ' ' .  $atts['visibility'];
}

$social_share_data = wpex_get_social_share_data( wpex_get_current_post_id(), $sites );

foreach ( $social_share_data as $datak => $datav ) {
	$wrap_attrs['data-' . $datak ] = $datav;
}

wp_enqueue_script( 'wpex-social-share' );

$output = '';

$output .= '<div ' . wpex_parse_attrs( $wrap_attrs ) . '>';

	$output .= '<ul class="clr">';

		// Get array of social share items
		// @see framework/helpers/social-share.php
		$items = wpex_get_social_items();

		// Loop through sites and save new array with filters for output
		foreach ( $sites as $k => $v ) {

			$site = isset( $v['site'] ) ? $v['site'] : '';

			if ( isset( $items[$site] ) ) {

				$item = $items[$site];

				$output .= '<li class="' . esc_attr( $item['li_class'] ) . '">';

					if ( isset( $item['href'] ) ) {

						$output .= '<a href="' . esc_url( $item['href'] ) . '" role="button" tabindex="1">';

					} else {
					
						$output .= '<a role="button" tabindex="1">';

					}

						$output .= '<span class="' . esc_attr( $item['icon_class'] ) . '" aria-hidden="true"></span>';
						
						$output .= '<span class="wpex-label">' . esc_html( $item['label'] ) . '</span>';
					
					$output .= '</a>';
				
				$output .= '</li>';

			}

		}

	$output .= '</ul>';

$output .= '</div>';

echo $output;