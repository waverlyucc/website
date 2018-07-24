<?php
/**
 * Custom Login Page Design
 *
 * @package Total WordPress theme
 * @subpackage Framework
 * @version 4.6.5
 *
 */

namespace TotalTheme;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class WPEX_Custom_Login {

	/**
	 * Start things up
	 *
	 * @since 1.6.0
	 */
	public function __construct() {

		if ( is_admin() ) {

			add_action( 'admin_menu', array( $this, 'add_admin_menu' ) );
			add_action( 'admin_init', array( $this, 'register_settings' ) );

		} else {

			add_action( 'login_head', array( $this, 'output_css' ) );
			add_action( 'login_headerurl', array( $this, 'login_headerurl' ) );
			add_filter( 'login_headertitle',array( $this, 'login_headertitle' ) );

		}

	}

	/**
	 * Returns custom login page settings
	 *
	 * @since 3.5.0
	 */
	public function options() {
		return wpex_get_mod( 'login_page_design', array(
			'enabled'                 => true,
			'logo'                    => null,
			'logo_url'                => null,
			'logo_url_title'          => null,
			'logo_height'             => null,
			'background_color'        => null,
			'background_img'          => null,
			'background_style'        => null,
			'form_background_color'   => null,
			'form_background_opacity' => null,
			'form_text_color'         => null,
			'form_top'                => null,
			'form_border_radius'      => null,
		) );
	}

	/**
	 * Add sub menu page
	 *
	 * @since 1.6.0
	 */
	public function add_admin_menu() {
		add_submenu_page(
			WPEX_THEME_PANEL_SLUG,
			esc_html__( 'Custom Login', 'total' ),
			esc_html__( 'Custom Login', 'total' ),
			'administrator',
			WPEX_THEME_PANEL_SLUG .'-admin-login',
			array( $this, 'create_admin_page' )
		);
	}

	/**
	 * Register a setting and its sanitization callback.
	 *
	 * @since 1.6.0
	 */
	public function register_settings() {
		register_setting( 'wpex_custom_login', 'login_page_design', array( $this, 'sanitize' ) );
	}

	/**
	 * Sanitization callback
	 *
	 * @since 1.6.0
	 */
	public function sanitize( $options ) {

		// If we have options lets save them in the theme_mods
		if ( $options ) {

			// Loop through options to prevent empty vars from saving
			foreach ( $options as $key => $val ) {
				if ( empty( $val ) ) {
					unset( $options[$key] );
				}
				if ( 'background_img' == $key && empty( $val ) ) {
					unset( $options['background_style'] );
				}
				if ( 'logo' == $key && empty( $val ) ) {
					unset( $options['logo_height'] );
				}
			}

			// Save theme_mod
			set_theme_mod( 'login_page_design', $options );


		}

		// Clear options
		$options = '';

		// Return nothing since we are storing in theme_mods not options table
		return;

	}

