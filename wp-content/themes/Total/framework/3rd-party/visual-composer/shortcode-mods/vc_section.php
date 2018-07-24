<?php
/**
 * Visual Composer Section Configuration
 *
 * @package Total WordPress Theme
 * @subpackage Visual Composer
 * @version 4.5.4
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Start Class
if ( ! class_exists( 'VCEX_VC_Section_Config' ) ) {
	
	class VCEX_VC_Section_Config {

		/**
		 * Main constructor
		 *
		 * @since 4.0
		 */
		public function __construct() {

			// Modify core params
			add_action( 'wpex_vc_modify_params', array( 'VCEX_VC_Section_Config', 'modify_params' ) );
			
			// Add/remove params
			add_action( 'wpex_vc_add_params', array( 'VCEX_VC_Section_Config', 'add_params' ) );

			// Edit fields when opening editor window
			add_filter( 'vc_edit_form_fields_attributes_vc_section', array( 'VCEX_VC_Section_Config', 'edit_form_fields' ) );
			
			// Parse attributes
			add_filter( 'shortcode_atts_vc_section', array( 'VCEX_VC_Section_Config', 'parse_attributes' ), 99 );

			// Add custom attributes to row
			add_filter( 'wpex_vc_section_wrap_atts', array( 'VCEX_VC_Section_Config', 'wrap_attributes' ), 10, 2 );
			
			// Filter classes
			add_filter( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, array( 'VCEX_VC_Section_Config', 'shortcode_classes' ), 10, 3 );

			// Alter output
			add_filter( 'vc_shortcode_output', array( 'VCEX_VC_Section_Config', 'custom_output' ), 10, 3 );

			// Hooks
			add_filter( 'wpex_hook_vc_section_bottom', array( 'VCEX_VC_Section_Config', 'hook_bottom' ), 50, 2 );

		}

		/**
		 * Modify core params
		 *
		 * @since 4.0
		 */
		public static function modify_params( $params ) {

			$params['vc_section'] = array(

				'el_id' => array(
					'weight' => 99
				),

				'el_class' => array(
					'weight' => 99,
				),

				'css_animation' => array(
					'weight' => 99,
				),

				'full_width' => array(
					'weight' => 99,
				),

				// Move video parallax setting
				'video_bg_parallax' => array(
					'group' => __( 'Video', 'total' ),
					'dependency' => array(
						'element' => 'video_bg',
						'value' => 'youtube',
					),
				),

				// Move youtube url
				'video_bg_url' => array(
					'group' => __( 'Video', 'total' ),
					'dependency' => array(
						'element' => 'video_bg',
						'value' => 'youtube',
					),
				),

				// Move video parallax speed
				'parallax_speed_video' => array(
					'group' => __( 'Video', 'total' ),
					'dependency' => array(
						'element' => 'video_bg',
						'value' => 'youtube',
					),
				),

				// Move design options
				'css' => array(
					'group' => __( 'Design Options', 'total' ),
					'weight' => -1,
				),

			);

			// Move parallax settings
			if ( vcex_supports_advanced_parallax() ) {

				$params['vc_section']['parallax'] = array(
					'group' => __( 'Parallax', 'total' ),
				);

				$params['vc_section']['parallax_image'] = array(
					'group' => __( 'Parallax', 'total' ),
				);

				$params['vc_section']['parallax_speed_bg'] = array(
					'group' => __( 'Parallax', 'total' ),
					'dependency' => array(
						'element' => 'parallax',
						'value' => array( 'content-moving', 'content-moving-fade' ),
					),
				);

			}

			return $params;

		}

		/**
		 * Adds new params for the VC Rows
		 *
		 * @since 4.0
		 */
		public static function add_params( $params ) {

			// User access
			$add_params[] = array(
				'type' => 'dropdown',
				'heading' => __( 'User Access', 'total' ),
				'param_name' => 'vcex_user_access',
				'weight' => 99,
				'value' => array(
					__( 'All', 'total' ) => '',
					__( 'Logged in', 'total' ) => 'logged_in',
					__( 'Logged out', 'total' ) => 'logged_out',
					__( 'Custom', 'total' ) => 'custom',
				)
			);
			$add_params[] = array(
				'type' => 'textfield',
				'heading' => __( 'Custom User Access', 'total' ),
				'param_name' => 'vcex_user_access_callback',
				'description' => __( 'Enter your callback function name here.', 'total' ),
				'weight' => 99,
				'dependency' => array( 'element' => 'vcex_user_access', 'value' => 'custom' ),
			);

			// Video
			$add_params[] = array(
				'type' => 'dropdown',
				'heading' => __( 'Video Background', 'total' ),
				'param_name' => 'video_bg',
				'std' => '',
				'value' => array(
					__( 'None', 'total' ) => '',
					__( 'Youtube', 'total' ) => 'youtube',
					__( 'Self Hosted', 'total' ) => 'self_hosted',
				),
				'group' => __( 'Video', 'total' ),
				'description' => __( 'Video backgrounds do not display on mobile because mobile devices do not allow the auto playing of videos it is recommended to apply a standard background image or color as a fallback for mobile devices.', 'total' ),
			);
			$add_params[] = array(
				'type' => 'textfield',
				'heading' => __( 'Video URL: MP4 URL', 'total' ),
				'param_name' => 'video_bg_mp4',
				'dependency' => array(
					'element' => 'video_bg',
					'value' => 'self_hosted',
				),
				'group' => __( 'Video', 'total' ),
			);
			$add_params[] = array(
				'type' => 'textfield',
				'heading' => __( 'Video URL: WEBM URL', 'total' ),
				'param_name' => 'video_bg_webm',
				'dependency' => array(
					'element' => 'video_bg',
					'value' => 'self_hosted',
				),
				'group' => __( 'Video', 'total' ),
			);
			$add_params[] = array(
				'type' => 'textfield',
				'heading' => __( 'Video URL: OGV URL', 'total' ),
				'param_name' => 'video_bg_ogv',
				'dependency' => array(
					'element' => 'video_bg',
					'value' => 'self_hosted',
				),
				'group' => __( 'Video', 'total' ),
			);

			$add_params['visibility'] = array(
				'type' => 'vcex_visibility',
				'heading' => __( 'Visibility', 'total' ),
				'param_name' => 'visibility',
				'weight' => 99,
				//'admin_label' => true,
			);

			// Overlay
			$add_params[] = array(
				'type' => 'dropdown',
				'heading' => __( 'Background Overlay', 'total' ),
				'param_name' => 'wpex_bg_overlay',
				'group' => __( 'Overlay', 'total' ),
				'value' => array(
					__( 'None', 'total' ) => '',
					__( 'Color', 'total' ) => 'color',
					__( 'Dark', 'total' ) => 'dark',
					__( 'Dotted', 'total' ) => 'dotted',
					__( 'Diagonal Lines', 'total' ) => 'dashed',
					__( 'Custom', 'total' ) => 'custom',
				),
			);

			$add_params[] = array(
				'type' => 'attach_image',
				'heading' => __( 'Custom Overlay Pattern', 'total' ),
				'param_name' => 'wpex_bg_overlay_image',
				'group' => __( 'Overlay', 'total' ),
				'dependency' => array( 'element' => 'wpex_bg_overlay', 'value' => array( 'custom' ) ),
			);

			$add_params[] = array(
				'type' => 'colorpicker',
				'heading' => __( 'Background Overlay Color', 'total' ),
				'param_name' => 'wpex_bg_overlay_color',
				'group' => __( 'Overlay', 'total' ),
				'dependency' => array( 'element' => 'wpex_bg_overlay', 'value' => array( 'color', 'dark', 'dotted', 'dashed', 'custom' ) ),
			);

			$add_params[] = array(
				'type' => 'textfield',
				'heading' => __( 'Background Overlay Opacity', 'total' ),
				'param_name' => 'wpex_bg_overlay_opacity',
				'dependency' => array( 'element' => 'wpex_bg_overlay', 'value' => array( 'color', 'dark', 'dotted', 'dashed', 'custom' ) ),
				'group' => __( 'Overlay', 'total' ),
				'description' => __( 'Default', 'total' ) . ': 0.65',
			);

			$add_params[] = array(
				'type' => 'textfield',
				'heading' => __( 'Background Position', 'total' ),
				'param_name' => 'wpex_bg_position',
				'group' => __( 'Design Options', 'total' ),
				'description' => __( 'Enter your custom background position. Example: "center center"', 'total' ),
				'weight' => -2,
			);

			// @todo add local scroll id to sections
			$add_params[] = array(
				'type' => 'textfield',
				'heading' => __( 'Local Scroll ID', 'total' ),
				'param_name' => 'local_scroll_id',
				'description' => __( 'Unique identifier for local scrolling links.', 'total' ),
				'weight' => 99,
			);

			$params['vc_section'] = $add_params;

			return $params;

		}

		/**
		 * Tweaks row attributes on edit
		 *
		 * @since 4.3
		 */
		public static function edit_form_fields( $atts ) {

			// Parse video background
			if ( ! empty( $atts['video_bg'] ) && 'yes' == $atts['video_bg'] ) {
				$atts['video_bg'] = 'youtube';
			}

			// Return attributes
			return $atts;

		}

		/**
		 * Parse VC section attributes on front-end
		 *
		 * @since 4.0
		 */
		public static function parse_attributes( $atts ) {

			if ( ! empty( $atts['full_width'] ) && 'boxed' == wpex_site_layout() ) {
				$atts['full_width'] = '';
				$atts['full_width_boxed_layout'] = 'true';
			}

			if ( ! empty( $atts['video_bg'] ) && 'self_hosted' == $atts['video_bg'] ) {
				$atts['video_bg'] = false;
				$atts['wpex_self_hosted_video_bg'] = true;
			}

			return $atts;

		}

		/**
		 * Add custom attributes to the row wrapper
		 *
		 * @since 4.0
		 */
		public static function wrap_attributes( $wrapper_attributes, $atts ) {
			$inline_style = '';

			// Local scroll ID
			if ( ! empty( $atts['local_scroll_id'] ) ) {
				$wrapper_attributes[] = 'data-ls_id="#' . esc_attr( $atts['local_scroll_id'] ) . '"';
			}

			// Background position
			if ( ! empty( $atts['wpex_bg_position'] ) ) {
				$inline_style .= 'background-position:'. $atts['wpex_bg_position'] .' !important;';
			}

			// Add inline style to wrapper attributes
			if ( $inline_style ) {
				$wrapper_attributes[] = 'style="'. $inline_style .'"';
			}

			// Return attributes
			return $wrapper_attributes;

		}

		/**
		 * Tweak shortcode classes
		 *
		 * @since 4.0
		 */
		public static function shortcode_classes( $class_string, $tag, $atts ) {

			// Edits only for columns
			if ( 'vc_section' != $tag ) {
				return $class_string;
			}

			$add_classes = array();

			// Tweak some classes
			if ( strpos( $class_string, 'vc_section-has-fill' ) !== false ) {
				$class_string = str_replace( 'vc_section-has-fill', '', $class_string );
				$add_classes['wpex-vc_section-has-fill'] = 'wpex-vc_section-has-fill';
			}

			// Visibility
			if ( ! empty( $atts['visibility'] ) ) {
				$add_classes[] = $atts['visibility'];
			}

			// Full width
			if ( ! empty( $atts['full_width'] ) ) {
				$add_classes[] = 'wpex-vc-row-stretched';
			}
			
			if ( ! empty( $atts['full_width_boxed_layout'] ) ) {
				$add_classes[] = 'wpex-vc-section-boxed-layout-stretched';
			}

			// Video bg
			if ( ! empty( $atts['wpex_self_hosted_video_bg'] ) ) {
				$add_classes[] = 'wpex-has-video-bg';
				$add_classes['wpex-vc_section-has-fill'] = 'wpex-vc_section-has-fill';
			}

			// Overlay
			if ( ! empty( $atts['wpex_bg_overlay'] ) && 'none' != $atts['wpex_bg_overlay'] ) {
				$add_classes[] = 'wpex-has-overlay';
			}

			// Remove negative margins
			if ( empty( $atts['full_width'] ) && isset( $add_classes['wpex-vc_section-has-fill'] ) ) {
				$add_classes[] = 'wpex-vc-reset-negative-margin';
			}

			// Add the classes
			if ( $add_classes ) {
				$add_classes = implode( ' ', array_filter( $add_classes, 'trim' ) );
				$class_string .= ' '. $add_classes;
			}

			// Return class string
			return $class_string;

		}

		/**
		 * Custom HTML output
		 *
		 * @since 4.1
		 */
		public static function custom_output( $output, $obj, $atts ) {

			// Tweaks neeed for vc_row only
			if ( 'vc_section' != $obj->settings( 'base' ) ) {
				return $output;
			}

			// Check user settings
			if ( isset( $atts['vcex_user_access'] )
				&& ! is_admin()
				&& ! vc_is_inline()
			) {
				$callback = ( 'custom' == $atts['vcex_user_access'] && isset( $atts['vcex_user_access_callback'] ) ) ? $atts['vcex_user_access_callback'] : '';
				if ( ! wpex_user_can_access( $atts['vcex_user_access'], $callback ) ) {
					return;
				}
			}

			// Add local scroll ID
			if ( ! empty( $atts['local_scroll_id'] ) ) {
				$needle = 'class="vc_section';
				$pos = strpos( $output, $needle );
				if ( $pos !== false ) {
					$local_scroll_id = 'data-ls_id="#' . esc_attr( $atts['local_scroll_id'] ) . '"';
					$output = substr_replace( $output, $local_scroll_id .' class="vc_section', $pos, strlen( $needle ) );
				}
			}

			// Return output
			return $output;

		}

		/**
		 * Insert custom elements to the Section content at the bottom
		 *
		 * Priority: 1
		 *
		 * @since 4.0
		 */
		public static function hook_bottom( $content, $atts ) {
			$content .= vcex_row_overlay( $atts );
			//$content .= vcex_parallax_bg( $atts ); // advanced parallax not added to section yet
			$content .= vcex_row_video( $atts );
			return $content;

		}

	}

}
new VCEX_VC_Section_Config();