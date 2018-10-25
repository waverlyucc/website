<?php
/**
 * Footer Builder Addon
 *
 * @package Total WordPress theme
 * @subpackage Framework
 * @version 4.7.1
 */

namespace TotalTheme;

use WP_Query;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class FooterBuilder {

	public $insert_hook;
	public $insert_priority;

	/**
	 * Start things up
	 *
	 * @since 2.0.0
	 */
	public function __construct() {

		$is_admin = is_admin();

		if ( $is_admin ) {

			// Add admin page
			add_action( 'admin_menu', array( $this, 'add_page' ), 20 );

			// Register admin options
			add_action( 'admin_init', array( $this, 'register_page_options' ) );

		}

		// Run actions and filters if footer_builder ID is defined
		if ( $builder_post_id = wpex_footer_builder_id() ) {

			// Do not register footer sidebars if disabled
			if ( ! wpex_get_mod( 'footer_builder_footer_widgets', false ) ) {
				add_filter( 'wpex_register_footer_sidebars', '__return_false' );
			}

			// Admin edits
			if ( $is_admin ) {
				add_filter( 'wpex_customizer_panels', array( $this, 'remove_customizer_panels' ) );
				add_filter( 'wpex_customizer_sections', array( $this, 'alter_customizer_settings' ) );
				add_filter( 'wpex_typography_settings', array( $this, 'remove_typography_settings' ) );
			}

			// Insert footer builder to site via theme hooks
			$this->insert_hook     = apply_filters( 'wpex_footer_builder_insert_hook', 'wpex_hook_footer_before' );
			$this->insert_priority = apply_filters( 'wpex_footer_builder_insert_priority', 40 );

			add_action( $this->insert_hook, array( $this, 'get_part' ), $this->insert_priority );

			// Include ID for Visual Composer custom CSS
			add_filter( 'wpex_vc_css_ids', array( $this, 'wpex_vc_css_ids' ) );

			// CSS
			add_filter( 'wpex_head_css', array( $this, 'wpex_head_css' ), 99 );

			// Alter template for live editing
			if ( wpex_vc_is_inline() ) {
				add_filter( 'template_include', array( $this, 'builder_template' ), 9999 );
			}

		}

	}

	/**
	 * Add sub menu page
	 *
	 * @since 2.0.0
	 */
	public function add_page() {
		add_submenu_page(
			WPEX_THEME_PANEL_SLUG,
			esc_html__( 'Footer Builder', 'total' ),
			esc_html__( 'Footer Builder', 'total' ),
			'administrator',
			WPEX_THEME_PANEL_SLUG .'-footer-builder',
			array( $this, 'create_admin_page' )
		);
	}

	/**
	 * Function that will register admin page options
	 *
	 * @since 2.0.0
	 */
	public function register_page_options() {

		// Register settings
		register_setting( 'wpex_footer_builder', 'footer_builder', array( $this, 'sanitize' ) );

		// Add main section to our options page
		add_settings_section( 'wpex_footer_builder_main', false, array( $this, 'section_main_callback' ), 'wpex-footer-builder-admin' );

		// Custom Page ID
		add_settings_field(
			'footer_builder_page_id',
			esc_html__( 'Footer Builder page', 'total' ),
			array( $this, 'content_id_field_callback' ),
			'wpex-footer-builder-admin',
			'wpex_footer_builder_main'
		);

		// Footer Bottom
		add_settings_field(
			'footer_builder_footer_bottom',
			esc_html__( 'Footer Bottom', 'total' ),
			array( $this, 'footer_builder_footer_bottom_field_callback' ),
			'wpex-footer-builder-admin',
			'wpex_footer_builder_main'
		);

		// Footer Widgets
		add_settings_field(
			'footer_builder_footer_widgets',
			esc_html__( 'Footer Widgets', 'total' ),
			array( $this, 'footer_widgets_field_callback' ),
			'wpex-footer-builder-admin',
			'wpex_footer_builder_main'
		);

		// Fixed Footer
		add_settings_field(
			'fixed_footer',
			esc_html__( 'Fixed Footer', 'total' ),
			array( $this, 'fixed_footer_field_callback' ),
			'wpex-footer-builder-admin',
			'wpex_footer_builder_main'
		);

		// Footer Reveal
		add_settings_field(
			'footer_reveal',
			esc_html__( 'Footer Reveal', 'total' ),
			array( $this, 'footer_reveal_field_callback' ),
			'wpex-footer-builder-admin',
			'wpex_footer_builder_main'
		);

		// Bg
		add_settings_field(
			'bg',
			esc_html__( 'Background Color', 'total' ),
			array( $this, 'bg_field_callback' ),
			'wpex-footer-builder-admin',
			'wpex_footer_builder_main'
		);

		// BG img
		add_settings_field(
			'bg_img',
			esc_html__( 'Background Image', 'total' ),
			array( $this, 'bg_img_field_callback' ),
			'wpex-footer-builder-admin',
			'wpex_footer_builder_main'
		);

		// BG img style
		add_settings_field(
			'bg_img_style',
			esc_html__( 'Background Image Style', 'total' ),
			array( $this, 'bg_img_style_field_callback' ),
			'wpex-footer-builder-admin',
			'wpex_footer_builder_main'
		);

	}

	/**
	 * Sanitization callback
	 *
	 * @since 2.0.0
	 */
	public function sanitize( $options ) {

		// Return if options are empty
		if ( empty( $options ) ) {
			return;
		}

		// Update footer builder page ID
		if ( ! empty( $options['content_id'] ) ) {
			set_theme_mod( 'footer_builder_page_id', $options['content_id'] );
		} else {
			remove_theme_mod( 'footer_builder_page_id' );
		}

		// Footer Bottom - Disabled by default
		if ( empty( $options['footer_builder_footer_bottom'] ) ) {
			remove_theme_mod( 'footer_builder_footer_bottom' );
		} else {
			set_theme_mod( 'footer_builder_footer_bottom', 1 );
		}

		// Footer Widgets - Disabled by default
		if ( empty( $options['footer_builder_footer_widgets'] ) ) {
			remove_theme_mod( 'footer_builder_footer_widgets' );
		} else {
			set_theme_mod( 'footer_builder_footer_widgets', 1 );
		}

		// Update fixed footer - Disabled by default
		if ( empty( $options['fixed_footer'] ) ) {
			remove_theme_mod( 'fixed_footer' );
		} else {
			set_theme_mod( 'fixed_footer', 1 );
		}

		// Update footer Reveal - Disabled by default
		if ( empty( $options['footer_reveal'] ) ) {
			remove_theme_mod( 'footer_reveal' );
		} else {
			set_theme_mod( 'footer_reveal', true );
		}

		// Update bg
		if ( empty( $options['bg'] ) ) {
			remove_theme_mod( 'footer_builder_bg' );
		} else {
			set_theme_mod( 'footer_builder_bg', $options['bg'] );
		}

		// Update bg img
		if ( empty( $options['bg_img'] ) ) {
			remove_theme_mod( 'footer_builder_bg_img' );
		} else {
			set_theme_mod( 'footer_builder_bg_img', $options['bg_img'] );
		}

		// Update bg img style
		if ( empty( $options['bg_img_style'] ) ) {
			remove_theme_mod( 'footer_builder_bg_img_style' );
		} else {
			set_theme_mod( 'footer_builder_bg_img_style', $options['bg_img_style'] );
		}

		// Dont save anything in the options table
		$options = '';
		return;
	}

	/**
	 * Main Settings section callback
	 *
	 * @since 2.0.0
	 */
	public function section_main_callback( $options ) {
		// Leave blank
	}

	/**
	 * Fields callback functions
	 *
	 * @since 2.0.0
	 */

	// Footer Builder Page ID
	public function content_id_field_callback() {

		// Get footer builder page ID
		$page_id = wpex_get_mod( 'footer_builder_page_id' ); ?>

		<select name="footer_builder[content_id]" id="wpex-footer-builder-select" class="wpex-chosen">

			<?php
			// Missing page
			if ( $page_id && FALSE === get_post_status( $page_id ) ) { ?>
				<option value="">-</option>
			<?php } ?>

			<option value=""><?php esc_html_e( 'None - Display Widgetized Footer', 'total' ); ?></option>

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

		<p class="description"><?php esc_html_e( 'Select your custom page for your footer layout.', 'total' ) ?></p>

		<br />

		<div id="wpex-footer-builder-edit-links">

			<a href="<?php echo admin_url( 'post.php?post='. $page_id .'&action=edit' ); ?>" class="button"><?php esc_html_e( 'Backend Edit', 'total' ); ?></a>
	
			<?php if ( WPEX_VC_ACTIVE ) { ?>
				<a href="<?php echo admin_url( 'post.php?vc_action=vc_inline&post_id=' . $page_id . '&post_type=' . get_post_type( $page_id ) ); ?>" class="button" target="_blank"><?php esc_html_e( 'Frontend Edit', 'total' ); ?></a>
			<?php } ?>

		</div>

	<?php }

	/**
	 * Footer Bottom Callback
	 *
	 * @since 2.0.0
	 */
	public function footer_builder_footer_bottom_field_callback() {

		$val = wpex_get_mod( 'footer_builder_footer_bottom', false );
		$val = $val ? 'on' : false; ?>

			<input type="checkbox" name="footer_builder[footer_builder_footer_bottom]" id="wpex-footer-builder-footer-bottom" <?php checked( $val, 'on' ); ?>>
		<?php
	}

	/**
	 * Fixed Footer Callback
	 *
	 * @since 2.0.0
	 */
	public function fixed_footer_field_callback() {

		$val = wpex_get_mod( 'fixed_footer', false );
		$val = $val ? 'on' : false; ?>

			<input type="checkbox" name="footer_builder[fixed_footer]" id="wpex-footer-builder-fixed" <?php checked( $val, 'on' ); ?>>
		<?php
	}

	/**
	 * Footer Reveal Callback
	 *
	 * @since 2.0.0
	 */
	public function footer_reveal_field_callback() {

		$val = wpex_get_mod( 'footer_reveal' );
		$val = $val ? 'on' : false; ?>

			<input type="checkbox" name="footer_builder[footer_reveal]" id="wpex-footer-builder-reveal" <?php checked( $val, 'on' ); ?>>

		<?php
	}

	/**
	 * Footer Widgets Callback
	 *
	 * @since 2.0.0
	 */
	public function footer_widgets_field_callback() {

		$val = wpex_get_mod( 'footer_builder_footer_widgets', false );
		$val = $val ? 'on' : false; ?>

			<input type="checkbox" name="footer_builder[footer_builder_footer_widgets]" id="wpex-footer-builder-widgets" <?php checked( $val, 'on' ); ?>>

		<?php
	}

	// Background Setting
	public function bg_field_callback() {

		// Get background
		$bg = wpex_get_mod( 'footer_builder_bg' ); ?>

		<input id="background_color" type="text" name="footer_builder[bg]" value="<?php echo esc_attr( $bg ); ?>" class="wpex-color-field">

	<?php }

	// Background Image Setting
	public function bg_img_field_callback() {

		$bg = wpex_get_mod( 'footer_builder_bg_img' ); ?>

		<div class="uploader">
			<input class="wpex-media-input" type="text" name="footer_builder[bg_img]" value="<?php echo esc_attr( $bg ); ?>">
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

		$style = wpex_get_mod( 'footer_builder_bg_img_style' ); ?>

			<select name="footer_builder[bg_img_style]">
			<?php
			$bg_styles = wpex_get_bg_img_styles();
			foreach ( $bg_styles as $key => $val ) { ?>
				<option value="<?php echo esc_attr( $key ); ?>" <?php selected( $style, $key, true ); ?>>
					<?php echo strip_tags( $val ); ?>
				</option>
			<?php } ?>
		</select>

	<?php }

	/**
	 * Settings page output
	 *
	 * @since 2.0.0
	 */
	public function create_admin_page() { ?>
		
		<div id="wpex-admin-page" class="wrap">
			
			<h1><?php esc_html_e( 'Footer Builder', 'total' ); ?> <a href="#" id="wpex-help-toggle" aria-hidden="true" style="text-decoration:none;"><span class="dashicons dashicons-editor-help" aria-hidden="true"></span><span class="screen-reader-text"><?php esc_html_e( 'learn more', 'total' ); ?></span></a></h1>
			
			<div id="wpex-notice" class="wpex-help-notice notice notice-info">
				<p>
					<?php echo esc_html__( 'By default the footer consists of a simple widgetized area. For more complex layouts you can use the option below to select a page which will hold the content and layout for your site footer. Selecting a custom footer will remove all footer functions (footer widgets and footer customizer options) so you can create an entire footer using the Visual Composer and not load that extra functions.', 'total' ); ?>
				
				</p>
			</div>

			<?php
			// Warning if footer builder page doesn't exist
			$page_id = wpex_get_mod( 'footer_builder_page_id' );
			if ( $page_id && FALSE === get_post_status( $page_id ) ) {

				echo '<div class="notice notice-warning"><p>' . esc_html__( 'It appears the page you had selected has been deleted, please re-save your settings to prevent issues.', 'total' ) . '</p></div>';

			} ?>

			<form method="post" action="options.php">

				<?php settings_fields( 'wpex_footer_builder' ); ?>
				<?php do_settings_sections( 'wpex-footer-builder-admin' ); ?>
				<?php submit_button(); ?>
			</form>

			<script>
				( function( $ ) {

					"use strict";

					$( document ).on( 'ready', function() {

						// Hide/Show fields
						var	$select      = $( '#wpex-footer-builder-select' );
						var $tableTr     = $( '#wpex-admin-page table tr' );
						var $selectTr    = $select.parents( 'tr' );
						var $footerLinks = $( '#wpex-footer-builder-edit-links' );

						// Check initial val
						if ( ! $select.val() ) {
							$tableTr.not( $selectTr ).hide();
							$footerLinks.hide();
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
	 * Alters get template
	 *
	 * @since 3.5.0
	 */
	public function builder_template( $template ) {
		$footer_builder_id = wpex_footer_builder_id(); // Get translated footer ID
		if ( $footer_builder_id && $footer_builder_id == wpex_get_current_post_id() ) {
			$new_template = locate_template( array( 'single-templatera.php' ) );
			if ( $new_template ) {
				return $new_template;
			}
		}
		return $template;
	}

	/**
	 * Add footer builder to array of ID's with CSS to load site-wide
	 *
	 * @since 2.0.0
	 */
	public function wpex_vc_css_ids( $ids ) {
		$footer_builder_id = wpex_footer_builder_id(); // Get translated footer ID
		if ( $footer_builder_id ) {
			$ids[] = $footer_builder_id;
		}
		return $ids;
	}

	/**
	 * Remove the footer and add custom footer if enabled
	 *
	 * @since 2.0.0
	 */
	public function remove_customizer_panels( $panels ) {
		if ( ! get_theme_mod( 'footer_builder_footer_widgets', false ) ) {
			unset( $panels['footer_widgets'] );
		}
		if ( ! get_theme_mod( 'footer_builder_footer_bottom', false ) ) {
			unset( $panels['footer_bottom'] );
		}
		return $panels;
	}

	/**
	 * Remove the footer and add custom footer if enabled
	 *
	 * @since 2.0.0
	 */
	public function alter_customizer_settings( $sections ) {
		if ( get_theme_mod( 'footer_builder_footer_widgets', false ) ) {
			unset( $sections['wpex_footer_widgets']['settings']['footer_widgets'] );
			unset( $sections['wpex_footer_widgets']['settings']['fixed_footer'] );
			unset( $sections['wpex_footer_widgets']['settings']['footer_reveal'] );
		}
		if ( get_theme_mod( 'footer_builder_footer_bottom', false ) ) {
			unset( $sections['wpex_footer_bottom']['settings']['footer_bottom'] );
		}
		return $sections;
	}

	/**
	 * Remove typography settings
	 *
	 * @since 4.7.1
	 */
	public function remove_typography_settings( $settings ) {
		if ( ! get_theme_mod( 'footer_builder_footer_widgets', false ) ) {
			unset( $settings['footer_widgets'] );
			unset( $settings['footer_widget_title'] );
		}
		if ( ! get_theme_mod( 'footer_builder_footer_bottom', false ) ) {
			unset( $settings['copyright'] );
			unset( $settings['footer_menu'] );
		}
		return $settings;
	}

	/**
	 * Gets the footer builder template part if the footer is enabled
	 *
	 * @since 2.0.0
	 */
	public function get_part() {
		if ( wpex_has_footer() ) {
			get_template_part( 'partials/footer/footer-builder' );
		}
	}

	/**
	 * Custom CSS for footer builder
	 *
	 * @since 3.5.0
	 */
	public function wpex_head_css( $css ) {
		$add_css = '';
		if ( $bg = wpex_get_mod( 'footer_builder_bg' ) ) {
			$add_css .= 'background-color:'. $bg .';';
		}
		if ( $bg_img = wpex_sanitize_data( wpex_get_mod( 'footer_builder_bg_img' ), 'image_src_from_mod' ) ) {
			$add_css .= 'background-image:url('. $bg_img .');';
		}
		if ( $bg_img && $bg_img_style = wpex_sanitize_data( wpex_get_mod( 'footer_builder_bg_img_style' ), 'background_style_css' ) ) {
			$add_css .= $bg_img_style;
		}
		if ( $add_css ) {
			$add_css = '#footer-builder{ '. $add_css .'}';
			$css .= '/*FOOTER BUILDER*/'. $add_css;
		}
		return $css;
	}


}
new FooterBuilder();