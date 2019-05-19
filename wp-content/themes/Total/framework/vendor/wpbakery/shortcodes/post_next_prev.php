<?php
/**
 * Visual Composer Next & Previous Posts
 *
 * @package Total WordPress Theme
 * @subpackage VC Functions
 * @version 4.8
 */

if ( ! class_exists( 'VCEX_Post_Next_Prev_Shortcode' ) ) {

	class VCEX_Post_Next_Prev_Shortcode {

		/**
		 * Main constructor
		 *
		 * @since 4.3
		 */
		public function __construct() {
			add_shortcode( 'vcex_post_next_prev', array( $this, 'output' ) );
			vc_lean_map( 'vcex_post_next_prev', array( $this, 'map' ) );
		}

		/**
		 * Shortcode output => Get template file and display shortcode
		 *
		 * @since 4.3
		 */
		public function output( $atts, $content = null ) {
			ob_start();
			include( locate_template( 'vcex_templates/vcex_post_next_prev.php' ) );
			return ob_get_clean();
		}

		/**
		 * Map shortcode to VC
		 *
		 * @since 4.3
		 */
		public function map() {
			return array(
				'name' => __( 'Next & Previous Post', 'total' ),
				'description' => __( 'Display next/prev post buttons', 'total' ),
				'base' => 'vcex_post_next_prev',
				'icon' => 'vcex-breadcrumbs vcex-icon ticon ticon-arrows-h',
				'category' => wpex_get_theme_branding(),
				'params' => array(
					array(
						'type' => 'textfield',
						'heading' => __( 'Extra class name', 'total' ),
						'param_name' => 'el_class',
						'description' => __( 'Style particular content element differently - add a class name and refer to it in custom CSS.', 'total' ),
					),
					array(
						'type' => 'vcex_visibility',
						'heading' => __( 'Visibility', 'total' ),
						'param_name' => 'visibility',
					),
					array(
						'type' => 'dropdown',
						'std' => 'chevron',
						'heading' => __( 'Arrows Style', 'total' ),
						'param_name' => 'icon_style',
						'value' => array(
							__( 'Chevron', 'total' ) => 'chevron',
							__( 'Chevron Circle', 'total' ) => 'chevron-circle',
							__( 'Angle', 'total' ) => 'angle',
							__( 'Double Angle', 'total' ) => 'angle-double',
							__( 'Arrow', 'total' ) => 'arrow',
							__( 'Long Arrow', 'total' ) => 'long-arrow',
							__( 'Caret', 'total' ) => 'caret',
							__( 'Cirle', 'total' ) => 'arrow-circle',
							__( 'None', 'total' ) => '',
						),
					),
					array(
						'type' => 'vcex_select_buttons',
						'std' => 'icon',
						'heading' => __( 'Link Format', 'total' ),
						'param_name' => 'link_format',
						'choices' => array(
							'icon' => __( 'Icon Only', 'total' ),
							'title' => __( 'Post Name', 'total' ),
							'custom' => __( 'Custom Text', 'total' ),
						),
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Previous Text', 'total' ),
						'param_name' => 'previous_link_custom_text',
						'dependency' => array( 'element' => 'link_format', 'value' => 'custom' )
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Next Text', 'total' ),
						'param_name' => 'next_link_custom_text',
						'dependency' => array( 'element' => 'link_format', 'value' => 'custom' )
					),
					array(
						'type' => 'vcex_text_alignments',
						'heading' => __( 'Align', 'total' ),
						'param_name' => 'align',
					),
					array(
						'type' => 'vcex_ofswitch',
						'std' => 'true',
						'heading' => __( 'Previous Link', 'total' ),
						'param_name' => 'previous_link',
					),
					array(
						'type' => 'vcex_ofswitch',
						'std' => 'true',
						'heading' => __( 'Next Link', 'total' ),
						'param_name' => 'next_link',
					),
					array(
						'type' => 'vcex_ofswitch',
						'std' => 'false',
						'heading' => __( 'Reverse Order', 'total' ),
						'param_name' => 'reverse_order',
					),
					array(
						'type' => 'vcex_ofswitch',
						'std' => 'false',
						'heading' => __( 'In Same Term?', 'total' ),
						'param_name' => 'in_same_term',
					),
					array(
						'type' => 'textfield',
						'std' => '',
						'heading' => __( 'Same Term Taxonomy Name', 'total' ),
						'param_name' => 'same_term_tax',
						'description' => __( 'If you want to display posts from the same term enter the taxonomy name here. Such as category, portfolio_category, staff_category..etc.', 'total' ),
						'dependency' => array( 'element' => 'in_same_term', 'value' => 'true' )
					),
					// Design
					array(
						'type' => 'vcex_button_styles',
						'heading' => __( 'Button Style', 'total' ),
						'param_name' => 'button_style',
						'group' => __( 'Design', 'total' ),
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Font Size', 'total' ),
						'param_name' => 'font_size',
						'group' => __( 'Design', 'total' ),
					),
					array(
						'type' => 'vcex_button_colors',
						'heading' => __( 'Color', 'total' ),
						'param_name' => 'button_color',
						'group' => __( 'Design', 'total' ),
					),
				)
			);
		}
	}
}
new VCEX_Post_Next_Prev_Shortcode;