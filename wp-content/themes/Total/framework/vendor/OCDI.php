<?php
/**
 * Returns array of all demos and their settings
 *
 * @package Total WordPress Theme
 * @subpackage Framework
 * @version 4.6.6
 */

namespace TotalTheme\Vendor;

use WP_Error;
use RevSlider;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class OCDI {

	/**
	 * Main constructor
	 *
	 * @version 4.6.6
	 */
	public function __construct() {
		add_filter( 'pt-ocdi/disable_pt_branding', '__return_true' );
		add_filter( 'pt-ocdi/import_files', array( $this, 'register_import_files' ) );
		add_action( 'pt-ocdi/after_import', array( $this, 'after_import' ), PHP_INT_MAX );
	}

	/**
	 * Return import files array
	 *
	 * @since 4.6.6
	 */
	public function get_import_files() {

		$demos = get_transient( 'wpex_import_files' );

		//delete_transient( 'wpex_import_files' );

		if ( ! $demos ) {

			$json_url = 'http://totaltheme.wpengine.com/import-files/';

			$response = wp_remote_get( $json_url );

			if ( is_wp_error( $response ) || ( wp_remote_retrieve_response_code( $response ) != 200 ) ) {
				return array();
			}

			$body = wp_remote_retrieve_body( $response );

			if ( ! $body ) {
				return array();
			}

			$demos = json_decode( $body, true );

			$demos['adapt']['import_notice'] = 'You need to activate WooCommerce before running the demo import.';

			set_transient( 'wpex_import_files', $demos, 48 * HOUR_IN_SECONDS );

		}

		return apply_filters( 'wpex_import_files', $demos );

	}

	/**
	 * Register import files
	 *
	 * @since 4.6.6
	 */
	public function register_import_files() {
		return $this->get_import_files();
	}

	/**
	 * Run actions after import
	 *
	 * @since 4.6.6
	 */
	public function after_import( $index ) {

		update_option( 'blogname', $index[ 'import_file_name' ] );

		$this->set_menus( $index );

		if ( isset( $index[ 'homepage_slug' ] ) ) {
			$this->set_homepage( $index[ 'homepage_slug' ] );
		}

		if ( isset( $index[ 'page_for_posts' ] ) ) {
			$this->set_page_for_posts( $index[ 'page_for_posts' ] );
		}

		if ( isset( $index[ 'shop_slug' ] ) ) {
			$this->set_shop( $index[ 'shop_slug' ] );
		}

		if ( isset( $index[ 'revsliders' ] ) ) {
			$this->import_revsliders( $index[ 'revsliders' ] );
		}

	}

	/**
	 * Setup menus after import
	 *
	 * @since 4.6.6
	 */
	public function set_menus( $index ) {

		if ( empty( $index[ 'nav_menu_locations' ] ) ) {
			return;
		}

		$locations = get_theme_mod( 'nav_menu_locations' );

		foreach ( $index[ 'nav_menu_locations' ] as $location => $name ) {
			$menu = get_term_by( 'name', $name, 'nav_menu');
			if ( $menu ) {
				$locations[ $location ] = $menu->term_id;
			}
		}

		set_theme_mod( 'nav_menu_locations', $locations );

	}

	/**
	 * Set correct homepage after import
	 *
	 * @since 4.6.6
	 */
	public function set_homepage( $slug ) {

		$page = get_page_by_path( $slug );

		if ( $page && $page->ID ) {
			update_option( 'page_on_front', $page->ID );
			update_option( 'show_on_front', 'page' );
		}

	}

	/**
	 * Set correct posts page after import
	 *
	 * @since 4.6.6
	 */
	public function set_page_for_posts( $slug ) {

		$posts_page = get_page_by_path( $slug );

		if ( $posts_page && $posts_page->ID ) {

			update_option( 'page_for_posts', $posts_page->ID );

		}

	}

	/**
	 * Set correct shop page after import
	 *
	 * @since 4.6.6
	 */
	public function set_shop( $slug ) {

		$shop = get_page_by_path( $slug );

		if ( $shop && $shop->ID ) {

			update_option( 'woocommerce_shop_page_id', $shop->ID );

		}

	}

	/**
	 * Import Slider Revolition sliders
	 *
	 * @since 4.6.6
	 */
	public function import_revsliders( $sliders ) {

		$ocdi = OCDI\OneClickDemoImport::get_instance();

		// Checks
		if ( ! function_exists( 'download_url' ) ) {
			$ocdi->append_to_frontend_error_messages( __( 'Sliders could not be imported because the download_url function does not exist.', 'total' ) );
		}

		// Make sure rev is active
		if ( ! class_exists( 'RevSlider' ) ) {
			$ocdi->append_to_frontend_error_messages( __( 'Sliders could not be imported because the Revolution slider plugin is disabled.', 'total' ) );
		}

		$errors = array();

		// Loop through slider zips and upload to media library then import
		foreach( $sliders as $slider_url ) {

			// Download zip from github
			$temp = download_url( $slider_url );

			// Files array for use with media_handle_sideload()
			$file_array = array(
				'name'     => basename( $slider_url ),
				'tmp_name' => $temp
			);

			// Check for download errors
			if ( $error = is_wp_error( $temp ) ) {
				if ( is_object( $error ) ) {
					$ocdi->append_to_frontend_error_messages( __( 'Slider error:', 'total' ) . ' ' . $temp->get_error_message() );
				}
				unlink( $file_array[ 'tmp_name' ] );
				continue;
			}

			// Get media ID
			$id = media_handle_sideload( $file_array, 0 );

			// Check for handle sideload errors.
			if ( is_wp_error( $id ) ) {
				if ( is_object( $id ) ) {
					$ocdi->append_to_frontend_error_messages( __( 'Slider error:', 'total' ) . ' ' . $temp->get_error_message() );
				}
				unlink( $file_array['tmp_name'] );
				continue;
			}

			// Get attachment url
			$attachment_url = get_attached_file( $id );

			// New Revslider Class
			$slider = new RevSlider();

			// Import slider
			$slider->importSliderFromPost( true, true, $attachment_url );
		}

	}

}
new OCDI();