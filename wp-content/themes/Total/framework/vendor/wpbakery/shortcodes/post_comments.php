<?php
/**
 * Visual Composer Comments
 *
 * @package Total WordPress Theme
 * @subpackage VC Functions
 * @version 4.8
 */

if ( ! class_exists( 'VCEX_Comments_Shortcode' ) ) {

	class VCEX_Comments_Shortcode {

		/**
		 * Main constructor
		 *
		 * @since 4.3
		 */
		public function __construct() {
			add_shortcode( 'vcex_post_comments', array( $this, 'output' ) );
			vc_lean_map( 'vcex_post_comments', array( $this, 'map' ) );
		}

		/**
		 * Shortcode output => Get template file and display shortcode
		 *
		 * @since 4.3
		 */
		public function output( $atts, $content = null ) {
			ob_start();
			include( locate_template( 'vcex_templates/vcex_post_comments.php' ) );
			return ob_get_clean();
		}

		/**
		 * Map shortcode to VC
		 *
		 * @since 4.3
		 */
		public function map() {
			return array(
				'name' => __( 'Post Comments', 'total' ),
				'description' => __( 'Display your post comments.', 'total' ),
				'base' => 'vcex_post_comments',
				'icon' => 'vcex-post-comments vcex-icon ticon ticon-comments',
				'category' => wpex_get_theme_branding(),
				'params' => array(
					array(
						'type' => 'vcex_visibility',
						'heading' => __( 'Visibility', 'total' ),
						'param_name' => 'visibility',
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Extra class name', 'total' ),
						'param_name' => 'el_class',
						'description' => __( 'Style particular content element differently - add a class name and refer to it in custom CSS.', 'total' ),
					),
				)
			);
		}
	}
}
new VCEX_Comments_Shortcode;