	/**
	 * Settings page output
	 *
	 * @since 1.6.0
	 */
	public function create_admin_page() { ?>

		<div class="wrap">

			<h1><?php esc_html_e( 'Custom Login', 'total' ); ?></h1>

			<h2 class="nav-tab-wrapper wpex-panel-js-tabs">
				<a href="#main" class="nav-tab nav-tab-active"><?php esc_html_e( 'Main', 'total' ); ?></a>
				<a href="#background" class="nav-tab"><?php esc_html_e( 'Background', 'total' ); ?></a>
				<a href="#form" class="nav-tab"><?php esc_html_e( 'Form', 'total' ); ?></a>
				<a href="#button" class="nav-tab"><?php esc_html_e( 'Button', 'total' ); ?></a>
			</h2>

			<?php $theme_mod = $this->options(); ?>

			<form method="post" action="options.php">

				<?php settings_fields( 'wpex_custom_login' ); ?>

				<table class="form-table wpex-tabs-wrapper">

					<tr valign="top" class="wpex-tab-content wpex-main">
						<th scope="row"><?php esc_html_e( 'Enable', 'total' ); ?></th>
						<td>
							<?php $enabled = isset ( $theme_mod['enabled'] ) ? $theme_mod['enabled'] : ''; ?>
							<input type="checkbox" name="login_page_design[enabled]" <?php checked( $enabled, 'on' ); ?>>
						</td>
					</tr>

					<tr valign="top" class="wpex-tab-content wpex-main">
						<th scope="row"><?php esc_html_e( 'Logo', 'total' ); ?></th>
						<td>
							<?php $option = isset( $theme_mod['logo'] ) ? $theme_mod['logo'] : ''; ?>
							<input class="wpex-media-input" type="text" name="login_page_design[logo]" value="<?php echo esc_attr( $option ); ?>">
							<input class="wpex-media-upload-button button-secondary" type="button" value="<?php esc_html_e( 'Upload', 'total' ); ?>" />
							<a href="#" class="wpex-media-remove button-secondary" style="display:none;"><span class="dashicons dashicons-no-alt" style="line-height: inherit;"></span></a>
							<?php $preview = wpex_sanitize_data( $option, 'image_src_from_mod' ); ?>
							<div class="wpex-media-live-preview">
								<?php if ( $preview ) { ?>
									<img src="<?php echo esc_url( $preview ); ?>" alt="<?php esc_html_e( 'Preview Image', 'total' ); ?>" />
								<?php } ?>
							</div>
						</td>
					</tr>

					<tr valign="top" class="wpex-tab-content wpex-main">
						<th scope="row"><?php esc_html_e( 'Logo Height', 'total' ); ?></th>
						<td>
							<?php $option = ! empty( $theme_mod['logo_height'] ) ? intval( $theme_mod['logo_height'] ) : ''; ?>
							<input type="number" name="login_page_design[logo_height]" value="<?php echo esc_attr( $option ); ?>">
						</td>
					</tr>

					<tr valign="top" class="wpex-tab-content wpex-main">
						<th scope="row"><?php esc_html_e( 'Logo URL', 'total' ); ?></th>
						<td>
							<?php $option = isset( $theme_mod['logo_url'] ) ? $theme_mod['logo_url'] : ''; ?>
							<input type="text" name="login_page_design[logo_url]" value="<?php echo esc_attr( $option ); ?>">
						</td>
					</tr>

					<tr valign="top" class="wpex-tab-content wpex-main">
						<th scope="row"><?php esc_html_e( 'Logo Title Attribute', 'total' ); ?></th>
						<td>
							<?php $option = isset( $theme_mod['logo_url_title'] ) ? $theme_mod['logo_url_title'] : ''; ?>
							<input type="text" name="login_page_design[logo_url_title]" value="<?php echo esc_attr( $option ); ?>">
						</td>
					</tr>

					<tr valign="top" class="wpex-tab-content wpex-background">
						<th scope="row"><?php esc_html_e( 'Background Color', 'total' ); ?></th>
						<td>
							<?php $option = isset( $theme_mod['background_color'] ) ? $theme_mod['background_color'] : ''; ?>
							<input id="background_color" type="text" name="login_page_design[background_color]" value="<?php echo esc_attr( $option ); ?>" class="wpex-color-field">
						</td>
					</tr>

					<tr valign="top" class="wpex-tab-content wpex-background">
						<th scope="row"><?php esc_html_e( 'Background Image', 'total' ); ?></th>
						<td>
							<?php $option = isset( $theme_mod['background_img'] ) ? $theme_mod['background_img'] : ''; ?>
							<div class="uploader">
								<input class="wpex-media-input" type="text" name="login_page_design[background_img]" value="<?php echo esc_attr( $option ); ?>">
								<input class="wpex-media-upload-button button-secondary" type="button" value="<?php esc_html_e( 'Upload', 'total' ); ?>" />
								<a href="#" class="wpex-media-remove button-secondary" style="display:none;"><span class="dashicons dashicons-no-alt" style="line-height: inherit;"></span></a>
								<?php $preview = wpex_sanitize_data( $option, 'image_src_from_mod' ); ?>
								<div class="wpex-media-live-preview">
									<?php if ( $preview ) { ?>
										<img src="<?php echo esc_url( $preview ); ?>" alt="<?php esc_html_e( 'Preview Image', 'total' ); ?>" />
									<?php } ?>
								</div>
							</div>
						</td>
					</tr>

					<tr valign="top" class="wpex-tab-content wpex-background">
						<th scope="row"><?php esc_html_e( 'Background Image Style', 'total' ); ?></th>
						<td>
							<?php $option = isset( $theme_mod['background_style'] ) ? $theme_mod['background_style'] : ''; ?>
							<select name="login_page_design[background_style]">
								<?php
								$bg_styles = array(
									'stretched' => esc_html__( 'Stretched','total' ),
									'repeat'    => esc_html__( 'Repeat','total' ),
									'fixed'     => esc_html__( 'Center Fixed','total' ),
								);
								foreach ( $bg_styles as $key => $val ) { ?>
									<option value="<?php echo esc_attr( $key ); ?>" <?php selected( $option, $key, true ); ?>>
										<?php echo strip_tags( $val ); ?>
									</option>
								<?php } ?>
							</select>
						</td>
					</tr>

					<tr valign="top" class="wpex-tab-content wpex-form">
						<th scope="row"><?php esc_html_e( 'Form Background Color', 'total' ); ?></th>
						<td>
							<?php $option = isset( $theme_mod['form_background_color'] ) ? $theme_mod['form_background_color'] : ''; ?>
							<input id="form_background_color" type="text" name="login_page_design[form_background_color]" value="<?php echo esc_attr( $option ); ?>" class="wpex-color-field">
						</td>
					</tr>

					<tr valign="top" class="wpex-tab-content wpex-form">
						<th scope="row"><?php esc_html_e( 'Form Background Opacity', 'total' ); ?></th>
						<td>
							<?php $option = ! empty( $theme_mod['form_background_opacity'] ) ? floatval( $theme_mod['form_background_opacity'] ) : ''; ?>
							<input type="number" name="login_page_design[form_background_opacity]" value="<?php echo esc_attr( $option ); ?>" min="0" max="1" step="0.1">
						</td>
					</tr>

					<tr valign="top" class="wpex-tab-content wpex-form">
						<th scope="row"><?php esc_html_e( 'Form Text Color', 'total' ); ?></th>
						<td>
							<?php $option = isset( $theme_mod['form_text_color'] ) ? $theme_mod['form_text_color'] : ''; ?>
							<input id="form_text_color" type="text" name="login_page_design[form_text_color]" value="<?php echo esc_attr( $option ); ?>" class="wpex-color-field">
						</td>
					</tr>

					<tr valign="top" class="wpex-tab-content wpex-form">
						<th scope="row"><?php esc_html_e( 'Form Input Background', 'total' ); ?></th>
						<td>
							<?php $option = isset( $theme_mod['form_input_bg'] ) ? $theme_mod['form_input_bg'] : ''; ?>
							<input id="form_input_bg" type="text" name="login_page_design[form_input_bg]" value="<?php echo esc_attr( $option ); ?>" class="wpex-color-field">
						</td>
					</tr>

					<tr valign="top" class="wpex-tab-content wpex-form">
						<th scope="row"><?php esc_html_e( 'Form Input Color', 'total' ); ?></th>
						<td>
							<?php $option = isset( $theme_mod['form_input_color'] ) ? $theme_mod['form_input_color'] : ''; ?>
							<input id="form_input_color" type="text" name="login_page_design[form_input_color]" value="<?php echo esc_attr( $option ); ?>" class="wpex-color-field">
						</td>
					</tr>

					<tr valign="top" class="wpex-tab-content wpex-form">
						<th scope="row"><?php esc_html_e( 'Form Top Margin', 'total' ); ?></th>
						<td>
							<?php $option = ! empty( $theme_mod['form_top'] ) ? wpex_sanitize_data( $theme_mod['form_top'], 'px_pct' ) : ''; ?>
							<input type="text" name="login_page_design[form_top]" value="<?php echo esc_attr( $option ); ?>">
						</td>
					</tr>

					<tr valign="top" class="wpex-tab-content wpex-form">
						<th scope="row"><?php esc_html_e( 'Form Border Radius', 'total' ); ?></th>
						<td>
							<?php $option = ! empty( $theme_mod['form_border_radius'] ) ? intval( $theme_mod['form_border_radius'] ) : ''; ?>
							<input type="text" name="login_page_design[form_border_radius]" value="<?php echo esc_attr( $option ); ?>">
						</td>
					</tr>

					<tr valign="top" class="wpex-tab-content wpex-button">
						<th scope="row"><?php esc_html_e( 'Form Button Background', 'total' ); ?></th>
						<td>
							<?php $option = isset( $theme_mod['form_button_bg'] ) ? $theme_mod['form_button_bg'] : ''; ?>
							<input id="form_button_bg" type="text" name="login_page_design[form_button_bg]" value="<?php echo esc_attr( $option ); ?>" class="wpex-color-field">
						</td>
					</tr>

					<tr valign="top" class="wpex-tab-content wpex-button">
						<th scope="row"><?php esc_html_e( 'Form Button Color', 'total' ); ?></th>
						<td>
							<?php $option = isset( $theme_mod['form_button_color'] ) ? $theme_mod['form_button_color'] : ''; ?>
							<input id="form_button_color" type="text" name="login_page_design[form_button_color]" value="<?php echo esc_attr( $option ); ?>" class="wpex-color-field">
						</td>
					</tr>

					<tr valign="top" class="wpex-tab-content wpex-button">
						<th scope="row"><?php esc_html_e( 'Form Button Background: Hover', 'total' ); ?></th>
						<td>
							<?php $option = isset( $theme_mod['form_button_bg_hover'] ) ? $theme_mod['form_button_bg_hover'] : ''; ?>
							<input id="form_button_bg_hover" type="text" name="login_page_design[form_button_bg_hover]" value="<?php echo esc_attr( $option ); ?>" class="wpex-color-field">
						</td>
					</tr>

				</table>

				<?php submit_button(); ?>

			</form>

		</div>

	<?php }

