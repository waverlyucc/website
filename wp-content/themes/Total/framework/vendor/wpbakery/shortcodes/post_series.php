<?php
/**
 * Visual Composer Post Media
 *
 * @package Total WordPress Theme
 * @subpackage VC Functions
 * @version 4.8
 */

if ( ! class_exists( 'VCEX_Post_Series_Shortcode' ) ) {

	class VCEX_Post_Series_Shortcode {

		/**
		 * Main constructor
		 *
		 * @since 4.6
		 */
		public function __construct() {
			add_shortcode( 'vcex_post_series', array( $this, 'output' ) );
			vc_lean_map( 'vcex_post_series', array( $this, 'map' ) );
		}

		/**
		 * Shortcode output => Get template file and display shortcode
		 *
		 * @since 4.6
		 */
		public function output( $atts, $content = null ) {
			ob_start();
			wpex_get_template_part( 'post_series' );
			return ob_get_clean();
		}

		/**
		 * Map shortcode to VC
		 *
		 * @since 4.6
		 */
		public function map() {
			return array(
				'name' => __( 'Post Series', 'total' ),
				'description' => __( 'Display your post series.', 'total' ),
				'base' => 'vcex_post_series',
				'icon' => 'vcex-post-series vcex-icon ticon ticon-pencil',
				'category' => wpex_get_theme_branding(),
				'params' => array(
					array(
						'type' => 'vcex_notice',
						'param_name' => 'main_notice',
						'text' => __( 'This module displays your post series as defined via the theme template parts so there aren\'t any individual settings.', 'total' ),
					),
				)
			);
		}
	}
}
new VCEX_Post_Series_Shortcode;