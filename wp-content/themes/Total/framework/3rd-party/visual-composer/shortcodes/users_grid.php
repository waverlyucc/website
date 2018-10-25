<?php
/**
 * Visual Composer Users Grid
 *
 * @package Total WordPress Theme
 * @subpackage VC Functions
 * @version 4.6.5
 */

if ( ! class_exists( 'VCEX_Users_Grid_Shortcode' ) ) {

	class VCEX_Users_Grid_Shortcode {

		/**
		 * Main constructor
		 *
		 * @since 4.0
		 */
		public function __construct() {
			add_shortcode( 'vcex_users_grid', array( $this, 'output' ) );
			vc_lean_map( 'vcex_users_grid', array( $this, 'map' ) );
			add_filter( 'vc_autocomplete_vcex_users_grid_role__in_callback', 'vcex_suggest_user_roles', 10, 1 );
			add_filter( 'vc_autocomplete_vcex_users_grid_role__in_render', 'vcex_render_user_roles', 10, 1 );
			add_filter( 'vc_edit_form_fields_attributes_vcex_users_grid', array( $this, 'edit_fields' ), 10 );
		}

		/**
		 * Shortcode output => Get template file and display shortcode
		 *
		 * @since 4.0
		 */
		public function output( $atts, $content = null ) {
			ob_start();
			include( locate_template( 'vcex_templates/vcex_users_grid.php' ) );
			return ob_get_clean();
		}

		/**
		 * Map shortcode to VC
		 *
		 * @since 4.0
		 */
		public function map() {
			return array(
				'name' => __( 'Users Grid', 'total' ),
				'description' => __( 'Displays a grid of users', 'total' ),
				'base' => 'vcex_users_grid',
				'category' => wpex_get_theme_branding(),
				'icon' => 'vcex-users-grid vcex-icon fa fa-users',
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
						'heading' => __( 'Custom Classes', 'total' ),
						'param_name' => 'classes',
						'admin_label' => true,
					),
					vcex_vc_map_add_css_animation(),
					array(
						'type' => 'vcex_visibility',
						'heading' => __( 'Visibility', 'total' ),
						'param_name' => 'visibility',
					),
					array(
						'type' => 'dropdown',
						'heading' => __( 'Grid Style', 'total' ),
						'param_name' => 'grid_style',
						'std' => 'fit_columns',
						'value' => array(
							__( 'Fit Columns', 'total' ) => 'fit_columns',
							__( 'Masonry', 'total' ) => 'masonry',
						),
						'edit_field_class' => 'vc_col-sm-3 vc_column clear',
					),
					array(
						'type' => 'vcex_grid_columns',
						'heading' => __( 'Columns', 'total' ),
						'param_name' => 'columns',
						'std' => '5',
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
						'value' => array( __( 'Yes', 'total' ) => 'true', __( 'No', 'false' ) => 'false' ),
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
						'std' => 'author_page',
						'heading' => __( 'On click action', 'total' ),
						'param_name' => 'onclick',
						'value' => array(
							__( 'Open author page', 'total' ) => 'author_page',
							__( 'Open user website', 'total' ) => 'user_website',
							__( 'Disable', 'total' ) => 'disable',
						),
					),
					// Query
					array(
						'type' => 'autocomplete',
						'heading' => __( 'User Roles', 'total' ),
						'param_name' => 'role__in',
						'admin_label' => true,
						'std' => '',
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
					),
					array(
						'type' => 'dropdown',
						'heading' => __( 'Order', 'total' ),
						'param_name' => 'order',
						'group' => __( 'Query', 'total' ),
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
							__( 'Display Name', 'total' ) => 'display_name',
							__( 'Nicename', 'total' ) => 'nicename',
							__( 'Login', 'total' ) => 'login',
							__( 'Registered', 'total' ) => 'registered',
							'ID' => 'ID',
							__( 'Email', 'total' ) => 'email',
						),
						'group' => __( 'Query', 'total' ),
					),
					// Image
					array(
						'type' => 'vcex_ofswitch',
						'std' => 'true',
						'heading' => __( 'Enable', 'total' ),
						'param_name' => 'avatar',
						'group' => __( 'Avatar', 'total' ),
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Size', 'total' ),
						'param_name' => 'avatar_size',
						'std' => '150',
						'group' => __( 'Avatar', 'total' ),
						'dependency' => array( 'element' => 'avatar', 'value' => 'true' ),
						'description' => __( 'Size of Gravatar to return (max is 512 for standard Gravatars)', 'total' ),
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Meta Field', 'total' ),
						'param_name' => 'avatar_meta_field',
						'std' => '',
						'group' => __( 'Avatar', 'total' ),
						'dependency' => array( 'element' => 'avatar', 'value' => 'true' ),
						'description' => __( 'Enter the "ID" of a custom user meta field to pull the avatar from there instead of searching for the user\'s Gravatar', 'total' ),
					),
					array(
						'type' => 'vcex_image_hovers',
						'heading' => __( 'CSS3 Image Hover', 'total' ),
						'param_name' => 'avatar_hover_style',
						'group' => __( 'Avatar', 'total' ),
					),
					// Name
					array(
						'type' => 'vcex_ofswitch',
						'std' => 'true',
						'heading' => __( 'Enable', 'total' ),
						'param_name' => 'name',
						'group' => __( 'Name', 'total' ),
					),
					array(
						'type' => 'vcex_select_buttons',
						'heading' => __( 'Tag', 'total' ),
						'param_name' => 'name_heading_tag',
						'choices' => 'html_tag',
						'std' => 'div',
						'group' => __( 'Name', 'total' ),
					),
					array(
						'type' => 'colorpicker',
						'heading' => __( 'Color', 'total' ),
						'param_name' => 'name_color',
						'group' => __( 'Name', 'total' ),
						'std' => '',
						'dependency' => array( 'element' => 'name', 'value' => 'true' ),
					),
					array(
						'type'  => 'vcex_font_family_select',
						'heading' => __( 'Font Family', 'total' ),
						'param_name' => 'name_font_family',
						'group' => __( 'Name', 'total' ),
						'dependency' => array( 'element' => 'name', 'value' => 'true' ),
					),
					array(
						'type' => 'vcex_font_weight',
						'heading' => __( 'Font Weight', 'total' ),
						'param_name' => 'name_font_weight',
						'group' => __( 'Name', 'total' ),
						'dependency' => array( 'element' => 'name', 'value' => 'true' ),
					),
					array(
						'type'  => 'textfield',
						'heading' => __( 'Font Size', 'total' ),
						'param_name' => 'name_font_size',
						'group' => __( 'Name', 'total' ),
						'dependency' => array( 'element' => 'name', 'value' => 'true' ),
					),
					array(
						'type' => 'vcex_text_transforms',
						'heading' => __( 'Text Transform', 'total' ),
						'param_name' => 'name_text_transform',
						'group' => __( 'Name', 'total' ),
						'dependency' => array( 'element' => 'name', 'value' => 'true' ),
					),
					array(
						'type'  => 'textfield',
						'heading' => __( 'Bottom Margin', 'total' ),
						'param_name' => 'name_margin_bottom',
						'group' => __( 'Name', 'total' ),
						'dependency' => array( 'element' => 'name', 'value' => 'true' ),
					),
					// Description
					array(
						'type' => 'vcex_ofswitch',
						'std' => 'true',
						'heading' => __( 'Enable', 'total' ),
						'param_name' => 'description',
						'group' => __( 'Description', 'total' ),
					),
					array(
						'type' => 'colorpicker',
						'heading' => __( 'Color', 'total' ),
						'param_name' => 'description_color',
						'group' => __( 'Description', 'total' ),
						'std' => '',
						'dependency' => array( 'element' => 'description', 'value' => 'true' ),
					),
					array(
						'type'  => 'vcex_font_family_select',
						'heading' => __( 'Font Family', 'total' ),
						'param_name' => 'description_font_family',
						'group' => __( 'Description', 'total' ),
						'dependency' => array( 'element' => 'description', 'value' => 'true' ),
					),
					array(
						'type' => 'vcex_font_weight',
						'heading' => __( 'Font Weight', 'total' ),
						'param_name' => 'description_font_weight',
						'group' => __( 'Description', 'total' ),
						'dependency' => array( 'element' => 'description', 'value' => 'true' ),
					),
					array(
						'type'  => 'textfield',
						'heading' => __( 'Font Size', 'total' ),
						'param_name' => 'description_font_size',
						'group' => __( 'Description', 'total' ),
						'dependency' => array( 'element' => 'description', 'value' => 'true' ),
					),
					// Social
					array(
						'type' => 'vcex_ofswitch',
						'std' => 'true',
						'heading' => __( 'Enable', 'total' ),
						'param_name' => 'social_links',
						'group' => __( 'Social', 'total' ),
					),
					array(
						'type' => 'vcex_social_button_styles',
						'heading' => __( 'Style', 'total' ),
						'param_name' => 'social_links_style',
						'std' => wpex_get_mod( 'staff_social_default_style', 'minimal-round' ),
						'group' => __( 'Social', 'total' ),
						'dependency' => array( 'element' => 'social_links', 'value' => 'true' ),
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Font Size', 'total' ),
						'param_name' => 'social_links_size',
						'group' => __( 'Social', 'total' ),
						'dependency' => array( 'element' => 'social_links', 'value' => 'true' ),
					),
					array(
						'type' => 'vcex_trbl',
						'heading' => __( 'Padding', 'total' ),
						'param_name' => 'social_links_padding',
						'group' => __( 'Social', 'total' ),
						'dependency' => array( 'element' => 'social_links', 'value' => 'true' ),
					),
					array(
						'type' => 'css_editor',
						'heading' => __( 'CSS', 'total' ),
						'param_name' => 'entry_css',
						'group' => __( 'Entry CSS', 'total' ),
					),
					// Deprecated
					array( 'type' => 'hidden', 'param_name' => 'link_to_author_page' ),
				)
			);
		}

		/**
		 * Edit form fields
		 *
		 * @since 4.5.1
		 */
		public function edit_fields( $atts ) {

			if ( isset( $atts['link_to_author_page'] ) ) {
				if ( 'false' == $atts['link_to_author_page'] ) {
					$atts['onclick'] = 'disable';
					unset( $atts['link_to_author_page'] );
				}
			}

			return $atts;

		}

	}
}
new VCEX_Users_Grid_Shortcode;