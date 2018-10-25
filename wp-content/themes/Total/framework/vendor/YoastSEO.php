<?php
/**
 * Yoast SEO Configuration Class
 *
 * @package Total WordPress Theme
 * @subpackage 3rd Party
 * @version 4.6.5
 */

namespace TotalTheme\Vendor;

use WPEX_Breadcrumbs;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class YoastSEO {

	/**
	 * Start things up
	 *
	 * @version 3.3.0
	 */
	public function __construct() {

		// Add support for Yoast SEO breadcrumb settings in the WP Customizer
		add_theme_support( 'yoast-seo-breadcrumbs' );

		// Customize theme breadcrumbs
		if ( function_exists( 'yoast_breadcrumb' ) ) {
			add_filter( 'wpex_custom_breadcrumbs', array( $this, 'breacrumbs' ) );
		}

		// Filter the ancestors of the yoast seo breadcrumbs
		if ( apply_filters( 'wpex_filter_wpseo_breadcrumb_links', true ) ) {
			add_filter( 'wpseo_breadcrumb_links', array( $this, 'wpseo_breadcrumb_links' ) );
		}

		// Trim the title
		add_filter( 'wpseo_breadcrumb_single_link_info', array( $this, 'trim_title' ), 10, 3 );

		// Make sure there is a description
		if ( apply_filters( 'wpex_filter_wpseo_metadesc', true ) ) {
			add_filter( 'wpseo_metadesc', array( $this, 'metadesc' ) );
		}

	}

	/**
	 * Override breadcrumbs
	 *
	 * @version 3.3.5
	 */
	public function breacrumbs() {

		if ( current_theme_supports( 'yoast-seo-breadcrumbs' ) ) {

			// Define classes variable
			$classes = '';

			// Get classes from main class
			if ( class_exists( 'WPEX_Breadcrumbs' ) ) {
				$classes = WPEX_Breadcrumbs::wrap_classes();
			}

			// Breadcrumbs settings
			$before  = '<nav class="'. esc_attr( $classes ) .'"><span class="breadcrumb-trail">';
			$after   = '</span></nav>';
			$display = false;

			// Return breadcrumbs
			return yoast_breadcrumb( $before, $after, $display );

		}

	}

	/**
	 * Filter the ancestors of the yoast seo breadcrumbs
	 * Adds the portfolio, staff, testimonials and blog links
	 *
	 * @version 3.3.0
	 */
	public function wpseo_breadcrumb_links( $links ) {

		global $post;
		$new_breadcrumb = array();

		// Loop through items
		$types = array( 'portfolio', 'staff', 'testimonials', 'post' );
		foreach ( $types as $type ) {
			if ( is_singular( $type ) ) {
				if ( 'post' == $type ) {
					$type = 'blog';
				}
				$page_id = wpex_parse_obj_id( wpex_get_mod( $type . '_page' ), 'page' );
				if ( $page_id ) {
					$page_title     = get_the_title( $page_id );
					$page_permalink = get_permalink( $page_id );
					if ( $page_permalink && $page_title ) {
						$new_breadcrumb[] = array(
							'url'  => $page_permalink,
							'text' => $page_title,
						);
					}
				}
			}
		} // End foreach loop

		// Combine new crumb
		if ( $new_breadcrumb ) {
			array_splice( $links, 1, -2, $new_breadcrumb );
		}

		// Return links
		return $links;

	}

	/**
	 * Trim the Yoast SEO title
	 *
	 * @version 3.3.2
	 */
	public function trim_title( $link_info, $index, $crumbs ) {
		$trim = absint( wpex_get_mod( 'breadcrumbs_title_trim' ) );
		if ( $trim && is_array( $crumbs ) && ( absint( $index ) + 1 == count( $crumbs ) ) ) {
			if ( isset( $link_info['text'] ) ) {
				$link_info['text'] = wp_trim_words( $link_info['text'], $trim );
			}
		}
		return $link_info;
	}

	/**
	 * Auto Generate meta description if empty using Total excerpt function.
	 *
	 * @version 3.3.2
	 */
	public function metadesc( $metadesc ) {
		if ( ! $metadesc && is_singular() ) {
			$metadesc = wpex_get_excerpt( array(
				'length'    => apply_filters( 'wpex_yoast_metadesc_length', 160 ),
				'trim_type' => 'characters',
				'more'      => null,
			) );
		}
		return trim( wp_strip_all_tags( $metadesc ) );
	}

}
new YoastSEO();