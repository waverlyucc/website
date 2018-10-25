<?php
/**
 * Theme tweaks for WooCommerce images
 *
 * @package Total WordPress Theme
 * @subpackage WooCommerce
 * @version 4.6
 *
 */

if ( ! class_exists( 'WPEX_WooCommerce_Thumbnails' ) ) {

	class WPEX_WooCommerce_Thumbnails {

		/**
		 * Main Class Constructor
		 *
		 * @since 4.0
		 */
		public function __construct() {

			// This class should only run if dynamic resizing is not enabled for WooCommerce
			// @since 4.6
			if ( wpex_get_mod( 'woo_dynamic_image_resizing', false ) ) {
				return;
			}

			// Disable thumb regeneration as much as possible
			add_filter( 'woocommerce_resize_images', '__return_false' );
			add_filter( 'woocommerce_image_sizes_to_resize', '__return_empty_array' );
			add_filter( 'woocommerce_regenerate_images_intermediate_image_sizes', '__return_empty_array' );
			remove_filter( 'wp_get_attachment_image_src', array( 'WC_Regenerate_Images', 'maybe_resize_image' ), 10 );
			add_action( 'customize_register', array( 'WPEX_WooCommerce_Thumbnails', 'remove_customizer_sections' ), 99 );

			// Admin only functions
			if ( is_admin() ) {

				// Set admin post thumbnail to correct size
				add_filter( 'admin_post_thumbnail_size', array( 'WPEX_WooCommerce_Thumbnails', 'admin_post_thumbnail_size' ), 10, 3 );

				// Remove image size settings in Woo Product Display tab
				// @todo remove since things have changed in 3.3.0 (give time for customers to update)
				add_filter( 'woocommerce_product_settings', array( 'WPEX_WooCommerce_Thumbnails', 'remove_product_settings' ) );

				// Add WooCommerce tab to Total image sizes panel
				add_filter( 'wpex_image_sizes_tabs', array( 'WPEX_WooCommerce_Thumbnails', 'image_sizes_tabs' ), 10 );

			}

			// Add image sizes to Total panel
			add_filter( 'wpex_image_sizes', array( 'WPEX_WooCommerce_Thumbnails', 'add_image_sizes' ), 99 );

			// Define single shop thumbnail size
			add_filter( 'woocommerce_gallery_thumbnail_size', array( 'WPEX_WooCommerce_Thumbnails', 'gallery_thumbnail_size' ) );
			
			// Filter image sizes and return cropped versions
			if ( wpex_get_mod( 'image_resizing', true ) ) {

				// @todo Could be optimized a bit to prevent duplicate checks
				// @todo Figure out how to add retina support to the WooCommerce images since you can't easily add data attributes via Woo filters
				add_filter( 'wp_get_attachment_image_src', array( 'WPEX_WooCommerce_Thumbnails', 'attachment_image_src' ), 9999, 4 );

				// Alter cart thumbnail so it can be custom cropped seperately from the product entries
				add_filter( 'woocommerce_cart_item_thumbnail', array( 'WPEX_WooCommerce_Thumbnails', 'cart_item_thumbnail' ), 10, 3 );

			}

		}

		/**
		 * Remove image size settings in Woo Product Display tab.
		 *
		 * @since 2.0.0
		 */
		public static function remove_product_settings( $settings ) {
			$remove = array(
				'image_options',
				'shop_catalog_image_size',
				'shop_single_image_size',
				'shop_thumbnail_image_size',
				'woocommerce_enable_lightbox'
			);
			foreach( $settings as $key => $val ) {
				if ( isset( $val['id'] ) && in_array( $val['id'], $remove ) ) {
					unset( $settings[$key] );
				}
			}
			return $settings;
		}

		/**
		 * Add WooCommerce tab to Total image sizes panel.
		 *
		 * @since 3.3.2
		 */
		public static function image_sizes_tabs( $array ) {
			$array['woocommerce'] = 'WooCommerce';
			return $array;
		}

		/**
		 * Add image sizes to Total panel.
		 *
		 * @since 2.0.0
		 */
		public static function add_image_sizes( $sizes ) {
			return array_merge( $sizes, array(
				'shop_catalog' => array(
					'label'   => __( 'Product Entry', 'total' ),
					'width'   => 'woo_entry_width',
					'height'  => 'woo_entry_height',
					'crop'    => 'woo_entry_image_crop',
					'section' => 'woocommerce',
				),
				'shop_single' => array(
					'label'   => __( 'Product Post', 'total' ),
					'width'   => 'woo_post_width',
					'height'  => 'woo_post_height',
					'crop'    => 'woo_post_image_crop',
					'section' => 'woocommerce',
				),
				'shop_single_thumbnail' => array(
					'label'   => __( 'Product Post Gallery Thumbnail', 'total' ),
					'width'   => 'woo_post_thumb_width',
					'height'  => 'woo_post_thumb_height',
					'crop'    => 'woo_post_thumb_crop',
					'section' => 'woocommerce',
				),
				'shop_category' => array(
					'label'     => __( 'Category Thumbnail', 'total' ),
					'width'     => 'woo_cat_entry_width',
					'height'    => 'woo_cat_entry_height',
					'crop'      => 'woo_cat_entry_image_crop',
					'section'   => 'woocommerce',
				),
				'shop_thumbnail_cart' => array(
					'label'     => __( 'Widgets & Cart Thumbnail', 'total' ),
					'width'     => 'woo_shop_thumbnail_width',
					'height'    => 'woo_shop_thumbnail_height',
					'crop'      => 'woo_shop_thumbnail_crop',
					'section'   => 'woocommerce',
				),
			) );
		}

		
		/**
		 * Define single shop thumbnail size
		 *
		 * @since 4.6
		 */		
		public static function gallery_thumbnail_size() {
			return 'shop_single_thumbnail';
		}

		/**
		 * Filter image sizes and return cropped versions where we aren't altering the HTML
		 *
		 * @since 4.0
		 */		
		public static function attachment_image_src( $image, $attachment_id, $size, $icon ) {

			if ( ! $image ) {
				return;
			}

			// Shop single
			if ( in_array( $size, array( 'single', 'shop_single', 'woocommerce_single' ) ) ) {

				$dims = wpex_get_thumbnail_sizes( 'shop_single' );

				$generate_image = wpex_image_resize( array(
					'attachment' => $attachment_id,
					'size'       => 'shop_single',
					'height'     => isset( $dims['height'] ) ? $dims['height'] : '',
					'width'      => isset( $dims['width'] ) ? $dims['width'] : '',
					'crop'       => isset( $dims['crop'] ) ? $dims['crop'] : '',
					'image_src'  => $image, // IMPORTANT !!
				) );

				$image = $generate_image ? $generate_image : $image;

			}

			// Single product gallery thumbnail
			elseif ( in_array( $size, array( 'shop_single_thumbnail' ) ) ) {

				$dims = wpex_get_thumbnail_sizes( 'shop_single_thumbnail' );

				$generate_image = wpex_image_resize( array(
					'attachment' => $attachment_id,
					'size'       => 'shop_single_thumbnail',
					'height'     => isset( $dims['height'] ) ? $dims['height'] : '',
					'width'      => isset( $dims['width'] ) ? $dims['width'] : '',
					'crop'       => isset( $dims['crop'] ) ? $dims['crop'] : '',
					'image_src'  => $image, // IMPORTANT !!
				) );

				$image = $generate_image ? $generate_image : $image;
				
			}

			// Thumbnails
			elseif ( $size == 'woocommerce_thumbnail' ) {

				$dims = wpex_get_thumbnail_sizes( 'shop_thumbnail_cart' );

				$generate_image = wpex_image_resize( array(
					'attachment' => $attachment_id,
					'size'       => 'shop_thumbnail_cart',
					'height'     => isset( $dims['height'] ) ? $dims['height'] : '',
					'width'      => isset( $dims['width'] ) ? $dims['width'] : '',
					'crop'       => isset( $dims['crop'] ) ? $dims['crop'] : '',
					'image_src'  => $image, // IMPORTANT !!
				) );

				$image = $generate_image ? $generate_image : $image;

			}

			// Return src
			return $image;

		}

		/**
		 * Alter the cart item thumbnail size
		 *
		 * Needed to add retina support and properly crop images
		 *
		 * @since 4.0
		 */
		public static function cart_item_thumbnail( $thumb, $cart_item, $cart_item_key ) {
			if ( ! empty( $cart_item['variation_id'] )
				&& $thumbnail = get_post_thumbnail_id( $cart_item['variation_id'] )
			) {
				return wpex_get_post_thumbnail( array(
					'size'       => 'shop_thumbnail_cart',
					'attachment' => $thumbnail,
				) );
			} elseif ( isset( $cart_item['product_id'] )
				&& $thumbnail = get_post_thumbnail_id( $cart_item['product_id'] )
			) {
				return wpex_get_post_thumbnail( array(
					'size'       => 'shop_thumbnail_cart',
					'attachment' => $thumbnail,
				) );
			} else {
				return wc_placeholder_img();
			}
			return $thumb;
		}

		/**
		 * Remove customizer sections
		 *
		 * @since 4.6
		 */
		public static function remove_customizer_sections( $wp_customize ) {
			$wp_customize->remove_section( 'woocommerce_product_images' );
		}

		/**
		 * Set admin post thumbnail to correct size
		 *
		 * @see wp-admin/includes/post.php
		 * @since 4.6
		 */
		public static function admin_post_thumbnail_size( $size, $thumbnail_id, $post ) {
			if ( 'product' == get_post_type( $post ) ) {
				return 'shop_single';
			}
			return $size;
		}
		
	}

}
new WPEX_WooCommerce_Thumbnails;