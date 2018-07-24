<?php
/**
 * Adds favicons and mobile icon meta to the wp_head
 *
 * @package Total WordPress theme
 * @subpackage Framework
 * @version 4.6.5
 */

namespace TotalTheme;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Favicons {

	/**
	 * Start things up
	 */
	public function __construct() {
		if ( is_admin() ) {
			add_action( 'admin_menu', array( $this, 'add_page' ) );
			add_action( 'admin_init', array( $this, 'register_page_options' ) );
			add_action( 'admin_head', array( $this, 'output_favicons' ) );
			remove_action( 'login_head', 'wp_site_icon', 99 );
			add_action( 'admin_init', array( $this, 'remove_admin_wp_site_icon' ), 99 );
		} else {
			add_action( 'wp_head', array( $this, 'output_favicons' ) );
			remove_action( 'wp_head', 'wp_site_icon', 99 );
		}
	}

	/**
	 * Add sub menu page
	 *
	 * @since 1.6.0
	 */
	public function add_page() {
		add_submenu_page(
			WPEX_THEME_PANEL_SLUG,
			esc_html__( 'Favicons', 'total' ),
			esc_html__( 'Favicons', 'total' ),
			'administrator',
			WPEX_THEME_PANEL_SLUG . '-favicons',
			array( $this, 'create_admin_page' )
		);
	}

	/**
	 * Function that will register admin page options.
	 *
	 * @since 1.6.0
	 */
	public function register_page_options() {

		// Register Setting
		register_setting( 'wpex_favicons', 'wpex_favicons', array( $this, 'sanitize' ) );

		// Add main section to our options page
		add_settings_section( 'wpex_favicons_main', false, array( $this, 'section_main_callback' ), 'wpex-favicons' );

		// Favicon
		add_settings_field(
			'wpex_favicon',
			esc_html__( 'Favicon', 'total' ),
			array( $this, 'favicon_callback' ),
			'wpex-favicons',
			'wpex_favicons_main'
		);

		// iPhone
		add_settings_field(
			'wpex_iphone_icon',
			esc_html__( 'Apple iPhone Icon ', 'total' ),
			array( $this, 'iphone_icon_callback' ),
			'wpex-favicons',
			'wpex_favicons_main'
		);

		// Ipad
		add_settings_field(
			'wpex_ipad_icon',
			esc_html__( 'Apple iPad Icon ', 'total' ),
			array( $this, 'ipad_icon_callback' ),
			'wpex-favicons',
			'wpex_favicons_main'
		);

		// iPhone Retina
		add_settings_field(
			'wpex_iphone_icon_retina',
			esc_html__( 'Apple iPhone Retina Icon ', 'total' ),
			array( $this, 'iphone_icon_retina_callback' ),
			'wpex-favicons',
			'wpex_favicons_main'
		);

		// iPad Retina
		add_settings_field(
			'wpex_ipad_icon_retina',
			esc_html__( 'Apple iPad Retina Icon ', 'total' ),
			array( $this, 'ipad_icon_retina_callback' ),
			'wpex-favicons',
			'wpex_favicons_main'
		);

	}

	/**
	 * Sanitization callback
	 *
	 * @since 1.6.0
	 */
	public function sanitize( $options ) {

		// Set all options to theme_mods
		if ( is_array( $options ) && ! empty( $options ) ) {
			foreach ( $options as $key => $value ) {
				if ( ! empty( $value ) ) {
					set_theme_mod( $key, $value );
				} else {
					remove_theme_mod( $key );
				}
			}
		}

		// Set options to nothing since we are storing in the theme mods
		$options = '';
		return;
	}

	/**
	 * Main Settings section callback
	 *
	 * @since 1.6.0
	 */
	public function section_main_callback() {
		// Leave blank
	}

	/**
	 * Returns correct value for preview
	 *
	 * @since 1.6.0
	 */
	private static function sanitize_val( $val, $instance = 'mod' ) {
		if ( 'image' == $instance && is_numeric( $val ) ) {
			$val = wp_get_attachment_image_src( $val, 'full' );
			$val = $val[0];
		} elseif( is_numeric( $val ) ) {
			$val = absint( $val );
		}
		return $val;
	}

