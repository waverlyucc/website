<?php
/**
 * Registers the image swap shortcode and adds it to the Visual Composer
 *
 * @package Total WordPress Theme
 * @subpackage VC Templates
 * @version 4.6.5
 */

if ( ! class_exists( 'VCEX_Image_Before_After_Shortcode' ) ) {

	class VCEX_Image_Before_After_Shortcode {

		/**
		 * Main constructor
		 *
		 * @since 4.3
		 */
		public function __construct() {
			add_shortcode( 'vcex_image_ba', array( $this, 'output' ) );
			vc_lean_map( 'vcex_image_ba', array( $this, 'map' ) );
		}

		/**
		 * Register scripts
		 *
		 * @since 4.3
		 */
		public function enqueue_scripts() {
				
			wp_enqueue_script(
				'jquery-move',
				wpex_asset_url( 'js/dynamic/jquery.event.move.js' ),
				array( 'jquery' ),
				WPEX_THEME_VERSION,
				true
			);

			wp_enqueue_script(
				'twentytwenty',
				wpex_asset_url( 'js/dynamic/jquery.twentytwenty.js' ),
				array( 'jquery', 'jquery-move' ),
				WPEX_THEME_VERSION,
				true
			);

		}

		/**
		 * Shortcode output => Get template file and display shortcode
		 *
		 * @since 4.3
		 */
		public function output( $atts, $content = null ) {
			ob_start();
			include( locate_template( 'vcex_templates/vcex_image_before_after.php' ) );
			return ob_get_clean();
		}

		/**
		 * Map shortcode to VC
		 *
		 * @since 4.3
		 */
		public function map() {
			return array(
				'name' => __( 'Image Before/After', 'total' ),
				'description' => __( 'Visual difference between two images', 'total' ),
				'base' => 'vcex_image_ba',
				'icon' => 'vcex-image-ba vcex-icon fa fa-picture-o',
				'category' => wpex_get_theme_branding(),
				'params' => array(
					// Images
					array(
						'type' => 'attach_image',
						'heading' => __( 'Before', 'total' ),
						'param_name' => 'before_img',
						'group' => __( 'Images', 'total' ),
					),
					array(
						'type' => 'attach_image',
						'heading' => __( 'After', 'total' ),
						'param_name' => 'after_img',
						'group' => __( 'Images', 'total' ),
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Module Width', 'total' ),
						'param_name' => 'width',
						'group' => __( 'Images', 'total' ),
						'description' => __( 'Enter a width to constrain this module.', 'total' ),
					),
					array(
						'type' => 'vcex_text_alignments',
						'heading' => __( 'Module Align', 'total' ),
						'param_name' => 'align',
						'group' => __( 'Images', 'total' ),
						'dependency' => array( 'element' => 'width', 'not_empty' => true ),
					),
					array(
						'type' => 'vcex_image_sizes',
						'heading' => __( 'Image Size', 'total' ),
						'param_name' => 'img_size',
						'std' => 'full',
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
						'type' => 'vcex_number',
						'heading' => __( 'Default Offset Percentage', 'total' ),
						'std' => '0.5',
						'param_name' => 'default_offset_pct',
						'max'  => 1,
						'min'  => 0.1,
						'step' => 0.1,
					),
					array(
						'type' => 'vcex_select_buttons',
						'heading' => __( 'Orientation', 'total' ),
						'param_name' => 'orientation',
						'std' => 'horizontal',
						'choices' => array(
							'horizontal' => __( 'Horizontal', 'total' ),
							'vertical' => __( 'Vertical', 'total' ),
						),
					),
					array(
						'type' => 'vcex_ofswitch',
						'heading' => __( 'Overlay', 'total' ),
						'std' => 'true',
						'param_name' => 'overlay',
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Before Label', 'total' ),
						'param_name' => 'before_label',
						'dependency' => array( 'element' => 'overlay', 'value' => 'true' ),
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'After Label', 'total' ),
						'param_name' => 'after_label',
						'dependency' => array( 'element' => 'overlay', 'value' => 'true' ),
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Extra class name', 'total' ),
						'description' => __( 'Style particular content element differently - add a class name and refer to it in custom CSS.', 'total' ),
						'param_name' => 'el_class',
					),
					vcex_vc_map_add_css_animation(),
					// Design Options
					array(
						'type' => 'css_editor',
						'heading' => __( 'CSS', 'total' ),
						'param_name' => 'css',
						'group' => __( 'Design options', 'total' ),
					),
				)
			);
		}

	}
}
new VCEX_Image_Before_After_Shortcode;