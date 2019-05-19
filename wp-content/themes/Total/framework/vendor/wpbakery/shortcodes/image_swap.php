<?php
/**
 * Registers the image swap shortcode and adds it to the Visual Composer
 *
 * @package Total WordPress Theme
 * @subpackage VC Templates
 * @version 4.8
 */

if ( ! class_exists( 'VCEX_Image_Swap_Shortcode' ) ) {

	class VCEX_Image_Swap_Shortcode {

		/**
		 * Main constructor
		 *
		 * @since 3.5.0
		 */
		public function __construct() {
			add_shortcode( 'vcex_image_swap', array( $this, 'output' ) );
			vc_lean_map( 'vcex_image_swap', array( $this, 'map' ) );
		}

		/**
		 * Shortcode output => Get template file and display shortcode
		 *
		 * @since 3.5.0
		 */
		public function output( $atts, $content = null ) {
			ob_start();
			include( locate_template( 'vcex_templates/vcex_image_swap.php' ) );
			return ob_get_clean();
		}

		/**
		 * Map shortcode to VC
		 *
		 * @since 3.5.0
		 */
		public function map() {
			return array(
				'name' => __( 'Image Swap', 'total' ),
				'description' => __( 'Double Image Hover Effect', 'total' ),
				'base' => 'vcex_image_swap',
				'icon' => 'vcex-image-swap vcex-icon ticon ticon-picture-o',
				'category' => wpex_get_theme_branding(),
				'params' => array(
					// Images
					array(
						'type' => 'attach_image',
						'heading' => __( 'Primary Image', 'total' ),
						'param_name' => 'primary_image',
						'group' => __( 'Images', 'total' ),
					),
					array(
						'type' => 'attach_image',
						'heading' => __( 'Secondary Image', 'total' ),
						'param_name' => 'secondary_image',
						'group' => __( 'Images', 'total' ),
					),
					array(
						'type' => 'vcex_image_sizes',
						'heading' => __( 'Image Size', 'total' ),
						'param_name' => 'img_size',
						'std' => 'wpex_custom',
						'group' => __( 'Images', 'total' ),
					),
					array(
						'type' => 'vcex_image_crop_locations',
						'heading' => __( 'Image Crop Location', 'total' ),
						'param_name' => 'img_crop',
						'std' => 'center-center',
						'dependency' => array( 'element' => 'img_size', 'value' => 'wpex_custom' ),
						'group' => __( 'Images', 'total' ),
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Image Crop Width', 'total' ),
						'param_name' => 'img_width',
						'group' => __( 'Images', 'total' ),
						'dependency' => array( 'element' => 'img_size', 'value' => 'wpex_custom' ),
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Image Crop Height', 'total' ),
						'param_name' => 'img_height',
						'description' => __( 'Enter a height in pixels. Leave empty to disable vertical cropping and keep image proportions.', 'total' ),
						'group' => __( 'Images', 'total' ),
						'dependency' => array( 'element' => 'img_size', 'value' => 'wpex_custom' ),
					),
					// General
					array(
						'type' => 'textfield',
						'heading' => __( 'Unique Id', 'total' ),
						'param_name' => 'unique_id',
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Extra class name', 'total' ),
						'description' => __( 'Style particular content element differently - add a class name and refer to it in custom CSS.', 'total' ),
						'param_name' => 'classes',
					),
					vcex_vc_map_add_css_animation(),
					// Link
					array(
						'type' => 'vc_link',
						'heading' => __( 'Link', 'total' ),
						'param_name' => 'link',
						'group' => __( 'Link', 'total' ),
					),
					array(
						'type' => 'vcex_ofswitch',
						'std' => 'false',
						'heading' => __( 'Enable Tooltip?', 'total' ),
						'param_name' => 'link_tooltip',
						'group' => __( 'Link', 'total' ),
					),
					// Design Options
					array(
						'type' => 'css_editor',
						'heading' => __( 'CSS', 'total' ),
						'param_name' => 'css',
						'group' => __( 'Design options', 'total' ),
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Container Width', 'total' ),
						'param_name' => 'container_width',
						'group' => __( 'Image', 'total' ),
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Border Radius', 'total' ),
						'param_name' => 'border_radius',
						'group' => __( 'Image', 'total' ),
					),
					// Hidden
					array(
						'type' => 'hidden',
						'param_name' => 'link_title',
					),
					array(
						'type' => 'hidden',
						'param_name' => 'link_target',
					),
				)
			);
		}

	}
}
new VCEX_Image_Swap_Shortcode;