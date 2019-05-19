<?php
/**
 * Footer builder output
 *
 * @package Total WordPress Theme
 * @subpackage Partials
 * @version 4.8.4
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Get footer builder ID
$id = wpex_footer_builder_id();

// Return if no id defined
if ( ! $id ) {
	return;
} ?>

<div id="footer-builder" class="footer-builder clr">
	<div class="footer-builder-content clr container entry">
		<?php if ( wpex_is_footer_builder_page() && ( wpex_vc_is_inline() || wpex_elementor_is_preview_mode() ) ) {
			while ( have_posts() ) : the_post();
				the_content();
			endwhile;
		} else {
			if ( 'elementor_library' == get_post_type( $id ) && class_exists( 'Elementor\Frontend' ) ) {
				$front_end = new \Elementor\Frontend();
				echo $front_end->get_builder_content_for_display( $id );
			} else {
				echo apply_filters( 'wpex_footer_builder_content', do_shortcode( get_post_field( 'post_content', $id ) ) );
			}
		} ?>
	</div>
</div>