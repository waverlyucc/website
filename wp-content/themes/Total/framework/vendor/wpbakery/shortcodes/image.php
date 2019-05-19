<?php
/**
 * Visual Composer Image Module
 *
 * @package Total WordPress Theme
 * @subpackage VC Templates
 * @version 4.8
 */

if ( ! class_exists( 'VCEX_Image_Shortcode' ) ) {

	class VCEX_Image_Shortcode {

		/**
		 * Main constructor
		 *
		 * @since 4.5
		 */
		public function __construct() {
			add_shortcode( 'vcex_image', array( $this, 'output' ) );
			vc_lean_map( 'vcex_image', array( $this, 'map' ) );
		}
		/**
		 * Shortcode output => Get template file and display shortcode
		 *
		 * @since 4.5
		 */
		public function output( $atts, $content = null ) {
			ob_start();
			include( locate_template( 'vcex_templates/vcex_image.php' ) );
			return ob_get_clean();
		}

		/**
		 * Map shortcode to VC
		 *
		 * @since 4.5
		 */
		public function map() {
			return array(
				'name' => __( 'Image', 'total' ),
				'description' => __( 'Single Image', 'total' ),
				'base' => 'vcex_image',
				'icon' => 'vcex-image-banner vcex-icon ticon ticon-picture-o',
				'category' => wpex_get_theme_branding(),
				'params' => array(
					array(
						'type' => 'dropdown',
						'heading' => __( 'Source', 'total' ),
						'param_name' => 'source',
						'std' => 'media_library',
						'value' => array(
							__( 'Media Library', 'total' ) => 'media_library',
							__( 'External', 'total' ) => 'external',
							__( 'Custom Field', 'total' ) => 'custom_field',
							__( 'Featured Image', 'total' ) => 'featured',
							__( 'Post Author Avatar', 'total' ) => 'author_avatar',
							__( 'Current User Avatar', 'total' ) => 'user_avatar',
						),
					),
					array(
						'type' => 'attach_image',
						'heading' => __( 'Image', 'total' ),
						'param_name' => 'image_id',
						'dependency' => array( 'element' => 'source', 'value' => 'media_library' ),
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'External Image URL', 'total' ),
						'param_name' => 'external_image',
						'dependency' => array( 'element' => 'source', 'value' => 'external' ),
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Custom Field Name', 'total' ),
						'param_name' => 'custom_field_name',
						'dependency' => array( 'element' => 'source', 'value' => 'custom_field' ),
					),
					array(
						'type' => 'vc_link',
						'heading' => __( 'Link', 'total' ),
						'param_name' => 'link',
						'dependency' => array( 'element' => 'lightbox', 'value' => 'false' ),
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Alt Attribute', 'total' ),
						'param_name' => 'alt_attr',
					),
					array(
						'type' => 'vcex_ofswitch',
						'heading' => __( 'Lightbox', 'total' ),
						'param_name' => 'lightbox',
						'std' => 'false',
					),
					array(
						'type' => 'vcex_text_alignments',
						'heading' => __( 'Align', 'total' ),
						'param_name' => 'align',
						'std' => '',
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Width', 'total' ),
						'param_name' => 'width',
						'std' => '',
						'description' => __( 'The width function can be used to constrict your image to a specific width without having to crop the image. It also can be used in combination with different overlay styles that require the image to stretch to fit the parent container', 'total' )
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Border Radius', 'total' ),
						'param_name' => 'border_radius',
						'std' => '',
					),
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
					// Crop
					array(
						'type' => 'vcex_notice',
						'param_name' => 'main_notice',
						'text' => __( 'For security reasons cropping only works on images hosted on your own server in the WordPress uploads folder. If you are using an external image it will display in full.', 'total' ),
						'group' => __( 'Size', 'total' ),
					),
					array(
						'type' => 'vcex_image_sizes',
						'heading' => __( 'Image Size', 'total' ),
						'param_name' => 'img_size',
						'std' => 'wpex_custom',
						'group' => __( 'Size', 'total' ),
					),
					array(
						'type' => 'vcex_image_crop_locations',
						'heading' => __( 'Image Crop Location', 'total' ),
						'param_name' => 'img_crop',
						'std' => 'center-center',
						'group' => __( 'Size', 'total' ),
						'dependency' => array( 'element' => 'img_size', 'value' => 'wpex_custom' ),
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Image Crop Width', 'total' ),
						'param_name' => 'img_width',
						'group' => __( 'Size', 'total' ),
						'dependency' => array( 'element' => 'img_size', 'value' => 'wpex_custom' ),
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Image Crop Height', 'total' ),
						'param_name' => 'img_height',
						'description' => __( 'Enter a height in pixels. Leave empty to disable vertical cropping and keep image proportions.', 'total' ),
						'group' => __( 'Size', 'total' ),
						'dependency' => array( 'element' => 'img_size', 'value' => 'wpex_custom' ),
					),
					// Lightbox
					array(
						'type' => 'textfield',
						'heading' => __( 'Custom Lightbox URL', 'total' ),
						'param_name' => 'lightbox_url',
						'group' => __( 'lightbox', 'total' ),
						'dependency' => array( 'element' => 'lightbox', 'value' => 'true' ),
						'group' => __( 'Lightbox', 'total' ),
					),
					array(
						'type' => 'dropdown',
						'heading' => __( 'Lightbox Type', 'total' ),
						'param_name' => 'lightbox_type',
						'std' => 'video',
						'value' => array(
							__( 'Youtube, Vimeo, Embed or Iframe', 'total' ) => 'video',
							__( 'Image', 'total' ) => 'image',
							__( 'URL', 'total' ) => 'url',
							__( 'HTML5', 'total' ) => 'html5',
							__( 'Quicktime', 'total' ) => 'quicktime',
						),
						'group' => __( 'Lightbox', 'total' ),
						'dependency' => array( 'element' => 'lightbox_url', 'not_empty' => true ),
					),
					array(
						'type' => 'vcex_ofswitch',
						'heading' => __( 'Video Overlay Icon?', 'total' ),
						'param_name' => 'lightbox_video_overlay_icon',
						'group' => __( 'Lightbox', 'total' ),
						'std' => 'false',
						'dependency' => array( 'element' => 'lightbox_type', 'value' => 'video' ),
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Lightbox Title', 'total' ),
						'param_name' => 'lightbox_title',
						'group' => __( 'Lightbox', 'total' ),
						'dependency' => array( 'element' => 'lightbox', 'value' => 'true' ),
					),
					array(
						'type' => 'textarea',
						'heading' => __( 'Lightbox Caption', 'total' ),
						'param_name' => 'lightbox_caption',
						'group' => __( 'Lightbox', 'total' ),
						'dependency' => array( 'element' => 'lightbox', 'value' => 'true' ),
						'description' => __( 'The lightbox caption can be used to add a longer caption to your image lighbox. This setting is exclusive for singular images.', 'total' ),
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Lightbox Dimensions', 'total' ),
						'param_name' => 'lightbox_dimensions',
						'description' => __( 'Enter a custom width and height for your lightbox pop-up window. Use format widthxheight. Example: 900x600.', 'total' ),
						'group' => __( 'Lightbox', 'total' ),
						'dependency' => array( 'element' => 'lightbox_type', 'value' => array( 'video', 'url', 'html5', 'quicktime' ) ),
					),
					array(
						'type' => 'attach_image',
						'admin_label' => false,
						'heading' => __( 'Custom Image', 'total' ),
						'param_name' => 'lightbox_custom_img',
						'group' => __( 'Lightbox', 'total' ),
						'dependency' => array( 'element' => 'lightbox', 'value' => 'true' ),
					),
					array(
						'type' => 'vcex_attach_images',
						'admin_label' => false,
						'heading' => __( 'Custom Gallery', 'total' ),
						'param_name' => 'lightbox_gallery',
						'description' => __( 'Select images to create a lightbox Gallery.', 'total' ),
						'group' => __( 'Lightbox', 'total' ),
						'dependency' => array( 'element' => 'lightbox', 'value' => 'true' ),
					),
					array(
						'type' => 'vcex_ofswitch',
						'std' => 'false',
						'heading' => __( 'Post Gallery', 'total' ),
						'param_name' => 'lightbox_post_gallery',
						'group' => __( 'Lightbox', 'total' ),
						'dependency' => array( 'element' => 'lightbox', 'value' => 'true' ),
					),
					// Custom CSS
					array(
						'type' => 'css_editor',
						'heading' => __( 'Design Options', 'total' ),
						'param_name' => 'css',
						'group' => __( 'Design Options', 'total' ),
					),
					// Overlay and Hover
					array(
						'type' => 'vcex_hover_animations',
						'heading' => __( 'Hover Animation', 'total'),
						'param_name' => 'hover_animation',
						'group' => __( 'Other', 'total' ),
					),
					array(
						'type' => 'vcex_overlay',
						'heading' => __( 'Image Overlay', 'total' ),
						'param_name' => 'overlay_style',
						'std' => 'none',
						'group' => __( 'Other', 'total' ),
						'exclude_choices' => array( 'thumb-swap', 'category-tag', 'category-tag-two' ),
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Overlay Excerpt Length', 'total' ),
						'param_name' => 'overlay_excerpt_length',
						'value' => '15',
						'group' => __( 'Other', 'total' ),
						'dependency' => array( 'element' => 'overlay_style', 'value' => 'title-excerpt-hover' ),
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Overlay Button Text', 'total' ),
						'param_name' => 'overlay_button_text',
						'group' => __( 'Other', 'total' ),
						'dependency' => array( 'element' => 'overlay_style', 'value' => 'hover-button' ),
					),
					array(
						'type' => 'vcex_image_hovers',
						'heading' => __( 'CSS3 Image Hover', 'total' ),
						'param_name' => 'img_hover_style',
						'group' => __( 'Other', 'total' ),
						'dependency' => array( 'element' => 'hover_animation', 'is_empty' => true ),
					),
					array(
						'type' => 'vcex_image_filters',
						'heading' => __( 'Image Filter', 'total' ),
						'param_name' => 'img_filter',
						'group' => __( 'Other', 'total' ),
					),
				)
			);
		}

	}
}
new VCEX_Image_Shortcode;