<?php
/**
 * Mobile Icons Header Menu
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

<div id="mobile-menu" class="<?php echo wpex_header_mobile_menu_classes(); ?>"><?php

	// Display mobile menu toggle icon
	$toggle_icon = '';

	$aria_label = wpex_get_mod( 'mobile_menu_toggle_aria_label', esc_attr_x( 'Toggle mobile menu', 'aria-label', 'total' ) );

	$toggle_icon .= '<a href="#" class="mobile-menu-toggle" aria-label="' . $aria_label .'">';

		$toggle_icon_inner = '<span class="wpex-bars" aria-hidden="true"><span></span></span>';

		$toggle_icon .= apply_filters( 'wpex_mobile_menu_open_button_text', $toggle_icon_inner );

		$toggle_icon .= '<span class="screen-reader-text">' . esc_html__( 'Open Mobile Menu', 'total' ) . '</span>';

	$toggle_icon .= '</a>';

	// Output user-defined mobile icons
	echo wpex_get_mobile_menu_extra_icons();

	// Output main toggle icon
	echo $toggle_icon;

?></div>