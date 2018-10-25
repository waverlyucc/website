<?php
/**
 * Visual Composer Animated Text Shortcode
 *
 * @package Total WordPress Theme
 * @subpackage VC Functions
 * @version 4.6.5
 */

if ( ! class_exists( 'VCEX_Animated_Text_Shortcode' ) ) {

	class VCEX_Animated_Text_Shortcode {

		/**
		 * Main constructor
		 *
		 * @since 3.5.0
		 */
		public function __construct() {
			add_shortcode( 'vcex_animated_text', array( $this, 'output' ) );
			vc_lean_map( 'vcex_animated_text', array( $this, 'map' ) );
		}

		/**
		 * Enqueue scripts
		 *
		 * @since 4.6.5
		 */
		public function enqueue_scripts() {

			wp_enqueue_script(
				'typed',
				wpex_asset_url( 'js/dynamic/typed.min.js' ),
				array( 'jquery' ),
				'2.1.0',
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
			include( locate_template( 'vcex_templates/vcex_animated_text.php' ) );
			return ob_get_clean();
		}

		/**
		 * Map shortcode to VC
		 *
		 * @since 3.5.0
		 */
		public function map() {
			return array(
				'name' => __( 'Animated Text', 'total' ),
				'description' => __( 'Animated text', 'total' ),
				'base' => 'vcex_animated_text',
				'icon' => 'vcex-animated-text vcex-icon fa fa-text-width',
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
					array(
						'type' => 'vcex_text_alignments',
						'heading' => __( 'Align', 'total' ),
						'param_name' => 'text_align',
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Speed', 'total' ),
						'param_name' => 'speed',
						'description' => __( 'Enter a value in milliseconds.', 'total' ),
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Back Delay', 'total' ),
						'param_name' => 'back_delay',
						'std' => '500',
						'description' => __( 'Enter a value in milliseconds.', 'total' ),
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Back Speed', 'total' ),
						'param_name' => 'back_speed',
						'description' => __( 'Enter a value in milliseconds.', 'total' ),
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Start Delay', 'total' ),
						'param_name' => 'start_delay',
						'description' => __( 'Enter a value in milliseconds.', 'total' ),
					),
					array(
						'type' => 'vcex_ofswitch',
						'std' => 'true',
						'heading' => __( 'Loop', 'total' ),
						'param_name' => 'loop',
					),
					array(
						'type' => 'vcex_ofswitch',
						'std' => 'false',
						'heading' => __( 'Cursor', 'total' ),
						'param_name' => 'type_cursor',
					),

					// Typography
					array(
						'type'  => 'vcex_font_family_select',
						'heading' => __( 'Font Family', 'total' ),
						'param_name' => 'font_family',
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
						'type' => 'vcex_font_weight',
						'param_name' => 'font_weight',
						'heading' => __( 'Font Weight', 'total' ),
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
						'heading' => __( 'Font Style', 'total' ),
						'param_name' => 'font_style',
						'type' => 'vcex_select_buttons',
						'std' => '',
						'choices' => array(
							'' => __( 'Normal', 'total' ),
							'italic' => __( 'Italic', 'total' ),
						),
						'group' => __( 'Typography', 'total' ),
					),
					// Animated Text
					array(
						'type'  => 'textfield',
						'heading' => __( 'Fixed Width', 'total' ),
						'param_name' => 'animated_span_width',
						'group' => __( 'Animated Text', 'total' ),
						'description' => __( 'Enter a custom width to keep the animated container fixed. Useful when adding custom background or static text after the animated text.', 'total' ),
					),
					array(
						'type'  => 'vcex_text_alignments',
						'heading' => __( 'Text Align', 'total' ),
						'param_name' => 'animated_text_align',
						'group' => __( 'Animated Text', 'total' ),
						'exclude_choices' => array( 'default' ),
						'std' => 'left',
						'dependency' => array( 'element' => 'animated_span_width', 'not_empty' => true )
					),
					array(
						'type' => 'param_group',
						'param_name' => 'strings',
						'group' => __( 'Animated Text', 'total' ),
						'value' => urlencode( json_encode( array(
							array(
								'text' => __( 'Welcome', 'total' ),
							),
							array(
								'text' => __( 'Bienvenido', 'total' ),
							),
							array(
								'text' => __( 'Welkom', 'total' ),
							),
							array(
								'text' => __( 'Bienvenue', 'total' ),
							),
						) ) ),
						'params' => array(
							array(
								'type' => 'textfield',
								'heading' => __( 'Text', 'total' ),
								'param_name' => 'text',
								'admin_label' => true,
							),
						),
					),
					array(
						'type'  => 'vcex_font_family_select',
						'heading' => __( 'Font Family', 'total' ),
						'param_name' => 'animated_font_family',
						'dependency' => array( 'element' => 'static_text', 'value' => 'true' ),
						'group' => __( 'Animated Text', 'total' ),
					),
					array(
						'type' => 'colorpicker',
						'heading' => __( 'Color', 'total' ),
						'param_name' => 'animated_color',
						'dependency' => array( 'element' => 'static_text', 'value' => 'true' ),
						'group' => __( 'Animated Text', 'total' ),
					),
					array(
						'type' => 'vcex_font_weight',
						'param_name' => 'animated_font_weight',
						'heading' => __( 'Font Weight', 'total' ),
						'std' => '',
						'dependency' => array( 'element' => 'static_text', 'value' => 'true' ),
						'group' => __( 'Animated Text', 'total' ),
					),
					array(
						'heading' => __( 'Font Style', 'total' ),
						'param_name' => 'animated_font_style',
						'type' => 'vcex_select_buttons',
						'std' => '',
						'choices' => array(
							'' => __( 'Normal', 'total' ),
							'italic' => __( 'Italic', 'total' ),
						),
						'dependency' => array( 'element' => 'static_text', 'value' => 'true' ),
						'group' => __( 'Animated Text', 'total' ),
					),
					array(
						'type' => 'vcex_select_buttons',
						'heading' => __( 'Text Decoration', 'total' ),
						'param_name' => 'animated_text_decoration',
						'choices' => 'text_decoration',
						'dependency' => array( 'element' => 'static_text', 'value' => 'true' ),
						'group' => __( 'Animated Text', 'total' ),
					),
					array(
						'type' => 'css_editor',
						'heading' => __( 'CSS', 'total' ),
						'param_name' => 'animated_css',
						'group' => __( 'Animated Text', 'total' ),
					),
					// Static Text
					array(
						'type' => 'vcex_ofswitch',
						'heading' => __( 'Enable', 'total' ),
						'param_name' => 'static_text',
						'group' => __( 'Static Text', 'total' ),
						'std' => 'false',
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Before', 'total' ),
						'param_name' => 'static_before',
						'group' => __( 'Static Text', 'total' ),
						'dependency' => array( 'element' => 'static_text', 'value' => 'true' ),
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'After', 'total' ),
						'param_name' => 'static_after',
						'group' => __( 'Static Text', 'total' ),
						'dependency' => array( 'element' => 'static_text', 'value' => 'true' ),
					),

					// Container Design
					array(
						'type' => 'css_editor',
						'heading' => __( 'Container Design', 'total' ),
						'param_name' => 'css',
						'group' => __( 'Container Design', 'total' ),
					),
				),
			);
		}
		
	}

}
new VCEX_Animated_Text_Shortcode;