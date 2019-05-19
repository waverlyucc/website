<?php
/**
 * The div template file.
 *
 * This is the most generic template file in a WordPress theme and one of the
 * two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * For example, it puts together the home page when no home.php file exists.
 *
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
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

			<div id="content" class="site-content">

				<?php wpex_hook_content_top(); ?>

				<?php
				// Display posts if there are in fact posts to display
				if ( have_posts() ) :

					// Get index loop type
					$loop_type = wpex_get_index_loop_type();

					// Get loop top
					get_template_part( 'partials/loop/loop-top', $loop_type );

						// Define counter for clearing floats
						$wpex_count = 0;

						// Loop through posts
						while ( have_posts() ) : the_post();

							// Before entry hook
							wpex_hook_archive_loop_before_entry();

							// Get content template part (entry content)
							get_template_part( 'partials/loop/loop', $loop_type );

							// After entry hook
							wpex_hook_archive_loop_after_entry();

						// End loop
						endwhile;

					// Get loop bottom
					get_template_part( 'partials/loop/loop-bottom', $loop_type );

					// Display pagination
					if ( 'blog' == $loop_type ) {
						global $wp_query;
						wpex_blog_pagination( array(
							'query'    => $wp_query->query,
							'is_home'  => is_home(),
							'grid'     => '#blog-entries',
							'count'    => $wpex_count,
							'perPage'  => $wp_query->query_vars['posts_per_page'],
							'maxPages' => $wp_query->max_num_pages,
							'columns'  => wpex_blog_entry_columns(),
							'category' => is_category() ? get_query_var( 'cat' ) : false,
						) );
					} else {
						wpex_pagination();
					} ?>

				<?php
				// Show message because there aren't any posts
				else : ?>

					<article class="clr"><?php esc_html_e( 'No Posts found.', 'total' ); ?></article>

				<?php endif; ?>

				 <?php wpex_hook_content_bottom(); ?>

			</div><!-- #content -->

		<?php wpex_hook_content_after(); ?>

		</div><!-- #primary -->

		<?php wpex_hook_primary_after(); ?>

	</div><!-- .container -->

<?php get_footer(); ?>