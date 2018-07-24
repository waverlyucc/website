<?php
/**
 * Adds custom CSS to the site from Customizer settings
 *
 * @package Total WordPress Theme
 * @subpackage Framework
 * @version 4.6.5
 */

namespace TotalTheme;

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Start Class
class InlineCSS {

	/**
	 * Main constructor
	 *
	 * @since 4.0
	 */
	public function __construct() {

		// Add custom CSS to head tag
		add_action( 'wp_head', array( $this, 'ouput_css' ), 9999 );

		// Minify custom CSS on front-end only
		// Note: Can't minify on backend or messes up the Custom CSS panel
		if ( ! is_admin() && ! is_customize_preview() && apply_filters( 'wpex_minify_inline_css', true ) ) {
			add_filter( 'wp_get_custom_css', array( $this, 'minify' ) );
		}

	}

	/**
	 * Add all custom CSS into the WP Header
	 *
	 * @since 4.6.5
	 */
	public function ouput_css( $output = NULL ) {

		// Add filter for adding custom css via other functions
		$output = apply_filters( 'wpex_head_css', $output );

		// Custom CSS panel => Add last after all filters to make sure it always overrides
		// Deprecated in 4.0 - the theme now uses native WP additional css function for the custom css.
		if ( $css = wpex_get_mod( 'custom_css', false ) ) {
			$output .= '/*CUSTOM CSS*/'. $css;
		}

		// Minify and output CSS in the wp_head
		if ( ! empty( $output ) ) {

			// Sanitize output
			$output = wp_strip_all_tags( wpex_minify_css( $output ) );

			// Echo output
			// Don't rename #wpex-css or things will break !!! Important !!!
			echo '<style type="text/css" data-type="wpex-css" id="wpex-css">' . trim( $output ) . '</style>';

		}

	}

	/**
	 * Filter the WP custom CSS to minify the output since WP doesn't do it by default
	 *
	 * @since 4.6.5
	 */
	public function minify( $css ) {
		return wpex_minify_css( $css );
	}

}
new InlineCSS();