	/**
	 * Fields callback functions
	 *
	 * @since 1.6.0
	 */

	// Favicon
	public function favicon_callback() {
		$val     = wpex_get_mod( 'favicon' );
		$val     = $this->sanitize_val( $val );
		$preview = $this->sanitize_val( $val, 'image' ); ?>
		<input type="text" name="wpex_favicons[favicon]" value="<?php echo esc_attr( $val ); ?>" class="wpex-media-input">
		<input class="wpex-media-upload-button button-secondary" name="login_page_design_bg_img_button" type="button" value="<?php esc_attr_e( 'Upload', 'total' ); ?>" />
		<a href="#" class="wpex-media-remove button-secondary" style="display:none;"><span class="dashicons dashicons-no-alt" style="line-height: inherit;"></span></a>
		<p class="description">32x32</p>
		<div class="wpex-media-live-preview" data-image-size="32">
			<?php if ( $preview ) { ?>
				<img src="<?php echo esc_url( $preview ); ?>" alt="<?php esc_attr_e( 'Preview Image', 'total' ); ?>" style="width:32px;height:32px;" />
			<?php } ?>
		</div>
	<?php }

	// iPhone
	public function iphone_icon_callback() {
		$val	 = wpex_get_mod( 'iphone_icon' );
		$val     = $this->sanitize_val( $val );
		$preview = $this->sanitize_val( $val, 'image' ); ?>
		<input type="text" name="wpex_favicons[iphone_icon]" value="<?php echo esc_attr( $val ); ?>" class="wpex-media-input">
		<input class="wpex-media-upload-button button-secondary" name="login_page_design_bg_img_button" type="button" value="<?php esc_attr_e( 'Upload', 'total' ); ?>" />
		<a href="#" class="wpex-media-remove button-secondary" style="display:none;"><span class="dashicons dashicons-no-alt" style="line-height: inherit;"></span></a>
		<p class="description">57x57</p>
		<div class="wpex-media-live-preview" data-image-size="57">
			<?php if ( $preview ) { ?>
				<img src="<?php echo esc_url( $preview ); ?>" alt="<?php esc_attr_e( 'Preview Image', 'total' ); ?>" style="width:57px;height:57px;" />
			<?php } ?>
		</div>
	<?php }

	// iPad
	public function ipad_icon_callback() {
		$val	 = wpex_get_mod( 'ipad_icon' );
		$val     = $this->sanitize_val( $val );
		$preview = $this->sanitize_val( $val, 'image' ); ?>
		<input type="text" name="wpex_favicons[ipad_icon]" value="<?php echo esc_attr( $val ); ?>" class="wpex-media-input">
		<input class="wpex-media-upload-button button-secondary" name="login_page_design_bg_img_button" type="button" value="<?php esc_attr_e( 'Upload', 'total' ); ?>" />
		<a href="#" class="wpex-media-remove button-secondary" style="display:none;"><span class="dashicons dashicons-no-alt" style="line-height: inherit;"></span></a>
		<p class="description">76x76</p>
		<div class="wpex-media-live-preview" data-image-size="76">
			<?php if ( $preview ) { ?>
				<img src="<?php echo esc_url( $preview ); ?>" alt="<?php esc_attr_e( 'Preview Image', 'total' ); ?>" style="width:76px;height:76px;" />
			<?php } ?>
		</div>
	<?php }

	// iPhone Retina
	public function iphone_icon_retina_callback() {
		$val	 = wpex_get_mod( 'iphone_icon_retina' );
		$val     = $this->sanitize_val( $val );
		$preview = $this->sanitize_val( $val, 'image' ); ?>
		<input type="text" name="wpex_favicons[iphone_icon_retina]" value="<?php echo esc_attr( $val ); ?>" class="wpex-media-input">
		<input class="wpex-media-upload-button button-secondary" name="login_page_design_bg_img_button" type="button" value="<?php esc_attr_e( 'Upload', 'total' ); ?>" />
		<a href="#" class="wpex-media-remove button-secondary" style="display:none;"><span class="dashicons dashicons-no-alt" style="line-height: inherit;"></span></a>
		<p class="description">120x120</p>
		<div class="wpex-media-live-preview" data-image-size="120">
			<?php if ( $preview ) { ?>
				<img src="<?php echo esc_url( $preview ); ?>" alt="<?php esc_attr_e( 'Preview Image', 'total' ); ?>" style="width:120px;height:120px;" />
			<?php } ?>
		</div>
	<?php }

