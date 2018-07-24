<?php
/**
 * Helper functions for the blog
 *
 * @package Total WordPress Theme
 * @subpackage Framework
 * @version 4.5.4
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Exclude categories from the blog
 * This function runs on pre_get_posts
 *
 * @since 1.0.0
 */
if ( ! function_exists( 'wpex_blog_exclude_categories' ) ) {
	function wpex_blog_exclude_categories( $deprecated = true ) {
		$cats = wpex_get_mod( 'blog_cats_exclude' );
		if ( $cats && ! is_array( $cats ) ) {
			$cats = explode( ',', $cats ); // Convert to array
		}
		return $cats;
	}
}

/**
 * Returns the correct blog style
 *
 * @since 1.5.3
 */
function wpex_blog_style() {

	// Get default style from Customizer
	$style = wpex_get_mod( 'blog_style' );

	// Check custom category style
	if ( is_category() ) {
		$term      = get_query_var( 'cat' );
		$term_data = get_option( "category_$term" );
		if ( $term_data && ! empty ( $term_data['wpex_term_style'] ) ) {
			$style = $term_data['wpex_term_style'] .'-entry-style';
		}
	}

	// Sanitize
	$style = $style ? $style : 'large-image-entry-style';

	// Apply filters for child theming
	$style = apply_filters( 'wpex_blog_style', $style );

	// Return style
	return $style;

}

/**
 * Returns the grid style
 *
 * @since 1.5.3
 */
function wpex_blog_grid_style() {

	// Get default style from Customizer
	$style = wpex_get_mod( 'blog_grid_style' );

	// Check custom category style
	if ( is_category() ) {
		$term       = get_query_var( 'cat' );
		$term_data  = get_option( "category_$term" );
		if ( $term_data && ! empty ( $term_data['wpex_term_grid_style'] ) ) {
			$style = $term_data['wpex_term_grid_style'];
		}
	}

	// Sanitize
	$style = $style ? $style : 'fit-rows';

	// Apply filters for child theming
	$style = apply_filters( 'wpex_blog_grid_style', $style );

	// Return style
	return $style;

}

/**
 * Checks if it's a fit-rows style grid
 *
 * @since 1.5.3
 */
function wpex_blog_fit_rows() {

	// Return false by default
	$return = false;

	// Get current blog style
	if ( 'grid-entry-style' == wpex_blog_style() ) {
		$return = true;
	} else {
		$return = false;
	}

	// Apply filters for child theming
	$return = apply_filters( 'wpex_blog_fit_rows', $return );

	// Return bool
	return $return;

}

/**
 * Returns the correct pagination style
 *
 * @since 1.5.3
 */
function wpex_blog_pagination_style() {

	// Get default style from Customizer
	$style = wpex_get_mod( 'blog_pagination_style' );

	// Check custom category style
	if ( is_category() ) {
		$term       = get_query_var( 'cat' );
		$term_data  = get_option( "category_$term" );
		if ( $term_data && ! empty ( $term_data['wpex_term_pagination'] ) ) {
			$style = $term_data['wpex_term_pagination'];
		}
	}

	// Apply filters for child theming
	$style = apply_filters( 'wpex_blog_pagination_style', $style );

	// Return style
	return $style;
}

/**
 * Returns correct style for the blog entry based on theme options or category options
 *
 * @since 1.5.3
 */
function wpex_blog_entry_style() {

	// Get default style from Customizer
	$style = wpex_get_mod( 'blog_style' );

	// Category checks
	$category = null;
	if ( ! empty( $_POST['loadmore'] ) && isset( $_POST['loadmore']['category'] ) ) {
		$category = $_POST['loadmore']['category'];
	} elseif ( is_category() ) {
		$category = get_query_var( 'cat' );
	}

	if ( $category ) {
		$term_data = get_option( 'category_' . $category );
		if ( ! empty ( $term_data['wpex_term_style'] ) ) {
			$style = $term_data['wpex_term_style'] .'-entry-style';
		}
	}

	// Sanitize
	$style = $style ? $style : 'large-image-entry-style';

	// Apply filters for child theming
	$style = apply_filters( 'wpex_blog_entry_style', $style );

	// Return style
	return $style;

}

/**
 * Checks if the blog entries should have equal heights
 *
 * @since   2.0.0
 * @return  bool
 */
function wpex_blog_entry_equal_heights() {
	if ( ! wpex_get_mod( 'blog_archive_grid_equal_heights', false ) ) {
		return false;
	}
	$entry_style = wpex_blog_entry_style();
	if ( 'grid-entry-style' == $entry_style && 'masonry' != $entry_style ) {
		return true;
	}
}

/**
 * Returns correct columns for the blog entries
 *
 * @since 1.5.3
 */
