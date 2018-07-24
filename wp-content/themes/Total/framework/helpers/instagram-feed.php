<?php
/**
 * Fetch Instagram feed
 *
 * @package Total WordPress Theme
 * @subpackage Framework
 * @version 4.6.1
 */

// Prevent direct file access
if ( ! defined ( 'ABSPATH' ) ) {
	exit;
}

function wpex_fetch_instagram_feed( $username = '', $slice = 4 ) {

	// Sanitize input and get transient
	$username       = trim( strtolower( $username ) );
	$transient_name = 'wpex-instagram-feed-' . sanitize_title_with_dashes( $username ) . '-' . $slice;
	$instagram      = get_transient( $transient_name );

	// Clear transient
	if ( ! empty( $_GET['wpex_clear_transients'] ) ) {
		$instagram = '';
	}

	// Fetch instagram items
	if ( ! $instagram ) {

		switch ( substr( $username, 0, 1 ) ) {
			case '#':
				$url = 'https://instagram.com/explore/tags/' . str_replace( '#', '', $username );
				break;

			default:
				$url = 'https://instagram.com/' . str_replace( '@', '', $username );
				break;
		}

		$remote = wp_remote_get( $url );

		if ( is_wp_error( $remote ) ) {
			return new WP_Error( 'site_down', esc_html__( 'Unable to communicate with Instagram.', 'total' ) );
		}

		if ( 200 !== wp_remote_retrieve_response_code( $remote ) ) {
			return new WP_Error( 'invalid_response', esc_html__( 'Instagram did not return a 200.', 'total' ) );
		}

		$shards      = explode( 'window._sharedData = ', $remote['body'] );
		$insta_json  = explode( ';</script>', $shards[1] );
		$insta_array = json_decode( $insta_json[0], true );

		if ( ! $insta_array ) {
			return new WP_Error( 'bad_json', esc_html__( 'Instagram has returned invalid data.', 'total' ) );
		}

		if ( isset( $insta_array['entry_data']['ProfilePage'][0]['graphql']['user']['edge_owner_to_timeline_media']['edges'] ) ) {
			$images = $insta_array['entry_data']['ProfilePage'][0]['graphql']['user']['edge_owner_to_timeline_media']['edges'];
		} elseif ( isset( $insta_array['entry_data']['TagPage'][0]['graphql']['hashtag']['edge_hashtag_to_media']['edges'] ) ) {
			$images = $insta_array['entry_data']['TagPage'][0]['graphql']['hashtag']['edge_hashtag_to_media']['edges'];
		} else {
			return new WP_Error( 'bad_json_2', esc_html__( 'Instagram has returned invalid data.', 'wp-instagram-widget' ) );
		}

		if ( ! is_array( $images ) ) {
			return new WP_Error( 'bad_array', esc_html__( 'Instagram has returned invalid data.', 'total' ) );
		}

		$instagram = array();

		foreach ( $images as $image ) {
			if ( true === $image['node']['is_video'] ) {
				$type = 'video';
			} else {
				$type = 'image';
			}

			$caption = __( 'Instagram Image', 'wp-instagram-widget' );
			if ( ! empty( $image['node']['edge_media_to_caption']['edges'][0]['node']['text'] ) ) {
				$caption = wp_kses( $image['node']['edge_media_to_caption']['edges'][0]['node']['text'], array() );
			}

			$instagram[] = array(
				'description' => $caption,
				'link'        => trailingslashit( '//instagram.com/p/' . $image['node']['shortcode'] ),
				'time'        => $image['node']['taken_at_timestamp'],
				'comments'    => $image['node']['edge_media_to_comment']['count'],
				'likes'       => $image['node']['edge_liked_by']['count'],
				'thumbnail'   => preg_replace( '/^https?\:/i', '', $image['node']['thumbnail_resources'][0]['src'] ),
				'small'       => preg_replace( '/^https?\:/i', '', $image['node']['thumbnail_resources'][2]['src'] ),
				'large'       => preg_replace( '/^https?\:/i', '', $image['node']['thumbnail_resources'][4]['src'] ),
				'original'    => preg_replace( '/^https?\:/i', '', $image['node']['display_url'] ),
				'type'        => $type,
			);
		} // End foreach().

		// Set transient if not empty
		if ( ! empty( $instagram ) ) {
			$instagram = serialize( $instagram );
			set_transient(
				$transient_name,
				$instagram,
				apply_filters( 'wpex_instagram_widget_cache_time', HOUR_IN_SECONDS*2 )
			);
		}

	}

	// Return array
	if ( ! empty( $instagram )  ) {
		if ( ! is_array( $instagram ) && 1 != $instagram ) {
			$instagram = unserialize( $instagram );
		}
		if ( is_array( $instagram ) ) {
			return array_slice( $instagram, 0, $slice );
		}
	}

	// No images returned
	else {

		return new WP_Error( 'no_images', esc_html__( 'Instagram did not return any images.', 'total' ) );

	}

}