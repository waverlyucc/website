<?php
/**
 * WooCommerce Default template
 *
 * @package Total WordPress Theme
 * @subpackage Templates
 * @version 4.8
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header(); ?>

	<div id="content-wrap" class="container clr">

		<?php wpex_hook_primary_before(); ?>

		<div id="primary" class="content-area clr">

			<?php wpex_hook_content_before(); ?>

			<div id="content" class="clr site-content">

				<?php wpex_hook_content_top(); ?>

				<article class="entry-content entry clr">

					<?php if ( wpex_woo_archive_has_loop() ) {
						woocommerce_content();
					} else {
						woocommerce_product_archive_description();
					} ?>

				</article><!-- #post -->

				<?php wpex_hook_content_bottom(); ?>

			</div><!-- #content -->

			<?php wpex_hook_content_after(); ?>

		</div><!-- #primary -->

		<?php wpex_hook_primary_after(); ?>

	</div><!-- #content-wrap -->

<?php get_footer(); ?>