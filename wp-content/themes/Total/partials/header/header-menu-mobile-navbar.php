<?php
/**
 * Navbar Style Mobile Menu Toggle
 *
 * Note: By default this file only loads if wpex_header_has_mobile_menu returns true.
 *
 * @package Total WordPress Theme
 * @subpackage Partials
 * @version 4.8
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Get menu text
$text = wpex_get_translated_theme_mod( 'mobile_menu_toggle_text' );
$text = $text ? $text : esc_html__( 'Menu', 'total' );
$text = apply_filters( 'wpex_mobile_menu_navbar_open_text', $text ); ?>

<div id="wpex-mobile-menu-navbar" class="<?php echo wpex_header_mobile_menu_classes(); ?>">
	<div class="container clr">
		<a href="#mobile-menu" class="mobile-menu-toggle">
			<?php echo apply_filters( 'wpex_mobile_menu_navbar_open_icon', '<span class="ticon ticon-navicon" aria-hidden="true"></span>' ); ?><span class="wpex-text"><?php echo wp_kses_post( $text ); ?></span>
		</a>
	</div>
</div>