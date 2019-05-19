<?php
/**
 * Visual Composer Pricing
 *
 * @package Total WordPress Theme
 * @subpackage VC Functions
 * @version 4.8
 */

if ( ! class_exists( 'VCEX_Pricing_Shortcode' ) ) {

	class VCEX_Pricing_Shortcode {

		/**
		 * Main constructor
		 *
		 * @since 3.5.0
		 */
		public function __construct() {
			add_shortcode( 'vcex_pricing', array( $this, 'output' ) );
			vc_lean_map( 'vcex_pricing', array( $this, 'map' ) );
			add_filter( 'vc_edit_form_fields_attributes_vcex_pricing', array( $this, 'edit_form_fields' ) );
		}

		/**
		 * Shortcode output => Get template file and display shortcode
		 *
		 * @since 3.5.0
		 */
		public function output( $atts, $content = null ) {
			ob_start();
			include( locate_template( 'vcex_templates/vcex_pricing.php' ) );
			return ob_get_clean();
		}

		/**
		 * Alter module fields on edit
		 *
		 * @since 3.5.0
		 */
		public function edit_form_fields( $atts ) {
			if ( ! empty( $atts['button_url'] )
				&& false === strpos( $atts['button_url'], 'url:' )
				&& '|' != $atts['button_url']
				&& '||' != $atts['button_url']
				&& '|||' != $atts['button_url']
			) {
				$url = 'url:' . rawurlencode( $atts['button_url'] ) . '|';
				$atts['button_url'] = $url;
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
				'name' => __( 'Pricing Table', 'total' ),
				'description' => __( 'Insert a pricing column', 'total' ),
				'base' => 'vcex_pricing',
				'category' => wpex_get_theme_branding(),
				'icon' => 'vcex-pricing vcex-icon ticon ticon-usd',
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
						'param_name' => 'el_class',
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
					// Plan
					array(
						'type' => 'vcex_ofswitch',
						'heading' => __( 'Featured', 'total'),
						'param_name' => 'featured',
						'group' => __( 'Plan', 'total' ),
						'std' => 'no',
						'vcex' => array( 'on'  => 'yes', 'off' => 'no' ),
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Plan', 'total' ),
						'param_name' => 'plan',
						'group' => __( 'Plan', 'total' ),
						'std' => __( 'Basic', 'total' ),
						'admin_label' => true,
					),
					array(
						'type' => 'colorpicker',
						'heading' => __( 'Background', 'total' ),
						'param_name' => 'plan_background',
						'group' => __( 'Plan', 'total' ),
						'dependency' => array( 'element' => 'plan', 'not_empty' => true ),
					),
					array(
						'type' => 'colorpicker',
						'heading' => __( 'Color', 'total' ),
						'param_name' => 'plan_color',
						'group' => __( 'Plan', 'total' ),
						'dependency' => array( 'element' => 'plan', 'not_empty' => true ),
					),
					array(
						'type' => 'vcex_font_weight',
						'heading' => __( 'Font Weight', 'total' ),
						'param_name' => 'plan_weight',
						'group' => __( 'Plan', 'total' ),
						'dependency' => array( 'element' => 'plan', 'not_empty' => true ),
					),
					array(
						'type' => 'vcex_font_family_select',
						'heading' => __( 'Font Family', 'total' ),
						'param_name' => 'plan_font_family',
						'group' => __( 'Plan', 'total' ),
						'dependency' => array( 'element' => 'plan', 'not_empty' => true ),
					),
					array(
						'type' => 'vcex_text_transforms',
						'heading' => __( 'Text Transform', 'total' ),
						'param_name' => 'plan_text_transform',
						'group' => __( 'Plan', 'total' ),
						'dependency' => array( 'element' => 'plan', 'not_empty' => true ),
					),
					array(
						'type' => 'vcex_responsive_sizes',
						'target' => 'font-size',
						'heading' => __( 'Font Size', 'total' ),
						'param_name' => 'plan_size',
						'group' => __( 'Plan', 'total' ),
						'dependency' => array( 'element' => 'plan', 'not_empty' => true ),
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Letter Spacing', 'total' ),
						'param_name' => 'plan_letter_spacing',
						'group' => __( 'Plan', 'total' ),
						'dependency' => array( 'element' => 'plan', 'not_empty' => true ),
						'description' => __( 'Please enter a px value.', 'total' ),
					),
					array(
						'type' => 'vcex_trbl',
						'heading' => __( 'Padding', 'total' ),
						'param_name' => 'plan_padding',
						'group' => __( 'Plan', 'total' ),
						'dependency' => array( 'element' => 'plan', 'not_empty' => true ),
					),
					array(
						'type' => 'vcex_trbl',
						'heading' => __( 'Margin', 'total' ),
						'param_name' => 'plan_margin',
						'group' => __( 'Plan', 'total' ),
						'dependency' => array( 'element' => 'plan', 'not_empty' => true ),
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Border', 'total' ),
						'param_name' => 'plan_border',
						'description' => __( 'Please use the shorthand format: width style color. Enter 0px or "none" to disable border.', 'total' ),
						'group' => __( 'Plan', 'total' ),
						'dependency' => array( 'element' => 'plan', 'not_empty' => true ),
					),
					// Cost
					array(
						'type' => 'textfield',
						'heading' => __( 'Cost', 'total' ),
						'param_name' => 'cost',
						'group' => __( 'Cost', 'total' ),
						'std' => '$20',
					),
					array(
						'type' => 'colorpicker',
						'heading' => __( 'Background', 'total' ),
						'param_name' => 'cost_background',
						'group' => __( 'Cost', 'total' ),
						'dependency' => array( 'element' => 'cost', 'not_empty' => true ),
					),
					array(
						'type' => 'colorpicker',
						'heading' => __( 'Color', 'total' ),
						'param_name' => 'cost_color',
						'group' => __( 'Cost', 'total' ),
						'dependency' => array( 'element' => 'cost', 'not_empty' => true ),
					),
					array(
						'type' => 'vcex_font_family_select',
						'heading' => __( 'Font Family', 'total' ),
						'param_name' => 'cost_font_family',
						'group' => __( 'Cost', 'total' ),
						'dependency' => array( 'element' => 'cost', 'not_empty' => true ),
					),
					array(
						'type' => 'vcex_font_weight',
						'heading' => __( 'Font Weight', 'total' ),
						'param_name' => 'cost_weight',
						'group' => __( 'Cost', 'total' ),
						'dependency' => array( 'element' => 'cost', 'not_empty' => true ),
					),
					array(
						'type' => 'vcex_responsive_sizes',
						'target' => 'font-size',
						'heading' => __( 'Font Size', 'total' ),
						'param_name' => 'cost_size',
						'group' => __( 'Cost', 'total' ),
						'dependency' => array( 'element' => 'cost', 'not_empty' => true ),
					),
					array(
						'type' => 'vcex_trbl',
						'heading' => __( 'Padding', 'total' ),
						'param_name' => 'cost_padding',
						'group' => __( 'Cost', 'total' ),
						'dependency' => array( 'element' => 'cost', 'not_empty' => true ),
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Border', 'total' ),
						'param_name' => 'cost_border',
						'description' => __( 'Please use the shorthand format: width style color. Enter 0px or "none" to disable border.', 'total' ),
						'group' => __( 'Cost', 'total' ),
						'dependency' => array( 'element' => 'cost', 'not_empty' => true ),
					),
					// Per
					array(
						'type' => 'textfield',
						'heading' => __( 'Per', 'total' ),
						'param_name' => 'per',
						'group' => __( 'Per', 'total' ),
						'dependency' => array( 'element' => 'cost', 'not_empty' => true ),
					),
					array(
						'type' => 'dropdown',
						'heading' => __( 'Display', 'total' ),
						'param_name' => 'per_display',
						'value' => array(
							__( 'Default', 'total' ) => '',
							__( 'Inline', 'total' ) => 'inline',
							__( 'Block', 'total' ) => 'block',
							__( 'Inline-Block', 'total' ) => 'inline-block',
						),
						'group' => __( 'Per', 'total' ),
						'dependency' => array( 'element' => 'per', 'not_empty' => true ),
					),
					array(
						'type' => 'colorpicker',
						'heading' => __( 'Color', 'total' ),
						'param_name' => 'per_color',
						'group' => __( 'Per', 'total' ),
						'dependency' => array( 'element' => 'per', 'not_empty' => true ),
					),
					array(
						'type' => 'vcex_font_family_select',
						'heading' => __( 'Font Family', 'total' ),
						'param_name' => 'per_font_family',
						'group' => __( 'Per', 'total' ),
						'dependency' => array( 'element' => 'per', 'not_empty' => true ),
					),
					array(
						'type' => 'vcex_font_weight',
						'heading' => __( 'Font Weight', 'total' ),
						'param_name' => 'per_weight',
						'group' => __( 'Per', 'total' ),
						'dependency' => array( 'element' => 'per', 'not_empty' => true ),
					),
					array(
						'type' => 'vcex_text_transforms',
						'heading' => __( 'Text Transform', 'total' ),
						'param_name' => 'per_transform',
						'group' => __( 'Per', 'total' ),
						'dependency' => array( 'element' => 'per', 'not_empty' => true ),
					),
					array(
						'type' => 'vcex_responsive_sizes',
						'target' => 'font-size',
						'heading' => __( 'Font Size', 'total' ),
						'param_name' => 'per_size',
						'group' => __( 'Per', 'total' ),
						'dependency' => array( 'element' => 'per', 'not_empty' => true ),
					),
					// Features
					array(
						'type' => 'textarea_html',
						'heading' => __( 'Features', 'total' ),
						'param_name' => 'content',
						'value' => '<ul>
							<li>30GB Storage</li>
							<li>512MB Ram</li>
							<li>10 databases</li>
							<li>1,000 Emails</li>
							<li>25GB Bandwidth</li>
						</ul>',
						'description' => __('Enter your pricing content. You can use a UL list as shown by default but anything would really work!','total'),
						'group' => __( 'Features', 'total' ),
					),
					array(
						'type' => 'colorpicker',
						'heading' => __( 'Color', 'total' ),
						'param_name' => 'font_color',
						'group' => __( 'Features', 'total' ),
					),
					array(
						'type' => 'colorpicker',
						'heading' => __( 'Background', 'total' ),
						'param_name' => 'features_bg',
						'group' => __( 'Features', 'total' ),
					),
					array(
						'type' => 'vcex_font_family_select',
						'heading' => __( 'Font Family', 'total' ),
						'param_name' => 'font_family',
						'group' => __( 'Features', 'total' ),
					),
					array(
						'type' => 'vcex_responsive_sizes',
						'target' => 'font-size',
						'heading' => __( 'Font Size', 'total' ),
						'param_name' => 'font_size',
						'group' => __( 'Features', 'total' ),
					),
					array(
						'type' => 'vcex_trbl',
						'heading' => __( 'Padding', 'total' ),
						'param_name' => 'features_padding',
						'group' => __( 'Features', 'total' ),
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Border', 'total' ),
						'param_name' => 'features_border',
						'description' => __( 'Please use the shorthand format: width style color. Enter 0px or "none" to disable border.', 'total' ),
						'group' => __( 'Features', 'total' ),
					),
					// Button
					array(
						'type' => 'textarea_raw_html',
						'heading' => __( 'Custom Button HTML', 'total' ),
						'param_name' => 'custom_button',
						'description' => __( 'Enter your custom button HTML, such as your paypal button code.', 'total' ),
						'group' => __( 'Button', 'total' ),
					),
					array(
						'type' => 'vc_link',
						'heading' => __( 'URL', 'total' ),
						'param_name' => 'button_url',
						'group' => __( 'Button', 'total' ),
						'dependency' => array( 'element' => 'custom_button', 'is_empty' => true ),
					),
					array(
						'type' => 'vcex_ofswitch',
						'std' => 'false',
						'heading' => __( 'Local Scroll?', 'total' ),
						'param_name' => 'button_local_scroll',
						'group' => __( 'Button', 'total' ),
						'dependency' => array( 'element' => 'custom_button', 'is_empty' => true ),
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Text', 'total' ),
						'param_name' => 'button_text',
						'value' => __( 'Text', 'total' ),
						'group' => __( 'Button', 'total' ),
						'dependency' => array( 'element' => 'custom_button', 'is_empty' => true ),
					),
					array(
						'type' => 'colorpicker',
						'heading' => __( 'Area Background', 'total' ),
						'param_name' => 'button_wrap_bg',
						'group' => __( 'Button', 'total' ),
					),
					array(
						'type' => 'vcex_trbl',
						'heading' => __( 'Area Padding', 'total' ),
						'param_name' => 'button_wrap_padding',
						'group' => __( 'Button', 'total' ),
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Area Border', 'total' ),
						'param_name' => 'button_wrap_border',
						'description' => __( 'Please use the shorthand format: width style color. Enter 0px or "none" to disable border.', 'total' ),
						'group' => __( 'Button', 'total' ),
					),
					array(
						'type' => 'vcex_button_styles',
						'heading' => __( 'Style', 'total' ),
						'param_name' => 'button_style',
						'group' => __( 'Button', 'total' ),
							'dependency' => array( 'element' => 'custom_button', 'is_empty' => true ),
					),
					array(
						'type' => 'vcex_button_colors',
						'heading' => __( 'Color', 'total' ),
						'param_name' => 'button_style_color',
						'group' => __( 'Button', 'total' ),
						'dependency' => array( 'element' => 'custom_button', 'is_empty' => true ),
					),
					array(
						'type' => 'vcex_font_family_select',
						'heading' => __( 'Font Family', 'total' ),
						'param_name' => 'button_font_family',
						'group' => __( 'Button', 'total' ),
						'dependency' => array( 'element' => 'custom_button', 'is_empty' => true ),
					),
					array(
						'type' => 'vcex_font_weight',
						'heading' => __( 'Font Weight', 'total' ),
						'param_name' => 'button_weight',
						'group' => __( 'Button', 'total' ),
						'dependency' => array( 'element' => 'custom_button', 'is_empty' => true ),
					),
					array(
						'type' => 'vcex_text_transforms',
						'heading' => __( 'Text Transform', 'total' ),
						'param_name' => 'button_transform',
						'group' => __( 'Button', 'total' ),
						'dependency' => array( 'element' => 'custom_button', 'is_empty' => true ),
					),
					array(
						'type' => 'vcex_responsive_sizes',
						'target' => 'font-size',
						'heading' => __( 'Font Size', 'total' ),
						'param_name' => 'button_size',
						'group' => __( 'Button', 'total' ),
						'dependency' => array( 'element' => 'custom_button', 'is_empty' => true ),
					),
					array(
						'type' => 'colorpicker',
						'heading' => __( 'Background', 'total' ),
						'param_name' => 'button_bg_color',
						'group' => __( 'Button', 'total' ),
							'dependency' => array( 'element' => 'custom_button', 'is_empty' => true ),
					),
					array(
						'type' => 'colorpicker',
						'heading' => __( 'Background: Hover', 'total' ),
						'param_name' => 'button_hover_bg_color',
						'group' => __( 'Button', 'total' ),
							'dependency' => array( 'element' => 'custom_button', 'is_empty' => true ),
					),
					array(
						'type' => 'colorpicker',
						'heading' => __( 'Color', 'total' ),
						'param_name' => 'button_color',
						'group' => __( 'Button', 'total' ),
							'dependency' => array( 'element' => 'custom_button', 'is_empty' => true ),
					),
					array(
						'type' => 'colorpicker',
						'heading' => __( 'Color: Hover', 'total' ),
						'param_name' => 'button_hover_color',
						'group' => __( 'Button', 'total' ),
						'dependency' => array( 'element' => 'custom_button', 'is_empty' => true ),
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Border Radius', 'total' ),
						'param_name' => 'button_border_radius',
						'group' => __( 'Button', 'total' ),
						'dependency' => array( 'element' => 'custom_button', 'is_empty' => true ),
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Letter Spacing', 'total' ),
						'param_name' => 'button_letter_spacing',
						'group' => __( 'Button', 'total' ),
						'dependency' => array( 'element' => 'custom_button', 'is_empty' => true ),
					),
					array(
						'type' => 'vcex_trbl',
						'heading' => __( 'Padding', 'total' ),
						'param_name' => 'button_padding',
						'group' => __( 'Button', 'total' ),
						'dependency' => array( 'element' => 'custom_button', 'is_empty' => true ),
					),
					//Icons
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
						'group' => __( 'Button Icons', 'total' ),
						'dependency' => array( 'element' => 'custom_button', 'is_empty' => true ),
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
						'group' => __( 'Button Icons', 'total' ),
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
						'group' => __( 'Button Icons', 'total' ),
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
						'group' => __( 'Button Icons', 'total' ),
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
						'group' => __( 'Button Icons', 'total' ),
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
						'group' => __( 'Button Icons', 'total' ),
					),
					array(
						'type' => 'iconpicker',
						'heading' => __( 'Icon Left', 'total' ),
						'param_name' => 'button_icon_left_pixelicons',
						'settings' => array(
							'emptyIcon' => false,
							'type' => 'pixelicons',
							'source' => vcex_pixel_icons(),
						),
						'dependency' => array( 'element' => 'icon_type', 'value' => 'pixelicons' ),
						'group' => __( 'Button Icons', 'total' ),
					),
					array(
						'type' => 'iconpicker',
						'heading' => __( 'Icon Right', 'total' ),
						'param_name' => 'button_icon_right',
						'settings' => array(
							'emptyIcon' => true,
							'iconsPerPage' => 200,
						),
						'dependency' => array(
							'element' => 'icon_type',
							'value' => 'fontawesome',
						),
						'group' => __( 'Button Icons', 'total' ),
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
						'dependency' => array(
							'element' => 'icon_type',
							'value' => 'openiconic',
						),
						'group' => __( 'Button Icons', 'total' ),
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
						'group' => __( 'Button Icons', 'total' ),
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
						'group' => __( 'Button Icons', 'total' ),
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
						'group' => __( 'Button Icons', 'total' ),
					),
					array(
						'type' => 'iconpicker',
						'heading' => __( 'Icon Right', 'total' ),
						'param_name' => 'button_icon_right_pixelicons',
						'settings' => array(
							'emptyIcon' => false,
							'type' => 'pixelicons',
							'source' => vcex_pixel_icons(),
						),
						'dependency' => array( 'element' => 'icon_type', 'value' => 'pixelicons' ),
						'group' => __( 'Button Icons', 'total' ),
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Left Icon: Hover Transform x', 'total' ),
						'param_name' => 'button_icon_left_transform',
						'group' => __( 'Button Icons', 'total' ),
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Right Icon: Hover Transform x', 'total' ),
						'param_name' => 'button_icon_right_transform',
						'group' => __( 'Button Icons', 'total' ),
					),
					// CSS
					array(
						'type' => 'css_editor',
						'heading' => __( 'Design Options', 'total' ),
						'param_name' => 'css',
						'group' => __( 'CSS', 'total' ),
					),
				)
			);
		}

	}
}
new VCEX_Pricing_Shortcode;