<?php
/**
 * Shortcodes in the TinyMCE
 *
 * @package Total WordPress Theme
 * @subpackage Framework
 * @version 4.6.5
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Allow for the use of shortcodes in the WordPress excerpt
add_filter( 'the_excerpt', 'shortcode_unautop' );
add_filter( 'the_excerpt', 'do_shortcode' );

// Allow shortcodes in menus
add_filter( 'wp_nav_menu_items', 'do_shortcode' );

/**
 * Text highlight
 *
 * @since 4.6.5
 */
if ( ! shortcode_exists( 'highlight' ) ) {
	function wpex_text_highlight( $atts, $content = '' ) {
		$atts = shortcode_atts( array(
			'color'  => '',
			'height' => '',
		), $atts, 'highlight' );
		return '<span class="wpex-highlight">' . wp_kses_post( $content ) . '<span class="wpex-after wpex-accent-bg"' . wpex_parse_inline_style( array( 'background' => $atts['color'], 'height' => $atts['height'] ), true ) . '></span></span>';
	}
	add_shortcode( 'highlight', 'wpex_text_highlight' );
}

/**
 * Line break shortcode
 *
 * @since 4.5.2
 */
if ( ! shortcode_exists( 'br' ) ) {
	function wpex_br_shortcode() {
		return '<br />';
	}
	add_shortcode( 'br', 'wpex_br_shortcode' );
}

/**
 * Username shortcode
 *
 * @since 4.4
 */
if ( ! shortcode_exists( 'username' ) ) {
	function wpex_username_shortcode() {
		$current_user = wp_get_current_user();
		if ( ! ( $current_user instanceof WP_User ) ) {
			return;
		}
		return esc_html( $current_user->display_name );
	}
	add_shortcode( 'username', 'wpex_username_shortcode' );
}

/**
 * Custom date shortcode
 *
 * @since 4.4
 */
if ( ! shortcode_exists( 'date' ) ) {
	function wpex_date_format( $atts ) {
		$atts = shortcode_atts( array(
			'id'     => null,
			'format' => 'F j, Y',
		), $atts );
		$id = ! empty( $atts['id'] ) ? $atts['id'] : get_the_ID();
		$format = ! empty( $atts['format'] ) ? $atts['format'] : get_option( 'date_format' );
		return get_the_date( $format, $id );
	}
	add_shortcode( 'date', 'wpex_date_format' );
}

/**
 * Allow shortcodes in widgets
 *
 * @since 1.3.3
 */
add_filter( 'widget_text', 'do_shortcode' );

/**
 * Fixes spacing issues with shortcodes
 *
 * @since 1.0.0
 */
if ( ! function_exists( 'wpex_fix_shortcodes' ) ) {
	function wpex_fix_shortcodes( $content ){
		$array = array(
			'<p>['    => '[', 
			']</p>'   => ']', 
			']<br />' => ']'
		);
		$content = strtr( $content, $array) ;
		return $content;
	}
}
add_filter( 'the_content', 'wpex_fix_shortcodes' );

/**
 * Searchform shortcode
 *
 * @since 3.5.0
 */
if ( ! function_exists( 'wpex_searchform_shortcode' ) && ! shortcode_exists( 'searchform' ) ) {
	function wpex_searchform_shortcode() {
		ob_start();
		get_search_form();
		return ob_get_clean();
	}
	add_shortcode( 'searchform', 'wpex_searchform_shortcode' );
}

/**
 * Post Title
 *
 * @since 4.4.1
 */
if ( ! shortcode_exists( 'post_title' ) ) {
	function wpex_post_title() {
		return get_the_title();
	}
	add_shortcode( 'post_title', 'wpex_post_title' );
}

/**
 * Post Permalink
 *
 * @since 4.4.1
 */
if ( ! shortcode_exists( 'post_permalink' ) ) {
	function wpex_post_permalink() {
		return get_permalink();
	}
	add_shortcode( 'post_permalink', 'wpex_post_permalink' );
}

/**
 * Post Publish Date
 *
 * @since 4.4.1
 */
