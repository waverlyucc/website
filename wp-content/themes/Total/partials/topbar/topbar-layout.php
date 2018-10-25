<?php
/**
 * Topbar layout
 *
 * @package Total WordPress theme
 * @subpackage Partials
 * @version 4.5
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
} ?>

<?php wpex_hook_topbar_before(); ?>

	<div id="top-bar-wrap" class="<?php echo esc_attr( wpex_topbar_classes() ); ?>">
		<div id="top-bar" class="clr container">
			<?php wpex_hook_topbar_inner(); ?>
		</div><!-- #top-bar -->
	</div><!-- #top-bar-wrap -->

<?php wpex_hook_topbar_after(); ?>