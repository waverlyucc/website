<?php
/**
 * Adds custom styles to the tinymce editor "Formats" dropdown
 *
 * @package Total WordPress Theme
 * @subpackage Framework
 * @version 4.6.5
 */

namespace TotalTheme;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class MceEditorFormats {

	/**
	 * Main constructor
	 *
	 * @since 2.1.0
	 */
	public function __construct() {
		add_filter( 'tiny_mce_before_init', array( $this, 'add_formats' ) );
	}

	/**
	 * Adds custom styles to the formats dropdown by altering the $settings
	 *
	 * @since 2.1.0
	 */
	public function add_formats( $settings ) {

		// General
		$total = apply_filters( 'wpex_tiny_mce_formats_items', array(
			array(
				'title'    => esc_html__( 'Theme Button', 'total' ),
				'selector' => 'a',
				'classes'  => 'theme-button',
			),
			array(
				'title'   => esc_html__( 'Highlight', 'total' ),
				'inline'  => 'span',
				'classes' => 'text-highlight',
			),
			array(
				'title'   => esc_html__( 'Thin Font', 'total' ),
				'inline'  => 'span',
				'classes' => 'thin-font'
			),
			array(
				'title'   => esc_html__( 'White Text', 'total' ),
				'inline'  => 'span',
				'classes' => 'white-text'
			),
			array(
				'title'    => esc_html__( 'Check List', 'total' ),
				'selector' => 'ul',
				'classes'  => 'check-list'
			),
		) );

		// Dropcaps
		$dropcaps = apply_filters( 'wpex_tiny_mce_formats_dropcaps', array(
			array(
				'title'   => esc_html__( 'Dropcap', 'total' ),
				'inline'  => 'span',
				'classes' => 'dropcap',
			),
			array(
				'title'   => esc_html__( 'Boxed Dropcap', 'total' ),
				'inline'  => 'span',
				'classes' => 'dropcap boxed',
			),
		) );

		// Color buttons
		$color_buttons = apply_filters( 'wpex_tiny_mce_formats_color_buttons', array(
			array(
				'title'     => esc_html__( 'Blue', 'total' ),
				'selector'  => 'a',
				'classes'   => 'color-button blue',
			),
			array(
				'title'     => esc_html__( 'Black', 'total' ),
				'selector'  => 'a',
				'classes'   => 'color-button black',
			),
			array(
				'title'     => esc_html__( 'Red', 'total' ),
				'selector'  => 'a',
				'classes'   => 'color-button red',
			),
			array(
				'title'     => esc_html__( 'Orange', 'total' ),
				'selector'  => 'a',
				'classes'   => 'color-button orange',
			),
			array(
				'title'     => esc_html__( 'Green', 'total' ),
				'selector'  => 'a',
				'classes'   => 'color-button green',
			),
			array(
				'title'     => esc_html__( 'Gold', 'total' ),
				'selector'  => 'a',
				'classes'   => 'color-button gold',
			),
			array(
				'title'     => esc_html__( 'Teal', 'total' ),
				'selector'  => 'a',
				'classes'   => 'color-button teal',
			),
			array(
				'title'     => esc_html__( 'Purple', 'total' ),
				'selector'  => 'a',
				'classes'   => 'color-button purple',
			),
			array(
				'title'     => esc_html__( 'Pink', 'total' ),
				'selector'  => 'a',
				'classes'   => 'color-button pink',
			),
			array(
				'title'     => esc_html__( 'Brown', 'total' ),
				'selector'  => 'a',
				'classes'   => 'color-button brown',
			),
			array(
				'title'     => esc_html__( 'Rosy', 'total' ),
				'selector'  => 'a',
				'classes'   => 'color-button rosy',
			),
			array(
				'title'     => esc_html__( 'White', 'total' ),
				'selector'  => 'a',
				'classes'   => 'color-button white',
			),
		) );

		$formats = array();

		if ( $total ) {

			$branding = wpex_get_theme_branding();
			$branding = $branding ? $branding . ' ' : '';

			$formats[] = array(
				'title' => $branding . esc_html__( 'Styles', 'total' ),
				'items' => ( object ) $total,
			);

		}

		if ( $dropcaps ) {

			$formats[] = array(
				'title' => esc_html__( 'Dropcaps', 'total' ),
				'items' => ( object ) $dropcaps,
			);

		}

		if ( $color_buttons ) {

			$formats[] = array(
				'title' => esc_html__( 'Color Buttons', 'total' ),
				'items' => ( object ) $color_buttons,
			);

		}

		if ( $formats ) {

			// Merge Formats
			$settings['style_formats_merge'] = true;

			// Add new formats
			$settings['style_formats'] = json_encode( $formats );

		}

		// Return New Settings
		return $settings;

	}

}
new MceEditorFormats();