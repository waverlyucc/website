<?php
/**
 * Visual Composer Post Content
 *
 * @package Total WordPress Theme
 * @subpackage VC Functions
 * @version 4.6.5
 */

if ( ! class_exists( 'VCEX_Post_Content_Shortcode' ) ) {

	class VCEX_Post_Content_Shortcode {

		/**
		 * Main constructor
		 *
		 * @since 4.3
		 */
		public function __construct() {
			add_shortcode( 'vcex_post_content', array( $this, 'output' ) );
			vc_lean_map( 'vcex_post_content', array( $this, 'map' ) );
		}

		/**
		 * Shortcode output => Get template file and display shortcode
		 *
		 * @since 4.3
		 */
		public function output( $atts, $content = null ) {
			ob_start();
			include( locate_template( 'vcex_templates/vcex_post_content.php' ) );
			return ob_get_clean();
		}

		/**
		 * Map shortcode to VC
		 *
		 * @since 4.3
		 */
		public function map() {
			return array(
				'name' => __( 'Post Content', 'total' ),
				'description' => __( 'Display your post content.', 'total' ),
				'base' => 'vcex_post_content',
				'icon' => 'vcex-post-content vcex-icon fa fa-pencil',
				'category' => wpex_get_theme_branding(),
				'params' => array(
					array(
						'type' => 'vcex_notice',
						'param_name' => 'main_notice',
						'text' => __( 'The Post Content module should be used only when creating a custom template via templatera that will override the default output of a post/page.', 'total' ),
					),
					array(
						'type' => 'vcex_font_family_select',
						'heading' => __( 'Font Family', 'total' ),
						'param_name' => 'font_family',
					),
					array(
						'type' => 'vcex_responsive_sizes',
						'heading' => __( 'Font Size', 'total' ),
						'param_name' => 'font_size',
						'target' => 'font-size',
					),
					array(
						'type' => 'css_editor',
						'heading' => __( 'Design Options', 'total' ),
						'param_name' => 'css',
					),
				)
			);
		}
	}
}
new VCEX_Post_Content_Shortcode;