<?php
/**
 * Post Type Archive
 *
 * @package Total WordPress Theme
 * @subpackage VC Functions
 * @version 4.8
 */

if ( ! class_exists( 'VCEX_Post_Type_Archive_Shortcode' ) ) {

	class VCEX_Post_Type_Archive_Shortcode {

		/**
		 * Main constructor
		 *
		 * @since 3.5.0
		 */
		public function __construct() {

			// Add shortcode
			add_shortcode( 'vcex_post_type_archive', array( $this, 'output' ) );

			// Map to VC
			vc_lean_map( 'vcex_post_type_archive', array( $this, 'map' ) );

			// Admin filters
			if ( is_admin() ) {

				// Get autocomplete suggestion
				add_filter( 'vc_autocomplete_vcex_post_type_archive_tax_query_taxonomy_callback', 'vcex_suggest_taxonomies', 10, 1 );
				add_filter( 'vc_autocomplete_vcex_post_type_archive_tax_query_terms_callback', 'vcex_suggest_terms', 10, 1 );
				add_filter( 'vc_autocomplete_vcex_post_type_archive_author_in_callback', 'vcex_suggest_users', 10, 1 );

				// Render autocomplete suggestions
				add_filter( 'vc_autocomplete_vcex_post_type_archive_tax_query_taxonomy_render', 'vcex_render_taxonomies', 10, 1 );
				add_filter( 'vc_autocomplete_vcex_post_type_archive_tax_query_terms_render', 'vcex_render_terms', 10, 1 );
				add_filter( 'vc_autocomplete_vcex_post_type_archive_author_in_render', 'vcex_render_users', 10, 1 );

			}

		}

		/**
		 * Shortcode output => Get template file and display shortcode
		 *
		 * @since 3.5.0
		 */
		public function output( $atts, $content = null ) {
			ob_start();
			include( locate_template( 'vcex_templates/vcex_post_type_archive.php' ) );
			return ob_get_clean();
		}

		/**
		 * Map shortcode to VC
		 *
		 * @since 3.5.0
		 */
		public function map() {
			$post_types = array();
			if ( is_admin() ) {
				$post_types = vcex_get_post_types();
			}
			return array(
				'name' => __( 'Post Types Archive', 'total' ),
				'description' => __( 'Custom post type archive', 'total' ),
				'base' => 'vcex_post_type_archive',
				'category' => wpex_get_theme_branding(),
				'icon' => 'vcex-post-type-grid vcex-icon ticon ticon-files-o',
				'params' => array(
					// General
					array(
						'type' => 'textfield',
						'heading' => __( 'Heading', 'total' ),
						'param_name' => 'heading',
					),
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
					array(
						'type' => 'vcex_visibility',
						'heading' => __( 'Visibility', 'total' ),
						'param_name' => 'visibility',
					),
					vcex_vc_map_add_css_animation(),
					// Query
					array(
						'type' => 'vcex_ofswitch',
						'std' => 'false',
						'heading' => __( 'Advanced Query?', 'total' ),
						'param_name' => 'custom_query',
						'group' => __( 'Query', 'total' ),
						'description' => __( 'Enable to build a custom query using your own parameter string.', 'total' ),
					),
					array(
						'type' => 'textarea_safe',
						'heading' => __( 'Custom query', 'total' ),
						'param_name' => 'custom_query_args',
						'description' => __( 'Build custom query according to <a href="http://codex.wordpress.org/Function_Reference/query_posts" target="_blank">WordPress Codex</a>.', 'total' ),
						'group' => __( 'Query', 'total' ),
						'dependency' => array( 'element' => 'custom_query', 'value' => array( 'true' ) ),
					),
					array(
						'type' => 'dropdown',
						'heading' => __( 'Post Type', 'total' ),
						'param_name' => 'post_type',
						'value' => $post_types,
						'group' => __( 'Query', 'total' ),
						'dependency' => array( 'element' => 'custom_query', 'value' => array( 'false' ) ),
					),
					array(
						'type' => 'dropdown',
						'heading' => __( 'Order', 'total' ),
						'param_name' => 'order',
						'group' => __( 'Query', 'total' ),
						'value' => array(
							__( 'Default', 'total' ) => '',
							__( 'DESC', 'total' ) => 'DESC',
							__( 'ASC', 'total' ) => 'ASC',
						),
						'dependency' => array( 'element' => 'custom_query', 'value' => array( 'false' ) ),
					),
					array(
						'type' => 'dropdown',
						'heading' => __( 'Order By', 'total' ),
						'param_name' => 'orderby',
						'value' => vcex_orderby_array(),
						'group' => __( 'Query', 'total' ),
						'dependency' => array( 'element' => 'custom_query', 'value' => array( 'false' ) ),
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Orderby: Meta Key', 'total' ),
						'param_name' => 'orderby_meta_key',
						'group' => __( 'Query', 'total' ),
						'dependency' => array(
							'element' => 'orderby',
							'value' => array( 'meta_value_num', 'meta_value' ),
						),
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Offset', 'total' ),
						'param_name' => 'offset',
						'group' => __( 'Query', 'total' ),
						'description' => __( 'Number of post to displace or pass over. Warning: Setting the offset parameter overrides/ignores the paged parameter and breaks pagination. The offset parameter is ignored when posts per page is set to -1.', 'total' ),
						'dependency' => array( 'element' => 'custom_query', 'value' => array( 'false' ) ),
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Posts Per Page', 'total' ),
						'param_name' => 'posts_per_page',
						'value' => '12',
						'description' => __( 'You can enter "-1" to display all posts.', 'total' ),
						'group' => __( 'Query', 'total' ),
						'dependency' => array( 'element' => 'custom_query', 'value' => array( 'false' ) ),
					),
					array(
						'type' => 'vcex_ofswitch',
						'std' => 'false',
						'heading' => __( 'Query by Taxonomy', 'total' ),
						'param_name' => 'tax_query',
						'group' => __( 'Query', 'total' ),
						'dependency' => array( 'element' => 'custom_query', 'value' => array( 'false' ) ),
					),
					array(
						'type' => 'autocomplete',
						'heading' => __( 'Taxonomy Name', 'total' ),
						'param_name' => 'tax_query_taxonomy',
						'settings' => array(
							'multiple' => false,
							'min_length' => 1,
							'groups' => false,
							//'unique_values' => true,
							'display_inline' => true,
							'delay' => 0,
							'auto_focus' => true,
						),
						'group' => __( 'Query', 'total' ),
						'description' => __( 'If you do not see your taxonomy in the dropdown you can still enter the taxonomy name manually.', 'total' ),
						'dependency' => array( 'element' => 'tax_query', 'value' => array( 'true' ) ),
					),
					array(
						'type' => 'autocomplete',
						'heading' => __( 'Terms', 'total' ),
						'param_name' => 'tax_query_terms',
						'dependency' => array(
							'element' => 'tax_query',
							'value' => 'true',
						),
						'settings' => array(
							'multiple' => true,
							'min_length' => 1,
							'groups' => true,
							//'unique_values' => true,
							'display_inline' => true,
							'delay' => 0,
							'auto_focus' => true,
						),
						'group' => __( 'Query', 'total' ),
						'description' => __( 'If you do not see your terms in the dropdown you can still enter the term slugs manually seperated by a space.', 'total' ),
						'dependency' => array( 'element' => 'tax_query', 'value' => array( 'true' ) ),
					),
					array(
						'type' => 'vcex_ofswitch',
						'std' => 'false',
						'heading' => __( 'Post With Thumbnails Only', 'total' ),
						'param_name' => 'thumbnail_query',
						'group' => __( 'Query', 'total' ),
						'dependency' => array( 'element' => 'custom_query', 'value' => array( 'false' ) ),
					),
					array(
						'type' => 'vcex_ofswitch',
						'std' => 'false',
						'heading' => __( 'Pagination', 'total' ),
						'param_name' => 'pagination',
						'group' => __( 'Query', 'total' ),
						'dependency' => array( 'element' => 'custom_query', 'value' => array( 'false' ) ),
					),
					array(
						'type' => 'vcex_ofswitch',
						'std' => 'false',
						'heading' => __( 'Load More Button', 'total' ),
						'param_name' => 'pagination_loadmore',
						'group' => __( 'Query', 'total' ),
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Limit By Post ID\'s', 'total' ),
						'param_name' => 'posts_in',
						'group' => __( 'Query', 'total' ),
						'description' => __( 'Seperate by a comma.', 'total' ),
						'dependency' => array( 'element' => 'custom_query', 'value' => array( 'false' ) ),
					),
					array(
						'type' => 'autocomplete',
						'heading' => __( 'Limit By Author', 'total' ),
						'param_name' => 'author_in',
						'settings' => array(
							'multiple' => true,
							'min_length' => 1,
							'groups' => false,
							'unique_values' => true,
							'display_inline' => true,
							'delay' => 0,
							'auto_focus' => true,
						),
						'group' => __( 'Query', 'total' ),
						'dependency' => array( 'element' => 'custom_query', 'value' => array( 'false' ) ),
					),
				)
			);
		}

	}
}
new VCEX_Post_Type_Archive_Shortcode;