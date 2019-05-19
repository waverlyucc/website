<?php
/**
 * Visual Composer Recent News
 *
 * @package Total WordPress Theme
 * @subpackage VC Templates
 * @version 4.7.1
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
if ( ! function_exists( 'vc_map_get_attributes' ) || ! function_exists( 'vc_shortcode_custom_css_class' ) ) {
	vcex_function_needed_notice();
	return;
}

// Define output var
$output = '';

// Deprecated Attributes
$term_slug = isset( $atts['term_slug'] ) ? $atts['term_slug'] : '';

// Store orginal atts value for use in non-builder params
$og_atts = $atts;

// Define entry counter
$entry_count = ! empty( $og_atts['entry_count'] ) ? $og_atts['entry_count'] : 0;

// Get shortcode attributes
$atts = vc_map_get_attributes( 'vcex_recent_news', $atts );

// Add paged attribute for load more button (used for WP_Query)
if ( ! empty( $og_atts['paged'] ) ) {
	$atts['paged'] = $og_atts['paged'];
}

// Define non-vc attributes
$atts['tax_query']  = '';
$atts['taxonomies'] = 'category';

// Extract shortcode atts
extract( $atts );

// IMPORTANT: Fallback required from VC update when params are defined as empty
// AKA - set things to enabled by default
$title     = ( ! $title ) ? 'true' : $title;
$date      = ( ! $date ) ? 'true' : $date;
$excerpt   = ( ! $excerpt ) ? 'true' : $excerpt;
$read_more = ( ! $read_more ) ? 'true' : $read_more;

// Fallback for term slug
if ( ! empty( $term_slug ) && empty( $include_categories ) ) {
	$include_categories = $term_slug;
}

// Custom taxonomy only for standard posts
if ( 'custom_post_types' == $get_posts ) {
	$atts['include_categories'] = $atts['exclude_categories'] = '';
}

// Get Standard posts
if ( 'standard_post_types' == $get_posts ) {
	$atts['post_types'] = 'post';
}

// Build the WordPress query
$wpex_query = vcex_build_wp_query( $atts );

// Output posts
if ( $wpex_query->have_posts() ) :

	// Sanitize data + declare vars
	$grid_columns = $grid_columns ? $grid_columns : '1';

	// Wrap Classes
	$wrap_classes = array( 'vcex-module', 'vcex-recent-news', 'clr' );
	if ( $classes ) {
		$wrap_classes[] = vcex_get_extra_class( $classes );
	}
	if ( $visibility ) {
		$wrap_classes[] = $visibility;
	}
	if ( '1' != $grid_columns ) {
		$wrap_classes[] = 'wpex-row';
		if ( $columns_gap ) {
			$wrap_classes[] = 'gap-' . $columns_gap;
		}
		$atts['columns'] = $grid_columns;
		$grid_columns_class = vcex_get_grid_column_class( $atts );
	}
	if ( $css ) {
		$wrap_classes[] = vc_shortcode_custom_css_class( $css );
	}

	// Entry Classes
	$entry_classes = array( 'vcex-recent-news-entry', 'clr' );
	if ( 'true' != $date ) {
		$entry_classes[] = 'no-left-padding';
	}
	if ( $css_animation && 'none' != $css_animation ) {
		$entry_classes[] = vcex_get_css_animation( $css_animation );
	}

	// Entry Style
	$entry_style = vcex_inline_style( array(
		'border_color' => $entry_bottom_border_color
	) );

	// Heading style
	if ( 'true' == $title ) {
		$title_tag = $title_tag ? $title_tag : 'h2';
		$heading_style = vcex_inline_style( array(
			'font_size'      => $title_size,
			'font_weight'    => $title_weight,
			'text_transform' => $title_transform,
			'line_height'    => $title_line_height,
			'margin'         => $title_margin,
			'color'          => $title_color,
		) );
	}

	// Excerpt style
	if ( 'true' == $excerpt ) {
		$excerpt_style = vcex_inline_style( array(
			'font_size' => $excerpt_font_size,
			'color' => $excerpt_color,
		) );
	}

	// Month Style
	if ( 'true' == $date ) {
		$month_style = vcex_inline_style( array(
			'background_color' => $month_background,
			'color' => $month_color,
		) );
	}

	// Readmore design and classes
	if ( 'true' == $read_more ) {

		// Readmore text
		$read_more_text = $read_more_text ? $read_more_text : esc_html__( 'read more', 'total' );

		// Readmore classes
		$readmore_classes = wpex_get_button_classes( $readmore_style, $readmore_style_color );

		// Read more style
		$readmore_border_color  = ( 'outline' == $readmore_style ) ? $readmore_color : '';
		$readmore_style = vcex_inline_style( array(
			'background' => $readmore_background,
			'color' => $readmore_color,
			'border_color' => $readmore_border_color,
			'font_size' => $readmore_size,
			'padding' => $readmore_padding,
			'border_radius' => $readmore_border_radius,
			'margin' => $readmore_margin,
		) );

		// Readmore data
		$readmore_hover_data = array();
		if ( $readmore_hover_background ) {
			$readmore_hover_data['background'] = $readmore_hover_background;
		}
		if ( $readmore_hover_color ) {
			$readmore_hover_data['color'] = $readmore_hover_color;
		}
		if ( $readmore_hover_data ) {
			$readmore_hover_data = json_encode( $readmore_hover_data );
		}

	}

	// Convert arrays to strings
	$wrap_classes = implode( ' ', $wrap_classes );

	// VC filter
	$wrap_classes = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, $wrap_classes, 'vcex_recent_news', $atts );

	// Add wrapper (introduced in 4.8 for load more function)
	$output .= '<div class="vcex-recent-news-wrap clr">';

	// Output module
	$output .= '<div class="' . esc_attr( $wrap_classes ) . '"' . vcex_get_unique_id( $unique_id ) . '>';

		// Display header if enabled
		if ( $header ) {

			$output .= wpex_heading( array(
				'echo'    => false,
				'content' => $header,
				'classes' => array( 'vcex-recent-news-header vcex-module-heading' ),
			) );

		}

		// Loop through posts
		$total_count = 0;
		while ( $wpex_query->have_posts() ) :

			// Get post from query
			$wpex_query->the_post();

			// Add to counters
			$entry_count++;

			// Create new post object.
			$post = new stdClass();

			// Post vars
			$post->ID            = get_the_ID();
			$post->permalink     = wpex_get_permalink( $post->ID );
			$post->the_title     = get_the_title( $post->ID );
			$post->the_title_esc = esc_attr( the_title_attribute( 'echo=0' ) );
			$post->type          = get_post_type( $post->ID );
			$post->video_embed   = wpex_get_post_video_html();
			$post->format        = get_post_format( $post->ID );

			$entry_wrap_classes = 'vcex-recent-news-entry-wrap vcex-grid-item';
			if ( $grid_columns > '1' ) {
				$entry_wrap_classes .= ' col ' . $grid_columns_class . ' col-' . $entry_count;
			}

			$output .= '<div class="' . esc_attr( $entry_wrap_classes ) . '">';

			$output .= '<article ' . wpex_get_post_class( $entry_classes, $post->ID ) . '' . $entry_style . '>';

				// Display date
				if ( 'true' == $date ) {

					$output .= '<div class="vcex-recent-news-date">';

						$output .= '<span class="day">';

							// Standard day display
							$day = get_the_time( 'd', $post->ID );

							// Filter day display for tribe events calendar plugin
							// @todo move to events config file
							if ( 'tribe_events' == $post->type && function_exists( 'tribe_get_start_date' ) ) {
								$day = tribe_get_start_date( $post->ID, false, 'd' );
							}

							// Apply filters and return date
							$output .= apply_filters( 'vcex_recent_news_day_output', $day );

						// Close day
						$output .= '</span>';

						$output .= '<span class="month"' . $month_style . '>';

							// Standard month year display
							$month_year = '<span>' . get_the_time( 'M', $post->ID ) . '</span>';
							$month_year .= ' <span class="year">' . get_the_time( 'y', $post->ID ) . '</span>';

							// Filter month/year display for tribe events calendar plugin
							// @todo move to events config file
							if ( 'tribe_events' == $post->type && function_exists( 'tribe_get_start_date' ) ) {
								$month_year = '<span>' . tribe_get_start_date( $post->ID, false, 'M' ) . '</span>';
								$month_year .= ' <span class="year">' . tribe_get_start_date( $post->ID, false, 'y' ) . '</span>';
							}

							// Echo the month/year
							$output .= apply_filters( 'vcex_recent_news_month_year_output', $month_year );

						// Close month
						$output .= '</span>';

					$output .= '</div>';

				}

				$output .= '<div class="vcex-news-entry-details clr">';

					// Show featured media if enabled
					if ( 'true' == $featured_image ) {

						// Display video
						if ( 'true' == $featured_video && $post->video_embed ) {

							$output .= '<div class="vcex-news-entry-video clr">' . $post->video_embed . '</div>';

						// Display featured image
						} elseif ( has_post_thumbnail( $post->ID ) ) {

							$output .= '<div class="vcex-news-entry-thumbnail clr">';

								$output .= '<a href="' . $post->permalink . '" title="' . wpex_get_esc_title() . '">';

									// Display thumbnail
									$output .= wpex_get_post_thumbnail( array(
										'size'          => $img_size,
										'crop'          => $img_crop,
										'width'         => $img_width,
										'height'        => $img_height,
										'alt'           => wpex_get_esc_title(),
										'apply_filters' => 'vcex_recent_news_thumbnail_args',
										'filter_arg1'   => $atts,
									) );

									$output .= wpex_get_entry_media_after( 'vcex_recent_news' );

								$output .= '</a>';

							$output .= '</div>';

						} // End thumbnail check

					} // End featured image check

					// Show title if enabled
					if ( 'true' == $title ) {

						$output .= '<header class="vcex-recent-news-entry-title entry-title">';

							$output .= '<' . $title_tag . ' class="vcex-recent-news-entry-title-heading"' . $heading_style . '>';

								$output .= '<a href="' . $post->permalink . '">' . wp_kses_post( $post->the_title ) . '</a>';

							$output .= '</' . $title_tag . '>';

						$output .= '</header>';

					} // End title check

					// Excerpt and readmore
					if ( 'true' == $excerpt || 'true' == $read_more ) {

						$output .= '<div class="vcex-recent-news-entry-excerpt clr">';

							if ( 'true' == $excerpt ) {

								$output .= '<div class="entry"' . $excerpt_style . '>';

									// Output excerpt
									$output .= wpex_get_excerpt( array(
										'length'  => $excerpt_length,
										'context' => 'vcex_recent_news',
									) );

								$output .= '</div>';

							} // End excerpt check

							// Display readmore link
							if ( 'true' == $read_more ) {

								$attrs = array(
									'href'  => esc_url( $post->permalink ),
									'class' => $readmore_classes,
									'rel'   => 'bookmark',
									'style' => $readmore_style,
								);

								if ( $readmore_hover_data ) {
									$attrs['data-wpex-hover'] = $readmore_hover_data;
								}

								$output .= '<a ' . wpex_parse_attrs( $attrs ) . '>';

									$output .= $read_more_text;

									if ( 'true' == $readmore_rarr ) {

										$output .= '<span class="vcex-readmore-rarr">' . wpex_element( 'rarr' ) . '</span>';

									}

								$output .= '</a>';

							} // End readmore text

						$output .= '</div>';

					} // End excerpt + readmore

				$output .= '</div>';

			$output .= '</article>';

			$output .= '</div>'; // entry wrap close

			if ( $entry_count == $grid_columns ) {
				$entry_count=0;
			}

		endwhile;

	$output .= '</div>';

	// Display pagination if enabled
	if ( ( 'true' == $atts['pagination'] || ( 'true' == $atts['custom_query'] && ! empty( $wpex_query->query['pagination'] ) ) )
		&& 'true' != $atts['pagination_loadmore']
	) {

		$output .= wpex_pagination( $wpex_query, false );

	}

	// Load more button
	if ( 'true' == $atts['pagination_loadmore'] && ! empty( $wpex_query->max_num_pages ) ) {

		vcex_loadmore_scripts();
		$og_atts['entry_count'] = $entry_count; // Update counter
		$output .= vcex_get_loadmore_button( 'vcex_recent_news', $og_atts, $wpex_query );

	}

	// Remove post object from memory
	$post = null;

	// Reset the post data to prevent conflicts with WP globals
	wp_reset_postdata();

	// Close wrap
	$output .= '</div>';

	// Echo output
	echo $output;

// If no posts are found display message
else :

	// Display no posts found error if function exists
	echo vcex_no_posts_found_message( $atts );

// End post check
endif;