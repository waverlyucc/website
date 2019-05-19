<?php
/**
 * Header buttons used for certain header styles such as header style 7
 *
 * @package Total WordPress Theme
 * @subpackage Partials
 * @version 4.8
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
} ?>

<ul id="header-buttons" class="clr">
	<?php
	// Define list of buttons
	$buttons = array(
		'header_button' => array(
			'class'    => 'theme-button',
			'content'  => wp_kses_post( wpex_get_mod( 'header_button_text', __( 'Button', 'total' ) ) ),
			'href'     => esc_url( wpex_get_mod( 'header_button_href', '#' ) ),
			'nofollow' => wpex_get_mod( 'header_button_nofollow' ),
			'target'   => wpex_get_mod( 'header_button_target' ),
		),
	);
	// Apply filters for child theme editing
	$buttons = apply_filters( 'wpex_header_buttons', $buttons );
	foreach ( $buttons as $k => $button_atts ) {
		if ( ! empty( $button_atts[ 'href' ] ) && ! empty( $button_atts[ 'content' ] ) ) {
			echo '<li>' . wpex_parse_html( 'a', $button_atts, $button_atts[ 'content' ] ) . '</li>';
		}
	} ?>
</ul>