function wpex_blog_entry_columns( $entry_style = null ) {
	
	$entry_style = $entry_style ? $entry_style : wpex_blog_entry_style();

	if ( 'grid-entry-style' != $entry_style ) {
		return 1; // always 1 unless it's a grid
	}

	// Get columns from customizer setting
	$columns = wpex_get_mod( 'blog_grid_columns' );

	// Category checks
	$category = null;
	if ( ! empty( $_POST['loadmore'] ) && isset( $_POST['loadmore']['category'] ) ) {
		$category = $_POST['loadmore']['category'];
	} elseif ( is_category() ) {
		$category = get_query_var( 'cat' );
	}

	// Get custom columns per category basis
	if ( $category ) {
		$term_data  = get_option( 'category_' . $category );
		if ( ! empty ( $term_data['wpex_term_grid_cols'] ) ) {
			$columns = $term_data['wpex_term_grid_cols'];
		}
	}

	// Sanitize
	$columns = $columns ? $columns : '2';

	// Apply filters for child theming
	$columns = apply_filters( 'wpex_blog_entry_columns', $columns );

	// Return columns
	return $columns;

}


/**
 * Returns correct blog entry classes
 *
 * @since 1.1.6
 */
function wpex_blog_entry_classes() {

	// Define classes array
	$classes = array();

	// Entry Style
	$entry_style = wpex_blog_entry_style();

	// Core classes
	$classes[] = 'blog-entry';
	$classes[] = 'clr';

	// Masonry classes
	if ( 'masonry' == wpex_blog_grid_style() ) {
		$classes[] = 'isotope-entry';
	}

	// Add columns for grid style entries
	if ( 'grid-entry-style' == $entry_style ) {
		$classes[] = 'col';
		$classes[] = wpex_grid_class( wpex_blog_entry_columns( $entry_style ) );
	}

	// No Featured Image Class, don't add if oembed or self hosted meta are defined
	if ( ! has_post_thumbnail()
		&& '' == get_post_meta( get_the_ID(), 'wpex_post_self_hosted_shortcode', true )
		&& '' == get_post_meta( get_the_ID(), 'wpex_post_oembed', true ) ) {
		$classes[] = 'no-featured-image';
	}

	// Blog entry style
	$classes[] = $entry_style;

	// Avatar
	if ( $avatar_enabled = wpex_get_mod( 'blog_entry_author_avatar' ) ) {
		$classes[] = 'entry-has-avatar';
	}

	// Counter
	global $wpex_count;
	if ( $wpex_count ) {
		$classes[] = 'col-'. $wpex_count;
	}

	// Apply filters to entry post class for child theming
	$classes = apply_filters( 'wpex_blog_entry_classes', $classes );

	// Rturn classes array
	return $classes;
}

/**
 * Returns the blog entry thumbnail
 *
 * @since 1.0.0
 */
function wpex_blog_entry_thumbnail( $args = '' ) {
	echo wpex_get_blog_entry_thumbnail( $args );
}

/**
 * Returns the blog entry thumbnail
 *
 * @since 1.0.0
 */
function wpex_get_blog_entry_thumbnail( $args = '' ) {

	// If args isn't array then it's the attachment
	if ( $args && ! is_array( $args ) ) {
		$args = array(
			'attachment' => $args,
		);
	}

	// Define thumbnail args
	$defaults = array(
		'attachment'    => get_post_thumbnail_id(),
		'size'          => 'blog_entry',
		'apply_filters' => 'wpex_blog_entry_thumbnail_args',
	);

	// Parse arguments
	$args = wp_parse_args( $args, $defaults );

	// Custom sizes for categories
	if ( is_category() ) {

		// Get term data
		$term       = get_query_var('cat');
		$term_data  = get_option("category_$term");

		// Width
		if ( ! empty( $term_data['wpex_term_image_width'] ) ) {
			$args['size']   = 'wpex_custom';
			$args['width']  = $term_data['wpex_term_image_width'];
		}

		// height
		if ( ! empty( $term_data['wpex_term_image_height'] ) ) {
			$args['size']   = 'wpex_custom';
			$args['height'] = $term_data['wpex_term_image_height'];
		}

	}

	// Generate thumbnail and apply filters
	return apply_filters( 'wpex_blog_entry_thumbnail', wpex_get_post_thumbnail( $args ) );

}

/**
 * Displays the blog post thumbnail
 *
 * @since Total 1.0
 */
function wpex_blog_post_thumbnail( $args = '' ) {
	echo wpex_get_blog_post_thumbnail( $args );
}

/**
 * Returns the blog post thumbnail
 *
 * @since 1.0.0
 */
