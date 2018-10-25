<?php
/**
 * Used to display the portfolio slider
 *
 * @package Total WordPress Theme
 * @subpackage Partials
 * @version 4.4.1
 */

$post_id = get_the_ID();

if ( apply_filters( 'wpex_single_portfolio_media_lightbox', true ) && wpex_gallery_is_lightbox_enabled() ) {
	$lightbox_enabled = true;
} else {
	$lightbox_enabled = false;
}

$args = array(
	'lightbox'       => $lightbox_enabled,
	'lightbox_title' => apply_filters( 'wpex_portfolio_gallery_lightbox_title', false ),
	'slider_data'    => array(
		'filter_tag' => 'wpex_portfolio_single_gallery',
	),
	'thumbnail_args' => array(
		'size'          => 'portfolio_post',
		'class'         => 'portfolio-single-media-img',
		'apply_filters' => 'wpex_get_portfolio_post_thumbnail_args',
	),
);

$gallery = wpex_get_post_media_gallery_slider( $post_id, $args );

if ( ! $gallery ) {
	return;
} ?>

<div id="portfolio-single-gallery" class="portfolio-post-slider wpex-clr"><?php echo $gallery; ?></div>