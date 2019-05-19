<?php
/**
 * VC Select Buttons Parameter
 *
 * @package Total WordPress Theme
 * @subpackage VC Functions
 * @version 4.4.1
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function vcex_select_buttons( $settings, $value ) {

	$wrap_classes = 'vcex-select-buttons-param vcex-custom-select vcex-noselect clr';

	$choices = $settings['choices'] ? $settings['choices'] : array();

	if ( 'button_size' == $choices ) {
		$choices = array(
			''       => __( 'Default', 'total' ),
			'small'  => __( 'Small', 'total' ),
			'medium' => __( 'Medium', 'total' ),
			'large'  => __( 'Large', 'total' ),
		);
	} elseif ( 'button_layout' == $choices ) {
		$choices = array(
			'inline' => __( 'Inline', 'total' ),
			'block' => __( 'Block', 'total' ),
			'expanded' => __( 'Expanded', 'total' ),
		);
	} elseif ( 'link_target' == $choices ) {
		$choices = array(
			'self' => __( 'Same tab', 'total' ),
			'_blank' => __( 'New tab', 'total' )
		);
	} elseif( 'html_tag' == $choices ) {
		$choices = array(
			'h1' => 'h1',
			'h2' => 'h2',
			'h3' => 'h3',
			'h4' => 'h4',
			'h5' => 'h5',
			'div' => 'div',
			'span' => 'span',
		);
	} elseif ( 'masonry_layout_mode' == $choices ) {
		$choices = array(
			'masonry' => __( 'Masonry', 'total' ),
			'fitRows' => __( 'Fit Rows', 'total' ),
		);
	} elseif ( 'filter_layout_mode' == $choices ) {
		$choices = array(
			'masonry' => __( 'Masonry', 'total' ),
			'fitRows' => __( 'Fit Rows', 'total' ),
		);
	} elseif ( 'grid_style' == $choices ) {
		$choices = array(
			'fit_columns' => __( 'Fit Columns', 'total' ),
			'masonry' => __( 'Masonry', 'total' ),
		);
	} elseif ( 'slider_animation' == $choices ) {
		$choices = array(
			'fade_slides' => __( 'Fade', 'total' ),
			'slide' => __( 'Slide', 'total' ),
		);
	} elseif( 'text_decoration' == $choices ) {
		$choices = wpex_text_decorations();
	} elseif( 'font_style' == $choices ) {
		$choices = wpex_font_styles();
	} elseif( 'bullet_styles' == $choices ) {
		$choices = array(
			'check'  => '<img src=" ' . wpex_asset_url( 'images/check.png' ) . '" />',
			'blue'   => '<img src=" ' . wpex_asset_url( 'images/bullets-blue.png' ) . '" />',
			'gray'   => '<img src=" ' . wpex_asset_url( 'images/bullets-gray.png' ) . '" />',
			'purple' => '<img src=" ' . wpex_asset_url( 'images/bullets-purple.png' ) . '" />',
			'red'    => '<img src=" ' . wpex_asset_url( 'images/bullets-red.png' ) . '" />',
		);
		$wrap_classes .= ' vcex-no-active-bg';
	} elseif ( is_callable( $choices ) ) {
		$choices = call_user_func( $choices );
	}

	if ( ! $choices ) {
		return;
	}

	$output = '<div class="' . $wrap_classes . '">';

	if ( ! $value ) {
		if ( isset( $settings['std'] ) ) {
			$value = $settings['std'];
		} else {
			$temp_choices = $choices; 
			reset( $temp_choices );
			$value = key( $temp_choices );
		}
	}

	foreach ( $choices as $id => $label ) {

		$class = 'vcex-opt';
		if ( $id == $value ) {
			$class .= ' vcex-active';
		}
		if ( $id ) {
			$class .= ' vcex-opt-' . $id;
		}

		$output .= '<div class="' . $class . '" data-value="'. esc_attr( $id )  .'">' . $label . '</div>';

	}

	$output .= '<input name="' . $settings['param_name'] . '" class="vcex-hidden-input wpb-input wpb_vc_param_value  ' . $settings['param_name'] . ' ' . $settings['type'] . '_field" type="hidden" value="' . esc_attr( $value ) . '" />';

	$output .= '</div>';

	return $output;

}
vc_add_shortcode_param(
	'vcex_select_buttons',
	'vcex_select_buttons',
	wpex_asset_url( 'js/dynamic/wpbakery/vcex-params.min.js?v=' . WPEX_THEME_VERSION )
);