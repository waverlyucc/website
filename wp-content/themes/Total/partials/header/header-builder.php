<?php
/**
 * Header Builder Content
 *
 * @package Total WordPress Theme
 * @subpackage Partials
 * @version 4.6.5
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Live builder
if ( wpex_vc_is_inline() && wpex_is_header_builder_page() ) :

	while ( have_posts() ) : the_post();

		the_content();

	endwhile;

// Front end
else :

	$id = wpex_header_builder_id();

	if ( $id ) {

		echo do_shortcode( get_post_field( 'post_content', $id ) );

	}

endif;