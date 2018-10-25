<?php
/**
 * Visual Composer Post Meta
 *
 * @package Total WordPress Theme
 * @subpackage VC Templates
 * @version 4.5.4.2
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Helps speed up rendering in backend of VC
if ( is_admin() && ! wp_doing_ajax() ) {
	return;
}

// Required VC functions
if ( ! function_exists( 'vc_map_get_attributes' ) || ! function_exists( 'vc_param_group_parse_atts' ) ) {
	vcex_function_needed_notice();
	return;
}

// Get and extract shortcode attributes
extract( vc_map_get_attributes( 'vcex_post_meta', $atts ) );

$sections = $sections ? (array) vc_param_group_parse_atts( $sections ) : '';

if ( ! $sections ) {
	return;
}

global $post;

if ( ! $post ) {
	return;
}

$output = '';

// Use some fallbaks when previewing with Templatera
$is_templatera = ( 'templatera' == $post->post_type ) ? true : false;

// Classes
$classes = 'meta vcex-post-meta vcex-clr';
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

	// Sections
	foreach ( $sections as $section ) {

		$type          = isset( $section['type'] ) ? $section['type'] : '';
		$icon_type     = isset( $section['icon_type'] ) ? $section['icon_type'] : '';
		$icon          = isset( $section['icon'] ) ? $section['icon'] : '';
		$icon_typicons = isset( $section['icon_typicons'] ) ? $section['icon_typicons'] : '';
		$icon_class    = vcex_get_icon_class( $section, 'icon' );

		if ( $icon_class && 'fontawesome' != $icon_type ) {
			vcex_enqueue_icon_font( $icon_type );
		}

		// Date
		if ( 'date' == $type ) {

			$output .= '<li class="meta-date">';

				if ( $icon_class ) {
					$output .= '<span class="' . $icon_class . ' meta-icon" aria-hidden="true"></span>';
				}

				$output .= '<time class="updated" datetime="' . esc_attr( get_the_date( 'Y-m-d' ) ) . '"' . wpex_get_schema_markup( 'publish_date' ) . '>' . get_the_date( '', $post->ID ) . '</time>';

			$output .= '</li>';

		}
		
		// Author
		if ( 'author' == $type ) {

			$output .= '<li class="meta-author">';

				if ( $icon_class ) {
					$output .= '<span class="' . $icon_class . ' meta-icon" aria-hidden="true"></span>';
				}

				$output .= '<span class="vcard author"' . wpex_get_schema_markup( 'author_name' ) . '><span class="fn"><a href="' . esc_url( get_author_posts_url( $post->post_author ) ) . '">' . get_the_author_meta( 'nickname', $post->post_author ) . '</a></span></span>';

			$output .= '</li>';

		}
		
		// Comment
		if ( 'comments' == $type ) {

			$output .= '<li class="meta-comments comment-scroll">';

				if ( $icon_class ) {
					$output .= '<span class="' . $icon_class . ' meta-icon" aria-hidden="true"></span>';
				}

				$comment_number = get_comments_number();
				if ( $comment_number == 0 ) {
					$output .= __( '0 Comments', 'total' );
				} elseif ( $comment_number > 1 ) {
					$output .= $comment_number .' '. __( 'Comments', 'total' );
				} else {
					$output .= __( '1 Comment',  'total' );
				}

			$output .= '</li>';

		}

		// Terms
		if ( 'post_terms' == $type ) {

			$taxonomy = isset( $section['taxonomy'] ) ? $section['taxonomy'] : '';
			$get_terms    = '';

			if ( $is_templatera ) {

				$output .= '<li class="meta-post-terms clr">';

					if ( $icon_class ) {
						$output .= '<span class="' . $icon_class . ' meta-icon" aria-hidden="true"></span>';
					}

					$output .= '<a href="#">' . __( 'Sample Item', 'total' ) . '</a>';

				$output .= '</li>';

			} elseif ( $taxonomy && $get_terms = wpex_list_post_terms( $taxonomy, true, false ) ) {

				$output .= '<li class="meta-post-terms clr">';

					if ( $icon_class ) {
						$output .= '<span class="' . $icon_class . ' meta-icon" aria-hidden="true"></span>';
					}

					$output .= $get_terms;

				$output .= '</li>';


			}

		}


	}
	
$output .= '</ul>';

// Echo output
echo $output;