<?php
/**
 * Image Swap style thumbnail
 *
 * @package Total Wordpress Theme
 * @subpackage Templates/WooCommerce
 * @version 4.5.5
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Return dummy image if no featured image is defined
if ( ! has_post_thumbnail() ) {
	wpex_woo_placeholder_img();
	return;
}

// Globals
global $product;

// Get first image
$attachment = get_post_thumbnail_id();

// Get Second Image in Gallery
$attachment_ids   = $product->get_gallery_image_ids();
$secondary_img_id = '';

if ( ! empty( $attachment_ids ) ) {
	$attachment_ids = array_unique( $attachment_ids ); // remove duplicate images
	if ( $attachment_ids['0'] != $attachment ) {
		$secondary_img_id = $attachment_ids['0'];
	} elseif ( isset( $attachment_ids['1'] ) && $attachment_ids['1'] != $attachment ) {
		$secondary_img_id = $attachment_ids['1'];
	}
}

$secondary_img_id = ( $secondary_img_id != $attachment ) ? $secondary_img_id : '';
			
// Return thumbnail
if ( $secondary_img_id ) : ?>

	<div class="woo-entry-image-swap wpex-clr">
		<?php
		// Main Image
		wpex_post_thumbnail( array(
			'attachment' => $attachment,
			'size'       => 'shop_catalog',
			'alt'        => wpex_get_esc_title(),
			'class'      => 'woo-entry-image-main',
		) );
		
		// Secondary Image
		wpex_post_thumbnail( array(
			'attachment' => $secondary_img_id,
			'size'       => 'shop_catalog',
			'class'      => 'woo-entry-image-secondary',
		) ); ?>
	</div><!-- .woo-entry-image-swap -->

<?php else : ?>

	<?php
	// Single Image
	wpex_post_thumbnail( array(
		'attachment' => $attachment,
		'size'       => 'shop_catalog',
		'alt'        => wpex_get_esc_title(),
		'class'      => 'woo-entry-image-main',
	) ); ?>

<?php endif; ?>