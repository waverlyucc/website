<?php
/**
 * Header Builder Addon
 *
 * @package Total WordPress theme
 * @subpackage Framework
 * @version 4.6.5
 */

namespace TotalTheme;

use WP_Query;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class HeaderBuilder {

	public $insert_hook;
	public $insert_priority;

	/**
	 * Start things up
	 *
	 * @since 3.5.0
	 */
	public function __construct() {

		$is_admin = is_admin();

		if ( $is_admin ) {

			// Add admin page
			add_action( 'admin_menu', array( $this, 'add_page' ), 20 );

			// Register admin options
			add_action( 'admin_init', array( $this, 'register_page_options' ) );

		}

		// Run actions and filters if header_builder ID is defined
		if ( $builder_post_id = wpex_header_builder_id() ) {

			// Do not register header sidebars
			add_filter( 'wpex_register_header_sidebars', '__return_false' );

			// Alter the header on init
			add_action( 'wp', array( $this, 'alter_header' ) );

			// Include ID for Visual Composer custom CSS
			add_filter( 'wpex_vc_css_ids', array( $this, 'wpex_vc_css_ids' ) );

			// Remove header customizer settings
			add_filter( 'wpex_customizer_panels', array( $this, 'remove_customizer_header_panel' ) );

			// Alter template for live editing
			if ( wpex_vc_is_inline() ) {
				add_filter( 'template_include', array( $this, 'builder_template' ), 9999 );
			}

			// Remove Meta options
			if ( $is_admin ) {
				add_filter( 'wpex_metabox_array', array( $this, 'remove_meta' ), 99, 2 );
			}

			// CSS
			add_filter( 'wpex_head_css', array( $this, 'custom_css' ), 99 );

		}

	}

	/**
	 * Add sub menu page
	 *
	 * @since 3.5.0
	 */
	public function add_page() {
		add_submenu_page(
			WPEX_THEME_PANEL_SLUG,
			esc_html__( 'Header Builder', 'total' ),
			esc_html__( 'Header Builder', 'total' ),
			'administrator',
			WPEX_THEME_PANEL_SLUG .'-header-builder',
			array( $this, 'create_admin_page' )
		);
	}

	/**
	 * Returns settings array
	 *
	 * @since 3.5.0
	 */
	public function settings() {
		return array(
			'page_id'      => esc_html__( 'Header Builder page', 'total' ),
			'bg'           => esc_html__( 'Background Color', 'total' ),
			'bg_img'       => esc_html__( 'Background Image', 'total' ),
			'bg_img_style' => esc_html__( 'Background Image Style', 'total' ),
			'top_bar'      => esc_html__( 'Top Bar', 'total' ),
			'sticky'       => esc_html__( 'Sticky Header', 'total' ),
		);
	}

	/**
	 * Function that will register admin page options
	 *
	 * @since 3.5.0
	 */
	public function register_page_options() {

		// Register settings
		register_setting(
			'wpex_header_builder',
			'header_builder',
			array( $this, 'sanitize' )
		);

		// Register setting section
		add_settings_section(
			'wpex_header_builder_main',
			false,
			array( $this, 'section_main_callback' ),
			'wpex-header-builder-admin'
		);

		// Add settings
		$settings = $this->settings();
		foreach ( $settings as $key => $val ) {
			add_settings_field(
				$key,
				$val,
				array( $this, $key .'_field_callback' ),
				'wpex-header-builder-admin',
				'wpex_header_builder_main'
			);
		}

	}

	/**
	 * Sanitization callback
	 *
	 * @since 3.5.0
	 */
	public function sanitize( $options ) {

		$settings = $this->settings();

		foreach ( $settings as $key => $val ) {

			if ( 'top_bar' == $key ) {
				if ( empty( $options['top_bar'] ) ) {
					set_theme_mod( 'top_bar', false );
				} else {
					remove_theme_mod( 'top_bar' );
				}
				continue;
			}

			if ( 'sticky' == $key ) {
				if ( ! empty( $options['header_builder_sticky'] ) ) {
					set_theme_mod( 'header_builder_sticky', true );
				} else {
					remove_theme_mod( 'header_builder_sticky' );
				}
				continue;
			}

			if ( empty( $options[$key] ) ) {
				remove_theme_mod( 'header_builder_' . $key );
			} else {
				set_theme_mod( 'header_builder_' . $key, $options[$key] );
			}

		}

		$options = '';
		return;

	}

	/**
	 * Main Settings section callback
	 *
	 * @since 3.5.0
	 */
	public function section_main_callback( $options ) {
		// not needed
	}

	/**
	 * Fields callback functions
	 *
	 * @since 3.5.0
	 */

	// Header Builder Page ID
	public function page_id_field_callback() {

		// Get header builder page ID
		$page_id = wpex_get_mod( 'header_builder_page_id' ); ?>

		<select name="header_builder[page_id]" id="wpex-header-builder-select" class="wpex-chosen">

			<?php
			// Missing page
			if ( $page_id && FALSE === get_post_status( $page_id ) ) { ?>
				<option value="">-</option>
			<?php } ?>

			<option value=""><?php esc_html_e( 'None', 'total' ); ?></option>

			<?php if ( post_type_exists( 'templatera' ) ) {

				$templates = new WP_Query( array(
					'posts_per_page' => -1,
					'post_type'      => 'templatera',
				) );
				if ( $templates->have_posts() ) { ?>

					<optgroup label="<?php esc_html_e( 'Templatera', 'total' ); ?>">
						
						<?php while ( $templates->have_posts() ) {

							$templates->the_post();

							echo '<option value="'. get_the_ID() .'"'. selected( $page_id, get_the_ID(), false ) .'>'. get_the_title() .'</option>';

						}
						wp_reset_postdata(); ?>
					</optgroup>

				<?php }

			} ?>

			<?php if ( post_type_exists( 'elementor_library' ) ) {

				$templates = new WP_Query( array(
					'posts_per_page' => -1,
					'post_type'      => 'elementor_library',
				) );
				if ( $templates->have_posts() ) { ?>

					<optgroup label="<?php esc_html_e( 'Elementor Templates', 'total' ); ?>">
						
						<?php while ( $templates->have_posts() ) {

							$templates->the_post();

							echo '<option value="'. get_the_ID() .'"'. selected( $page_id, get_the_ID(), false ) .'>'. get_the_title() .'</option>';

						}
						wp_reset_postdata(); ?>
					</optgroup>

				<?php }

			} ?>
			
			<optgroup label="<?php esc_html_e( 'Pages', 'total' ); ?>">
				<?php
				$pages = get_pages( array(
					'exclude' => get_option( 'page_on_front' ),
				) );
				if ( $pages ) {
					foreach ( $pages as $page ) {
						echo '<option value="'. $page->ID .'"'. selected( $page_id, $page->ID, false ) .'>'. $page->post_title .'</option>';
					}
				} ?>
			</optgroup>

		</select>

		<br />

		<p class="description"><?php esc_html_e( 'Select your custom page for your header layout.', 'total' ) ?></p>

		<br />

		<div id="wpex-header-builder-edit-links">

			<a href="<?php echo admin_url( 'post.php?post='. $page_id .'&action=edit' ); ?>" class="button"><?php esc_html_e( 'Backend Edit', 'total' ); ?></a>

			<?php if ( WPEX_VC_ACTIVE ) { ?>
				<a href="<?php echo admin_url( 'post.php?vc_action=vc_inline&post_id=' . $page_id . '&post_type=' . get_post_type( $page_id ) ); ?>" class="button" target="_blank"><?php esc_html_e( 'Frontend Edit', 'total' ); ?></a>
			<?php } ?>

		</div>
		
	<?php }

	// Background Setting
	public function bg_field_callback() {

		// Get background
		$bg = wpex_get_mod( 'header_builder_bg' ); ?>

		<input id="background_color" type="text" name="header_builder[bg]" value="<?php echo esc_attr( $bg ); ?>" class="wpex-color-field">

	<?php }

	// Background Image Setting
	public function bg_img_field_callback() {

		// Get background
		$bg = wpex_get_mod( 'header_builder_bg_img' ); ?>

		<div class="uploader">
			<input class="wpex-media-input" type="text" name="header_builder[bg_img]" value="<?php echo esc_attr( $bg ); ?>">
			<input class="wpex-media-upload-button button-secondary" type="button" value="<?php esc_html_e( 'Upload', 'total' ); ?>" />
			<?php $preview = wpex_sanitize_data( $bg, 'image_src_from_mod' ); ?>
			<a href="#" class="wpex-media-remove button-secondary" style="display:none;"><span class="dashicons dashicons-no-alt" style="line-height: inherit;"></span></a>
			<div class="wpex-media-live-preview">
				<?php if ( $preview ) { ?>
					<img src="<?php echo esc_url( $preview ); ?>" alt="<?php esc_html_e( 'Preview Image', 'total' ); ?>" />
				<?php } ?>
			</div>
		</div>

	<?php }

	// Background Image Style Setting
	public function bg_img_style_field_callback() {

		// Get setting
		$style = wpex_get_mod( 'header_builder_bg_img_style' ); ?>

			<select name="header_builder[bg_img_style]">
			<?php
			$bg_styles = wpex_get_bg_img_styles();
			foreach ( $bg_styles as $key => $val ) { ?>
				<option value="<?php echo esc_attr( $key ); ?>" <?php selected( $style, $key, true ); ?>>
					<?php echo strip_tags( $val ); ?>
				</option>
			<?php } ?>
		</select>

	<?php }

	// Top bar setting callback
	public function top_bar_field_callback() {

		// Get theme mod val
		$val = get_theme_mod( 'top_bar', true );
		$val = $val ? 'on' : false; ?>

		<input type="checkbox" name="header_builder[top_bar]" id="wpex-header-builder-top-bar" <?php checked( $val, 'on' ); ?>>

	<?php }

	// Sticky setting callback
	public function sticky_field_callback() {

		// Get theme mod val
		$val = get_theme_mod( 'header_builder_sticky', false );
		$val = $val ? 'on' : false; ?>

		<input type="checkbox" name="header_builder[header_builder_sticky]" id="wpex-header-builder-sticky" <?php checked( $val, 'on' ); ?>>

	<?php }

	/**
	 * Settings page output
	 *
	 * @since 3.5.0
	 */
	public function create_admin_page() { ?>

		<div id="wpex-admin-page" class="wrap">
			
			<h1><?php esc_html_e( 'Header Builder', 'total' ); ?> <a href="#" id="wpex-help-toggle" aria-hidden="true" style="text-decoration:none;"><span class="dashicons dashicons-editor-help" aria-hidden="true"></span><span class="screen-reader-text"><?php esc_html_e( 'learn more', 'total' ); ?></span></a></h1>
			
			<div id="wpex-notice" class="wpex-help-notice notice notice-info">
				<p>
				<?php echo esc_html__( 'Use this setting to replace the default theme header with content created with the Visual Composer. When enabled all Customizer settings for the Header will be removed. This is an advanced functionality so if this is the first time you use the theme we recommend you first test out the built-in header which can be customized at Appearance > Customize > Header.', 'total' ); ?>	
				</p>
			</div>

			<?php
			// Warning if footer builder page doesn't exist
			$page_id = wpex_get_mod( 'header_builder_page_id' );
			if ( $page_id && FALSE === get_post_status( $page_id ) ) {

				echo '<div class="notice notice-warning"><p>' . esc_html__( 'It appears the page you had selected has been deleted, please re-save your settings to prevent issues.', 'total' ) . '</p></div>';

			} ?>
			
			<form method="post" action="options.php">
				<?php settings_fields( 'wpex_header_builder' ); ?>
				<?php do_settings_sections( 'wpex-header-builder-admin' ); ?>
				<?php submit_button(); ?>
			</form>
			
			<script>
				( function( $ ) {

					"use strict";

					$( document ).on( 'ready', function() {

						// Hide/Show fields
						var $tableTr     = $( '#wpex-admin-page table tr' );
						var	$select      = $( '#wpex-header-builder-select' );
						var $selectTr    = $select.parents( 'tr' );
						var $footerLinks = $( '#wpex-header-builder-edit-links' );

						// Check initial val
						if ( ! $select.val() ) {
							$footerLinks.hide();
							$tableTr.not( $selectTr ).hide();
						}

						// Check on change
						$( $select ).change(function () {
							$footerLinks.hide();
							if ( ! $( this ).val() ) {
								$tableTr.not( $selectTr ).hide();
							} else {
								$tableTr.show();
							}
						} );

					} );

				} ) ( jQuery );

			</script>

		</div>

	<?php }

	/**
	 * Remove the header and add custom header if enabled
	 *
	 * @since 3.5.0
	 */
	public function alter_header() {

		// Remove all actions in header hooks
		$hooks = wpex_theme_hooks();
		if ( isset( $hooks['header']['hooks'] ) ) {
			foreach( $hooks['header']['hooks'] as $hook ) {
				remove_all_actions( $hook, false );
			}
		}

		// Insert footer builder to site via theme hooks
		$this->insert_hook     = apply_filters( 'wpex_header_builder_insert_hook', 'wpex_hook_header_inner' );
		$this->insert_priority = apply_filters( 'wpex_header_builder_insert_priority', 0 );

		add_action( $this->insert_hook, array( $this, 'get_part' ), $this->insert_priority );

	}

	/**
	 * Alters get template
	 *
	 * @since 3.5.0
	 */
	public function builder_template( $template ) {
		$header_builder_id = wpex_header_builder_id();
		if ( $header_builder_id && $header_builder_id == wpex_get_current_post_id() ) {
			$new_template = locate_template( array( 'single-templatera.php' ) );
			if ( $new_template ) {
				return $new_template;
			}
		}
		return $template;
	}

	/**
	 * Add header builder to array of ID's with CSS to load site-wide
	 *
	 * @since 3.5.0
	 */
	public function wpex_vc_css_ids( $ids ) {
		$header_builder_id = wpex_header_builder_id();
		if ( $header_builder_id ) {
			$ids[] = $header_builder_id;
		}
		return $ids;
	}

	/**
	 * Remove the header and add custom header if enabled
	 *
	 * @since 3.5.0
	 */
	public function remove_customizer_header_panel( $panels ) {
		unset( $panels['header'] );
		return $panels;
	}

	/**
	 * Gets the header builder template part if the header is enabled
	 *
	 * @since 3.5.0
	 */
	public function get_part() {
		if ( wpex_has_header() || wpex_vc_is_inline() ) {
			get_template_part( 'partials/header/header-builder' );
		}
	}

	/**
	 * Remove header meta that isn't needed anymore
	 *
	 * @since 3.5.0
	 */
	public function remove_meta( $array, $post ) {
		if ( $post && $post->ID == wpex_header_builder_id() ) {
			$array = ''; // remove on actual builderpage
		} else {
			unset( $array['header']['settings']['custom_menu'] );
			unset( $array['header']['settings']['overlay_header_style'] );
			unset( $array['header']['settings']['overlay_header_dropdown_style'] );
			unset( $array['header']['settings']['overlay_header_font_size'] );
			unset( $array['header']['settings']['overlay_header_logo'] );
			unset( $array['header']['settings']['overlay_header_logo_retina'] );
			unset( $array['header']['settings']['overlay_header_retina_logo_height'] );
		}
		return $array;
	}

	/**
	 * Custom CSS for header builder
	 *
	 * @since 3.5.0
	 */
	public function custom_css( $css ) {
		$add_css = '';
		if ( $bg = wpex_get_mod( 'header_builder_bg' ) ) {
			$add_css .= 'background-color:'. $bg .';';
			$css .= '#site-header-sticky-wrapper.is-sticky #site-header{background-color:'. $bg .';}';
		}
		if ( $bg_img = wpex_sanitize_data( wpex_get_mod( 'header_builder_bg_img' ), 'image_src_from_mod' ) ) {
			$add_css .= 'background-image:url('. $bg_img .');';
		}
		if ( $bg_img && $bg_img_style = wpex_sanitize_data( wpex_get_mod( 'header_builder_bg_img_style' ), 'background_style_css' ) ) {
			$add_css .= $bg_img_style;
		}
		if ( $add_css ) {
			$add_css = '#site-header.header-builder{ '. $add_css .'}';
			$css .= '/*HEADER BUILDER*/'. $add_css;
		}
		return $css;
	}

}
new HeaderBuilder();