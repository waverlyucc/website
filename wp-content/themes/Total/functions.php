<?php
/**
 * This file loads all the needed files for the theme to work properly.
 *
 * DO NOT : USE AN ILLEGAL COPY OF THIS THEME
 * DO NOT : EVER EDIT THIS FILE
 * DO NOT : COPY AND PASTE THIS FILE INTO YOUR CHILD THEME
 * DO NOT : COPY AND PASTE ANYTHING FROM THIS FILE TO YOUR CHILD THEME BECAUSE IT WILL CAUSE ERRORS
 * YES    : USE HOOKS, FILTERS & TEMPLATE PARTS TO ALTER THIS THEME VIA A CHILD THEME
 *
 * Theme Docs        : https://wpexplorer-themes.com/total/docs/
 * Request Support   : https://wpexplorer-themes.com/total/docs/how-to-request-support/
 * Theme Snippets    : https://wpexplorer-themes.com/total/snippets/
 * Using Hooks       : https://wpexplorer-themes.com/total/docs/action-hooks/
 * Theme Support     : https://wpexplorer-themes.com/support/ (valid purchase & support license required)
 *
 * @package Total WordPress Theme
 * @subpackage Templates
 * @version 4.8.5
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Start up class
final class WPEX_Theme_Setup {

	/**
	 * WPEX_Theme_Setup Constructor
	 *
	 * Loads all necessary classes, functions, hooks, configuration files and actions for the theme.
	 * Everything starts here.
	 *
	 * @since 1.6.0
	 *
	 */
	public function __construct() {

		// Define constants
		$this->constants();

		// Store theme mods in $wpex_theme_mods global variable and register theme mod functions
		require_once WPEX_THEME_DIR . '/framework/theme-mods.php';

		// Include main Admin Panel
		require_once WPEX_THEME_DIR . '/framework/AdminPanel.php';

		// Perform actions after updating the theme => Run before anything else
		require_once WPEX_THEME_DIR . '/framework/updates/after-update.php';

		// Load all core theme function files
		// Load Before classes and addons so we can make use of them => IMPORTANT!!!
		$this->main_includes();

		// Load configuration classes (post types & 3rd party plugins)
		// Must load first so it can use hooks defined in the classes
		add_action( 'after_setup_theme', array( $this, 'configs' ), 3 );

		// Load framework classes
		// Runs after config files since there are filters used in the config files that may target these classes
		add_action( 'after_setup_theme', array( $this, 'classes' ), 4 );

		// Setup theme => add_theme_support, register_nav_menus, load_theme_textdomain, etc
		// Must run on 10 priority or else child theme locale will be overritten
		add_action( 'after_setup_theme', array( $this, 'theme_setup' ), 10 );

		// Defines hooks and adds theme actions
		// Moved to after_setup_theme hook in v3.6.0 so it can be accessed earlier if needed
		// to remove actions
		add_action( 'after_setup_theme', array( $this, 'hooks_actions' ), 10 );

	} // End constructor

	/**
	 * Defines the constants for use within the theme.
	 *
	 * @since 2.0.0
	 */
	public function constants() {

		define( 'TOTAL_THEME_ACTIVE', true );
		define( 'WPEX_THEME_VERSION', '4.8.5' );
		define( 'WPEX_VC_SUPPORTED_VERSION', '5.7' );

		define( 'WPEX_THEME_DIR', get_template_directory() );
		define( 'WPEX_THEME_URI', get_template_directory_uri() );

		define( 'WPEX_THEME_BRANDING', get_theme_mod( 'theme_branding', 'Total' ) );

		define( 'WPEX_THEME_PANEL_SLUG', 'wpex-panel' );
		define( 'WPEX_ADMIN_PANEL_HOOK_PREFIX', 'theme-panel_page_' . WPEX_THEME_PANEL_SLUG );

		define( 'WPEX_FRAMEWORK_DIR', WPEX_THEME_DIR . '/framework/' );
		define( 'WPEX_FRAMEWORK_DIR_URI', WPEX_THEME_URI . '/framework/' );

		define( 'WPEX_ClASSES_DIR', WPEX_FRAMEWORK_DIR . 'classes/' );

		define( 'WPEX_THEME_STYLE_HANDLE', 'wpex-style' );
		define( 'WPEX_THEME_JS_HANDLE', 'wpex-core' );

		define( 'WPEX_VC_ACTIVE', class_exists( 'Vc_Manager' ) );
		define( 'WPEX_TEMPLATERA_ACTIVE', class_exists( 'VcTemplateManager' ) );
		define( 'WPEX_BBPRESS_ACTIVE', class_exists( 'bbPress' ) );
		define( 'WPEX_WOOCOMMERCE_ACTIVE', class_exists( 'WooCommerce' ) );
		define( 'WPEX_WPML_ACTIVE', class_exists( 'SitePress' ) );

		define( 'WPEX_PORTFOLIO_IS_ACTIVE', get_theme_mod( 'portfolio_enable', true ) );
		define( 'WPEX_STAFF_IS_ACTIVE', get_theme_mod( 'staff_enable', true ) );
		define( 'WPEX_TESTIMONIALS_IS_ACTIVE', get_theme_mod( 'testimonials_enable', true ) );

	}

	/**
	 * Defines all theme hooks and runs all needed actions for theme hooks.
	 *
	 * @since 2.0.0
	 */
	public function hooks_actions() {

		// Register hooks (needed in admin for Custom Actions panel)
		require_once WPEX_FRAMEWORK_DIR . 'hooks/hooks.php';

		// Core theme hooks and actions // if running in backend it breaks VC grid builder
		if ( ! is_admin() ) {
			require_once WPEX_FRAMEWORK_DIR . 'hooks/actions.php';
			require_once WPEX_FRAMEWORK_DIR . 'hooks/partials.php';
		}

	}

	/**
	 * Framework functions
	 *
	 * IMPORTANT: Load before Classes & Addons so we can make use of them
	 *
	 * @since 2.0.0
	 */
	public function main_includes() {

		if ( class_exists( 'OCDI\OneClickDemoImport' ) ) {
			require_once WPEX_FRAMEWORK_DIR . 'vendor/OCDI.php';
		}

		require_once WPEX_FRAMEWORK_DIR . 'sanitize.php';
		require_once WPEX_FRAMEWORK_DIR . 'conditionals.php';
		require_once WPEX_FRAMEWORK_DIR . 'core-functions.php';

		require_once WPEX_FRAMEWORK_DIR . 'classes/License.php';
		require_once WPEX_FRAMEWORK_DIR . 'classes/ImportExport.php';
		require_once WPEX_FRAMEWORK_DIR . 'classes/Accessibility.php';

		require_once WPEX_FRAMEWORK_DIR . 'template-parts.php';
		require_once WPEX_FRAMEWORK_DIR . 'arrays.php';
		require_once WPEX_FRAMEWORK_DIR . 'shortcodes.php';

		require_once WPEX_ClASSES_DIR . 'MetaBoxes.php';
		require_once WPEX_ClASSES_DIR . 'ResizeImage.php';
		require_once WPEX_ClASSES_DIR . 'ParseInlineStyle.php';
		require_once WPEX_ClASSES_DIR . 'TermMeta.php';

		require_once WPEX_FRAMEWORK_DIR . 'helpers/js-localize-data.php';
		require_once WPEX_FRAMEWORK_DIR . 'helpers/aria-landmarks.php';
		require_once WPEX_FRAMEWORK_DIR . 'helpers/post-thumbnails.php';
		require_once WPEX_FRAMEWORK_DIR . 'helpers/post-types.php';
		require_once WPEX_FRAMEWORK_DIR . 'helpers/translations.php';
		require_once WPEX_FRAMEWORK_DIR . 'helpers/fonts.php';
		require_once WPEX_FRAMEWORK_DIR . 'helpers/schema-markup.php';
		require_once WPEX_FRAMEWORK_DIR . 'helpers/overlays.php';
		require_once WPEX_FRAMEWORK_DIR . 'helpers/social-share.php';
		require_once WPEX_FRAMEWORK_DIR . 'helpers/videos.php';
		require_once WPEX_FRAMEWORK_DIR . 'helpers/audio.php';
		require_once WPEX_FRAMEWORK_DIR . 'helpers/post-media.php';
		require_once WPEX_FRAMEWORK_DIR . 'helpers/excerpts.php';
		require_once WPEX_FRAMEWORK_DIR . 'helpers/togglebar.php';
		require_once WPEX_FRAMEWORK_DIR . 'helpers/topbar.php';
		require_once WPEX_FRAMEWORK_DIR . 'helpers/header.php';
		require_once WPEX_FRAMEWORK_DIR . 'helpers/header-menu.php';
		require_once WPEX_FRAMEWORK_DIR . 'helpers/title.php';
		require_once WPEX_FRAMEWORK_DIR . 'helpers/post-slider.php';
		require_once WPEX_FRAMEWORK_DIR . 'helpers/page-header.php';
		require_once WPEX_FRAMEWORK_DIR . 'helpers/callout.php';
		require_once WPEX_FRAMEWORK_DIR . 'helpers/footer.php';
		require_once WPEX_FRAMEWORK_DIR . 'helpers/pagination.php';
		require_once WPEX_FRAMEWORK_DIR . 'helpers/blog.php';
		require_once WPEX_FRAMEWORK_DIR . 'helpers/instagram-feed.php';

		require_once WPEX_FRAMEWORK_DIR . 'helpers/portfolio.php';
		require_once WPEX_FRAMEWORK_DIR . 'helpers/testimonials.php';
		require_once WPEX_FRAMEWORK_DIR . 'helpers/staff.php';

		require_once WPEX_FRAMEWORK_DIR . 'wp-actions/widgets-init.php';
		require_once WPEX_FRAMEWORK_DIR . 'wp-actions/enqueue-scripts.php';
		require_once WPEX_FRAMEWORK_DIR . 'wp-actions/template-redirect.php';
		require_once WPEX_FRAMEWORK_DIR . 'wp-actions/meta-viewport.php';
		require_once WPEX_FRAMEWORK_DIR . 'wp-actions/meta-generator.php';
		require_once WPEX_FRAMEWORK_DIR . 'wp-actions/pre-get-posts.php';

		if ( apply_filters( 'wpex_x_ua_compatible_headers', true ) ) {
			require_once WPEX_FRAMEWORK_DIR . 'wp-actions/x-ua-compatible-headers.php';
		}

		require_once WPEX_FRAMEWORK_DIR . 'wp-filters/honor-ssl-for-attachements.php';
		require_once WPEX_FRAMEWORK_DIR . 'wp-filters/body-class.php';
		require_once WPEX_FRAMEWORK_DIR . 'wp-filters/post-class.php';
		require_once WPEX_FRAMEWORK_DIR . 'wp-filters/comments-link.php';
		require_once WPEX_FRAMEWORK_DIR . 'wp-filters/tagcloud-args.php';
		require_once WPEX_FRAMEWORK_DIR . 'wp-filters/oembed.php';
		require_once WPEX_FRAMEWORK_DIR . 'wp-filters/wp-list-categories-args.php';
		require_once WPEX_FRAMEWORK_DIR . 'wp-filters/next-previous-posts-exclude.php';
		require_once WPEX_FRAMEWORK_DIR . 'wp-filters/kses-allowed-protocols.php';
		require_once WPEX_FRAMEWORK_DIR . 'wp-filters/singular-pagination-fix.php';
		require_once WPEX_FRAMEWORK_DIR . 'wp-filters/move-comment-form-fields.php';
		require_once WPEX_FRAMEWORK_DIR . 'wp-filters/authors-posts-link-schema.php';
		require_once WPEX_FRAMEWORK_DIR . 'wp-filters/custom-password-protected-form.php';

		if ( wpex_get_mod( 'remove_emoji_scripts_enable', true ) ) {
			require_once WPEX_FRAMEWORK_DIR . 'wp-actions/remove-emoji-scripts.php';
		}

		// Admin only functions
		if ( is_admin() ) {

			require_once WPEX_FRAMEWORK_DIR . 'wp-actions/delete-term-data.php';
			require_once WPEX_FRAMEWORK_DIR . 'wp-actions/after-switch-theme.php';
			require_once WPEX_FRAMEWORK_DIR . 'wp-actions/admin-enqueue-scripts.php';

			require_once WPEX_FRAMEWORK_DIR . 'wp-filters/disable-wp-update-check.php';
			require_once WPEX_FRAMEWORK_DIR . 'wp-filters/tiny-mce-font-sizes.php';
			require_once WPEX_FRAMEWORK_DIR . 'wp-filters/tiny-mce-buttons.php';
			require_once WPEX_FRAMEWORK_DIR . 'wp-filters/dashboard-thumbnails.php';
			require_once WPEX_FRAMEWORK_DIR . 'wp-filters/user-contact-methods.php';

		}

		// Include deprecated functions if enabled
		if ( wpex_load_deprecated_functions() ) {
			require_once WPEX_FRAMEWORK_DIR . 'deprecated/deprecated.php';
		}

	}

	/**
	 * Configs for post types and 3rd party plugins.
	 *
	 * @since 2.0.0
	 */
	public function configs() {

		if ( WPEX_PORTFOLIO_IS_ACTIVE ) {
			require_once WPEX_FRAMEWORK_DIR . 'classes/PostTypes/Portfolio.php';
		}

		if ( WPEX_STAFF_IS_ACTIVE ) {
			require_once WPEX_FRAMEWORK_DIR . 'classes/PostTypes/Staff.php';
		}

		if ( WPEX_TESTIMONIALS_IS_ACTIVE ) {
			require_once WPEX_FRAMEWORK_DIR . 'classes/PostTypes/Testimonials.php';
		}

		if ( WPEX_WOOCOMMERCE_ACTIVE && wpex_woo_version_supported() ) {
			require_once WPEX_FRAMEWORK_DIR . 'vendor/woocommerce/woocommerce.php';
		}

		if ( WPEX_VC_ACTIVE && wpex_has_vc_mods() ) {
			require_once WPEX_FRAMEWORK_DIR . 'vendor/wpbakery/vc-config.php';
		}

		if ( class_exists( 'Tribe__Events__Main' ) && apply_filters( 'wpex_tribe_events', true ) ) {
			require_once WPEX_FRAMEWORK_DIR . 'vendor/tribe-events/TribeEvents.php';
		}

		if ( WPEX_WPML_ACTIVE ) {
			require_once WPEX_FRAMEWORK_DIR . 'vendor/WPML.php';
		}

		if ( class_exists( 'Polylang' ) ) {
			require_once WPEX_FRAMEWORK_DIR . 'vendor/Polylang.php';
		}

		if ( WPEX_BBPRESS_ACTIVE ) {
			require_once WPEX_FRAMEWORK_DIR . 'vendor/bbpress/bbPress.php';
		}

		if ( function_exists( 'buddypress' ) ) {
			require_once WPEX_FRAMEWORK_DIR . 'vendor/BuddyPress.php';
		}

		if ( function_exists( 'Sensei' ) ) {
			require_once WPEX_FRAMEWORK_DIR . 'vendor/sensei.php';
		}

		if ( defined( 'WPSEO_VERSION' ) && apply_filters( 'wpex_yoast_support', true ) ) {
			require_once WPEX_FRAMEWORK_DIR . 'vendor/YoastSEO.php';
		}

		if ( defined( 'WPCF7_VERSION' ) ) {
			require_once WPEX_FRAMEWORK_DIR . 'vendor/ContactForm7.php';
		}

		if ( defined( 'RML_VERSION' ) ) {
			require_once WPEX_FRAMEWORK_DIR . 'vendor/real-media-library.php';
		}

		if ( class_exists( 'RevSlider' ) ) {
			require_once WPEX_FRAMEWORK_DIR . 'vendor/Revslider.php';
		}

		if ( class_exists( 'TablePress' ) ) {
			require_once WPEX_FRAMEWORK_DIR . 'vendor/TablePress.php';
		}

		if ( class_exists( 'LS_Sliders' ) ) {
			require_once WPEX_FRAMEWORK_DIR . 'vendor/LayerSlider.php';
		}

		if ( class_exists( 'Jetpack' ) && apply_filters( 'wpex_jetpack_support', true ) ) {
			require_once WPEX_FRAMEWORK_DIR . 'vendor/JetPack.php';
		}

		if ( class_exists( 'RGForms' ) ) {
			require_once WPEX_FRAMEWORK_DIR . 'vendor/GravityForms.php';
		}

		if ( class_exists( 'Post_Types_Unlimited' ) ) {
			require_once WPEX_FRAMEWORK_DIR . 'vendor/PostTypesUnlimited.php';
		}

		if ( function_exists( 'cptui_init' ) ) {
			require_once WPEX_FRAMEWORK_DIR . 'vendor/cpt-ui.php';
		}

		if ( defined( 'MPC_MASSIVE_VERSION' ) ) {
			require_once WPEX_FRAMEWORK_DIR . 'vendor/MassiveAddons.php';
		}

	}

	/**
	 * Framework Classes
	 *
	 * @since 2.0.0
	 */
	public function classes() {

		// Takes an input and outputs sanitized data
		require_once WPEX_ClASSES_DIR . 'SanitizeData.php';

		// Loads iLightbox scripts and registers lightbox settings
		require_once WPEX_ClASSES_DIR . 'iLightbox.php';

		// Adds new Post Series taxonomy
		if ( wpex_get_mod( 'post_series_enable', true ) ) {
			require_once WPEX_ClASSES_DIR . 'PostSeries.php';
		}

		// Enable the default WP header image function and outputs the design
		if ( wpex_get_mod( 'header_image_enable' ) ) {
			require_once WPEX_ClASSES_DIR . 'CustomHeader.php';
		}

		// Adds Thumbnail support for taxonomies
		if ( wpex_get_mod( 'term_thumbnails_enable', true ) ) {
			require_once WPEX_ClASSES_DIR . 'TermThumbnails.php';
		}

		// Removes slugs from Custom post types
		if ( wpex_get_mod( 'remove_posttype_slugs' ) ) {
			require_once WPEX_ClASSES_DIR . 'RemovePostTypeSlugs.php';
		}

		// Register theme image sizes and adds image sizing admin panel
		if ( wpex_get_mod( 'image_sizes_enable', true ) ) {
			require_once WPEX_ClASSES_DIR . 'ImageSizes.php';
		}

		// Admin only classes
		if ( is_admin() ) {

			// Recommends plugins to install and/or update
			if ( wpex_recommended_plugins() && wpex_get_mod( 'recommend_plugins_enable', true ) ) {
				require_once WPEX_FRAMEWORK_DIR . 'vendor/TGMPA.php';
			}

			// Provides auto updates for plugins based on recommended versions
			// @deprecated in 4.4.1 - must be enabled via filter
			if ( apply_filters( 'wpex_plugins_updater', false ) ) {
				require_once WPEX_ClASSES_DIR . 'PluginUpdater.php';
			}

			// Enables a post type editor panel for theme post types
			require_once WPEX_ClASSES_DIR . 'PostTypeEditorPanel.php';

			// Enables the Gallery Meta box in the post type editor to define post specific gallery images
			require_once WPEX_ClASSES_DIR . 'GalleryMetabox.php';

			// Introduces custom settings to the category taxonomy edit screen
			if ( apply_filters( 'wpex_category_settings', true ) ) {
				require_once WPEX_ClASSES_DIR . 'CategorySettings.php';
			}

			// Adds custom meta fields to the media edit screen
			require_once WPEX_ClASSES_DIR . 'MediaMetaFields.php';

		}

		// Outputs inline CSS to the front-end of the site based on Customizer settings
		require_once WPEX_ClASSES_DIR . 'InlineCSS.php';

		// Outputs CSS to the live site for your custom accent color
		require_once WPEX_ClASSES_DIR . 'AccentColors.php';

		// Outputs CSS to the live site for your custom theme borders color
		require_once WPEX_ClASSES_DIR . 'BorderColors.php';

		// Outputs CSS to the live site for your custom site and post-based backgrounds
		require_once WPEX_ClASSES_DIR . 'SiteBackgrounds.php';

		// Outputs CSS to the live site for advanced Customizer settings
		require_once WPEX_ClASSES_DIR . 'AdvancedStyles.php';

		// Front-end breadcrumbs class
		require_once WPEX_ClASSES_DIR . 'breadcrumbs.php';

		// Disable Google Services | Removes Google Fonts
		if ( wpex_disable_google_services() ) {
			require_once WPEX_ClASSES_DIR . 'DisableGoogleServices.php';
		}

		/*** IMPORTANT: Customizer Class must load last ***/
		require_once WPEX_FRAMEWORK_DIR . 'customizer/customizer.php';

	}

	/**
	 * Adds basic theme support functions and registers the nav menus
	 *
	 * @since 1.6.0
	 */
	public function theme_setup() {

		// Load text domain
		load_theme_textdomain( 'total', WPEX_THEME_DIR . '/languages' );

		// Get globals
		global $content_width;

		// Set content width based on theme's default design
		if ( ! isset( $content_width ) ) {
			$content_width = 980;
		}

		// Register theme navigation menus
		register_nav_menus( array(
			'topbar_menu'     => esc_html__( 'Top Bar', 'total' ),
			'main_menu'       => esc_html__( 'Main/Header', 'total' ),
			'mobile_menu_alt' => esc_html__( 'Mobile Menu Alternative', 'total' ),
			'mobile_menu'     => esc_html__( 'Mobile Icons', 'total' ),
			'footer_menu'     => esc_html__( 'Footer', 'total' ),
		) );

		// Declare theme support
		//add_theme_support( 'custom-logo' );
		add_theme_support( 'post-formats', array( 'video', 'gallery', 'audio', 'quote', 'link' ) );
		add_theme_support( 'automatic-feed-links' );
		add_theme_support( 'post-thumbnails' );
		add_theme_support( 'html5' );
		add_theme_support( 'title-tag' );
		add_theme_support( 'customize-selective-refresh-widgets' );

		// Enable excerpts for pages.
		add_post_type_support( 'page', 'excerpt' );

		// Add styles to the WP editor
		add_editor_style( 'assets/css/wpex-editor-style.css' );
		add_editor_style( wpex_asset_url( 'lib/ticons/css/ticons.min.css' ) );

	}

}
new WPEX_Theme_Setup;