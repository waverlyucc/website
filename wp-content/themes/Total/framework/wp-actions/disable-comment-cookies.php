<?php
/**
 * Remove comment form cookies
 *
 * @package Total WordPress Theme
 * @subpackage Framework
 * @version 4.6.7
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

remove_action( 'set_comment_cookies', 'wp_set_comment_cookies' );

function wpex_remove_comment_form_cookies( $fields ) {
	unset( $fields['cookies'] );
	return $fields;
}
add_filter( 'comment_form_default_fields', 'wpex_remove_comment_form_cookies', 50 );