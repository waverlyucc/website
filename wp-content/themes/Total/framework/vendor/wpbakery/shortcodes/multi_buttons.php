<?php
/**
 * Visual Composer Multi Buttons
 *
 * @package Total WordPress Theme
 * @subpackage VC Functions
 * @version 4.8
 */

if ( ! class_exists( 'VCEX_Multi_Buttons_Shortcode' ) ) {

	class VCEX_Multi_Buttons_Shortcode {

		/**
		 * Main constructor
		 *
		 * @since 4.3
		 */
		public function __construct() {
			add_shortcode( 'vcex_multi_buttons', array( $this, 'output' ) );
			vc_lean_map( 'vcex_multi_buttons', array( $this, 'map' ) );
		}

		/**
		 * Shortcode output => Get template file and display shortcode
		 *
		 * @since 4.3
		 */
		public function output( $atts, $content = null ) {
			ob_start();
			include( locate_template( 'vcex_templates/vcex_multi_buttons.php' ) );
			return ob_get_clean();
		}

		/**
		 * Map shortcode to VC
		 *
		 * @since 4.3
		 */
		public function map() {
			return array(
				'name' => __( 'Multi-Buttons', 'total' ),
				'description' => __( 'Multiple Buttons side by side', 'total' ),
				'base' => 'vcex_multi_buttons',
				'icon' => 'vcex-multi-buttons vcex-icon ticon ticon-ellipsis-h',
				'category' => wpex_get_theme_branding(),
				'params' => array(
					// Buttons
					array(
						'type' => 'param_group',
						'param_name' => 'buttons',
						'group' => __( 'Buttons', 'total' ),
						'value' => urlencode( json_encode( array(
							array(
								'text' => __( 'Button 1', 'total' ),
								'link' => 'url:#',
							),
							array(
								'text' => __( 'Button 2', 'total' ),
								'link' => 'url:#',
							),
						) ) ),
						'params' => array(
							array(
								'type' => 'textfield',
								'heading' => __( 'Text', 'total' ),
								'param_name' => 'text',
								'admin_label' => true,
							),
							array(
								'type' => 'vc_link',
								'heading' => __( 'Link', 'total' ),
								'param_name' => 'link',
							),
							vcex_vc_map_add_css_animation(),
							array(
								'type' => 'vcex_ofswitch',
								'heading' => __( 'Local Scroll', 'total' ),
								'param_name' => 'local_scroll',
								'std' => 'false',
							),
							array(
								'type' => 'vcex_select_buttons',
								'std' => 'flat',
								'heading' => __( 'Style', 'total' ),
								'param_name' => 'style',
								'choices' => apply_filters( 'wpex_button_styles', array(
									'flat' => __( 'Flat', 'total' ),
									'outline' => __( 'Outline', 'total' ),
									'plain-text' => __( 'Plain Text', 'total' ),
								) ),
							),
							array(
								'type' => 'vcex_button_colors',
								'heading' => __( 'Prefixed Color', 'total' ),
								'param_name' => 'color',
								'description' => __( 'Custom color options can be added via a child theme.', 'total' ),
							),
							array(
								'type' => 'colorpicker',
								'heading' => __( 'Custom Color', 'total' ),
								'param_name' => 'custom_color',
							),
							array(
								'type' => 'colorpicker',
								'heading' => __( 'Custom Color: Hover', 'total' ),
								'param_name' => 'custom_color_hover',
							),
						),
					),
					// General
					array(
						'type' => 'vcex_visibility',
						'heading' => __( 'Visibility', 'total' ),
						'param_name' => 'visibility',
					),
					array(
						'type' => 'textfield',
						'admin_label' => true,
						'heading' => __( 'Extra class name', 'total' ),
						'param_name' => 'el_class',
						'description' => __( 'Style particular content element differently - add a class name and refer to it in custom CSS.', 'total' ),
					),
					array(
						'type' => 'vcex_text_alignments',
						'heading' => __( 'Align', 'total' ),
						'param_name' => 'align',
						'std' => 'center',
						'exclude_choices' => array( 'default' ),
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Button Width', 'total' ),
						'param_name' => 'width',
						'description' => __( 'Number in pixels.', 'total' ),
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Line Height', 'total' ),
						'param_name' => 'line_height',
						'description' => __( 'Number in pixels.', 'total' ),
					),
					array(
						'type' => 'vcex_trbl',
						'heading' => __( 'Button Padding', 'total' ),
						'param_name' => 'padding',
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Spacing', 'total' ),
						'param_name' => 'spacing',
						'description' => __( 'Enter a custom spacing in pixels that will be added between the buttons.', 'total' ),
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Border Radius', 'total' ),
						'param_name' => 'border_radius',
						'description' => __( 'Please enter a px value.', 'total' ),
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Border Width', 'total' ),
						'param_name' => 'border_width',
						'description' => __( 'Please enter a px value. This will control the border width when using the outline style button. Default is 3px.', 'total' ),
					),
					array(
						'type' => 'vcex_ofswitch',
						'std' => 'false',
						'heading' => __( 'Full-Width on Small Screens', 'total' ),
						'param_name' => 'small_screen_full_width',
						'description' => __( 'If enabled the buttons will render at 100% width on devices under 480px.', 'total' ),
					),
					// Typography
					array(
						'type' => 'vcex_font_family_select',
						'heading' => __( 'Font Family', 'total' ),
						'param_name' => 'font_family',
						'group' => __( 'Typography', 'total' ),
					),
					array(
						'type' => 'vcex_responsive_sizes',
						'heading' => __( 'Font Size', 'total' ),
						'param_name' => 'font_size',
						'group' => __( 'Typography', 'total' ),
						'target' => 'font-size',
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
						'group' => __( 'Typography', 'total' ),
					),
				)
			);
		}

	}

}
new VCEX_Multi_Buttons_Shortcode;