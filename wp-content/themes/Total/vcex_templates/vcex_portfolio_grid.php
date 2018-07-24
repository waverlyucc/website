<?php
/**
 * Visual Composer Portfolio Grid
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

// Define output var
$output = '';

// Deprecated Attributes
if ( ! empty( $atts['term_slug'] ) && empty( $atts['include_categories']) ) {
	$atts['include_categories'] = $atts['term_slug'];
}

// Get shortcode attributes based on vc_lean_map => This makes sure no attributes are empty
$atts = vc_map_get_attributes( 'vcex_portfolio_grid', $atts );

// Add base to attributes
$atts['base'] = 'vcex_portfolio_grid';

// Define user-generated attributes
$atts['post_type'] = 'portfolio';
$atts['taxonomy']  = 'portfolio_category';
$atts['tax_query'] = '';

// Build the WordPress query
$wpex_query = vcex_build_wp_query( $atts );

// Output posts
if ( $wpex_query->have_posts() ) :

	// IMPORTANT: Fallback required from VC update when params are defined as empty
	// AKA - set things to enabled by default
	$atts['entry_media'] = empty( $atts['entry_media'] ) ? 'true' : $atts['entry_media'];
	$atts['title']       = empty( $atts['title'] ) ? 'true' : $atts['title'];
	$atts['excerpt']     = empty( $atts['excerpt'] ) ? 'true' : $atts['excerpt'];
	$atts['read_more']   = empty( $atts['read_more'] ) ? 'true' : $atts['read_more'];

	// Declare main vars and parse data
	$grid_data                  = array();
	$wrap_classes               = array( 'vcex-module', 'vcex-portfolio-grid-wrap', 'wpex-clr' );
	$grid_classes               = array( 'wpex-row', 'vcex-portfolio-grid', 'wpex-clr', 'entries' );
	$is_isotope                 = false;
	$atts['excerpt_length']     = $atts['excerpt_length'] ? $atts['excerpt_length'] : '30';
	$atts['css_animation']      = vcex_get_css_animation( $atts['css_animation'] );
	$atts['css_animation']      = ( 'true' == $atts['filter'] ) ? false : $atts['css_animation'];
	$atts['equal_heights_grid'] = ( 'true' == $atts['equal_heights_grid'] && $atts['columns'] > '1' ) ? true : false;
	$atts['overlay_style']      = $atts['overlay_style'] ? $atts['overlay_style'] : 'none';
	$atts['title_tag']          = apply_filters( 'vcex_grid_default_title_tag', $atts['title_tag'], $atts );
	$atts['title_tag']          = $atts['title_tag'] ? $atts['title_tag'] : 'h2';

	// Load lightbox scripts
	if ( 'lightbox' == $atts['thumb_link'] || 'lightbox_gallery' == $atts['thumb_link'] ) {
		wpex_enqueue_ilightbox_skin( $atts['lightbox_skin'] );
	}

	// Enable Isotope
	if ( 'true' == $atts['filter'] || 'masonry' == $atts['grid_style'] || 'no_margins' == $atts['grid_style'] ) {
		$is_isotope = true;
	}

	// No need for masonry if not enough columns and filter is disabled
	if ( 'true' != $atts['filter'] && 'masonry' == $atts['grid_style'] ) {
		$post_count = count( $wpex_query->posts );
		if ( $post_count <= $atts['columns'] ) {
			$is_isotope = false;
		}
	}

	// Get filter taxonomy
	if ( 'true' == $atts['filter'] ) {
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
				$atts['filter_active_category'] = $active_cat_query_arg;
			}

			// Check if filter active cat exists on current page
			$filter_has_active_cat = in_array( $atts['filter_active_category'], wp_list_pluck( $filter_terms, 'term_id' ) ) ? true : false;

			// Add show on load animation when active filter is enabled to prevent double animation
			if ( $filter_has_active_cat ) {
				$grid_classes[] = 'wpex-show-on-load';
			}

		} else {
			$filter = false; // no terms
		}

	}

	// Wrap classes
	if ( $atts['visibility'] ) {
		$wrap_classes[] = $atts['visibility'];
	}
	if ( $atts['classes'] ) {
		$wrap_classes[] = vcex_get_extra_class( $atts['classes'] );
	}

	// Main grid classes
	if ( $atts['columns_gap'] ) {
		$grid_classes[] = 'gap-'. $atts['columns_gap'];
	}
	if ( $atts['equal_heights_grid'] ) {
		$grid_classes[] = 'match-height-grid';
	}
	if ( $is_isotope ) {
		$grid_classes[] = 'vcex-isotope-grid';
	}
	if ( 'no_margins' == $atts['grid_style'] ) {
		$grid_classes[] = 'vcex-no-margin-grid';
	}
	if ( 'left_thumbs' == $atts['single_column_style'] ) {
		$grid_classes[] = 'left-thumbs';
	}
	if ( 'lightbox' == $atts['thumb_link'] || 'lightbox_gallery' == $atts['thumb_link'] ) {
		if ( 'true' == $atts['thumb_lightbox_gallery'] ) {
			$grid_classes[] = ' lightbox-group';
			if ( $atts['lightbox_skin'] ) {
				$grid_data[] = 'data-skin="'. $atts['lightbox_skin'] .'"';
			}
			$lightbox_single_class = ' wpex-lightbox-group-item';
		} else {
			$lightbox_single_class = ' wpex-lightbox';
		}
	}

	// Grid data attributes
	if ( 'true' == $atts['filter'] ) {
		if ( 'fitRows' == $atts['masonry_layout_mode'] ) {
			$grid_data[] = 'data-layout-mode="fitRows"';
		}
		if ( $atts['filter_speed'] ) {
			$grid_data[] = 'data-transition-duration="'. esc_attr( $atts['filter_speed'] ) .'"';
		}
		if ( ! empty( $filter_has_active_cat ) ) {
			$grid_data[] = 'data-filter=".cat-' . esc_attr( $atts['filter_active_category'] ) . '"';
		}
	} else {
		$grid_data[] = 'data-transition-duration="0.0"';
	}

	// Entry inner classes
	$inner_classes = 'portfolio-entry-inner entry-inner wpex-clr';
	if ( $atts['entry_css'] ) {
		$inner_classes .= ' '. vc_shortcode_custom_css_class( $atts['entry_css'] );
	}
	$columns_class = vcex_get_grid_column_class( $atts );

	// Media classes
	if ( 'true' == $atts['entry_media'] ) {
		$media_classes = array( 'portfolio-entry-media', 'entry-media', 'wpex-clr' );
		if ( $atts['img_filter'] ) {
			$media_classes[] = wpex_image_filter_class( $atts['img_filter'] );
		}
		if ( $atts['img_hover_style'] ) {
			$media_classes[] = wpex_image_hover_classes( $atts['img_hover_style'] );
		}
		if ( 'none' != $atts['overlay_style'] ) {
			$media_classes[] = wpex_overlay_classes( $atts['overlay_style'] );
		}
		$media_classes = implode( ' ', $media_classes );
	}

	// Content Design
	$content_style = array(
		'color'   => $atts['content_color'],
		'opacity' => $atts['content_opacity'],
	);
	if ( ! $atts['content_css'] ) {
		if ( isset( $atts['content_background'] ) ) {
			$content_style['background'] = $atts['content_background'];
		}
		if ( isset( $atts['content_padding'] ) ) {
			$content_style['padding'] = $atts['content_padding'];
		}
		if ( isset( $atts['content_margin'] ) ) {
			$content_style['margin'] = $atts['content_margin'];
		}
		if ( isset( $atts['content_border'] ) ) {
			$content_style['border'] = $atts['content_border'];
		}
		$content_css = $atts['content_css'];
	} else {
		$content_css = vc_shortcode_custom_css_class( $atts['content_css'] );
	}
	$content_style = vcex_inline_style( $content_style );

	// Heading style
	if ( 'true' == $atts['title'] ) {

		// Heading Design
		$heading_style = vcex_inline_style( array(
			'margin'         => $atts['content_heading_margin'],
			'font_size'      => $atts['content_heading_size'],
			'color'          => $atts['content_heading_color'],
			'font_weight'    => $atts['content_heading_weight'],
			'text_transform' => $atts['content_heading_transform'],
			'line_height'    => $atts['content_heading_line_height'],
		) );

		// Heading Link style
		$heading_link_style = vcex_inline_style( array(
			'color' => $atts['content_heading_color'],
		) );

	}

	// Categories style
	if ( 'true' == $atts['show_categories'] ) {
		$categories_style = vcex_inline_style( array(
			'margin'    => $atts['categories_margin'],
			'font_size' => $atts['categories_font_size'],
			'color'     => $atts['categories_color'],
		) );
		$categories_classes = 'portfolio-entry-categories entry-categories wpex-clr';
		if ( $atts['categories_color'] ) {
			$categories_classes .= ' wpex-child-inherit-color';
		}
	}

	// Excerpt style
	if ( 'true' == $atts['excerpt'] ) {
		$excerpt_style = vcex_inline_style( array(
			'font_size' => $atts['content_font_size'],
		) );
	}

	// Readmore design
	if ( 'true' == $atts['read_more'] ) {

		// Read more text
		$read_more_text = $atts['read_more_text'] ? $atts['read_more_text'] : esc_html__( 'read more', 'total' );

		// Readmore classes
		$readmore_classes = wpex_get_button_classes( $atts['readmore_style'], $atts['readmore_style_color'] );

		// Readmore style
		$readmore_inline_style = vcex_inline_style( array(
			'background'    => $atts['readmore_background'],
			'color'         => $atts['readmore_color'],
			'font_size'     => $atts['readmore_size'],
			'padding'       => $atts['readmore_padding'],
			'border_radius' => $atts['readmore_border_radius'],
			'margin'        => $atts['readmore_margin'],
		), false );

		// Readmore data
		$readmore_hover_data = array();
		if ( $atts['readmore_hover_background'] ) {
			$readmore_hover_data['background'] = $atts['readmore_hover_background'];
		}
		if ( $atts['readmore_hover_color'] ) {
			$readmore_hover_data['color'] = $atts['readmore_hover_color'];
		}
		if ( $readmore_hover_data ) {
			$readmore_hover_data = json_encode( $readmore_hover_data );
		}

	}

	// Apply filters before implode
	$wrap_classes = apply_filters( 'vcex_portfolio_grid_wrap_classes', $wrap_classes ); // @todo remove deprecated
	$grid_classes = apply_filters( 'vcex_portfolio_grid_classes', $grid_classes );
	$grid_data    = apply_filters( 'vcex_portfolio_grid_data_attr', $grid_data );

	// Convert arrays into strings
	$wrap_classes = implode( ' ', $wrap_classes );
	$grid_classes = implode( ' ', $grid_classes );
	$grid_data    = $grid_data ? ' '. implode( ' ', $grid_data ) : '';

	// VC filters
	$wrap_classes = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, $wrap_classes, 'vcex_portfolio_grid', $atts );

	// Begin output
	$output .= '<div class="'. esc_attr( $wrap_classes ) .'"'. vcex_get_unique_id( $atts['unique_id'] ) .'>';
	
		// Display filter links
		if ( 'true' == $atts['filter'] && ! empty( $filter_terms ) ) :

			// Sanitize all text
			$all_text = $atts['all_text'] ? $atts['all_text'] : __( 'All', 'total' );

			// Filter button classes
			$filter_button_classes = wpex_get_button_classes( $atts['filter_button_style'], $atts['filter_button_color'] );

			// Filter font size
			$filter_style = vcex_inline_style( array(
				'font_size' => $atts['filter_font_size'],
			) );

			$filter_classes = 'vcex-portfolio-filter vcex-filter-links clr';
			if ( 'yes' == $atts['center_filter'] ) {
				$filter_classes .= ' center';
			}

			$output .= '<ul class="'. $filter_classes .'"'. $filter_style .'>';
				
				if ( 'true' == $atts['filter_all_link'] ) {

					$output .= '<li';

						if ( empty( $filter_has_active_cat ) ) {
							$output .= ' class="active"';
						}

					$output .= '>';

						$output .= '<a href="#" data-filter="*" class="'. $filter_button_classes .'"><span>'. esc_html( $all_text ) .'</span></a>';

					$output .= '</li>';

				}

				foreach ( $filter_terms as $term ) :

					// Open Filter link
					$output .= '<li class="filter-cat-'. $term->term_id;

						if ( $atts['filter_active_category'] == $term->term_id ) {
							$output .= ' active';
						}

					$output .= '">';

						// Add main filter cat link
						$output .= '<a href="#" data-filter=".cat-'. $term->term_id .'" class="'. $filter_button_classes .'">';
							$output .= esc_html( $term->name );
						$output .= '</a>';

					$output .= '</li>';

				endforeach;

				if ( $vcex_after_grid_filter = apply_filters( 'vcex_after_grid_filter', '', $atts ) ) { 
					
					$output .= wp_kses_post( $vcex_after_grid_filter );

				}

			$output .= '</ul>';

		endif; // End filter

		$output .= '<div class="'. $grid_classes .'"'. $grid_data .'>';

			// Define counter var to clear floats
			$count=0;

			// Start loop
			while ( $wpex_query->have_posts() ) :

				// Get post from query
				$wpex_query->the_post();

				// Post Data
				$atts['post_id']            = get_the_ID();
				$atts['post_permalink']     = wpex_get_permalink( $atts['post_id'] );
				$atts['post_title']         = get_the_title();
				$atts['post_esc_title']     = esc_attr( $atts['post_title'] );
				$atts['post_video']         = ( 'true' == $atts['featured_video'] ) ? wpex_get_post_video_html() : '';
				$atts['post_excerpt']       = '';
				$atts['has_post_thumbnail'] = has_post_thumbnail( $atts['post_id'] );

				// Post Excerpt
				if ( 'true' == $atts['excerpt'] || 'true' == $atts['thumb_lightbox_caption'] ) {

					$atts['post_excerpt'] = wpex_get_excerpt( array(
						'length'  => $atts['excerpt_length'],
						'context' => 'vcex_portfolio_grid',
					) );

				}

				// Readmore link - allow it to be filterable
				if ( 'true' == $atts['read_more'] ) {
					$atts['readmore_link'] = $atts['post_permalink'];
				}

				// Categories tax
				if ( 'true' == $atts['show_categories'] ) {
					$atts['show_categories_tax'] = 'portfolio_category';
				}

				// Apply filters to attributes
				$latts = apply_filters( 'vcex_shortcode_loop_atts', $atts );

				// Does entry have details?
				if ( 'true' == $latts['title']
					|| 'true' == $latts['show_categories']
					|| ( 'true' == $latts['excerpt'] && $latts['post_excerpt'] )
					|| 'true' == $latts['read_more']
				) {
					$entry_has_details = true;
				} else {
					$entry_has_details = false;
				}

				// Add to the counter var
				$count++;

				// Add classes to the entries
				$entry_classes = array( 'portfolio-entry' );
				if ( $entry_has_details ) {
					$entry_classes[] = 'entry-has-details';
				}
				$entry_classes[] = $columns_class;

				if ( 'false' == $atts['columns_responsive'] ) {
					$entry_classes[] = 'nr-col';
				} else {
					$entry_classes[] = 'col';
				}
				if ( $count ) {
					$entry_classes[] = 'col-'. $count;
				}
				if ( $atts['css_animation'] ) {
					$entry_classes[] = $atts['css_animation'];
				}
				if ( $is_isotope ) {
					$entry_classes[] = 'vcex-isotope-entry';
				}
				if ( 'no_margins' == $atts['grid_style'] ) {
					$entry_classes[] = 'vcex-no-margin-entry';
				}
				if ( $latts['content_alignment'] ) {
					$entry_classes[] = 'text'. $latts['content_alignment'];
				}

				// Get and save lightbox data for use with media and title
				if ( ( $latts['has_post_thumbnail'] && ( 'lightbox' == $latts['thumb_link'] || 'lightbox_gallery' == $latts['thumb_link'] ) )
					|| 'lightbox' == $latts['title_link']
				) {

					// Define vars
					$latts['lightbox_data'] = array();
					$lightbox_gallery_imgs  = null;

					// Save correct lightbox class
					$latts['lightbox_class'] = $lightbox_single_class;

					// Gallery
					if ( 'lightbox_gallery' == $latts['thumb_link'] ) {
						if ( $lightbox_gallery_imgs = wpex_get_gallery_images( $latts['post_id'], 'lightbox' ) ) {
							$latts['lightbox_class']  = ' wpex-lightbox-gallery';
							$latts['lightbox_data'][] = 'data-gallery="'. implode( ',', $lightbox_gallery_imgs ) .'"';
						}
					}

					/*
					// Include captions and titles in the lightbox gallery
					// @todo - requires re-factor of iLightbox script
					if ( 'lightbox_gallery' == $latts['thumb_link'] ) {
						if ( $lightbox_gallery_ids = wpex_get_gallery_ids( $latts['post_id'], 'lightbox' ) ) {
							$latts['lightbox_class']  = ' wpex-lightbox-gallery';
							$lightbox_gallery_array = array();
							foreach ( $lightbox_gallery_ids as $id ) {
								$lightbox_gallery_array[$id] = array(
									'url'     => wpex_get_lightbox_image( $id ),
								);
								if ( $attachment_title = wpex_get_attachment_data( $id, 'title' ) ) {
									$lightbox_gallery_array[$id]['title'] = $attachment_title;
								}
								if ( $attachment_caption = wpex_get_attachment_data( $id, 'caption' ) ) {
									$lightbox_gallery_array[$id]['caption'] = $attachment_caption;
								}
							}
							$latts['lightbox_data'][] = 'data-gallery=\'' . json_encode( $lightbox_gallery_array ) . '\'';
						}
					}
					*/

					// Generate lightbox image
					$lightbox_image = wpex_get_lightbox_image();

					// Get lightbox link
					$latts['lightbox_link'] = $lightbox_image;

					// Add lightbox data attributes
					if ( $atts['lightbox_skin'] ) {
						$latts['lightbox_data'][] = 'data-skin="'. $atts['lightbox_skin'] .'"';
					}
					if ( 'true' == $atts['thumb_lightbox_title'] ) {
						$latts['lightbox_data'][] = 'data-title="'. wpex_get_esc_title() .'"';
					} else {
						$latts['lightbox_data'][] = 'data-show_title="false"';
					}
					if ( 'true' == $atts['thumb_lightbox_caption'] && $latts['post_excerpt'] ) {
						$latts['lightbox_data'][] = 'data-caption="'. str_replace( '"',"'", $latts['post_excerpt'] ) .'"';
					}

					// Check for video
					if ( ! $lightbox_gallery_imgs
						&& $oembed_video_url = wpex_get_post_video_oembed_url( $atts['post_id'] )
					) {
						$embed_url = wpex_get_video_embed_url( $oembed_video_url );
						if ( $embed_url ) {
							$latts['lightbox_link']                 = $embed_url;
							$latts['lightbox_data']['data-type']    = 'data-type="iframe"';
							$latts['lightbox_data']['data-options'] = 'data-options="iframeType:\'video\',thumbnail:\''. $lightbox_image .'\'"';
						}
					}

					$lightbox_data = ! empty( $latts['lightbox_data']  ) ? ' '. implode( ' ', $latts['lightbox_data'] ) : '';

				}

				// Begin entry output
				$output .= '<div '. vcex_grid_get_post_class( $entry_classes, $atts['post_id'] ) .'>';

					$output .= '<div class="'. $inner_classes .'">';

						// Entry Media
						$media_output = '';
						if ( 'true' == $latts['entry_media'] ) {

							/* Video
							-------------------------------------------------------------------------------*/
							if ( $latts['post_video'] ) {

								$media_output .= '<div class="portfolio-entry-media portfolio-featured-video entry-media wpex-clr">';

									$media_output .= $latts['post_video'];

								$media_output .= '</div>';

							/* Featured Image
							-------------------------------------------------------------------------------*/
							} elseif ( $latts['has_post_thumbnail'] ) {

								$media_output .= '<div class="'. $media_classes .'">';

									// Open link tag if thumblink does not equal nowhere
									if ( 'nowhere' != $latts['thumb_link'] ) {

										// Lightbox
										if ( 'lightbox' == $latts['thumb_link'] || 'lightbox_gallery' == $latts['thumb_link'] ) {

											$media_output .= '<a href="'. $latts["lightbox_link"] .'" title="'. $latts['post_esc_title'] .'" class="portfolio-entry-media-link'. $latts['lightbox_class'] .'"'. $lightbox_data .'>';

										// Standard post link
										} else {

											$media_output .= '<a href="'. $latts['post_permalink'] .'" title="'. $latts['post_esc_title'] .'" class="portfolio-entry-media-link"'. vcex_html( 'target_attr', $latts['link_target'] ) .'>';

										}

									} // End Opening link

									// Define thumbnail args
									$thumbnail_args = array(
										'width'         => $latts['img_width'],
										'height'        => $latts['img_height'],
										'crop'          => $latts['img_crop'],
										'size'          => $latts['img_size'],
										'class'         => 'portfolio-entry-img',
										'apply_filters' => 'vcex_grid_thumbnail_args', // @todo rename filter to vcex_portfolio_grid_thumbnail_args
										'filter_arg1'   => $latts,
									);
									
									// Add data-no-lazy to prevent conflicts with WP-Rocket
									if ( $is_isotope ) {
										$thumbnail_args['attributes'] = array( 'data-no-lazy' => 1 );
									}

									// Display post thumbnail
									$media_output .= wpex_get_post_thumbnail( $thumbnail_args );

									// Inner link overlay HTML
									if ( $latts['overlay_style'] && 'none' != $latts['overlay_style'] ) {
										ob_start();
										wpex_overlay( 'inside_link', $latts['overlay_style'], $latts );
										$media_output .= ob_get_clean();
									}

									// Entry media after
									$media_output .= wpex_get_entry_media_after( 'vcex_portfolio_grid' );

									// Close link tag
									if ( 'nowhere' != $latts['thumb_link'] ) {
										$media_output .= '</a>';
									}

									// Outer link overlay HTML
									if ( $latts['overlay_style'] && 'none' != $latts['overlay_style'] ) {
										ob_start();
										wpex_overlay( 'outside_link', $latts['overlay_style'], $latts );
										$media_output .= ob_get_clean();
									}

								$media_output .= '</div>';

							} // End has_post_thumbnail check

							$output .= apply_filters( 'vcex_portfolio_grid_media', $media_output, $atts );


						} // End media

						// Display content if needed
						if ( $entry_has_details ) :
							
							// Entry details start
							$output .= '<div class="portfolio-entry-details entry-details wpex-clr';
								
								if ( $content_css ) {
									$output .= ' '. $content_css;
								}

								$output .= '"';

								$output .= $content_style;

							$output .= '>';

								// Equal height div
								if ( $atts['equal_heights_grid'] ) {
									$output .= '<div class="match-height-content">';
								}

								// Display title
								$title_output = '';
								if ( 'true' == $latts['title'] ) {

									$title_output .= '<'. esc_attr( $atts['title_tag'] ) .' class="portfolio-entry-title entry-title"'. $heading_style .'>';

										// Display title without link
										if ( 'nowhere' == $latts['title_link'] ) {

											$title_output .= wp_kses_post( $latts['post_title'] );

										// Link title to lightbox
										} elseif ( 'lightbox' == $latts['title_link'] ) {

											if ( $latts["lightbox_link"] ) {

												$title_output .= '<a href="'. $latts["lightbox_link"] .'" title="'. $latts['post_esc_title'] .'" class="wpex-lightbox"'. $heading_link_style . $lightbox_data .'>';
													
													$title_output .= wp_kses_post( $latts['post_title'] );
												
												$title_output .= '</a>';

											} else {

												$title_output .= wp_kses_post( $latts['post_title'] );

											}

										// Link title to post
										} else {

											$title_output .= '<a href="'. $latts['post_permalink'] .'" title="'. $latts['post_esc_title'] .'"'. $heading_link_style .''. vcex_html( 'target_attr', $latts['link_target'] ) .'>';
												
												$title_output .= wp_kses_post( $latts['post_title'] );
											
											$title_output .= '</a>';

										}

									$title_output .= '</' . esc_attr( $atts['title_tag'] ) . '>';

									$output .= apply_filters( 'vcex_portfolio_grid_title', $title_output, $atts );

								}

								// Display categories
								$categories_output = '';
								if ( 'true' == $latts['show_categories'] ) {

									$categories_output .= '<div class="'. $categories_classes .'"'. $categories_style .'>';

										// Display categories
										if ( 'true' == $latts['show_first_category_only'] ) {
										
											$categories_output .= wpex_get_first_term_link( $latts['post_id'], $latts['show_categories_tax'] );
										
										} else {

											$categories_output .= wpex_get_list_post_terms( $latts['show_categories_tax'], true, true );
										
										}

									$categories_output .= '</div>';

									$output .= apply_filters( 'vcex_portfolio_grid_categories', $categories_output, $atts );

								} // End categories

								// Display excerpt
								$excerpt_output = '';
								if ( 'true' == $latts['excerpt'] && $latts['post_excerpt'] ) {

									$excerpt_output .= '<div class="portfolio-entry-excerpt entry-excerpt wpex-clr"'. $excerpt_style .'>';
									
										$excerpt_output .= $latts['post_excerpt']; // Already sanitized
									
									$excerpt_output .= '</div>';

									$output .= apply_filters( 'vcex_portfolio_grid_excerpt', $excerpt_output, $atts );

								} // End excerpt

								// Display read more button
								$readmore_output = '';
								if ( 'true' == $latts['read_more'] ) :

									$readmore_output .= '<div class="portfolio-entry-readmore-wrap entry-readmore-wrap wpex-clr">';

										$attrs = array(
											'href'   => esc_url( $atts['readmore_link'] ),
											'class'  => $readmore_classes,
											'rel'    => 'bookmark',
											'style'  => $readmore_inline_style,
											'target' => $latts['link_target'],
										);

										if ( $readmore_hover_data ) {
											$attrs['data-wpex-hover'] = $readmore_hover_data;
										}

										$readmore_output .= '<a ' . wpex_parse_attrs( $attrs ) . '>';

											$readmore_output .= $read_more_text;

											if ( 'true' == $latts['readmore_rarr'] ) {
												$readmore_output .= ' <span class="vcex-readmore-rarr">' . wpex_element( 'rarr' ) . '</span>';
											}

										$readmore_output .= '</a>';

									$readmore_output .= '</div>';

									$output .= apply_filters( 'vcex_portfolio_grid_readmore', $readmore_output, $atts );

								endif;
								
								// Close Equal height container
								if ( $atts['equal_heights_grid'] ) {
									$output .= '</div>';
								}

							$output .= '</div>';

						endif; // End details check

					$output .= '</div>'; // Close entry inner

				$output .= '</div>'; // Close entry

				// Reset entry counter
				if ( $count == $atts['columns'] ) {
					$count=0;
				}
			
			endwhile; // End post loop

		$output .= '</div>';
		
		// Display pagination if enabled
		if ( 'true' == $atts['pagination']
			|| ( 'true' == $atts['custom_query'] && ! empty( $wpex_query->query['pagination'] ) )
		) {
			$output .= wpex_pagination( $wpex_query, false );
		}

	$output .= '</div>';

	// Reset the post data to prevent conflicts with WP globals
	wp_reset_postdata();

	// Echo output
	echo $output;

// If no posts are found display message
else :

	// Display no posts found error if function exists
	echo vcex_no_posts_found_message( $atts );

// End post check
endif;