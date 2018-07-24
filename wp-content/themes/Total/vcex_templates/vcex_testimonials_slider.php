<?php
/**
 * Visual Composer Testimonials Slider
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

// Deprecated Attributes
if ( ! empty( $atts['term_slug'] ) && empty( $atts['include_categories']) ) {
	$atts['include_categories'] = $atts['term_slug'];
}

// Get shortcode attribtues
$atts = vc_map_get_attributes( 'vcex_testimonials_slider', $atts );

// Define non-vc attributes
$atts['post_type'] = 'testimonials';
$atts['taxonomy']  = 'testimonials_category';
$atts['tax_query'] = '';

// Extract shortcode atts
extract( $atts );

// Posts per page
$posts_per_page = $count;

// Build the WordPress query
$wpex_query = vcex_build_wp_query( $atts );

// Output posts
if ( $wpex_query->have_posts() ) :

	// Define and sanitize variables
	$slideshow = vc_is_inline() ? 'false' : $slideshow;

	// Add Style - OLD deprecated params.
	$wrap_style = '';
	if ( ! $css ) {
		$wrap_style = array();
		if ( isset( $atts['background'] ) ) {
			$wrap_style['background_color'] = $atts['background'];
		}
		if ( isset( $atts['background_image'] ) ) {
			$wrap_style['background_image'] = wp_get_attachment_url( $atts['background_image'] ) ;
		}
		if ( isset( $atts['padding_top'] ) ) {
			$wrap_style['padding_top'] = $atts['padding_top'];
		}
		if ( isset( $atts['padding_bottom'] ) ) {
			$wrap_style['padding_bottom'] = $atts['padding_bottom'];
		}
		$wrap_style = vcex_inline_style( $wrap_style );
	}

	// Slide Style
	$slide_style = vcex_inline_style( array(
		'font_size'   => $font_size,
		'font_weight' => $font_weight,
	) );

	// Image classes
	$img_classes = '';
	if ( ( $img_width || $img_height ) || 'wpex_custom' != $img_size ) {
		$img_classes .= 'remove-dims';
	}

	// Define wrap attributes
	$wrap_attrs = array(
		'id'    => $unique_id,
		'class' => '',
	);

	// Wrap classes
	$wrap_classes = array( 'vcex-module', 'vcex-testimonials-fullslider', 'vcex-flexslider-wrap', 'wpex-fs-21px' );
	if ( $skin ) {
		$wrap_classes[] = $skin .'-skin';
	}
	if ( 'true' == $direction_nav ) {
		$wrap_classes[] = 'has-arrows';
	}
	if ( 'true' == $control_thumbs ) {
		$wrap_classes[] = 'has-thumbs';
	}
	if ( 'true' == $control_nav ) {
		$wrap_classes[] = 'has-controlnav';
	}
	if ( ! empty( $background_style ) && ! empty( $background_image ) ) {
		$wrap_classes[] = 'vcex-background-'. $background_style;
	}
	if ( $css_animation ) {
		$wrap_classes[] = vcex_get_css_animation( $css_animation );
	}
	if ( $visibility ) {
		$wrap_classes[] = $visibility;
	}
	if ( $css ) {
		$wrap_classes[] = vc_shortcode_custom_css_class( $css );
	}
	if ( $classes ) {
		$wrap_classes[] = vcex_get_extra_class( $classes );
	}

	// Get responsive data
	if ( $responsive_data = vcex_get_module_responsive_data( $atts ) ) {
		$wrap_attrs['data-wpex-rcss'] = $responsive_data;
	}

	// Turn class array into string
	$wrap_classes = implode( ' ', $wrap_classes );
	$wrap_attrs['class'] = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, $wrap_classes, 'vcex_testimonials_slider', $atts );

	// Wrap data
	$slider_data = '';
	$slider_data .= ' data-fade-arrows="false"';
	$slider_data .= ' data-arrows="true"';
	if ( 'true' == $control_nav || 'true' == $direction_nav ) {
		$slider_data .= ' data-buttons="true"';
	} else {
		$slider_data .= ' data-buttons="false"';
	}
	if ( 'false' != $loop ) {
		$slider_data .= ' data-loop="true"';
	}
	if ( 'false' == $slideshow ) {
		$slider_data .= ' data-auto-play="false"';
	}
	if ( in_array( $animation, array( 'fade', 'fade_slides' ) ) ) {
		$slider_data .= ' data-fade="true"';
	}
	if ( $slideshow && $slideshow_speed ) {
		$slider_data .= ' data-auto-play-delay="'. $slideshow_speed .'"';
	}
	if ( 'true' == $control_thumbs ) {
		$slider_data .= ' data-thumbnails="true"';
	}
	if ( $animation_speed ) {
		$slider_data .= ' data-animation-speed="'. intval( $animation_speed ) .'"';
	}
	if ( 'false' == $auto_height ) {
		$slider_data .= ' data-auto-height="false"';
	} elseif ( $height_animation ) {
		$height_animation = intval( $height_animation );
		$height_animation = 0 == $height_animation ? '0.0' : $height_animation;
		$slider_data .= ' data-height-animation-duration="'. $height_animation .'"';
	}

	// Image settings & style
	$img_style = array( 'border_radius' => $img_border_radius );
	if ( 'wpex_custom' == $img_size && $img_width ) {
		$img_style['width'] = $img_width; // for retina support
	}
	$img_style = vcex_inline_style( $img_style, false );

	// Start output
	$output .= '<div ' . wpex_parse_attrs( $wrap_attrs ) . '>';

		$output .= '<div class="wpex-slider slider-pro"'. $slider_data .'>';

			$output .= '<div class="wpex-slider-slides sp-slides">';

				// Store posts in an array for use with the thumbnails later
				$posts_cache = array();

				// Loop through posts
				while ( $wpex_query->have_posts() ) :

					// Get post from query
					$wpex_query->the_post();

					// Get post data and make available in $atts array
					$atts['post_id']           = get_the_ID();
					$atts['post_content']      = get_the_content();
					$atts['post_meta_author']  = get_post_meta( $atts['post_id'], 'wpex_testimonial_author', true );
					$atts['post_meta_company'] = get_post_meta( $atts['post_id'], 'wpex_testimonial_company', true );
					$atts['post_meta_url']     = get_post_meta( $atts['post_id'], 'wpex_testimonial_url', true );

					// Store post ids
					$posts_cache[] = $atts['post_id'];

					// Testimonial start
					if ( '' != $atts['post_content'] ) :

						$output .= '<div class="wpex-slider-slide sp-slide">';

							$output .= '<div class="vcex-testimonials-fullslider-inner textcenter clr">';

								// Author avatar
								$avatar_output = '';
								if ( 'yes' == $display_author_avatar && has_post_thumbnail( $atts['post_id'] ) ) {

									$avatar_output .= '<div class="vcex-testimonials-fullslider-avatar">';

										// Output thumbnail
										$avatar_output .= wpex_get_post_thumbnail( array(
											'size'          => $img_size,
											'crop'          => $img_crop,
											'width'         => $img_width,
											'height'        => $img_height,
											'style'         => $img_style,
											'class'         => $img_classes,
											'apply_filters' => 'vcex_testimonials_slider_thumbnail_args',
											'filter_arg1'   => $atts,
										) );

									$avatar_output .= '</div>';

								}

								$output .= apply_filters( 'vcex_testimonials_slider_avatar', $avatar_output, $atts );

								// Custom Excerpt
								$excerpt_output = '';
								if ( 'true' == $excerpt ) :

									if ( 'true' == $read_more ) {

										$read_more_text = $read_more_text ? $read_more_text : __( 'read more', 'total' );
										
										$read_more_link = '&hellip;<a href="'. get_permalink() .'" title="'. esc_attr( $read_more_text ) .'">'. esc_html( $read_more_text ) .'<span>&rarr;</span></a>';

									} else {

										$read_more_link = '&hellip;';

									}

									$excerpt_output .= '<div class="entry wpex-fw-300 clr"'. $slide_style .'>';

										$excerpt_output .= wpex_get_excerpt( array(
											'length'               => $excerpt_length,
											'more'                 => $read_more_link,
											'context'              => 'vcex_testimonials_slider',
											'custom_excerpts_more' => true, // force readmore on custom excerpts
										) );

									$excerpt_output .= '</div>';

								// Full content
								else :

									$excerpt_output .= '<div class="entry clr"' . $slide_style . '>';

										$excerpt_output .= wpex_the_content( get_the_content(), 'vcex_testimonials_slider' );

									$excerpt_output .= '</div>';
								
								endif;

								$output .= apply_filters( 'vcex_testimonials_slider_excerpt', $excerpt_output, $atts );

								// Details name
								$meta_output = '';
								if ( 'yes' == $display_author_name
									|| 'yes' == $display_author_company
									|| 'true' == $rating
								) :

									$meta_output .= '<div class="vcex-testimonials-fullslider-author wpex-fs-14px clr">';

										// Display author name
										$meta_author_output = '';
										if ( 'yes' == $display_author_name ) {
											$meta_author_output .= wp_kses_post( $atts['post_meta_author'] );
										}
										$meta_output .= apply_filters( 'vcex_testimonials_slider_meta_author', $meta_author_output, $atts );

										// Display company
										$meta_company_output = '';
										if ( $atts['post_meta_company'] && 'yes' == $display_author_company ) {

											if ( $atts['post_meta_url'] ) {

												$meta_company_output .= '<a href="' . esc_url( $atts['post_meta_url'] ) . '" class="vcex-testimonials-fullslider-company display-block" title="' . esc_attr( $atts['post_meta_company'] ) . '" target="_blank">';

													$meta_company_output .= wp_kses_post( $atts['post_meta_company'] );

												$meta_company_output .= '</a>';

											} else {

												$meta_company_output .= '<div class="vcex-testimonials-fullslider-company">';

													$meta_company_output .= wp_kses_post( $atts['post_meta_company'] );

												$meta_company_output .= '</div>';

											}

										}
										$meta_output .= apply_filters( 'vcex_testimonials_slider_meta_company', $meta_company_output, $atts );

										// Display rating
										$meta_rating_output = '';
										if ( 'true' == $rating ) {
											$atts['post_rating'] = wpex_get_star_rating( '', $atts['post_id'] );
											if ( $atts['post_rating'] ) {
												$meta_rating_output .= '<div class="vcex-testimonials-fullslider-rating clr">'. $atts['post_rating'] .'</div>';
											}
										}
										$meta_output .= apply_filters( 'vcex_testimonials_slider_meta_rating', $meta_rating_output, $atts );

									$meta_output .= '</div>';

								endif;

								$output .= apply_filters( 'vcex_testimonials_slider_meta', $meta_output, $atts );

							$output .= '</div>';

						$output .= '</div>';

					endif;

				endwhile;

			$output .= '</div>';

			if ( 'true' == $control_thumbs ) :

				$output .= '<div class="wpex-slider-thumbnails sp-nc-thumbnails">';

					foreach ( $posts_cache as $post_id ) :

						// Output thumbnail image
						$output .= wpex_get_post_thumbnail( array(
							'attachment'    => get_post_thumbnail_id( $post_id ),
							'crop'          => $control_thumbs_crop,
							'width'         => $control_thumbs_width,
							'height'        => $control_thumbs_height,
							'class'         => 'sp-nc-thumbnail',
							'apply_filters' => 'vcex_testimonials_slider_nav_thumbnail_args',
							'filter_arg1'   => $atts,
						) );

					endforeach;

				$output .= '</div>';

			endif;

		$output .= '</div>';

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