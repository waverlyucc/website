<?php
/**
 * Load More functions for Total VC grid modules
 *
 * @package Total WordPress Theme
 * @subpackage VC Functions
 * @version x
 * @todo Finish
 */

function vcex_loadmore_button( $base, $atts, $query ) {

	unset( $atts['wrap_css'] );

	wp_enqueue_script(
		'vcex-loadmore',
		wpex_asset_url( 'js/dynamic/vcex-loadmore.js' ),
		array( 'jquery' ),
		'1.0',
		true
	);

	$params = '';
	foreach ( $atts  as $k => $v ) {
		$params .= $k . '="' . esc_attr( $v ) .'" ';
	}
	$shortcode = '[' . $base . ' ' . trim( $params ) . ']';
	
	$text = wpex_get_mod( 'loadmore_text' );
	$text = $text ? $text : __( 'Load More', 'total' );
	$text = apply_filters( 'vcex_loadmore_button_text', $text );

	if ( apply_filters( 'wpex_loadmore_enqueue_mediaelement', false ) ) {
		wp_enqueue_style( 'wp-mediaelement' );
		wp_enqueue_script( 'wp-mediaelement' );
	}

	$nonce = wp_create_nonce( 'vcex-load-more-nonce' );

	$text         = esc_attr( wpex_get_mod( 'loadmore_text', esc_html__( 'Load More', 'total' ) ) );
	$loading_text = esc_attr( wpex_get_mod( 'loadmore_loading_text', esc_html__( 'Loading...', 'total' ) ) );
	$failed_text  = esc_attr( wpex_get_mod( 'loadmore_failed_text', esc_html__( 'Failed to load posts.', 'total' ) ) );

	return '<div class="vcex-load-more-wrap"><div class="vcex-loadmore theme-button expanded" data-text="'. $text .'" data-loading-text="' . $loading_text . '" data-failed-text="' . $failed_text . '" data-nonce="' . esc_attr( $nonce ) .'" data-shortcode="' . htmlentities( $shortcode ) . '" data-page="1" data-max-pages="' . $query->max_num_pages . '"><span class="theme-button-inner">' . $text . '</span></div></div>';

}

function vcex_loadmore_render() {

	check_ajax_referer( 'vcex-load-more-nonce', 'nonce' );

	if ( empty( $_POST['shortcode'] ) ) {
		wp_die();
	}

	$shortcode = stripslashes( $_POST['shortcode'] );

	$data = do_shortcode( $shortcode );

	wp_send_json_success( $data );

	wp_die();

}
add_action( 'wp_ajax_vcex_loadmore_render', 'vcex_loadmore_render' );
add_action( 'wp_ajax_nopriv_vcex_loadmore_render', 'vcex_loadmore_render' );