<?php
/**
 * Header Builder Content
 *
 * @package Total WordPress Theme
 * @subpackage Partials
 * @version 4.8.3
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Live builder
if ( wpex_is_header_builder_page() && ( wpex_vc_is_inline() || wpex_elementor_is_preview_mode() ) ) :

	while ( have_posts() ) : the_post();

		the_content();

	endwhile;

// Front end
else :

	$id = wpex_header_builder_id();

	if ( $id ) {

		if ( 'elementor_library' == get_post_type( $id ) && class_exists( 'Elementor\Frontend' ) ) {
			$front_end = new \Elementor\Frontend();
			echo $front_end->get_builder_content_for_display( $id );
		} else {
			echo do_shortcode( get_post_field( 'post_content', $id ) );
		}

	}

endif;