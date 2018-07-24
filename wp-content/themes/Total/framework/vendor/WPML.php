<?php
/**
 * WPML Configuration Class
 *
 * @package Total WordPress Theme
 * @subpackage 3rd Party
 * @version 4.6.5
 */

namespace TotalTheme\Vendor;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class WPML {

	/**
	 * Start things up
	 *
	 * @since 4.6.5
	 */
	public function __construct() {

		// Add Actions
		add_action( 'admin_init', array( $this, 'register_strings' ) );
		add_filter( 'body_class', array( $this, 'body_class' ) );

		// Add Filters
		add_filter( 'upload_dir', array( $this, 'upload_dir' ) );
		add_filter( 'wpex_shortcodes_tinymce_json', array( $this, 'tinymce_shortcode' ) );

		// Register shortcodes
		add_shortcode( 'wpml_translate', array( $this, 'translate_shortcode' ) );
		add_shortcode( 'wpml_lang_selector', array( $this, 'switcher_shortcode' ) );

	}

	/**
	 * Registers theme_mod strings into WPML
	 *
	 * @since 4.6.5
	 */
	public function register_strings() {
		if ( function_exists( 'icl_register_string' ) && $strings = wpex_register_theme_mod_strings() ) {
			foreach( $strings as $string => $default ) {
				icl_register_string( 'Theme Settings', $string, get_theme_mod( $string, $default ) );
			}
		}
	}

	/**
	 * Registers theme_mod strings into WPML
	 *
	 * @since 3.0.0
	 */
	public function body_class( $classes ) {
		if ( defined( 'ICL_LANGUAGE_CODE' ) ) {
			$classes[] = 'wpml-language-' . ICL_LANGUAGE_CODE;
		}
		return $classes;
	}

	/**
	 * Fix for when users have the Language URL Option on "different domains"
	 * which causes cropped images to fail.
	 *
	 * @since 4.6.5
	 */
	public function upload_dir( $upload ) {

		// Check if WPML language_negociation type
		$language_negociation = apply_filters( 'wpml_setting', false, 'language_negotiation_type' );
		if ( $language_negociation !== false && $language_negociation == 2 ) {
			$upload['baseurl'] = apply_filters( 'wpml_permalink', $upload['baseurl'] );
		}

		// Return $upload var
		return $upload;

	}

	/**
	 * WPML Translation Shortcode
	 *
	 * [wpml_translate lang=es]Hola[/wpml_translate]
	 * [wpml_translate lang=en]Hello[/wpml_translate]
	 *
	 * @since 4.6.5
	 */
	public function translate_shortcode( $atts, $content = null ) {
		extract( shortcode_atts( array(
			'lang'	=> '',
		), $atts ) );
		$lang_active = ICL_LANGUAGE_CODE;
		if ( $lang == $lang_active ) {
			return do_shortcode( $content );
		}
	}

	/**
	 * Language switcher plugin
	 *
	 * @since 4.6.5
	 */
	public function switcher_shortcode( $atts, $content = null ) {
		ob_start();
		do_action( 'icl_language_selector' );
		return ob_get_clean();
	}

	/**
	 * Add shortcodes to the tiny MCE
	 *
	 * @since 4.6.5
	 */
	public function tinymce_shortcode( $data ) {
		if ( shortcode_exists( 'wpml_translate' ) ) {
			$data['shortcodes']['wpml_lang_selector'] = array(
				'text' => esc_html__( 'WPML Switcher', 'total' ),
				'insert' => '[wpml_lang_selector]',
			);
		}
		return $data;
	}

}
new WPML();