<?php
/**
 * Visual Composer Spacing
 *
 * @package Total WordPress Theme
 * @subpackage VC Functions
 * @version 4.8
 */

if ( ! class_exists( 'VCEX_Spacing_Shortcode' ) ) {

	class VCEX_Spacing_Shortcode {

		/**
		 * Main constructor
		 *
		 * @since 3.5.0
		 */
		public function __construct() {
			add_shortcode( 'vcex_spacing', array( $this, 'output' ) );
			vc_lean_map( 'vcex_spacing', array( $this, 'map' ) );
		}

		/**
		 * Shortcode output => Get template file and display shortcode
		 *
		 * @since 3.5.0
		 */
		public function output( $atts, $content = null ) {
			ob_start();
			include( locate_template( 'vcex_templates/vcex_spacing.php' ) );
			return ob_get_clean();
		}

		/**
		 * Map shortcode to VC
		 *
		 * @since 3.5.0
		 */
		public function map() {
			return array(
				'name'        => __( 'Spacing', 'total' ),
				'description' => __( 'Adds spacing anywhere you need it', 'total' ),
				'base'        => 'vcex_spacing',
				'category'    => wpex_get_theme_branding(),
				'icon'        => 'vcex-spacing vcex-icon ticon ticon-sort',
				'params'      => array(
					array(
						'type'        => 'textfield',
						'admin_label' => true,
						'heading'     => __( 'Spacing', 'total' ),
						'param_name'  => 'size',
						'value'       => '30px',
					),
					array(
						'type'       => 'textfield',
						'heading'    => __( 'Custom Classes', 'total' ),
						'param_name' => 'class',
					),
					array(
						'type'       => 'vcex_visibility',
						'heading'    => __( 'Visibility', 'total' ),
						'param_name' => 'visibility',
					),
				)
			);
		}

	}
}
new VCEX_Spacing_Shortcode;