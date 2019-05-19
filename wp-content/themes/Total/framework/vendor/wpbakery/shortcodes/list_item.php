<?php
/**
 * Visual Composer List Item
 *
 * @package Total WordPress Theme
 * @subpackage VC Functions
 * @version 4.8
 */

if ( ! class_exists( 'VCEX_List_item_Shortcode' ) ) {

	class VCEX_List_item_Shortcode {

		/**
		 * Main constructor
		 *
		 * @since 3.5.0
		 */
		public function __construct() {
			add_shortcode( 'vcex_list_item', array( $this, 'output' ) );
			vc_lean_map( 'vcex_list_item', array( $this, 'map' ) );
		}

		/**
		 * Shortcode output => Get template file and display shortcode
		 *
		 * @since 3.5.0
		 */
		public function output( $atts, $content = null ) {
			ob_start();
			include( locate_template( 'vcex_templates/vcex_list_item.php' ) );
			return ob_get_clean();
		}

		/**
		 * Map shortcode to VC
		 *
		 * @since 3.5.0
		 */
		public function map() {
			return array(
				'name' => __( 'List Item', 'total' ),
				'description' => __( 'Font Icon list item', 'total' ),
				'base' => 'vcex_list_item',
				'icon' => 'vcex-list-item vcex-icon ticon ticon-list',
				'category' => wpex_get_theme_branding(),
				'params' => array(
					// General
					array(
						'type' => 'dropdown',
						'heading' => __( 'Text Source', 'total' ),
						'param_name' => 'text_source',
						'value' => array(
							__( 'Custom Text', 'total' ) => 'custom_text',
							__( 'Custom Field', 'total' ) => 'custom_field',
							__( 'Callback Function', 'total' ) => 'callback_function',
						),
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Text', 'total' ),
						'param_name' => 'content',
						'admin_label' => true,
						'value' => __( 'This is a pretty list item', 'total' ),
						'dependency' => array( 'element' => 'text_source', 'value' => 'custom_text' ),
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Custom Field ID', 'total' ),
						'param_name' => 'text_custom_field',
						'dependency' => array( 'element' => 'text_source', 'value' => 'custom_field' ),
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Callback Function', 'total' ),
						'param_name' => 'text_callback_function',
						'dependency' => array( 'element' => 'text_source', 'value' => 'callback_function' ),
					),
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
					// Typography
					array(
						'type' => 'vcex_text_alignments',
						'heading' => __( 'Text Align', 'total' ),
						'param_name' => 'text_align',
						'group' => __( 'Typography', 'total' ),
					),
					array(
						'type'  => 'vcex_font_family_select',
						'heading'  => __( 'Font Family', 'total' ),
						'param_name'  => 'font_family',
						'group' => __( 'Typography', 'total' ),
					),
					array(
						'type' => 'vcex_font_weight',
						'heading' => __( 'Font Weight', 'total' ),
						'param_name' => 'font_weight',
						'group' => __( 'Typography', 'total' ),
					),
					array(
						'type' => 'colorpicker',
						'heading' => __( 'Color', 'total' ),
						'param_name' => 'font_color',
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
						'type' => 'vcex_ofswitch',
						'std' => 'false',
						'heading' => __( 'Auto Responsive Font Size', 'total' ),
						'param_name' => 'responsive_font_size',
						'group' => __( 'Typography', 'total' ),
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Minimum Font Size', 'total' ),
						'param_name' => 'min_font_size',
						'dependency' => array( 'element' => 'responsive_font_size', 'value' => 'true' ),
						'group' => __( 'Typography', 'total' ),
					),
					array(
						'type' => 'vcex_select_buttons',
						'heading' => __( 'Font Style', 'total' ),
						'param_name' => 'font_style',
						'std' => '',
						'choices' => 'font_style',
						'group' => __( 'Typography', 'total' ),
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
						'value' => 'fa fa-info-circle',
						'settings' => array(
							'emptyIcon' => true,
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
						'type' => 'textfield',
						'heading' => __( 'Icon Alternative Classes', 'total' ),
						'param_name' => 'icon_alternative_classes',
						'group' => __( 'Icon', 'total' ),
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Side Margin', 'total' ),
						'param_name' => 'margin_right',
						'group' => __( 'Icon', 'total' ),
					),
					array(
						'type' => 'colorpicker',
						'heading' => __( 'Color', 'total' ),
						'param_name' => 'color',
						'group' => __( 'Icon', 'total' ),
					),
					array(
						'type' => 'colorpicker',
						'heading' => __( 'Background', 'total' ),
						'param_name' => 'icon_background',
						'group' => __( 'Icon', 'total' ),
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Size', 'total' ),
						'param_name' => 'icon_size',
						'group' => __( 'Icon', 'total' ),
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Border Radius', 'total' ),
						'param_name' => 'icon_border_radius',
						'group' => __( 'Icon', 'total' ),
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Width', 'total' ),
						'param_name' => 'icon_width',
						'group' => __( 'Icon', 'total' ),
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Height', 'total' ),
						'param_name' => 'icon_height',
						'group' => __( 'Icon', 'total' ),
					),
					// Link
					array(
						'type' => 'vc_link',
						'heading' => __( 'Link', 'total' ),
						'param_name' => 'link',
						'group' => __( 'Link', 'total' ),
					),
					// CSS
					array(
						'type' => 'css_editor',
						'heading' => __( 'CSS', 'total' ),
						'param_name' => 'css',
						'group' => __( 'Design', 'total' ),
					),
				)
			);
		}

	}
}
new VCEX_List_item_Shortcode;