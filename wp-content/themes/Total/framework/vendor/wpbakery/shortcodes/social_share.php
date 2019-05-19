<?php
/**
 * Visual Composer Divider
 *
 * @package Total WordPress Theme
 * @subpackage VC Functions
 * @version 4.8
 */

if ( ! class_exists( 'VCEX_Social_Share_Shortcode' ) ) {

	class VCEX_Social_Share_Shortcode {

		/**
		 * Main constructor
		 *
		 * @since 4.4.1
		 */
		public function __construct() {
			add_shortcode( 'vcex_social_share', array( $this, 'output' ) );
			vc_lean_map( 'vcex_social_share', array( $this, 'map' ) );
		}

		/**
		 * Shortcode output => Get template file and display shortcode
		 *
		 * @since 4.4.1
		 */
		public function output( $atts, $content = null ) {
			ob_start();
			include( locate_template( 'vcex_templates/vcex_social_share.php' ) );
			return ob_get_clean();
		}

		/**
		 * Map shortcode to VC
		 *
		 * @since 4.4.1
		 */
		public function map() {

			$social_share_items = wpex_get_social_items();

			$default_sites = array();
			$site_choices  = array();

			foreach ( $social_share_items as $k => $v ) {
				$default_sites[$k] = array(
					'site' => $k
				);
				$site_choices[$v['site']] = $k;
			}

			return array(
				'name' => __( 'Social Share', 'total' ),
				'description' => __( 'Display post social share.', 'total' ),
				'base' => 'vcex_social_share',
				'icon' => 'vcex-social-share vcex-icon ticon ticon-share-alt',
				'category' => wpex_get_theme_branding(),
				'params' => array(
					array(
						'type' => 'vcex_visibility',
						'heading' => __( 'Visibility', 'total' ),
						'param_name' => 'visibility',
					),
					array(
						'type' => 'vcex_select_buttons',
						'heading' => __( 'Style', 'total' ),
						'param_name' => 'style',
						'std' => 'flat',
						'choices' => array(
							'flat' => __( 'Flat', 'total' ),
							'minimal' => __( 'Minimal', 'total' ),
							'three-d' => __( '3D', 'total' ),
							'rounded' => __( 'Rounded', 'total' ),
							'custom' => __( 'Custom', 'total' ),
						),
					),
					// Sites
					array(
						'type' => 'param_group',
						'param_name' => 'sites',
						'value' => urlencode( json_encode( $default_sites ) ),
						'params' => array(
							array(
								'type' => 'dropdown',
								'heading' => __( 'Site', 'total' ),
								'param_name' => 'site',
								'admin_label' => true,
								'value' => $site_choices,
							),
						),
					),
				)
			);
		}
	}
}
new VCEX_Social_Share_Shortcode;