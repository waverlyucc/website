<?php
/**
 * Visual Composer Post Video
 *
 * @package Total WordPress Theme
 * @subpackage VC Functions
 * @version 4.3
 *
 * @todo Turn into single class
 */

/**
 * Create shortcode for Post Grid
 *
 * @since 3.2.2
 */
function vcex_post_video_gitem_shortcode( $atts ) {
   return '{{ vcex_post_video:' . http_build_query( (array) $atts ) . ' }}';
}
add_shortcode( 'vcex_gitem_post_video', 'vcex_post_video_gitem_shortcode' );

/**
 * Map Shortcode to grid items
 *
 * @since 3.2.2
 */
if ( ! function_exists( 'vcex_gitem_post_video_add_grid_shortcodes' ) ) {
	function vcex_gitem_post_video_add_grid_shortcodes( $shortcodes ) {
		$shortcodes['vcex_gitem_post_video'] = array(
			'name'        => __( 'Post Video', 'total' ),
			'base'        => 'vcex_gitem_post_video',
			'icon'        => 'vcex-gitem-post-video vcex-icon fa fa-film',
			'category'    => wpex_get_theme_branding(),
			'description' => __( 'Featured post video.', 'total' ),
			'post_type'   => Vc_Grid_Item_Editor::postType(),
			'params'      => array(
				array(
					'type' => 'textfield',
					'heading' => __( 'Post ID', 'total' ),
					'param_name' => 'post_id',
					'description' => __( 'Leave empty to use current post or post in loop.', 'total' ),
				),
				array(
					'type' => 'css_editor',
					'heading' => __( 'CSS', 'total' ),
					'param_name' => 'css',
				),
			)
		);
		return $shortcodes;
	}
}
add_filter( 'vc_grid_item_shortcodes', 'vcex_gitem_post_video_add_grid_shortcodes' );

/**
 * Add data to the vcex_gitem_post_video shortcode
 *
 * @since 3.2.2
 */
function vc_gitem_template_attribute_vcex_post_video( $value, $data ) {

	// Extract data
	extract( array_merge( array(
		'output' => '',
		'post'   => null,
		'data'   => '',
	), $data ) );

	// Get and extract shortcode attributes
	$atts = array();
	parse_str( $data, $atts );

	$atts = vc_map_get_attributes( 'vcex_gitem_post_video', $atts );

	extract( $atts );

	// Get post id
	$post_id = ! empty( $post_id ) ? $post_id : $post->ID;

	// Get video
	if ( 'vc_grid_item' == get_post_type( $post->ID ) ) {
		$video = 'https://www.youtube.com/watch?v=2JyW4yAyTl0';
	} else {
		$video = wpex_get_post_video( $post_id );
	}

	// Parse video into HTML format
	$video = $video ? wpex_get_post_video_html( $video ) : '';

	// Display video
	if ( $video ) {

		// Custom CSS
		if ( $css ) {
			$css = ' ' . vc_shortcode_custom_css_class( $css );
		}

		// Generate output
		$output .= '<div class="vcex-gitem-post-video wpex-clr' . $css . '">';

			$output .= $video;
			
		$output .= '</div>';

		// Return output
		return $output;

	}
}
add_filter( 'vc_gitem_template_attribute_vcex_post_video', 'vc_gitem_template_attribute_vcex_post_video', 10, 2 );