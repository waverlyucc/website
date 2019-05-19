<?php
/**
 * Custom pagination functions
 *
 * @package Total WordPress Theme
 * @subpackage Framework
 * @version 4.8
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Numbered Pagination for archives
 *
 * @since 4.8
 * @todo replace for blog and main archives
 */
function wpex_get_pagination() {

	// Arrow style
	$arrow_style = wpex_get_mod( 'pagination_arrow' );
	$arrow_style = $arrow_style ? esc_attr( $arrow_style ) : 'angle';

	// Arrows with RTL support
	$prev_arrow = is_rtl() ? 'ticon ticon-' . $arrow_style . '-right' : 'ticon ticon-' . $arrow_style . '-left';
	$next_arrow = is_rtl() ? 'ticon ticon-' . $arrow_style . '-left' : 'ticon ticon-' . $arrow_style . '-right';

	return get_the_posts_pagination( array(
		'type'               => 'list',
		'prev_text'          => '<span class="' . $prev_arrow . '" aria-hidden="true"></span><span class="screen-reader-text">' . esc_html__( 'Previous', 'total' ) . '</span>',
		'next_text'          => '<span class="' . $next_arrow . '" aria-hidden="true"></span><span class="screen-reader-text">' . esc_html__( 'Next', 'total' ) . '</span>',
		'before_page_number' => '<span class="screen-reader-text">' . esc_html__( 'Page', 'total' ) . ' </span>',
	) );
}


/**
 * Numbered Pagination
 *
 * @since 1.0.0
 */
if ( ! function_exists( 'wpex_pagination' ) ) { // MUST KEEP CHECK SO USERS CAN OVERRIDE

	function wpex_pagination( $query = '', $echo = true ) {

		// Arrow style
		$arrow_style = wpex_get_mod( 'pagination_arrow' );
		$arrow_style = $arrow_style ? esc_attr( $arrow_style ) : 'angle';

		// Arrows with RTL support
		$prev_arrow = is_rtl() ? 'ticon ticon-' . $arrow_style . '-right' : 'ticon ticon-' . $arrow_style . '-left';
		$next_arrow = is_rtl() ? 'ticon ticon-' . $arrow_style . '-left' : 'ticon ticon-' . $arrow_style . '-right';

		// Get global $query
		if ( ! $query ) {
			global $wp_query;
			$query = $wp_query;
		}

		// Set vars
		$total  = $query->max_num_pages;
		$big    = 999999999;

		// Display pagination
		if ( $total > 1 ) {

			// Get current page
			if ( $current_page = get_query_var( 'paged' ) ) {
				$current_page = $current_page;
			} elseif ( $current_page = get_query_var( 'page' ) ) {
				$current_page = $current_page;
			} else {
				$current_page = 1;
			}

			// Get permalink structure
			if ( get_option( 'permalink_structure' ) ) {
				if ( is_page() ) {
					$format = 'page/%#%/';
				} else {
					$format = '/%#%/';
				}
			} else {
				$format = '&paged=%#%';
			}

			// Previous text
			$prev_text = '<span class="' . $prev_arrow . '" aria-hidden="true"></span><span class="screen-reader-text">' . esc_html__( 'Previous', 'total' ) . '</span>';

			// Next text
			$next_text = '<span class="' . $next_arrow . '" aria-hidden="true"></span><span class="screen-reader-text">' . esc_html__( 'Next', 'total' ) . '</span>';

			// Define and add filter to pagination args
			$args = apply_filters( 'wpex_pagination_args', array(
				'base'               => str_replace( $big, '%#%', html_entity_decode( get_pagenum_link( $big ) ) ),
				'format'             => $format,
				'current'            => max( 1, $current_page ),
				'total'              => $total,
				'mid_size'           => 3,
				'type'               => 'list',
				'prev_text'          => $prev_text,
				'next_text'          => $next_text,
				'before_page_number' => '<span class="screen-reader-text">' . esc_html__( 'Page', 'total' ) . ' </span>',
			) );

			// Alignment classes based on Customizer settings
			$align = ' text' . wpex_get_mod( 'pagination_align', 'left' );

			// Output pagination
			if ( $echo ) {
				echo '<div class="wpex-pagination wpex-clr' . $align . '">' . paginate_links( $args ) . '</div>';
			} else {
				return '<div class="wpex-pagination wpex-clr' . $align . '">' . paginate_links( $args ) . '</div>';
			}

		}

	}

}

/**
 * Next/Prev Pagination
 *
 * @since 1.0.0
 */