	// iPad Retina
	public function ipad_icon_retina_callback() {
		$val	 = wpex_get_mod( 'ipad_icon_retina' );
		$val     = $this->sanitize_val( $val );
		$preview = $this->sanitize_val( $val, 'image' ); ?>
		<input type="text" name="wpex_favicons[ipad_icon_retina]" value="<?php echo esc_attr( $val ); ?>" class="wpex-media-input">
		<input class="wpex-media-upload-button button-secondary" name="login_page_design_bg_img_button" type="button" value="<?php esc_attr_e( 'Upload', 'total' ); ?>" />
		<a href="#" class="wpex-media-remove button-secondary" style="display:none;"><span class="dashicons dashicons-no-alt" style="line-height: inherit;"></span></a>
		<p class="description">152x152</p>
		<div class="wpex-media-live-preview" data-image-size="152">
			<?php if ( $preview ) { ?>
				<img src="<?php echo esc_url( $preview ); ?>" alt="<?php esc_attr_e( 'Preview Image', 'total' ); ?>" style="width:152px;height:152px;" />
			<?php } ?>
		</div>
	<?php }

	/**
	 * Settings page output
	 *
	 * @since 1.6.0
	 */
	public function create_admin_page() {
		delete_option( 'wpex_favicons' ); // Remove useless option since we are saving data to theme_mods ?>
		<div class="wrap">
			<h1><?php echo esc_html__( 'Favicons', 'total' ); ?></h1>
			<form method="post" action="options.php">
				<?php settings_fields( 'wpex_favicons' ); ?>
				<?php do_settings_sections( 'wpex-favicons' ); ?>
				<?php submit_button(); ?>
			</form>
		</div><!-- .wrap -->
	<?php }

	/**
	 * Settings page output
	 *
	 * @since 1.6.0
	 */
	public function output_favicons() {

		// Favicon - Standard
		if ( $icon = wpex_get_mod( 'favicon' ) ) {
			echo '<link rel="icon" href="'. esc_url( $this->sanitize_val( $icon, 'image' ) ) .'" sizes="32x32">';
			echo '<link rel="shortcut icon" href="'. esc_url( $this->sanitize_val( $icon, 'image' ) ) .'">'; // For older IE
		}

		// Apple iPhone Icon - 57px
		if ( $icon = wpex_get_mod( 'iphone_icon' ) ) {
			echo '<link rel="apple-touch-icon" href="'. esc_url( $this->sanitize_val( $icon, 'image' ) ) .'" sizes="57x57" >';
		}

		// Apple iPad Icon - 76px
		if ( $icon = wpex_get_mod( 'ipad_icon' ) ) {
			echo '<link rel="apple-touch-icon" href="'. esc_url( $this->sanitize_val( $icon, 'image' ) ) .'" sizes="76x76" >';
		}

		// Apple iPhone Retina Icon - 120px
		if ( $icon = wpex_get_mod( 'iphone_icon_retina' ) ) {
			echo '<link rel="apple-touch-icon" href="'. esc_url( $this->sanitize_val( $icon, 'image' ) ) .'" sizes="120x120">';
		}

		// Apple iPad Retina Icon - 114px
		if ( $icon = wpex_get_mod( 'ipad_icon_retina' ) ) {
			echo '<link rel="apple-touch-icon" href="'. esc_url( $this->sanitize_val( $icon, 'image' ) ) .'" sizes="114x114">';
		}

	}

	/**
	 * Remove the WP site icon in the admin
	 *
	 * @since 1.6.0
	 */
	public function remove_admin_wp_site_icon() {
		remove_action( 'admin_head', 'wp_site_icon', 10 );
	}

}
new Favicons();