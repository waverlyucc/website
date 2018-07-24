<?php
/**
 * Visual Composer Carousel
 *
 * @package Total WordPress Theme
 * @subpackage VC Templates
 * @version 4.6.5
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
if ( ! function_exists( 'vc_map_get_attributes' ) ) {
	vcex_function_needed_notice();
	return;
}

// Define output
$output = '';

// Deprecated Attributes
if ( ! empty( $atts['term_slug'] ) && empty( $atts['include_categories']) ) {
	$atts['include_categories'] = $atts['term_slug'];
}

// Get and extract shortcode attributes
$atts = vcex_vc_map_get_attributes( 'vcex_blog_carousel', $atts );

// Define vars
$atts['post_type'] = 'post';
$atts['taxonomy']  = 'category';
$atts['tax_query'] = '';

// Extract attributes
extract( $atts );

// Build the WordPress query
$wpex_query = vcex_build_wp_query( $atts );

// Output posts
if ( $wpex_query->have_posts() ) :

	// Sanitize & declare variables
	$overlay_style = $overlay_style ? $overlay_style : 'none';

	// IMPORTANT: Fallback required from VC update when params are defined as empty
	// AKA - set things to enabled by default
	$media   = ( ! $media ) ? 'true' : $media;
	$title   = ( ! $title ) ? 'true' : $title;
	$date    = ( ! $date ) ? 'true' : $date;
	$excerpt = ( ! $excerpt ) ? 'true' : $excerpt;

	// Main Classes
	$wrap_classes = array( 'wpex-carousel', 'wpex-carousel-blog', 'owl-carousel', 'clr' );

	// Carousel style
	if ( $style && 'default' != $style ) {
		$wrap_classes[] = $style;
		$arrows_position = ( 'no-margins' == $style && 'default' == $arrows_position ) ? 'abs' : $arrows_position;
	}

	// Alignment
	if ( $content_alignment ) {
		$wrap_classes[] = 'text'. $content_alignment;
	}

	// Arrow style
	$arrows_style = $arrows_style ? $arrows_style : 'default';
	$wrap_classes[] = 'arrwstyle-'. $arrows_style;

	// Arrow position
	if ( $arrows_position && 'default' != $arrows_position ) {
		$wrap_classes[] = 'arrwpos-'. $arrows_position;
	}

	// Css animation
	if ( $css_animation && 'none' != $css_animation ) {
		$wrap_classes[] = vcex_get_css_animation( $css_animation );
	}

	// Extra classes
	if ( $classes ) {
		$wrap_classes[] = vcex_get_extra_class( $classes );
	}

	// Visibility
	if ( $visibility ) {
		$wrap_classes[] = $visibility;
	}

	// Entry media classes
	if ( 'true' == $media ) {

		$media_classes = array( 'wpex-carousel-entry-media', 'clr' );
		
		if ( $img_hover_style ) {
			$media_classes[] = wpex_image_hover_classes( $img_hover_style );
		}
		
		if ( $img_filter ) {
			$media_classes[] = wpex_image_filter_class( $img_filter );
		}
		
		if ( $overlay_style ) {
			$media_classes[] = wpex_overlay_classes( $overlay_style );
		}
		
		if ( 'lightbox' == $thumbnail_link ) {
			$wrap_classes[] = 'wpex-carousel-lightbox';
			vcex_enque_style( 'ilightbox' );
		}

		$media_classes = implode( ' ', $media_classes );

	}

	// New content design settings
	if ( $content_css ) {
		$content_css = ' '. vc_shortcode_custom_css_class( $content_css );
	}
	// Old content design settings
	else {
		$content_style = array(
			'background' => $content_background,
			'padding'    => $content_padding,
			'margin'     => $content_margin,
			'border'     => $content_border,
		);
	}
	$content_style['opacity']    = $content_opacity;
	$content_style = vcex_inline_style( $content_style );

	// Title design
	if ( 'true' == $title ) {
		
		$heading_style = vcex_inline_style( array(
			'margin'         => $content_heading_margin,
			'font_size'      => $content_heading_size,
			'font_weight'    => $content_heading_weight,
			'text_transform' => $content_heading_transform,
			'line_height'    => $content_heading_line_height,
			'color'          => $content_heading_color,
		
		) );
	}

	// Date design
	if ( 'true' == $date ) {
		
		$date_style = vcex_inline_style( array(
			'color'     => $date_color,
			'font_size' => $date_font_size,
		
		) );
	}

	// Excerpt style
	if ( 'true' == $excerpt ) {
		
		$excerpt_styling = vcex_inline_style( array(
			'color'     => $content_color,
			'font_size' => $content_font_size,
		
		) );
	}

	// Readmore design and classes
	if ( 'true' == $read_more ) {

		// Readmore text
		$read_more_text = $read_more_text ? $read_more_text : esc_html__( 'read more', 'total' );

		// Readmore classes
		$readmore_classes = wpex_get_button_classes( $readmore_style, $readmore_style_color );

		// Readmore style
		$readmore_style = vcex_inline_style( array(
			'background'    => $readmore_background,
			'color'         => $readmore_color,
			'font_size'     => $readmore_size,
			'padding'       => $readmore_padding,
			'border_radius' => $readmore_border_radius,
			'margin'        => $readmore_margin,
		), false );

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

	// Sanitize carousel data
	$arrows                 = wpex_esc_attr( $arrows, 'true' );
	$dots                   = wpex_esc_attr( $dots, 'false' );
	$auto_play              = wpex_esc_attr( $auto_play, 'false' );
	$infinite_loop          = wpex_esc_attr( $infinite_loop, 'true' );
	$center                 = wpex_esc_attr( $center, 'false' );
	$items                  = wpex_intval( $items, 4 );
	$items_scroll           = wpex_intval( $items_scroll, 1 );
	$timeout_duration       = wpex_intval( $timeout_duration, 5000 );
	$items_margin           = wpex_intval( $items_margin, 15 );
	$items_margin           = ( 'no-margins' == $style ) ? 0 : $items_margin;
	$tablet_items           = wpex_intval( $tablet_items, 3 );
	$mobile_landscape_items = wpex_intval( $mobile_landscape_items, 2 );
	$mobile_portrait_items  = wpex_intval( $mobile_portrait_items, 1 );
	$animation_speed        = wpex_intval( $animation_speed );

	// Disable autoplay
	if ( '1' == count( $wpex_query->posts ) ) {
		$auto_play = 'false';
	}

	// Convert arrays to strings
	$wrap_classes = implode( ' ', $wrap_classes );
	$wrap_classes = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, $wrap_classes, 'vcex_blog_carousel', $atts );

	// Begin shortcode output
	$output .= '<div class="'. esc_attr( $wrap_classes ) .'"'. vcex_get_unique_id( $unique_id ) .' data-items="'. $items .'" data-slideby="'. $items_scroll .'" data-nav="'. $arrows .'" data-dots="'. $dots .'" data-autoplay="'. $auto_play .'" data-loop="'. $infinite_loop .'" data-autoplay-timeout="'. $timeout_duration .'" data-center="'. $center .'" data-margin="'. intval( $items_margin ) .'" data-items-tablet="'. $tablet_items .'" data-items-mobile-landscape="'. $mobile_landscape_items .'" data-items-mobile-portrait="'. $mobile_portrait_items .'" data-smart-speed="'. $animation_speed .'">';

		// Define counter
		$count = 0;

		// Start loop
		while ( $wpex_query->have_posts() ) :

			// Get post from query
			$wpex_query->the_post();

			// Add to counter
			$count++;
		
			// Post VARS
			$atts['post_id']             = get_the_ID();
			$atts['post_permalink']      = wpex_get_permalink( $atts['post_id'] );
			$atts['post_title']          = get_the_title();
			$atts['post_esc_title']      = wpex_get_esc_title();
			$atts['post_thumbnail']      = get_post_thumbnail_id( $atts['post_id'] );
			$atts['post_thumbnail_link'] = $atts['post_permalink'];

			// Lets store the dynamic $atts['post_id'] into the shortcodes attributes
			$atts['post_id'] = $atts['post_id'];

			// Only display carousel item if there is content to show
			if ( ( 'true' == $media && $atts['post_thumbnail'] )
				|| 'true' == $title
				|| 'true' == $date
				|| 'true' == $excerpt
			) :

				// Entry classes
				$entry_classes = 'wpex-carousel-slide clr';
				if ( $atts['post_thumbnail'] ) {
					$entry_classes .= ' has-media';
				}

				$output .= '<div class="' . $entry_classes . '">';
				
					// Display thumbnail if enabled and defined
					$media_output = '';
					if ( 'true' == $media ) :

						if ( $atts['post_thumbnail'] ) {

							$media_output .= '<div class="' . $media_classes . '">';

								// If thumbnail link doesn't equal none
								if ( 'none' != $thumbnail_link ) :

									// Lightbox thumbnail
									if ( 'lightbox' == $thumbnail_link ) {
										$atts['lightbox_link'] = wpex_get_lightbox_image( $atts['post_thumbnail'] );
										$atts['post_thumbnail_link']  = $atts['lightbox_link'];
									}

									// Link attributes
									$link_attrs = array(
										'href'  => esc_url( $atts['post_thumbnail_link'] ),
										'title' => $atts['post_esc_title'],
										'class' => 'wpex-carousel-entry-img',
									);
									// Add lightbox link
									if ( 'lightbox' == $thumbnail_link ) {
										$link_attrs['class'] .= ' wpex-carousel-lightbox-item';
										$link_attrs['data-title'] = $atts['post_esc_title'];
										$link_attrs['data-count'] = $count;
									}

								$media_output .= '<a ' . wpex_parse_attrs( $link_attrs ) . '>';

								endif; // End thumbnail_link check

								// Display post thumbnail
								$media_output .= wpex_get_post_thumbnail( array(
									'attachment'    => $atts['post_thumbnail'],
									'width'         => $img_width,
									'height'        => $img_height,
									'size'          => $img_size,
									'crop'          => $img_crop,
									'attributes'    => array( 'data-no-lazy' => 1 ),
									'apply_filters' => 'vcex_blog_carousel_thumbnail_args',
									'filter_arg1'   => $atts,
								) );

								// Inner link overlay html
								if ( 'none' != $overlay_style ) {
									ob_start();
									wpex_overlay( 'inside_link', $overlay_style, $atts );
									$media_output .= ob_get_clean();
								}

								// Entry after media hook
								$media_output .= wpex_get_entry_media_after( 'vcex_blog_carousel' );

								// Close link tag
								if ( 'none' != $thumbnail_link ) {
									$media_output .= '</a>';
								}

								// Outer link overlay HTML
								if ( 'none' != $overlay_style ) {
									ob_start();
									wpex_overlay( 'outside_link', $overlay_style, $atts );
									$media_output .= ob_get_clean();
								}

							$media_output .= '</div>';

						}

						$output .= apply_filters( 'vcex_blog_carousel_media', $media_output, $atts );

					endif; // End media check

					// Open details element if the title or excerpt are true
					if ( 'true' == $title || 'true' == $date || 'true' == $excerpt || 'true' == $read_more ) :

						$output .= '<div class="wpex-carousel-entry-details clr' . $content_css . '"' . $content_style . '>';

							// Display title if $title is true and there is a post title
							$title_output = '';
							if ( 'true' == $title ) :

								$title_output .= '<div class="wpex-carousel-entry-title entry-title"' . $heading_style . '>';
									
									$title_output .= '<a href="' . $atts['post_permalink'] . '">';
									
										$title_output .= wp_kses_post( $atts['post_title'] );
								
									$title_output .= '</a>';
								
								$title_output .= '</div>';

								$output .= apply_filters( 'vcex_blog_carousel_title', $title_output, $atts );

							endif; // End title check

							// Display publish date if $date is enabled
							$date_output = '';
							if ( 'true' == $date ) :

								$date_output .= '<div class="vcex-blog-entry-date"' . $date_style . '>';
								
									$date_output .= get_the_date();
								
								$date_output .= '</div>';

								$output .= apply_filters( 'vcex_blog_carousel_date', $date_output, $atts );

							endif; // End data check

							// Display excerpt if $excerpt is true
							$excerpt_output = '';
							if ( 'true' == $excerpt ) :

								$excerpt_output .= '<div class="wpex-carousel-entry-excerpt clr"' . $excerpt_styling . '>';
									
									$excerpt_output .= wpex_get_excerpt( array(
										'length'  => $excerpt_length,
										'context' => 'vcex_blog_carousel',
									) );
								
								$excerpt_output .= '</div>';

								$output .= apply_filters( 'vcex_blog_carousel_excerpt', $excerpt_output, $atts );
								
							endif; // End excerpt check

							// Display read more button if $read_more is true
							$readmore_output = '';
							if ( 'true' == $read_more ) :

								$readmore_output .= '<div class="entry-readmore-wrap clr">';

									$attrs = array(
										'href'  => esc_url( $atts['post_permalink'] ),
										'class' => $readmore_classes,
										'rel'   => 'bookmark',
										'style' => $readmore_style,
									);

									if ( $readmore_hover_data ) {
										$attrs['data-wpex-hover'] = $readmore_hover_data;
									}

									$readmore_output .= '<a ' . wpex_parse_attrs( $attrs ) . '>';

										$readmore_output .= $read_more_text;

										if ( 'true' == $readmore_rarr ) {
											$readmore_output .= ' <span class="vcex-readmore-rarr">' . wpex_element( 'rarr' ) . '</span>';
										}

									$readmore_output .= '</a>';

								$readmore_output .= '</div>';

								$output .= apply_filters( 'vcex_blog_carousel_readmore', $readmore_output, $atts );

							endif; // End readmore check

						$output .= '</div>';

					endif; // End details check

				$output .= '</div>';

			endif; // End data check

		// End entry loop
		endwhile;

	$output .= '</div>';

	// Reset the post data to prevent conflicts with WP globals
	wp_reset_postdata();

	// Output shortcode
	echo $output;

// If no posts are found display message
else :

	// Display no posts found error if function exists
	echo vcex_no_posts_found_message( $atts );

// End post check
endif;