if ( ! function_exists( 'wpex_pagejump' ) ) {

	function wpex_pagejump( $pages = '', $range = 4, $echo = true ) {
		$output     = '';
		$showitems  = ( $range * 2 ) + 1;
		global $paged;
		if ( empty( $paged ) ) $paged = 1;

		if ( $pages == '' ) {
			global $wp_query;
			$pages = $wp_query->max_num_pages;
			if ( ! $pages) {
				$pages = 1;
			}
		}
		if ( 1 != $pages ) {

			$output .= '<div class="page-jump wpex-clr">';
				$output .= '<div class="alignleft newer-posts">';
					$output .= get_previous_posts_link( '&larr; '. esc_html__( 'Newer Posts', 'total' ) );
				$output .= '</div>';
				$output .= '<div class="alignright older-posts">';
					$output .= get_next_posts_link( esc_html__( 'Older Posts', 'total' ) .' &rarr;' );
				$output .= '</div>';
			$output .= '</div>';

			if ( $echo ) {
				echo $output;
			} else {
				return $output;
			}

		}
	}

}

/**
 * Infinite Scroll Pagination
 *
 * @since 1.0.0
 */
if ( ! function_exists( 'wpex_infinite_scroll' ) ) {

	function wpex_infinite_scroll( $type = 'standard' ) {

		// Make sure lightbox CSS is loaded to prevent bugs when items are loaded that must load this CSS
		wpex_enqueue_ilightbox_skin();

		// Load infinite scroll script
		wp_enqueue_script(
			'wpex-infinite-scroll',
			wpex_asset_url( 'js/dynamic/wpex-infinite-scroll.min.js' ),
			array( 'jquery' ),
			WPEX_THEME_VERSION,
			true
		);

		// Loading text
		$loading_text = wpex_get_mod( 'loadmore_loading_text', esc_html__( 'Loading...', 'total' ) );

		// Loading img
		$gif = apply_filters( 'wpex_loadmore_gif', includes_url( 'images/spinner-2x.gif' ) );

		// Localize loading text
		$is_params = apply_filters( 'wpex_infinite_scroll_args', array(
			'loading' => array(
				'msgText'      => '<div class="wpex-infscr-spinner"><img src="' . esc_url( $gif ) . '" class="wpex-spinner" alt="' . esc_html( $loading_text ) . '" /><span class="ticon ticon-spinner"></span></div>',
				'msg'          => null,
				'finishedMsg'  => null,
			),
			'blankImg'     => esc_url( wpex_asset_url( 'images/blank.gif' ) ),
			'navSelector'  => 'div.infinite-scroll-nav',
			'nextSelector' => 'div.infinite-scroll-nav div.older-posts a',
			'itemSelector' => '.blog-entry',
		), 'blog' );
		wp_localize_script( 'wpex-infinite-scroll', 'wpexInfiniteScroll', $is_params );

		if ( wpex_get_mod( 'blog_entry_audio_output', false ) || apply_filters( 'wpex_infinite_scroll_enqueue_mediaelement', false ) ) {
			wp_enqueue_style( 'wp-mediaelement' );
			wp_enqueue_script( 'wp-mediaelement' );
		}

		// Output pagination HTML
		$output = '';

		$output .= '<div class="infinite-scroll-nav clr">';

			$output .= '<div class="alignleft newer-posts">';

				$output .= get_previous_posts_link('&larr; '. esc_html__( 'Newer Posts', 'total' ) );

			$output .= '</div>';

			$output .= '<div class="alignright older-posts">';

				$output .= get_next_posts_link( esc_html__( 'Older Posts', 'total' ) .' &rarr;');

			$output .= '</div>';

		$output .= '</div>';

		echo $output;

	}

}

/**
 * Ajax load more
 *
 * @since 4.4.1
 */
function wpex_loadmore( $args = array() ) {

	wp_enqueue_script( 'wpex-loadmore' );
	wpex_enqueue_ilightbox_skin(); // make sure lightbox is loaded for any lightbox items

	$defaults = array(
		'nonce'    => wp_create_nonce( 'wpex-load-more-nonce' ),
		'query'    => '',
		'maxPages' => '',
		'perPage'  => '',
		'columns'  => '',
	);

	$args = wp_parse_args( $args, $defaults );

	$text         = wpex_get_mod( 'loadmore_text', esc_html__( 'Load More', 'total' ), true );
	$loading_text = wpex_get_mod( 'loadmore_loading_text', esc_html__( 'Loading...', 'total' ) );
	$gif          = apply_filters( 'wpex_loadmore_gif', includes_url( 'images/spinner-2x.gif' ) );

	if ( wpex_get_mod( 'blog_entry_audio_output', false ) || apply_filters( 'wpex_loadmore_enqueue_mediaelement', false ) ) {
		wp_enqueue_style( 'wp-mediaelement' );
		wp_enqueue_script( 'wp-mediaelement' );
	}

	echo '<div class="wpex-load-more-wrap"><a href="#" class="wpex-load-more theme-button expanded" data-loadmore="' . htmlentities( json_encode( $args ) ) . '"><span class="theme-button-inner">' . esc_html( $text ) . '</span></a><img src="' . esc_url( $gif ) . '" class="wpex-spinner" alt="' . esc_html( $loading_text ) . '" /><span class="ticon ticon-spinner"></span></div>';

}

