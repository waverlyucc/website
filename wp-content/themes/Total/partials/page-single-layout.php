<?php
/**
 * Single Page Layout
 *
 * @package Total WordPress theme
 * @subpackage Partials
 * @version 4.4.1
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Custom template design
if ( $template_content = wpex_get_singular_template_content( 'page' ) ) {
	wpex_singular_template( $template_content );
	return;
} ?>

<article id="single-blocks" class="single-page-article wpex-clr">

	<?php
	// Get single layout blocks
	$blocks = wpex_single_blocks();

	// Make sure we have blocks
	if ( ! empty( $blocks ) ) :

		// Loop through blocks
		foreach ( $blocks as $block ) :

			// Media not needed for this position
			if ( 'media' == $block && wpex_get_custom_post_media_position() ) {
				continue;
			}

			// Callable output
			if ( 'the_content' != $block && is_callable( $block ) ) {

				call_user_func( $block );

			}

			// Get block template part
			else {

				get_template_part( 'partials/page-single-'. $block );

			}

		endforeach;

	endif; ?>

</article><!-- #single-blocks -->