function wpex_get_blog_post_thumbnail( $args = '' ) {

	$supports_thumbnail = ( 'audio' == get_post_format() ) ? false : true;

	if ( ! apply_filters( 'wpex_blog_post_supports_thumbnail', $supports_thumbnail ) ) {
		return;
	}

	// If args isn't array then it's the attachment
	if ( ! is_array( $args ) && ! empty( $args ) ) {
		$args = array(
			'attachment'    => $args,
			'schema_markup' => false,
		);
	}

	// Defaults
	$defaults = array(
		'size'          => 'blog_post',
		'schema_markup' => true,
		'alt'           => wpex_get_esc_title(), // Keep alt for posts since there is no ling with title attribute
		'apply_filters' => 'wpex_blog_post_thumbnail_args',
	);

	// Parse arguments
	$args = wp_parse_args( $args, $defaults );

	// Change size for above media
	if ( 'above' == wpex_get_custom_post_media_position() ) {
		$args['size'] = 'blog_post_full';
	}

	// Generate thumbnail and apply filters
	return apply_filters( 'wpex_blog_post_thumbnail', wpex_get_post_thumbnail( $args ) );

}

/**
 * Returns post video URL
 *
 * @since 1.0.0
 */
function wpex_post_video_url( $post_id = '' ) {

	// Sanitize post_id var
	$post_id = $post_id ? $post_id : wpex_get_current_post_id();

	// Oembed
	if ( $meta = get_post_meta( $post_id, 'wpex_post_oembed', true ) ) {
		return esc_url( $meta );
	}

	// Self Hosted redux
	$video = get_post_meta( $post_id, 'wpex_post_self_hosted_shortcode_redux', true );
	if ( is_array( $video ) && ! empty( $video['url'] ) ) {
		return $video['url'];
	}

	// Self Hosted old - Thunder theme compatibility
	if ( $meta = get_post_meta( $post_id, 'wpex_post_self_hosted_shortcode', true ) ) {
		return $meta;
	}

}

/**
 * Returns post audio URL
 *
 * @since 1.0.0
 */
function wpex_post_audio_url( $post_id = '' ) {

	// Sanitize post_id var
	$post_id = $post_id ? $post_id : wpex_get_current_post_id();

	// Oembed
	if ( $meta = get_post_meta( $post_id, 'wpex_post_oembed', true ) ) {
		return $meta;
	}

	// Self Hosted redux
	$audio = get_post_meta( $post_id, 'wpex_post_self_hosted_shortcode_redux', true );
	if ( is_array( $audio ) && ! empty( $audio['url'] ) ) {
		return $audio['url'];
	}

	// Self Hosted old - Thunder theme compatibility
	if ( $meta = get_post_meta( $post_id, 'wpex_post_self_hosted_shortcode', true ) ) {
		return $meta;
	}

}
/**
 * Adds main classes to blog post entries
 *
 * @since 1.1.6
 */
function wpex_blog_wrap_classes( $classes = NULL ) {

	// Return custom class if set
	if ( $classes ) {
		return $classes;
	}

	// Admin defaults
	$style   = wpex_blog_style();
	$classes = array( 'entries', 'clr' );

	// Isotope classes
	if ( $style == 'grid-entry-style' ) {
		$classes[] = 'wpex-row';
		if ( 'masonry' == wpex_blog_grid_style() ) {
			$classes[] = 'blog-masonry-grid';
		} else {
			if ( 'infinite_scroll' == wpex_blog_pagination_style() ) {
				$classes[] = 'blog-masonry-grid';
			} else {
				$classes[] = 'blog-grid';
			}
		}
		if ( $gap = wpex_get_mod( 'blog_grid_gap' ) ) {
			$classes[] = 'gap-'. $gap;
		}
	}

	// Left thumbs
	if ( 'thumbnail-entry-style' == $style ) {
		$classes[] = 'left-thumbs';
	}

	// Add some margin when author is enabled
	if ( $style == 'grid-entry-style' && wpex_get_mod( 'blog_entry_author_avatar' ) ) {
		$classes[] = 'grid-w-avatars';
	}

	// Equal heights
	if ( wpex_blog_entry_equal_heights() ) {
		$classes[] = 'blog-equal-heights';
	}

	// Infinite scroll classes
	if ( 'infinite_scroll' == wpex_blog_pagination_style() ) {
		$classes[] = 'infinite-scroll-wrap';
	}

	// Add filter for child theming
	$classes = apply_filters( 'wpex_blog_wrap_classes', $classes );

	// Turn classes into space seperated string
	if ( is_array( $classes ) ) {
		$classes = implode( ' ', $classes );
	}

	// Echo classes
	echo esc_attr( $classes );

}

/**
 * Gets correct heading for the related blog items
 *
 * @since 2.0.0
 */
function wpex_blog_related_heading() {
	$heading = wpex_get_translated_theme_mod( 'blog_related_title' );
	$heading = $heading ? $heading : esc_html__( 'Related Posts', 'total' );
	return $heading;
}