/**
 * Ajax load more
 *
 * @since 4.4.1
 */
function wpex_ajax_load_more() {

	check_ajax_referer( 'wpex-load-more-nonce', 'nonce' );

	$loadmore = isset( $_POST['loadmore'] ) ? $_POST['loadmore'] : '';

	$query_args = isset( $loadmore['query'] ) ? $loadmore['query'] : array();

	if ( ! empty( $query_args['s'] ) ) {
		$post_type = 'post'; // search results when set to blog style
	} else {
		$query_args['post_type'] = ! empty( $query_args['post_type'] ) ? $query_args['post_type'] : 'post';
		$post_type = $query_args['post_type'];
	}

	$query_args['post_status'] = 'publish';
	$query_args['paged']       = isset( $_POST['page'] ) ? $_POST['page'] : 2;

	//$context = isset( $_POST['context'] ) ? $_POST['context'] : 'archive';
	global $wpex_count;
	$wpex_count = isset( $loadmore['count'] ) ? $loadmore['count'] : 0;
	$columns    = isset( $loadmore['columns'] ) ? $loadmore['columns'] : 0;

	if ( ! empty( $loadmore['is_home'] ) && $cats = wpex_blog_exclude_categories() ) {
		$query_args['category__not_in'] = $cats;
	}

	ob_start();

	$loop = new WP_Query( $query_args );

	if ( $loop->have_posts() ) :

		while ( $loop->have_posts() ): $loop->the_post();

			$wpex_count++;

			if ( 'post' == $post_type ) {

				wpex_get_template_part( 'blog_entry' );

			}

			if ( $columns == $wpex_count ) {
				$wpex_count=0;
			}

		endwhile;

	endif;

	wp_reset_postdata();

	$data = ob_get_clean();

	wp_send_json_success( $data );

	wp_die();

}
add_action( 'wp_ajax_wpex_ajax_load_more', 'wpex_ajax_load_more' );
add_action( 'wp_ajax_nopriv_wpex_ajax_load_more', 'wpex_ajax_load_more' );

/**
 * Blog Pagination
 * Execute the correct pagination function based on the theme settings
 *
 * @since 1.0.0
 */
if ( ! function_exists( 'wpex_blog_pagination' ) ) {

	function wpex_blog_pagination( $args = array() ) {

		// Admin Options
		$blog_style       = wpex_get_mod( 'blog_style', 'large-image' );
		$pagination_style = wpex_get_mod( 'blog_pagination_style', 'standard' );

		// Category based settings
		if ( is_category() ) {

			// Get taxonomy meta
			$term       = get_query_var( 'cat' );
			$term_data  = get_option( 'category_'. $term );
			$term_style = $term_pagination = '';

			if ( isset( $term_data['wpex_term_style'] ) ) {
				$term_style = '' != $term_data['wpex_term_style'] ? $term_data['wpex_term_style'] .'' : $term_style;
			}

			if ( isset( $term_data['wpex_term_pagination'] ) ) {
				$term_pagination = '' != $term_data['wpex_term_pagination'] ? $term_data['wpex_term_pagination'] .'' : '';
			}

			if ( $term_style ) {
				$blog_style = $term_style .'-entry-style';
			}

			if ( $term_pagination ) {
				$pagination_style = $term_pagination;
			}

		}

		// Set default $type for infnite scroll
		if ( 'grid-entry-style' == $blog_style ) {
			$infinite_type = 'standard-grid';
		} else {
			$infinite_type = 'standard';
		}

		// Execute the correct pagination function
		if ( 'infinite_scroll' == $pagination_style ) {
			wpex_infinite_scroll( $infinite_type );
		} elseif ( 'load_more' == $pagination_style ) {
			wpex_loadmore( $args );
		} elseif ( 'next_prev' == $pagination_style ) {
			wpex_pagejump();
		} else {
			wpex_pagination();
		}

	}

}