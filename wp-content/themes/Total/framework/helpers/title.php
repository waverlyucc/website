<?php
/**
 * Returns the correct title to display for any post/page/archive
 *
 * @package Total WordPress Theme
 * @subpackage Framework
 * @version 4.6.1
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Returns current page title
 *
 * @since 1.0
 */
function wpex_title( $post_id = '' ) {

	// Default title is null
	$title = null;

	// Return singular title if post id is defined and don't apply filters
	// This is used for VC heading module
	if ( $post_id ) {
		$meta = get_post_meta( $post_id, 'wpex_post_title', true );
		return $meta ? $meta : single_post_title( '', false );
	}
	
	// Get post ID from global object
	if ( is_singular() ) {

		// Get post data
		$post_id = get_the_ID();
		$type    = get_post_type();

		// Single Pages
		if ( in_array( $type, array( 'page', 'attachment', 'wp_router_page' ) ) ) {
			$title = single_post_title( '', false );
		}

		// Single blog posts
		elseif ( 'post' == $type ) {
			$display = wpex_get_mod( 'blog_single_header', 'custom_text' );
			if ( 'custom_text' == $display ) {
				$title = wpex_get_translated_theme_mod( 'blog_single_header_custom_text' );
				$title = $title ? $title : __( 'Blog', 'total' );
			} elseif ( 'first_category' == $display ) {
				$title = wpex_get_first_term_name();
			} else {
				$title = single_post_title( '', false );
			}
		}

		// Templatera
		elseif ( 'templatera' == $type ) {
			$title = single_post_title( '', false );
		}

		// Other posts (custom types)
		else {
			$obj = get_post_type_object( $type );
			if ( is_object( $obj ) ) {
				$title = $obj->labels->name;
			}
		}
	
	// Homepage - display blog description if not a static page
	} elseif ( is_front_page() ) {
		
		if ( get_bloginfo( 'description' ) ) {
			$title = get_bloginfo( 'description' );
		} else {
			return __( 'Recent Posts', 'total' );
		}

	// Homepage posts page
	} elseif ( is_home() ) {

		$title = get_the_title( get_option( 'page_for_posts', true ) );
		$title = $title ? $title : __( 'Home', 'total' );

	}

	// Search => NEEDS to go before archives
	elseif ( is_search() ) {
		$title = __( 'Search results for:', 'total' ) .' &quot;'. esc_html( get_search_query( false ) ) .'&quot;';
	}
		
	// Archives
	elseif ( is_archive() ) {

		// Author
		if ( is_author() ) {
			if ( $author = get_queried_object() ) {
				$title = $author->display_name; // Fix for authors with 0 posts
			} else {
				$title = get_the_archive_title();
			}
		}

		// Post Type archive title
		elseif ( is_post_type_archive() ) {
			$title = post_type_archive_title( '', false );
		}

		// Daily archive title
		elseif ( is_day() ) {
			$title = sprintf( __( 'Daily Archives: %s', 'total' ), get_the_date() );
		}

		// Monthly archive title
		elseif ( is_month() ) {
			$title = sprintf( __( 'Monthly Archives: %s', 'total' ), get_the_date( 'F Y' ) );
		}

		// Yearly archive title
		elseif ( is_year() ) {
			$title = sprintf( __( 'Yearly Archives: %s', 'total' ), get_the_date( 'Y' ) );
		}

		// Categories/Tags/Other
		else {

			// Get term title
			$title = single_term_title( '', false );

		}

	} // End is archive check

	// 404 Page
	elseif ( is_404() ) {

		// Custom 404 page
		if ( $page_id = wpex_parse_obj_id( wpex_get_mod( 'error_page_content_id' ), 'page' ) ) {
			$title = get_the_title( $page_id );
		}

		// Default 404 page
		else {
			$title = wpex_get_translated_theme_mod( 'error_page_title' );
			$title = $title ? $title : __( '404: Page Not Found', 'total' );
		}

	}

	// Last check if title is empty
	if ( ! $title ) {
		$post_id = wpex_get_current_post_id();
		$title   = get_the_title( $post_id );
	}

	// Apply filters and return title
	return apply_filters( 'wpex_title', $title, $post_id );
	
}

/**
 * Overrides the title string when meta is defined
 * This function helps prevent issues with custom edits made to the wpex_title and wpex_page_header_title_args filters
 *
 * @since 4.0
 */
function wpex_page_header_title_meta_override( $args ) {
	$post_id = wpex_get_current_post_id();
	if ( $post_id && $meta = get_post_meta( $post_id, 'wpex_post_title', true ) ) {
		$args['string'] = $meta;
	}
	return $args;
}
add_filter( 'wpex_page_header_title_args', 'wpex_page_header_title_meta_override', 40 );