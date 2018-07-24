<?php
/**
 * Visual Composer Milestone
 *
 * @package Total WordPress Theme
 * @subpackage VC Functions
 * @version 4.6.5
 */

if ( ! class_exists( 'VCEX_Milestone_Shortcode' ) ) {

	class VCEX_Milestone_Shortcode {

		/**
		 * Main constructor
		 *
		 * @since 3.5.0
		 */
		public function __construct() {
			add_shortcode( 'vcex_milestone', array( $this, 'output' ) );
			vc_lean_map( 'vcex_milestone', array( $this, 'map' ) );
		}

		/**
		 * Enqueue scripts
		 *
		 * @since 4.6.5
		 */
		public function enqueue_scripts() {

			wp_enqueue_script(
				'countUp',
				wpex_asset_url( 'js/dynamic/countUp.min.js' ),
				array( 'jquery' ),
				'1.9.3',
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
			include( locate_template( 'vcex_templates/vcex_milestone.php' ) );
			return ob_get_clean();
		}

		/**
		 * Map shortcode to VC
		 *
		 * @since 3.5.0
		 */
		public function map() {
			return array(
				'name' => __( 'Milestone', 'total' ),
				'description' => __( 'Animated counter', 'total' ),
				'base' => 'vcex_milestone',
				'icon' => 'vcex-milestone vcex-icon fa fa-medium',
				'category' => wpex_get_theme_branding(),
				'params' => array(
					// General
					array(
						'type' => 'textfield',
						'admin_label' => true,
						'heading' => __( 'Unique Id', 'total' ),
						'param_name' => 'unique_id',
					),
					array(
						'type' => 'textfield',
						'admin_label' => true,
						'heading' => __( 'Extra class name', 'total' ),
						'description' => __( 'Style particular content element differently - add a class name and refer to it in custom CSS.', 'total' ),
						'param_name' => 'classes',
					),
					array(
						'type' => 'vcex_visibility',
						'heading' => __( 'Visibility', 'total' ),
						'param_name' => 'visibility',
					),
					vcex_vc_map_add_css_animation(),
					array(
						'type' => 'vcex_hover_animations',
						'heading' => __( 'Hover Animation', 'total'),
						'param_name' => 'hover_animation',
					),
					array(
						'type' => 'vcex_ofswitch',
						'std' => 'true',
						'heading' => __( 'Animated', 'total' ),
						'param_name' => 'animated',
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Start Value', 'total' ),
						'param_name' => 'startval',
						'value' => '0',
						'description' => __( 'Enter the number which to start counting from, if the number is greater then the value set under the number tab then the counter will count down instead of up.','total'),
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Speed', 'total' ),
						'param_name' => 'speed',
						'value' => '2500',
						'description' => __( 'The number of milliseconds it should take to finish counting.','total'),
					),
					// Number
					array(
						'type' => 'textfield',
						'admin_label' => true,
						'heading' => __( 'Number', 'total' ),
						'param_name' => 'number',
						'std' => '45',
						'group' => __( 'Number', 'total' ),
						'description' => __( 'Enter a PHP function name if you would like to return a dynamic number based on a custom function', 'total' )
					),
					array(
						'type' => 'textfield',
						'std' => ',',
						'heading' => __( 'Thousand Seperator Type', 'total' ),
						'param_name' => 'separator',
						'group' => __( 'Number', 'total' ),
						'description' => __( 'Enter your custom seperator type. The default is a comma. Leave empty to remove completely.', 'total' )
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Decimal Places', 'total' ),
						'param_name' => 'decimals',
						'value' => '0',
						'group' => __( 'Number', 'total' ),
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Before', 'total' ),
						'param_name' => 'before',
						'group' => __( 'Number', 'total' ),
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'After', 'total' ),
						'param_name' => 'after',
						'default' => '%',
						'group' => __( 'Number', 'total' ),
					),
					array(
						'type'  => 'vcex_font_family_select',
						'heading' => __( 'Font Family', 'total' ),
						'param_name' => 'number_font_family',
						'group' => __( 'Number', 'total' ),
					),
					array(
						'type' => 'colorpicker',
						'heading' => __( 'Color', 'total' ),
						'param_name' => 'number_color',
						'group' => __( 'Number', 'total' ),
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Font Size', 'total' ),
						'param_name' => 'number_size',
						'group' => __( 'Number', 'total' ),
					),
					array(
						'type' => 'vcex_font_weight',
						'heading' => __( 'Font Weight', 'total' ),
						'param_name' => 'number_weight',
						'group' => __( 'Number', 'total' ),
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Bottom Margin', 'total' ),
						'param_name' => 'number_bottom_margin',
						'group' => __( 'Number', 'total' ),
					),
					// Icons
					array(
						'type' => 'vcex_ofswitch',
						'std' => 'false',
						'heading' => __( 'Enable Icon', 'total' ),
						'param_name' => 'enable_icon',
						'group' => __( 'Icon', 'total' ),
					),
					array(
						'type' => 'dropdown',
						'heading' => __( 'Icon Position', 'total' ),
						'param_name' => 'icon_position',
						'group' => __( 'Icon', 'total' ),
						'value' => array(
							esc_html__( 'Inline', 'total' ) => 'inline',
							esc_html__( 'Top', 'total' ) => 'top',
							esc_html__( 'Left', 'total' ) => 'left',
							esc_html__( 'Right', 'total' ) => 'right',
						),
						'dependency' => array( 'element' => 'enable_icon', 'value' => 'true' ),
					),
					array(
						'type' => 'dropdown',
						'heading' => esc_html__( 'Icon library', 'total' ),
						'param_name' => 'icon_type',
						'description' => esc_html__( 'Select icon library.', 'total' ),
						'value' => array(
							esc_html__( 'Font Awesome', 'total' ) => 'fontawesome',
							esc_html__( 'Open Iconic', 'total' ) => 'openiconic',
							esc_html__( 'Typicons', 'total' ) => 'typicons',
							esc_html__( 'Entypo', 'total' ) => 'entypo',
							esc_html__( 'Linecons', 'total' ) => 'linecons',
							esc_html__( 'Pixel', 'total' ) => 'pixelicons',
						),
						'dependency' => array( 'element' => 'enable_icon', 'value' => 'true' ),
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
						'heading' => __( 'Icon Font Alternative Classes', 'total' ),
						'param_name' => 'icon_alternative_classes',
						'dependency' => array( 'element' => 'enable_icon', 'value' => 'true' ),
						'group' => __( 'Icon', 'total' ),
					),
					array(
						'type' => 'colorpicker',
						'heading' => __(  'Color', 'total' ),
						'param_name' => 'icon_color',
						'group' => __( 'Icon', 'total' ),
						'dependency' => array( 'element' => 'enable_icon', 'value' => 'true' ),
					),
					array(
						'type' => 'vcex_number',
						'heading' => __( 'Font Size', 'total' ),
						'param_name' => 'icon_size',
						'group' => __( 'Icon', 'total' ),
						'dependency' => array( 'element' => 'enable_icon', 'value' => 'true' ),
						'max' => 200,
					),
					// caption
					array(
						'type' => 'textfield',
						'class' => 'vcex-animated-counter-caption',
						'heading' => __( 'Caption', 'total' ),
						'param_name' => 'caption',
						'value' => 'Awards Won',
						'admin_label' => true,
						'group' => __( 'Caption', 'total' ),
					),
					array(
						'type'  => 'vcex_font_family_select',
						'heading' => __( 'Font Family', 'total' ),
						'param_name' => 'caption_font_family',
						'group' => __( 'Caption', 'total' ),
					),
					array(
						'type' => 'colorpicker',
						'heading' => __(  'Color', 'total' ),
						'param_name' => 'caption_color',
						'group' => __( 'Caption', 'total' ),
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Font Size', 'total' ),
						'param_name' => 'caption_size',
						'group' => __( 'Caption', 'total' ),
					),
					array(
						'type' => 'vcex_font_weight',
						'heading' => __( 'Font Weight', 'total' ),
						'param_name' => 'caption_font',
						'group' => __( 'Caption', 'total' ),
					),
					// Link
					array(
						'type' => 'textfield',
						'heading' => __( 'URL', 'total' ),
						'param_name' => 'url',
						'group' => __( 'Link', 'total' ),
					),
					array(
						'type' => 'vcex_select_buttons',
						'heading' => __( 'Target', 'total' ),
						'param_name' => 'url_target',
						'std' => 'self',
						'choices' => array(
							'self' => __( 'Self', 'total' ),
							'blank' => __( 'Blank', 'total' ),
						),
						'group' => __( 'Link', 'total' ),
					),
					array(
						'type' => 'vcex_select_buttons',
						'heading' => __( 'Rel', 'total' ),
						'param_name' => 'url_rel',
						'std' => '',
						'choices' => array(
							'' => __( 'None', 'total' ),
							'nofollow' => __( 'Nofollow', 'total' ),
						),

						'group' => __( 'Link', 'total' ),
					),
					array(
						'type' => 'vcex_ofswitch',
						'std' => 'false',
						'heading' => __( 'Link Container Wrap', 'total' ),
						'param_name' => 'url_wrap',
						'group' => __( 'Link', 'total' ),
						'description' => __( 'Apply the link to the entire wrapper?', 'total' ),
					),
					// CSS
					array(
						'type' => 'css_editor',
						'heading' => __( 'Design', 'total' ),
						'param_name' => 'css',
						'group' => __( 'Design options', 'total' ),
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Width', 'total' ),
						'param_name' => 'width',
						'group' => __( 'Design options', 'total' ),
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Border Radius', 'total' ),
						'param_name' => 'border_radius',
						'group' => __( 'Design options', 'total' ),
					),
				)
			);
		}

	}
}
new VCEX_Milestone_Shortcode;