	/**
	 * RGBA to HEX conversions
	 *
	 * @since 1.6.0
	 */
	public function hex2rgba( $color, $opacity = false ) {

		// Define default rgba
		$default = 'rgb(0,0,0)';

		//Return default if no color provided
		if( empty( $color ) ) {
			return $default;
		}

		//Sanitize $color if "#" is provided 
		if ( $color[0] == '#' ) {
			$color = substr( $color, 1 );
		}

		//Check if color has 6 or 3 characters and get values
		if ( strlen( $color ) == 6) {
			$hex = array( $color[0] . $color[1], $color[2] . $color[3], $color[4] . $color[5] );
		} elseif ( strlen( $color ) == 3 ) {
			$hex = array( $color[0] . $color[0], $color[1] . $color[1], $color[2] . $color[2] );
		} else {
			return $default;
		}

		//Convert hexadec to rgb
		$rgb =  array_map( 'hexdec', $hex );

		//Check if opacity is set(rgba or rgb)
		if( $opacity ) {
			if( abs ( $opacity ) > 1 )
				$opacity = 1.0;
			$output = 'rgba('.implode( ",", $rgb ).','.$opacity.')';
		} else {
			$output = 'rgb('.implode( ",", $rgb ).')';
		}

		//Return rgb(a) color string
		return $output;
	}

