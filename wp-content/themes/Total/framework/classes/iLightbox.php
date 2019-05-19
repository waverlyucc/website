<?php
/**
 * iLightbox class
 *
 * @package Total WordPress Theme
 * @subpackage Framework
 * @version 4.8
 */

namespace TotalTheme;

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Start Class
class iLightbox {

	/**
	 * Main constructor
	 *
	 * @since 2.1.0
	 */
	public function __construct() {

		// Define lightbox stylesheets
		add_action( 'wp_enqueue_scripts', array( $this, 'register_styles' ), 20 );

		// Load scripts
		if ( wpex_get_mod( 'lightbox_auto', false )
			|| apply_filters( 'wpex_load_ilightbox_globally', wpex_get_mod( 'lightbox_load_style_globally', false ) )
		) {
			add_action( 'wp_enqueue_scripts', array( $this, 'load_stylesheet_always' ), 40 );
		}

		// Add to localize array
		add_filter( 'wpex_localize_array', array( $this, 'localize' ) );

		// Add customizer settings
		add_filter( 'wpex_customizer_sections', array( $this, 'customizer_settings' ) );

	}

	/**
	 * Localize scripts
	 *
	 * @since 2.1.0
	 */
	public static function active_skin() {
		$skin = wpex_get_mod( 'lightbox_skin' );
		$skin = $skin ? $skin : 'total'; // default skin is "total"
		return apply_filters( 'wpex_lightbox_skin', $skin );
	}

	/**
	 * Localize scripts
	 *
	 * @since 2.1.0
	 */
	public static function localize( $array ) {

		// Define lightbox type
		$array['lightboxType'] = 'iLightbox';

		// Get skin
		$skin = wpex_ilightbox_skin();

		// lightbox animations
		if ( wpex_get_mod( 'ilightbox_animated_effects', true ) ) {
			$effects = array(
				'reposition'      => true,
                'repositionSpeed' => 200,
                'switchSpeed'     => 300,
				'loadedFadeSpeed' => 50,
				'fadeSpeed'       => 500,
			);
			$display_speed = 200;
		} else {
			$effects = array(
				'reposition'      => false,
                'repositionSpeed' => 0,
                'switchSpeed'     => 0,
				'loadedFadeSpeed' => 0,
				'fadeSpeed'       => 0,
			);
			$display_speed = 0;
		}

		// Get maxwidth
		$maxwidth = intval( wpex_get_mod( 'lightbox_width' ) );

		// Add lightbox to localize array and apply filters for easier tweaking.
		$array['iLightbox'] = apply_filters( 'wpex_ilightbox_settings', array(
			'auto'        => wpex_get_mod( 'lightbox_auto', false ) ? '.wpb_text_column a:has(img), body.no-composer .entry a:has(img)' : false,
			'skin'        => $skin,
			'path'        => 'horizontal',
			'infinite'    => false,
			'maxScale'    => 1,
			'minScale'    => 0,
			'width'       => $maxwidth ? $maxwidth : 1400,
			'height'      => '',
			'videoWidth'  => 1280, // 720p
			'videoHeight' => 720,  // 720p
			'controls' => array(
				'arrows'     => wpex_get_mod( 'lightbox_arrows', true ),
				'thumbnail'  => wpex_get_mod( 'lightbox_thumbnails', true ),
				'fullscreen' => wpex_get_mod( 'lightbox_fullscreen', true ),
				'mousewheel' => wpex_get_mod( 'lightbox_mousewheel', false ),
				'slideshow'  => true,
			),
			'slideshow' => array(
				'pauseTime'    => 3000,
				'startPaused'  => true,
			),
			'effects' => $effects,
			'show' => array(
				'title' => true,
				'speed' => $display_speed,
			),
			'hide' => array(
				'speed' => $display_speed,
			),
			'overlay' => array(
				'blur'    => true,
				'opacity' => 'total' == $skin ? '0.85' : '0.9',
			),
			'social' => array(
				'start'   => true,
				'show'    => 'mouseenter',
				'hide'    => 'mouseleave',
				'buttons' => false,
			),
			'text' => array(
				'close'           => 'Press Esc to close',
				'enterFullscreen' => 'Enter Fullscreen (Shift+Enter)',
				'exitFullscreen'  => 'Exit Fullscreen (Shift+Enter)',
				'slideShow'       => 'Slideshow',
				'next'            => 'Next',
				'previous'        => 'Previous',
			),
			'thumbnails' => array(
				'maxWidth'  => 120,
				'maxHeight' => 80,
			),
		) );
		return $array;
	}

	/**
	 * Holds an array of lightbox skins
	 *
	 * @since 2.1.0
	 */
	public static function skins() {
		return apply_filters( 'wpex_ilightbox_skins', array(
			'minimal'     => __( 'Minimal', 'total' ),
			'white'       => __( 'White', 'total' ),
			'dark'        => __( 'Dark', 'total' ),
			'flat-dark'   => __( 'Flat Dark', 'total' ),
			'light'       => __( 'Light', 'total' ),
			'mac'         => __( 'Mac', 'total' ),
			'metro-black' => __( 'Metro Black', 'total' ),
			'metro-white' => __( 'Metro White', 'total' ),
			'parade'      => __( 'Parade', 'total' ),
			'smooth'      => __( 'Smooth', 'total' ),
		) );
	}

