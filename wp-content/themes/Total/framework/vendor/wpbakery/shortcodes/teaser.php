<?php
/**
 * Visual Composer Teaser
 *
 * @package Total WordPress Theme
 * @subpackage VC Functions
 * @version 4.8
 */

if ( ! class_exists( 'VCEX_Teaser_Shortcode' ) ) {

	class VCEX_Teaser_Shortcode {

		/**
		 * Main constructor
		 *
		 * @since 3.5.0
		 */
		public function __construct() {
			
			// Add shortcode
			add_shortcode( 'vcex_teaser', array( $this, 'output' ) );

			// Map to VC
			vc_lean_map( 'vcex_teaser', array( $this, 'map' ) );

			// Edit form fields
			add_filter( 'vc_edit_form_fields_attributes_vcex_teaser', 'vcex_parse_image_size' );

		}

		/**
		 * Shortcode output => Get template file and display shortcode
		 *
		 * @since 3.5.0
		 */
		public function output( $atts, $content = null ) {
			ob_start();
			include( locate_template( 'vcex_templates/vcex_teaser.php' ) );
			return ob_get_clean();
		}

		/**
		 * Map shortcode to VC
		 *
		 * @since 3.5.0
		 */
		public function map() {
			return array(
				'name' => __( 'Teaser Box', 'total' ),
				'description' => __( 'A teaser content box', 'total' ),
				'base' => 'vcex_teaser',
				'category' => wpex_get_theme_branding(),
				'icon' => 'vcex-teaser vcex-icon ticon ticon-file-text-o',
				'params' => array(
					// General
					array(
						'type' => 'dropdown',
						'heading' => __( 'Style', 'total' ),
						'param_name' => 'style',
						'value' => array(
							__( 'Default', 'total' ) => '',
							__( 'Plain', 'total' ) => 'one',
							__( 'Boxed 1 - Legacy', 'total' ) => 'two',
							__( 'Boxed 2 - Legacy', 'total' ) => 'three',
							__( 'Outline - Legacy', 'total' ) => 'four',
						),
					),
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
						'type' => 'vcex_text_alignments',
						'heading' => __( 'Text Align', 'total' ),
						'param_name' => 'text_align',
						'std' => '',
					),
					array(
						'type' => 'vcex_trbl',
						'heading' => __( 'Padding', 'total' ),
						'param_name' => 'padding',
						'dependency' => array( 'element' => 'style', 'value' => 'two' ),
					),
					array(
						'type' => 'colorpicker',
						'heading' => __( 'Background Color', 'total' ),
						'param_name' => 'background',
						'dependency' => array( 'element' => 'style', 'value' => array( 'two', 'three' ) ),
					),
					array(
						'type' => 'colorpicker',
						'heading' => __( 'Border Color', 'total' ),
						'param_name' => 'border_color',
						'dependency' => array( 'element' => 'style', 'value' => array( 'two', 'three', 'four' ) ),
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Border Radius', 'total' ),
						'param_name' => 'border_radius',
						'dependency' => array( 'element' => 'style', 'value' => array( 'two', 'three', 'four' ) ),
					),

					// Heading
					array(
						'type' => 'textfield',
						'heading' => __( 'Heading', 'total' ),
						'param_name' => 'heading',
						'value' => 'Sample Heading',
						'group' => __( 'Heading', 'total' ),
					),
					array(
						'type' => 'colorpicker',
						'heading' => __( 'Color', 'total' ),
						'param_name' => 'heading_color',
						'group' => __( 'Heading', 'total' ),
					),
					array(
						'type' => 'vcex_select_buttons',
						'heading' => __( 'Tag', 'total' ),
						'param_name' => 'heading_type',
						'group' => __( 'Heading', 'total' ),
						'std' => 'h2',
						'choices' => 'html_tag',
					),
					array(
						'type'  => 'vcex_font_family_select',
						'heading' => __( 'Font Family', 'total' ),
						'param_name' => 'heading_font_family',
						'group' => __( 'Heading', 'total' ),
					),
					array(
						'type' => 'vcex_font_weight',
						'heading' => __( 'Font Weight', 'total' ),
						'param_name' => 'heading_weight',
						'group' => __( 'Heading', 'total' ),
					),
					array(
						'type' => 'vcex_text_transforms',
						'heading' => __( 'Text Transform', 'total' ),
						'param_name' => 'heading_transform',
						'group' => __( 'Heading', 'total' ),
					),
					array(
						'type' => 'vcex_responsive_sizes',
						'target' => 'font-size',
						'heading' => __( 'Font Size', 'total' ),
						'param_name' => 'heading_size',
						'group' => __( 'Heading', 'total' ),
					),
					array(
						'type' => 'vcex_trbl',
						'heading' => __( 'Margin', 'total' ),
						'param_name' => 'heading_margin',
						'group' => __( 'Heading', 'total' ),
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Letter Spacing', 'total' ),
						'param_name' => 'heading_letter_spacing',
						'group' => __( 'Heading', 'total' ),
					),
					// Content
					array(
						'type' => 'textarea_html',
						'holder' => 'div',
						'heading' => __( 'Content', 'total' ),
						'param_name' => 'content',
						'value' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed faucibus feugiat convallis. Integer nec eros et risus condimentum tristique vel vitae arcu.',
						'group' => __( 'Content', 'total' ),
					),
					array(
						'type' => 'colorpicker',
						'heading' => __( 'Background', 'total' ),
						'param_name' => 'content_background',
						'group' => __( 'Content', 'total' ),
					),
					array(
						'type' => 'colorpicker',
						'heading' => __( 'Color', 'total' ),
						'param_name' => 'content_color',
						'group' => __( 'Content', 'total' ),
					),
					array(
						'type' => 'vcex_trbl',
						'heading' => __( 'Margin', 'total' ),
						'param_name' => 'content_margin',
						'group' => __( 'Content', 'total' ),
					),
					array(
						'type' => 'vcex_trbl',
						'heading' => __( 'Padding', 'total' ),
						'param_name' => 'content_padding',
						'group' => __( 'Content', 'total' ),
					),
					array(
						'type' => 'vcex_responsive_sizes',
						'target' => 'font-size',
						'heading' => __( 'Font Size', 'total' ),
						'param_name' => 'content_font_size',
						'group' => __( 'Content', 'total' ),
					),
					array(
						'type' => 'vcex_font_weight',
						'heading' => __( 'Font Weight', 'total' ),
						'param_name' => 'content_font_weight',
						'group' => __( 'Content', 'total' ),
					),
					// Media
					array(
						'type' => 'attach_image',
						'heading' => __( 'Image', 'total' ),
						'param_name' => 'image',
						'group' => __( 'Media', 'total' ),
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Image Alt', 'total' ),
						'param_name' => 'image_alt',
						'group' => __( 'Media', 'total' ),
						'dependency' => array( 'element' => 'image', 'not_empty' => true ),
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Video link', 'total' ),
						'param_name' => 'video',
						'description' => __( 'Enter in a video URL that is compatible with WordPress\'s built-in oEmbed feature.', 'total' ),
						'group' => __( 'Media', 'total' ),
					),
					array(
						'type' => 'vcex_select_buttons',
						'heading' => __( 'Image Style', 'total' ),
						'param_name' => 'img_style',
						'std' => '',
						'choices' => array(
							'' => __( 'Auto', 'total' ),
							'stretch' => __( 'Stretch', 'total' ),
						),
						'group' => __( 'Media', 'total' ),
					),
					array(
						'type' => 'vcex_text_alignments',
						'heading' => __( 'Image Align', 'total' ),
						'param_name' => 'img_align',
						'std' => '',
						'group' => __( 'Media', 'total' ),
					),
					array(
						'type' => 'vcex_image_sizes',
						'heading' => __( 'Image Size', 'total' ),
						'param_name' => 'img_size',
						'std' => 'wpex_custom',
						'group' => __( 'Media', 'total' ),
					),
					array(
						'type' => 'vcex_image_crop_locations',
						'heading' => __( 'Image Crop Location', 'total' ),
						'param_name' => 'img_crop',
						'std' => 'center-center',
						'group' => __( 'Media', 'total' ),
						'dependency' => array( 'element' => 'img_size', 'value' => 'wpex_custom' ),
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Image Crop Width', 'total' ),
						'param_name' => 'img_width',
						'group' => __( 'Media', 'total' ),
						'dependency' => array( 'element' => 'img_size', 'value' => 'wpex_custom' ),
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Image Crop Height', 'total' ),
						'param_name' => 'img_height',
						'description' => __( 'Enter a height in pixels. Leave empty to disable vertical cropping and keep image proportions.', 'total' ),
						'group' => __( 'Media', 'total' ),
						'dependency' => array( 'element' => 'img_size', 'value' => 'wpex_custom' ),
					),
					array(
						'type' => 'vcex_image_filters',
						'heading' => __( 'Image Filter', 'total' ),
						'param_name' => 'img_filter',
						'group' => __( 'Media', 'total' ),
					),
					array(
						'type' => 'vcex_image_hovers',
						'heading' => __( 'CSS3 Image Hover', 'total' ),
						'param_name' => 'img_hover_style',
						'group' => __( 'Media', 'total' ),
					),
					// Link
					array(
						'type' => 'vc_link',
						'heading' => __( 'URL', 'total' ),
						'param_name' => 'url',
						'group' => __( 'Link', 'total' ),
					),
					array(
						'type' => 'vcex_ofswitch',
						'heading' => __( 'Local Scroll', 'total' ),
						'param_name' => 'url_local_scroll',
						'group' => __( 'Link', 'total' ),
						'std' => 'false',
					),
					// CSS
					array(
						'type' => 'css_editor',
						'heading' => __( 'Design Options', 'total' ),
						'param_name' => 'css',
						'group' => __( 'Design Options', 'total' ),
					),
				)
			);
		}

	}
}
new VCEX_Teaser_Shortcode;