	/**
	 * Outputs the CSS for the custom login page
	 *
	 * @since 1.6.0
	 */
	public function output_css() {

		// Get options
		$options = $this->options();

		// Do nothing if disabled
		if ( empty( $options['enabled'] ) ) {
			return;
		}

		// Sanitize data
		$logo                    = $this->parseOption( 'logo' );
		$logo_height             = $this->parseOption( 'logo_height', '84px' );
		$logo_height             = intval( $logo_height ) .'px';
		$background_img          = $this->parseOption( 'background_img' );
		$background_style        = $this->parseOption( 'background_style' );
		$background_color        = $this->parseOption( 'background_color' );
		$form_background_color   = $this->parseOption( 'form_background_color' );
		$form_background_opacity = $this->parseOption( 'form_background_opacity' );
		$form_text_color         = $this->parseOption( 'form_text_color' );
		$form_top                = $this->parseOption( 'form_top', '150px' );
		$form_input_bg           = $this->parseOption( 'form_input_bg' );
		$form_input_color        = $this->parseOption( 'form_input_color' );
		$form_border_radius      = $this->parseOption( 'form_border_radius' );
		$form_button_bg          = $this->parseOption( 'form_button_bg' );
		$form_button_bg_hover    = $this->parseOption( 'form_button_bg_hover' );
		$form_button_color       = $this->parseOption( 'form_button_color' );

		// Convert image ID's to urls
		if ( is_numeric( $logo ) ) {
			$logo = wp_get_attachment_image_src( $logo, 'full' );
			$logo = $logo[0];
		}
		if ( is_numeric( $background_img ) ) {
			$background_img = wp_get_attachment_image_src( $background_img, 'full' );
			$background_img = $background_img[0];
		}

		// Output Styles
		$output = '';

			// Logo
			if ( $logo ) {
				$output .='body.login div#login h1 a {';
					$output .='background: url("'. $logo .'") center center no-repeat;';
					$output .='height: '. intval( $logo_height ) .'px;';
					$output .='width: 100%;';
					$output .='display: block;';
					$output .='margin: 0 auto 30px;';
				$output .='}';
			}

			// Background image
			if ( $background_img ) {
				if ( 'stretched' == $background_style ) {
					$output .= 'body.login { background: url('. $background_img .') no-repeat center center fixed; -webkit-background-size: cover;-moz-background-size: cover;-o-background-size: cover;background-size: cover; }';
				} elseif ( 'repeat' == $background_style ) {
					$output .= 'body.login { background: url('. $background_img .') repeat; }';
				} elseif ( 'fixed' == $background_style ) {
					$output .= 'body.login { background: url('. $background_img .') center top fixed no-repeat; }';
				}
			}

			// Background color
			if ( $background_color ) {
				$output .='body.login { background-color: '. $background_color .'; }';
			}

			// Form top
			if ( $form_top ) {
				$output .= 'body.login div#login { padding-top: 0; position: relative; top:'. wpex_sanitize_data( $form_top, 'px_pct' ) .'; }';
			}

			// Form Background Color
			if ( $form_background_color ) {
				$form_bg_color_rgba = $this->hex2rgba( $form_background_color, $form_background_opacity );
				$output .='.login form { background: none; -webkit-box-shadow: none; box-shadow: none; padding: 0 0 20px; } #backtoblog { display: none; } .login #nav { text-align: center; }';
				$output .='body.login div#login { background: '. $form_background_color .'; background: '. $form_bg_color_rgba .';height:auto;left:50%;margin: 0 0 0 -200px;padding:40px;position:absolute;width:320px; max-width:90%; border-radius: 5px; }';
			} else {
				$output .= 'body.login div#login { opacity:'. $form_background_opacity .'; }';
			}

			// Form top
			if ( $form_border_radius ) {
				if ( $form_background_color ) {
					$output .= 'body.login div#login { border-radius:'. intval( $form_border_radius ) .'px; }';
				} else {
					$output .= 'body.login div#login #loginform { border-radius:'. intval( $form_border_radius ) .'px; }';
				}
			}

			// Form input
			if ( $form_input_bg ) {
				$output .='body.login div#login input.input { background: '. $form_input_bg .'; border: 0; box-shadow: none; }';
			}
			if ( $form_input_color ) {
				$output .='body.login form .input { color: '. $form_input_color .'; }';
			}

			// Text Color
			if ( $form_text_color ) {
				$output .='.login label, .login #nav a, .login #backtoblog a, .login #nav { color: '. $form_text_color .'; }';
			}

			// Button background
			if ( $form_button_bg ) {
				$output .='body.login div#login .button { background: '. $form_button_bg .'; border:0; outline: 0; }';
			}

			// Button background
			if ( $form_button_color ) {
				$output .='body.login div#login .button { color: '. $form_button_color .'; }';
			}

			// Button background Hover
			if ( $form_button_bg_hover ) {
				$output .='body.login div#login .button:hover { background: '. $form_button_bg_hover .'; border:0; outline: 0; }';
			}

			// Remove box-shadow
			if ( $form_button_bg || $form_button_bg_hover ) {
				$output .= 'body.login div#login .button{ box-shadow: none !important; }';
			}

			// Remove text-shadow
			if ( $form_button_color || $form_button_bg ) {
				$output .= 'body.login div#login .button{ text-shadow:none; }';
			}

		// Echo output
		if ( $output ) {
			echo '<style>' . wp_strip_all_tags( $output ) . '</style>';
		}

	}

	/**
	 * Parses data
	 *
	 * @since 1.6.0
	 */
	public function parseOption( $option_id, $default = '' ) {
		$options = $this->options();
		return ! empty( $options[$option_id] ) ? $options[$option_id] : $default;
	}

	/**
	 * Custom login page logo URL
	 *
	 * @since 1.6.0
	 */
	public function login_headerurl( $url ) {
		$options = $this->options();
		$url     = isset( $options['logo_url'] ) ? esc_url( $options['logo_url'] ) : $url;
		return $url;
	}

	/**
	 * Custom login page logo URL title attribute
	 *
	 * @since 4.5.4.2
	 */
	public function login_headertitle( $title ) {
		$options = $this->options();
		$title   = isset( $options['logo_url_title'] ) ? esc_attr( $options['logo_url_title'] ) : $title;
		return $title;
	}

}

new WPEX_Custom_Login();