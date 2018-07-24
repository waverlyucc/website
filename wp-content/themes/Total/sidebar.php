<?php
/**
 * Main sidebar area containing your defined widgets.
 * You shouldn't have to edit this file ever since things are added via hooks.
 *
 * @package Total WordPress Theme
 * @subpackage Templates
 * @version 4.5.5
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
} ?>

<?php wpex_hook_sidebar_before(); ?>

<aside id="sidebar" class="sidebar-container sidebar-primary"<?php wpex_schema_markup( 'sidebar' ); ?><?php wpex_aria_landmark( 'sidebar' ); ?>>

	<?php wpex_hook_sidebar_top(); ?>

	<div id="sidebar-inner" class="clr">

		<?php wpex_hook_sidebar_inner(); // Sidebar is added via framework/hooks/partials/ @see wpex_display_sidebar() ?>

	</div><!-- #sidebar-inner -->

	<?php wpex_hook_sidebar_bottom(); ?>

</aside><!-- #sidebar -->

<?php wpex_hook_sidebar_after(); ?>