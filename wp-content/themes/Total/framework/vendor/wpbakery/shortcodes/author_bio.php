<?php
/**
 * Visual Composer Divider
 *
 * @package Total WordPress Theme
 * @subpackage VC Functions
 * @version 4.8
 */

if ( ! class_exists( 'VCEX_Author_Bio_Shortcode' ) ) {

	class VCEX_Author_Bio_Shortcode {

		/**
		 * Main constructor
		 *
		 * @since 4.5.4
		 */
		public function __construct() {
			add_shortcode( 'vcex_author_bio', array( $this, 'output' ) );
			vc_lean_map( 'vcex_author_bio', array( $this, 'map' ) );
		}

		/**
		 * Shortcode output => Get template file and display shortcode
		 *
		 * @since 4.5.4
		 */
		public function output( $atts, $content = null ) {
			ob_start();
			wpex_get_template_part( 'author_bio' );
			return ob_get_clean();
		}

		/**
		 * Map shortcode to VC
		 *
		 * @since 4.5.4
		 */
		public function map() {
			return array(
				'name'        => __( 'Author Bio', 'total' ),
				'description' => __( 'Display current author bio.', 'total' ),
				'base'        => 'vcex_author_bio',
				'icon'        => 'vcex-author-bio vcex-icon ticon ticon-user-circle',
				'category'    => wpex_get_theme_branding(),
				'params'      => array(
					array(
						'type'       => 'vcex_notice',
						'param_name' => 'main_notice',
						'text'       => __( 'This module doesn\'t have any settings.', 'total' ),
					),
				),
				'show_settings_on_create' => false,
			);
		}
	}
}
new VCEX_Author_Bio_Shortcode;