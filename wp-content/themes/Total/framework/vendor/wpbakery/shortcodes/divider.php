<?php
/**
 * Visual Composer Divider
 *
 * @package Total WordPress Theme
 * @subpackage VC Functions
 * @version 4.8
 */

if ( ! class_exists( 'VCEX_Divider_Shortcode' ) ) {

	class VCEX_Divider_Shortcode {

		/**
		 * Main constructor
		 *
		 * @since 3.5.0
		 */
		public function __construct() {
			
			// Add shortcode
			add_shortcode( 'vcex_divider', array( $this, 'output' ) );

			// Map to VC
			vc_lean_map( 'vcex_divider', array( $this, 'map' ) );

			// Parse attributes on form open
			add_filter( 'vc_edit_form_fields_attributes_vcex_divider', array( $this, 'edit_form_fields' ) );

			// Parse attributes on front-end
			add_filter( 'shortcode_atts_vcex_divider', array( $this, 'parse_attributes' ), 99 );

		}

		/**
		 * Shortcode output => Get template file and display shortcode
		 *
		 * @since 3.5.0
		 */
		public function output( $atts, $content = null ) {
			ob_start();
			include( locate_template( 'vcex_templates/vcex_divider.php' ) );
			return ob_get_clean();
		}

		/**
		 * Map shortcode to VC
		 *
		 * @since 3.5.0
		 */
		public function map() {
			return array(
				'name' => __( 'Divider', 'total' ),
				'description' => __( 'Line Separator', 'total' ),
				'base' => 'vcex_divider',
				'icon' => 'vcex-divider vcex-icon ticon ticon-minus',
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
					// Design
					array(
						'type' => 'vcex_select_buttons',
						'admin_label' => true,
						'heading' => __( 'Style', 'total' ),
						'param_name' => 'style',
						'std' => 'solid',
						'choices' => array(
							'solid' => __( 'Solid', 'total' ),
							'dashed' => __( 'Dashed', 'total' ),
							'double' => __( 'Double', 'total' ),
							'dotted-line' => __( 'Dotted', 'total' ),
							'dotted' => __( 'Ben-Day', 'total' ),
						),
						'group' => __( 'Design', 'total' ),
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Width', 'total' ),
						'param_name' => 'width',
						'description' => __( 'Enter a pixel or percentage value.', 'total' ),
						'group' => __( 'Design', 'total' ),
					),
					array(
						'type' => 'vcex_text_alignments',
						'heading' => __( 'Align', 'total' ),
						'param_name' => 'align',
						'group' => __( 'Design', 'total' ),
						'dependency' => array( 'element' => 'width', 'not_empty' => true ),
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Height', 'total' ),
						'param_name' => 'height',
						'dependency' => array(
							'element' => 'style',
							'value' => array( 'solid', 'dashed', 'double', 'dotted-line' ),
						),
						'group' => __( 'Design', 'total' ),
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Height', 'total' ),
						'param_name' => 'dotted_height',
						'dependency' => array(
							'element' => 'style',
							'value' => 'dotted',
						),
						'group' => __( 'Design', 'total' ),
					),
					array(
						'type' => 'colorpicker',
						'heading' => __( 'Color', 'total' ),
						'param_name' => 'color',
						'value' => '',
						'dependency' => array(
							'element' => 'style',
							'value' => array( 'solid', 'dashed', 'double', 'dotted-line' ),
						),
						'group' => __( 'Design', 'total' ),
					),
					array(
						'type' => 'vcex_trbl',
						'heading' => __( 'Margin', 'total' ),
						'param_name' => 'margin',
						'group' => __( 'Design', 'total' ),
					),
					// Icon
					array(
						'type' => 'dropdown',
						'heading' => __( 'Icon library', 'total' ),
						'param_name' => 'icon_type',
						'description' => __( 'Select icon library.', 'total' ),
						'value' => array(
							__( 'Font Awesome', 'total' ) => 'fontawesome',
							__( 'Open Iconic', 'total' ) => 'openiconic',
							__( 'Typicons', 'total' ) => 'typicons',
							__( 'Entypo', 'total' ) => 'entypo',
							__( 'Linecons', 'total' ) => 'linecons',
							__( 'Pixel', 'total' ) => 'pixelicons',
						),
						'group' => __( 'Icon', 'total' ),
					),
					array(
						'type' => 'iconpicker',
						'heading' => __( 'Icon', 'total' ),
						'param_name' => 'icon',
						'std' => '',
						'settings' => array(
							'emptyIcon' => true,
							'type' => 'fontawesome',
							'iconsPerPage' => 200,
						),
						'dependency' => array( 'element' => 'icon_type', 'value' => 'fontawesome' ),
						'group' => __( 'Icon', 'total' ),
					),
					array(
						'type' => 'iconpicker',
						'heading' => __( 'Icon', 'total' ),
						'param_name' => 'icon_openiconic',
						'settings' => array(
							'emptyIcon' => true,
							'type' => 'openiconic',
							'iconsPerPage' => 200,
						),
						'dependency' => array( 'element' => 'icon_type', 'value' => 'openiconic' ),
						'group' => __( 'Icon', 'total' ),
					),
					array(
						'type' => 'iconpicker',
						'heading' => __( 'Icon', 'total' ),
						'param_name' => 'icon_typicons',
						'settings' => array(
							'emptyIcon' => true,
							'type' => 'typicons',
							'iconsPerPage' => 200,
						),
						'dependency' => array( 'element' => 'icon_type', 'value' => 'typicons' ),
						'group' => __( 'Icon', 'total' ),
					),
					array(
						'type' => 'iconpicker',
						'heading' => __( 'Icon', 'total' ),
						'param_name' => 'icon_entypo',
						'settings' => array(
							'emptyIcon' => true,
							'type' => 'entypo',
							'iconsPerPage' => 300,
						),
						'dependency' => array( 'element' => 'icon_type', 'value' => 'entypo' ),
						'group' => __( 'Icon', 'total' ),
					),
					array(
						'type' => 'iconpicker',
						'heading' => __( 'Icon', 'total' ),
						'param_name' => 'icon_linecons',
						'settings' => array(
							'emptyIcon' => true,
							'type' => 'linecons',
							'iconsPerPage' => 200,
						),
						'dependency' => array( 'element' => 'icon_type', 'value' => 'linecons' ),
						'group' => __( 'Icon', 'total' ),
					),
					array(
						'type' => 'iconpicker',
						'heading' => __( 'Icon', 'total' ),
						'param_name' => 'icon_pixelicons',
						'settings' => array(
							'emptyIcon' => true,
							'type' => 'pixelicons',
							'source' => vcex_pixel_icons(),
						),
						'dependency' => array( 'element' => 'icon_type', 'value' => 'pixelicons' ),
						'group' => __( 'Icon', 'total' ),
					),
					array(
						'type' => 'colorpicker',
						'heading' => __( 'Icon Color', 'total' ),
						'param_name' => 'icon_color',
						'group' => __( 'Icon', 'total' ),
					),
					array(
						'type' => 'colorpicker',
						'heading' => __( 'Icon Background', 'total' ),
						'param_name' => 'icon_bg',
						'group' => __( 'Icon', 'total' ),
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Icon Size', 'total' ),
						'param_name' => 'icon_size',
						'description' => __( 'You can use em or px values, but you must define them.', 'total' ),
						'group' => __( 'Icon', 'total' ),
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Icon Height', 'total' ),
						'param_name' => 'icon_height',
						'group' => __( 'Icon', 'total' ),
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Icon Width', 'total' ),
						'param_name' => 'icon_width',
						'group' => __( 'Icon', 'total' ),
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Icon Border Radius', 'total' ),
						'param_name' => 'icon_border_radius',
						'group' => __( 'Icon', 'total' ),
					),
					array(
						'type' => 'vcex_trbl',
						'heading' => __( 'Icon Padding', 'total' ),
						'param_name' => 'icon_padding',
						'group' => __( 'Icon', 'total' ),
					),
					// Hidden Removed attributes
					array( 'type' => 'hidden', 'param_name' => 'margin_top' ),
					array( 'type' => 'hidden', 'param_name' => 'margin_bottom' ),
				)
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
new VCEX_Divider_Shortcode;