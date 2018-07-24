<?php
/**
 * Adds all Typography options to the Customizer and outputs the custom CSS for them
 *
 * @package Total WordPress Theme
 * @subpackage Customizer
 * @version 4.6.5
 */

namespace TotalTheme;

use WP_Customize_Control;
use WPEX_Fonts_Dropdown_Custom_Control;
use WPEX_Customize_Multicheck_Control;
use WP_Customize_Color_Control;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Typography {

	/**
	 * Main constructor
	 *
	 * @since 1.6.0
	 */
	public function __construct() {

		// Get customizer enabled panels
		$enabled_panels = get_option( 'wpex_customizer_panels', array( 'typography' => true ) );

		// Register customizer settings
		if ( isset( $enabled_panels['typography'] ) ) {
			add_action( 'customize_register', array( $this, 'register' ), 40 );
		}

		// Admin functions
		if ( is_admin() ) {

			// Add fonts to the mce editor
			add_action( 'admin_init', array( $this, 'mce_scripts' ) );
			add_filter( 'tiny_mce_before_init', array( $this, 'mce_fonts' ) );

		}

		// Front end functions
		else {

			// Load Google Font scripts
			if ( wpex_get_mod( 'google_fonts_in_footer' ) ) {
				add_action( 'wp_footer', array( $this, 'load_fonts' ) );
			} else {
				add_action( 'wp_enqueue_scripts', array( $this, 'load_fonts' ) );
			}

		}

		// CSS output
		if ( is_customize_preview() && isset( $enabled_panels['typography'] ) ) {
			add_action( 'customize_preview_init', array( $this, 'customize_preview_init' ) );
			add_action( 'wp_head', array( $this, 'live_preview_styles' ), 999 );
		} else {
			add_filter( 'wpex_head_css', array( $this, 'head_css' ), 99 );
		}

	}

	/**
	 * Array of Typography settings to add to the customizer
	 *
	 * @since 4.2
	 */
	public function get_settings() {
		return apply_filters( 'wpex_typography_settings', array(
			'body' => array(
				'label' => __( 'Body', 'total' ),
				'target' => 'body',
				'defaults' => array(
					'font-family' => wpex_has_google_services_support() ? 'Open Sans' : '',
				),
			),
			'logo' => array(
				'label' => __( 'Logo', 'total' ),
				'target' => '#site-logo a.site-logo-text',
				'exclude' => array( 'color' ),
				'active_callback' => 'wpex_cac_hasnt_custom_logo',
				'condition' => 'wpex_header_has_text_logo',
			),
			'button' => array(
				'label' => __( 'Buttons', 'total' ),
				'target' => '.theme-button,input[type="submit"],button,#site-navigation .menu-button>a>span.link-inner,.woocommerce .button',
				'exclude' => array( 'color', 'margin', 'font-size' ),
			),
			'top_menu' => array(
				'label' => __( 'Top Bar', 'total' ),
				'target' => '#top-bar-content',
				'exclude' => array( 'color' ),
				'active_callback' => 'wpex_cac_has_topbar',
				'condition' => 'wpex_has_topbar',
			),
			'header_aside' => array(
				'label' => __( 'Header Aside', 'total' ),
				'target' => '#header-aside',
			),
			'menu' => array(
				'label' => __( 'Main Menu', 'total' ),
				'target' => '#site-navigation .dropdown-menu a',
				'exclude' => array( 'color', 'line-height' ),
			),
			'menu_dropdown' => array(
				'label' => __( 'Main Menu: Dropdowns', 'total' ),
				'target' => '#site-navigation .dropdown-menu ul a',
				'exclude' => array( 'color' ),
			),
			'mobile_menu' => array(
				'label' => __( 'Mobile Menu', 'total' ),
				'target' => '.wpex-mobile-menu, #sidr-main',
				'exclude' => array( 'color' ),
			),
			'page_title' => array(
				'label' => __( 'Page Header Title', 'total' ),
				'target' => '.page-header .page-header-title',
				'exclude' => array( 'color' ),
				'active_callback' => 'wpex_cac_has_page_header',
				'condition' => 'wpex_has_page_header',
			),
			'page_subheading' => array(
				'label' => __( 'Page Title Subheading', 'total' ),
				'target' => '.page-header .page-subheading',
				'active_callback' => 'wpex_cac_has_page_header',
				'condition' => 'wpex_has_page_header',
			),
			'blog_entry_title' => array(
				'label' => __( 'Blog Entry Title', 'total' ),
				'target' => '.blog-entry-title.entry-title, .blog-entry-title.entry-title a, .blog-entry-title.entry-title a:hover',
			),
			'blog_post_title' => array(
				'label' => __( 'Blog Post Title', 'total' ),
				'target' => 'body .single-post-title',
			),
			'breadcrumbs' => array(
				'label' => __( 'Breadcrumbs', 'total' ),
				'target' => '.site-breadcrumbs',
				'exclude' => array( 'color', 'line-height' ),
				'active_callback' => 'wpex_cac_has_breadcrumbs',
				'condition' => 'wpex_has_breadcrumbs',
			),
			'headings' => array(
				'label' => __( 'Headings', 'total' ),
				'target' => 'h1,h2,h3,h4,h5,h6,.theme-heading,.page-header-title,.heading-typography,.widget-title,.wpex-widget-recent-posts-title,.comment-reply-title,.vcex-heading,.entry-title,.sidebar-box .widget-title,.search-entry h2',
				'exclude' => array( 'font-size' ),
			),
			'theme_heading' => array(
				'label' => __( 'Theme Heading', 'total' ),
				'target' => '.theme-heading',
				'description' =>  __( 'Heading used in various places such as the related and comments heading. ', 'total' ),
				'margin' => true,
			),
			'sidebar_widget_title' => array(
				'label' => __( 'Sidebar Widget Heading', 'total' ),
				'target' => '.sidebar-box .widget-title',
				'margin' => true,
				'exclude' => array( 'color' ),
				'condition' => 'wpex_has_sidebar',
			),
			'entry_h1' => array(
				'label' => __( 'Post H1', 'total' ),
				'target' => '.entry h1',
				'margin' => true,
			),
			'entry_h2' => array(
				'label' => __( 'Post H2', 'total' ),
				'target' => '.entry h2',
				'margin' => true,
			),
			'entry_h3' => array(
				'label' => __( 'Post H3', 'total' ),
				'target' => ' .entry h3',
				'margin' => true,
			),
			'entry_h4' => array(
				'label' => __( 'Post H4', 'total' ),
				'target' => '.entry h4',
				'margin' => true,
			),
			'footer_widgets' => array(
				'label' => __( 'Footer Widgets', 'total' ),
				'target' => '#footer-widgets',
				'exclude' => array( 'color' ),
				'active_callback' => 'wpex_cac_has_footer_widgets',
				'condition' => 'wpex_footer_has_widgets',
			),
			'footer_widget_title' => array(
				'label' => __( 'Footer Widget Heading', 'total' ),
				'target' => '.footer-widget .widget-title',
				'exclude' => array( 'color' ),
				'margin' => true,
				'active_callback' => 'wpex_cac_has_footer_widgets',
				'condition' => 'wpex_footer_has_widgets',
			),
			'callout' => array(
				'label' => __( 'Footer Callout', 'total' ),
				'target' => '.footer-callout-content',
				'exclude' => array( 'color' ),
				'condition' => 'wpex_has_callout',
			),
			'copyright'           => array(
				'label'           => __( 'Footer Bottom Text', 'total' ),
				'target'          => '#copyright',
				'exclude'         => array( 'color' ),
				'active_callback' => 'wpex_cac_has_footer_bottom',
				'condition'       => 'wpex_has_footer_bottom',
			),
			'footer_menu'         => array(
				'label'           => __( 'Footer Bottom Menu', 'total' ),
				'target'          => '#footer-bottom-menu',
				'exclude'         => array( 'color' ),
				'active_callback' => 'wpex_cac_has_footer_bottom',
				'condition'       => 'wpex_has_footer_bottom',
			),
		) );
	}

	/**
	 * Loads js file for customizer preview
	 *
	 * @since 3.3.0
	 */
	public function customize_preview_init() {

		wp_enqueue_script( 'wpex-typography-customize-preview',
			wpex_asset_url( 'js/dynamic/wpex-typography-customize-preview.js' ),
			array( 'customize-preview' ),
			WPEX_THEME_VERSION,
			true
		);

		wp_localize_script( 'wpex-typography-customize-preview', 'wpexTypo', array(
			'stdFonts'          => wpex_standard_fonts(),
			'customFonts'       => wpex_add_custom_fonts(),
			'googleFontsUrl'    => wpex_get_google_fonts_url(),
			'googleFontsSuffix' => '100i,200i,300i,400i,500i,600i,700i,800i,100,200,300,400,500,600,700,800',
			'sytemUIFontStack'  => wpex_get_system_ui_font_stack(),
			'settings'          => $this->get_settings(),
			'attributes'        => array(
				'font-family',
				'font-weight',
				'font-style',
				'font-size',
				'color',
				'line-height',
				'letter-spacing',
				'text-transform',
				'margin',
			),
		) );
		
	}

	/**
	 * Register typography options to the Customizer
	 *
	 * @since 1.6.0
	 */
	public function register( $wp_customize ) {

		if ( ! class_exists( 'WPEX_Customizer' ) ) {
			return;
		}

		// Get Settings
		$settings = $this->get_settings();

		// Return if settings are empty. This check is needed due to the filter added above
		if ( empty( $settings ) ) {
			return;
		}

		// Add General Panel
		$wp_customize->add_panel( 'wpex_typography', array(
			'priority'   => 142,
			'capability' => 'edit_theme_options',
			'title'      => __( 'Typography', 'total' ),
		) );

		// Add General Tab with font smoothing
		$wp_customize->add_section( 'wpex_typography_general' , array(
			'title'    => __( 'General', 'total' ),
			'priority' => 1,
			'panel'    => 'wpex_typography',
		) );

		// Font Smoothing
		$wp_customize->add_setting( 'enable_font_smoothing', array(
			'type'              => 'theme_mod',
			'transport'         => 'postMessage',
			'sanitize_callback' => 'wpex_sanitize_checkbox',
		) );
		$wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'enable_font_smoothing', array(
			'label'       => __( 'Font Smoothing', 'total' ),
			'section'     => 'wpex_typography_general',
			'settings'    => 'enable_font_smoothing',
			'type'        => 'checkbox',
			'description' => __( 'Enable font-smoothing site wide. This makes fonts look a little "skinner". ', 'total' ),
		) ) );

		// Google Font settings
		if ( wpex_has_google_services_support() ) {

			// Load custom font 1
			$wp_customize->add_setting( 'load_custom_google_font_1', array(
				'type'              => 'theme_mod',
				'sanitize_callback' => 'esc_html',
			) );
			$wp_customize->add_control( new WPEX_Fonts_Dropdown_Custom_Control( $wp_customize, 'load_custom_google_font_1', array(
					'label'       => __( 'Load Custom Font', 'total' ),
					'section'     => 'wpex_typography_general',
					'settings'    => 'load_custom_google_font_1',
					'type'        => 'wpex-font-family',
					'description' => __( 'Allows you to load a custom font site wide for use with custom CSS. ', 'total' ),
				)
			) );

			// Load fonts in footer
			$wp_customize->add_setting( 'google_fonts_in_footer', array(
				'type'              => 'theme_mod',
				'transport'         => 'postMessage',
				'sanitize_callback' => 'wpex_sanitize_checkbox',
			) );

			$wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'google_fonts_in_footer', array(
				'label'    => __( 'Load Fonts After The Body Tag', 'total' ),
				'section'  => 'wpex_typography_general',
				'settings' => 'google_fonts_in_footer',
				'type'     => 'checkbox',
			) ) );

			// Select subsets
			$wp_customize->add_setting( 'google_font_subsets', array(
				'type'              => 'theme_mod',
				'default'           => 'latin',
				'sanitize_callback' => 'esc_html',
			) );
			$wp_customize->add_control( new WPEX_Customize_Multicheck_Control( $wp_customize, 'google_font_subsets', array(
					'label'    => __( 'Font Subsets', 'total' ),
					'section'  => 'wpex_typography_general',
					'settings' => 'google_font_subsets',
					'choices'  => array(
						'latin'        => 'latin',
						'latin-ext'    => 'latin-ext',
						'cyrillic'     => 'cyrillic',
						'cyrillic-ext' => 'cyrillic-ext',
						'greek'        => 'greek',
						'greek-ext'    => 'greek-ext',
						'vietnamese'   => 'vietnamese',
					),
				)
			) );
		}

		// Save translations in memory
		// Beacause we loop through all settings this way we don't have to call the __() function multiple times
		// and the strings can be translated prior to the loop.
		$s_family         = __( 'Font Family', 'total' );
		$s_style          = __( 'Font Style', 'total' );
		$s_default        = __( 'Default', 'total' );
		$s_xlight         = __( 'Extra Light: 100', 'total' );
		$s_light          = __( 'Light: 200', 'total' );
		$s_weight         = __( 'Font Weight', 'total' );
		$s_weight_desc    = __( 'Note: Not all Fonts support every font weight style. ', 'total' );
		$s_300            = __( 'Book: 300', 'total' );
		$s_400            = __( 'Normal: 400', 'total' );
		$s_500            = __( 'Medium: 500', 'total' );
		$s_600            = __( 'Semibold: 600', 'total' );
		$s_700            = __( 'Bold: 700', 'total' );
		$s_800            = __( 'Extra Bold: 800', 'total' );
		$s_900            = __( 'Black: 900', 'total' );
		$s_normal         = __( 'Normal', 'total' );
		$s_italic         = __( 'Italic', 'total' );
		$s_capitalize     = __( 'Capitalize', 'total' );
		$s_lowercase      = __( 'Lowercase', 'total' );
		$s_uppercase      = __( 'Uppercase', 'total' );
		$s_em_px          = __( 'Value in px or em. ', 'total' );
		$s_transform      = __( 'Text Transform', 'total' );
		$s_size           = __( 'Font Size', 'total' );
		$s_color          = __( 'Font Color', 'total' );
		$s_line_height    = __( 'Line Height', 'total' );
		$s_letter_spacing = __( 'Letter Spacing', 'total' );
		$s_margin         = __( 'Margin', 'total' );
		$s_margin_desc    = __( 'Please use the following format: top right bottom left. ', 'total' );

		// Loop through settings
		foreach( $settings as $element => $array ) {

			$label = ! empty( $array['label'] ) ? $array['label'] : null;

			if ( ! $label ) {
				continue; // label is required
			}

			$exclude_attributes = ! empty( $array['exclude'] ) ? $array['exclude'] : false;
			$active_callback    = ! empty( $array['active_callback'] ) ? $array['active_callback'] : null;
			$description        = ! empty( $array['description'] ) ? $array['description'] : '';
			$transport          = ! empty( $array['transport'] ) ? $array['transport'] : 'postMessage';

			// Get attributes
			if ( ! empty ( $array['attributes'] ) ) {
				$attributes = $array['attributes'];
			} else {
				$attributes = array(
					'font-family',
					'font-weight',
					'font-style',
					'text-transform',
					'font-size',
					'line-height',
					'letter-spacing',
					'color',
				);
			}

			// Allow for margin on this attribute
			if ( isset( $array['margin'] ) ) {
				$attributes[] = 'margin';
			}

			// Set keys equal to vals
			$attributes = array_combine( $attributes, $attributes );

			// Exclude attributes for specific options
			if ( $exclude_attributes ) {
				foreach ( $exclude_attributes as $key => $val ) {
					unset( $attributes[ $val ] );
				}
			}

			// Define Section
			$wp_customize->add_section( 'wpex_typography_' . $element , array(
				'title'       => $label,
				'panel'       => 'wpex_typography',
				'description' => $description
			) );

			// Font Family
			if ( in_array( 'font-family', $attributes ) ) {

				// Get default
				$default = ! empty( $array['defaults']['font-family'] ) ? $array['defaults']['font-family'] : NULL;

				// Add setting
				$wp_customize->add_setting( $element . '_typography[font-family]', array(
					'type'              => 'theme_mod',
					'default'           => $default,
					'transport'         => $transport,
					'sanitize_callback' => 'esc_html',
				) );

				// Add Control
				$wp_customize->add_control( new WPEX_Fonts_Dropdown_Custom_Control( $wp_customize, $element . '_typography[font-family]', array(
						'type'            => 'wpex-font-family',
						'label'           => $s_family,
						'section'         => 'wpex_typography_' . $element,
						'settings'        => $element . '_typography[font-family]',
						'active_callback' => $active_callback,
				) ) );

			}

			// Font Weight
			if ( in_array( 'font-weight', $attributes ) ) {

				$wp_customize->add_setting( $element . '_typography[font-weight]', array(
					'type'              => 'theme_mod',
					'sanitize_callback' => 'wpex_sanitize_customizer_select',
					'transport'         => $transport,
				) );
				$wp_customize->add_control( $element . '_typography[font-weight]', array(
					'label'           => $s_weight,
					'section'         => 'wpex_typography_' . $element,
					'settings'        => $element . '_typography[font-weight]',
					'type'            => 'select',
					'active_callback' => $active_callback,
					'choices' => array(
						''    => $s_default,
						'100' => $s_xlight,
						'200' => $s_light,
						'300' => $s_300,
						'400' => $s_400,
						'500' => $s_500,
						'600' => $s_600,
						'700' => $s_700,
						'800' => $s_800,
						'900' => $s_900,
					),
					'description' => $s_weight_desc,
				) );

			}

			// Font Style
			if ( in_array( 'font-style', $attributes ) ) {

				$wp_customize->add_setting( $element . '_typography[font-style]', array(
					'type'              => 'theme_mod',
					'sanitize_callback' => 'wpex_sanitize_customizer_select',
					'transport'         => $transport,
				) );

				$wp_customize->add_control( $element . '_typography[font-style]', array(
					'label'           => $s_style,
					'section'         => 'wpex_typography_' . $element,
					'settings'        => $element . '_typography[font-style]',
					'type'            => 'select',
					'active_callback' => $active_callback,
					'choices'     => array(
						''       => $s_default,
						'normal' => $s_normal,
						'italic' => $s_italic,
					),
				) );

			}

			// Text-Transform
			if ( in_array( 'text-transform', $attributes ) ) {

				$wp_customize->add_setting( $element . '_typography[text-transform]', array(
					'type'              => 'theme_mod',
					'sanitize_callback' => 'wpex_sanitize_customizer_select',
					'transport'         => $transport,
				) );

				$wp_customize->add_control( $element . '_typography[text-transform]', array(
					'label'           => $s_transform,
					'section'         => 'wpex_typography_' . $element,
					'settings'        => $element . '_typography[text-transform]',
					'type'            => 'select',
					'active_callback' => $active_callback,
					'choices'         => array(
						''           => $s_default,
						'capitalize' => $s_capitalize,
						'lowercase'  => $s_lowercase,
						'uppercase'  => $s_uppercase,
					),
				) );

			}

			// Font Size
			if ( in_array( 'font-size', $attributes ) ) {

				$wp_customize->add_setting( $element . '_typography[font-size]', array(
					'type'              => 'theme_mod',
					'sanitize_callback' => 'wpex_sanitize_font_size',
					'transport'         => $transport,
				) );

				$wp_customize->add_control( $element . '_typography[font-size]', array(
					'label'           => $s_size,
					'section'         => 'wpex_typography_' . $element,
					'settings'        => $element . '_typography[font-size]',
					'type'            => 'text',
					'description'     => $s_em_px,
					'active_callback' => $active_callback,
				) );

			}

			// Font Color
			if ( in_array( 'color', $attributes ) ) {

				$wp_customize->add_setting( $element . '_typography[color]', array(
					'type'              => 'theme_mod',
					'default'           => '',
					'sanitize_callback' => 'sanitize_hex_color',
					'transport'         => $transport,
				) );
				
				$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, $element . '_typography_color', array(
					'label'           => $s_color,
					'section'         => 'wpex_typography_' . $element,
					'settings'        => $element . '_typography[color]',
					'active_callback' => $active_callback,
				) ) );

			}

			// Line Height
			if ( in_array( 'line-height', $attributes ) ) {

				$wp_customize->add_setting( $element . '_typography[line-height]', array(
					'type'              => 'theme_mod',
					'sanitize_callback' => 'esc_html',
					'transport'         => $transport,
				) );

				$wp_customize->add_control( $element . '_typography[line-height]',
					array(
						'label'           => $s_line_height,
						'section'         => 'wpex_typography_' . $element,
						'settings'        => $element . '_typography[line-height]',
						'type'            => 'text',
						'active_callback' => $active_callback,
				) );

			}

			// Letter Spacing
			if ( in_array( 'letter-spacing', $attributes ) ) {

				$wp_customize->add_setting( $element . '_typography[letter-spacing]', array(
					'type'              => 'theme_mod',
					'sanitize_callback' => 'wpex_sanitize_letter_spacing',
					'transport'         => $transport,
				) );

				$wp_customize->add_control( new WP_Customize_Control( $wp_customize, $element . '_typography_letter_spacing', array(
					'label'           => $s_letter_spacing,
					'section'         => 'wpex_typography_' . $element,
					'settings'        => $element . '_typography[letter-spacing]',
					'type'            => 'text',
					'active_callback' => $active_callback,
					'description'     => $s_em_px,
				) ) );

			}

			// Margin
			if ( in_array( 'margin', $attributes ) ) {

				$wp_customize->add_setting( $element . '_typography[margin]', array(
					'type'              => 'theme_mod',
					'sanitize_callback' => 'esc_html',
					'transport'         => $transport,
				) );

				$wp_customize->add_control( $element . '_typography[margin]',
					array(
						'label'           => $s_margin,
						'section'         => 'wpex_typography_' . $element,
						'settings'        => $element . '_typography[margin]',
						'type'            => 'text',
						'active_callback' => $active_callback,
						'description'     => $s_margin_desc,
				) );

			}

		}

	}

	/**
	 * Loop through settings
	 *
	 * @since 1.6.0
	 */
	public function loop( $return = 'css' ) {

		// Define Vars
		$css            = '';
		$fonts          = array();
		$preview_styles = array();
		$settings       = $this->get_settings();

		if ( ! $settings ) {
			return;
		}

		// Supported fonts
		$gf_support = wpex_has_google_services_support();
		if ( ! $gf_support ) {
			$supported_fonts = array_merge( wpex_standard_fonts(), wpex_add_custom_fonts() );
		}

		// Loop through settings that need typography styling applied to them
		foreach( $settings as $element => $array ) {

			// Check conditional first
			if ( isset( $array['condition'] ) && ! call_user_func( $array['condition'] ) ) {
				continue;
			}

			// Add empty css var
			$add_css = '';

			// Get target and current mod
			$target  = isset( $array['target'] ) ? $array['target'] : '';
			$get_mod = wpex_get_mod( $element . '_typography' );

			// Attributes to loop through
			if ( ! empty( $array['attributes'] ) ) {
				$attributes = $array['attributes'];
			} else {
				$attributes = array(
					'font-family',
					'font-weight',
					'font-style',
					'font-size',
					'color',
					'line-height',
					'letter-spacing',
					'text-transform',
					'margin',
				);
			}

			// Loop through attributes
			foreach ( $attributes as $attribute ) {

				// Define val
				$default = isset( $array['defaults'][$attribute] ) ? $array['defaults'][$attribute] : NULL;
				$val     = isset ( $get_mod[$attribute] ) ? $get_mod[$attribute] : $default;

				if ( 'font-family' == $attribute
					&& isset( $supported_fonts )
					&& ! in_array( $val, $supported_fonts )
				) {
					$val = null;
				}

				// If there is a value lets do something
				if ( $val ) {

					// Sanitize
					$val = str_replace( '"', '', $val );

					// Sanitize data
					if ( 'font-size' == $attribute ) {
						$val = wpex_sanitize_font_size( $val );
					} elseif ( 'letter-spacing' == $attribute ) {
						$val = wpex_sanitize_letter_spacing( $val );
					}

					// Add quotes around font-family && font family to scripts array
					if ( 'font-family' == $attribute ) {
						$fonts[] = $val;
						$val = wpex_sanitize_font_family( $val ); // convert html characters
						if ( strpos( $val, '"' ) || strpos( $val, ',' ) ) {
							$val = $val;
						} else {
							$val = '"' . esc_html( $val ) . '"';
						}
					}

					// Add to inline CSS
					if ( 'css' == $return ) {
						$add_css .= $attribute . ':' . $val . ';';
					}

					// Customizer styles need to be added for each attribute
					elseif ( 'preview_styles' == $return ) {
						$preview_styles['wpex-customizer-' . $element . '-' . $attribute] = $target . '{' . $attribute . ':' . $val . ';}';
					}

				}

			}

			// Front-end inline CSS
			if ( $add_css && 'css' == $return ) {
				$css .= $target . '{' . $add_css . '}';
			}

		}

		// Check for custom font
		if ( $custom_font = wpex_get_mod( 'load_custom_google_font_1' ) ) {
			$fonts[] = $custom_font;
		}

		// Update selected fonts class var
		if ( $fonts ) {
			$fonts = array_unique( $fonts ); // Return only 1 of each font
		}

		// Update css class var
		if ( $css ) {
			$css = '/*TYPOGRAPHY*/' . $css;
		}

		// Return data
		if ( 'css' == $return ) {
			return $css;
		} elseif ( 'preview_styles' == $return ) {
			return $preview_styles;
		} elseif ( 'fonts' == $return ) {
			return $fonts;
		}

	}

	/**
	 * Outputs the typography custom CSS
	 *
	 * @since 1.6.0
	 */
	public function head_css( $output ) {
		$typography_css = $this->loop( 'css' );
		if ( $typography_css ) {
			$output .= $typography_css;
		}
		return $output;
	}

	/**
	 * Returns correct CSS to output to wp_head
	 *
	 * @since 2.1.3
	 */
	public function live_preview_styles() {
		$live_preview_styles = $this->loop( 'preview_styles' );
		if ( is_array( $live_preview_styles ) ) {
			foreach ( $live_preview_styles as $key => $val ) {
				if ( ! empty( $val ) ) {
					echo '<style id="' . $key . '"> ' . $val . '</style>';
				}
			}
		}
	}

	/**
	 * Loads Google fonts via wp_enqueue_style
	 *
	 * @since 1.6.0
	 */
	public function load_fonts() {
		if ( wpex_disable_google_services() ) {
			return;
		}
		$fonts = $this->loop( 'fonts' );
		if ( is_array( $fonts ) ) {
			foreach ( $fonts as $font ) {
				wpex_enqueue_google_font( $font );
			}
		}
	}

	/**
	 * Add loaded fonts into the TinyMCE
	 *
	 * @since 1.6.0
	 */
	public function mce_fonts( $initArray ) {

		// Get fonts from class
		$fonts = $this->loop( 'fonts' );

		// Apply filters for child theme editing
		$fonts = apply_filters( 'wpex_mce_fonts', $fonts );

		// Sanitize to prevent issues with custom fonts
		$fonts = $fonts ? $fonts : array();

		// Declare fonts array to add to mce
		$fonts_array = array();

		// Add custom fonts
		$custom_fonts = wpex_add_custom_fonts();
		if ( $custom_fonts && is_array( $custom_fonts ) ) {
			$fonts = array_merge( $fonts, $custom_fonts );
		}

		// Loop through fonts
		if ( $fonts ) {

			// Create new array of fonts
			foreach ( $fonts as $font ) {
				if ( false !== stripos( $font, ',' ) ) {
					continue; // Allow only single fonts no font families as it breaks the editor
				}
				$fonts_array[] = $font . '=' . $font;
			}

			// Implode fonts array into a semicolon seperated list
			$fonts = implode( ';', $fonts_array );

			// Add Fonts To MCE
			if ( $fonts ) {

				$initArray['font_formats'] = $fonts . ';Andale Mono=andale mono,times;Arial=arial,helvetica,sans-serif;Arial Black=arial black,avant garde;Book Antiqua=book antiqua,palatino;Comic Sans MS=comic sans ms,sans-serif;Courier New=courier new,courier;Georgia=georgia,palatino;Helvetica=helvetica;Impact=impact,chicago;Symbol=symbol;Tahoma=tahoma,arial,helvetica,sans-serif;Terminal=terminal,monaco;Times New Roman=times new roman,times;Trebuchet MS=trebuchet ms,geneva;Verdana=verdana,geneva;Webdings=webdings;Wingdings=wingdings,zapf dingbats';

			}

		}

		// Return hook array
		return $initArray;

	}

	/**
	 * Add loaded fonts to the sourcode in the admin so it can display in the editor
	 *
	 * @since 1.6.0
	 */
	public function mce_scripts() {

		// Get Google fonts
		$google_fonts = wpex_google_fonts_array();

		// For google fonts only so return if none are defined
		if ( ! $google_fonts ) {
			return;
		}

		// Get fonts
		$fonts = $this->loop( 'fonts' );

		// Apply filters
		$fonts = apply_filters( 'wpex_mce_fonts', $fonts );

		// Check
		if ( empty( $fonts ) || ! is_array( $fonts ) ) {
			return;
		}

		// Add Google fonts to tinymce
		foreach ( $fonts as $font ) {
			if ( ! in_array( $font, $google_fonts ) ) {
				continue;
			}
			$subset = wpex_get_mod( 'google_font_subsets', 'latin' );
			$subset = $subset ? $subset : 'latin';
			$subset = '&amp;subset=' . $subset;
			$font   = wpex_get_google_fonts_url() . '/css?family=' . str_replace(' ', '%20', $font ) . ':300italic,400italic,600italic,700italic,800italic,400,300,600,700,800' . $subset;
			$style  = str_replace( ',', '%2C', $font );
			add_editor_style( $style );
		}
	}

}
new Typography();