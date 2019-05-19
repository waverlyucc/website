<?php
/**
 * Visual Composer Image Banner Module
 *
 * @package Total WordPress Theme
 * @subpackage VC Templates
 * @version 4.8
 *
 * @todo	Add Design Option settings (css) but it will require migrating the "padding" param
 *			to prevent conflicts with the css param.
 */

if ( ! class_exists( 'VCEX_Image_Banner_Shortcode' ) ) {

	class VCEX_Image_Banner_Shortcode {

		/**
		 * Main constructor
		 *
		 * @since 4.3
		 */
		public function __construct() {
			add_shortcode( 'vcex_image_banner', array( $this, 'output' ) );
			vc_lean_map( 'vcex_image_banner', array( $this, 'map' ) );
		}
		/**
		 * Shortcode output => Get template file and display shortcode
		 *
		 * @since 4.3
		 */
		public function output( $atts, $content = null ) {
			ob_start();
			include( locate_template( 'vcex_templates/vcex_image_banner.php' ) );
			return ob_get_clean();
		}

		/**
		 * Map shortcode to VC
		 *
		 * @since 4.3
		 */
		public function map() {
			return array(
				'name' => __( 'Image Banner', 'total' ),
				'description' => __( 'Image Banner with overlay text', 'total' ),
				'base' => 'vcex_image_banner',
				'icon' => 'vcex-image-banner vcex-icon ticon ticon-picture-o',
				'category' => wpex_get_theme_branding(),
				'params' => array(
					// General
					array(
						'type' => 'dropdown',
						'heading' => __( 'Background Image Source', 'total' ),
						'param_name' => 'image_source',
						'std' => 'media_library',
						'value' => array(
							__( 'Media Library', 'total' ) => 'media_library',
							__( 'Custom Field', 'total' ) => 'custom_field',
							__( 'Featured Image', 'total' ) => 'featured',
						),
					),
					array(
						'type' => 'attach_image',
						'heading' => __( 'Background Image', 'total' ),
						'param_name' => 'image',
						'dependency' => array( 'element' => 'image_source', 'value' => 'media_library' ),
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Custom Field Name', 'total' ),
						'param_name' => 'image_custom_field',
						'dependency' => array( 'element' => 'image_source', 'value' => 'custom_field' ),
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Background Image Position', 'total' ),
						'param_name' => 'image_position',
						'description' => __( 'Enter your custom background position. Example: "center center"', 'total' ),
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Module Width', 'total' ),
						'param_name' => 'width',
						'value' => '',
					),
					array(
						'type' => 'vcex_text_alignments',
						'heading' => __( 'Module Align', 'total' ),
						'param_name' => 'align',
						'dependency' => array( 'element' => 'width', 'not_empty' => true ),
					),
					array(
						'type' => 'vcex_text_alignments',
						'heading' => __( 'Content Align', 'total' ),
						'param_name' => 'content_align',
					),
					array(
						'type' => 'vcex_trbl',
						'heading' => __( 'Custom Inner Padding', 'total' ),
						'param_name' => 'padding',
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Text Width', 'total' ),
						'param_name' => 'content_width',
						'description' => __( 'Enter a max width to constrain the inner text. You can enter a pixel value such as 200px or a percentage such as 50%.', 'total' ),
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Extra class name', 'total' ),
						'description' => __( 'Style particular content element differently - add a class name and refer to it in custom CSS.', 'total' ),
						'param_name' => 'el_class',
					),
					vcex_vc_map_add_css_animation(),
					// Heading
					array(
						'type' => 'textfield',
						'heading' => __( 'Heading', 'total' ),
						'param_name' => 'heading',
						'value' => __( 'Add Your Heading', 'total' ),
						'group' => __( 'Heading', 'total' ),
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Bottom Padding', 'total' ),
						'param_name' => 'heading_bottom_padding',
						'group' => __( 'Heading', 'total' ),
					),
					array(
						'heading' => __( 'Tag', 'total' ),
						'param_name' => 'heading_tag',
						'type' => 'vcex_select_buttons',
						'std' => 'div',
						'choices' => 'html_tag',
						'group' => __( 'Heading', 'total' ),
					),
					array(
						'type' => 'colorpicker',
						'heading' => __( 'Color', 'total' ),
						'param_name' => 'heading_color',
						'group' => __( 'Heading', 'total' ),
					),
					array(
						'type' => 'vcex_font_family_select',
						'heading' => __( 'Font Family', 'total' ),
						'param_name' => 'heading_font_family',
						'group' => __( 'Heading', 'total' ),
					),
					array(
						'type' => 'vcex_responsive_sizes',
						'heading' => __( 'Font Size', 'total' ),
						'param_name' => 'heading_font_size',
						'group' => __( 'Heading', 'total' ),
						'target' => 'font-size',
					),
					array(
						'type' => 'vcex_font_weight',
						'heading' => __( 'Font Weight', 'total' ),
						'param_name' => 'heading_font_weight',
						'group' => __( 'Heading', 'total' ),
					),
					array(
						'type' => 'vcex_ofswitch',
						'heading' => __( 'Italic', 'total' ),
						'param_name' => 'heading_italic',
						'std' => 'false',
						'group' => __( 'Heading', 'total' ),
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Line Height', 'total' ),
						'param_name' => 'heading_line_height',
						'group' => __( 'Heading', 'total' ),
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Letter Spacing', 'total' ),
						'param_name' => 'heading_letter_spacing',
						'group' => __( 'Heading', 'total' ),
					),
					// Caption
					array(
						'type' => 'textfield',
						'heading' => __( 'Caption', 'total' ),
						'param_name' => 'caption',
						'value' => __( 'Add your custom caption', 'total' ),
						'group' => __( 'Caption', 'total' ),
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Bottom Padding', 'total' ),
						'param_name' => 'caption_bottom_padding',
						'group' => __( 'Caption', 'total' ),
					),
					array(
						'type' => 'colorpicker',
						'heading' => __( 'Color', 'total' ),
						'param_name' => 'caption_color',
						'group' => __( 'Caption', 'total' ),
					),
					array(
						'type' => 'vcex_font_family_select',
						'heading' => __( 'Font Family', 'total' ),
						'param_name' => 'caption_font_family',
						'group' => __( 'Caption', 'total' ),
					),
					array(
						'type' => 'vcex_responsive_sizes',
						'heading' => __( 'Font Size', 'total' ),
						'param_name' => 'caption_font_size',
						'group' => __( 'Caption', 'total' ),
						'target' => 'font-size',
					),
					array(
						'type' => 'vcex_font_weight',
						'heading' => __( 'Font Weight', 'total' ),
						'param_name' => 'caption_font_weight',
						'group' => __( 'Caption', 'total' ),
					),
					array(
						'type' => 'vcex_ofswitch',
						'heading' => __( 'Italic', 'total' ),
						'param_name' => 'caption_italic',
						'std' => 'false',
						'group' => __( 'Caption', 'total' ),
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Line Height', 'total' ),
						'param_name' => 'caption_line_height',
						'group' => __( 'Caption', 'total' ),
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Letter Spacing', 'total' ),
						'param_name' => 'caption_letter_spacing',
						'group' => __( 'Caption', 'total' ),
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
						'heading' => __( 'Local Scroll', 'total' ),
						'param_name' => 'link_local_scroll',
						'std' => 'false',
						'group' => __( 'Link', 'total' ),
					),
					// Overlay
					array(
						'type' => 'vcex_ofswitch',
						'heading' => __( 'Overlay', 'total' ),
						'param_name' => 'overlay',
						'std' => 'true',
						'group' => __( 'Overlay', 'total' ),
					),
					array(
						'type' => 'colorpicker',
						'heading' => __( 'Overlay Color', 'total' ),
						'param_name' => 'overlay_color',
						'group' => __( 'Overlay', 'total' ),
						'description' => __( 'If you select a custom overlay color make sure to select a custom alpha transparency so that your background image is still visible.', 'total' ),
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Overlay Opacity', 'total' ),
						'param_name' => 'overlay_opacity',
						'group' => __( 'Overlay', 'total' ),
					),
					// Button
					array(
						'type' => 'vcex_ofswitch',
						'heading' => __( 'Button', 'total' ),
						'param_name' => 'button',
						'std' => 'false',
						'group' => __( 'Button', 'total' ),
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Text', 'total' ),
						'param_name' => 'button_text',
						'group' => __( 'Button', 'total' ),
						'value' => __( 'learn more', 'total' ),
						'dependency' => array( 'element' => 'button', 'value' => array( 'true' ) ),
					),
					array(
						'type' => 'vcex_font_family_select',
						'heading' => __( 'Font Family', 'total' ),
						'param_name' => 'button_font_family',
						'group' => __( 'Button', 'total' ),
						'dependency' => array( 'element' => 'button', 'value' => array( 'true' ) ),
					),
					array(
						'type' => 'vcex_button_styles',
						'heading' => __( 'Style', 'total' ),
						'param_name' => 'button_style',
						'group' => __( 'Button', 'total' ),
						'dependency' => array( 'element' => 'button', 'value' => array( 'true' ) ),
					),
					array(
						'type' => 'vcex_button_colors',
						'heading' => __( 'Color', 'total' ),
						'param_name' => 'button_color',
						'group' => __( 'Button', 'total' ),
						'dependency' => array( 'element' => 'button', 'value' => array( 'true' ) ),
					),
					array(
						'type' => 'vcex_font_weight',
						'heading' => __( 'Font Weight', 'total' ),
						'param_name' => 'button_font_weight',
						'group' => __( 'Button', 'total' ),
						'dependency' => array( 'element' => 'button', 'value' => array( 'true' ) ),
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Letter Spacing', 'total' ),
						'param_name' => 'button_letter_spacing',
						'group' => __( 'Button', 'total' ),
						'dependency' => array( 'element' => 'button', 'value' => array( 'true' ) ),
					),
					array(
						'type' => 'vcex_responsive_sizes',
						'heading' => __( 'Font Size', 'total' ),
						'param_name' => 'button_font_size',
						'group' => __( 'Button', 'total' ),
						'target' => 'font-size',
						'dependency' => array( 'element' => 'button', 'value' => array( 'true' ) ),
					),
					array(
						'type' => 'vcex_ofswitch',
						'heading' => __( 'Italic', 'total' ),
						'param_name' => 'button_italic',
						'std' => 'false',
						'group' => __( 'Button', 'total' ),
						'dependency' => array( 'element' => 'button', 'value' => array( 'true' ) ),
					),
					array(
						'type' => 'colorpicker',
						'heading' => __( 'Background', 'total' ),
						'param_name' => 'button_custom_background',
						'group' => __( 'Button', 'total' ),
						'dependency' => array( 'element' => 'button', 'value' => array( 'true' ) ),
					),
					array(
						'type' => 'colorpicker',
						'heading' => __( 'Background: Hover', 'total' ),
						'param_name' => 'button_custom_hover_background',
						'group' => __( 'Button', 'total' ),
						'dependency' => array( 'element' => 'button', 'value' => array( 'true' ) ),
					),
					array(
						'type' => 'colorpicker',
						'heading' => __( 'Color', 'total' ),
						'param_name' => 'button_custom_color',
						'group' => __( 'Button', 'total' ),
						'dependency' => array( 'element' => 'button', 'value' => array( 'true' ) ),
					),
					array(
						'type' => 'colorpicker',
						'heading' => __( 'Color: Hover', 'total' ),
						'param_name' => 'button_custom_hover_color',
						'group' => __( 'Button', 'total' ),
						'dependency' => array( 'element' => 'button', 'value' => array( 'true' ) ),
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Custom Width', 'total' ),
						'param_name' => 'button_width',
						'description' => __( 'Please use a pixel or percentage value.', 'total' ),
						'group' => __( 'Button', 'total' ),
						'dependency' => array( 'element' => 'button', 'value' => array( 'true' ) ),
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Border Radius', 'total' ),
						'param_name' => 'button_border_radius',
						'description' => __( 'Please enter a px value.', 'total' ),
						'group' => __( 'Button', 'total' ),
						'dependency' => array( 'element' => 'button', 'value' => array( 'true' ) ),
					),
					array(
						'type' => 'vcex_trbl',
						'heading' => __( 'Padding', 'total' ),
						'param_name' => 'button_padding',
						'group' => __( 'Button', 'total' ),
						'dependency' => array( 'element' => 'button', 'value' => array( 'true' ) ),
					),
					// Hover
					array(
						'type' => 'vcex_ofswitch',
						'heading' => __( 'Text on Hover', 'total' ),
						'param_name' => 'show_on_hover',
						'std' => 'false',
						'group' => __( 'Hover', 'total' ),
					),
					array(
						'type' => 'vcex_select_buttons',
						'heading' => __( 'Hover Text Animation', 'total' ),
						'param_name' => 'show_on_hover_anim',
						'std' => 'fade-up',
						'choices' => array(
							'fade-up' => __( 'Fade Up', 'total' ),
							'fade' => __( 'Fade', 'total' ),
						),
						'group' => __( 'Hover', 'total' ),
						'dependency' => array( 'element' => 'show_on_hover', 'value' => array( 'true' ) ),
					),
					array(
						'type' => 'vcex_ofswitch',
						'heading' => __( 'Hover Image Zoom', 'total' ),
						'param_name' => 'image_zoom',
						'std' => 'false',
						'group' => __( 'Hover', 'total' ),
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Hover Image Zoom Speed', 'total' ),
						'param_name' => 'image_zoom_speed',
						'std' => '0.4',
						'description' => __( 'Value in seconds', 'total' ),
						'group' => __( 'Hover', 'total' ),
					),
				)
			);
		}

	}
}
new VCEX_Image_Banner_Shortcode;