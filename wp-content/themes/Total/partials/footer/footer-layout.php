<?php
/**
 * Footer Layout
 *
 * @package Total WordPress Theme
 * @subpackage Partials
 * @version 4.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
} ?>

<?php wpex_hook_footer_before(); ?>

<?php if ( wpex_footer_has_widgets() ) : ?>

    <footer id="footer" class="site-footer"<?php wpex_schema_markup( 'footer' ); ?>>

        <?php wpex_hook_footer_top(); ?>

        <div id="footer-inner" class="site-footer-inner container clr">

            <?php wpex_hook_footer_inner(); // widgets are added via this hook ?>

        </div><!-- #footer-widgets -->

        <?php wpex_hook_footer_bottom(); ?>

    </footer><!-- #footer -->

<?php endif; ?>

<?php wpex_hook_footer_after(); ?>