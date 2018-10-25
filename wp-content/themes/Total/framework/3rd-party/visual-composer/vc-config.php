<?php
/**
 * Visual Composer configuration file
 *
 * @package Total WordPress Theme
 * @subpackage Visual Composer
 * @version 4.6.5
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Start class
class WPEX_Visual_Composer_Config {

	/**
	 * Start things up
	 *
	 * @since 1.6.0
	 */
	public function __construct() {

		// Define useful Paths
		define( 'WPEX_VCEX_DIR', WPEX_FRAMEWORK_DIR . '3rd-party/visual-composer/' );
		define( 'WPEX_VCEX_DIR_URI', WPEX_FRAMEWORK_DIR_URI . '3rd-party/visual-composer/' );

		// Global post CSS
		require_once WPEX_VCEX_DIR . 'vc-global-post-css.php';

		// Include helper functions and classes
		require_once WPEX_VCEX_DIR . 'vc-helpers.php';
		require_once WPEX_VCEX_DIR . 'helpers/build-query.php';
		require_once WPEX_VCEX_DIR . 'helpers/autocomplete.php';
		require_once WPEX_VCEX_DIR . 'vc-loadmore.php';

		// Disable Welcome message
		require_once WPEX_VCEX_DIR . 'vc-disable-welcome.php';

		// Remove core elements
		require_once WPEX_VCEX_DIR . 'vc-remove-elements.php';

		// Register accent colors
		require_once WPEX_VCEX_DIR . 'vc-accent-color.php';

		// Alter core vc modules
		require_once WPEX_VCEX_DIR . 'shortcode-mods/vc_section.php';
		require_once WPEX_VCEX_DIR . 'shortcode-mods/vc_row.php';
		require_once WPEX_VCEX_DIR . 'shortcode-mods/vc_column.php';
		require_once WPEX_VCEX_DIR . 'shortcode-mods/vc_single_image.php';
		require_once WPEX_VCEX_DIR . 'shortcode-mods/vc_column_text.php';
		require_once WPEX_VCEX_DIR . 'shortcode-mods/vc_tabs_tour.php';
		require_once WPEX_VCEX_DIR . 'shortcode-mods/vc_toggle.php';

		require_once WPEX_VCEX_DIR . 'shortcode-mods/vc-add-params.php';
		require_once WPEX_VCEX_DIR . 'shortcode-mods/vc-modify-params.php';

		// Parse attributes
		require_once WPEX_VCEX_DIR . 'parse-atts/row-atts.php';

		// Add new parameter types
		if ( function_exists( 'vc_add_shortcode_param' ) ) {
			require_once WPEX_VCEX_DIR . 'shortcode-params/custom-params.php';
		}

		// Add custom templates
		// @to do Add custom templates - best done via new media library style.
		require_once WPEX_VCEX_DIR . 'vc-templates.php';

		// Add shortcodes to the tinyMCE editor
		require_once WPEX_VCEX_DIR . 'vcex-tinymce-shortcodes.php';

		// Alter the font container tags
		require_once WPEX_VCEX_DIR . 'vc-font-container.php';

		// Disable functions for non active VC licenses
		if ( vcex_theme_mode_check() ) {
			require_once WPEX_VCEX_DIR . 'vc-disable-design-options.php';
			require_once WPEX_VCEX_DIR . 'vc-disable-updater.php';
			require_once WPEX_VCEX_DIR . 'vc-disable-template-library.php';
		}

		// Templatera tweaks
		if ( WPEX_TEMPLATERA_ACTIVE ) {
			require_once WPEX_VCEX_DIR . 'templatera.php';
		}

		// Register total VC modules
		if ( function_exists( 'vc_lean_map' )
			&& class_exists( 'WPBakeryShortCode' )
			&& wpex_get_mod( 'extend_visual_composer', true )
		) {
			require_once WPEX_VCEX_DIR . 'vcex-modules.php';
		}

		// Run on init
		add_action( 'init', array( 'WPEX_Visual_Composer_Config', 'init' ), 20 );
		add_action( 'admin_init', array( 'WPEX_Visual_Composer_Config', 'admin_init' ), 20 );

		// Tweak scripts
		add_action( 'wp_enqueue_scripts', array( 'WPEX_Visual_Composer_Config', 'load_composer_front_css' ), 0 );
		add_action( 'wp_enqueue_scripts', array( 'WPEX_Visual_Composer_Config', 'total_vc_css' ) );
		add_action( 'vc_frontend_editor_render',  array( 'WPEX_Visual_Composer_Config', 'remove_editor_font_awesome' ) );
		add_action( 'wp_footer', array( 'WPEX_Visual_Composer_Config', 'remove_footer_scripts' ) );

		// Admin/iFrame scrips
		add_action( 'admin_enqueue_scripts', array( 'WPEX_Visual_Composer_Config', 'admin_scripts' ) );
		add_action( 'vc_load_iframe_jscss', array( 'WPEX_Visual_Composer_Config', 'iframe_scripts' ) );

		// Popup scripts
		add_action( 'vc_frontend_editor_enqueue_js_css', array( 'WPEX_Visual_Composer_Config', 'popup_scripts' ) );
		add_action( 'vc_backend_editor_enqueue_js_css', array( 'WPEX_Visual_Composer_Config', 'popup_scripts' ) );

		// Replace lightbox - @todo finish after next big VC update since things will change
		//if ( wpex_get_mod( 'replace_vc_lightbox', true ) ) {
			//require_once WPEX_VCEX_DIR . 'vc-replace-prettyphoto.php';
		//}

		// Hide VC buttons in media library attachments in the backend
		add_action( 'admin_head', array( 'WPEX_Visual_Composer_Config', 'hide_vc_buttons_media_library' ) );

		// Add Customizer settings
		add_filter( 'wpex_customizer_panels', array( 'WPEX_Visual_Composer_Config', 'customizer_settings' ) );

		// Remove default templates => Do not edit due to extension plugin and snippets
		add_filter( 'vc_load_default_templates', '__return_empty_array' );

		// Add noscript tag for stretched rows
		if ( apply_filters( 'wpex_noscript_tags', true ) ) {
			add_action( 'wp_head', array( 'WPEX_Visual_Composer_Config', 'noscript' ), 60 );
		}

		// Add new background styles
		add_filter( 'vc_css_editor_background_style_options_data', array( 'WPEX_Visual_Composer_Config', 'background_styles' ) );

	}

	/**
	 * Functions that run on init
	 *
	 * @since 2.0.0
	 */
	public static function init() {

		if ( function_exists( 'visual_composer' ) ) {
			remove_action( 'wp_head', array( visual_composer(), 'addMetaData' ) );
		}

		if ( function_exists( 'vc_set_default_editor_post_types' ) ) {
			vc_set_default_editor_post_types( array( 'page', 'portfolio', 'staff' ) );
		}

	}

	/**
	 * Functions that run on admin_init
	 *
	 * @since 4.5
	 */
	public static function admin_init() {

		// Tweak VC logo - remove's their link
		add_filter( 'vc_nav_front_logo', array( 'WPEX_Visual_Composer_Config', 'editor_nav_logo' ) );

		// Remove purchase notice
		wpex_remove_class_filter( 'admin_notices', 'Vc_License', 'adminNoticeLicenseActivation', 10 );

	}

	/**
	 * Override editor logo
	 *
	 * @since 3.0.0
	 */
	public static function editor_nav_logo() {
		return '<div id="vc_logo" class="vc_navbar-brand" aria-hidden="true"></div>';
	}

	/**
	 * Load js_composer_front CSS eaerly on for easier modification
	 *
	 * @since  2.1.3
	 */
	public static function load_composer_front_css() {
		wp_enqueue_style( 'js_composer_front' );
	}

	/**
	 * Load and remove stylesheets
	 *
	 * @since 2.0.0
	 */
	public static function total_vc_css() {

		// Add Scripts
		wp_enqueue_style(
			'wpex-visual-composer',
			wpex_asset_url( 'css/wpex-visual-composer.css' ),
			array( WPEX_THEME_STYLE_HANDLE, 'js_composer_front' ),
			WPEX_THEME_VERSION
		);

		wp_enqueue_style(
			'wpex-visual-composer-extend',
			wpex_asset_url( 'css/wpex-visual-composer-extend.css' ),
			array( WPEX_THEME_STYLE_HANDLE, 'js_composer_front' ),
			WPEX_THEME_VERSION
		);

		/* Remove Scripts to fix Customizer issue with jQuery UI
		 * Fixed in WP 4.4
		 * @deprecated 3.3.0
		if ( is_customize_preview() ) {
			wp_deregister_script( 'wpb_composer_front_js' );
			wp_dequeue_script( 'wpb_composer_front_js' );
		}*/

	}

	/**
	 * Remove scripts from backend editor
	 *
	 * @since 3.6.0
	 */
	public static function remove_editor_font_awesome() {
		wp_deregister_style( 'font-awesome' );
		wp_dequeue_style( 'font-awesome' );
	}

	/**
	 * Remove scripts hooked in too late for me to remove on wp_enqueue_scripts
	 *
	 * @since 2.1.0
	 */
	public static function remove_footer_scripts() {

		// JS
		wp_dequeue_script( 'vc_pageable_owl-carousel' );
		wp_dequeue_script( 'vc_grid-js-imagesloaded' );

		// Styles conflict with Total owl carousel styles
		wp_deregister_style( 'vc_pageable_owl-carousel-css-theme' );
		wp_dequeue_style( 'vc_pageable_owl-carousel-css-theme' );
		wp_deregister_style( 'vc_pageable_owl-carousel-css' );
		wp_dequeue_style( 'vc_pageable_owl-carousel-css' );

	}

	/**
	 * Admin Scripts
	 *
	 * @since 1.6.0
	 */
	public static function admin_scripts( $hook ) {

		$hooks = array(
			'edit.php',
			'post.php',
			'post-new.php',
			'widgets.php',
			'toolset_page_ct-editor', // Support VC widget plugin
		);

		if ( ! in_array( $hook, $hooks ) ) {
			return;
		}

		wp_enqueue_style(
			'vcex-admin',
			wpex_asset_url( 'css/wpex-visual-composer-admin.css' ),
			array(),
			WPEX_THEME_VERSION
		);

		if ( is_rtl() ) {
			wp_enqueue_style(
				'vcex-admin-rtl',
				wpex_asset_url( 'css/wpex-visual-composer-admin-rtl.css' ),
				array(),
				WPEX_THEME_VERSION
			);
		}

	}

	/**
	 * iFrame Scripts
	 *
	 * @since 4.0
	 */
	public static function iframe_scripts() {

		wp_enqueue_style(
			'vcex-iframe-css',
			wpex_asset_url( 'css/wpex-visual-composer-iframe.css' ),
			array(),
			WPEX_THEME_VERSION
		);

	}

	/**
	 * Popup Window Scripts
	 *
	 * @since 4.0
	 */
	public static function popup_scripts() {

		wp_enqueue_script(
			'wpex-chosen-js',
			wpex_asset_url( 'lib/chosen/chosen.jquery.min.js' ),
			array( 'jquery' ),
			'1.4.1',
			true
		);

		wp_enqueue_style(
			'wpex-chosen-css',
			wpex_asset_url( 'lib/chosen/chosen.min.css' ),
			false,
			'1.4.1'
		);

		wp_enqueue_style(
			'vcex-admin',
			wpex_asset_url( 'css/wpex-visual-composer-admin.css' ),
			array(),
			WPEX_THEME_VERSION
		);

	}

	/**
	 * Hide VC buttons in media library attachments in the backend for multi-sites
	 *
	 * @since 4.0
	 */
	public static function hide_vc_buttons_media_library() {
		$screen = get_current_screen();
		$hide_types = apply_filters( 'wpex_admin_hide_vc_editor_post_types', array( 'attachment', 'acf', 'wpex_sidebars' ) );
		if ( isset( $screen->id ) && in_array( $screen->id, $hide_types ) ) {
			echo '<style>.composer-switch{display:none!important;}</style>';
		}

	}

	/**
	 * Adds Customizer settings for VC
	 *
	 * @since 4.0
	 */
	public static function customizer_settings( $panels ) {
		$panels['visual_composer'] = array(
			'title'      => __( 'Visual Composer', 'total' ),
			'settings'   => WPEX_VCEX_DIR . 'vc-customizer-settings.php',
			'is_section' => true,
		);
		return $panels;
	}

	/**
	 * Add noscript tag for stretched rows
	 *
	 * @since 4.4.1
	 */
	public static function noscript() {
		echo '<noscript><style>body .wpex-vc-row-stretched, body .vc_row-o-full-height { visibility: visible; }</style></noscript>';
	}

	/**
	 * Add noscript tag for stretched rows
	 *
	 * @since 4.5.4
	 */
	public static function background_styles( $styles ) {
		$styles[__( 'Repeat-x', 'js_composer' )] = 'repeat-x';
		$styles[__( 'Repeat-y', 'js_composer' )] = 'repeat-y';
		return $styles;
	}
	

}
new WPEX_Visual_Composer_Config();

/*
 * @todo Allow shortcodes in some modules so they can be used with dynamic template function
 * Example:
add_filter( 'shortcode_atts_vc_gmaps', function( $out, $pairs, $atts ) {
	if ( isset( $out['link'] ) ) {
		$out['link'] = do_shortcode( trim( vc_value_from_safe( $out['link'] ) ) );
	}
	return $out;
}, 40, 3 ); */