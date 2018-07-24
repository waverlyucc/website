<?php
/**
 * Visual Composer Image Carousel
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
if ( ! function_exists( 'vc_map_get_attributes' ) || ! function_exists( 'vc_shortcode_custom_css_class' ) ) {
	vcex_function_needed_notice();
	return;
}

// Define output var
$output = '';

// Get and extract shortcode attributes
extract( vc_map_get_attributes( 'vcex_image_carousel', $atts ) );

// Output var
$output = '';

// Get images from post gallery
if ( 'true' == $post_gallery ) {
	$image_ids = wpex_get_gallery_ids();
}

// Get images based on Real Media folder
elseif ( defined( 'RML_VERSION' ) && $rml_folder ) {
	$rml_query = new WP_Query( array(
		'post_status'    => 'inherit',
		'posts_per_page' => $posts_per_page,
		'post_type'      => 'attachment',
		'orderby'        => 'rml', // Order by custom order of RML
		'rml_folder'     => $rml_folder,
		'fields'         => 'ids',
	) );
	if ( $rml_query->have_posts() ) {
		$image_ids = $rml_query->posts;
	}
}

// If there aren't any images lets display a notice
if ( empty( $image_ids ) ) {
	return;
}

// Otherwise if there are images lets turn it into an array
else {

	// Get image ID's
	if ( ! is_array( $image_ids ) ) {
		$attachment_ids = explode( ',', $image_ids );
	} else {
		$attachment_ids = $image_ids;
	}

}

// Remove duplicate images
$attachment_ids = array_unique( $attachment_ids );

// Sanitize attachments to make sure they exist
$attachment_ids = array_filter( $attachment_ids, 'wpex_attachment_exists' );

if ( ! $attachment_ids ) {
	return;
}

// Turn links into array
if ( $custom_links ) {
	$custom_links = explode( ',', $custom_links );
} else {
	$custom_links = array();
}

// Count items
$attachment_ids_count = count( $attachment_ids );
$custom_links_count   = count( $custom_links );

// Add empty values to custom_links array for images without links
if ( $attachment_ids_count > $custom_links_count ) {
	$count = 0;
	foreach( $attachment_ids as $val ) {
		$count++;
		if ( ! isset( $custom_links[$count] ) ) {
			$custom_links[$count] = '#';
		}
	}
}

// New custom links count
$custom_links_count = count( $custom_links );

// Remove extra custom links
if ( $custom_links_count > $attachment_ids_count ) {
	$count = 0;
	foreach( $custom_links as $key => $val ) {
		$count ++;
		if ( $count > $attachment_ids_count ) {
			unset( $custom_links[$key] );
		}
	}
}

// Set links as the keys for the images
$images_links_array = array_combine( $attachment_ids, $custom_links );

// Return if no images
if ( ! $images_links_array ) {
	return;
}

// Randomize images
if ( 'true' == $randomize_images ) {
	$orderby = 'rand';
} else {
	$orderby = 'post__in';
}

// Lets create a new Query for the image carousel
$wpex_query = new WP_Query( array(
	'post_type'      => 'attachment',
	//'post_mime_type'    => 'image/jpeg,image/gif,image/jpg,image/png',
	'post_status'    => 'any',
	'posts_per_page' => -1,
	'paged'          => NULL,
	'no_found_rows'  => true,
	'post__in'       => $attachment_ids,
	'orderby'        => $orderby,
) );

// Display carousel if there are images
if ( $wpex_query->have_posts() ) :

	// Main Classes
	$wrap_classes = array( 'vcex-module', 'wpex-carousel', 'wpex-carousel-images', 'clr', 'owl-carousel' );

	// Carousel style
	if ( $style && 'default' != $style ) {
		$wrap_classes[] = $style;
		$arrows_position = ( 'no-margins' == $style && 'default' == $arrows_position ) ? 'abs' : $arrows_position;
	}

	// Arrow classes
	if ( 'true' == $arrows ) {
		$arrows_style = $arrows_style ? $arrows_style : 'default';
		$wrap_classes[] = 'arrwstyle-'. $arrows_style;
		if ( $arrows_position && 'default' != $arrows_position ) {
			$wrap_classes[] = 'arrwpos-'. $arrows_position;
		}
	}

	// Rounded
	if ( 'yes' == $rounded_image ) {
		$wrap_classes[] = 'wpex-rounded-images';
	}

	// CSS animation class
	if ( $css_animation && 'none' != $css_animation ) {
		$wrap_classes[] = vcex_get_css_animation( $css_animation );
	}

	// Custom classes
	if ( $classes ) {
		$wrap_classes[] = vcex_get_extra_class( $classes );
	}

	// Entry classes
	$entry_classes = 'wpex-carousel-slide';
	if ( $entry_css ) {
		$entry_classes .= ' '. vc_shortcode_custom_css_class( $entry_css );
	}

	// Image Classes
	$img_classes = array( 'wpex-carousel-entry-media', 'clr' );
	if ( $overlay_style ) {
		$img_classes[] = wpex_overlay_classes( $overlay_style );
	}
	if ( $img_filter ) {
		$img_classes[] = wpex_image_filter_class( $img_filter );
	}
	if ( $img_hover_style ) {
		$img_classes[] = wpex_image_hover_classes( $img_hover_style );
	}

	// Lightbox css/js/classes
	if ( 'lightbox' == $thumbnail_link ) {
		vcex_enque_style( 'ilightbox', $lightbox_skin );
		$wrap_classes[] = 'wpex-carousel-lightbox';
	}

	// Items to scroll fallback for old setting
	if ( 'page' == $items_scroll ) {
		$items_scroll = $items;
	}

	// Title design
	if ( 'yes' == $title ) {
		$heading_style = vcex_inline_style( array(
			'margin'         => $content_heading_margin,
			'text_transform' => $content_heading_transform,
			'font_weight'    => $content_heading_weight,
			'font_size'      => $content_heading_size,
			'color'          => $content_heading_color,
		) );
	}

	// Content Design
	if ( 'yes' == $title || 'yes' == $caption ) {

		// Defined var
		$content_style = '';

		// Non css_editor fields
		$content_style = array(
			'text_align' => $content_alignment,
			'color'      => $content_color,
			'font_size'  => $content_font_size,
		);

		// Deprecated fields
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
			$content_css = vc_shortcode_custom_css_class( $content_css ); // Custom CSS class
		}

		// Generate inline style
		if ( $content_style ) {
			$content_style = vcex_inline_style( $content_style );
		}
		
	}

	// Sanitize carousel data to prevent errors
	$arrows                 = wpex_esc_attr( $arrows, 'true' );
	$dots                   = wpex_esc_attr( $dots, 'false' );
	$auto_play              = wpex_esc_attr( $auto_play, 'false' );
	$infinite_loop          = wpex_esc_attr( $infinite_loop, 'true' );
	$auto_height            = wpex_esc_attr( $auto_height, 'false' );
	$auto_width             = wpex_esc_attr( $auto_width, 'false' );
	$center                 = wpex_esc_attr( $center, 'false' );
	$items                  = wpex_intval( $items, 4 );
	$items_scroll           = wpex_intval( $items_scroll, 1 );
	$timeout_duration       = wpex_intval( $timeout_duration, 5000 );
	$items_margin           = wpex_intval( $items_margin, 15 );
	$items_margin           = ( 'no-margins' == $style ) ? 0 : $items_margin;
	$tablet_items           = wpex_intval( $tablet_items, 3 );
	$mobile_landscape_items = wpex_intval( $mobile_landscape_items, 2 );
	$mobile_portrait_items  = wpex_intval( $mobile_portrait_items, 1 );
	$animation_speed        = wpex_intval( $animation_speed, 150 );

	// Prevent auto play in visual composer
	if ( wpex_vc_is_inline() ) {
		$auto_play = 'false';
	}

	// Implode arrays
	$wrap_classes = implode( ' ', $wrap_classes );
	$img_classes  = implode( ' ', $img_classes );

	// Apply filters
	$wrap_classes = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, $wrap_classes, 'vcex_image_carousel', $atts );

	// Open wrapper for auto height
	if ( 'true' == $auto_height ) {

		$output .= '<div class="owl-wrapper-outer">';

	}

	// Begin output
	$output .= '<div'. vcex_html( 'id_attr', $unique_id ) .' class="'. esc_attr( $wrap_classes ) .'" data-items="'. $items .'" data-slideby="'. $items_scroll .'" data-nav="'. $arrows .'" data-dots="'. $dots .'" data-autoplay="'. $auto_play .'" data-loop="'. $infinite_loop .'" data-autoplay-timeout="'. $timeout_duration .'" data-center="'. $center .'" data-margin="'. intval( $items_margin ) .'" data-items-tablet="'. $tablet_items .'" data-items-mobile-landscape="'. $mobile_landscape_items .'" data-items-mobile-portrait="'. $mobile_portrait_items .'" data-smart-speed="'. $animation_speed .'" data-auto-height="'. $auto_height .'" data-auto-width="'. $auto_width .'">';
		
		// Start counter used for lightbox
		$count=0;

		// Loop through images
		while ( $wpex_query->have_posts() ) :

			// Add to counter
			$count++;

			// Get post from query
			$wpex_query->the_post();

			// Attachment VARS
			$atts['post_id']      = get_the_ID();
			$atts['post_data']    = wpex_get_attachment_data( $atts['post_id'] );
			$atts['post_link']    = $atts['post_data']['url'];
			$atts['post_alt']     = esc_attr( $atts['post_data']['alt'] );
			$atts['post_caption'] = $atts['post_data']['caption'];
			$atts['post_video']   = apply_filters( 'vcex_image_carousel_video_support', false ) ? $atts['post_data']['video'] : null;

			// Save link target for overlays
			$atts['link_target'] = $custom_links_target;

			// Get original attachment ID - fix for WPML
			$post_id = $atts['post_id'];
			if ( $custom_links_count && WPEX_WPML_ACTIVE ) {
				global $sitepress;
				if ( $sitepress ) {
					$default_lang = $sitepress->get_default_language();
					$post_id = icl_object_id( $post_id, 'attachment', false, $default_lang );
				}
			}

			// Pluck array to see if item has custom link
			$atts['post_url'] = $images_links_array[$post_id];

			// Validate URl
			$atts['post_url'] = ( '#' !== $atts['post_url'] ) ? esc_url( $atts['post_url'] ) : '';

			// Get correct title
			if ( 'title' == $title_type || ! $title_type ) {
				$attachment_title = get_the_title();
			} elseif ( 'alt' == $title_type ) {
				$attachment_title = esc_attr( $atts['post_data']['alt'] );
			}
			
			// Image output
			if ( ! $atts['post_video'] ) {
				$image_output = wpex_get_post_thumbnail( array(
					'attachment'    => $atts['post_id'],
					'crop'          => $img_crop,
					'size'          => $img_size,
					'width'         => $img_width,
					'height'        => $img_height,
					'alt'           => $atts['post_alt'],
					'attributes'    => array( 'data-no-lazy' => 1 ),
					'apply_filters' => 'vcex_image_carousel_thumbnail_args',
					'filter_arg1'   => $atts,
				) );
			}

			// Set classes var so we can add new ones if needed on a per-item basis
			$classes = $entry_classes;

			// Add video class
			if ( $atts['post_video'] ) {
				$classes .= ' owl-item-video';
			}

			// Begin item output
			$output .= '<div class="' . esc_attr( $classes ) . '">';

				$output .= '<figure class="' . esc_attr( $img_classes ) . '">';

					// Video
					if ( $atts['post_video'] ) {

						$output .= '<a href="' . set_url_scheme( $atts['post_video'] ) . '" class="owl-video"></a>';

						//$output .= '<div class="wpex-carousel-video responsive-video-wrap">' . wpex_video_oembed( $atts['post_video'] ) . '</div>';

					}

					// Image thumbnail
					else {

						// Add custom links to attributes for use with the overlay styles
						if ( 'custom_link' == $thumbnail_link && $atts['post_url'] ) {
							$atts['overlay_link'] = $atts['post_url'];
						}

						// Lightbox
						if ( 'lightbox' == $thumbnail_link ) {

							// Main link attributes
							$link_attrs = array(
								'href'  => wpex_get_lightbox_image( $atts['post_id'] ),
								'title' => $atts['post_alt'],
								'class' => 'wpex-carousel-entry-img',
							);

							// Main link lightbox attributes
							if ( 'lightbox' == $thumbnail_link ) {
								$link_attrs['class']     .= ' wpex-carousel-lightbox-item';
								$link_attrs['data-title'] = wp_strip_all_tags( $atts['post_alt'] );
								$link_attrs['data-count'] = $count;
								$link_attrs['data-type']  = 'image';
								if ( $lightbox_skin ) {
									$link_attrs['data-skin'] = $lightbox_skin;
								}
								if ( $lightbox_path ) {
									if ( 'disabled' == $lightbox_path ) {
										$link_attrs['data-thumbnails'] = 'false';
									} else {
										$link_attrs['data-path'] = $lightbox_path;
									}
								}

								if ( ! in_array( $lightbox_title, array( 'false', 'none' ) ) ) {
									if ( 'title' == $lightbox_title ) {
										$link_attrs['data-title'] = wp_strip_all_tags( get_the_title( $atts['post_id'] ) );
									} elseif ( 'alt' == $lightbox_title ) {
										$link_attrs['data-title'] = wp_strip_all_tags( $atts['post_alt'] );
									}
								} else {
									$link_attrs['data-show_title']  = 'false';
								}

								// Caption data
								if ( 'false' != $lightbox_caption && $attachment_caption = get_post_field( 'post_excerpt', $atts['post_id'] ) ) {
									$link_attrs['data-caption'] = str_replace( '"',"'", $attachment_caption );
								}

							}

							$output .= '<a ' . wpex_parse_attrs( $link_attrs ) . '>';

								$output .= $image_output;

								ob_start();
								wpex_overlay( 'inside_link', $overlay_style, $atts );
								$output .= ob_get_clean();

							$output .= '</a>';


						}

						// Custom Link
						elseif ( 'custom_link' == $thumbnail_link && $atts['post_url'] ) {

							$output .= '<a href="'. esc_url( $atts['post_url'] ) .'" class="wpex-carousel-entry-img"' . vcex_html( 'target_attr', $custom_links_target ) . '>';

								$output .= $image_output;

								ob_start();
								wpex_overlay( 'inside_link', $overlay_style, $atts );
								$output .= ob_get_clean();

							$output .= '</a>';

						}

						// No link
						else {

							$output .= $image_output;

							ob_start();
							wpex_overlay( 'inside_link', $overlay_style, $atts );
							$output .= ob_get_clean();

						}

						// Outside link overlay html
						ob_start();
						wpex_overlay( 'outside_link', $overlay_style, $atts );
						$output .= ob_get_clean();

					} // end video/image check

				$output .= '</figure>';

				// Display details
				if ( ( 'yes' == $title && $attachment_title ) || (  'yes' == $caption && $atts['post_caption'] ) ) :

					$classes = 'wpex-carousel-entry-details clr';
					
					if ( $content_css ) {
						$classes .= ' '. $content_css;
					}

					$output .= '<div class="' . esc_attr( $classes ) . '"' . $content_style . '>';

						// Display title
						if ( 'yes' == $title && $attachment_title ) :

							$output .= '<div class="wpex-carousel-entry-title entry-title"' . $heading_style . '>';

								$output .= wp_kses_post( $attachment_title );

							$output .= '</div>';

						endif;

						// Display caption
						if ( 'yes' == $caption && $atts['post_caption'] ) :

							$output .= '<div class="wpex-carousel-entry-excerpt clr">';

								$output .= wp_kses_post( $atts["post_caption"] );

							$output .= '</div>';

						endif;
					
					$output .= '</div>';

				endif;

			$output .= '</div>';

		endwhile;

	$output .= '</div>';

	// Close wrap for single item auto height
	if ( 'true' == $auto_height ) {

		$output .= '</div>';
		
	}

	// Reset the post data to prevent conflicts with WP globals
	wp_reset_postdata();

	// Output shortcode html
	echo $output;

// End Query
endif;