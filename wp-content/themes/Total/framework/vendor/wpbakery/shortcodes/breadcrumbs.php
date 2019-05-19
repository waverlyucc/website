<?php
/**
 * Visual Composer Divider
 *
 * @package Total WordPress Theme
 * @subpackage VC Functions
 * @version 4.8
 */

if ( ! class_exists( 'VCEX_Breadcrumbs_Shortcode' ) ) {

	class VCEX_Breadcrumbs_Shortcode {

		/**
		 * Main constructor
		 *
		 * @since 4.3
		 */
		public function __construct() {
			add_shortcode( 'vcex_breadcrumbs', array( $this, 'output' ) );
			vc_lean_map( 'vcex_breadcrumbs', array( $this, 'map' ) );
		}

		/**
		 * Shortcode output => Get template file and display shortcode
		 *
		 * @since 4.3
		 */
		public function output( $atts, $content = null ) {
			ob_start();
			include( locate_template( 'vcex_templates/vcex_breadcrumbs.php' ) );
			return ob_get_clean();
		}

		/**
		 * Map shortcode to VC
		 *
		 * @since 4.3
		 */
		public function map() {
			return array(
				'name' => __( 'Breadcrumbs', 'total' ),
				'description' => __( 'Display page breadcrumbs', 'total' ),
				'base' => 'vcex_breadcrumbs',
				'icon' => 'vcex-breadcrumbs vcex-icon ticon ticon-terminal',
				'category' => wpex_get_theme_branding(),
				'params' => array(
					array(
						'type' => 'vcex_visibility',
						'heading' => __( 'Visibility', 'total' ),
						'param_name' => 'visibility',
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Extra class name', 'total' ),
						'param_name' => 'el_class',
						'description' => __( 'Style particular content element differently - add a class name and refer to it in custom CSS.', 'total' ),
					),
					array(
						'type' => 'vcex_text_alignments',
						'heading' => __( 'Align', 'total' ),
						'param_name' => 'align',
					),
					array(
						'type' => 'colorpicker',
						'heading' => __( 'Color', 'total' ),
						'param_name' => 'color',
					),
					array(
						'type' => 'vcex_font_family_select',
						'heading' => __( 'Font Family', 'total' ),
						'param_name' => 'font_family',
					),
					array(
						'type' => 'vcex_responsive_sizes',
						'target' => 'font-size',
						'heading' => __( 'Font Size', 'total' ),
						'param_name' => 'font_size',
					),
					array(
						'type' => 'css_editor',
						'heading' => __( 'CSS', 'total' ),
						'param_name' => 'css',
						'group' => __( 'Design Options', 'total' ),
					),
				)
			);
		}
	}
}
new VCEX_Breadcrumbs_Shortcode;