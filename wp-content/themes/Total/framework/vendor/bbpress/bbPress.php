<?php
/**
 * bbPress Configuration Class
 *
 * @package Total WordPress Theme
 * @subpackage 3rd Party
 * @version 4.8
 */

namespace TotalTheme;

use WPEX_Breadcrumbs;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class bbPress {

	/**
	 * Start things up
	 *
	 * @access public
	 * @since  3.6.0
	 */
	public function __construct() {

		// Define bbPress directory
		define( 'WPEX_BBPRESS_DIR', WPEX_FRAMEWORK_DIR . 'vendor/bbpress/' );

		// Load custom CSS for bbPress
		add_action( 'wp_enqueue_scripts', array( $this, 'scripts' ), 10 );

		// Load bbPress widgets
		add_action( 'widgets_init', array( $this, 'custom_widgets' ), 10 );

		// Add a bbPress sidebar
		if ( wpex_get_mod( 'bbpress_custom_sidebar', true ) ) {
			add_filter( 'wpex_register_sidebars_array', array( $this, 'register_sidebar' ), 10 );
		}

		// Alter main sidebar to display bbPress sidebar
		add_filter( 'wpex_get_sidebar', array( $this, 'display_sidebar' ), 10 );

		// Remove page title
		add_filter( 'wpex_single_blocks', array( $this, 'page_blocks' ), 99, 2 );

		// Disable next/prev
		add_filter( 'wpex_has_next_prev', array( $this, 'next_prev' ), 99 );

		// Set correct bbPress layouts
		add_filter( 'wpex_post_layout_class', array( $this, 'layouts' ), 10 );

		// Title fixes
		add_filter( 'wpex_title', array( $this, 'title' ), 10 );

		// Add custom post classes
		add_filter( 'post_class', array( $this, 'post_class' ), 10 );

		// Breadcrumbs Fixes
		if ( class_exists( 'WPEX_Breadcrumbs' ) ) {
			add_filter( 'wpex_breadcrumbs_trail', array( $this, 'breadcrumbs' ), 10 );
		}

		// Accents
		add_filter( 'wpex_accent_texts', array( $this, 'accent_texts' ), 10 );
		add_filter( 'wpex_accent_backgrounds', array( $this, 'accent_backgrounds' ), 10 );

		// Add Customizer panel
		add_filter( 'wpex_customizer_panels', array( $this, 'add_customizer_panel' ), 10 );

		// Add to localize array
		add_filter( 'wpex_localize_array', array( $this, 'localize_array' ), 10 );

	}

	/**
	 * Load custom CSS for bbPress
	 *
	 * Must load globally because of Widgets
	 *
	 * @since  3.6.0
	 */
	public function scripts() {
		wp_enqueue_style(
			'wpex-bbpress',
			wpex_asset_url( 'css/wpex-bbpress.css' ),
			array( 'bbp-default' ),
			WPEX_THEME_VERSION
		);
	}

	/**
	 * Load custom widgets
	 *
	 * @since  3.6.0
	 */
	public function custom_widgets() {
		require_once WPEX_BBPRESS_DIR . 'classes/ForumInfoWidget.php';
		require_once WPEX_BBPRESS_DIR . 'classes/TopicInfoWidget.php';
	}

	/**
	 * Registers a bbpress_sidebar widget area
	 *
	 * @since  3.6.0
	 */
	public function register_sidebar( $sidebars ) {
		$sidebars['bbpress_sidebar'] = esc_html__( 'bbPress Sidebar', 'total' );
		return $sidebars;
	}

	/**
	 * Alter main sidebar to display bbpress_sidebar sidebar
	 *
	 * @since  3.6.0
	 */
	public function display_sidebar( $sidebar ) {
		if ( function_exists( 'is_bbpress' )
			&& is_bbpress()
			&& wpex_get_mod( 'bbpress_custom_sidebar', true )
		) {
			$sidebar = 'bbpress_sidebar';
		}
		return $sidebar;
	}

	/**
	 * Alter page blocks to remove elements that aren't needed for bbPress
	 *
	 * @since  3.6.0
	 */
	public function page_blocks( $blocks, $type ) {
		if ( function_exists( 'is_bbpress' ) && is_bbpress() ) {
			return array( 'content' ); // Only content needed
		}
		return $blocks;
	}

	/**
	 * Disable next/prev for bbPress
	 *
	 * @since  3.6.0
	 */
	public function next_prev( $bool ) {
		if ( function_exists( 'is_bbpress' ) && is_bbpress() ) {
			return false;
		}
		return $bool;
	}

	/**
	 * Set layouts
	 *
	 * @version 4.5
	 */
	public function layouts( $layout ) {

		// Forum
		if ( bbp_is_forum_archive() ) {
			$layout = wpex_get_mod( 'bbpress_forums_layout' );
		}

		// Single forum
		if ( bbp_is_single_forum() ) {
			$layout = wpex_get_mod( 'bbpress_single_forum_layout' );
		}

		// Topics
		if ( bbp_is_topic_archive() ) {
			$layout = wpex_get_mod( 'bbpress_topics_layout' );
		}

		// Topics
		if ( bbp_is_single_topic()
			|| bbp_is_topic_edit()
			|| bbp_is_topic_merge()
			|| bbp_is_topic_split()
		) {
			$layout = wpex_get_mod( 'bbpress_single_topic_layout' );
		}

		// User profile
		if ( bbp_is_single_user() ) {
			$layout = wpex_get_mod( 'bbpress_user_layout', 'full-width' );
		}

		// Return layout
		return $layout;

	}

	/**
	 * Fix page header title
	 *
	 * @since 3.6.0
	 */
	public function title( $title ) {
		if ( bbp_is_single_forum()
			|| bbp_is_single_topic()
			|| bbp_is_topic_edit()
			|| bbp_is_topic_merge()
			|| bbp_is_topic_split()
		) {
			$title = get_the_title();
		}
		return $title;
	}

	/**
	 * Add custom post classes
	 *
	 * @since 3.6.0
	 */
	public function post_class( $classes ) {
		if ( 'forum' == get_post_type() ) {
			$count = bbp_show_lead_topic() ? bbp_get_forum_reply_count() : bbp_get_forum_post_count();
			$count = ( 0 == $count ) ? 'no' : $count;
			$classes[] = $count .'-replies';
		}
		return $classes;
	}

	/**
	 * Fix Breadcrumbs trail
	 *
	 * @since 3.6.0
	 */
	public function breadcrumbs( $trail ) {

		// Set correct archive for single topic
		if ( bbp_is_single_topic() || bbp_is_single_user() ) {
			$obj = get_post_type_object( 'forum' );
			if ( $obj && $forums_link = get_post_type_archive_link( 'forum' ) ) {
				$trail['post_type_archive'] = WPEX_Breadcrumbs::get_crumb_html( $obj->labels->name, $forums_link, 'trail-forums' );
			}
			/*
			@deprecated in 4.6 - already included in main crumbs
			if ( bbp_is_single_topic() ) {
				$forum = wp_get_post_parent_id( get_the_ID() );
				if ( $forum ) {
					$text = get_the_title( $forum );
					$link = get_permalink( $forum );
					$trail['pre_trail_end'] = WPEX_Breadcrumbs::get_crumb_html( $text, $link, 'trail-forums' );
				}
			}*/
		}

		// Set correct end_trail for user
		if ( bbp_is_single_user() ) {
			$trail['trail_end'] = get_the_title();
		}

		// Search results
		if ( bbp_is_search_results() ) {
			$obj = get_post_type_object( 'forum' );
			if ( $obj && $forums_link = get_post_type_archive_link( 'forum' ) ) {
				$trail['post_type_archive'] = WPEX_Breadcrumbs::get_crumb_html( $obj->labels->name, $forums_link, 'trail-forums' );
			}
			$trail['trail_end'] = __( 'Search Results', 'total' );
		}

		// Return breadcrumbs trail
		return $trail;

	}

	/**
	 * Adds new elements for text accents
	 *
	 * @since 3.6.0
	 */
	public function accent_texts( $elements ) {
		return array_merge( array(
			'.bbp-body a.bbp-forum-title',
			'.bbp-reply-title a.bbp-topic-permalink',
		), $elements );
		return $elements;
	}

	/**
	 * Adds new elements for background accents
	 *
	 * @since 3.6.0
	 */
	public function accent_backgrounds( $elements ) {
		return array_merge( array(
			'#bbpress-forums #bbp-single-user-details #bbp-user-navigation li.current a',
		), $elements );
		return $elements;
	}

	/**
	 * Adds new Customizer Panel for bbPress
	 *
	 * @since 3.6.0
	 */
	public function add_customizer_panel( $panels ) {
		$panels['bbpress'] = array(
			'title'      => __( 'bbPress', 'total' ),
			'is_section' => true,
			'settings'   => WPEX_BBPRESS_DIR . 'customizer.php'
		);
		return $panels;
	}

	/**
	 * Add settings to the localize array
	 *
	 * @since 4.5.4
	 */
	public function localize_array( $settings ) {
		$custom_selects = isset( $settings['customSelects'] ) ? $settings['customSelects'] : '';
		$settings['customSelects'] = $custom_selects . ',#bbp_stick_topic_select,#bbp_topic_status_select,#bbp_destination_topic';
		return $settings;
	}

}
new bbPress();