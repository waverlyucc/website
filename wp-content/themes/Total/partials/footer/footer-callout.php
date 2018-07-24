<?php
/**
 * Footer bottom content
 *
 * @package Total WordPress Theme
 * @subpackage Partials
 * @version 4.7
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Get post ID
$post_id = wpex_get_current_post_id();

// Return if disabled
if ( ! wpex_has_callout( $post_id ) ) {
	return;
}

// Get post content
$content = wpex_callout_content( $post_id );

// Get link
if ( $post_id && $meta = get_post_meta( $post_id, 'wpex_callout_link', true ) ) {
	$link = $meta;
} else {
	$link = wpex_get_mod( 'callout_link', '#' );
}

// Get link text
if ( $post_id && $meta = get_post_meta( $post_id, 'wpex_callout_link_txt', true ) ) {
	$link_text = $meta;
} else {
	$link_text = wpex_get_mod( 'callout_link_txt', 'Get In Touch' );
}

// Button Icon
$icon = wpex_get_mod( 'callout_button_icon' );
$icon_position = wpex_get_mod( 'callout_button_icon_position', 'after_text' );

if ( $icon ) {

	if ( 'before_text' == $icon_position ) {
		$link_text = '<span class="theme-button-icon-left fa fa-' . esc_html( $icon ) .'"></span>' . $link_text;
	} else {
		$link_text = $link_text . '<span class="theme-button-icon-right fa fa-' . esc_html( $icon ) .'"></span>';
	}

}

// Translate Theme mods
$link      = wpex_translate_theme_mod( 'callout_link', $link );
$link_text = wpex_translate_theme_mod( 'callout_link_txt', $link_text );

// Bail if conditions are not met
if ( ! $content && ( ! $link || ! $link_text ) ) {
	return;
}

// Callout classes
$classes = 'clr';
if ( ! $content ) {
	$classes .= ' btn-only';
}
if ( 'always-visible' != wpex_get_mod( 'callout_visibility', 'always-visible' ) ) {
	$classes .= ' ' . wpex_get_mod( 'callout_visibility' );
}

// Attributes
$attrs = array(
	'id'    => 'footer-callout-wrap',
	'class' => esc_attr( $classes ),
);
if ( $label = wpex_get_mod( 'footer_callout_aria_label' ) ) {
	$attrs['aria-label'] = esc_attr( $label );
} ?>
	
<div <?php echo wpex_parse_attrs( $attrs ); ?><?php wpex_aria_landmark( 'footer_callout' ); ?>>

	<div id="footer-callout" class="clr<?php if ( $content ) echo ' container'; ?>">

		<?php
		// Display content
		if ( $content ) : ?>

			<div id="footer-callout-left" class="footer-callout-content clr<?php if ( ! $link ) echo ' full-width'; ?>"><?php

				// Output content
				echo do_shortcode( wp_kses_post( $content ) );

			?></div>

		<?php endif; ?>

		<?php
		// Display footer callout button if callout link & text options are not blank in the admin
		if ( $link ) : ?>

			<div id="footer-callout-right" class="footer-callout-button wpex-clr">
				<?php
				// Display callout button
				echo wpex_parse_html( 'a', array(
					'href'   => esc_url( $link ),
					'class'  => wpex_get_button_classes( wpex_get_mod( 'callout_button_style' ), wpex_get_mod( 'callout_button_color' ) ),
					'target' => wpex_get_mod( 'callout_button_target', 'blank' ),
					'rel'    => wpex_get_mod( 'callout_button_rel' ),
				), wp_kses_post( $link_text ) ); ?>
			</div>

		<?php endif; ?>

	</div>

</div>