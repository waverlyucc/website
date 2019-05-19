<?php
/**
 * Visual Composer WooCommerce Carousel
 *
 * @package Total WordPress Theme
 * @subpackage VC Templates
 * @version 4.6.5
 *
 * @todo convert output to var $output
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Helps speed up rendering in backend of VC
if ( is_admin() && ! wp_doing_ajax() ) {
	return;
}

// Deprecated Attributes
if ( ! empty( $atts['term_slug'] ) && empty( $atts['include_categories']) ) {
	$atts['include_categories'] = $atts['term_slug'];
}

// Get and extract shortcode attributes
$atts = vc_map_get_attributes( 'vcex_woocommerce_carousel', $atts );

// Define vars
$atts['post_type'] = 'product';
$atts['taxonomy']  = 'product_cat';
$atts['tax_query'] = '';

// Move featured check to the end of array to prevent issues with tax_query
if ( $atts['featured_products_only'] ) {
	$fonly = $atts['featured_products_only'];
	unset( $atts['featured_products_only'] );
	$atts['featured_products_only'] = $fonly;
}

// Extract attributes
extract( $atts );

if ( 'woo_top_rated' == $atts['orderby'] ) {
	add_filter( 'posts_clauses', array( 'WC_Shortcodes', 'order_by_rating_post_clauses' ) );
}

// Build the WordPress query
$wpex_query = vcex_build_wp_query( $atts );

if ( 'woo_top_rated' == $atts['orderby'] ) {
	remove_filter( 'posts_clauses', array( 'WC_Shortcodes', 'order_by_rating_post_clauses' ) );
}

// Output posts
if ( $wpex_query->have_posts() ) :

	// Disable auto play if there is only 1 post
	if ( '1' == count( $wpex_query->posts ) ) {
		$auto_play = false;
	}

	// Sanitize Overlay style
	$overlay_style = empty( $overlay_style ) ? 'none' : $overlay_style;

	// Items to scroll fallback for old setting
	if ( 'page' == $items_scroll ) {
		$items_scroll = $items;
	}

	// Wrap Classes
	$wrap_classes = array( 'vcex-module', 'wpex-carousel', 'wpex-carousel-woocommerce', 'owl-carousel', 'clr' );

	if ( $style && 'default' != $style ) {
		$wrap_classes[] = $style;
		$arrows_position = ( 'no-margins' == $style && 'default' == $arrows_position ) ? 'abs' : $arrows_position;
	}

	$arrows_style = $arrows_style ? $arrows_style : 'default';
	$wrap_classes[] = 'arrwstyle-'. $arrows_style;

	if ( $arrows_position && 'default' != $arrows_position ) {
		$wrap_classes[] = 'arrwpos-'. $arrows_position;
	}

	if ( $visibility ) {
		$wrap_classes[] = $visibility;
	}

	if ( $css_animation && 'none' != $css_animation ) {
		$wrap_classes[] = vcex_get_css_animation( $css_animation );
	}

	if ( $classes ) {
		$wrap_classes[] = vcex_get_extra_class( $classes );
	}

	// Entry media classes
	$media_classes = array( 'wpex-carousel-entry-media', 'clr' );
	if ( $img_filter ) {
		$media_classes[] = wpex_image_filter_class( $img_filter );
	}
	if ( $img_hover_style ) {
		$media_classes[] = wpex_image_hover_classes( $img_hover_style );
	}
	if ( $overlay_style ) {
		$media_classes[] = wpex_overlay_classes( $overlay_style );
	}
	if ( 'lightbox' == $thumbnail_link ) {
		$wrap_classes[] = 'wpex-carousel-lightbox';
		vcex_enque_style( 'ilightbox' );
	}
	$media_classes = implode( ' ', $media_classes );

	// Content Design
	$content_style = vcex_inline_style( array(
		'background' => $content_background,
		'padding'    => $content_padding,
		'margin'     => $content_margin,
		'border'     => $content_border,
		//'opacity'    => $content_opacity, Removed due to bugs
		'text_align' => $content_alignment,
	) );

	// Price style
	if ( 'true' == $price ) {
		$price_style = vcex_inline_style( array(
			'font_size' => $content_font_size,
			'color'     => $content_color,
		) );
	}

	// Title design
	if ( $title ) {
		$heading_style = vcex_inline_style( array(
			'margin'         => $content_heading_margin,
			'font_size'      => $content_heading_size,
			'font_weight'    => $content_heading_weight,
			'text_transform' => $content_heading_transform,
			'line_height'    => $content_heading_line_height,
			'color'          => $content_heading_color,
		) );
		$heading_link_style = vcex_inline_style( array(
			'color' => $content_heading_color,
		) );
	}

	// Readmore design and classes
	if ( 'true' == $read_more ) {

		// Readmore text
		$read_more_text = $read_more_text ? $read_more_text : esc_html__( 'view product', 'total' );

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
	if ( vc_is_inline() || '1' == count( $wpex_query->posts ) ) {
		$auto_play = 'false';
	}

	// Convert arrays to strings
	$wrap_classes = implode( ' ', $wrap_classes );

	// VC filter
	$wrap_classes = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, $wrap_classes, 'vcex_woocommerce_carousel', $atts );

	// @todo - move attributes into array and use wpex_parse_attrs() ?>

	<div class="woocommerce <?php echo $wrap_classes; ?>"<?php vcex_unique_id( $unique_id ); ?> data-items="<?php echo $items; ?>" data-slideby="<?php echo $items_scroll; ?>" data-nav="<?php echo $arrows; ?>" data-dots="<?php echo $dots; ?>" data-autoplay="<?php echo $auto_play; ?>" data-smart-speed="<?php echo $animation_speed; ?>" data-loop="<?php echo $infinite_loop; ?>" data-autoplay-timeout="<?php echo $timeout_duration; ?>" data-center="<?php echo $center; ?>" data-margin="<?php echo intval( $items_margin ); ?>" data-items-tablet="<?php echo $tablet_items; ?>" data-items-mobile-landscape="<?php echo $mobile_landscape_items; ?>" data-items-mobile-portrait="<?php echo $mobile_portrait_items; ?>">

		<?php
		// Loop through posts
		$count=0;
		while ( $wpex_query->have_posts() ) :
			$count++;

			// Get post from query
			$wpex_query->the_post();

			// Create new post object.
			$post = new stdClass();

			// Get post data
			$get_post = get_post(); ?>

			<div class="wpex-carousel-slide">

				<?php
				// Post VARS
				$post->ID        = $get_post->ID;
				$post->title     = $get_post->post_title;
				$post->permalink = wpex_get_permalink( $post->ID );
				$post->esc_title = wpex_get_esc_title();

				// Generate thumbnail
				$post->thumbnail = wpex_get_post_thumbnail( array(
					'size'          => $img_size,
					'crop'          => $img_crop,
					'width'         => $img_width,
					'height'        => $img_height,
					'apply_filters' => 'vcex_woocommerce_carousel_thumbnail_args',
					'filter_arg1'   => $atts,
				) ); ?>

				<?php
				// Media Wrap
				if ( has_post_thumbnail() ) : ?>

					<div class="<?php echo esc_attr( $media_classes ); ?>">

						<?php wc_get_template( 'loop/sale-flash.php' ); ?>

						<?php
						// No links
						if ( 'none' == $thumbnail_link) : ?>

							<?php echo $post->thumbnail; ?>

						<?php
						// Lightbox
						elseif ( 'lightbox' == $thumbnail_link ) : ?>

							<a href="<?php wpex_lightbox_image(); ?>" title="<?php echo $post->esc_title; ?>" class="wpex-carousel-entry-img wpex-carousel-lightbox-item" data-count="<?php echo $count; ?>" data-title="<?php echo $post->esc_title; ?>">

								<?php echo $post->thumbnail; ?>

						<?php
						// Link to post
						else : ?>

							<a href="<?php echo $post->permalink; ?>" title="<?php echo $post->esc_title; ?>" class="wpex-carousel-entry-img">

								<?php echo $post->thumbnail; ?>

						<?php endif; ?>

						<?php
						// Overlay & close link
						if ( ! in_array( $thumbnail_link, array( 'none', 'nowhere' ) ) ) : ?>

							<?php
							// Inner Overlay
							if ( $overlay_style ) {

								wpex_overlay( 'inside_link', $overlay_style, $atts );

							}

							// Close link
							echo '</a>';

							// Outside Overlay
							if ( $overlay_style ) {

								wpex_overlay( 'outside_link', $overlay_style, $atts );

							}

						endif; ?>

					</div><!-- .wpex-carousel-entry-media -->

				<?php endif; // Thumbnail check ?>

				<?php
				// Title
				if ( 'true' == $title || 'true' == $price ) : ?>

					<div class="wpex-carousel-entry-details clr"<?php echo $content_style; ?>>

						<?php
						// Title
						if ( 'true' == $title && $post->title ) : ?>

							<div class="wpex-carousel-entry-title entry-title"<?php echo $heading_style; ?>>
								<a href="<?php echo $post->permalink; ?>" title="<?php echo $post->esc_title; ?>"<?php echo $heading_link_style; ?>><?php echo $post->title; ?></a>
							</div><!-- .wpex-carousel-entry-title -->

						<?php endif; ?>

						<?php
						// Excerpt
						if ( 'true' == $price && $get_price = wpex_get_woo_product_price() ) : ?>

							<div class="wpex-carousel-entry-price price clr"<?php echo $price_style; ?>>
								<?php echo $get_price; ?>
							</div><!-- .wpex-carousel-entry-price -->

						<?php endif; ?>

						<?php
						// Display read more button if $read_more is true and $read_more_text isn't empty
						if ( 'true' == $read_more ) : ?>

							<div class="entry-readmore-wrap clr">

								<?php
								$attrs = array(
									'href'  => esc_url( $post->permalink ),
									'class' => $readmore_classes,
									'rel'   => 'bookmark',
									'style' => $readmore_style,
								);

								if ( $readmore_hover_data ) {
									$attrs['data-wpex-hover'] = $readmore_hover_data;
								} ?>

								<a <?php echo wpex_parse_attrs( $attrs ); ?>><?php

									echo esc_html( $read_more_text );

									if ( 'true' == $readmore_rarr ) { ?>

										<span class="vcex-readmore-rarr"><?php echo wpex_element( 'rarr' ); ?></span>

									<?php }

								?></a>

							</div>

						<?php endif; // End readmore check ?>

					</div><!-- .wpex-carousel-entry-details -->

				<?php endif; ?>

			</div><!-- .wpex-carousel-slide -->

		<?php endwhile; ?>

	</div><!-- .wpex-carousel -->

	<?php
	// Remove post object from memory
	$post = null;

	// Reset the post data to prevent conflicts with WP globals
	wp_reset_postdata(); ?>

<?php
// If no posts are found display message
else : ?>

	<?php
	// Display no posts found error if function exists
	echo vcex_no_posts_found_message( $atts ); ?>

<?php
// End post check
endif; ?>