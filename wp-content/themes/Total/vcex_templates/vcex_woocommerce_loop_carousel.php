<?php
/**
 * Visual Composer WooCommerce Loop Carousel
 *
 * @package Total WordPress Theme
 * @subpackage VC Templates
 * @version 4.5.5
 *
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Helps speed up rendering in backend of VC
if ( is_admin() && ! wp_doing_ajax() ) {
	return;
}

// Get and extract shortcode attributes
$atts = vc_map_get_attributes( 'vcex_woocommerce_loop_carousel', $atts );

// Define vars
$atts['post_type'] = 'product';
$atts['taxonomy']  = 'product_cat';
$atts['tax_query'] = '';

// Custom query_products_by argument
if ( $atts['query_products_by'] ) {
	if ( 'featured' == $atts['query_products_by'] ) {
		$atts['featured_products_only'] = true;
	} elseif ( 'on_sale' == $atts['query_products_by'] ) {
		if ( function_exists( 'wc_get_product_ids_on_sale' ) ) {
			$atts['post__in'] = array_merge( array( 0 ), wc_get_product_ids_on_sale() );
		}
	}
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

	// Wrap Classes
	$wrap_classes = array( 'vcex-module', 'wpex-carousel', 'wpex-carousel-woocommerce-loop', 'owl-carousel', 'products', 'clr' );

	if ( 'true' == $arrows ) {
		$wrap_classes[] = $arrows_style ? 'arrwstyle-' . $arrows_style : 'arrwstyle-default';
		if ( $arrows_position && 'default' != $arrows_position ) {
			$wrap_classes[] = 'arrwpos-' . $arrows_position;
		}
	}

	/*
	// Note: not be ideal for carousels
	// Can be added via 'VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG'
	if ( wpex_get_mod( 'woo_entry_equal_height', false ) ) {
		//$wrap_classes[] = 'match-height-grid';
	}*/

	if ( $visibility ) {
		$wrap_classes[] = $visibility;
	}

	if ( $css_animation && 'none' != $css_animation ) {
		$wrap_classes[] = vcex_get_css_animation( $css_animation );
	}

	if ( $classes ) {
		$wrap_classes[] = vcex_get_extra_class( $classes );
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
	$items_margin           = $items_margin;
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

	<div class="woocommerce clr">

		<ul class="<?php echo $wrap_classes; ?>"<?php vcex_unique_id( $unique_id ); ?> data-items="<?php echo $items; ?>" data-slideby="<?php echo $items_scroll; ?>" data-nav="<?php echo $arrows; ?>" data-dots="<?php echo $dots; ?>" data-autoplay="<?php echo $auto_play; ?>" data-smart-speed="<?php echo $animation_speed; ?>" data-loop="<?php echo $infinite_loop; ?>" data-autoplay-timeout="<?php echo $timeout_duration; ?>" data-center="<?php echo $center; ?>" data-margin="<?php echo intval( $items_margin ); ?>" data-items-tablet="<?php echo $tablet_items; ?>" data-items-mobile-landscape="<?php echo $mobile_landscape_items; ?>" data-items-mobile-portrait="<?php echo $mobile_portrait_items; ?>">

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

				<?php
				// Get woocommerce template part
				wc_get_template_part( 'content', 'product' ); ?>

			<?php endwhile; ?>

		</ul>

	</div>

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