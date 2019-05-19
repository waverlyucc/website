<?php
/**
 * Visual Composer Callout
 *
 * @package Total WordPress Theme
 * @subpackage VC Functions
 * @version 4.8
 */

if ( ! class_exists( 'VCEX_Callout_Shortcode' ) ) {

	class VCEX_Callout_Shortcode {

		/**
		 * Main constructor
		 *
		 * @since 3.5.0
		 */
		public function __construct() {
			add_shortcode( 'vcex_callout', array( $this, 'output' ) );
			vc_lean_map( 'vcex_callout', array( $this, 'map' ) );
		}

		/**
		 * Shortcode output
		 *
		 * @since 3.5.0
		 */
		public function output( $atts, $content = null ) {
			ob_start();
			include( locate_template( 'vcex_templates/vcex_callout.php' ) );
			return ob_get_clean();
		}

		/**
		 * Map shortcode to VC
		 *
		 * @since 3.5.0
		 */
		public function map() {
			return array(
				'name' => __( 'Callout', 'total' ),
				'description' => __( 'Call to action section with or without button', 'total' ),
				'base' => 'vcex_callout',
				'icon' => 'vcex-callout vcex-icon ticon ticon-bullhorn',
				'category' => wpex_get_theme_branding(),
				'params' => array(
					array(
						'type' => 'textfield',
						'admin_label' => true,
						'heading' => __( 'Unique Id', 'total' ),
						'description' => __( 'Give your main element a unique ID.', 'total' ),
						'param_name' => 'unique_id',
					),
					array(
						'type' => 'textfield',
						'admin_label' => true,
						'heading' => __( 'Classes', 'total' ),
						'description' => __( 'Add additonal classes to the main element.', 'total' ),
						'param_name' => 'classes',
					),
					array(
						'type' => 'vcex_visibility',
						'heading' => __( 'Visibility', 'total' ),
						'param_name' => 'visibility',
					),
					vcex_vc_map_add_css_animation(),
					// Content
					array(
						'type' => 'textfield',
						'heading' => __( 'Custom Content Area Width', 'total' ),
						'param_name' => 'content_width',
						'group' => __( 'Content', 'total' ),
						'description' => __( 'Preferably a percentage value to prevent issues with responsiveness.', 'total' ),
					),
					array(
						'type' => 'textarea_html',
						'holder' => 'div',
						'class' => 'vcex-callout',
						'heading' => __( 'Content', 'total' ),
						'param_name' => 'content',
						'value' => 'Curabitur et suscipit tellus, quis dapibus nisl. Duis ultrices faucibus sapien, vel hendrerit est scelerisque vel.',
						'group' => __( 'Content', 'total' ),
					),
					array(
						'type' => 'colorpicker',
						'heading' => __( 'Color', 'total' ),
						'param_name' => 'content_color',
						'group' => __( 'Content', 'total' ),
					),
					array(
						'type' => 'vcex_font_family_select',
						'heading' => __( 'Font Family', 'total' ),
						'param_name' => 'content_font_family',
						'group' => __( 'Content', 'total' ),
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Font Size', 'total' ),
						'param_name' => 'content_font_size',
						'group' => __( 'Content', 'total' ),
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Letter Spacing', 'total' ),
						'param_name' => 'content_letter_spacing',
						'group' => __( 'Content', 'total' ),
					),
					array(
						'type' => 'vcex_font_weight',
						'heading' => __( 'Font Weight', 'total' ),
						'param_name' => 'content_font_weight',
						'group' => __( 'Content', 'total' ),
					),
					// Button
					array(
						'type' => 'textfield',
						'heading' => __( 'URL', 'total' ),
						'param_name' => 'button_url',
						'group' => __( 'Button', 'total' ),
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Text', 'total' ),
						'param_name' => 'button_text',
						'group' => __( 'Button', 'total' ),
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Custom Button Area Width', 'total' ),
						'param_name' => 'button_width',
						'group' => __( 'Button', 'total' ),
						'description' => __( 'Preferably a percentage value to prevent issues with responsiveness.', 'total' ),
					),
					array(
						'type' => 'vcex_ofswitch',
						'std' => 'false',
						'heading' => __( 'Button Full-Width', 'total' ),
						'param_name' => 'button_full_width',
						'group' => __( 'Button', 'total' ),
					),
					array(
						'type' => 'vcex_text_alignments',
						'heading' => __( 'Button Align', 'total' ),
						'param_name' => 'button_align',
						'dependency' => array( 'element' => 'button_full_width', 'value' => 'false' ),
						'group' => __( 'Button', 'total' ),
					),
					array(
						'type' => 'vcex_button_styles',
						'heading' => __( 'Button Style', 'total' ),
						'param_name' => 'button_style',
						'group' => __( 'Button', 'total' ),
					),
					array(
						'type' => 'vcex_button_colors',
						'heading' => __( 'Color', 'total' ),
						'param_name' => 'button_color',
						'group' => __( 'Button', 'total' ),
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Border Radius', 'total' ),
						'param_name' => 'button_border_radius',
						'description' => __( 'Please enter a px value.', 'total' ),
						'group' => __( 'Button', 'total' ),
					),
					array(
						'type' => 'vcex_select_buttons',
						'heading' => __( 'Link Target', 'total' ),
						'param_name' => 'button_target',
						'std' => '',
						'choices' => array(
							'' => __( 'Self', 'total' ),
							'blank' => __( 'Blank', 'total' ),
							'local' => __( 'Local', 'total' ),
						),
						'group' => __( 'Button', 'total' ),
					),
					array(
						'type' => 'vcex_select_buttons',
						'heading' => __( 'Rel', 'total' ),
						'param_name' => 'button_rel',
						'std' => '',
						'choices' => array(
							'' => __( 'None', 'total' ),
							'nofollow' => __( 'Nofollow', 'total' ),
						),
						'group' => __( 'Button', 'total' ),
					),
					// Button styling
					array(
						'type' => 'colorpicker',
						'heading' => __( 'Background', 'total' ),
						'param_name' => 'button_custom_background',
						'group' => __( 'Button', 'total' ),
					),
					array(
						'type' => 'colorpicker',
						'heading' => __( 'Background: Hover', 'total' ),
						'param_name' => 'button_custom_hover_background',
						'group' => __( 'Button', 'total' ),
					),
					array(
						'type' => 'colorpicker',
						'heading' => __( 'Color', 'total' ),
						'param_name' => 'button_custom_color',
						'group' => __( 'Button', 'total' ),
					),
					array(
						'type' => 'colorpicker',
						'heading' => __( 'Color: Hover', 'total' ),
						'param_name' => 'button_custom_hover_color',
						'group' => __( 'Button', 'total' ),
					),
					array(
						'type' => 'vcex_trbl',
						'heading' => __( 'Padding', 'total' ),
						'param_name' => 'button_padding',
						'group' => __( 'Button', 'total' ),
					),
					// Button Typography
					array(
						'type' => 'vcex_font_family_select',
						'heading' => __( 'Font Family', 'total' ),
						'param_name' => 'button_font_family',
						'group' => __( 'Button', 'total' ),
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Font Size', 'total' ),
						'param_name' => 'button_font_size',
						'group' => __( 'Button', 'total' ),
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Letter Spacing', 'total' ),
						'param_name' => 'button_letter_spacing',
						'group' => __( 'Button', 'total' ),
					),
					array(
						'type' => 'vcex_font_weight',
						'heading' => __( 'Font Weight', 'total' ),
						'param_name' => 'button_font_weight',
						'group' => __( 'Button', 'total' ),
					),
					// Button Icons
					array(
						'type' => 'dropdown',
						'heading' => __( 'Icon library', 'total' ),
						'param_name' => 'icon_type',
						'description' => __( 'Select icon library.', 'total' ),
						'std' => 'fontawesome',
						'value' => array(
							__( 'Font Awesome', 'total' ) => 'fontawesome',
							__( 'Open Iconic', 'total' ) => 'openiconic',
							__( 'Typicons', 'total' ) => 'typicons',
							__( 'Entypo', 'total' ) => 'entypo',
							__( 'Linecons', 'total' ) => 'linecons',
							__( 'Pixel', 'total' ) => 'pixelicons',
						),
						'group' => __( 'Button', 'total' ),
					),
					array(
						'type' => 'iconpicker',
						'heading' => __( 'Icon Left', 'total' ),
						'param_name' => 'button_icon_left',
						'settings' => array(
							'emptyIcon' => true,
							'iconsPerPage' => 200,
						),
						'dependency' => array( 'element' => 'icon_type', 'value' => 'fontawesome' ),
						'group' => __( 'Button', 'total' ),
					),
					array(
						'type' => 'iconpicker',
						'heading' => __( 'Icon Left', 'total' ),
						'param_name' => 'button_icon_left_openiconic',
						'settings' => array(
							'emptyIcon' => true,
							'type' => 'openiconic',
							'iconsPerPage' => 200,
						),
						'dependency' => array( 'element' => 'icon_type', 'value' => 'openiconic' ),
						'group' => __( 'Button', 'total' ),
					),
					array(
						'type' => 'iconpicker',
						'heading' => __( 'Icon Left', 'total' ),
						'param_name' => 'button_icon_left_typicons',
						'settings' => array(
							'emptyIcon' => true,
							'type' => 'typicons',
							'iconsPerPage' => 200,
						),
						'dependency' => array( 'element' => 'icon_type', 'value' => 'typicons' ),
						'group' => __( 'Button', 'total' ),
					),
					array(
						'type' => 'iconpicker',
						'heading' => __( 'Icon Left', 'total' ),
						'param_name' => 'button_icon_left_entypo',
						'settings' => array(
							'emptyIcon' => true,
							'type' => 'entypo',
							'iconsPerPage' => 300,
						),
						'dependency' => array( 'element' => 'icon_type', 'value' => 'entypo' ),
						'group' => __( 'Button', 'total' ),
					),
					array(
						'type' => 'iconpicker',
						'heading' => __( 'Icon Left', 'total' ),
						'param_name' => 'button_icon_left_linecons',
						'settings' => array(
							'emptyIcon' => true,
							'type' => 'linecons',
							'iconsPerPage' => 200,
						),
						'dependency' => array( 'element' => 'icon_type', 'value' => 'linecons' ),
						'group' => __( 'Button', 'total' ),
					),
					array(
						'type' => 'iconpicker',
						'heading' => __( 'Icon Right', 'total' ),
						'param_name' => 'button_icon_right',
						'settings' => array(
							'emptyIcon' => true,
							'iconsPerPage' => 200,
						),
						'dependency' => array( 'element' => 'icon_type', 'value' => 'fontawesome' ),
						'group' => __( 'Button', 'total' ),
					),
					array(
						'type' => 'iconpicker',
						'heading' => __( 'Icon Right', 'total' ),
						'param_name' => 'button_icon_right_openiconic',
						'settings' => array(
							'emptyIcon' => true,
							'type' => 'openiconic',
							'iconsPerPage' => 200,
						),
						'dependency' => array( 'element' => 'icon_type', 'value' => 'openiconic' ),
						'group' => __( 'Button', 'total' ),
					),
					array(
						'type' => 'iconpicker',
						'heading' => __( 'Icon Right', 'total' ),
						'param_name' => 'button_icon_right_typicons',
						'settings' => array(
							'emptyIcon' => true,
							'type' => 'typicons',
							'iconsPerPage' => 200,
						),
						'dependency' => array( 'element' => 'icon_type', 'value' => 'typicons' ),
						'group' => __( 'Button', 'total' ),
					),
					array(
						'type' => 'iconpicker',
						'heading' => __( 'Icon Right', 'total' ),
						'param_name' => 'button_icon_right_entypo',
						'settings' => array(
							'emptyIcon' => true,
							'type' => 'entypo',
							'iconsPerPage' => 300,
						),
						'dependency' => array( 'element' => 'icon_type', 'value' => 'entypo' ),
						'group' => __( 'Button', 'total' ),
					),
					array(
						'type' => 'iconpicker',
						'heading' => __( 'Icon Right', 'total' ),
						'param_name' => 'button_icon_right_linecons',
						'settings' => array(
							'emptyIcon' => true,
							'type' => 'linecons',
							'iconsPerPage' => 200,
						),
						'dependency' => array( 'element' => 'icon_type', 'value' => 'linecons' ),
						'group' => __( 'Button', 'total' ),
					),
					// Design Options
					array(
						'type' => 'css_editor',
						'heading' => __( 'CSS', 'total' ),
						'param_name' => 'css',
						'group' => __( 'Design options', 'total' ),
					),
				)
			);
		}

	}
}
new VCEX_Callout_Shortcode;