	/**
	 * Returns correct skin stylesheet
	 *
	 * @since 2.1.0
	 */
	public static function skin_style( $skin = null ) {

		// Sanitize skin
		$skin = $skin ? $skin : wpex_ilightbox_skin();

		// Loop through skins
		$stylesheet = wpex_asset_url( 'lib/ilightbox/' . $skin . '/ilightbox-' . $skin . '-skin.css' );

		// Apply filters and return
		return apply_filters( 'wpex_ilightbox_stylesheet', $stylesheet );

	}

	/**
	 * Enqueues iLightbox skin stylesheet
	 *
	 * @since 2.1.0
	 */
	public static function enqueue_style( $skin = null ) {
		$skin = ( $skin && 'default' != $skin ) ? $skin : wpex_ilightbox_skin();
		if ( $skin && 'total' !== $skin ) {
			wp_enqueue_style( 'wpex-ilightbox-' . $skin );
		}
	}

	/**
	 * Registers all stylesheets for quick usage and enqueues default skin for the whole site
	 *
	 * @since 2.1.0
	 */
	public static function register_styles() {
		foreach( self::skins() as $key => $val ) {
			wp_register_style( 'wpex-ilightbox-' . $key, self::skin_style( $key ), false, WPEX_THEME_VERSION );
		}
	}

	/**
	 * Will load the lightbox main stylesheet everywhere
	 *
	 * @since 2.1.0
	 */
	public static function load_stylesheet_always() {
		$skin = self::active_skin();
		if ( $skin && 'total' !== $skin ) {
			wp_enqueue_style( 'wpex-ilightbox-' . $skin, false, WPEX_THEME_VERSION );
		}
	}

	/**
	 * Loads the stylesheet
	 *
	 * @since 2.1.0
	 */
	public static function load_css() {
		self::enqueue_style();
	}

	/**
	 * Adds lightbox customizer settings
	 *
	 * @return array
	 *
	 * @since 2.1.0
	 */
	public static function customizer_settings( $sections ) {
		$skins = array( '' => __( 'Default', 'total' ) );
		$skins = $skins + wpex_ilightbox_skins();
		$sections['wpex_lightbox'] = array(
			'title' => __( 'Lightbox', 'total' ),
			'panel' => 'wpex_general',
			'settings' => array(
				array(
					'id' => 'lightbox_skin',
					'transport' => 'postMessage',
					'default' => '',
					'control' => array(
						'label' => __( 'Skin', 'total' ),
						'type' => 'select',
						'choices' => $skins,
						'desc'  => __( 'You must save your options and refresh your live site to preview changes to this setting.', 'total' ),
					),
				),
				array(
					'id' => 'lightbox_width',
					'default' => 1500,
					'control' => array(
						'label' => __( 'Image Max Width', 'total' ),
						'type' => 'number',
					),
				),
				array(
					'id' => 'lightbox_load_style_globally',
					'default' => false,
					'control' => array(
						'label' => __( 'Load Skin Globally', 'total' ),
						'type' => 'checkbox',
						'desc' => __( 'Load the lightbox skin on the entire website. Enable if you are adding custom inline lightbox links via HTML.', 'total' ),
					),
				),
				array(
					'id' => 'lightbox_auto',
					'control' => array(
						'label' => __( 'Auto Lightbox', 'total' ),
						'type' => 'checkbox',
						'desc' => __( 'Automatically add Lightbox to images inserted into the post editor.', 'total' ),
					),
				),
				array(
					'id' => 'ilightbox_animated_effects',
					'default' => true,
					'control' => array(
						'label' => __( 'Animated Effects', 'total' ),
						'type' => 'checkbox',
					),
				),
				array(
					'id' => 'lightbox_thumbnails',
					'default' => true,
					'control' => array(
						'label' => __( 'Gallery Thumbnails', 'total' ),
						'type' => 'checkbox',
					),
				),
				array(
					'id' => 'lightbox_arrows',
					'default' => true,
					'control' => array(
						'label' => __( 'Gallery Arrows', 'total' ),
						'type' => 'checkbox',
					),
				),
				array(
					'id' => 'lightbox_mousewheel',
					'default' => false,
					'control' => array(
						'label' => __( 'Gallery Mousewheel Scroll', 'total' ),
						'type' => 'checkbox',
					),
				),
				array(
					'id' => 'lightbox_titles',
					'default' => true,
					'control' => array(
						'label' => __( 'Titles', 'total' ),
						'type' => 'checkbox',
					),
				),
				array(
					'id' => 'lightbox_fullscreen',
					'default' => true,
					'control' => array(
						'label' => __( 'Fullscreen Button', 'total' ),
						'type' => 'checkbox',
					),
				),
			),
		);
		return $sections;
	}

}
new iLightbox();