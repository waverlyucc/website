<?php
/**
 * Secondary Image Swap & Title
 *
 * @package Total WordPress Theme
 * @subpackage Partials
 * @version 4.5.5
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Only used for inside position
if ( 'inside_link' != $position ) {
	return;
}

// Thumbnail required
if ( ! has_post_thumbnail() ) {
	return;
}

// Get secondary image
$secondary_image = wpex_get_secondary_thumbnail();

// Secondary image required
if ( ! $secondary_image ) {
	return;
}

if ( is_numeric( $secondary_image ) ) {

	$secondary_image = wpex_get_post_thumbnail( array(
		'attachment' => $secondary_image,
		'width'      => isset( $args['img_width'] ) ? $args['img_width'] :'',
		'height'     => isset( $args['img_height'] ) ? $args['img_height'] :'',
		'crop'       => isset( $args['img_crop'] ) ? $args['img_crop'] :'',
		'alt'        => isset( $args['post_esc_title'] ) ?$args['post_esc_title'] :'',
		'size'       => isset( $args['img_size'] ) ?$args['img_size'] :'',
	) );

} else {

	esc_url( $secondary_image );

}

if ( $secondary_image ) {

	echo '<div class="overlay-thumb-swap-secondary">' . $secondary_image . '<div class="overlay-thumb-swap-title"><span>' . esc_html( get_the_title() ) . '</span></div></div>';

}