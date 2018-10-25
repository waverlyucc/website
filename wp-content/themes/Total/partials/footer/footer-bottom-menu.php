<?php
/**
 * Footer bottom content
 *
 * @package Total WordPress Theme
 * @subpackage Partials
 * @version 4.6.5
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Get footer menu location and apply filters for child theming
$menu_location = apply_filters( 'wpex_footer_menu_location', 'footer_menu' );

// Menu is required
if ( ! has_nav_menu( $menu_location ) ) {
	return;
} ?>

<div id="footer-bottom-menu" class="clr"<?php wpex_aria_landmark( 'footer_bottom_menu' ); ?> aria-label="<?php echo wpex_get_mod( 'footer_menu_aria_label', esc_attr_x( 'Footer menu', 'aria-label', 'total' ) ); ?>"><?php

	// Display footer menu
	wp_nav_menu( array(
		'theme_location' => $menu_location,
		'sort_column'    => 'menu_order',
		'fallback_cb'    => false,
	) );

?></div><!-- #footer-bottom-menu -->