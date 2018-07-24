<?php
/**
 * The template for editing templatera templates via the front-end editor.
 *
 * @package Total WordPress Theme
 * @subpackage Templates
 * @version 4.5
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// This file is only used for the front-end editor.
if ( ! wpex_vc_is_inline() ) {
	exit;
}

get_header(); ?>

	<div id="content-wrap" class="container clr">

		<?php wpex_hook_primary_before(); ?>

		<div id="primary" class="content-area clr">

			<?php wpex_hook_content_before(); ?>

			<div id="content" class="site-content clr">

				<?php wpex_hook_content_top(); ?>

				<div class="single-page-content entry clr">

					<?php if ( wpex_is_footer_builder_page() || wpex_is_header_builder_page() ) : ?>

						<p style="padding-top: 30px;"><?php esc_html_e( 'Your page content goes here.', 'total' ); ?></p>

					<?php else : ?>

						<?php while ( have_posts() ) : the_post(); ?>

							<?php the_content(); ?>

						<?php endwhile; ?>

					<?php endif; ?>

				</div>

				<?php wpex_hook_content_bottom(); ?>

			</div><!-- #content -->

			<?php wpex_hook_content_after(); ?>

		</div><!-- #primary -->

		<?php wpex_hook_primary_after(); ?>

	</div><!-- .container -->

<?php get_footer(); ?>