<?php
/**
 * Visual Composer Post Meta
 *
 * @package Total WordPress Theme
 * @subpackage VC Functions
 * @version 4.8
 *
 */

if ( ! class_exists( 'VCEX_Post_Meta_Shortcode' ) ) {

	class VCEX_Post_Meta_Shortcode {

		/**
		 * Main constructor
		 *
		 * @since 4.4.1
		 */
		public function __construct() {
			add_shortcode( 'vcex_post_meta', array( $this, 'output' ) );
			vc_lean_map( 'vcex_post_meta', array( $this, 'map' ) );
		}

		/**
		 * Shortcode output => Get template file and display shortcode
		 *
		 * @since 4.4.1
		 */
		public function output( $atts, $content = null ) {
			ob_start();
			include( locate_template( 'vcex_templates/vcex_post_meta.php' ) );
			return ob_get_clean();
		}

		/**
		 * Map shortcode to VC
		 *
		 * @since 4.4.1
		 */
		public function map() {
			return array(
				'name' => __( 'Post Meta', 'total' ),
				'description' => __( 'Author, date, comments...', 'total' ),
				'base' => 'vcex_post_meta',
				'category' => wpex_get_theme_branding(),
				'icon' => 'vcex-post-meta vcex-icon ticon ticon-list-alt',
				'params' => array(
					array(
						'type' => 'vcex_text_alignments',
						'heading' => __( 'Align', 'total' ),
						'param_name' => 'align',
						'std' => '',
					),
					// Sections
					array(
						'type' => 'param_group',
						'param_name' => 'sections',
						'value' => urlencode( json_encode( array(
							array(
								'type' => 'date',
								'icon_type' => 'fontawesome',
								'icon' => 'fa fa-clock-o',
							),
							array(
								'type' => 'author',
								'icon_type' => 'fontawesome',
								'icon' => 'fa fa-user-o',
							),
							array(
								'type' => 'comments',
								'icon_type' => 'fontawesome',
								'icon' => 'fa fa-comment-o',
							),
							array(
								'type' => 'post_terms',
								'taxonomy' => 'category',
								'fist_only' => 'false',
								'icon_type' => 'fontawesome',
								'icon' => 'fa fa-folder-o',
							),
						) ) ),
						'params' => array(
							array(
								'type' => 'dropdown',
								'heading' => __( 'Section', 'total' ),
								'param_name' => 'type',
								'admin_label' => true,
								'value' => apply_filters( 'vcex_post_meta_sections', array(
									__( 'Date', 'total' ) => 'date',
									__( 'Author', 'total' ) => 'author',
									__( 'Comments', 'total' ) => 'comments',
									__( 'Post Terms', 'total' ) => 'post_terms',
								) ),
							),
							array(
								'type' => 'textfield',
								'heading' => __( 'Taxonony Name', 'total' ),
								'param_name' => 'taxonomy',
								'dependency' => array( 'element' => 'type', 'value' => 'post_terms' )
							),
							array(
								'type' => 'dropdown',
								'heading' => __( 'Icon library', 'total' ),
								'param_name' => 'icon_type',
								'description' => __( 'Select icon library.', 'total' ),
								'value' => array(
									__( 'Font Awesome', 'total' ) => 'fontawesome',
									__( 'Typicons', 'total' ) => 'typicons',
								),
							),
							array(
								'type' => 'iconpicker',
								'heading' => __( 'Icon', 'total' ),
								'param_name' => 'icon',
								'value' => 'fa fa-info-circle',
								'settings' => array( 'emptyIcon' => true, 'iconsPerPage' => 200 ),
								'dependency' => array( 'element' => 'icon_type', 'value' => 'fontawesome' ),
							),
							array(
								'type' => 'iconpicker',
								'heading' => __( 'Icon', 'total' ),
								'param_name' => 'icon_typicons',
								'settings' => array(
									'emptyIcon' => true,
									'type' => 'typicons',
									'iconsPerPage' => 200,
								),
								'dependency' => array( 'element' => 'icon_type', 'value' => 'typicons' ),
							),
						),
					),
					// Typography
					array(
						'type' => 'textfield',
						'heading' => __( 'Font Size', 'total' ),
						'param_name' => 'font_size',
						'group' => __( 'Typography', 'total' ),
					),
					array(
						'type' => 'colorpicker',
						'heading' => __( 'Color', 'total' ),
						'param_name' => 'color',
						'group' => __( 'Typography', 'total' ),
					),
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
new VCEX_Post_Meta_Shortcode;