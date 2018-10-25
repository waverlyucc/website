<?php
/**
 * WooCommerce Entry Mods
 *
 * @package Total WordPress Theme
 * @subpackage WooCommerce
 * @version 4.5.5
 *
 */

if ( ! class_exists( 'WPEX_WooCommerce_Entries' ) ) {

	class WPEX_WooCommerce_Entries {

		/**
		 * Main Class Constructor
		 *
		 * @since 4.4
		 */
		public function __construct() {

			// Add HTML to product entries
			// Note link opens on 10 and closes on 5
			add_action( 'woocommerce_before_shop_loop_item', array( 'WPEX_WooCommerce_Entries', 'add_shop_loop_item_inner_div' ), 0 );
			add_action( 'woocommerce_after_shop_loop_item', array( 'WPEX_WooCommerce_Entries', 'close_shop_loop_item_inner_div' ), 99 );
			add_action( 'woocommerce_before_shop_loop_item', array( 'WPEX_WooCommerce_Entries', 'add_shop_loop_item_out_of_stock_badge' ) );

			// Add wrapper around product entry details to align buttons
			add_action( 'woocommerce_before_shop_loop_item_title', array( 'WPEX_WooCommerce_Entries', 'loop_details_open' ), 99 );
			add_action( 'woocommerce_after_shop_loop_item', array( 'WPEX_WooCommerce_Entries', 'loop_details_close' ), 4 );

		}

		/**
		 * Adds an opening div "product-inner" around product entries.
		 *
		 * @since 4.4
		 */
		public static function add_shop_loop_item_inner_div() {
			echo '<div class="product-inner clr">';
		}

		/**
		 * Closes the "product-inner" div around product entries.
		 *
		 * @since 4.4
		 */
		public static function close_shop_loop_item_inner_div() {
			echo '</div>';
		}

		/**
		 * Adds an out of stock tag to the products.
		 *
		 * @since 4.4
		 */
		public static function add_shop_loop_item_out_of_stock_badge() {
			if ( wpex_woo_product_instock() ) {
				return;
			} ?>
			<div class="outofstock-badge"><?php echo apply_filters( 'wpex_woo_outofstock_text', esc_html__( 'Out of Stock', 'total' ) ); ?></div>
			<?php
		}

		/**
		 * Open details wrapper
		 *
		 * @since 4.4
		 */
		public static function loop_details_open() {
			echo '<div class="product-details match-height-content">';
		}

		/**
		 * Close details wrapper
		 *
		 * @since 4.4
		 */
		public static function loop_details_close() {
			echo '</div>';
		}

	}

}
new WPEX_WooCommerce_Entries;