<?php
/**
 * Helper functions for the testimonials post type
 *
 * @package Total WordPress Theme
 * @subpackage Testimonials Functions
 * @version 4.5.4
 */

/**
 * Returns correct thumbnail HTML for the testimonials entries
 *
 * @since 2.0.0
 */
function wpex_get_testimonials_entry_thumbnail() {
    return wpex_get_post_thumbnail( array(
        'size'  => 'testimonials_entry',
        'class' => 'testimonials-entry-img',
    ) );
}

/**
 * Returns testimonials archive columns
 *
 * @since 2.0.0
 */
function wpex_testimonials_archive_columns() {
	return wpex_get_mod( 'testimonials_entry_columns', '4' );
}

/**
 * Returns correct classes for the testimonials archive wrap
 *
 * @since 2.0.0
 */
function wpex_get_testimonials_wrap_classes() {

	// Define main classes
	$classes = array( 'wpex-row', 'clr' );

	// Add gap
	if ( $gap = wpex_get_mod( 'testimonials_archive_grid_gap' ) ) {
		$classes[] = 'gap-'. $gap;
	}

	// Apply filters
	$classes = apply_filters( 'wpex_testimonials_wrap_classes', $classes );

	// Turn array into string
	$classes = implode( " ", $classes );

	// Return
	return $classes;

}