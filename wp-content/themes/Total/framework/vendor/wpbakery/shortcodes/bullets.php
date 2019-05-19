<?php
/**
 * Visual Composer Bullets
 *
 * @package Total WordPress Theme
 * @subpackage VC Functions
 * @version 4.8
 */

if ( ! class_exists( 'VCEX_Bullets_Shortcode' ) ) {

	class VCEX_Bullets_Shortcode {

		/**
		 * Main constructor
		 *
		 * @since 3.5.0
		 */
		public function __construct() {
			add_shortcode( 'vcex_bullets', array( $this, 'output' ) );
			vc_lean_map( 'vcex_bullets', array( $this, 'map' ) );
		}

		/**
		 * Shortcode output => Get template file and display shortcode
		 *
		 * @since 3.5.0
		 */
		public function output( $atts, $content = null ) {
			ob_start();
			include( locate_template( 'vcex_templates/vcex_bullets.php' ) );
			return ob_get_clean();
		}

		/**
		 * Map shortcode to VC
		 *
		 * @since 3.5.0
		 */
		public function map() {
			return array(
				'name' => __( 'Bullets', 'total' ),
				'description' => __( 'Styled bulleted lists', 'total' ),
				'base' => 'vcex_bullets',
				'category' => wpex_get_theme_branding(),
				'icon' => 'vcex-bullets vcex-icon ticon ticon-dot-circle-o',
				'params' => array(
					array(
						'type' => 'textfield',
						'heading' => __( 'Unique Id', 'total' ),
						'param_name' => 'unique_id',
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Extra class name', 'total' ),
						'param_name' => 'classes',
						'description' => __( 'Style particular content element differently - add a class name and refer to it in custom CSS.', 'total' ),
					),
					array(
						'type' => 'vcex_visibility',
						'heading' => __( 'Visibility', 'total' ),
						'param_name' => 'visibility',
					),
					vcex_vc_map_add_css_animation(),
					array(
						'type' => 'textarea_html',
						'heading' => __( 'Insert Unordered List', 'total' ),
						'param_name' => 'content',
						'value' => '<ul><li>List 1</li><li>List 2</li><li>List 3</li><li>List 4</li></ul>',
					),
					// Icon
					array(
						'type' => 'vcex_ofswitch',
						'heading' => __( 'Enable Icon', 'total' ),
						'param_name' => 'has_icon',
						'admin_label' => true,
						'std' => 'true',
						'group' => __( 'Icon', 'total' ),
					),
					array(
						'type' => 'vcex_select_buttons',
						'heading' => __( 'Style', 'total' ),
						'param_name' => 'style',
						'admin_label' => true,
						'std' => 'check',
						'choices' => 'bullet_styles',
						'group' => __( 'Icon', 'total' ),
						'dependency' => array( 'element' => 'has_icon', 'value' => 'true' ),
					),
					array(
						'type' => 'vcex_icon_font_family',
						'heading' => __( 'Custom Icon', 'total' ),
						'param_name' => 'icon_type',
						'description' => __( 'Select icon library.', 'total' ),
						'group' => __( 'Icon', 'total' ),
						'dependency' => array( 'element' => 'has_icon', 'value' => 'true' ),
					),
					array(
						'type' => 'iconpicker',
						'heading' => __( 'Icon', 'total' ),
						'param_name' => 'icon',
						'value' => 'fa fa-info-circle',
						'settings' => array(
							'emptyIcon' => true,
							'iconsPerPage' => 4000,
						),
						'dependency' => array( 'element' => 'icon_type', 'value' => 'fontawesome' ),
						'group' => __( 'Icon', 'total' ),
					),
					array(
						'type' => 'iconpicker',
						'heading' => __( 'Icon', 'total' ),
						'param_name' => 'icon_openiconic',
						'std' => '',
						'settings' => array(
							'emptyIcon' => true,
							'type' => 'openiconic',
							'iconsPerPage' => 4000,
						),
						'dependency' => array( 'element' => 'icon_type', 'value' => 'openiconic' ),
						'group' => __( 'Icon', 'total' ),
					),
					array(
						'type' => 'iconpicker',
						'heading' => __( 'Icon', 'total' ),
						'param_name' => 'icon_typicons',
						'std' => '',
						'settings' => array(
							'emptyIcon' => true,
							'type' => 'typicons',
							'iconsPerPage' => 4000,
						),
						'dependency' => array( 'element' => 'icon_type', 'value' => 'typicons' ),
						'group' => __( 'Icon', 'total' ),
					),
					array(
						'type' => 'iconpicker',
						'heading' => __( 'Icon', 'total' ),
						'param_name' => 'icon_entypo',
						'std' => '',
						'settings' => array(
							'emptyIcon' => true,
							'type' => 'entypo',
							'iconsPerPage' => 4000,
						),
						'dependency' => array( 'element' => 'icon_type', 'value' => 'entypo' ),
						'group' => __( 'Icon', 'total' ),
					),
					array(
						'type' => 'iconpicker',
						'heading' => __( 'Icon', 'total' ),
						'param_name' => 'icon_linecons',
						'std' => '',
						'settings' => array(
							'emptyIcon' => true,
							'type' => 'linecons',
							'iconsPerPage' => 4000,
						),
						'dependency' => array( 'element' => 'icon_type', 'value' => 'linecons' ),
						'group' => __( 'Icon', 'total' ),
					),
					array(
						'type' => 'colorpicker',
						'heading' => __( 'Icon Color', 'total' ),
						'param_name' => 'icon_color',
						'dependency' => array( 'element' => 'icon_type', 'not_empty' => true ),
						'group' => __( 'Icon', 'total' ),
					),

					// Typography
					array(
						'type' => 'vcex_font_family_select',
						'heading' => __( 'Font Family', 'total' ),
						'param_name' => 'font_family',
						'group' => __( 'Typography', 'total' ),
					),
					array(
						'type' => 'colorpicker',
						'heading' => __( 'Color', 'total' ),
						'param_name' => 'color',
						'group' => __( 'Typography', 'total' ),
					),
					array(
						'type' => 'vcex_responsive_sizes',
						'target' => 'font-size',
						'heading' => __( 'Font Size', 'total' ),
						'param_name' => 'font_size',
						'group' => __( 'Typography', 'total' ),
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Line Height', 'total' ),
						'param_name' => 'line_height',
						'group' => __( 'Typography', 'total' ),
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Letter Spacing', 'total' ),
						'param_name' => 'letter_spacing',
						'group' => __( 'Typography', 'total' ),
					),
					array(
						'type' => 'vcex_font_weight',
						'heading' => __( 'Font Weight', 'total' ),
						'param_name' => 'font_weight',
						'std' => '',
						'group' => __( 'Typography', 'total' ),
					),
				)
			);
		}

	}
}
new VCEX_Bullets_Shortcode;