<?php
/**
 * Visual Composer Icon Box
 *
 * @package Total WordPress Theme
 * @subpackage VC Functions
 * @version 4.6.5
 */

if ( ! class_exists( 'VCEX_Icon_Box_Shortcode' ) ) {

	class VCEX_Icon_Box_Shortcode {

		/**
		 * Main constructor
		 *
		 * @since 3.5.0
		 */
		public function __construct() {
			add_shortcode( 'vcex_icon_box', array( $this, 'output' ) );
			vc_lean_map( 'vcex_icon_box', array( $this, 'map' ) );
			add_filter( 'vc_edit_form_fields_attributes_vcex_icon_box', array( $this, 'edit_fields' ), 10 );
		}

		/**
		 * Shortcode output => Get template file and display shortcode
		 *
		 * @since 3.5.0
		 */
		public function output( $atts, $content = null ) {
			ob_start();
			include( locate_template( 'vcex_templates/vcex_icon_box.php' ) );
			return ob_get_clean();
		}

		/**
		 * Edit form fields
		 *
		 * @since 3.5.0
		 */
		public function edit_fields( $atts ) {

			// Set font family if icon is defined
			if ( isset( $atts['icon'] ) && empty( $atts['icon_type'] ) ) {
				$atts['icon_type'] = 'fontawesome';
				if ( strpos( $atts['icon'], 'fa' ) === false ) {
					$atts['icon'] = 'fa fa-'. $atts['icon'];
				}
			}

			// Return $atts
			return $atts;
		}

		/**
		 * Map shortcode to VC
		 *
		 * @since 3.5.0
		 */
		public function map() {
			return array(
				'name' => __( 'Icon Box', 'total' ),
				'base' => 'vcex_icon_box',
				'category' => wpex_get_theme_branding(),
				'icon' => 'vcex-icon-box vcex-icon fa fa-star',
				'description' => __( 'Content box with icon', 'total' ),
				'params' => array(
					// General
					array(
						'type' => 'dropdown',
						'heading' => __( 'Style', 'total' ),
						'param_name' => 'style',
						'value' => vcex_icon_box_styles(),
						'description' => __( 'For greater control select left, right or top icon styles then go to the "Design" tab to modify the icon box design.', 'total' ),
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Unique Id', 'total' ),
						'param_name' => 'unique_id',
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Extra class name', 'total' ),
						'param_name' => 'classes',
						'description' => __( 'Style particular content element differently - add a class name and refer to it in custom CSS.', 'total' ),
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
						'heading' => __( 'Alignment', 'total' ),
						'param_name' => 'alignment',
						'std' => '',
						'dependency' => array( 'element' => 'style', 'value' => array( 'two' ) ),
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Container Left Padding', 'total' ),
						'param_name' => 'container_left_padding',
						'dependency' => array( 'element' => 'style', 'value' => array( 'one' ) ),
						'description' => __( 'Use to offset your icon size.', 'total' ),
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Container Right Padding', 'total' ),
						'param_name' => 'container_right_padding',
						'description' => __( 'Please enter a px value.', 'total' ),
						'dependency' => array( 'element' => 'style', 'value' => 'seven' ),
						'description' => __( 'Use to offset your icon size.', 'total' ),
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
						'type' => 'vcex_responsive_sizes',
						'heading' => __( 'Font Size', 'total' ),
						'param_name' => 'font_size',
						'group' => __( 'Content', 'total' ),
						'target' => 'font-size',
					),
					array(
						'type' => 'colorpicker',
						'heading' => __( 'Color', 'total' ),
						'param_name' => 'font_color',
						'group' => __( 'Content', 'total' ),
					),
					// Heading
					array(
						'type' => 'textfield',
						'heading' => __( 'Heading', 'total' ),
						'param_name' => 'heading',
						'std' => 'Sample Heading',
						'group' => __( 'Heading', 'total' ),
					),
					array(
						'type'  => 'vcex_font_family_select',
						'heading' => __( 'Font Family', 'total' ),
						'param_name' => 'heading_font_family',
						'group' => __( 'Heading', 'total' ),
					),
					array(
						'type' => 'colorpicker',
						'heading' => __( 'Color', 'total' ),
						'param_name' => 'heading_color',
						'group' => __( 'Heading', 'total' ),
					),
					array(
						'heading' => __( 'Tag', 'total' ),
						'param_name' => 'heading_type',
						'type' => 'vcex_select_buttons',
						'std' => 'div',
						'choices' => 'html_tag',
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
						'heading' => __( 'Font Size', 'total' ),
						'param_name' => 'heading_size',
						'group' => __( 'Heading', 'total' ),
						'target' => 'font-size',
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Letter Spacing', 'total' ),
						'param_name' => 'heading_letter_spacing',
						'group' => __( 'Heading', 'total' ),
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Bottom Margin', 'total' ),
						'param_name' => 'heading_bottom_margin',
						'group' => __( 'Heading', 'total' ),
					),
					// Icons
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
						'value' => 'fa fa-info-circle',
						'settings' => array(
							'emptyIcon' => true,
							'iconsPerPage' => 200,
						),
						'dependency' => array(
							'element' => 'icon_type',
							'value' => 'fontawesome',
						),
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
						'dependency' => array(
							'element' => 'icon_type',
							'value' => 'openiconic',
						),
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
						'dependency' => array(
							'element' => 'icon_type',
							'value' => 'typicons',
						),
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
						'dependency' => array(
							'element' => 'icon_type',
							'value' => 'entypo',
						),
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
						'dependency' => array(
							'element' => 'icon_type',
							'value' => 'linecons',
						),
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
						'dependency' => array(
							'element' => 'icon_type',
							'value' => 'pixelicons',
						),
						'group' => __( 'Icon', 'total' ),
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Icon Font Alternative Classes', 'total' ),
						'param_name' => 'icon_alternative_classes',
						'group' => __( 'Icon', 'total' ),
					),
					array(
						'type' => 'colorpicker',
						'heading' => __( 'Color', 'total' ),
						'param_name' => 'icon_color',
						'group' => __( 'Icon', 'total' ),
					),
					array(
						'type' => 'colorpicker',
						'heading' => __( 'Background', 'total' ),
						'param_name' => 'icon_background',
						'group' => __( 'Icon', 'total' ),
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Bottom Margin', 'total' ),
						'param_name' => 'icon_bottom_margin',
						'dependency' => array( 'element' => 'style', 'value' => array( 'two', 'three', 'four', 'five', 'six' ) ),
						'group' => __( 'Icon', 'total' ),
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Icon Size', 'total' ),
						'param_name' => 'icon_size',
						'group' => __( 'Icon', 'total' ),
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Border Radius', 'total' ),
						'param_name' => 'icon_border_radius',
						'description' => __( 'For a circle enter 50%.', 'total' ),
						'group' => __( 'Icon', 'total' ),
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Width', 'total' ),
						'param_name' => 'icon_width',
						'group' => __( 'Icon', 'total' ),
						'description' => __( 'If you are using the left-style icon box be sure to also alter the "Container Left Padding" setting under the general tab to allow space for your custom icon size', 'total' )
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Height', 'total' ),
						'param_name' => 'icon_height',
						'group' => __( 'Icon', 'total' ),
					),
					// Icon
					array(
						'type' => 'attach_image',
						'heading' => __( 'Icon Image Alternative', 'total' ),
						'param_name' => 'image',
						'group' => __( 'Image', 'total' ),
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Bottom Margin', 'total' ),
						'param_name' => 'image_bottom_margin',
						'group' => __( 'Image', 'total' ),
						'dependency' => array( 'element' => 'style', 'value' => array( 'two' ) ),
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Width', 'total' ),
						'param_name' => 'image_width',
						'group' => __( 'Image', 'total' ),
						'description' => __( 'If you are using the "Left Icon" style, be sure to also alter the "Container Left Padding" setting under the general tab to allow space for your custom icon size.', 'total' )
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Height', 'total' ),
						'param_name' => 'image_height',
						'group' => __( 'Image', 'total' ),
					),
					array(
						'type' => 'vcex_ofswitch',
						'std' => 'true',
						'heading' => __( 'Resize Image', 'total' ),
						'param_name' => 'resize_image',
						'group' => __( 'Image', 'total' ),
						'description' => __( 'Enable to run the image through the resizing script, disable to simply resize via CSS.', 'total' )
					),
					// URL
					array(
						'type' => 'textfield',
						'heading' => __( 'URL', 'total' ),
						'param_name' => 'url',
						'group' => __( 'URL', 'total' ),
					),
					array(
						'type' => 'vcex_select_buttons',
						'heading' => __( 'Target', 'total' ),
						'param_name' => 'url_target',
						'std' => 'self',
						'choices' => array(
							'self' => __( 'Self', 'total' ),
							'_blank' => __( 'Blank', 'total' ),
							'local' => __( 'Local', 'total' ),
						),
						'group' => __( 'URL', 'total' ),
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
						'group' => __( 'URL', 'total' ),
					),
					array(
						'type' => 'vcex_ofswitch',
						'heading' => __( 'Link Container Wrap', 'total' ),
						'param_name' => 'url_wrap',
						'std' => 'false',
						'group' => __( 'URL', 'total' ),
						'description' => __( 'Apply the link to the entire wrapper?', 'total' ),
						'dependency' => array( 'element' => 'style', 'value' => array( 'one', 'two', 'three', 'seven' ) ),
					),
					// Design
					array(
						'type' => 'css_editor',
						'heading' => __( 'Container Design', 'total' ),
						'param_name' => 'css',
						'description' => __( 'If any of these are defined it will add a new wrapper around your icon box with the custom CSS applied to it.', 'total' ),
						'group' => __( 'Container Design', 'total' ),
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Border Radius', 'total' ),
						'param_name' => 'border_radius',
						'group' => __( 'Container Design', 'total' ),
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Width', 'total' ),
						'param_name' => 'width',
						'group' => __( 'Container Design', 'total' ),
					),
					array(
						'type' => 'colorpicker',
						'heading' => __( 'Background: Hover', 'total' ),
						'param_name' => 'hover_background',
						'description' => __( 'Will add a hover background color to your entire icon box or replace the current hover color for specific icon box styles.', 'total' ),
						'group' => __( 'Container Design', 'total' ),
					),
					array(
						'type' => 'vcex_ofswitch',
						'std' => 'false',
						'heading' => __( 'White Text On Hover', 'total' ),
						'param_name' => 'hover_white_text',
						'group' => __( 'Container Design', 'total' ),
					),
				),
			);
		}
	}
}
new VCEX_Icon_Box_Shortcode;