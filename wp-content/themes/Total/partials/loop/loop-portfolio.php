<?php
/**
 * Main Loop
 *
 * @package Total WordPress theme
 * @subpackage Partials
 * @version 4.5
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Add to counter
global $wpex_count;
$wpex_count++;

	// Include template part
	get_template_part( 'partials/portfolio/portfolio-entry' );

// Clear Counter
if ( $wpex_count == wpex_portfolio_archive_columns() ) {
	$wpex_count=0;
}