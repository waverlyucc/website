<?php
/**
 * Post Type Grid
 *
 * @package Total WordPress Theme
 * @subpackage VC Functions
 * @version 4.7.1
 */

if ( ! class_exists( 'VCEX_Post_Type_Grid_Shortcode' ) ) {

	class VCEX_Post_Type_Grid_Shortcode {

		/**
		 * Main constructor
		 *
		 * @since 3.5.0
		 */
		public function __construct() {
			
			// Add shortcode
			add_shortcode( 'vcex_post_type_grid', array( $this, 'output' ) );

			// Map to VC
			vc_lean_map( 'vcex_post_type_grid', array( $this, 'map' ) );

			// Admin filters
			if ( is_admin() ) {

				// Get autocomplete suggestion
				add_filter( 'vc_autocomplete_vcex_post_type_grid_tax_query_taxonomy_callback', 'vcex_suggest_taxonomies', 10, 1 );
				add_filter( 'vc_autocomplete_vcex_post_type_grid_filter_taxonomy_callback', 'vcex_suggest_taxonomies', 10, 1 );
				add_filter( 'vc_autocomplete_vcex_post_type_grid_tax_query_terms_callback', 'vcex_suggest_terms', 10, 1 );
				add_filter( 'vc_autocomplete_vcex_post_type_grid_author_in_callback', 'vcex_suggest_users', 10, 1 );
				add_filter( 'vc_autocomplete_vcex_post_type_grid_categories_taxonomy_callback', 'vcex_suggest_taxonomies', 10, 1 );
				add_filter( 'vc_autocomplete_vcex_post_type_grid_posts_in_callback', 'vc_include_field_search', 10, 1 );

				// Render autocomplete suggestions
				add_filter( 'vc_autocomplete_vcex_post_type_grid_filter_taxonomy_render', 'vcex_render_taxonomies', 10, 1 );
				add_filter( 'vc_autocomplete_vcex_post_type_grid_tax_query_taxonomy_render', 'vcex_render_taxonomies', 10, 1 );
				add_filter( 'vc_autocomplete_vcex_post_type_grid_categories_taxonomy_render', 'vcex_render_taxonomies', 10, 1 );
				add_filter( 'vc_autocomplete_vcex_post_type_grid_tax_query_terms_render', 'vcex_render_terms', 10, 1 );
				add_filter( 'vc_autocomplete_vcex_post_type_grid_author_in_render', 'vcex_render_users', 10, 1 );
				add_filter( 'vc_autocomplete_vcex_post_type_grid_posts_in_render', 'vc_include_field_render', 10, 1 );

				// Move content design elements into new entry CSS field
				add_filter( 'vc_edit_form_fields_attributes_vcex_post_type_grid', 'vcex_parse_deprecated_grid_entry_content_css' );

				// Set image height to full if crop/width are empty
				add_filter( 'vc_edit_form_fields_attributes_vcex_post_type_grid', 'vcex_parse_image_size' );
				
			}

		}

		/**
		 * Shortcode output => Get template file and display shortcode
		 *
		 * @since 3.5.0
		 */
		public function output( $atts, $content = null ) {
			ob_start();
			include( locate_template( 'vcex_templates/vcex_post_type_grid.php' ) );
			return ob_get_clean();
		}

		/**
		 * Map shortcode to VC
		 *
		 * @since 3.5.0
		 */
		public function map() {
			return array(
				'name' => __( 'Post Types Grid', 'total' ),
				'description' => __( 'Posts grid', 'total' ),
				'base' => 'vcex_post_type_grid',
				'category' => wpex_get_theme_branding(),
				'icon' => 'vcex-post-type-grid vcex-icon fa fa-files-o',
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
						'description' => __( 'Add additonal classes to the main element.', 'total' ),
						'param_name' => 'classes',
					),
					array(
						'type' => 'vcex_visibility',
						'heading' => __( 'Visibility', 'total' ),
						'param_name' => 'visibility',
					),
					vcex_vc_map_add_css_animation(),
					array(
						'type' => 'dropdown',
						'heading' => __( 'Grid Style', 'total' ),
						'param_name' => 'grid_style',
						'value' => array(
							__( 'Fit Columns', 'total' ) => 'fit_columns',
							__( 'Masonry', 'total' ) => 'masonry',
							__( 'No Margins', 'total' ) => 'no_margins',
						),
						'edit_field_class' => 'vc_col-sm-3 vc_column clear',
					),
					array(
						'type' => 'vcex_grid_columns',
						'heading' => __( 'Columns', 'total' ),
						'param_name' => 'columns',
						'std' => '3',
						'edit_field_class' => 'vc_col-sm-3 vc_column',
					),
					array(
						'type' => 'vcex_column_gaps',
						'heading' => __( 'Gap', 'total' ),
						'param_name' => 'columns_gap',
						'edit_field_class' => 'vc_col-sm-3 vc_column',
					),
					array(
						'type' => 'dropdown',
						'heading' => __( 'Responsive', 'total' ),
						'param_name' => 'columns_responsive',
						'value' => array(
							__( 'Yes', 'no' ) => 'true',
							__( 'No', 'no' ) => 'false',
						),
						'edit_field_class' => 'vc_col-sm-3 vc_column',
						'dependency' => array( 'element' => 'columns', 'value' => array( '2', '3', '4', '5', '6', '7', '8', '9', '10' ) ),
					),
					array(
						'type' => 'vcex_grid_columns_responsive',
						'heading' => __( 'Responsive Settings', 'total' ),
						'param_name' => 'columns_responsive_settings',
						'dependency' => array( 'element' => 'columns_responsive', 'value' => 'true' ),
					),
					array(
						'type' => 'dropdown',
						'heading' => __( '1 Column Style', 'total' ),
						'param_name' => 'single_column_style',
						'value' => array(
							__( 'Default', 'total') => '',
							__( 'Left Image And Right Content', 'total' ) => 'left_thumbs',
						),
						'dependency' => array( 'element' => 'columns', 'value' => '1' ),
					),
					array(
						'type' => 'vcex_ofswitch',
						'std' => 'false',
						'heading' => __( 'Equal Heights?', 'total' ),
						'param_name' => 'equal_heights_grid',
						'description' => __( 'Enable so the content area for each entry is the same height.', 'total' ),
					),
					array(
						'type' => 'vcex_select_buttons',
						'heading' => __( 'Post Link Target', 'total' ),
						'param_name' => 'url_target',
						'std' => 'self',
						'choices' => 'link_target',
					),
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
						'type' => 'posttypes',
						'heading' => __( 'Post types', 'total' ),
						'param_name' => 'post_types',
						'std' => 'post',
						'group' => __( 'Query', 'total' ),
						'admin_label' => true,
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
						'heading' => __( 'Pagination', 'total' ),
						'param_name' => 'pagination',
						'group' => __( 'Query', 'total' ),
						'dependency' => array( 'element' => 'custom_query', 'value' => array( 'false' ) ),
					),
					/*array(
						'type' => 'vcex_ofswitch',
						'std' => 'false',
						'heading' => __( 'Load More Button', 'total' ),
						'param_name' => 'pagination_loadmore',
						'group' => __( 'Query', 'total' ),
						'dependency' => array( 'element' => 'pagination', 'value' => array( 'true' ) ),
					),*/
					array(
						'type' => 'textfield',
						'heading' => __( 'Offset', 'total' ),
						'param_name' => 'offset',
						'group' => __( 'Query', 'total' ),
						'description' => __( 'Number of post to displace or pass over. Warning: Setting the offset parameter overrides/ignores the paged parameter and breaks pagination. The offset parameter is ignored when posts per page is set to -1.', 'total' ),
						'dependency' => array( 'element' => 'custom_query', 'value' => array( 'false' ) ),
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
						'type' => 'autocomplete',
						'heading' => __( 'Query Specific Posts', 'total' ),
						'param_name' => 'posts_in',
						'group' => __( 'Query', 'total' ),
						'settings' => array(
							'multiple' => true,
							'min_length' => 1,
							'groups' => false,
							'unique_values' => true,
							'display_inline' => true,
							'delay' => 0,
							'auto_focus' => true,
						),
						'description' => __( 'Start typing a post name to locate and add it.', 'total' ),
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
						'dependency' => array(
							'element' => 'tax_query',
							'value' => 'true',
						),
						'settings' => array(
							'multiple' => false,
							'min_length' => 1,
							'groups' => false,
							'display_inline' => true,
							'delay' => 0,
							'auto_focus' => true,
						),
						'group' => __( 'Query', 'total' ),
						'description' => __( 'If you do not see your taxonomy in the dropdown you can still enter the taxonomy name manually.', 'total' ),
						'dependency' => array( 'element' => 'custom_query', 'value' => array( 'false' ) ),
					),
					array(
						'type' => 'autocomplete',
						'heading' => __( 'Terms', 'total' ),
						'param_name' => 'tax_query_terms',
						'dependency' => array( 'element' => 'tax_query', 'value' => 'true' ),
						'settings' => array(
							'multiple' => true,
							'min_length' => 1,
							'groups' => true,
							'display_inline' => true,
							'delay' => 0,
							'auto_focus' => true,
						),
						'group' => __( 'Query', 'total' ),
						'description' => __( 'If you do not see your terms in the dropdown you can still enter the term slugs manually seperated by a space.', 'total' ),
						'dependency' => array( 'element' => 'custom_query', 'value' => array( 'false' ) ),
					),
					array(
						'type' => 'dropdown',
						'heading' => __( 'Order', 'total' ),
						'param_name' => 'order',
						'group' => __( 'Query', 'total' ),
						'value' => array(
							__( 'Default', 'total' ) => 'default',
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
						'dependency' => array( 'element' => 'orderby', 'value' => array( 'meta_value_num', 'meta_value' ) ),
					),
					// Filter
					array(
						'type' => 'vcex_ofswitch',
						'std' => 'false',
						'heading' => __( 'Enable', 'total' ),
						'param_name' => 'filter',
						'description' => __( 'If more then one post type is selected it will display a post type filter, otherwise it will display the categories for the current post type.', 'total' ),
						'group' => __( 'Filter', 'total' ),
					),
					array(
						'type' => 'vcex_button_styles',
						'heading' => __( 'Button Style', 'total' ),
						'param_name' => 'filter_button_style',
						'group' => __( 'Filter', 'total' ),
						'std' => 'minimal-border',
						'dependency' => array( 'element' => 'filter', 'value' => 'true' ),
					),
					array(
						'type' => 'vcex_button_colors',
						'heading' => __( 'Button Color', 'total' ),
						'param_name' => 'filter_button_color',
						'group' => __( 'Filter', 'total' ),
						'dependency' => array( 'element' => 'filter', 'value' => 'true' ),
					),
					array(
						'type' => 'vcex_select_buttons',
						'heading' => __( 'Filter What?', 'total' ),
						'param_name' => 'filter_type',
						'std' => 'post_types',
						'choices' => array(
							'post_types' => __( 'Post Types', 'total' ),
							'taxonomy' => __( 'Custom Taxonomy', 'total' ),
						),
						'group' => __( 'Filter', 'total' ),
						'dependency' => array( 'element' => 'filter', 'value' => 'true' ),
					),
					array(
						'type' => 'autocomplete',
						'heading' => __( 'Filter Taxonomy Name', 'total' ),
						'param_name' => 'filter_taxonomy',
						'dependency' => array( 'element' => 'filter_type', 'value' => array( 'taxonomy' ) ),
						'settings' => array(
							'multiple' => false,
							'min_length' => 1,
							'groups' => false,
							'unique_values' => true,
							'display_inline' => true,
							'delay' => 0,
							'auto_focus' => true,
						),
						'description' => __( 'Enter the taxonomy name for the filter links.', 'total' ),
						'group' => __( 'Filter', 'total' ),
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Custom Filter "All" Text', 'total' ),
						'param_name' => 'all_text',
						'group' => __( 'Filter', 'total' ),
						'dependency' => array( 'element' => 'filter', 'value' => 'true' ),
					),
					array(
						'type' => 'vcex_select_buttons',
						'heading' => __( 'Layout Mode', 'total' ),
						'param_name' => 'masonry_layout_mode',
						'std' => 'masonry',
						'choices' => 'masonry_layout_mode',
						'group' => __( 'Filter', 'total' ),
						'dependency' => array( 'element' => 'filter', 'value' => 'true' ),
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Custom Filter Speed', 'total' ),
						'param_name' => 'filter_speed',
						'description' => __( 'Default is 0.4 seconds. Enter 0.0 to disable.', 'total' ),
						'group' => __( 'Filter', 'total' ),
						'dependency' => array( 'element' => 'filter', 'value' => 'true' ),
					),
					array(
						'type' => 'vcex_ofswitch',
						'std' => 'no',
						'heading' => __( 'Center Filter Links', 'total' ),
						'param_name' => 'center_filter',
						'vcex' => array(
							'off' => 'no',
							'on' => 'yes',
						),
						'group' => __( 'Filter', 'total' ),
						'dependency' => array( 'element' => 'filter', 'value' => 'true' ),
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Font Size', 'total' ),
						'param_name' => 'filter_font_size',
						'group' => __( 'Filter', 'total' ),
						'dependency' => array( 'element' => 'filter', 'value' => 'true' ),
					),
					// Media
					array(
						'type' => 'vcex_ofswitch',
						'std' => 'true',
						'heading' => __( 'Enable', 'total' ),
						'param_name' => 'entry_media',
						'group' => __( 'Media', 'total' ),
					),
					array(
						'type' => 'vcex_ofswitch',
						'std' => 'true',
						'heading' => __( 'Display Featured Videos?', 'total' ),
						'param_name' => 'featured_video',
						'group' => __( 'Media', 'total' ),
						'dependency' => array( 'element' => 'entry_media', 'value' => 'true' ),
					),
					array(
						'type' => 'dropdown',
						'heading' => __( 'Image Links To', 'total' ),
						'param_name' => 'thumb_link',
						'value' => array(
							__( 'Post', 'total' ) => 'post',
							__( 'Lightbox', 'total' ) => 'lightbox',
							__( 'Post Gallery Lightbox', 'total' ) => 'lightbox_gallery',
							__( 'Nowhere', 'total' ) => 'nowhere',
						),
						'group' => __( 'Media', 'total' ),
						'dependency' => array( 'element' => 'entry_media', 'value' => 'true' ),
					),
					array(
						'type' => 'vcex_image_sizes',
						'heading' => __( 'Image Size', 'total' ),
						'param_name' => 'img_size',
						'group' => __( 'Media', 'total' ),
						'dependency' => array( 'element' => 'entry_media', 'value' => 'true' ),
					),
					array(
						'type' => 'vcex_image_crop_locations',
						'heading' => __( 'Image Crop Location', 'total' ),
						'param_name' => 'img_crop',
						'std' => 'center-center',
						'dependency' => array( 'element' => 'img_size', 'value' => 'wpex_custom' ),
						'group' => __( 'Media', 'total' ),
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Image Crop Width', 'total' ),
						'param_name' => 'img_width',
						'dependency' => array( 'element' => 'img_size', 'value' => 'wpex_custom' ),
						'description' => __( 'Enter a width in pixels.', 'total' ),
						'group' => __( 'Media', 'total' ),
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Image Crop Height', 'total' ),
						'param_name' => 'img_height',
						'dependency' => array( 'element' => 'img_size', 'value' => 'wpex_custom' ),
						'description' => __( 'Enter a height in pixels. Leave empty to disable vertical cropping and keep image proportions.', 'total' ),
						'group' => __( 'Media', 'total' ),
					),
					array(
						'type' => 'vcex_overlay',
						'heading' => __( 'Image Overlay', 'total' ),
						'param_name' => 'overlay_style',
						'group' => __( 'Media', 'total' ),
						'dependency' => array( 'element' => 'entry_media', 'value' => 'true' ),
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Overlay Button Text', 'total' ),
						'param_name' => 'overlay_button_text',
						'group' => __( 'Media', 'total' ),
						'dependency' => array( 'element' => 'overlay_style', 'value' => 'hover-button' ),
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Overlay Excerpt Length', 'total' ),
						'param_name' => 'overlay_excerpt_length',
						'value' => '15',
						'group' => __( 'Media', 'total' ),
						'dependency' => array( 'element' => 'overlay_style', 'value' => 'title-excerpt-hover' ),
					),
					array(
						'type' => 'vcex_image_hovers',
						'heading' => __( 'Image Hover', 'total' ),
						'param_name' => 'img_hover_style',
						'group' => __( 'Media', 'total' ),
						'dependency' => array( 'element' => 'entry_media', 'value' => 'true' ),
					),
					array(
						'type' => 'vcex_image_filters',
						'heading' => __( 'Image Filter', 'total' ),
						'param_name' => 'img_filter',
						'group' => __( 'Media', 'total' ),
						'dependency' => array( 'element' => 'entry_media', 'value' => 'true' ),
					),
					// Title
					array(
						'type' => 'vcex_ofswitch',
						'std' => 'true',
						'heading' => __( 'Enable', 'total' ),
						'param_name' => 'title',
						'group' => __( 'Title', 'total' ),
					),
					array(
						'type' => 'vcex_select_buttons',
						'heading' => __( 'Links To', 'total' ),
						'param_name' => 'title_link',
						'std' => 'post',
						'choices' => array(
							'post' => __( 'Post', 'total' ),
							'nowhere' => __( 'Nowhere', 'total' ),
						),
						'group' => __( 'Title', 'total' ),
						'dependency' => array( 'element' => 'title', 'value' => 'true' ),
					),
					array(
						'type' => 'vcex_select_buttons',
						'heading' => __( 'Tag', 'total' ),
						'param_name' => 'title_tag',
						'group' => __( 'Title', 'total' ),
						'std' => 'h2',
						'choices' => 'html_tag',
						'dependency' => array( 'element' => 'title', 'value' => 'true' ),
					),
					array(
						'type' => 'colorpicker',
						'heading' => __( 'Color', 'total' ),
						'param_name' => 'content_heading_color',
						'group' => __( 'Title', 'total' ),
						'dependency' => array( 'element' => 'title', 'value' => 'true' ),
					),
					array(
						'type' => 'textfield',
						'heading' => __(  'Font Size', 'total' ),
						'param_name' => 'content_heading_size',
						'group' => __( 'Title', 'total' ),
						'dependency' => array( 'element' => 'title', 'value' => 'true' ),
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Line Height', 'total' ),
						'param_name' => 'content_heading_line_height',
						'group' => __( 'Title', 'total' ),
						'dependency' => array( 'element' => 'title', 'value' => 'true' ),
					),
					array(
						'type' => 'vcex_trbl',
						'heading' => __( 'Margin', 'total' ),
						'param_name' => 'content_heading_margin',
						'group' => __( 'Title', 'total' ),
						'dependency' => array( 'element' => 'title', 'value' => 'true' ),
					),
					array(
						'type' => 'vcex_font_weight',
						'heading' => __( 'Font Weight', 'total' ),
						'param_name' => 'content_heading_weight',
						'group' => __( 'Title', 'total' ),
						'dependency' => array( 'element' => 'title', 'value' => 'true' ),
					),
					array(
						'type' => 'vcex_text_transforms',
						'heading' => __( 'Text Transform', 'total' ),
						'param_name' => 'content_heading_transform',
						'group' => __( 'Title', 'total' ),
						'dependency' => array( 'element' => 'title', 'value' => 'true' ),
					),
					// Date
					array(
						'type' => 'vcex_ofswitch',
						'std' => 'true',
						'heading' => __( 'Enable', 'total' ),
						'param_name' => 'date',
						'group' => __( 'Date', 'total' ),
					),
					array(
						'type' => 'colorpicker',
						'heading' => __( 'Color', 'total' ),
						'param_name' => 'date_color',
						'group' => __( 'Date', 'total' ),
						'dependency' => array( 'element' => 'date', 'value' => 'true' ),
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Font Size', 'total' ),
						'param_name' => 'date_font_size',
						'group' => __( 'Date', 'total' ),
						'dependency' => array( 'element' => 'date', 'value' => 'true' ),
					),
					// Categories
					array(
						'type' => 'vcex_ofswitch',
						'std' => 'false',
						'heading' => __( 'Enable', 'total' ),
						'param_name' => 'show_categories',
						'group' => __( 'Categories', 'total' ),
					),
					array(
						'type' => 'autocomplete',
						'heading' => __( 'Taxonomy', 'total' ),
						'param_name' => 'categories_taxonomy',
						'group' => __( 'Categories', 'total' ),
						'dependency' => array( 'element' => 'show_categories', 'value' => 'true' ),
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
						'type' => 'vcex_ofswitch',
						'std' => 'false',
						'heading' => __( 'Show Only The First Category', 'total' ),
						'param_name' => 'show_first_category_only',
						'group' => __( 'Categories', 'total' ),
						'dependency' => array( 'element' => 'show_categories', 'value' => 'true' ),
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Font Size', 'total' ),
						'param_name' => 'categories_font_size',
						'group' => __( 'Categories', 'total' ),
						'dependency' => array( 'element' => 'show_categories', 'value' => 'true' ),
					),
					array(
						'type' => 'vcex_trbl',
						'heading' => __( 'Margin', 'total' ),
						'param_name' => 'categories_margin',
						'group' => __( 'Categories', 'total' ),
						'dependency' => array( 'element' => 'show_categories', 'value' => 'true' ),
					),
					array(
						'type' => 'colorpicker',
						'heading' => __( 'Color', 'total' ),
						'param_name' => 'categories_color',
						'group' => __( 'Categories', 'total' ),
						'dependency' => array( 'element' => 'show_categories', 'value' => 'true' ),
					),
					// Excerpt
					array(
						'type' => 'vcex_ofswitch',
						'std' => 'true',
						'heading' => __( 'Enable', 'total' ),
						'param_name' => 'excerpt',
						'group' => __( 'Excerpt', 'total' ),
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Length', 'total' ),
						'param_name' => 'excerpt_length',
						'group' => __( 'Excerpt', 'total' ),
						'value' => '20',
						'description' => __( 'Enter how many words to display for the excerpt. To display the full post content enter "-1". To display the full post content up to the "more" tag enter "9999".', 'total' ),
						'dependency' => array( 'element' => 'excerpt', 'value' => 'true' ),
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Font Size', 'total' ),
						'param_name' => 'content_font_size',
						'group' => __( 'Excerpt', 'total' ),
						'dependency' => array( 'element' => 'excerpt', 'value' => 'true' ),
					),
					array(
						'type' => 'colorpicker',
						'heading' => __( 'Color', 'total' ),
						'param_name' => 'content_color',
						'group' => __( 'Excerpt', 'total' ),
						'dependency' => array( 'element' => 'excerpt', 'value' => 'true' ),
					),
					// Readmore
					array(
						'type' => 'vcex_ofswitch',
						'std' => 'true',
						'heading' => __( 'Enable', 'total' ),
						'param_name' => 'read_more',
						'group' => __( 'Button', 'total' ),
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Custom Text', 'total' ),
						'param_name' => 'read_more_text',
						'group' => __( 'Button', 'total' ),
						'dependency' => array( 'element' => 'read_more', 'value' => 'true' ),
					),
					array(
						'type' => 'vcex_button_styles',
						'heading' => __( 'Style', 'total' ),
						'param_name' => 'readmore_style',
						'group' => __( 'Button', 'total' ),
						'dependency' => array( 'element' => 'read_more', 'value' => 'true' ),
					),
					array(
						'type' => 'vcex_button_colors',
						'heading' => __( 'Color', 'total' ),
						'param_name' => 'readmore_style_color',
						'group' => __( 'Button', 'total' ),
						'dependency' => array( 'element' => 'read_more', 'value' => 'true' ),
					),
					array(
						'type' => 'vcex_ofswitch',
						'std' => 'false',
						'heading' => __( 'Arrow', 'total' ),
						'param_name' => 'readmore_rarr',
						'group' => __( 'Button', 'total' ),
						'dependency' => array( 'element' => 'read_more', 'value' => 'true' ),
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Font Size', 'total' ),
						'param_name' => 'readmore_size',
						'group' => __( 'Button', 'total' ),
						'dependency' => array( 'element' => 'read_more', 'value' => 'true' ),
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Border Radius', 'total' ),
						'param_name' => 'readmore_border_radius',
						'group' => __( 'Button', 'total' ),
						'dependency' => array( 'element' => 'read_more', 'value' => 'true' ),
					),
					array(
						'type' => 'vcex_trbl',
						'heading' => __( 'Padding', 'total' ),
						'param_name' => 'readmore_padding',
						'group' => __( 'Button', 'total' ),
						'dependency' => array( 'element' => 'read_more', 'value' => 'true' ),
					),
					array(
						'type' => 'vcex_trbl',
						'heading' => __( 'Margin', 'total' ),
						'param_name' => 'readmore_margin',
						'group' => __( 'Button', 'total' ),
						'dependency' => array( 'element' => 'read_more', 'value' => 'true' ),
					),
					array(
						'type' => 'colorpicker',
						'heading' => __( 'Background', 'total' ),
						'param_name' => 'readmore_background',
						'group' => __( 'Button', 'total' ),
						'dependency' => array( 'element' => 'read_more', 'value' => 'true' ),
					),
					array(
						'type' => 'colorpicker',
						'heading' => __( 'Color', 'total' ),
						'param_name' => 'readmore_color',
						'group' => __( 'Button', 'total' ),
						'dependency' => array( 'element' => 'read_more', 'value' => 'true' ),
					),
					array(
						'type' => 'colorpicker',
						'heading' => __( 'Background: Hover', 'total' ),
						'param_name' => 'readmore_hover_background',
						'group' => __( 'Button', 'total' ),
						'dependency' => array( 'element' => 'read_more', 'value' => 'true' ),
					),
					array(
						'type' => 'colorpicker',
						'heading' => __( 'Color: Hover', 'total' ),
						'param_name' => 'readmore_hover_color',
						'group' => __( 'Button', 'total' ),
						'dependency' => array( 'element' => 'read_more', 'value' => 'true' ),
					),
					// Content CSS
					array(
						'type' => 'css_editor',
						'heading' => __( 'Content CSS', 'total' ),
						'param_name' => 'content_css',
						'group' => __( 'Content CSS', 'total' ),
					),
					array(
						'type' => 'vcex_text_alignments',
						'heading' => __( 'Content Alignment', 'total' ),
						'param_name' => 'content_alignment',
						'std' => '',
						'group' => __( 'Content CSS', 'total' ),
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Content Opacity', 'total' ),
						'param_name' => 'content_opacity',
						'description' => __( 'Enter a value between "0" and "1".', 'total' ),
						'group' => __( 'Content CSS', 'total' ),
					),
					// Entry CSS
					array(
						'type' => 'css_editor',
						'heading' => __( 'Entry CSS', 'total' ),
						'param_name' => 'entry_css',
						'group' => __( 'Entry CSS', 'total' ),
					),
					// Hidden fields
					array( 'type' => 'hidden', 'param_name' => 'content_background' ),
					array( 'type' => 'hidden', 'param_name' => 'content_border' ),
					array( 'type' => 'hidden', 'param_name' => 'content_margin' ),
					array( 'type' => 'hidden', 'param_name' => 'content_padding' ),
				)
			);
		}

	}
}
new VCEX_Post_Type_Grid_Shortcode;