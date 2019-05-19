<?php
/**
 * Visual Composer WooCommerce Carousel based on default loop output
 *
 * @package Total WordPress Theme
 * @subpackage VC Functions
 * @version 4.8
 */

if ( ! class_exists( 'VCEX_WooCommerce_Loop_Carousel' ) ) {

	class VCEX_WooCommerce_Loop_Carousel {

		/**
		 * Main constructor
		 *
		 * @since 3.5.0
		 */
		public function __construct() {
			
			// Add shortcode
			add_shortcode( 'vcex_woocommerce_loop_carousel', array( $this, 'output' ) );

			// Map to VC
			vc_lean_map( 'vcex_woocommerce_loop_carousel', array( $this, 'map' ) );
			
			// Admin filters
			if ( is_admin() ) {

				// Get autocomplete suggestion
				add_filter( 'vc_autocomplete_vcex_woocommerce_loop_carousel_include_categories_callback', 'vcex_suggest_product_categories', 10, 1 );
				add_filter( 'vc_autocomplete_vcex_woocommerce_loop_carousel_exclude_categories_callback', 'vcex_suggest_product_categories', 10, 1 );

				// Render autocomplete suggestions
				add_filter( 'vc_autocomplete_vcex_woocommerce_loop_carousel_include_categories_render', 'vcex_render_product_categories', 10, 1 );
				add_filter( 'vc_autocomplete_vcex_woocommerce_loop_carousel_exclude_categories_render', 'vcex_render_product_categories', 10, 1 );

			}

		}

		/**
		 * Shortcode output => Get template file and display shortcode
		 *
		 * @since 3.5.0
		 */
		public function output( $atts, $content = null ) {
			ob_start();
			include( locate_template( 'vcex_templates/vcex_woocommerce_loop_carousel.php' ) );
			return ob_get_clean();
		}

		/**
		 * Map shortcode to VC
		 *
		 * @since 3.5.0
		 */
		public function map() {
			return array(
				'name' => __( 'Woo Products Carousel', 'total' ),
				'description' => __( 'WooCommerce products carousel.', 'total' ),
				'base' => 'vcex_woocommerce_loop_carousel',
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
						'type' => 'vcex_ofswitch',
						'std' => 'false',
						'heading' => __( 'Advanced Query?', 'total' ),
						'param_name' => 'custom_query',
						'group' => __( 'Query', 'total' ),
						'description' => __( 'Enable to build a custom query using your own parameter string.', 'total' ),
					),
					array(
						'type' => 'textarea_safe',
						'heading' => __( 'Custom query', 'total' ),
						'param_name' => 'custom_query_args',
						'description' => __( 'Build custom query according to <a href="http://codex.wordpress.org/Function_Reference/query_posts" target="_blank">WordPress Codex</a>.', 'total' ),
						'group' => __( 'Query', 'total' ),
						'dependency' => array( 'element' => 'custom_query', 'value' => array( 'true' ) ),
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Post Count', 'total' ),
						'param_name' => 'count',
						'value' => '8',
						'group' => __( 'Query', 'total' ),
						'dependency' => array( 'element' => 'custom_query', 'value' => array( 'false' ) ),
					),
					array(
						'type' => 'dropdown',
						'heading' => __( 'Display', 'total' ),
						'param_name' => 'query_products_by',
						'group' => __( 'Query', 'total' ),
						'value' => array(
							__( 'All Products', 'total' ) => '',
							__( 'Featured Products', 'total' ) => 'featured',
							__( 'On Sale Products', 'total' ) => 'on_sale',
						),
						'dependency' => array( 'element' => 'custom_query', 'value' => array( 'false' ) ),
					),
					array(
						'type' => 'vcex_ofswitch',
						'std' => '',
						'vcex' => array( 'on' => true, 'off' => '' ),
						'heading' => __( 'Exclude Out of Stock Products', 'total' ),
						'param_name' => 'exclude_products_out_of_stock',
						'group' => __( 'Query', 'total' ),
						'dependency' => array( 'element' => 'custom_query', 'value' => array( 'false' ) ),
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
						'dependency' => array( 'element' => 'custom_query', 'value' => array( 'false' ) ),
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
						'dependency' => array( 'element' => 'custom_query', 'value' => array( 'false' ) ),
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
						'dependency' => array( 'element' => 'custom_query', 'value' => array( 'false' ) ),
					),
					array(
						'type' => 'vcex_orderby',
						'heading' => __( 'Order By', 'total' ),
						'param_name' => 'orderby',
						'group' => __( 'Query', 'total' ),
						'post_type' => 'woo_product',
						'dependency' => array( 'element' => 'custom_query', 'value' => array( 'false' ) ),
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
					// HIDDEN
					array(
						'type' => 'hidden',
						'param_name' => 'entry_output',
					),
				),
			);
		}

	}
}
new VCEX_WooCommerce_Loop_Carousel;