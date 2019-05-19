<?php
/**
 * Visual Composer Heading
 *
 * @package Total WordPress Theme
 * @subpackage VC Functions
 * @version 4.8
 */

if ( ! class_exists( 'VCEX_Heading_Shortcode' ) ) {

	class VCEX_Heading_Shortcode {

		/**
		 * Main constructor
		 *
		 * @since 3.5.0
		 */
		public function __construct() {
			add_action( 'admin_print_scripts', array( $this, 'admin_print_scripts' ), 999 );
			add_shortcode( 'vcex_heading', array( $this, 'output' ) );
			vc_lean_map( 'vcex_heading', array( $this, 'map' ) );
		}

		/**
		 * Adds scripts for custom module view
		 *
		 * @since 4.4.1
		 */
		public function admin_print_scripts() {
			if ( 'post' != get_current_screen()->base ) {
				return false;
			}
			wp_enqueue_script(
				'vcex-js-view',
				wpex_asset_url( 'js/dynamic/wpbakery/vcex-js-view.min.js' ),
				array( 'jquery' ),
				WPEX_THEME_VERSION,
				true
			);
		}

		/**
		 * Shortcode output => Get template file and display shortcode
		 *
		 * @since 3.5.0
		 */
		public function output( $atts, $content = null ) {
			ob_start();
			include( locate_template( 'vcex_templates/vcex_heading.php' ) );
			return ob_get_clean();
		}

		/**
		 * Map shortcode to VC
		 *
		 * @since 3.5.0
		 */
		public function map() {
			return array(
				'name' => __( 'Heading', 'total' ),
				'description' => __( 'A better heading module', 'total' ),
				'base' => 'vcex_heading',
				'category' => wpex_get_theme_branding(),
				'icon' => 'vcex-heading vcex-icon ticon ticon-font',
				'js_view' => 'VcexHeadingView',
				'params' => array(
					// General
					array(
						'type' => 'dropdown',
						'heading' => __( 'Text Source', 'total' ),
						'param_name' => 'source',
						'value' => array(
							__( 'Custom Text', 'total' ) => 'custom',
							__( 'Post or Page Title', 'total' ) => 'post_title',
							__( 'Post Publish Date', 'total' ) => 'post_date',
							__( 'Post Modified Date', 'total' ) => 'post_modified_date',
							__( 'Post Author', 'total' ) => 'post_author',
							__( 'Current User', 'total' ) => 'current_user',
							__( 'Custom Field', 'total' ) => 'custom_field',
							__( 'Callback Function', 'total' ) => 'callback_function',
						),
						'admin_label' => true,
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Custom Field ID', 'total' ),
						'param_name' => 'custom_field',
						'dependency' => array( 'element' => 'source', 'value' => 'custom_field' ),
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Callback Function', 'total' ),
						'param_name' => 'callback_function',
						'dependency' => array( 'element' => 'source', 'value' => 'callback_function' ),
					),
					array(
						'type' => 'textarea_safe',
						'heading' => __( 'Text', 'total' ),
						'param_name' => 'text',
						'value' => __( 'Heading', 'total' ),
						'vcex_rows' => 2,
						'description' => __( 'HTML Supported', 'total' ),
						'dependency' => array( 'element' => 'source', 'value' => 'custom' ),
					),
					array(
						'type' => 'vcex_select_buttons',
						'heading' => __( 'Style', 'total' ),
						'param_name' => 'style',
						'std' => 'plain',
						'choices' => array(
							'plain' => __( 'Plain', 'total' ),
							'bottom-border-w-color' => __( 'Border', 'total' ),
							'graphical' => __( 'Graphical', 'total' ),
						),
					),
					array(
						'type' => 'colorpicker',
						'heading' => __( 'Accent Border Color', 'total' ),
						'param_name' => 'inner_bottom_border_color',
						'dependency' => array( 'element' => 'style', 'value' => 'bottom-border-w-color' ),
					),
					array(
						'type' => 'colorpicker',
						'heading' => __( 'Border Color', 'total' ),
						'param_name' => 'inner_bottom_border_color_main',
						'dependency' => array( 'element' => 'style', 'value' => 'bottom-border-w-color' ),
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Width', 'total' ),
						'param_name' => 'width',
						'description' => __( 'Enter a custom width instead of using breaks to slim down your content width.', 'total' ),
					),
					array(
						'type' => 'vcex_text_alignments',
						'heading' => __( 'Module Align', 'total' ),
						'param_name' => 'align',
						'std' => '',
						'dependency' => array( 'element' => 'width', 'not_empty' => true ),
					),
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
					vcex_vc_map_add_css_animation(),
					// Typography
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
						'type' => 'vcex_text_transforms',
						'heading' => __( 'Text Transform', 'total' ),
						'param_name' => 'text_transform',
						'group' => __( 'Typography', 'total' ),
					),
					array(
						'type' => 'vcex_text_alignments',
						'heading' => __( 'Text Align', 'total' ),
						'param_name' => 'text_align',
						'group' => __( 'Typography', 'total' ),
					),
					array(
						'heading' => __( 'Tag', 'total' ),
						'param_name' => 'tag',
						'type' => 'vcex_select_buttons',
						'std' => 'div',
						'choices' => 'html_tag',
						'group' => __( 'Typography', 'total' ),
					),
					array(
						'type' => 'colorpicker',
						'heading' => __( 'Color', 'total' ),
						'param_name' => 'color',
						'group' => __( 'Typography', 'total' ),
					),
					array(
						'type' => 'colorpicker',
						'heading' => __( 'Color: Hover', 'total' ),
						'param_name' => 'color_hover',
						'group' => __( 'Typography', 'total' ),
					),
					array(
						'type' => 'vcex_responsive_sizes',
						'heading' => __( 'Font Size', 'total' ),
						'param_name' => 'font_size',
						'description' => __( 'You can enter a px or em value. Example 13px or 1em.', 'total' ),
						'group' => __( 'Typography', 'total' ),
						'target' => 'font-size',
					),
					array(
						'type' => 'vcex_ofswitch',
						'std' => 'false',
						'heading' => __( 'Auto Responsive Font Size', 'total' ),
						'param_name' => 'responsive_text',
						'group' => __( 'Typography', 'total' ),
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Minimum Font Size', 'total' ),
						'param_name' => 'min_font_size',
						'dependency' => array( 'element' => 'responsive_text', 'value' => 'true' ),
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
						'type' => 'vcex_ofswitch',
						'std' => 'false',
						'heading' => __( 'Italic', 'total' ),
						'param_name' => 'italic',
						'group' => __( 'Typography', 'total' ),
					),
					// Link
					array(
						'type' => 'vc_link',
						'heading' => __( 'URL', 'total' ),
						'param_name' => 'link',
						'group' => __( 'Link', 'total' ),
					),
					array(
						'type' => 'vcex_ofswitch',
						'std' => 'false',
						'heading' => __( 'Link: Local Scroll', 'total' ),
						'param_name' => 'link_local_scroll',
						'group' => __( 'Link', 'total' ),
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
						'value' => '',
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
						'type' => 'vcex_select_buttons',
						'heading' => __( 'Position', 'total' ),
						'param_name' => 'icon_position',
						'std' => 'left',
						'choices' => array(
							'left' => '<span class="ticon ticon-align-left"></span>',
							'right' => '<span class="ticon ticon-align-right"></span>',
						),
						'group' => __( 'Icon', 'total' ),
					),
					array(
						'type' => 'colorpicker',
						'heading' => __( 'Color', 'total' ),
						'param_name' => 'icon_color',
						'group' => __( 'Icon', 'total' ),
					),

					// Design
					array(
						'type' => 'css_editor',
						'heading' => __( 'CSS', 'total' ),
						'param_name' => 'css',
						'group' => __( 'Design', 'total' ),
					),
					array(
						'type' => 'vcex_ofswitch',
						'std' => 'false',
						'heading' => __( 'Add Design to Inner Span', 'total' ),
						'param_name' => 'add_css_to_inner',
						'group' => __( 'Design', 'total' ),
						'description' => __( 'Enable to add the background, padding, border, etc only around your text and icons and not the whole heading container.', 'total' ),
						'dependency' => array( 'element' => 'style', 'value' => 'plain' ),
					),
					array(
						'type' => 'colorpicker',
						'heading' => __( 'Background: Hover', 'total' ),
						'param_name' => 'background_hover',
						'group' => __( 'Design', 'total' ),
						'dependency' => array( 'element' => 'style', 'value' => 'plain' ),
					),
					array(
						'type' => 'vcex_ofswitch',
						'heading' => __( 'White Text On Hover', 'total' ),
						'param_name' => 'hover_white_text',
						'std' => 'false',
						'group' => __( 'Design', 'total' ),
						'dependency' => array( 'element' => 'style', 'value' => 'plain' ),
					),
				)

			);
		}

	}
}
new VCEX_Heading_Shortcode;

class WPBakeryShortCode_vcex_heading extends WPBakeryShortCode {
	protected function outputTitle( $title ) {
		$icon = $this->settings( 'icon' );
		return '<h4 class="wpb_element_title"><i class="vc_general vc_element-icon' . ( ! empty( $icon ) ? ' ' . $icon : '' ) . '"></i><span class="vcex-heading-text">' . esc_html__( 'Heading', 'total' ) . '<span></span></span></span></h4>';
	}
}