<?php
/**
 * Visual Composer Image Carousel
 *
 * @package Total WordPress Theme
 * @subpackage VC Functions
 * @version 4.7.1
 */

if ( ! class_exists( 'VCEX_Image_Carousel' ) ) {

	class VCEX_Image_Carousel {

		/**
		 * Main constructor
		 *
		 * @since 3.5.0
		 */
		public function __construct() {
			
			// Add shortcode
			add_shortcode( 'vcex_image_carousel', array( $this, 'output' ) );

			// Map to VC
			vc_lean_map( 'vcex_image_carousel', array( $this, 'map' ) );

			// Admin filters
			if ( is_admin() ) {

				// Move content design elements into new entry CSS field
				add_filter( 'vc_edit_form_fields_attributes_vcex_image_carousel', 'vcex_parse_deprecated_grid_entry_content_css' );
				
				// Set image height to full if crop/width are empty
				add_filter( 'vc_edit_form_fields_attributes_vcex_image_carousel', 'vcex_parse_image_size' );

			}

		}

		/**
		 * Shortcode output => Get template file and display shortcode
		 *
		 * @since 3.5.0
		 */
		public function output( $atts, $content = null ) {
			ob_start();
			include( locate_template( 'vcex_templates/vcex_image_carousel.php' ) );
			return ob_get_clean();
		}

		/**
		 * Map shortcode to VC
		 *
		 * @since 3.5.0
		 */
		public function map() {
			return array(
				'name' => __( 'Image Carousel', 'total' ),
				'description' => __( 'Image based jQuery carousel', 'total' ),
				'base' => 'vcex_image_carousel',
				'category' => wpex_get_theme_branding(),
				'icon' => 'vcex-image-carousel vcex-icon fa fa-picture-o',
				'params' => array(
					// Gallery
					array(
						'type' => 'vcex_attach_images',
						'heading'  => __( 'Images', 'total' ),
						'param_name' => 'image_ids',
						'group' => __( 'Gallery', 'total' ),
					),
					array(
						'type' => 'vcex_ofswitch',
						'admin_label' => true,
						'std' => 'false',
						'heading'  => __( 'Post Gallery', 'total' ),
						'param_name' => 'post_gallery',
						'group' => __( 'Gallery', 'total' ),
						'description' => __( 'Enable to display images from the current post "Image Gallery".', 'total' ),
					),
					array(
						'type' => 'vcex_ofswitch',
						'std' => 'false',
						'heading'  => __( 'Randomize Images', 'total' ),
						'param_name' => 'randomize_images',
						'group' => __( 'Gallery', 'total' ),
					),
					// General
					array(
						'type' => 'textfield',
						'heading'  => __( 'Unique Id', 'total' ),
						'param_name' => 'unique_id',
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Extra class name', 'total' ),
						'param_name' => 'classes',
						'description' => __( 'Style particular content element differently - add a class name and refer to it in custom CSS.', 'total' ),
					),
					vcex_vc_map_add_css_animation(),
					array(
						'type' => 'vcex_select_buttons',
						'heading' => __( 'Style', 'total' ),
						'param_name' => 'style',
						'std' => 'default',
						'choices' => array(
							'default' => __( 'Default', 'total' ),
							'no-margins' => __( 'No Margins', 'total' ),
						),
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Animation Speed', 'total' ),
						'param_name' => 'animation_speed',
						'value' => '150',
						'description' => __( 'Default is 150 milliseconds. Enter 0.0 to disable.', 'total' ),
					),
					array(
						'type' => 'vcex_ofswitch',
						'std' => 'true',
						'heading' => __( 'Arrows?', 'total' ),
						'param_name' => 'arrows',
					),
					array(
						'type' => 'vcex_carousel_arrow_styles',
						'heading' => __( 'Arrows Style', 'total' ),
						'param_name' => 'arrows_style',
						'dependency' => array( 'element' => 'arrows', 'value' => 'true' ),
					),
					array(
						'type' => 'vcex_carousel_arrow_positions',
						'heading' => __( 'Arrows Position', 'total' ),
						'param_name' => 'arrows_position',
						'std' => 'default',
						'dependency' => array( 'element' => 'arrows', 'value' => 'true' ),
					),
					array(
						'type' => 'vcex_ofswitch',
						'std' => 'false',
						'heading' => __( 'Dots?', 'total' ),
						'param_name' => 'dots',
					),
					array(
						'type' => 'vcex_ofswitch',
						'std' => 'false',
						'heading' => __( 'Auto Width', 'total' ),
						'param_name' => 'auto_width',
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Items To Display', 'total' ),
						'param_name' => 'items',
						'value' => '4',
						'dependency' => array( 'element' => 'auto_width', 'value' => 'false' ),
					),
					array(
						'type' => 'vcex_ofswitch',
						'std' => 'false',
						'heading' => __( 'Auto Height?', 'total' ),
						'param_name' => 'auto_height',
						'dependency' => array( 'element' => 'items', 'value' => '1' ),
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Tablet: Items To Display', 'total' ),
						'param_name' => 'tablet_items',
						'value' => '3',
						'dependency' => array( 'element' => 'auto_width', 'value' => 'false' ),
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Mobile Landscape: Items To Display', 'total' ),
						'param_name' => 'mobile_landscape_items',
						'value' => '2',
						'dependency' => array( 'element' => 'auto_width', 'value' => 'false' ),
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Mobile Portrait: Items To Display', 'total' ),
						'param_name' => 'mobile_portrait_items',
						'value' => '1',
						'dependency' => array( 'element' => 'auto_width', 'value' => 'false' ),
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Items To Scrollby', 'total' ),
						'param_name' => 'items_scroll',
						'value' => '1',
						'dependency' => array( 'element' => 'auto_width', 'value' => 'false' ),
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Margin Between Items', 'total' ),
						'param_name' => 'items_margin',
						'value' => '15',
					),
					array(
						'type' => 'vcex_ofswitch',
						'std' => 'true',
						'heading' => __( 'Auto Play', 'total' ),
						'param_name' => 'auto_play',
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Timeout Duration in milliseconds', 'total' ),
						'param_name' => 'timeout_duration',
						'value' => '5000',
						'dependency' => array( 'element' => 'auto_play', 'value' => 'true' ),
					),
					array(
						'type' => 'vcex_ofswitch',
						'std' => 'true',
						'heading' => __( 'Infinite Loop', 'total' ),
						'param_name' => 'infinite_loop',
						'std' => 'true',
					),
					array(
						'type' => 'vcex_ofswitch',
						'std' => 'false',
						'heading' => __( 'Center Item', 'total' ),
						'param_name' => 'center',
					),
					// Image
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
						'heading' => __( 'Image Crop Width', 'total' ),
						'param_name' => 'img_width',
						'group' => __( 'Image', 'total' ),
						'dependency' => array( 'element' => 'img_size', 'value' => 'wpex_custom' ),
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Image Crop Height', 'total' ),
						'param_name' => 'img_height',
						'description' => __( 'Enter a height in pixels. Leave empty to disable vertical cropping and keep image proportions.', 'total' ),
						'group' => __( 'Image', 'total' ),
						'dependency' => array( 'element' => 'img_size', 'value' => 'wpex_custom' ),
					),
					array(
						'type' => 'vcex_ofswitch',
						'std' => 'no',
						'vcex' => array(
							'on' => 'yes',
							'off' => 'no',
						),
						'heading' => __( 'Rounded Image?', 'total' ),
						'param_name' => 'rounded_image',
						'group' => __( 'Image', 'total' ),
					),
					array(
						'type' => 'vcex_overlay',
						'heading' => __( 'Image Overlay', 'total' ),
						'param_name' => 'overlay_style',
						'group' => __( 'Image', 'total' ),
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Overlay Button Text', 'total' ),
						'param_name' => 'overlay_button_text',
						'group' => __( 'Image', 'total' ),
						'dependency' => array( 'element' => 'overlay_style', 'value' => 'hover-button' ),
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
					// Links
					array(
						'type' => 'vcex_select_buttons',
						'heading' => __( 'Image Link', 'total' ),
						'param_name' => 'thumbnail_link',
						'std' => 'none',
						'choices' => array(
							'none' => __( 'None', 'total' ),
							'lightbox' => __( 'Lightbox', 'total' ),
							'full_image' => __( 'Full Image', 'total' ),
							'attachment_page' => __( 'Attachment Page', 'total' ),
							'custom_link' => __( 'Custom Links', 'total' ),
						),
						'group' => __( 'Links', 'total' ),
					),
					array(
						'type' => 'exploded_textarea',
						'heading'  => __( 'Custom links', 'total' ),
						'param_name' => 'custom_links',
						'description' => __( 'Enter links for each slide here. Divide links with linebreaks (Enter). For images without a link enter a # symbol. And don\'t forget to include the http:// at the front.', 'total'),
						'group' => __( 'Links', 'total' ),
						'dependency' => array( 'element' => 'thumbnail_link', 'value' => 'custom_link' ),
					),
					array(
						'type' => 'vcex_select_buttons',
						'heading'  => __( 'Target', 'total' ),
						'param_name' => 'custom_links_target',
						'group' => __( 'Links', 'total' ),
						'choices' => 'link_target',
						'dependency' => array(
							'element' => 'thumbnail_link',
							'value' => array( 'custom_link', 'attachment_page', 'full_image' )
						),
					),
					array(
						'type' => 'vcex_lightbox_skins',
						'heading' => __( 'Lightbox Skin', 'total' ),
						'param_name' => 'lightbox_skin',
						'group' => __( 'Links', 'total' ),
						'dependency' => array( 'element' => 'thumbnail_link', 'value' => 'lightbox' ),
					),
					array(
						'type' => 'vcex_select_buttons',
						'heading' => __( 'Lightbox Thumbnails Placement', 'total' ),
						'param_name' => 'lightbox_path',
						'std' => 'horizontal',
						'choices' => array(
							'horizontal' => __( 'Horizontal', 'total' ),
							'vertical' => __( 'Vertical', 'total' ),
						),
						'group' => __( 'Links', 'total' ),
						'dependency' => array( 'element' => 'thumbnail_link', 'value' => 'lightbox' ),
					),
					array(
						'type' => 'vcex_select_buttons',
						'heading' => __( 'Lightbox Title', 'total' ),
						'param_name' => 'lightbox_title',
						'std' => 'none',
						'choices' => array(
							'none' => __( 'None', 'total' ),
							'alt' => __( 'Alt', 'total' ),
							'title' => __( 'Title', 'total' ),
						),
						'group' => __( 'Links', 'total' ),
						'dependency' => array( 'element' => 'thumbnail_link', 'value' => 'lightbox' ),
					),
					array(
						'type' => 'vcex_ofswitch',
						'std' => 'true',
						'heading' => __( 'Lightbox Caption', 'total' ),
						'param_name' => 'lightbox_caption',
						'group' => __( 'Links', 'total' ),
						'dependency' => array( 'element' => 'thumbnail_link', 'value' => 'lightbox' ),
					),
					// Title
					array(
						'type' => 'vcex_ofswitch',
						'std' => 'no',
						'vcex' => array(
							'on' => 'yes',
							'off' => 'no',
						),
						'heading' => __( 'Title', 'total' ),
						'param_name' => 'title',
						'group' => __( 'Title', 'total' ),
					),
					array(
						'type' => 'vcex_select_buttons',
						'heading' => __( 'Title Based On Image', 'total' ),
						'param_name' => 'title_type',
						'std' => 'title',
						'choices' => array(
							'title' => __( 'Title', 'total' ),
							'alt' => __( 'Alt', 'total' ),
						),
						'group' => __( 'Title', 'total' ),
						'dependency' => array( 'element' => 'title', 'value' => 'yes' ),
					),
					array(
						'type' => 'colorpicker',
						'heading' => __( 'Color', 'total' ),
						'param_name' => 'content_heading_color',
						'group' => __( 'Title', 'total' ),
						'dependency' => array( 'element' => 'title', 'value' => 'yes' ),
					),
					array(
						'type' => 'vcex_font_weight',
						'heading' => __( 'Font Weight', 'total' ),
						'param_name' => 'content_heading_weight',
						'group' => __( 'Title', 'total' ),
						'dependency' => array( 'element' => 'title', 'value' => 'yes' ),
					),
					array(
						'type' => 'vcex_text_transforms',
						'heading' => __( 'Text Transform', 'total' ),
						'param_name' => 'content_heading_transform',
						'group' => __( 'Title', 'total' ),
						'dependency' => array( 'element' => 'title', 'value' => 'yes' ),
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Font Size', 'total' ),
						'param_name' => 'content_heading_size',
						'dependency' => array( 'element' => 'title', 'value' => 'yes' ),
						'group' => __( 'Title', 'total' ),
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Margin', 'total' ),
						'param_name' => 'content_heading_margin',
						'description' => __( 'Please use the following format: top right bottom left.', 'total' ),
						'dependency' => array( 'element' => 'title', 'value' => 'yes' ),
					),
					// Caption
					array(
						'type' => 'vcex_ofswitch',
						'std' => 'no',
						'vcex' => array(
							'on' => 'yes',
							'off' => 'no',
						),
						'heading' => __( 'Display Caption', 'total' ),
						'param_name' => 'caption',
						'group' => __( 'Caption', 'total' ),
					),
					array(
						'type' => 'colorpicker',
						'heading' => __( 'Color', 'total' ),
						'param_name' => 'content_color',
						'group' => __( 'Caption', 'total' ),
						'dependency' => array( 'element' => 'caption', 'value' => 'yes' ),
					),
					array(
						'type' => 'textfield',
						'heading'  => __( 'Font Size', 'total' ),
						'param_name' => 'content_font_size',
						'group' => __( 'Caption', 'total' ),
						'dependency' => array( 'element' => 'caption', 'value' => 'yes' ),
					),
					// Design
					array(
						'type' => 'css_editor',
						'heading' => __( 'Content CSS', 'total' ),
						'param_name' => 'content_css',
						'group' => __( 'Content CSS', 'total' ),
					),
					array(
						'type' => 'vcex_text_alignments',
						'heading' => __( 'Content Alignment', 'total' ),
						'param_name' => 'content_alignment',
						'group' => __( 'Content CSS', 'total' ),
						'std' => '',
					),
					// Entry CSS
					array(
						'type' => 'css_editor',
						'heading' => __( 'Entry CSS', 'total' ),
						'param_name' => 'entry_css',
						'group' => __( 'Entry CSS', 'total' ),
					),
				),
			);
		}

	}
}
new VCEX_Image_Carousel;