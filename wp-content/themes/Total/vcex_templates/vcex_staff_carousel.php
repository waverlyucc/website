<?php
/**
 * Visual Composer Staff Carousel
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

// Define output var
$output = '';

// Deprecated Attributes
if ( ! empty( $atts['term_slug'] ) && empty( $atts['include_categories']) ) {
	$atts['include_categories'] = $atts['term_slug'];
}

// Get and extract shortcode attributes
$atts = vc_map_get_attributes( 'vcex_staff_carousel', $atts );

// Extract shortcode atts
extract( $atts );

// Inline check
$is_inline = wpex_vc_is_inline();

// Build the WordPress query
$atts['post_type'] = 'staff';
$atts['tax_query'] = '';
$wpex_query = vcex_build_wp_query( $atts );

// Output posts
if ( $wpex_query->have_posts() ) :

	// IMPORTANT: Fallback required from VC update when params are defined as empty
	// AKA - set things to enabled by default
	$title   = ( ! $title ) ? 'true' : $title;
	$excerpt = ( ! $excerpt ) ? 'true' : $excerpt;

	// Prevent auto play in visual composer
	if ( $is_inline ) {
		$auto_play = 'false';
	}

	// Items to scroll fallback for old setting
	if ( 'page' == $items_scroll ) {
		$items_scroll = $items;
	}

	// Main Classes
	$wrap_classes = array( 'vcex-module', 'wpex-carousel', 'wpex-carousel-staff', 'clr', 'owl-carousel' );
		
	// Main carousel style & arrow position
	if ( $style && 'default' != $style ) {
		$wrap_classes[] = $style;
		$arrows_position = ( 'no-margins' == $style && 'default' == $arrows_position ) ? 'abs' : $arrows_position;
	}

	// Content alignment
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

	// Visibility
	if ( $visibility ) {
		$wrap_classes[] = $visibility;
	}

	// CSS animation
	if ( $css_animation && 'none' != $css_animation ) {
		$wrap_classes[] = vcex_get_css_animation( $css_animation );
	}

	// Extra classes
	if ( $classes ) {
		$wrap_classes[] = vcex_get_extra_class( $classes );
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

	// Position design
	if ( 'true' == $position ) {
		$position_style = vcex_inline_style( array(
			'font_size'   => $position_size,
			'font_weight' => $position_weight,
			'margin'      => $position_margin,
			'color'       => $position_color,
		) );
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
	$content_style['opacity'] = $content_opacity;
	$content_style = vcex_inline_style( $content_style );

	// Social links style
	if ( 'true' == $social_links ) {
		$social_links_inline_css = vcex_inline_style( array(
			'margin' => $social_links_margin,
		), false );
	}

	// Title design
	if ( 'true' == $title ) {

		$heading_style = vcex_inline_style( array(
			'margin'         => $content_heading_margin,
			'text_transform' => $content_heading_transform,
			'font_weight'    => $content_heading_weight,
			'font_size'      => $content_heading_size,
			'line_height'    => $content_heading_line_height,
		) );

		$heading_link_style = vcex_inline_style( array(
			'color' => $content_heading_color,
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
	if ( $is_inline || '1' == count( $wpex_query->posts ) ) {
		$auto_play = 'false';
	}

	// Turn array to strings
	$wrap_classes = implode( ' ', $wrap_classes );

	// VC filter
	$wrap_classes = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, $wrap_classes, 'vcex_staff_carousel', $atts );
	
	// Begin output
	$output .='<div class="'. esc_attr( $wrap_classes ) .'"'. vcex_get_unique_id( $unique_id ) .' data-items="'. $items .'" data-slideby="'. $items_scroll .'" data-nav="'. $arrows .'" data-dots="'. $dots .'" data-autoplay="'. $auto_play .'" data-loop="'. $infinite_loop .'" data-autoplay-timeout="'. $timeout_duration .'" data-center="'. $center .'" data-margin="'. intval( $items_margin ) .'" data-items-tablet="'. $tablet_items .'" data-items-mobile-landscape="'. $mobile_landscape_items .'" data-items-mobile-portrait="'. $mobile_portrait_items .'" data-smart-speed="'. $animation_speed .'">';

		// Loop through posts
		$lcount = 0;
		while ( $wpex_query->have_posts() ) :

			// Get post from query
			$wpex_query->the_post();
		
			// Post VARS
			$atts['post_id']        = get_the_ID();
			$atts['post_title']     = get_the_title( $atts['post_id'] );
			$atts['post_permalink'] = wpex_get_permalink( $atts['post_id'] );
			$atts['post_esc_title'] = esc_attr( $atts['post_title'] );

			$output .= '<div class="wpex-carousel-slide wpex-clr">';

				// Media Wrap
				$media_output = '';
				if ( 'true' == $media && has_post_thumbnail() ) {

					// Generate featured image
					$thumbnail = wpex_get_post_thumbnail( array(
						'size'          => $img_size,
						'crop'          => $img_crop,
						'width'         => $img_width,
						'height'        => $img_height,
						'attributes'    => array( 'data-no-lazy' => 1 ),
						'apply_filters' => 'vcex_staff_carousel_thumbnail_args',
					) );

					$media_output .= '<div class="' . $media_classes . '">';

						// No links
						if ( in_array( $thumbnail_link, array( 'none', 'nowhere' ) ) ) {

							$media_output .= $thumbnail;

							$media_output .= wpex_get_entry_media_after( 'vcex_staff_carousel' );

						}
						// Lightbox
						elseif ( 'lightbox' == $thumbnail_link ) {

							$lcount ++;

							$media_output .= '<a href="' . wpex_get_lightbox_image() . '" title="' . $atts['post_esc_title'] . '" data-title="' . $atts['post_esc_title'] . '" data-count="' . $lcount . '" class="wpex-carousel-entry-img wpex-carousel-lightbox-item">';

								$media_output .= $thumbnail;

						}
						// Link to post
						else {

							$media_output .= '<a href="' . $atts['post_permalink'] . '" title="' . $atts['post_esc_title'] . '" class="wpex-carousel-entry-img">';

								$media_output .= $thumbnail;

						}

						// Inner Overlay
						if ( 'none' != $overlay_style ) {
							ob_start();
							wpex_overlay( 'inside_link', $overlay_style, $atts );
							$media_output .= ob_get_clean();
						}

						// Close link
						if ( ! in_array( $thumbnail_link, array( 'none', 'nowhere' ) ) ) {

							$media_output .= wpex_get_entry_media_after( 'vcex_staff_carousel' );

							$media_output .= '</a>';

						}

						// Outside Overlay
						if ( 'none' != $overlay_style ) {
							ob_start();
							wpex_overlay( 'outside_link', $overlay_style, $atts );
							$media_output .= ob_get_clean();
						}

					$media_output .= '</div>';

				} // End media

				$output .= apply_filters( 'vcex_staff_carousel_media', $media_output, $atts );

				// Title, Postion, Excerpt and Social links
				if ( 'true' == $title
					|| 'true' == $position
					|| 'true' == $excerpt
					|| 'true' == $social_links
					|| 'true' == $read_more
				) {

					$output .= '<div class="wpex-carousel-entry-details clr' . $content_css . '"' . $content_style . '>';

						// Title
						$title_output = '';
						if ( 'true' == $title ) {

							$title_output .= '<div class="wpex-carousel-entry-title entry-title"' . $heading_style . '>';

								if ( 'nowhere' == $title_link ) {

									$title_output .= wp_kses_post( $atts['post_title'] );

								} else {

									$title_output .= '<a href="' . $atts['post_permalink'] . '"' . $heading_link_style . '>';
										
										$title_output .= wp_kses_post( $atts['post_title'] );

									$title_output .= '</a>';

								}

							$title_output .= '</div>';

						}

						$output .= apply_filters( 'vcex_staff_carousel_title', $title_output, $atts );

						// Display position
						$position_output = '';
						if ( 'true' == $position ) {

							if ( $get_position = get_post_meta( $atts['post_id'], 'wpex_staff_position', true ) ) {

								$position_output .= '<div class="staff-entry-position"' . $position_style . '>';

									$position_output .= apply_filters( 'wpex_staff_entry_position', wp_kses_post( $get_position ) );

								$position_output .= '</div>';

							}

							$output .= apply_filters( 'vcex_staff_carousel_position', $position_output, $atts );

						} // End position

						// Check if the excerpt is enabled
						$excerpt_output = '';
						if ( 'true' == $excerpt ) {

							// Generate excerpt
							$atts['post_excerpt'] = wpex_get_excerpt( array(
								'length'  => intval( $excerpt_length ),
								'context' => 'vcex_staff_carousel',
							) );

							// Display excerpt if there is one
							if ( $atts['post_excerpt'] ) {

								$excerpt_output .= '<div class="wpex-carousel-entry-excerpt clr">';

									$excerpt_output .= $atts['post_excerpt'];

								$excerpt_output .= '</div>';

							}

							$output .= apply_filters( 'vcex_staff_carousel_excerpt', $excerpt_output, $atts );

						} // End excerpt

						// Check if social is enabled
						$social_output = '';
						if ( 'true' == $social_links ) {

							$social_output .= wpex_get_staff_social( array(
								'style'        => $social_links_style,
								'font_size'    => $social_links_size,
								'inline_style' => $social_links_inline_css,
							) );

							$output .= apply_filters( 'vcex_staff_carousel_social', $social_output, $atts );

						} // End social check

						// Display read more button if $read_more is true
						$readmore_output = '';
						if ( 'true' == $read_more ) {

							$readmore_output .= '<div class="entry-readmore-wrap clr">';

								$attrs = array(
									'href'  => $atts['post_permalink'],
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

							$output .= apply_filters( 'vcex_staff_carousel_readmore', $readmore_output, $atts );

						} // End readmore check

					$output .= '</div>';

				} // End content

			$output .= '</div>';

		endwhile;

	$output .= '</div>';

	// Reset the post data to prevent conflicts with WP globals
	wp_reset_postdata();

	// Output shortcode HTML
	echo $output;

// If no posts are found display message
else :

	// Display no posts found error if function exists
	echo vcex_no_posts_found_message( $atts );

// End post check
endif;