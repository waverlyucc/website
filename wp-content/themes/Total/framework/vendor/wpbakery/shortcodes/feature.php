<?php
/**
 * Visual Composer Feature Box
 *
 * @package Total WordPress Theme
 * @subpackage VC Functions
 * @version 4.8
 */

if ( ! class_exists( 'VCEX_Feature_Box_Shortcode' ) ) {

	class VCEX_Feature_Box_Shortcode {

		/**
		 * Main constructor
		 *
		 * @since 3.5.0
		 */
		public function __construct() {
			add_shortcode( 'vcex_feature_box', array( $this, 'output' ) );
			vc_lean_map( 'vcex_feature_box', array( $this, 'map' ) );
		}

		/**
		 * Shortcode output => Get template file and display shortcode
		 *
		 * @since 3.5.0
		 */
		public function output( $atts, $content = null ) {
			ob_start();
			include( locate_template( 'vcex_templates/vcex_feature_box.php' ) );
			return ob_get_clean();
		}

		/**
		 * Map shortcode to VC
		 *
		 * @since 3.5.0
		 */
		public function map() {
			return array(
				'name' => __( 'Feature Box', 'total' ),
				'description' => __( 'A feature content box', 'total' ),
				'base' => 'vcex_feature_box',
				'category' => wpex_get_theme_branding(),
				'icon' => 'vcex-feature-box vcex-icon ticon ticon-trophy',
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
					array(
						'type' => 'vcex_visibility',
						'heading' => __( 'Visibility', 'total' ),
						'param_name' => 'visibility',
					),
					vcex_vc_map_add_css_animation(),
					array(
						'type' => 'vcex_select_buttons',
						'heading' => __( 'Content Placement', 'total' ),
						'param_name' => 'style',
						'std' => 'left-content-right-image',
						'choices' => array(
							'left-content-right-image' => __( 'Left', 'total' ),
							'left-image-right-content' => __( 'Right', 'total' ),
						),
					),
					array(
						'type' => 'vcex_ofswitch',
						'std' => 'false',
						'heading' => __( 'Vertical Align Content', 'total' ),
						'param_name' => 'content_vertical_align',
						'dependency' => array( 'element' => 'equal_heights', 'value' => 'false' ),
					),
					array(
						'type' => 'vcex_text_alignments',
						'heading' => __( 'Text Alignment', 'total' ),
						'std' => '',
						'param_name' => 'text_align',
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
						'type' => 'vcex_select_buttons',
						'heading' => __( 'Tag', 'total' ),
						'param_name' => 'heading_type',
						'group' => __( 'Heading', 'total' ),
						'std' => 'h2',
						'choices' => array(
							'h1' => 'h1',
							'h2' => 'h2',
							'h3' => 'h3',
							'h4' => 'h4',
							'h5' => 'h5',
							'div' => 'div',
						),
						'dependency' => array( 'element' => 'heading', 'not_empty' => true ),
					),
					array(
						'type' => 'vc_link',
						'heading' => __( 'Link', 'total' ),
						'param_name' => 'heading_url',
						'group' => __( 'Heading', 'total' ),
						'dependency' => array( 'element' => 'heading', 'not_empty' => true ),
					),
					array(
						'type'  => 'vcex_font_family_select',
						'heading' => __( 'Font Family', 'total' ),
						'param_name' => 'heading_font_family',
						'std' => '',
						'group' => __( 'Heading', 'total' ),
						'dependency' => array( 'element' => 'heading', 'not_empty' => true ),
					),
					array(
						'type' => 'colorpicker',
						'heading' => __( 'Color', 'total' ),
						'param_name' => 'heading_color',
						'group' => __( 'Heading', 'total' ),
						'dependency' => array( 'element' => 'heading', 'not_empty' => true ),
					),
					array(
						'type' => 'vcex_font_weight',
						'heading' => __( 'Font Weight', 'total' ),
						'param_name' => 'heading_weight',
						'group' => __( 'Heading', 'total' ),
						'dependency' => array( 'element' => 'heading', 'not_empty' => true ),
					),
					array(
						'type' => 'vcex_text_transforms',
						'heading' => __( 'Text Transform', 'total' ),
						'param_name' => 'heading_transform',
						'group' => __( 'Heading', 'total' ),
						'dependency' => array( 'element' => 'heading', 'not_empty' => true ),
					),
					array(
						'type' => 'vcex_responsive_sizes',
						'target' => 'font-size',
						'heading' => __( 'Font Size', 'total' ),
						'param_name' => 'heading_size',
						'group' => __( 'Heading', 'total' ),
						'dependency' => array( 'element' => 'heading', 'not_empty' => true ),
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Letter Spacing', 'total' ),
						'param_name' => 'heading_letter_spacing',
						'group' => __( 'Heading', 'total' ),
						'dependency' => array( 'element' => 'heading', 'not_empty' => true ),
					),
					array(
						'type' => 'vcex_trbl',
						'heading' => __( 'Margin', 'total' ),
						'param_name' => 'heading_margin',
						'group' => __( 'Heading', 'total' ),
						'dependency' => array( 'element' => 'heading', 'not_empty' => true ),
					),

					// Content
					array(
						'type' => 'textarea_html',
						'holder' => 'div',
						'heading' => __( 'Content', 'total' ),
						'param_name' => 'content',
						'value' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.',
						'group' => __( 'Content', 'total' ),
					),
					array(
						'type' => 'vcex_trbl',
						'heading' => __( 'Padding', 'total' ),
						'param_name' => 'content_padding',
						'group' => __( 'Content', 'total' ),
						'dependency' => array( 'element' => 'content', 'not_empty' => true ),
					),
					array(
						'type' => 'vcex_responsive_sizes',
						'target' => 'font-size',
						'heading' => __( 'Font Size', 'total' ),
						'param_name' => 'content_font_size',
						'group' => __( 'Content', 'total' ),
						'dependency' => array( 'element' => 'content', 'not_empty' => true ),
					),
					array(
						'type' => 'vcex_font_weight',
						'heading' => __( 'Font Weight', 'total' ),
						'param_name' => 'content_font_weight',
						'group' => __( 'Content', 'total' ),
						'dependency' => array( 'element' => 'content', 'not_empty' => true ),
					),
					array(
						'type' => 'colorpicker',
						'heading' => __( 'Background', 'total' ),
						'param_name' => 'content_background',
						'group' => __( 'Content', 'total' ),
						'dependency' => array( 'element' => 'content', 'not_empty' => true ),
					),
					array(
						'type' => 'colorpicker',
						'heading' => __( 'Color', 'total' ),
						'param_name' => 'content_color',
						'group' => __( 'Content', 'total' ),
						'dependency' => array( 'element' => 'content', 'not_empty' => true ),
					),
					// Image
					array(
						'type' => 'attach_image',
						'heading' => __( 'Image', 'total' ),
						'param_name' => 'image',
						'group' => __( 'Image', 'total' ),
					),
					array(
						'type' => 'vcex_ofswitch',
						'std' => 'false',
						'heading' => __( 'Equal Heights?', 'total' ),
						'param_name' => 'equal_heights',
						'description' => __( 'Keeps the image column the same height as your content.', 'total' ),
						'group' => __( 'Image', 'total' ),
					),
					array(
						'type' => 'vc_link',
						'heading' => __( 'Image URL', 'total' ),
						'param_name' => 'image_url',
						'group' => __( 'Image', 'total' ),
					),
					array(
						'type' => 'dropdown',
						'heading' => __( 'Lightbox Type', 'total' ),
						'param_name' => 'image_lightbox',
						'group' => __( 'Image', 'total' ),
						'value' => array(
							__( 'None', 'total' ) => '',
							__( 'Self', 'total' ) => 'image',
							__( 'URL', 'total' ) => 'url',
							__( 'Auto Detect - slow', 'total' ) => 'auto-detect',
							__( 'Video', 'total' ) => 'video_embed',
							__( 'HTML5', 'total' ) => 'html5',
							__( 'Quicktime', 'total' ) => 'quicktime',
						),
					),
					array(
						'type' => 'vcex_image_sizes',
						'heading' => __( 'Image Size', 'total' ),
						'param_name' => 'img_size',
						'std' => 'wpex_custom',
						'group' => __( 'Image', 'total' ),
					),
					array(
						'type' => 'vcex_image_crop_locations',
						'heading' => __( 'Image Crop Location', 'total' ),
						'param_name' => 'img_crop',
						'std' => 'center-center',
						'group' => __( 'Image', 'total' ),
						'dependency' => array( 'element' => 'img_size', 'value' => 'wpex_custom' ),
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Image Width', 'total' ),
						'param_name' => 'img_width',
						'description' => __( 'Enter a width in pixels.', 'total' ),
						'group' => __( 'Image', 'total' ),
						'dependency' => array( 'element' => 'img_size', 'value' => 'wpex_custom' ),
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Image Height', 'total' ),
						'param_name' => 'img_height',
						'description' => __( 'Enter a height in pixels. Leave empty to disable vertical cropping and keep image proportions.', 'total' ),
						'group' => __( 'Image', 'total' ),
						'dependency' => array( 'element' => 'img_size', 'value' => 'wpex_custom' ),
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Border Radius', 'total' ),
						'param_name' => 'img_border_radius',
						'description' => __( 'Please enter a px value.', 'total' ),
						'group' => __( 'Image', 'total' ),
						'dependency' => array( 'element' => 'equal_heights', 'value' => 'false' ),
					),
					array(
						'type' => 'vcex_image_hovers',
						'heading' => __( 'CSS3 Image Hover', 'total' ),
						'param_name' => 'img_hover_style',
						'group' => __( 'Image', 'total' ),
					),
					array(
						'type' => 'vcex_image_filters',
						'heading' => __( 'Image Filter', 'total' ),
						'param_name' => 'img_filter',
						'group' => __( 'Image', 'total' ),
					),
					// Video
					array(
						'type' => 'textfield',
						'heading' => __( 'Video link', 'total' ),
						'param_name' => 'video',
						'description' => __('Enter a URL that is compatible with WP\'s built-in oEmbed feature. ', 'total' ),
						'group' => __( 'Video', 'total' ),
					),
					// Widths
					array(
						'type' => 'textfield',
						'heading' => __( 'Content Width', 'total' ),
						'param_name' => 'content_width',
						'value' => '50%',
						'group' => __( 'Widths', 'total' ),
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Image Width', 'total' ),
						'param_name' => 'media_width',
						'value' => '50%',
						'group' => __( 'Widths', 'total' ),
					),
					array(
						'type' => 'vcex_select_buttons',
						'heading' => __( 'Tablet Widths', 'total' ),
						'param_name' => 'tablet_widths',
						'group' => __( 'Widths', 'total' ),
						'std' => '',
						'choices' => array(
							'' => __( 'Inherit', 'total' ),
							'fullwidth' => __( 'Full-Width', 'total' ),
						),
					),
					array(
						'type' => 'vcex_select_buttons',
						'heading' => __( 'Phone Widths', 'total' ),
						'param_name' => 'phone_widths',
						'group' => __( 'Widths', 'total' ),
						'std' => '',
						'choices' => array(
							'' => __( 'Inherit', 'total' ),
							'fullwidth' => __( 'Full-Width', 'total' ),
						),
					),
					array(
						'type' => 'colorpicker',
						'heading' => __( 'Background', 'total' ),
						'param_name' => 'background',
						'group' => __( 'Design Options', 'total' ),
					),
					array(
						'type' => 'vcex_trbl',
						'heading' => __( 'Padding', 'total' ),
						'param_name' => 'padding',
						'group' => __( 'Design Options', 'total' ),
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Border', 'total' ),
						'description' => __( 'Please use the shorthand format: width style color. Enter 0px or "none" to disable border.', 'total' ),
						'param_name' => 'border',
						'group' => __( 'Design Options', 'total' ),
					),
				)
			);
		}

	}

}
new VCEX_Feature_Box_Shortcode;