/**
 * Returns blog entry blocks
 *
 * @since 2.0.0
 * @todo  rename to 'wpex_blog_entry_blocks' for consistency
 */
function wpex_blog_entry_layout_blocks() {

	// Get layout blocks
	$blocks = wpex_get_mod( 'blog_entry_composer' );

	// If blocks are 100% empty return defaults
	$blocks = $blocks ? $blocks : 'featured_media,title,meta,excerpt_content,readmore';

	// Convert blocks to array so we can loop through them
	if ( ! is_array( $blocks ) ) {
		$blocks = explode( ',', $blocks );
	}

	// Set block keys equal to vals
	$blocks = $blocks ? array_combine( $blocks, $blocks ) : array();

	// Apply filters to entry layout blocks after they are turned into an array
	$blocks = apply_filters( 'wpex_blog_entry_layout_blocks', $blocks, 'front-end' );

	// Return blocks
	return $blocks;

}

/**
 * Returns blog entry meta sections
 *
 * @since 2.0.0
 */
function wpex_blog_entry_meta_sections() {

	// Default sections
	$sections = array( 'date', 'author', 'categories', 'comments' );

	// Get Sections from Customizer
	$sections = wpex_get_mod( 'blog_entry_meta_sections', $sections );

	// Turn into array if string
	if ( $sections && ! is_array( $sections ) ) {
		$sections = explode( ',', $sections );
	}

	// Array tweaks
	if ( is_array( $sections ) ) {

		// Set keys equal to values for easier modification
		$sections = $sections ? array_combine( $sections, $sections ) : array();

		// Remove comments for link format
		if ( $sections && 'link' == get_post_format() ) {
			unset( $sections['comments'] );
		}

	}

	// Apply filters for easy modification
	$sections = apply_filters( 'wpex_blog_entry_meta_sections', $sections );

	// Return sections
	return $sections;

}

/**
 * Returns single blog post blocks
 *
 * @since 2.0.0
 * @todo  rename to 'wpex_blog_single_blocks' for consistency
 */
function wpex_blog_single_layout_blocks() {

	// Default blocks
	$defaults = array(
		'featured_media',
		'title',
		'meta',
		'post_series',
		'the_content',
		'post_tags',
		'social_share',
		'author_bio',
		'related_posts',
		'comments',
	);

	// Get layout blocks
	$blocks = wpex_get_mod( 'blog_single_composer' );

	// If blocks are empty return defaults
	$blocks = $blocks ? $blocks : $defaults;

	// Convert blocks to array so we can loop through them
	if ( ! is_array( $blocks ) ) {
		$blocks = explode( ',', $blocks );
	}

	// Set block keys equal to vals
	$blocks = $blocks ? array_combine( $blocks, $blocks ) : array();

	// Remove items if post is password protected
	if ( post_password_required() ) {
		unset( $blocks['featured_media'] );
		unset( $blocks['post_tags'] );
		unset( $blocks['social_share'] );
		unset( $blocks['author_bio'] );
		unset( $blocks['author_bio'] );
	}

	// Apply filters to single layout blocks
	// This filter won't add setting to Customizer
	// This setting is simply for filtering on front-end > must be different.
	$blocks = apply_filters( 'wpex_blog_single_layout_blocks', $blocks, 'front-end' );

	// Return blocks
	return $blocks;

}

/**
 * Returns single blog meta sections
 *
 * @since 2.0.0
 */
function wpex_blog_single_meta_sections() {

	// Default sections
	$sections = array( 'date', 'author', 'categories', 'comments' );

	// Get Sections from Customizer
	$sections = wpex_get_mod( 'blog_post_meta_sections', $sections );

	// Turn into array if string
	if ( $sections && ! is_array( $sections ) ) {
		$sections = explode( ',', $sections );
	}

	// Array tweaks
	if ( is_array( $sections ) ) {

		// Set keys equal to values for easier modification
		$sections = ( $sections && is_array( $sections ) ) ? array_combine( $sections, $sections ) : array();

	}

	// Apply filters for easy modification
	$sections = apply_filters( 'wpex_blog_single_meta_sections', $sections );

	// Return sections
	return $sections;

}

/**
 * Returns data attributes for the blog gallery slider
 *
 * @since 2.0.0
 */
function wpex_blog_slider_data_atrributes() {
	echo wpex_get_slider_data( array(
		'filter_tag' => 'wpex_blog_slider_data_atrributes',
	) );
}

/**
 * Returns correct blog slider video embed code
 * Adds unique class for the slider
 *
 * @since 2.0.0
 */
function wpex_blog_slider_video( $attachment ) {
	$video = get_post_meta( $attachment, '_video_url', true );
	$video = wp_oembed_get( esc_url( $video ) );
	return wpex_add_sp_video_to_oembed( $video );
}