<?php
/**
 * Visual Composer Post Meta
 *
 * @package Total WordPress Theme
 * @subpackage VC Functions
 * @version 4.3
 *
 * @todo Convert into single class
 */

/**
 * Create shortcode for Post Grid
 *
 * @since 3.5.0
 */
function vcex_post_meta_gitem_shortcode( $atts ) {
	return '{{ vcex_post_meta:' . http_build_query( (array) $atts ) . ' }}';
}
add_shortcode( 'vcex_gitem_post_meta', 'vcex_post_meta_gitem_shortcode' );

/**
 * Map Shortcode to grid items
 *
 * @since 3.5.0
 */
if ( ! function_exists( 'vcex_gitem_post_meta_add_grid_shortcodes' ) ) {
	function vcex_gitem_post_meta_add_grid_shortcodes( $shortcodes ) {
		$shortcodes['vcex_gitem_post_meta'] = array(
			'name'        => __( 'Post Meta', 'total' ),
			'base'        => 'vcex_gitem_post_meta',
			'icon'        => 'vcex-gitem-post-meta vcex-icon fa fa-list-alt',
			'category'    => wpex_get_theme_branding(),
			'description' => __( 'Author, date, comments...', 'total' ),
			'post_type'   => Vc_Grid_Item_Editor::postType(),
			'params'      => array(
				array(
					'type' => 'textfield',
					'heading' => __( 'Post ID', 'total' ),
					'param_name' => 'post_id',
					'description' => __( 'Leave empty to use current post or post in loop.', 'total' ),
				),
				array(
					'type' => 'textfield',
					'heading' => __( 'Font Size', 'total' ),
					'param_name' => 'font_size',
				),
				array(
					'type' => 'colorpicker',
					'heading' => __( 'Color', 'total' ),
					'param_name' => 'color',
				),
				array(
					'type' => 'vcex_ofswitch',
					'heading' => __( 'Show Date?', 'total' ),
					'param_name' => 'date',
					'std' => 'true',
				),
				array(
					'type' => 'vcex_ofswitch',
					'heading' => __( 'Show Author?', 'total' ),
					'param_name' => 'author',
					'std' => 'true',
				),
				array(
					'type' => 'vcex_ofswitch',
					'heading' => __( 'Show Comments Count?', 'total' ),
					'param_name' => 'comments',
					'std' => 'true',
				),
				array(
					'type' => 'css_editor',
					'heading' => __( 'CSS', 'total' ),
					'param_name' => 'css',
					'group' => __( 'CSS', 'total' ),
				),
			)
		);
		return $shortcodes;
	}
}
add_filter( 'vc_grid_item_shortcodes', 'vcex_gitem_post_meta_add_grid_shortcodes' );

/**
 * Add data to the vcex_gitem_post_meta shortcode
 *
 * @since 3.5.0
 */
function vc_gitem_template_attribute_vcex_post_meta( $value, $data ) {

	// Extract data
	extract( array_merge( array(
		'output' => '',
		'post'   => null,
		'data'   => '',
	), $data ) );

	$atts = array();
	parse_str( $data, $atts );

	$atts = vc_map_get_attributes( 'vcex_gitem_post_meta', $atts );

	extract( $atts );

	// Get post id
	$post_id = ! empty( $post_id ) ? $post_id : $post->ID;

	// Return if no post ID
	if ( ! $post_id ) {
		return;
	}

	// Classes
	$classes = 'meta vcex-gitem-post-meta vcex-clr';
	if ( $color ) {
		$classes .= ' wpex-child-inherit-color';
	}
	if ( $css ) {
		$classes .= ' '. vc_shortcode_custom_css_class( $css );
	}

	// Inline CSS
	$inline_style = vcex_inline_style( array(
		'font_size' => $font_size,
		'color'     => $color,
	) );

	// Generate output
	$output .= '<ul class="'. esc_attr( $classes ) .'"'. $inline_style .'>';
		
		// Date
		if ( 'true' == $date ) {

			$output .= '<li class="meta-date"><span class="fa fa-clock-o"></span><time class="updated" datetime="'. esc_attr( get_the_date( 'Y-m-d' ) ) .'"'. wpex_get_schema_markup( 'publish_date' ) .'>'. get_the_date() .'</time></li>';

		}
		
		// Author
		if ( 'true' == $author ) {

			$output .= '<li class="meta-author"><span class="fa fa-user"></span><span class="vcard author"'. wpex_get_schema_markup( 'author_name' ).'><span class="fn">'. get_the_author_posts_link().'</span></span></li>';

		}
		
		// Comment
		if ( 'true' == $comments ) {

			$comment_number = get_comments_number();
			if ( $comment_number == 0 ) {
				$comment_output = __( '0 Comments', 'total' );
			} elseif ( $comment_number > 1 ) {
				$comment_output = $comment_number .' '. __( 'Comments', 'total' );
			} else {
				$comment_output = __( '1 Comment',  'total' );
			}
			$output .= '<li class="meta-comments comment-scroll"><span class="fa fa-comment-o"></span>'. $comment_output .'</li>';

		}

	$output .= '</ul>';

	// Return output
	return $output;

}
add_filter( 'vc_gitem_template_attribute_vcex_post_meta', 'vc_gitem_template_attribute_vcex_post_meta', 10, 2 );