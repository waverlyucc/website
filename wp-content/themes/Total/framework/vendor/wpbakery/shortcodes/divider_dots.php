<?php
/**
 * Visual Composer Divider: Dots
 *
 * @package Total WordPress Theme
 * @subpackage VC Functions
 * @version 4.8
 */

if ( ! class_exists( 'VCEX_Divider_Dots_Shortcode' ) ) {

	class VCEX_Divider_Dots_Shortcode {

		/**
		 * Main constructor
		 *
		 * @since 3.5.0
		 */
		public function __construct() {
			
			// Add shortcode
			add_shortcode( 'vcex_divider_dots', array( $this, 'output' ) );

			// Map to VC
			vc_lean_map( 'vcex_divider_dots', array( $this, 'map' ) );

			// Parse attributes on form open
			add_filter( 'vc_edit_form_fields_attributes_vcex_divider_dots', array( $this, 'edit_form_fields' ) );

			// Parse attributes on front-end
			add_filter( 'shortcode_atts_vcex_divider_dots', array( $this, 'parse_attributes' ), 99 );

		}

		/**
		 * Shortcode output => Get template file and display shortcode
		 *
		 * @since 3.5.0
		 */
		public function output( $atts, $content = null ) {
			ob_start();
			include( locate_template( 'vcex_templates/vcex_divider_dots.php' ) );
			return ob_get_clean();
		}

		/**
		 * Map shortcode to VC
		 *
		 * @since 3.5.0
		 */
		public function map() {
			return array(
				'name' => __( 'Divider Dots', 'total' ),
				'description' => __( 'Dot Separator', 'total' ),
				'base' => 'vcex_divider_dots',
				'icon' => 'vcex-dots vcex-icon ticon ticon-ellipsis-h',
				'category' => wpex_get_theme_branding(),
				'params' => array(
					// General
					array(
						'type' => 'vcex_visibility',
						'heading' => __( 'Visibility', 'total' ),
						'param_name' => 'visibility',
					),
					vcex_vc_map_add_css_animation(),
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
						'type' => 'textfield',
						'heading' => __( 'Count', 'total' ),
						'param_name' => 'count',
						'value' => '3',
						'admin_label' => true,
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Size', 'total' ),
						'param_name' => 'size',
						'description' => __( 'Default', 'total' ) . ': 5px',
					),
					array(
						'type' => 'colorpicker',
						'heading' => __( 'Color', 'total' ),
						'param_name' => 'color',
					),
					array(
						'type' => 'vcex_trbl',
						'heading' => __( 'Margin', 'total' ),
						'param_name' => 'margin',
					),
					// Hidden Removed attributes
					array( 'type' => 'hidden', 'param_name' => 'margin_top' ),
					array( 'type' => 'hidden', 'param_name' => 'margin_bottom' ),
				),
			);
		}

		/**
		 * Edit form fields
		 *
		 * @since 4.3
		 */
		public function edit_form_fields( $atts ) {

			// Parse old margin settings
			if ( empty( $atts['margin'] ) && ( $atts['margin_top'] || $atts['margin_bottom'] ) ) {
				$atts['margin'] = vcex_combine_trbl_fields( $atts['margin_top'], '', $atts['margin_bottom'], '' );
				unset( $atts['margin_top'] );
				unset( $atts['margin_bottom'] );
			}

			// Return $atts
			return $atts;

		}

		/**
		 * Parse VC row attributes on front-end
		 *
		 * @since 4.3
		 */
		public function parse_attributes( $atts ) {
			
			// Parse old margin settings
			if ( empty( $atts['margin'] ) && ( $atts['margin_top'] || $atts['margin_bottom'] ) ) {
				$atts['margin'] = vcex_combine_trbl_fields( $atts['margin_top'], '', $atts['margin_bottom'], '' );
			}

			// Return attributes
			return $atts;

		}

	}
}
new VCEX_Divider_Dots_Shortcode;