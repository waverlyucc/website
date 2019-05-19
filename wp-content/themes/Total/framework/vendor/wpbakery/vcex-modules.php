<?php
/**
 * Visual Composer Custom Modules
 *
 * @package Total WordPress Theme
 * @subpackage Visual Composer
 * @version 4.5.4
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'WPEX_Visual_Composer_Extension' ) ) {

	class WPEX_Visual_Composer_Extension {

		/**
		 * Start things up
		 *
		 * @since 4.5
		 */
		public function __construct() {
			add_action( 'vc_after_mapping', array( $this, 'load_classes' ), 0 );
		}

		/**
		 * Load custom module classes
		 *
		 * @since 4.5
		 */
		public function load_classes() {

			$modules = vcex_builder_modules();

			if ( ! empty( $modules ) ) {

				foreach ( $modules as $key => $val ) {

					if ( is_array( $val ) ) {

						$condition = isset( $val['condition'] ) ? $val['condition'] : true;
						$file      = isset( $val['file'] ) ? $val['file'] : WPEX_VCEX_DIR . 'shortcodes/' . $key . '.php';
						if ( $condition ) {
							require_once $file;
						}

					} else {

						$file = WPEX_VCEX_DIR . 'shortcodes/' . $val . '.php';
						require_once $file;

					}

				}

			}

		}

	}

}
new WPEX_Visual_Composer_Extension;