if ( ! shortcode_exists( 'post_publish_date' ) ) {
	function wpex_post_publish_date() {
		return get_the_date();
	}
	add_shortcode( 'post_publish_date', 'wpex_post_publish_date' );
}

/**
 * Post Modified Date
 *
 * @since 4.4.1
 */
if ( ! shortcode_exists( 'post_modified_date' ) ) {
	function wpex_post_modified_date() {
		return get_the_modified_date();
	}
	add_shortcode( 'post_modified_date', 'wpex_post_modified_date' );
}

/**
 * Post Author
 *
 * @since 4.4.1
 */
if ( ! shortcode_exists( 'post_author' ) ) {
	function wpex_post_author() {
		global $post;
		return $post ? get_the_author_meta( 'nicename', $post->post_author ) : '';
	}
	add_shortcode( 'post_author', 'wpex_post_author' );
}

/**
 * Year shortcode
 *
 * @since 1.0.0
 */
if ( ! shortcode_exists( 'current_year' ) ) {
	function wpex_year_shortcode() {
		return date( 'Y' );
	}
	add_shortcode( 'current_year', 'wpex_year_shortcode' );
}

/**
 * Custom field shortcode
 *
 * @since 1.0.0
 */
if ( ! shortcode_exists( 'cf_value' ) ) {
	function wpex_cf_value_shortcode( $atts ) {
		extract( shortcode_atts( array(
			'name' => '',
		), $atts ) );
		if ( $name ) {
			return get_post_meta( get_the_ID(), $name, true );
		}
	}
	add_shortcode( 'cf_value', 'wpex_cf_value_shortcode' );
}

/**
 * Font Awesome Shortcode
 *
 * @since 1.3.2
 */
if ( ! function_exists( 'wpex_font_awesome_shortcode' ) ) {

	function wpex_font_awesome_shortcode( $atts ) {

		extract( shortcode_atts( array(
			'icon'          => '',
			'link'          => '',
			'link_title'    => '',
			'link_target'   => '',
			'link_rel'      => '',
			'margin_right'  => '',
			'margin_left'   => '',
			'margin_top'    => '',
			'margin_bottom' => '',
			'color'         => '',
			'size'          => '',
			'link'          => '',
		), $atts ) );

		// Sanitize vars
		$link       = esc_url( $link );
		$icon       = esc_attr( $icon );
		$link_title = $link_title ? esc_attr( $link_title ) : '';

		// Generate inline styles
		$style = array();
		if ( $color ) {
			$style[] = 'color:' . esc_attr( $color ) . ';';
		}
		if ( $margin_left ) {
			$style[] = 'margin-left:' . intval( $margin_left ) . 'px;';
		}
		if ( $margin_right ) {
			$style[] = 'margin-right:' . intval( $margin_right ) . 'px;';
		}
		if ( $margin_top ) {
			$style[] = 'margin-top:' . intval( $margin_top ) . 'px;';
		}
		if ( $margin_bottom ) {
			$style[] = 'margin-bottom:' . intval( $margin_bottom ) . 'px;';
		}
		if ( $size ) {
			$style[] = 'font-size:' . intval( $size ) . 'px;';
		}
		$style = implode( '', $style );

		if ( $style ) {
			$style = wp_kses( $style, array() );
			$style = ' style="' . esc_attr( $style) . '"';
		}

		// Display icon with link
		if ( $link ) {

			$output = wpex_parse_html(
				'a',
				array(
					'href'   => $link,
					'title'  => $link_title,
					'target' => $link_target,
					'rel'    => $link_rel,
				),
				'<span class="fa fa-' . $icon . '"' . $style . '></span>'
			);

		}

		// Display icon without link
		else {
			$output = '<span class="fa fa-' . $icon . '"' . $style . '></span>';
		}

		// Return shortcode output
		return $output;

	}

}
add_shortcode( 'font_awesome', 'wpex_font_awesome_shortcode' );

/**
 * Login Link
 *
 * @since 1.3.2
 */
