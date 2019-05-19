<?php
/**
 * Visual Composer Post Terms
 *
 * @package Total WordPress Theme
 * @subpackage VC Functions
 * @version 4.8
 */

if ( ! class_exists( 'VCEX_Post_Terms_Shortcode' ) ) {

	class VCEX_Post_Terms_Shortcode {

		/**
		 * Main constructor
		 *
		 * @since 3.6.0
		 */
		public function __construct() {
			
			// Add main shortcode
			add_shortcode( 'vcex_post_terms', array( $this, 'output' ) );

			// Add grid item shortcode @todo
			//add_shortcode( 'vcex_gitem_post_terms', array( $this, 'gitem_output' ) );

			// Map to VC
			vc_lean_map( 'vcex_post_terms', array( $this, 'map' ) );

			// Admin filters
			if ( is_admin() ) {

				// Suggest tax
				add_filter( 'vc_autocomplete_vcex_post_terms_taxonomy_callback', 'vcex_suggest_taxonomies', 10, 1 );
				add_filter( 'vc_autocomplete_vcex_post_terms_taxonomy_render', 'vcex_render_taxonomies', 10, 1 );

				// Suggest terms
				add_filter( 'vc_autocomplete_vcex_post_terms_exclude_terms_callback', 'vcex_suggest_terms', 10, 1 );
				add_filter( 'vc_autocomplete_vcex_post_terms_exclude_terms_render', 'vcex_render_terms', 10, 1 );

			}

		}

		/**
		 * Shortcode output => Get template file and display shortcode
		 *
		 * @since 3.6.0
		 */
		public function output( $atts, $content = null ) {
			ob_start();
			include( locate_template( 'vcex_templates/vcex_post_terms.php' ) );
			return ob_get_clean();
		}

		/**
		 * Shortcode output => Get template file and display shortcode
		 *
		 * @since 3.6.0
		 */
		public function gitem_output( $atts, $content = null ) {
			ob_start();
			include( locate_template( 'vcex_templates/vcex_post_terms.php' ) );
			return ob_get_clean();
		}

		/**
		 * Map shortcode to VC
		 *
		 * @since 3.6.0
		 */
		public function map() {
			return array(
				'name' => __( 'Post Terms', 'total' ),
				'description' => __( 'Display your post terms', 'total' ),
				'base' => 'vcex_post_terms',
				'category' => wpex_get_theme_branding(),
				'icon' => 'vcex-post-terms vcex-icon ticon ticon-folder',
				'params' => array(
					// General
					array(
						'type' => 'textfield',
						'heading' => __( 'Unique Id', 'total' ),
						'param_name' => 'unique_id',
						'admin_label' => true,
					),
					array(
						'type' => 'textfield',
						'admin_label' => true,
						'heading' => __( 'Extra class name', 'total' ),
						'param_name' => 'classes',
						'description' => __( 'Style particular content element differently - add a class name and refer to it in custom CSS.', 'total' ),
					),
					vcex_vc_map_add_css_animation(),
					array(
						'type' => 'vcex_visibility',
						'heading' => __( 'Visibility', 'total' ),
						'param_name' => 'visibility',
					),
					array(
						'type' => 'autocomplete',
						'heading' => __( 'Taxonomy', 'total' ),
						'param_name' => 'taxonomy',
						'admin_label' => true,
						'std' => '',
						'settings' => array(
							'multiple' => false,
							'min_length' => 1,
							'groups' => false,
							'unique_values' => true,
							'display_inline' => true,
							'delay' => 0,
							'auto_focus' => true,
						),
					),
					array(
						'type' => 'autocomplete',
						'heading' => __( 'Exclude terms', 'total' ),
						'param_name' => 'exclude_terms',
						'settings' => array(
							'multiple' => true,
							'min_length' => 1,
							'groups' => true,
							'display_inline' => true,
							'delay' => 0,
							'auto_focus' => true,
						),
					),
					array(
						'type' => 'vcex_ofswitch',
						'std' => 'false',
						'heading' => __( 'Display First/Featured Term Only', 'total' ),
						'param_name' => 'first_term_only',
					),
					array(
						'type' => 'dropdown',
						'heading' => __( 'Order', 'total' ),
						'param_name' => 'order',
						'value' => array(
							__( 'ASC', 'total' ) => 'ASC',
							__( 'DESC', 'total' ) => 'DESC',
						),
					),
					array(
						'type' => 'dropdown',
						'heading' => __( 'Order By', 'total' ),
						'param_name' => 'orderby',
						'value' => array(
							__( 'Name', 'total' ) => 'name',
							__( 'Slug', 'total' ) => 'slug',
							__( 'Term Group', 'total' ) => 'term_group',
							__( 'Term ID', 'total' ) => 'term_id',
							'ID' => 'id',
							__( 'Description', 'total' ) => 'description',
						),
					),
					// Link
					array(
						'type' => 'vcex_ofswitch',
						'std' => 'true',
						'heading' => __( 'Link to Archive?', 'total' ),
						'param_name' => 'archive_link',
						'group' => __( 'Link', 'total' ),
					),
					array(
						'type' => 'vcex_select_buttons',
						'heading' => __( 'Link Target', 'total' ),
						'param_name' => 'target',
						'std' => 'self',
						'choices' => 'link_target',
						'group' => __( 'Link', 'total' ),
						'dependency' => array( 'element' => 'archive_link', 'value' => 'true' )
					),
					// Design
					array(
						'type' => 'vcex_button_styles',
						'heading' => __( 'Style', 'total' ),
						'param_name' => 'button_style',
						'group' => __( 'Design', 'total' ),
					),
					array(
						'type' => 'vcex_button_colors',
						'heading' => __( 'Color', 'total' ),
						'param_name' => 'button_color_style',
						'group' => __( 'Design', 'total' ),
					),
					array(
						'type' => 'vcex_text_alignments',
						'heading' => __( 'Align', 'total' ),
						'param_name' => 'button_align',
						'group' => __( 'Design', 'total' ),
					),
					array(
						'type' => 'dropdown',
						'heading' => __( 'Size', 'total' ),
						'param_name' => 'button_size',
						'std' => '',
						'value' => array(
							__( 'Default', 'total' ) => '',
							__( 'Small', 'total' ) => 'small',
							__( 'Medium', 'total' ) => 'medium',
							__( 'Large', 'total' ) => 'large',
						),
						'group' => __( 'Design', 'total' ),
					),
					array(
						'type' => 'vcex_font_family_select',
						'heading' => __( 'Font Family', 'total' ),
						'param_name' => 'button_font_family',
						'group' => __( 'Design', 'total' ),
					),
					array(
						'type' => 'colorpicker',
						'heading' => __( 'Background', 'total' ),
						'param_name' => 'button_background',
						'group' => __( 'Design', 'total' ),
					),
					array(
						'type' => 'colorpicker',
						'heading' => __( 'Background: Hover', 'total' ),
						'param_name' => 'button_hover_background',
						'group' => __( 'Design', 'total' ),
					),
					array(
						'type' => 'colorpicker',
						'heading' => __( 'Color', 'total' ),
						'param_name' => 'button_color',
						'group' => __( 'Design', 'total' ),
					),
					array(
						'type' => 'colorpicker',
						'heading' => __( 'Color: Hover', 'total' ),
						'param_name' => 'button_hover_color',
						'group' => __( 'Design', 'total' ),
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Font Size', 'total' ),
						'param_name' => 'button_font_size',
						'group' => __( 'Design', 'total' ),
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Letter Spacing', 'total' ),
						'param_name' => 'button_letter_spacing',
						'group' => __( 'Design', 'total' ),
					),
					array(
						'type' => 'vcex_text_transforms',
						'heading' => __( 'Text Transform', 'total' ),
						'param_name' => 'button_text_transform',
						'group' => __( 'Design', 'total' ),
					),
					array(
						'type' => 'vcex_font_weight',
						'heading' => __( 'Font Weight', 'total' ),
						'param_name' => 'button_font_weight',
						'group' => __( 'Design', 'total' ),
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Border Radius', 'total' ),
						'param_name' => 'button_border_radius',
						'description' => __( 'Please enter a px value.', 'total' ),
						'group' => __( 'Design', 'total' ),
					),
					array(
						'type' => 'vcex_trbl',
						'heading' => __( 'Padding', 'total' ),
						'param_name' => 'button_padding',
						'group' => __( 'Design', 'total' ),
					),
					array(
						'type' => 'vcex_trbl',
						'heading' => __( 'Margin', 'total' ),
						'param_name' => 'button_margin',
						'group' => __( 'Design', 'total' ),
					),
				)
			);
		}

	}
}
new VCEX_Post_Terms_Shortcode;