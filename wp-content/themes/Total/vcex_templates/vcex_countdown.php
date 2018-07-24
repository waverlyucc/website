<?php
/**
 * Visual Composer Countdown
 *
 * @package Total WordPress Theme
 * @subpackage VC Templates
 * @version 4.6.5
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
if ( ! function_exists( 'vc_map_get_attributes' ) || ! function_exists( 'vc_shortcode_custom_css_class' ) ) {
	vcex_function_needed_notice();
	return;
}

// Define vars
$output = '';

// Get and extract shortcode attributes
$atts = vcex_vc_map_get_attributes( 'vcex_countdown', $atts );

// Load js
$this->enqueue_scripts( $atts );

// Get end date data
$end_year  = ! empty( $atts['end_year'] ) ? intval( $atts['end_year'] ) : date( 'Y' );
$end_month = intval( $atts['end_month'] );
$end_day   = intval( $atts['end_day'] );

// Sanitize data to make sure input is not crazy
if ( $end_month > 12 ) {
	$end_month = '';
}
if ( $end_day > 31 ) {
	$end_day = '';
}

// Define end date
if ( $end_year && $end_month && $end_day ) {
	$end_date = $end_year . '-' . $end_month . '-' . $end_day;
} else {
	$end_date = '2018-12-15';
}

// Add end time
$atts['end_time'] = $atts['end_time'] ? $atts['end_time'] : '00:00';
$end_date = $end_date . ' ' . esc_html( $atts['end_time'] );

// Make sure date is in correct format
$end_date = date( 'Y-m-d H:i', strtotime( $end_date ) );

// Countdown data
$data = array();
$data['data-countdown'] = $end_date;
$data['data-days']      = $atts['days'] ? $atts['days'] : __( 'Days', 'total' );
$data['data-hours']     = $atts['hours'] ? $atts['hours'] : __( 'Hours', 'total' );
$data['data-minutes']   = $atts['minutes'] ? $atts['minutes'] : __( 'Minutes', 'total' );
$data['data-seconds']   = $atts['seconds'] ? $atts['seconds'] : __( 'Seconds', 'total' );

if ( $atts['timezone'] ) {
	$data['data-timezone'] = esc_attr( $atts['timezone'] );
}

$data = apply_filters( 'vcex_countdown_data', $data, $atts ); // Apply filters for translations

// Define wrap attributes
$wrap_attrs = array(
	'data' => ''
);

// Main classes
$wrap_classes = array( 'vcex-module', 'vcex-countdown-wrap' );
if ( $atts['css_animation'] && 'none' != $atts['css_animation'] ) {
	$wrap_classes[] = vcex_get_css_animation( $atts['css_animation'] );
}
if ( $atts['el_class'] ) {
	$wrap_classes[] = vcex_get_extra_class( $atts['el_class'] );
}

// Style
$styles = array(
	'color'          => $atts['color'],
	'font_family'    => $atts['font_family'],
	'font_size'      => $atts['font_size'],
	'letter_spacing' => $atts['letter_spacing'],
	'font_weight'    => $atts['font_weight'],
	'text_align'     => $atts['text_align'],
	'line_height'    => $atts['line_height'],
);
if ( $atts['font_family'] ) {
	wpex_enqueue_google_font( $atts['font_family'] );
}
if ( 'true' == $atts['italic'] ) {
	$styles['font_style'] = 'italic';
}
$wrap_style = vcex_inline_style( $styles, false );

// Responsive styles
if ( $responsive_data = vcex_get_module_responsive_data( $atts ) ) {
	$wrap_attrs['data-wpex-rcss'] = $responsive_data;
}

// Add to attributes
$wrap_attrs['class'] = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, implode( ' ', $wrap_classes ), 'vcex_countdown', $atts );;
$wrap_attrs['style'] = $wrap_style;

// Output
$output .= '<div ' . wpex_parse_attrs( $wrap_attrs ) . '>';

	$output .= '<div class="vcex-countdown clr" '. wpex_parse_attrs( $data ) .'></div>';

$output .= '</div>';

echo $output;