if ( ! function_exists( 'wpex_wp_login_url_shortcode' ) ) {

	function wpex_wp_login_url_shortcode( $atts ) {

		extract( shortcode_atts( array(
			'login_url'       => '',
			'url'             => '',
			'text'            => esc_html__( 'Login', 'total' ),
			'logout_text'     => esc_html__( 'Log Out', 'total' ),
			'target'          => '',
			'logout_redirect' => '',
		), $atts, 'wp_login_url' ) );

		// Target
		if ( 'blank' == $target ) {
			$target = 'target="_blank"';
		} else {
			$target = '';
		}

		// Define login url
		if ( $url ) {
			$login_url = $url;
		} elseif ( $login_url ) {
			$login_url = $login_url;
		} else {
			$login_url = wp_login_url();
		}

		// Logout redirect
		if ( ! $logout_redirect ) {
			$permalink = get_permalink();
			if ( $permalink ) {
				$logout_redirect = $permalink;
			} else {
				$logout_redirect = home_url( '/' );
			}
		}

		// Logged in link
		if ( is_user_logged_in() ) {
			$attrs = array(
				'href'  => wp_logout_url( $logout_redirect ),
				'class' => 'wpex_logout',
			);
			$content = strip_tags( $logout_text );
		}

		// Non-logged in link
		else {
			$attrs = array(
				'href'  => esc_url( $login_url ),
				'class' => 'login',
			);
			$content = strip_tags( $text );
		}

		$attrs['target'] = $target;

		return wpex_parse_html( 'a', $attrs, $content );

	}

}
add_shortcode( 'wp_login_url', 'wpex_wp_login_url_shortcode' );

// Add shortcode buttons to the MCE Editor
if ( wpex_get_mod( 'editor_shortcodes_enable', true ) ) {

	// Adds filters to admin_head
	function wpex_shortcodes_add_mce_button() {
		if ( ! current_user_can( 'edit_posts' ) && !current_user_can( 'edit_pages' ) ) {
			return;
		}
		if ( 'true' == get_user_option( 'rich_editing' ) ) {
			add_filter( 'mce_external_plugins', 'wpex_shortcodes_add_tinymce_plugin' );
			add_filter( 'mce_buttons', 'wpex_shortcodes_register_mce_button' );
		}
	}
	add_action( 'admin_head', 'wpex_shortcodes_add_mce_button' );

	// Loads js for the Button
	function wpex_shortcodes_add_tinymce_plugin( $plugin_array ) {
		$plugin_array['wpex_shortcodes_mce_button'] = wpex_asset_url( 'js/dynamic/wpex-tinymce.js' );
		return $plugin_array;
	}

	// Registers new button
	function wpex_shortcodes_register_mce_button( $buttons ) {
		array_push( $buttons, 'wpex_shortcodes_mce_button' );
		return $buttons;
	}

	// Localize js
	function wpex_shortcodes_tinymce_json() {

		// TinyMCE data array
		$data = array();
		$data['btnLabel']   = esc_html__( 'Shortcodes', 'total' );
		$data['shortcodes'] = array(
			'br' => array(
				'text' => esc_html__( 'Line Break', 'total' ),
				'insert' => '[br]',
			),
			'font_awesome' => array(
				'text' => esc_html__( 'Icon', 'total' ),
				'insert' => '[font_awesome link="" icon="bolt" color="000" size="16px" margin_right="" margin_left="" margin_top="" margin_bottom=""]',
			),
			'current_year' => array(
				'text' => esc_html__( 'Current Year', 'total' ),
				'insert' => '[current_year]',
			),
			'searchform' => array(
				'text' => esc_html__( 'WP Searchform', 'total' ),
				'insert' => '[searchform]',
			),
		);

		// Apply filters for child theming
		$data = apply_filters( 'wpex_shortcodes_tinymce_json', $data ); ?>

		<!-- Total TinyMCE Shortcodes -->
		<script>var wpexTinymce = <?php echo wp_json_encode( $data ); ?> ;</script>
		<!-- Total TinyMCE Shortcodes -->

	<?php }
	add_action( 'admin_footer', 'wpex_shortcodes_tinymce_json' );

}