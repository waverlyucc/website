<?php
/**
 * Visual Composer WooCommerce Carousel
 *
 * @package Total WordPress Theme
 * @subpackage VC Functions
 * @version 4.8
 */

if ( ! class_exists( 'VCEX_Woocommerce_Carousel_Shortcode' ) ) {

	class VCEX_Woocommerce_Carousel_Shortcode {

		/**
		 * Main constructor
		 *
		 * @since 3.5.0
		 */
		public function __construct() {
			
			// Add shortcode
			add_shortcode( 'vcex_woocommerce_carousel', array( $this, 'output' ) );

			// Map to VC
			vc_lean_map( 'vcex_woocommerce_carousel', array( $this, 'map' ) );
			
			// Admin filters
			if ( is_admin() ) {

				// Get autocomplete suggestion
				add_filter( 'vc_autocomplete_vcex_woocommerce_carousel_include_categories_callback', 'vcex_suggest_product_categories', 10, 1 );
				add_filter( 'vc_autocomplete_vcex_woocommerce_carousel_exclude_categories_callback', 'vcex_suggest_product_categories', 10, 1 );

				// Render autocomplete suggestions
				add_filter( 'vc_autocomplete_vcex_woocommerce_carousel_include_categories_render', 'vcex_render_product_categories', 10, 1 );
				add_filter( 'vc_autocomplete_vcex_woocommerce_carousel_exclude_categories_render', 'vcex_render_product_categories', 10, 1 );

			}

		}

		/**
		 * Shortcode output => Get template file and display shortcode
		 *
		 * @since 3.5.0
		 */
		public function output( $atts, $content = null ) {
			ob_start();
			include( locate_template( 'vcex_templates/vcex_woocommerce_carousel.php' ) );
			return ob_get_clean();
		}

		/**
		 * Map shortcode to VC
		 *
		 * @since 3.5.0
		 */
		public function map() {
			return array(
				'name' => __( 'Woo Products Carousel (Custom)', 'total' ),
				'description' => __( 'Custom products carousel', 'total' ),
				'base' => 'vcex_woocommerce_carousel',
				'category' => wpex_get_theme_branding(),
				'icon' => 'vcex-woocommerce-carousel vcex-icon ticon ticon-shopping-cart',
				'params' => array(
					// General
					array(
						'type' => 'textfield',
						'heading' => __( 'Unique Id', 'total' ),
						'description' => __( 'Give your main element a unique ID.', 'total' ),
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
						'dependency' => array( 'element' => 'arrows', 'value' => 'true' ),
					),
					array(
						'type' => 'vcex_ofswitch',
						'std' => 'false',
						'heading' => __( 'Dots?', 'total' ),
						'param_name' => 'dots',
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Items To Display', 'total' ),
						'param_name' => 'items',
						'value' => '4',
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Tablet: Items To Display', 'total' ),
						'param_name' => 'tablet_items',
						'value' => '3',
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Mobile Landscape: Items To Display', 'total' ),
						'param_name' => 'mobile_landscape_items',
						'value' => '2',
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Mobile Portrait: Items To Display', 'total' ),
						'param_name' => 'mobile_portrait_items',
						'value' => '1',
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Items To Scrollby', 'total' ),
						'param_name' => 'items_scroll',
						'value' => '1',
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
					),
					array(
						'type' => 'vcex_ofswitch',
						'std' => 'false',
						'heading' => __( 'Center Item', 'total' ),
						'param_name' => 'center',
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Animation Speed', 'total' ),
						'param_name' => 'animation_speed',
						'value' => '150',
						'description' => __( 'Default is 150 milliseconds. Enter 0.0 to disable.', 'total' ),
					),
					// Query
					array(
						'type' => 'textfield',
						'heading' => __( 'Post Count', 'total' ),
						'param_name' => 'count',
						'value' => '8',
						'group' => __( 'Query', 'total' ),
					),
					array(
						'type' => 'vcex_ofswitch',
						'std' => '',
						'vcex' => array( 'on' => true, 'off' => '' ),
						'heading' => __( 'Featured Products Only', 'total' ),
						'param_name' => 'featured_products_only',
						'group' => __( 'Query', 'total' ),
					),
					array(
						'type' => 'vcex_ofswitch',
						'heading' => __( 'Exclude Out of Stock Products', 'total' ),
						'param_name' => 'exclude_products_out_of_stock',
						'group' => __( 'Query', 'total' ),
						'std' => '',
						'vcex' => array( 'on'  => true, 'off' => '' ),
					),
					array(
						'type' => 'autocomplete',
						'heading' => __( 'Include Categories', 'total' ),
						'param_name' => 'include_categories',
						'param_holder_class' => 'vc_not-for-custom',
						'admin_label' => true,
						'settings' => array(
							'multiple' => true,
							'min_length' => 1,
							'groups' => true,
							'unique_values' => true,
							'display_inline' => true,
							'delay' => 0,
							'auto_focus' => true,
						),
						'group' => __( 'Query', 'total' ),
					),
					array(
						'type' => 'autocomplete',
						'heading' => __( 'Exclude Categories', 'total' ),
						'param_name' => 'exclude_categories',
						'param_holder_class' => 'vc_not-for-custom',
						'admin_label' => true,
						'settings' => array(
							'multiple' => true,
							'min_length' => 1,
							'groups' => true,
							'unique_values' => true,
							'display_inline' => true,
							'delay' => 0,
							'auto_focus' => true,
						),
						'group' => __( 'Query', 'total' ),
					),
					array(
						'type' => 'dropdown',
						'heading' => __( 'Order', 'total' ),
						'param_name' => 'order',
						'group' => __( 'Query', 'total' ),
						'value' => array(
							__( 'Default', 'total' ) => '',
							__( 'DESC', 'total' ) => 'DESC',
							__( 'ASC', 'total' ) => 'ASC',
						),
					),
					array(
						'type' => 'vcex_orderby',
						'heading' => __( 'Order By', 'total' ),
						'param_name' => 'orderby',
						'group' => __( 'Query', 'total' ),
						'post_type' => 'woo_product',
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Orderby: Meta Key', 'total' ),
						'param_name' => 'orderby_meta_key',
						'group' => __( 'Query', 'total' ),
						'dependency' => array(
							'element' => 'orderby',
							'value' => array( 'meta_value_num', 'meta_value' ),
						),
					),
					// Image
					array(
						'type' => 'vcex_select_buttons',
						'std' => 'post',
						'heading' => __( 'Image Links To', 'total' ),
						'param_name' => 'thumbnail_link',
						'choices' => array(
							'post' => __( 'Post', 'total' ),
							'lightbox' => __( 'Lightbox', 'total' ),
							'none' => __( 'None', 'total' ),
						),
						'group' => __( 'Image', 'total' ),
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
						'heading' => __( 'Image Crop Width', 'total' ),
						'param_name' => 'img_width',
						'description' => __( 'Enter a width in pixels.', 'total' ),
						'group' => __( 'Image', 'total' ),
						'dependency' => array(
							'element' => 'img_size',
							'value' => 'wpex_custom',
						),
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Image Crop Height', 'total' ),
						'param_name' => 'img_height',
						'dependency' => array(
							'element' => 'img_size',
							'value' => 'wpex_custom',
						),
						'description' => __( 'Enter a height in pixels. Leave empty to disable vertical cropping and keep image proportions.', 'total' ),
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
						'type' => 'textfield',
						'heading' => __( 'Overlay Excerpt Length', 'total' ),
						'param_name' => 'overlay_excerpt_length',
						'value' => '15',
						'group' => __( 'Image', 'total' ),
						'dependency' => array( 'element' => 'overlay_style', 'value' => 'title-excerpt-hover' ),
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
					// Title
					array(
						'type' => 'vcex_ofswitch',
						'std' => 'true',
						'vcex' => array( 'on'  => 'true', 'off' => 'false' ),
						'heading' => __( 'Display Title', 'total' ),
						'param_name' => 'title',
						'group' => __( 'Title', 'total' ),
					),
					array(
						'type' => 'colorpicker',
						'heading' => __( 'Color', 'total' ),
						'param_name' => 'content_heading_color',
						'group' => __( 'Title', 'total' ),
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Font Size', 'total' ),
						'param_name' => 'content_heading_size',
						'description' => __( 'You can use em or px values, but you must define them.', 'total' ),
						'group' => __( 'Title', 'total' ),
					),
					array(
						'type' => 'vcex_trbl',
						'heading' => __( 'Margin', 'total' ),
						'param_name' => 'content_heading_margin',
						'group' => __( 'Title', 'total' ),
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Line Height', 'total' ),
						'param_name' => 'content_heading_line_height',
						'description' => __( 'Enter a numerical, pixel or percentage value.', 'total' ),
						'group' => __( 'Title', 'total' ),
					),
					array(
						'type' => 'vcex_font_weight',
						'heading' => __( 'Font Weight', 'total' ),
						'param_name' => 'content_heading_weight',
						'description' => __( 'Note: Not all font families support every font weight.', 'total' ),
						'group' => __( 'Title', 'total' ),
					),
					array(
						'type' => 'vcex_text_transforms',
						'heading' => __( 'Text Transform', 'total' ),
						'param_name' => 'content_heading_transform',
						'group' => __( 'Title', 'total' ),
					),
					// Price
					array(
						'type' => 'vcex_ofswitch',
						'std' => 'true',
						'vcex' => array( 'on'  => 'true', 'off' => 'false' ),
						'heading' => __( 'Display Price', 'total' ),
						'param_name' => 'price',
						'group' => __( 'Price', 'total' ),
					),
					array(
						'type' => 'colorpicker',
						'heading' => __( 'Color', 'total' ),
						'param_name' => 'content_color',
						'group' => __( 'Price', 'total' ),
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Font Size', 'total' ),
						'param_name' => 'content_font_size',
						'group' => __( 'Price', 'total' ),
						'description' => __( 'You can use em or px values, but you must define them.', 'total' ),
					),
					// Readmore
					array(
						'type' => 'vcex_ofswitch',
						'std' => 'false',
						'heading' => __( 'Enable', 'total' ),
						'param_name' => 'read_more',
						'group' => __( 'Button', 'total' ),
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Custom Text', 'total' ),
						'param_name' => 'read_more_text',
						'group' => __( 'Button', 'total' ),
						'dependency' => array( 'element' => 'read_more', 'value' => 'true' ),
					),
					array(
						'type' => 'vcex_button_styles',
						'heading' => __( 'Style', 'total' ),
						'param_name' => 'readmore_style',
						'group' => __( 'Button', 'total' ),
						'dependency' => array( 'element' => 'read_more', 'value' => 'true' ),
					),
					array(
						'type' => 'vcex_button_colors',
						'heading' => __( 'Color', 'total' ),
						'param_name' => 'readmore_style_color',
						'group' => __( 'Button', 'total' ),
						'dependency' => array( 'element' => 'read_more', 'value' => 'true' ),
					),
					array(
						'type' => 'vcex_ofswitch',
						'std' => 'false',
						'heading' => __( 'Arrow', 'total' ),
						'param_name' => 'readmore_rarr',
						'group' => __( 'Button', 'total' ),
						'dependency' => array( 'element' => 'read_more', 'value' => 'true' ),
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Font Size', 'total' ),
						'param_name' => 'readmore_size',
						'group' => __( 'Button', 'total' ),
						'dependency' => array( 'element' => 'read_more', 'value' => 'true' ),
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Border Radius', 'total' ),
						'param_name' => 'readmore_border_radius',
						'description' => __( 'Please enter a px value.', 'total' ),
						'group' => __( 'Button', 'total' ),
						'dependency' => array( 'element' => 'read_more', 'value' => 'true' ),
					),
					array(
						'type' => 'vcex_trbl',
						'heading' => __( 'Padding', 'total' ),
						'param_name' => 'readmore_padding',
						'group' => __( 'Button', 'total' ),
						'dependency' => array( 'element' => 'read_more', 'value' => 'true' ),
					),
					array(
						'type' => 'vcex_trbl',
						'heading' => __( 'Margin', 'total' ),
						'param_name' => 'readmore_margin',
						'group' => __( 'Button', 'total' ),
						'dependency' => array( 'element' => 'read_more', 'value' => 'true' ),
					),
					array(
						'type' => 'colorpicker',
						'heading' => __( 'Background', 'total' ),
						'param_name' => 'readmore_background',
						'group' => __( 'Button', 'total' ),
						'dependency' => array( 'element' => 'read_more', 'value' => 'true' ),
					),
					array(
						'type' => 'colorpicker',
						'heading' => __( 'Color', 'total' ),
						'param_name' => 'readmore_color',
						'group' => __( 'Button', 'total' ),
						'dependency' => array( 'element' => 'read_more', 'value' => 'true' ),
					),
					array(
						'type' => 'colorpicker',
						'heading' => __( 'Background: Hover', 'total' ),
						'param_name' => 'readmore_hover_background',
						'group' => __( 'Button', 'total' ),
						'dependency' => array( 'element' => 'read_more', 'value' => 'true' ),
					),
					array(
						'type' => 'colorpicker',
						'heading' => __( 'Color: Hover', 'total' ),
						'param_name' => 'readmore_hover_color',
						'group' => __( 'Button', 'total' ),
						'dependency' => array( 'element' => 'read_more', 'value' => 'true' ),
					),
					// Design
					array(
						'type' => 'dropdown',
						'heading' => __( 'Style', 'total' ),
						'param_name' => 'style',
						'value' => array(
							__( 'Default', 'total') => 'default',
							__( 'No Margins', 'total' ) => 'no-margins',
						),
						'group' => __( 'Design', 'total' ),
					),
					array(
						'type' => 'colorpicker',
						'heading' => __( 'Content Background', 'total' ),
						'param_name' => 'content_background',
						'group' => __( 'Design', 'total' ),
					),
					array(
						'type' => 'vcex_text_alignments',
						'heading' => __( 'Content Alignment', 'total' ),
						'param_name' => 'content_alignment',
						'group' => __( 'Design', 'total' ),
					),
					array(
						'type' => 'vcex_trbl',
						'heading' => __( 'Content Margin', 'total' ),
						'param_name' => 'content_margin',
						'group' => __( 'Design', 'total' ),
					),
					array(
						'type' => 'vcex_trbl',
						'heading' => __( 'Content Padding', 'total' ),
						'param_name' => 'content_padding',
						'group' => __( 'Design', 'total' ),
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Content Border', 'total' ),
						'param_name' => 'content_border',
						'description' => __( 'Please use the shorthand format: width style color. Enter 0px or "none" to disable border.', 'total' ),
						'group' => __( 'Design', 'total' ),
					),
				),
			);
		}

	}
}
new VCEX_Woocommerce_Carousel_Shortcode;