<?php
/**
 * Visual Composer Feature Box
 *
 * @package Total WordPress Theme
 * @subpackage VC Templates
 * @version 4.5.4.2
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
if ( ! function_exists( 'vc_map_get_attributes' ) || ! function_exists( 'vc_build_link' ) ) {
	vcex_function_needed_notice();
	return;
}

// Output 
$output = '';

// Get and extract shortcode attributes
$atts = vcex_vc_map_get_attributes( 'vcex_feature_box', $atts );
extract( $atts );

// Sanitize vars
$image         = $image ? $image : 'dummy';
$heading_type  = $heading_type ? $heading_type : 'h2';
$equal_heights = $video ? 'false' : $equal_heights;

// Add style
$wrap_style = vcex_inline_style( array(
	'padding'    => $padding,
	'background' => $background,
	'border'     => $border,
	'text_align' => $text_align,
) );

// Classes
$wrap_classes = array( 'vcex-module', 'vcex-feature-box', 'clr' );
if ( $visibility ) {
	$wrap_classes[] = $visibility;
}
if ( $css_animation && 'none' != $css_animation ) {
	$wrap_classes[] = vcex_get_css_animation( $css_animation );
}
if ( $classes ) {
	$wrap_classes[] = vcex_get_extra_class( $classes );
}
if ( $style ) {
	$wrap_classes[] = $style;
}
if ( 'true' == $equal_heights ) {
	$wrap_classes[] = 'vcex-feature-box-match-height';
}
if ( $tablet_widths ) {
	$wrap_classes[] = 'tablet-fullwidth-columns';
}
if ( $phone_widths ) {
	$wrap_classes[] = 'phone-fullwidth-columns';
}
if ( 'true' == $content_vertical_align && 'true' != $equal_heights ) {
	$wrap_classes[] = 'v-align-middle';
}
$wrap_classes = implode( ' ', $wrap_classes );
$wrap_classes = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, $wrap_classes, 'vcex_feature_box', $atts );

$output .= '<div class="' . esc_attr( $wrap_classes ) . '"' . vcex_get_unique_id( $unique_id ) . $wrap_style . '>';

	// Image/Video check
	if ( $image || $video ) {

		// Image args
		$image_args = array(
			'attachment' => $image,
			'size'       => $img_size,
			'width'      => $img_width,
			'height'     => $img_height,
			'crop'       => $img_crop,
		);

		// Add classes
		$inner_classes = array( 'vcex-feature-box-media', 'clr' );
		if ( 'true' == $equal_heights ) {
			$inner_classes[] = 'vcex-match-height';
		}
		$inner_classes = implode( ' ', $inner_classes );

		// Media style
		$media_style = vcex_inline_style( array(
			'width' => $media_width,
		) );

		$output .= '<div class="' . $inner_classes . '"' . $media_style . '>';

			// Display Video
			if ( $video ) {

				$output .= '<div class="responsive-video-wrap">' . wp_oembed_get( esc_url( $video ) ) . '</div>';

			// Display Image
			} elseif ( $image ) {

				// Get image
				$image_alt = strip_tags( get_post_meta( $image, '_wp_attachment_image_alt', true ) );

				// Image inline CSS
				$image_style = '';
				if ( $img_border_radius ) {
					$image_style = vcex_inline_style( array(
						'border_radius' => $img_border_radius,
					) );
					$image_args['style'] = 'border-radius:' . $img_border_radius . ';';
				}

				// Image classes
				$image_classes = array( 'vcex-feature-box-image' );
				if ( $img_filter ) {
					$image_classes[] = wpex_image_filter_class( $img_filter );
				}
				if ( $img_hover_style && 'true' != $equal_heights ) {
					$image_classes[] = wpex_image_hover_classes( $img_hover_style );
				}

				// Image URL
				if ( $image_url || 'image' == $image_lightbox ) {

					// Standard URL
					$link     = vc_build_link( $image_url );
					$a_href   = isset( $link['url'] ) ? $link['url'] : '';
					$a_title  = isset( $link['title'] ) ? $link['title'] : '';
					$a_target = isset( $link['target'] ) ? $link['target'] : '';
					$a_target = ( false !== strpos( $a_target, 'blank' ) ) ? ' target="_blank"' : '';

					// Image lightbox
					$data_attributes = '';

					if ( $image_lightbox ) {

						vcex_enque_style( 'ilightbox' );
						if ( 'image' == $image_lightbox || 'self' == $image_lightbox ) {
							$a_href = wpex_get_lightbox_image( $image );
							$image_classes[] = 'wpex-lightbox';
							$data_attributes .= ' data-type="image"';
						} elseif ( 'url' == $image_lightbox ) {
							$image_classes[] = ' wpex-lightbox';
							$data_attributes .= ' data-type="iframe"';
						} elseif ( 'auto-detect' == $image_lightbox ) {
							$image_classes[] = ' wpex-lightbox-autodetect';
						} elseif ( 'video_embed' == $image_lightbox ) {
							$a_href = wpex_get_video_embed_url( $a_href );
							$image_classes[] = ' wpex-lightbox';
							$data_attributes .= ' data-type="iframe"';
							$data_attributes .= ' data-options="iframeType:\'video\'"';
						} elseif ( 'html5' == $image_lightbox ) {
							$poster = wp_get_attachment_image_src( $img_id, 'large');
							$poster = $poster[0];
							$image_classes[] = ' wpex-lightbox';
							$data_attributes .= ' data-type="video"';
							$data_attributes .= ' data-options="width:848, height:480, html5video: { webm: \'' . $lightbox_video_html5_webm . '\', poster: \'' . $poster . '\' }"';
						} elseif ( 'quicktime' == $image_lightbox ) {
							$image_classes[] = ' wpex-lightbox';
							$data_attributes .= ' data-type="video"';
							$data_attributes .= ' data-options="width:1920,height:1080"';
						} else {
						   $data_attributes .= ' data-options="smartRecognition:true,width:1920,height:1080"';
						}

					}

				}

				// Turn image classes into string
				$image_classes = implode( ' ', $image_classes );

				// Open link if defined
				if ( ! empty( $a_href ) ) {

					$output .= '<a href="' . esc_url( $a_href ) . '" title="' . esc_attr( $a_title ) . '" class="vcex-feature-box-image-link ' . esc_attr( $image_classes ) . '"' . $image_style . '' . $data_attributes . '' . $a_target . '>';


				// Link isn't defined open div
				} else {

					$output .= '<div class="' . $image_classes . '" ' . $image_style . '>';

				}

				// Display image
				$output .= wpex_get_post_thumbnail( $image_args );

				// Close link
				if ( isset( $a_href ) && $a_href ) {

					$output .= '</a>';

				// Link not defined, close div
				} else {

					$output .= '</div>';

				}

				} // End video check

			$output .= '</div>'; // close media

		} // $video or $image check

		// Content area
		if ( $content || $heading ) {

			$add_classes = 'vcex-feature-box-content clr';

			if ( 'true' == $equal_heights ) {

				$add_classes .= ' vcex-match-height';
			}

			$content_style = vcex_inline_style( array(
				'width'      => $content_width,
				'background' => $content_background
			) );

			$output .= '<div class="' . $add_classes . '"' . $content_style . '>';

			if ( $content_padding ) {

				$style = vcex_inline_style( array(
					'padding' => $content_padding,
				) );

				$output .= '<div class="vcex-feature-box-padding-container clr"' . $style . '>';

			}

			// Heading
			if ( $heading ) {

				// Load custom font
				if ( $heading_font_family ) {
					wpex_enqueue_google_font( $heading_font_family );
				}

				// Classes
				$heading_attrs = array(
					'class' => 'vcex-feature-box-heading',
				);

				// Heading style
				$heading_attrs['style'] = vcex_inline_style( array(
					'font_family'    => $heading_font_family,
					'color'          => $heading_color,
					'font_size'      => $heading_size,
					'font_weight'    => $heading_weight,
					'margin'         => $heading_margin,
					'letter_spacing' => $heading_letter_spacing,
					'text_transform' => $heading_transform,
				), false );

				// Get responsive data
				if ( $responsive_data = vcex_get_module_responsive_data( $heading_size, 'font_size' ) ) {
					$heading_attrs['data-wpex-rcss'] = $responsive_data;
				}

				// Heading URL
				$a_href = '';
				if ( $heading_url && '||' != $heading_url ) {
					$link     = vc_build_link( $heading_url );
					$a_href   = isset( $link['url'] ) ? $link['url'] : '';
					$a_title  = isset( $link['title'] ) ? $link['title'] : '';
					$a_target = isset( $link['target'] ) ? $link['target'] : '';
					$a_target = ( false !== strpos( $a_target, 'blank' ) ) ? ' target="_blank"' : '';
				}

				if ( isset( $a_href ) && $a_href ) {

					$output .= '<a href="' . esc_url( do_shortcode( $a_href ) ) . '" title="' . esc_attr( do_shortcode( $a_title ) ) . '"class="vcex-feature-box-heading-link"' . $a_target . '>';

				}

				$output .= wpex_parse_html( $heading_type, $heading_attrs, do_shortcode( $heading ) );

				if ( isset( $a_href ) && $a_href ) {
					 $output .= '</a>';
				}

			} //  End heading

			// Text
			if ( $content ) {

				$content_attrs = array(
					'class' => 'vcex-feature-box-text clr'
				);

				$content_attrs['style'] = vcex_inline_style( array(
					'font_size'   => $content_font_size,
					'color'       => $content_color,
					'font_weight' => $content_font_weight,
				), false );

				// Get responsive data
				if ( $responsive_data = vcex_get_module_responsive_data( $content_font_size, 'font_size' ) ) {
					$content_attrs['data-wpex-rcss'] = $responsive_data;
				}

				// Sanitize content
				$content = do_shortcode( wpautop( wp_kses_post( $content ) ) );

				// Output content
				$output .= wpex_parse_html( 'div', $content_attrs, $content );

			} // End content

			// Close padding container
			if ( $content_padding ) {

				$output .= '</div>';

			}

		$output .= '</div>';

	} // End content + Heading wrap

$output .= '</div>';

echo $output;