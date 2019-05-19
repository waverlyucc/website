<?php
/**
 * Visual Composer Navbar
 *
 * @package Total WordPress Theme
 * @subpackage Visual Composer
 * @version 4.8
 */

if ( ! class_exists( 'VCEX_Navbar_Shortcode' ) ) {

	class VCEX_Navbar_Shortcode {

		/**
		 * Main constructor
		 *
		 * @since 3.5.0
		 */
		public function __construct() {
			add_shortcode( 'vcex_navbar', array( $this, 'output' ) );
			vc_lean_map( 'vcex_navbar', array( $this, 'map' ) );
			add_filter( 'vc_edit_form_fields_attributes_vcex_navbar', array( $this, 'edit_fields' ) );
		}

		/**
		 * Shortcode output => Get template file and display shortcode
		 *
		 * @since 3.5.0
		 */
		public function output( $atts, $content = null ) {
			ob_start();
			include( locate_template( 'vcex_templates/vcex_navbar.php' ) );
			return ob_get_clean();
		}

		/**
		 * Edif form fields
		 *
		 * @since 3.5.0
		 */
		public function edit_fields( $atts ) {
			if ( isset( $atts['style'] ) && 'simple' == $atts['style'] ) {
				$atts['button_style'] = 'plain-text';
				unset( $atts['style'] );
			}
			return $atts;
		}

		/**
		 * Map shortcode to VC
		 *
		 * @since 3.5.0
		 */
		public function map() {
			return array(
				'name' => __( 'Navigation Bar', 'total' ),
				'description' => __( 'Custom menu navigation bar', 'total' ),
				'base' => 'vcex_navbar',
				'icon' => 'vcex-navbar vcex-icon ticon ticon-navicon',
				'category' => wpex_get_theme_branding(),
				'params' => array(
					// General
					array(
						'type' => 'textfield',
						'heading' => __( 'Unique Id', 'total' ),
						'param_name' => 'unique_id',
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Extra class name', 'total' ),
						'description' => __( 'Style particular content element differently - add a class name and refer to it in custom CSS.', 'total' ),
						'param_name' => 'classes',
					),
					vcex_vc_map_add_css_animation(),
					array(
						'type' => 'vcex_visibility',
						'heading' => __( 'Visibility', 'total' ),
						'param_name' => 'visibility',
					),
					// Menu
					array(
						'type' => 'vcex_menus_select',
						'admin_label' => true,
						'heading' => __( 'Menu', 'total' ),
						'param_name' => 'menu',
						'save_always' => true,
						'group' => __( 'Menu', 'total' ),
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Post Filter Grid ID', 'total' ),
						'param_name' => 'filter_menu',
						'description' => __( 'Enter the "Unique Id" of the post grid module you wish to filter. This will only work on the theme specific grids. Make sure the filter on the grid module is disabled to prevent conflicts. View theme docs for more info.', 'total' ),
						'group' => __( 'Menu', 'total' ),
					),
					array(
						'type' => 'vcex_ofswitch',
						'std' => 'false',
						'heading' => __( 'Local Scroll menu', 'total'),
						'param_name' => 'local_scroll',
						'group' => __( 'Menu', 'total' ),
					),
					array(
						'type' => 'vcex_ofswitch',
						'std' => 'false',
						'heading' => __( 'Sticky', 'total'),
						'param_name' => 'sticky',
						'group' => __( 'Menu', 'total' ),
						'description' => __( 'Note: Sticky is disabled in the front-end editor.', 'total' ),
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Sticky Endpoint', 'total'),
						'param_name' => 'sticky_endpoint',
						'group' => __( 'Menu', 'total' ),
						'description' => __( 'Enter the ID or classname of an element that when reached will disable the stickiness. Example: #footer', 'total' ),
						'dependency' => array( 'element' => 'sticky', 'value' => 'true' ),
					),
					array(
						'type' => 'vcex_ofswitch',
						'std' => 'false',
						'heading' => __( 'Full-Screen Center', 'total'),
						'param_name' => 'full_screen_center',
						'description' => __( 'Center the navigation links when using the full-screen page layout', 'total' ),
						'group' => __( 'Menu', 'total' ),
					),
					// Filter
					array(
						'type' => 'vcex_select_buttons',
						'heading' => __( 'Layout Mode', 'total' ),
						'param_name' => 'filter_layout_mode',
						'std' => 'masonry',
						'choices' => 'filter_layout_mode',
						'group' => __( 'Filter', 'total' ),
						'dependency' => array( 'element' => 'filter_menu', 'not_empty' => true ),
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Custom Filter Speed', 'total' ),
						'param_name' => 'filter_transition_duration',
						'description' => __( 'Default is "0.4" seconds. Enter "0.0" to disable.', 'total' ),
						'group' => __( 'Filter', 'total' ),
						'dependency' => array( 'element' => 'filter_menu', 'not_empty' => true ),
					),
					// Design
					array(
						'type' => 'dropdown',
						'heading' => __( 'Preset', 'total' ),
						'param_name' => 'preset_design',
						'value' => array(
							__( 'None', 'total' ) => 'none',
							__( 'Dark', 'total' ) => 'dark',
						),
						'group' => __( 'Design', 'total' ),
					),
					array(
						'type' => 'vcex_text_alignments',
						'heading' => __( 'Alignment', 'total' ),
						'param_name' => 'align',
						'group' => __( 'Design', 'total' ),
					),
					array(
						'type' => 'vcex_hover_animations',
						'heading' => __( 'Hover Animation', 'total'),
						'param_name' => 'hover_animation',
						'group' => __( 'Design', 'total' ),
					),
					array(
						'type' => 'vcex_button_styles',
						'heading' => __( 'Button Style', 'total' ),
						'param_name' => 'button_style',
						'group' => __( 'Design', 'total' ),
						'std' => 'minimal-border',
						'dependency' => array( 'element' => 'preset_design', 'value' => 'none' ),
					),
					array(
						'type' => 'vcex_button_colors',
						'heading' => __( 'Button Color', 'total' ),
						'param_name' => 'button_color',
						'group' => __( 'Design', 'total' ),
						'dependency' => array( 'element' => 'preset_design', 'value' => 'none' ),
					),
					array(
						'type' => 'vcex_select_buttons',
						'heading' => __( 'Layout', 'total' ),
						'param_name' => 'button_layout',
						'std' => '',
						'choices' => array(
							'' => __( 'Default', 'total' ),
							'list' => __( 'List', 'total' ),
							'expanded' => __( 'Expanded', 'total' ),
						),
						'group' => __( 'Design', 'total' ),
						'dependency' => array( 'element' => 'preset_design', 'value' => 'none' ),
					),
					array(
						'type' => 'colorpicker',
						'heading' => __( 'Color', 'total' ),
						'param_name' => 'color',
						'group' => __( 'Design', 'total' ),
						'dependency' => array( 'element' => 'preset_design', 'value' => 'none' ),
					),
					array(
						'type' => 'colorpicker',
						'heading' => __( 'Background', 'total' ),
						'param_name' => 'background',
						'group' => __( 'Design', 'total' ),
						'dependency' => array( 'element' => 'preset_design', 'value' => 'none' ),
					),
					array(
						'type' => 'colorpicker',
						'heading' => __( 'Color: Hover', 'total' ),
						'param_name' => 'hover_color',
						'group' => __( 'Design', 'total' ),
						'dependency' => array( 'element' => 'preset_design', 'value' => 'none' ),
					),
					array(
						'type' => 'colorpicker',
						'heading' => __( 'Background: Hover', 'total' ),
						'param_name' => 'hover_bg',
						'group' => __( 'Design', 'total' ),
						'dependency' => array( 'element' => 'preset_design', 'value' => 'none' ),
					),
					// Typography
					array(
						'type' => 'vcex_responsive_sizes',
						'target' => 'font-size',
						'heading' => __( 'Font Size', 'total' ),
						'param_name' => 'font_size',
						'group' => __( 'Typography', 'total' ),
					),
					array(
						'type' => 'vcex_font_family_select',
						'heading' => __( 'Font Family', 'total' ),
						'param_name' => 'font_family',
						'group' => __( 'Typography', 'total' ),
					),
					array(
						'type' => 'vcex_font_weight',
						'heading' => __( 'Font Weight', 'total' ),
						'param_name' => 'font_weight',
						'group' => __( 'Typography', 'total' ),
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Letter Spacing', 'total' ),
						'param_name' => 'letter_spacing',
						'group' => __( 'Typography', 'total' ),
					),
					// Advanced Styling
					array(
						'type' => 'css_editor',
						'heading' => __( 'Link CSS', 'total' ),
						'param_name' => 'css',
						'group' => __( 'Link CSS', 'total' ),
						'dependency' => array( 'element' => 'preset_design', 'value' => 'none' ),
					),
					array(
						'type' => 'css_editor',
						'heading' => __( 'Wrap CSS', 'total' ),
						'param_name' => 'wrap_css',
						'group' => __( 'Wrap CSS', 'total' ),
					),
					// Deprecated params
					array(
						'type' => 'hidden',
						'param_name' => 'style',
					),
					array(
						'type' => 'hidden',
						'param_name' => 'border_radius',
					),
				)
			);
		}

	}
}
new VCEX_Navbar_Shortcode;