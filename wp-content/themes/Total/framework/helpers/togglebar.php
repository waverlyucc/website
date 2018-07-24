<?php
/**
 * Togglebar functions
 *
 * @package Total WordPress Theme
 * @subpackage Framework
 * @version 4.6
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Get togglebar content ID
 *
 * @since 4.0
 */
function wpex_togglebar_content_id() {
	$id = apply_filters( 'wpex_toggle_bar_content_id', wpex_get_mod( 'toggle_bar_page', null ) );
	return $id ? wpex_parse_obj_id( intval( $id ) ) : null;
}

/**
 * Returns togglebar content
 *
 * @since 4.0
 */
function wpex_togglebar_content() {
	if ( $togglebar_id = wpex_togglebar_content_id() ) {
		return get_post_field( 'post_content', $togglebar_id );
	}
}

/**
 * Check if togglebar is enabled
 *
 * @since 4.0
 */
function wpex_has_togglebar( $post_id = '' ) {

	// Return false if toggle bar page is not defined
	if ( ! wpex_togglebar_content_id() ) {
		return false;
	}

	// Check if enabled in Customizer
	$return = wpex_get_mod( 'toggle_bar', true );

	// Get post ID
	$post_id = $post_id ? $post_id : wpex_get_current_post_id();

	// Check meta
	if ( $post_id ) {

		// Return true if enabled via the page settings
		if ( 'enable' == get_post_meta( $post_id, 'wpex_disable_toggle_bar', true ) ) {
			$return = true;
		}

		// Return false if disabled via the page settings
		if ( 'on' == get_post_meta( $post_id, 'wpex_disable_toggle_bar', true ) ) {
			$return = false;
		}

	}

	// Apply filters and return
	// @todo Rename to "wpex_has_togglebar" for consistancy
	return apply_filters( 'wpex_toggle_bar_active', $return );

}

/**
 * Get correct togglebar style
 *
 * @since 4.0
 */
function wpex_togglebar_style() {
	return apply_filters( 'wpex_togglebar_style', wpex_get_mod( 'toggle_bar_display', 'overlay' ) );
}

/**
 * Returns correct togglebar classes
 *
 * @since Total 1.0.0
 */
function wpex_togglebar_classes() {

	// Add default classes
	$classes = array( 'wpex-clr' );

	// Display
	$display = wpex_togglebar_style();
	$classes[] = 'toggle-bar-'. $display;

	// Default state
	if ( 'visible' == wpex_get_mod( 'toggle_bar_default_state', 'hidden' ) ) {
		$classes[] = 'active-bar';
	}

	// Add animation classes
	if ( 'overlay' == $display && $animation = wpex_get_mod( 'toggle_bar_animation', 'fade' ) ) {
		$classes[] = 'toggle-bar-'. $animation;
	}

	// Add visibility classes
	if ( $visibility = wpex_get_mod( 'toggle_bar_visibility', 'always-visible' ) ) {
		$classes[] = $visibility;
	}

	// Apply filters for child theming
	$classes = apply_filters( 'wpex_toggle_bar_active', $classes );

	// Turn classes into space seperated string
	$classes = implode( ' ', $classes );

	// Return classes
	return apply_filters( 'wpex_togglebar_classes', $classes );

}

/**
 * Use custom page template while editing the togglebar via the VC
 *
 * @since 4.0
 */
function wpex_togglebar_builder_template( $template ) {
	if ( ! wpex_vc_is_inline() ) {
		return $template;
	}
	$id = wpex_togglebar_content_id();
	if ( $id && is_page( $id ) ) {
		$new_template = locate_template( array( 'partials/togglebar/builder-template.php' ) );
		if ( $new_template ) {
			$template = $new_template;
		}
	}
	return $template;
}
add_filter( 'template_include', 'wpex_togglebar_builder_template', 9999 );