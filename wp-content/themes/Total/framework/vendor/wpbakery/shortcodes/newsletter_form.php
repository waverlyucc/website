<?php
/**
 * Visual Composer Newsletter Form
 *
 * @package Total WordPress Theme
 * @subpackage Visual Composer
 * @version 4.8
 */

if ( ! class_exists( 'VCEX_Newsletter_Shortcode' ) ) {

	class VCEX_Newsletter_Shortcode {

		/**
		 * Main constructor
		 *
		 * @since 3.5.0
		 */
		public function __construct() {
			add_shortcode( 'vcex_newsletter_form', array( $this, 'output' ) );
			vc_lean_map( 'vcex_newsletter_form', array( $this, 'map' ) );
		}

		/**
		 * Shortcode output => Get template file and display shortcode
		 *
		 * @since 3.5.0
		 */
		public function output( $atts, $content = null ) {
			ob_start();
			include( locate_template( 'vcex_templates/vcex_newsletter_form.php' ) );
			return ob_get_clean();
		}

		/**
		 * Map shortcode to VC
		 *
		 * @since 3.5.0
		 */
		public function map() {
			return array(
				'name'        => __( 'Mailchimp Form', 'total' ),
				'description' => __( 'Newsletter subscription form', 'total' ),
				'base'        => 'vcex_newsletter_form',
				'category'    => wpex_get_theme_branding(),
				'icon'        => 'vcex-newsletter vcex-icon ticon ticon-envelope',
				'params'      => array(
					// General
					array(
						'type'        => 'textfield',
						'admin_label' => true,
						'heading'     => __( 'Unique Id', 'total' ),
						'param_name'  => 'unique_id',
					),
					array(
						'type'        => 'textfield',
						'admin_label' => true,
						'heading'     => __( 'Extra class name', 'total' ),
						'description' => __( 'Style particular content element differently - add a class name and refer to it in custom CSS.', 'total' ),
						'param_name'  => 'classes',
					),
					array(
						'type'       => 'vcex_visibility',
						'heading'    => __( 'Visibility', 'total' ),
						'param_name' => 'visibility',
					),
					vcex_vc_map_add_css_animation(),
					array(
						'type'       => 'vcex_ofswitch',
						'std'        => 'false',
						'heading'    => __( 'Full-Width on Mobile', 'total'),
						'param_name' => 'fullwidth_mobile',
					),
					array(
						'type'        => 'textfield',
						'heading'     => __( 'Mailchimp Form Action', 'total' ),
						'param_name'  => 'mailchimp_form_action',
						'admin_label' => true,
						'value'       => '//domain.us1.list-manage.com/subscribe/post?u=numbers_go_here',
						'description' => __( 'Enter the MailChimp form action URL.', 'total' ) .' <a href="https://wpexplorer-themes.com/total/docs/mailchimp-form-action-url/" target="_blank">'. __( 'Learn More', 'total' ) .' &rarr;</a>',
					),
					array(
						'type'       => 'vcex_notice',
						'param_name' => 'mailchimp_form_action_notice',
						'text'       => __( 'While the module is optimized for Mailchimp you could potentially use the form action URL from another newsletter service.', 'total'),
					),
					// Input
					array(
						'type'       => 'textfield',
						'heading'    => __( 'Text', 'total' ),
						'param_name' => 'placeholder_text',
						'std'        => __( 'Enter your email address', 'total' ),
						'group'      => __( 'Input', 'total' ),
					),
					array(
						'type'       => 'colorpicker',
						'heading'    => __( 'Background', 'total' ),
						'param_name' => 'input_bg',
						'dependency' => array(
							'element'   => 'mailchimp_form_action',
							'not_empty' => true
						),
						'group'      => __( 'Input', 'total' ),
					),
					array(
						'type'       => 'colorpicker',
						'heading'    => __( 'Color', 'total' ),
						'param_name' => 'input_color',
						'group'      => __( 'Input', 'total' ),
					),
					array(
						'type'       => 'colorpicker',
						'heading'    => __( 'Border Color', 'total' ),
						'param_name' => 'input_border_color',
						'group'      => __( 'Input', 'total' ),
					),
					array(
						'type'       => 'textfield',
						'heading'    => __( 'Width', 'total' ),
						'param_name' => 'input_width',
						'group'      => __( 'Input', 'total' ),
					),
					array(
						'type'       => 'vcex_text_alignments',
						'heading'    => __( 'Alignment', 'total' ),
						'param_name' => 'input_align',
						'std' => '',
						'dependency' => array( 'element' => 'input_width', 'not_empty' => true ),
						'group'      => __( 'Input', 'total' ),
					),
					array(
						'type'       => 'textfield',
						'heading'    => __( 'Height', 'total' ),
						'param_name' => 'input_height',
						'group'      => __( 'Input', 'total' ),
					),
					array(
						'type'       => 'vcex_trbl',
						'heading'    => __( 'Padding', 'total' ),
						'param_name' => 'input_padding',
						'group'      => __( 'Input', 'total' ),
					),
					array(
						'type'       => 'textfield',
						'heading'    => __( 'Border', 'total' ),
						'param_name' => 'input_border',
						'description' => __( 'Please use the shorthand format: width style color. Enter 0px or "none" to disable border.', 'total' ),
						'group'      => __( 'Input', 'total' ),
					),
					array(
						'type'       => 'textfield',
						'heading'    => __( 'Border Radius', 'total' ),
						'param_name' => 'input_border_radius',
						'group'      => __( 'Input', 'total' ),
					),
					array(
						'type'       => 'textfield',
						'heading'    => __( 'Font Size', 'total' ),
						'param_name' => 'input_font_size',
						'group'      => __( 'Input', 'total' ),
					),
					array(
						'type'       => 'textfield',
						'heading'    => __( 'Letter Spacing', 'total' ),
						'param_name' => 'input_letter_spacing',
						'group'      => __( 'Input', 'total' ),
					),
					array(
						'type'       => 'vcex_font_weight',
						'heading'    => __( 'Font Weight', 'total' ),
						'param_name' => 'input_weight',
						'group'      => __( 'Input', 'total' ),
					),
					// Submit
					array(
						'type'       => 'textfield',
						'heading'    => __( 'Text', 'total' ),
						'param_name' => 'submit_text',
						'std' => __( 'Sign Up', 'total' ),
						'group'      => __( 'Submit', 'total' ),
					),
					array(
						'type'       => 'colorpicker',
						'heading'    => __( 'Background', 'total' ),
						'param_name' => 'submit_bg',
						'group'      => __( 'Submit', 'total' ),
					),
					array(
						'type'       => 'colorpicker',
						'heading'    => __( 'Background: Hover', 'total' ),
						'param_name' => 'submit_hover_bg',
						'group'      => __( 'Submit', 'total' ),
					),
					array(
						'type'       => 'colorpicker',
						'heading'    => __( 'Color', 'total' ),
						'param_name' => 'submit_color',
						'group'      => __( 'Submit', 'total' ),
					),
					array(
						'type'       => 'colorpicker',
						'heading'    => __( 'Color: Hover', 'total' ),
						'param_name' => 'submit_hover_color',
						'group'      => __( 'Submit', 'total' ),
					),
					array(
						'type'       => 'textfield',
						'heading'    => __( 'Height', 'total' ),
						'param_name' => 'submit_height',
						'group'      => __( 'Submit', 'total' ),
					),
					array(
						'type'       => 'vcex_trbl',
						'heading'    => __( 'Padding', 'total' ),
						'param_name' => 'submit_padding',
						'group'      => __( 'Submit', 'total' ),
					),
					array(
						'type'       => 'textfield',
						'heading'    => __( 'Border', 'total' ),
						'param_name' => 'submit_border',
						'description' => __( 'Please use the shorthand format: width style color. Enter 0px or "none" to disable border.', 'total' ),
						'group'      => __( 'Submit', 'total' ),
					),
					array(
						'type'       => 'textfield',
						'heading'    => __( 'Border Radius', 'total' ),
						'param_name' => 'submit_border_radius',
						'group'      => __( 'Submit', 'total' ),
					),
					array(
						'type'       => 'textfield',
						'heading'    => __( 'Font Size', 'total' ),
						'param_name' => 'submit_font_size',
						'group'      => __( 'Submit', 'total' ),
					),
					array(
						'type'       => 'textfield',
						'heading'    => __( 'Letter Spacing', 'total' ),
						'param_name' => 'submit_letter_spacing',
						'group'      => __( 'Submit', 'total' ),
					),
					array(
						'type'       => 'vcex_font_weight',
						'heading'    => __( 'Font Weight', 'total' ),
						'param_name' => 'submit_weight',
						'group'      => __( 'Submit', 'total' ),
					),
				)
			);
		}

	}
}
new VCEX_Newsletter_Shortcode;