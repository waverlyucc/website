<?php
/**
 * Registers the button shortcode and adds it to the Visual Composer
 *
 * @package Total WordPress Theme
 * @subpackage VC Templates
 * @version 4.8
 */

if ( ! class_exists( 'VCEX_Button_Shortcode' ) ) {

	class VCEX_Button_Shortcode {

		/**
		 * Main constructor
		 *
		 * @since 3.5.0
		 */
		public function __construct() {
			add_shortcode( 'vcex_button', array( $this, 'output' ) );
			vc_lean_map( 'vcex_button', array( $this, 'map' ) );
			add_filter( 'vc_edit_form_fields_attributes_vcex_button', array( $this, 'edit_form_fields' ) );
		}

		/**
		 * Shortcode output => Get template file and display shortcode
		 *
		 * @since 3.5.0
		 */
		public function output( $atts, $content = null ) {
			ob_start();
			include( locate_template( 'vcex_templates/vcex_button.php' ) );
			return ob_get_clean();
		}

		/**
		 * Map shortcode to VC
		 *
		 * @since 3.5.0
		 */
		public function map() {
			return array(
				'name' => __( 'Total Button', 'total' ),
				'description' => __( 'Eye catching button', 'total' ),
				'base' => 'vcex_button',
				'category' => wpex_get_theme_branding(),
				'icon' => 'vcex-total-button vcex-icon ticon ticon-external-link-square',
				'params' => array(
					// General
					array(
						'type' => 'dropdown',
						'heading' => __( 'Text Source', 'total' ),
						'param_name' => 'text_source',
						'value' => array(
							__( 'Custom Text', 'total' ) => 'custom_text',
							__( 'Custom Field', 'total' ) => 'custom_field',
							__( 'Callback Function', 'total' ) => 'callback_function',
						),
						'admin_label' => true,
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Text', 'total' ),
						'param_name' => 'content',
						'admin_label' => true,
						'std' => 'Button Text',
						'dependency' => array( 'element' => 'text_source', 'value' => 'custom_text' ),
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Custom Field ID', 'total' ),
						'param_name' => 'text_custom_field',
						'dependency' => array( 'element' => 'text_source', 'value' => 'custom_field' ),
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Callback Function', 'total' ),
						'param_name' => 'text_callback_function',
						'dependency' => array( 'element' => 'text_source', 'value' => 'callback_function' ),
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Unique Id', 'total' ),
						'param_name' => 'unique_id',
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
						'type' => 'textfield',
						'heading' => __( 'Extra class name', 'total' ),
						'param_name' => 'classes',
						'description' => __( 'Style particular content element differently - add a class name and refer to it in custom CSS.', 'total' ),
					),
					// Link
					array(
						'type' => 'dropdown',
						'heading' => __( 'On click action', 'total' ),
						'param_name' => 'onclick',
						'value' => array(
							__( 'Open custom link', 'total' ) => 'custom_link',
							__( 'Open custom field link', 'total' ) => 'custom_field',
							__( 'Open callback function link', 'total' ) => 'callback_function',
							__( 'Open image', 'total' ) => 'image',
							__( 'Open lightbox', 'total' ) => 'lightbox',
						),
						'group' => __( 'Link', 'total' ),
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'URL', 'total' ),
						'param_name' => 'url',
						'value' => '#',
						'dependency' => array( 'element' => 'onclick', 'value' => array( 'custom_link', 'lightbox' ) ),
						'group' => __( 'Link', 'total' ),
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Custom Field ID', 'total' ),
						'param_name' => 'url_custom_field',
						'dependency' => array( 'element' => 'onclick', 'value' => array( 'custom_field' ) ),
						'group' => __( 'Link', 'total' ),
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Callback Function', 'total' ),
						'param_name' => 'url_callback_function',
						'dependency' => array( 'element' => 'onclick', 'value' => array( 'callback_function' ) ),
						'group' => __( 'Link', 'total' ),
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Title Attribute', 'total' ),
						'param_name' => 'title',
						'group' => __( 'Link', 'total' ),
					),
					array(
						'type' => 'vcex_select_buttons',
						'heading' => __( 'Target', 'total' ),
						'param_name' => 'target',
						'std' => '',
						'choices' => array(
							'' => __( 'Self', 'total' ) ,
							'blank' => __( 'Blank', 'total' ),
							'local' => __( 'Local', 'total' ),
						),
						'group' => __( 'Link', 'total' ),
					),
					array(
						'type' => 'vcex_select_buttons',
						'heading' => __( 'Rel', 'total' ),
						'param_name' => 'rel',
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
						'heading' => __( 'Include Download Attribute', 'total' ),
						'param_name' => 'download_attribute',
						'group' => __( 'Link', 'total' ),
					),
					array(
						'type' => 'exploded_textarea',
						'heading' => __( 'Custom Data Attributes', 'total' ),
						'param_name' => 'data_attributes',
						'group' => __( 'Link', 'total' ),
						'description' => __( 'Enter your custom data attributes in the format of data|value. Hit enter after each set of data attributes.', 'total' ),
					),

					// Lightbox
					array(
						'type' => 'dropdown',
						'heading' => __( 'Type', 'total' ),
						'param_name' => 'lightbox_type',
						'value' => array(
							__( 'Auto Detect - slow', 'total' ) => '',
							__( 'iFrame', 'total' ) => 'iframe',
							__( 'Image', 'total' ) => 'image',
							__( 'Video', 'total' ) => 'video_embed',
							__( 'HTML5', 'total' ) => 'html5',
							__( 'Quicktime', 'total' ) => 'quicktime',
						),
						'description' => __( 'Auto detect depends on the iLightbox API, so by choosing your type it speeds things up and you also allows for HTTPS support.', 'total' ),
						'group' => __( 'Lightbox', 'total' ),
						'dependency' => array( 'element' => 'onclick', 'value' => 'lightbox' ),
					),
					array(
						'type' => 'vcex_ofswitch',
						'std' => 'true',
						'heading' => __( 'Lightbox Title', 'total' ),
						'param_name' => 'lightbox_title',
						'group' => __( 'Lightbox', 'total' ),
						'dependency' => array( 'element' => 'onclick', 'value' => 'lightbox' ),
					),
					array(
						'type' => 'attach_image',
						'heading' => __( 'Custom Image', 'total' ),
						'param_name' => 'image_attachment',
						'dependency' => array( 'element' => 'onclick', 'value' => array( 'image', 'lightbox' ) ),
						'group' => __( 'Lightbox', 'total' ),
					),
					array(
						'type' => 'attach_images',
						'heading' => __( 'Custom Gallery', 'total' ),
						'param_name' => 'lightbox_gallery',
						'description' => __( 'Select images to create a lightbox Gallery.', 'total' ),
						'group' => __( 'Lightbox', 'total' ),
						'dependency' => array( 'element' => 'onclick', 'value' => array( 'image', 'lightbox' ) ),
					),
					array(
						'type' => 'vcex_ofswitch',
						'std' => 'false',
						'heading' => __( 'Post Gallery', 'total' ),
						'param_name' => 'lightbox_post_gallery',
						'group' => __( 'Lightbox', 'total' ),
						'dependency' => array( 'element' => 'onclick', 'value' => array( 'image', 'lightbox' ) ),
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'HTML5 Webm URL', 'total' ),
						'param_name' => 'lightbox_video_html5_webm',
						'description' => __( 'Enter the URL to a video, SWF file, flash file or a website URL to open in lightbox.', 'total' ),
						'group' => __( 'Lightbox', 'total' ),
						'dependency' => array( 'element' => 'lightbox_type', 'value' => 'html5' ),
					),
					array(
						'type' => 'attach_image',
						'heading' => __( 'Lightbox HTML5 Poster Image', 'total' ),
						'param_name' => 'lightbox_poster_image',
						'dependency' => array( 'element' => 'lightbox_type', 'value' => 'html5' ),
						'group' => __( 'Lightbox', 'total' ),
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Lightbox Dimensions', 'total' ),
						'param_name' => 'lightbox_dimensions',
						'description' => __( 'Enter a custom width and height for your lightbox pop-up window. Use format widthxheight. Example: 900x600.', 'total' ),
						'group' => __( 'Lightbox', 'total' ),
						'dependency' => array( 'element' => 'lightbox_type', 'value' => array( 'iframe', 'html5', 'quicktime' ) ),
					),

					// Design
					array(
						'type' => 'vcex_button_styles',
						'heading' => __( 'Style', 'total' ),
						'param_name' => 'style',
						'group' => __( 'Design', 'total' ),
					),
					array(
						'type' => 'vcex_select_buttons',
						'heading' => __( 'Layout', 'total' ),
						'param_name' => 'layout',
						'choices' => 'button_layout',
						'group' => __( 'Design', 'total' ),
						'std' => 'inline',
						'description' => __( 'Note: If you add any custom settings in the container design tab the button can no longer render inline since the added elements are added as a wrapper.', 'total' )
					),
					array(
						'type' => 'vcex_text_alignments',
						'heading' => __( 'Alignment', 'total' ),
						'param_name' => 'align',
						'group' => __( 'Design', 'total' ),
						'description' => __( 'Note: Any alignment besides "Default" will add a wrapper around the button to position it so it will no longer be inline.', 'total' )
					),
					array(
						'type' => 'vcex_select_buttons',
						'heading' => __( 'Size', 'total' ),
						'param_name' => 'size',
						'std' => '',
						'choices' => 'button_size',
						'group' => __( 'Design', 'total' ),
						'dependency' => array( 'element' => 'font_size', 'is_empty' => true )
					),
					array(
						'type' => 'vcex_button_colors',
						'heading' => __( 'Color', 'total' ),
						'param_name' => 'color',
						'group' => __( 'Design', 'total' ),
					),
					array(
						'type' => 'colorpicker',
						'heading' => __( 'Background', 'total' ),
						'param_name' => 'custom_background',
						'group' => __( 'Design', 'total' ),
					),
					array(
						'type' => 'colorpicker',
						'heading' => __( 'Background: Hover', 'total' ),
						'param_name' => 'custom_hover_background',
						'group' => __( 'Design', 'total' ),
					),
					array(
						'type' => 'colorpicker',
						'heading' => __( 'Color', 'total' ),
						'param_name' => 'custom_color',
						'group' => __( 'Design', 'total' ),
					),
					array(
						'type' => 'colorpicker',
						'heading' => __( 'Color: Hover', 'total' ),
						'param_name' => 'custom_hover_color',
						'group' => __( 'Design', 'total' ),
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Custom Width', 'total' ),
						'param_name' => 'width',
						'description' => __( 'Please use a pixel or percentage value.', 'total' ),
						'group' => __( 'Design', 'total' ),
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Border Radius', 'total' ),
						'param_name' => 'border_radius',
						'description' => __( 'Please enter a px value.', 'total' ),
						'group' => __( 'Design', 'total' ),
					),
					array(
						'type' => 'vcex_trbl',
						'heading' => __( 'Padding', 'total' ),
						'param_name' => 'font_padding',
						'group' => __( 'Design', 'total' ),
					),
					array(
						'type' => 'vcex_trbl',
						'heading' => __( 'Margin', 'total' ),
						'param_name' => 'margin',
						'group' => __( 'Design', 'total' ),
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Border', 'total' ),
						'param_name' => 'border',
						'description' => __( 'Please use the shorthand format: width style color. Enter 0px or "none" to disable border.', 'total' ),
						'group' => __( 'Design', 'total' ),
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
						'type' => 'vcex_text_transforms',
						'heading' => __( 'Text Transform', 'total' ),
						'param_name' => 'text_transform',
						'group' => __( 'Typography', 'total' ),
					),
					array(
						'type' => 'vcex_font_weight',
						'heading' => __( 'Font Weight', 'total' ),
						'param_name' => 'font_weight',
						'group' => __( 'Typography', 'total' ),
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
						'group' => __( 'Icons', 'total' ),
					),
					array(
						'type' => 'iconpicker',
						'heading' => __( 'Icon Left', 'total' ),
						'param_name' => 'icon_left',
						'settings' => array(
							'emptyIcon' => true,
							'iconsPerPage' => 200,
						),
						'dependency' => array( 'element' => 'icon_type', 'value' => 'fontawesome' ),
						'group' => __( 'Icons', 'total' ),
					),
					array(
						'type' => 'iconpicker',
						'heading' => __( 'Icon Left', 'total' ),
						'param_name' => 'icon_left_openiconic',
						'settings' => array(
							'emptyIcon' => true,
							'type' => 'openiconic',
							'iconsPerPage' => 200,
						),
						'dependency' => array( 'element' => 'icon_type', 'value' => 'openiconic' ),
						'group' => __( 'Icons', 'total' ),
					),
					array(
						'type' => 'iconpicker',
						'heading' => __( 'Icon Left', 'total' ),
						'param_name' => 'icon_left_typicons',
						'settings' => array(
							'emptyIcon' => true,
							'type' => 'typicons',
							'iconsPerPage' => 200,
						),
						'dependency' => array( 'element' => 'icon_type', 'value' => 'typicons' ),
						'group' => __( 'Icons', 'total' ),
					),
					array(
						'type' => 'iconpicker',
						'heading' => __( 'Icon Left', 'total' ),
						'param_name' => 'icon_left_entypo',
						'settings' => array(
							'emptyIcon' => true,
							'type' => 'entypo',
							'iconsPerPage' => 300,
						),
						'dependency' => array( 'element' => 'icon_type', 'value' => 'entypo' ),
						'group' => __( 'Icons', 'total' ),
					),
					array(
						'type' => 'iconpicker',
						'heading' => __( 'Icon Left', 'total' ),
						'param_name' => 'icon_left_linecons',
						'settings' => array(
							'emptyIcon' => true,
							'type' => 'linecons',
							'iconsPerPage' => 200,
						),
						'dependency' => array( 'element' => 'icon_type', 'value' => 'linecons' ),
						'group' => __( 'Icons', 'total' ),
					),
					array(
						'type' => 'iconpicker',
						'heading' => __( 'Icon Left', 'total' ),
						'param_name' => 'icon_left_pixelicons',
						'settings' => array(
							'emptyIcon' => true,
							'type' => 'pixelicons',
							'source' => vcex_pixel_icons(),
						),
						'dependency' => array( 'element' => 'icon_type', 'value' => 'pixelicons' ),
						'group' => __( 'Icons', 'total' ),
					),
					array(
						'type' => 'iconpicker',
						'heading' => __( 'Icon Right', 'total' ),
						'param_name' => 'icon_right',
						'settings' => array(
							'emptyIcon' => true,
							'iconsPerPage' => 200,
						),
						'dependency' => array( 'element' => 'icon_type', 'value' => 'fontawesome' ),
						'group' => __( 'Icons', 'total' ),
					),
					array(
						'type' => 'iconpicker',
						'heading' => __( 'Icon Right', 'total' ),
						'param_name' => 'icon_right_openiconic',
						'settings' => array(
							'emptyIcon' => true,
							'type' => 'openiconic',
							'iconsPerPage' => 200,
						),
						'dependency' => array( 'element' => 'icon_type', 'value' => 'openiconic' ),
						'group' => __( 'Icons', 'total' ),
					),
					array(
						'type' => 'iconpicker',
						'heading' => __( 'Icon Right', 'total' ),
						'param_name' => 'icon_right_typicons',
						'settings' => array(
							'emptyIcon' => true,
							'type' => 'typicons',
							'iconsPerPage' => 200,
						),
						'dependency' => array( 'element' => 'icon_type', 'value' => 'typicons' ),
						'group' => __( 'Icons', 'total' ),
					),
					array(
						'type' => 'iconpicker',
						'heading' => __( 'Icon Right', 'total' ),
						'param_name' => 'icon_right_entypo',
						'settings' => array(
							'emptyIcon' => true,
							'type' => 'entypo',
							'iconsPerPage' => 300,
						),
						'dependency' => array( 'element' => 'icon_type', 'value' => 'entypo' ),
						'group' => __( 'Icons', 'total' ),
					),
					array(
						'type' => 'iconpicker',
						'heading' => __( 'Icon Right', 'total' ),
						'param_name' => 'icon_right_linecons',
						'settings' => array(
							'emptyIcon' => true,
							'type' => 'linecons',
							'iconsPerPage' => 200,
						),
						'dependency' => array( 'element' => 'icon_type', 'value' => 'linecons' ),
						'group' => __( 'Icons', 'total' ),
					),
					array(
						'type' => 'iconpicker',
						'heading' => __( 'Icon Right', 'total' ),
						'param_name' => 'icon_right_pixelicons',
						'settings' => array(
							'emptyIcon' => true,
							'type' => 'pixelicons',
							'source' => vcex_pixel_icons(),
						),
						'dependency' => array( 'element' => 'icon_type', 'value' => 'pixelicons' ),
						'group' => __( 'Icons', 'total' ),
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Left Icon: Right Padding', 'total' ),
						'param_name' => 'icon_left_padding',
						'group' => __( 'Icons', 'total' ),
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Left Icon: Hover Transform x', 'total' ),
						'param_name' => 'icon_left_transform',
						'group' => __( 'Icons', 'total' ),
						'description' => __( 'Enter a value to move the icon horizontally on hover. You can enter a px or em value. Use negative values to go left and positive values to go right. Example: 10px would move the icon 10 pixels to the right while -10px would move the icon 10 pixels to the left.', 'total' ),
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Right Icon: Left Padding', 'total' ),
						'param_name' => 'icon_right_padding',
						'group' => __( 'Icons', 'total' ),
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Right Icon: Hover Transform x', 'total' ),
						'param_name' => 'icon_right_transform',
						'group' => __( 'Icons', 'total' ),
						'description' => __( 'Enter a value to move the icon horizontally on hover. You can enter a px or em value. Use negative values to go left and positive values to go right. Example: 10px would move the icon 10 pixels to the right while -10px would move the icon 10 pixels to the left.', 'total' ),
					),
					// Design options
					array(
						'type' => 'css_editor',
						'heading' => __( 'Container Design', 'total' ),
						'param_name' => 'css_wrap',
						'group' => __( 'Container Design', 'total' ),
					),
					// Deprecated
					array( 'type' => 'hidden', 'param_name' => 'lightbox' ),
					array( 'type' => 'hidden', 'param_name' => 'lightbox_image' ),
				)
			);
		}

		/**
		 * Update fields on edit
		 *
		 * @since 3.5.0
		 */
		public function edit_form_fields( $atts ) {
			if ( ! empty( $atts['lightbox_image'] ) ) {
				$atts['image_attachment'] = $atts['lightbox_image'];
				unset( $atts['lightbox_image'] );
			}
			if ( isset( $atts['lightbox'] ) && 'true' == $atts['lightbox'] ) {
				$atts['onclick'] = 'lightbox';
			}
			return $atts;
		}

	}

}
new VCEX_Button_Shortcode;