<?php
/**
 * Visual Composer Staff Grid
 *
 * @package Total WordPress Theme
 * @subpackage VC Templates
 * @version 4.6.1
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

// Deprecated Attributes
if ( ! empty( $atts['term_slug'] ) && empty( $atts['include_categories']) ) {
	$atts['include_categories'] = $atts['term_slug'];
}

// Define output var
$output = '';

// Get and extract shortcode attributes
$atts = vc_map_get_attributes( 'vcex_staff_grid', $atts );

// Extract shortcode atts
extract( $atts );

// Define user-generated attributes
$atts['post_type'] = 'staff';
$atts['taxonomy']  = 'staff_category';
$atts['tax_query'] = '';

// Build the WordPress query
$wpex_query = vcex_build_wp_query( $atts );

// Output posts
if ( $wpex_query->have_posts() ) :

	// IMPORTANT: Fallback required from VC update when params are defined as empty
	// AKA - set things to enabled by default
	$entry_media = ( ! $entry_media ) ? 'true' : $entry_media;
	$title       = ( ! $title ) ? 'true' : $title;
	$excerpt     = ( ! $excerpt ) ? 'true' : $excerpt;
	$read_more   = ( ! $read_more ) ? 'true' : $read_more;

	// Sanitize data & declare main variables
	$wrap_classes       = array( 'vcex-module', 'vcex-staff-grid-wrap', 'clr' );
	$grid_classes       = array( 'wpex-row', 'vcex-staff-grid', 'entries', 'wpex-clr' );
	$grid_data          = array();
	$is_isotope         = false;
	$excerpt_length     = $excerpt_length ? $excerpt_length : '30';
	$css_animation      = ( $css_animation && 'true' != $filter ) ? vcex_get_css_animation( $css_animation ) : false;
	$equal_heights_grid = ( 'true' == $equal_heights_grid && $columns > '1' ) ? true : false;
	$overlay_style      = $overlay_style ? $overlay_style : 'none';
	$title_tag          = apply_filters( 'vcex_grid_default_title_tag', $title_tag, $atts );
	$title_tag          = $title_tag ? esc_attr( $title_tag ) : 'h2';

	// Load lightbox scripts
	if ( 'lightbox' == $thumb_link ) {
		wpex_enqueue_ilightbox_skin( $lightbox_skin );
	}

	// Enable Isotope
	if ( 'true' == $filter || 'masonry' == $grid_style || 'no_margins' == $grid_style ) {
		$is_isotope = true;
	}

	// No need for masonry if not enough columns and filter is disabled
	if ( 'true' != $filter && 'masonry' == $grid_style ) {
		$post_count = count( $wpex_query->posts );
		if ( $post_count <= $columns ) {
			$is_isotope = false;
		}
	}

	// Get filter taxonomy
	if ( 'true' == $filter ) {
		$filter_taxonomy = apply_filters( 'vcex_filter_taxonomy', $atts['taxonomy'], $atts );
		$filter_taxonomy = taxonomy_exists( $filter_taxonomy ) ? $filter_taxonomy : '';
		if ( $filter_taxonomy ) {
			$atts['filter_taxonomy'] = $filter_taxonomy; // Add to array to pass on to vcex_grid_filter_args()
		}
	} else {
		$filter_taxonomy = null;
	}

	// Get filter terms
	if ( $filter_taxonomy ) {

		// Get filter terms
		$filter_terms = get_terms( $filter_taxonomy, vcex_grid_filter_args( $atts, $wpex_query ) );

		// Make sure we have terms before doing things
		if ( $filter_terms ) {

			// Check url for filter cat
			if ( $active_cat_query_arg = vcex_grid_filter_get_active_item( $filter_taxonomy ) ) {
				$filter_active_category = $active_cat_query_arg;
			}

			// Check if filter active cat exists on current page
			$filter_has_active_cat = in_array( $filter_active_category, wp_list_pluck( $filter_terms, 'term_id' ) ) ? true : false;

			// Add show on load animation when active filter is enabled to prevent double animation
			if ( $filter_has_active_cat ) {
				$grid_classes[] = 'wpex-show-on-load';
			}

		} else {
			$filter = false; // No terms
		}

	}

	// Wrap classes
	if ( $visibility ) {
		$wrap_classes[] = $visibility;
	}
	if ( $classes ) {
		$wrap_classes[] = vcex_get_extra_class( $classes );
	}

	// Grid classes
	if ( $columns_gap ) {
		$grid_classes[] = 'gap-'. $columns_gap;
	}
	if ( $is_isotope ) {
		$grid_classes[] = 'vcex-isotope-grid';
	}
	if ( 'no_margins' == $grid_style ) {
		$grid_classes[] = 'vcex-no-margin-grid';
	}
	if ( 'left_thumbs' == $single_column_style ) {
		$grid_classes[] = 'left-thumbs';
	}
	if ( $equal_heights_grid ) {
		$grid_classes[] = 'match-height-grid';
	}
	if ( 'true' == $thumb_lightbox_gallery ) {
		$grid_classes[] = ' lightbox-group';
		if ( $lightbox_skin ) {
			$grid_data[] = 'data-skin="'. $lightbox_skin .'"';
		}
		$lightbox_single_class = ' wpex-lightbox-group-item';
	} else {
		$lightbox_single_class = ' wpex-lightbox';
	}

	// Grid data attributes when filter is enabled
	if ( 'true' == $filter ) {
		if ( 'fitRows' == $masonry_layout_mode ) {
			$grid_data[] = 'data-layout-mode="fitRows"';
		}
		if ( $filter_speed ) {
			$grid_data[] = 'data-transition-duration="'. $filter_speed .'"';
		}
		if ( ! empty( $filter_has_active_cat ) ) {
			$grid_data[] = 'data-filter=".cat-'. $filter_active_category .'"';
		}
	}

	// Grid data attributes with filter disabled
	else {

		if ( $is_isotope ) {
			$grid_data[] = 'data-transition-duration="0.0"';
		}

	}

	// Entry columns class
	$columns_class = vcex_get_grid_column_class( $atts );

	// Media classes
	$media_classes = array( 'staff-entry-media', 'entry-media', 'wpex-clr' );
	if ( $img_filter ) {
		$media_classes[] = wpex_image_filter_class( $img_filter );
	}
	if ( $img_hover_style ) {
		$media_classes[] = wpex_image_hover_classes( $img_hover_style );
	}
	if ( 'none' != $overlay_style ) {
		$media_classes[] = wpex_overlay_classes( $overlay_style );
	}
	$media_classes = implode( ' ', $media_classes );

	// Entry CSS class
	if ( $entry_css ) {
		$entry_css = vc_shortcode_custom_css_class( $entry_css );
	}

	// Content Design
	$content_style = array(
		'color'   => $content_color,
		'opacity' => $content_opacity,
	);
	if ( ! $content_css ) {
		if ( isset( $content_background ) ) {
			$content_style['background'] = $content_background;
		}
		if ( isset( $content_padding ) ) {
			$content_style['padding'] = $content_padding;
		}
		if ( isset( $content_margin ) ) {
			$content_style['margin'] = $content_margin;
		}
		if ( isset( $content_border ) ) {
			$content_style['border'] = $content_border;
		}
	} else {
		$content_css = vc_shortcode_custom_css_class( $content_css );
	}
	$content_style = vcex_inline_style( $content_style );

	// Heading Design
	if ( 'true' == $title ) {

		$heading_style = vcex_inline_style( array(
			'margin'         => $content_heading_margin,
			'font_size'      => $content_heading_size,
			'color'          => $content_heading_color,
			'font_weight'    => $content_heading_weight,
			'text_transform' => $content_heading_transform,
			'line_height'    => $content_heading_line_height,
		) );

	}

	// Heading Link style
	$heading_link_style = vcex_inline_style( array(
		'color' => $content_heading_color,
	) );

	// Position design
	if ( 'true' == $position ) {
		$position_style = vcex_inline_style( array(
			'font_size'   => $position_size,
			'font_weight' => $position_weight,
			'margin'      => $position_margin,
			'color'       => $position_color,
		) );
	}

	// Categories design
	if ( 'true' == $show_categories ) {
		$categories_style = vcex_inline_style( array(
			'padding'   => $categories_margin,
			'font_size' => $categories_font_size,
			'color'     => $categories_color,
		) );
		$categories_classes = 'staff-entry-categories wpex-clr';
		if ( $categories_color ) {
			$categories_classes .= ' wpex-child-inherit-color';
		}
	}

	// Excerpt style
	if ( 'true' == $excerpt ) {
		$excerpt_style = vcex_inline_style( array(
			'font_size' => $content_font_size,
		) );
	}

	// Social links style
	if ( 'true' == $social_links ) {
		$social_links_inline_css = vcex_inline_style( array(
			'padding' => $social_links_margin,
		) );
	}

	// Readmore design
	if ( 'true' == $read_more ) {

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

		// Readmore hover data
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

	// Apply filters
	$wrap_classes  = apply_filters( 'vcex_staff_grid_wrap_classes', $wrap_classes ); // @todo deprecate?
	$grid_classes  = apply_filters( 'vcex_staff_grid_classes', $grid_classes );
	$grid_data     = apply_filters( 'vcex_staff_grid_data_attr', $grid_data );

	// Convert arrays into strings
	$wrap_classes  = implode( ' ', $wrap_classes );
	$grid_classes  = implode( ' ', $grid_classes );
	$grid_data     = $grid_data ? ' '. implode( ' ', $grid_data ) : '';

	// VC filter
	$wrap_classes = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, $wrap_classes, 'vcex_staff_grid', $atts );

	// Begin shortcode output
	$output .= '<div class="'. esc_attr( $wrap_classes ) .'"'. vcex_get_unique_id( $unique_id ) .'>';

		// Display filter links
		if ( 'true' == $filter && ! empty( $filter_terms ) ) {

			// Sanitize all text
			$all_text = $all_text ? $all_text : __( 'All', 'total' );

			// Filter button classes
			$filter_button_classes = wpex_get_button_classes( $filter_button_style, $filter_button_color );

			// Filter font size
			$filter_style = vcex_inline_style( array(
				'font_size' => $filter_font_size,
			) );

			$filter_classes = 'vcex-staff-filter vcex-filter-links clr';
			if ( 'yes' == $center_filter ) {
				$filter_classes .= ' center';
			}

			$output .= '<ul class="'. $filter_classes .'"'. $filter_style .'>';

				if ( 'true' == $filter_all_link ) {

					$output .= '<li';
						if ( ! $filter_has_active_cat ) {
							$output .= ' class="active"';
						}
					$output .= '>';

						$output .= '<a href="#" data-filter="*" class="'. $filter_button_classes .'"><span>'. esc_html( $all_text ) .'</span></a>';

					$output .= '</li>';

				}

				foreach ( $filter_terms as $term ) :

					$output .= '<li class="filter-cat-'. $term->term_id;

						if ( $filter_active_category == $term->term_id ) {
							$output .= ' active';
						}

					$output .= '">';

					$output .= '<a href="#" data-filter=".cat-'. $term->term_id .'" class="'. $filter_button_classes .'">';
						$output .= $term->name;
					$output .= '</a></li>';

				endforeach;

				if ( $vcex_after_grid_filter = apply_filters( 'vcex_after_grid_filter', '', $atts ) ) {
					$output .= $vcex_after_grid_filter; // @todo remove vcex_after_grid_filter filter and instead use a filer to override the whole output of the filter.
				}

			$output .= '</ul>';

		}

		$output .= '<div class="'. $grid_classes .'"'. $grid_data .'>';

			// Define counter var to clear floats
			$count=0;

			// Start loop
			while ( $wpex_query->have_posts() ) :

				// Get post from query
				$wpex_query->the_post();

				// Post Data
				$atts['post_id']        = get_the_ID();
				$atts['post_permalink'] = wpex_get_permalink( $atts['post_id'] );
				$atts['post_title']     = get_the_title();
				$atts['post_esc_title'] = wpex_get_esc_title();
				$atts['post_excerpt']   = '';

				// Generate post Excerpt
				if ( 'true' == $excerpt || 'true' == $thumb_lightbox_caption ) {
					$atts['post_excerpt'] = wpex_get_excerpt( array(
						'length'  => $excerpt_length,
						'context' => 'vcex_staff_grid',
					) );
				}

				// Add to the counter var
				$count++;

				// Add classes to the entries
				$entry_classes = array( 'staff-entry' );
				$entry_classes[] = $columns_class;
				if ( $content_alignment ) {
					$entry_classes[] = 'text'. $content_alignment;
				}
				if ( 'false' == $columns_responsive ) {
					$entry_classes[] = 'nr-col';
				} else {
					$entry_classes[] = 'col';
				}
				if ( $count ) {
					$entry_classes[] = 'col-'. $count;
				}
				if ( 'true' == $read_more ) {
					$entry_classes[] = 'has-readmore';
				}
				if ( $css_animation && 'none' != $css_animation ) {
					$entry_classes[] = $css_animation;
				}
				if ( $is_isotope ) {
					$entry_classes[] = 'vcex-isotope-entry';
				}
				if ( 'no_margins' == $grid_style ) {
					$entry_classes[] = 'vcex-no-margin-entry';
				}

				$output .= '<div '. vcex_grid_get_post_class( $entry_classes, $atts['post_id'] ) .'>';

					$output .= '<div class="staff-entry-inner entry-inner clr';
						
						if ( $entry_css ) {
							$output .= ' '. $entry_css;
						}

					$output .= '">';

						/*** Featured Media ***/
						$media_output = '';
						if ( 'true' == $entry_media && has_post_thumbnail() ) :

							$media_output .= '<div class="'. $media_classes .'">';

								// Thumbnail with link
								if ( ! in_array( $thumb_link, array( 'none', 'nowhere' ) ) ) :

									// Lightbox link
									if ( $thumb_link == 'lightbox' ) :

										// Lightbox data
										if ( 'lightbox' == $thumb_link ) {

											// Lightbox link
											$atts['lightbox_link'] = wpex_get_lightbox_image();

											// Lightbox data
											$atts['lightbox_data'] = array();
											if ( $lightbox_skin ) {
												$atts['lightbox_data'][] = 'data-skin="'. $lightbox_skin .'"';
											}
											if ( 'true' == $thumb_lightbox_title ) {
												$atts['lightbox_data'][] = 'data-title="'. $atts['post_esc_title'] .'"';
											} else {
												$atts['lightbox_data'][] = 'data-show_title="false"';
											}
											if ( 'true' == $thumb_lightbox_caption && $atts['post_excerpt'] ) {
												$atts['lightbox_data'][] = 'data-caption="'. str_replace( '"',"'", $atts['post_excerpt'] ) .'"';
											}
											$lightbox_data = ' '. implode( ' ', $atts['lightbox_data'] );
										}

										$media_output .= '<a href="'. $atts['lightbox_link'] .'" title="'. $atts['post_esc_title'] .'" class="staff-entry-media-link'. $lightbox_single_class .'"'. $lightbox_data .'>';

									// Standarad post link
									else :

										$media_output .= '<a href="'. $atts['post_permalink'] .'" title="'. $atts['post_esc_title'] .'" class="staff-entry-media-link"'. vcex_html( 'target_attr', $link_target ) .'>';

									endif;

								endif; // End link tag

									// Define thumbnail args
									$thumbnail_args = array(
										'size'          => $img_size,
										'crop'          => $img_crop,
										'width'         => $img_width,
										'height'        => $img_height,
										'apply_filters' => 'vcex_staff_grid_thumbnail_args',
									);

									// Add data-no-lazy to prevent conflicts with WP-Rocket
									if ( $is_isotope ) {
										$thumbnail_args['attributes'] = array( 'data-no-lazy' => 1 );
									}

									// Output post thumbnail
									$media_output .= wpex_get_post_thumbnail( $thumbnail_args );

									$media_output .= wpex_get_entry_media_after( 'vcex_staff_grid' );

								// Close link and output inside overlay HTML
								if ( ! in_array( $thumb_link, array( 'none', 'nowhere' ) ) ) :

									// Inner Overlay
									if ( $overlay_style && 'none' != $overlay_style ) {
										ob_start();
										wpex_overlay( 'inside_link', $overlay_style, $atts );
										$media_output .= ob_get_clean();
									}

									$media_output .= '</a>';

								endif;

								// Outside Overlay
								if ( $overlay_style && 'none' != $overlay_style ) {
									ob_start();
									wpex_overlay( 'outside_link', $overlay_style, $atts );
									$media_output .= ob_get_clean();
								}

							$media_output .= '</div>';

						endif;

						$output .= apply_filters( 'vcex_staff_grid_media', $media_output, $atts );

						/*** Content ***/
						if ( 'true' == $title
							|| 'true' == $excerpt
							|| 'true' == $read_more
							|| 'true' == $position
							|| 'true' == $show_categories
							|| 'true' == $social_links
						) :

							$output .= '<div class="staff-entry-details entry-details wpex-clr';

								if ( $content_css ) {
									$output .= ' '. $content_css;
								}

								$output .= '"';

								$output .= $content_style;

							$output .= '>';

								// Open equal height container
								// Equal height div
								if ( $equal_heights_grid ) {
									$output .= '<div class="match-height-content">';
								}

								/*** Title ***/
								$title_output = '';
								if ( 'true' == $title ) :

									// Open title tag
									$title_output .= '<' . $title_tag . ' class="staff-entry-title entry-title"'. $heading_style .'>';

										// Display title and link to post
										if ( 'post' == $title_link ) :

											$title_output .= '<a href="'. $atts['post_permalink'] .'"'. $heading_link_style .''. vcex_html( 'target_attr', $link_target ) .'>'. wp_kses_post( $atts['post_title'] ) .'</a>';

										// Display title and link to lightbox
										elseif ( 'lightbox' == $title_link ) :

											// Load lightbox script
											vcex_enque_style( 'ilightbox', $lightbox_skin );

											$title_output .= '<a href="'. wpex_get_lightbox_image() .'"'. $heading_link_style .' class="wpex-lightbox">'. wp_kses_post( $atts['post_title'] ) .'</a>';

										// Display title without link
										else :

											$title_output .= wp_kses_post( $atts['post_title'] );

										endif;

									// Close title tag
									$title_output .= '</' . $title_tag . '>';

								endif;

								$output .= apply_filters( 'vcex_staff_grid_title', $title_output, $atts );

								/*** Position ***/
								$position_output = '';
								if ( 'true' == $position
									&& $get_position = get_post_meta( $atts['post_id'], 'wpex_staff_position', true )
								) :

									$position_output .= '<div class="staff-entry-position"' . $position_style . '>';

										$position_output .= apply_filters( 'wpex_staff_entry_position', wp_kses_post( $get_position ) );

									$position_output .= '</div>';

								endif;

								$output .= apply_filters( 'vcex_staff_grid_position', $position_output, $atts );

								/*** Categories ***/
								$categories_output = '';
								if ( 'true' == $show_categories ) :

									$categories_output .= '<div class="'. $categories_classes .'"'. $categories_style .'>';

										if ( 'true' == $show_first_category_only ) {

											$categories_output .= wpex_get_first_term_link( $atts['post_id'], 'staff_category' );

										} else {

											$categories_output .= wpex_get_list_post_terms( 'staff_category', true, true );

										}

									$categories_output .= '</div>';

								endif;

								$output .= apply_filters( 'vcex_staff_grid_categories', $categories_output, $atts );

								/*** Excerpt ***/
								$excerpt_output = '';
								if ( 'true' == $excerpt && $atts['post_excerpt'] ) :

									$excerpt_output .= '<div class="staff-entry-excerpt entry-excerpt wpex-clr"'. $excerpt_style .'>';

										$excerpt_output .= $atts['post_excerpt'];

									$excerpt_output .= '</div>';

								endif;

								$output .= apply_filters( 'vcex_staff_grid_excerpt', $excerpt_output, $atts );

								/*** Social Links ***/
								$social_output = '';
								if ( 'true' == $social_links ) :

									$social_output .= '<div class="staff-entry-social-links wpex-clr"'. $social_links_inline_css .'>';

										$social_output .= wpex_get_staff_social( array(
											'style'     => $social_links_style,
											'font_size' => $social_links_size,
										) );

									$social_output .= '</div>';

								endif;

								$output .= apply_filters( 'vcex_staff_grid_social', $social_output, $atts );

								/*** Read More ***/
								$readmore_output = '';
								if ( 'true' == $read_more && $read_more_text ) :

									$readmore_output .= '<div class="staff-entry-readmore-wrap entry-readmore-wrap clr">';

										$attrs = array(
											'href'   => $atts['post_permalink'],
											'class'  => $readmore_classes,
											'rel'    => 'bookmark',
											'target' => $link_target,
											'style'  => $readmore_style,
										);

										if ( $readmore_hover_data ) {
											$attrs['data-wpex-hover'] = $readmore_hover_data;
										}

										$readmore_output .= '<a ' . wpex_parse_attrs( $attrs ) . '>';

											$readmore_output .= esc_html( $read_more_text );

											if ( 'true' == $readmore_rarr ) {
												$readmore_output .= '<span class="vcex-readmore-rarr">'. wpex_element( 'rarr' ) .'</span>';
											}

										$readmore_output .= '</a>';

									$readmore_output .= '</div>';

								endif;

								$output .= apply_filters( 'vcex_staff_grid_readmore', $readmore_output, $atts );

								// Close Equal height div
								if ( $equal_heights_grid ) {

									$output .= '</div>';

								}

							$output .= '</div>'; // Entry details

						endif; // End staff entry details check

					$output .= '</div>'; // Entry inner

				$output .= '</div>'; // Entry

				// Reset counter
				if ( $count == $columns ) {
					$count=0;
				}

			endwhile;

		$output .= '</div>';

		// Display pagination if enabled
		if ( 'true' == $pagination ) {

			$output .= wpex_pagination( $wpex_query, false );

		}

	$output .= '</div>';

	// Reset the post data to prevent conflicts with WP globals
	wp_reset_postdata();

	// Echo output
	echo $output;

// If no posts are found display message
else :

	echo vcex_no_posts_found_message( $atts );

// End post check
endif;