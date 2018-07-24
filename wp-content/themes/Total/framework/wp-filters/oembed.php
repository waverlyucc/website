<?php
/**
 * Alter oEmbed output
 *
 * @package Total WordPress Theme
 * @subpackage Framework
 * @version 4.5.4.2
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function wpex_oembed_html( $cache, $url, $attr, $post_ID ) {

	// Remove frameborder
	//$cache = str_replace( 'frameborder="0"', '', $cache );

	// Supported embeds
	$hosts = apply_filters( 'wpex_oembed_responsive_hosts', array(
		'vimeo.com',
		'youtube.com',
		'youtube-nocookie.com',
		'blip.tv',
		'money.cnn.com',
		'dailymotion.com',
		'flickr.com',
		'hulu.com',
		'kickstarter.com',
		'vine.co',
		'soundcloud.com',
	) );

	// Supports responsive
	$supports_responsive = false;

	// Check if responsive wrap should be added
	foreach( $hosts as $host ) {
		if ( strpos( $url, $host ) !== false ) {
			$supports_responsive = true;
			break; // no need to loop further
		}
	}

	// Output code
	if ( $supports_responsive ) {
		return '<p class="wpex-roembed wpex-clr">' . $cache . '</p>';
	} else {
		return '<div class="wpex-oembed-wrap wpex-clr">' . $cache . '</div>';
	}

}
add_filter( 'embed_oembed_html', 'wpex_oembed_html', 99, 4 );

function wpex_oembed_dataparse( $return, $data, $url ){
	$return = str_ireplace(
		array(
			'frameborder="0"',
			//'gesture="media"',
			//'allowfullscreen',
			//'webkitallowfullscreen', 
			//'mozallowfullscreen' 
		),
		'',
		$return
	);
	return $return;
}
add_filter( 'oembed_dataparse', 'wpex_oembed_dataparse', 10, 3 );