<?php
/**
 * Mobile Icons Header Menu.
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
} ?>

<div id="wpex-mobile-menu-fixed-top" class="<?php echo wpex_header_mobile_menu_classes(); ?>">
	<div class="container clr">
		<div class="wpex-inner">
			<a href="#mobile-menu" class="mobile-menu-toggle"><?php echo apply_filters( 'wpex_mobile_menu_open_button_text', '<span class="ticon ticon-navicon"></span>' ); ?><span class="wpex-text"><?php echo wpex_get_mod( 'mobile_menu_toggle_text', esc_html__( 'Menu', 'total' ) ); ?></span></a>
			<?php /* if ( $extra_icons = wpex_get_mobile_menu_extra_icons() ) { ?>
				<div class="wpex-aside"><?php echo $extra_icons; ?></div>
			<?php } */ ?>
		</div>
	</div>
</div>