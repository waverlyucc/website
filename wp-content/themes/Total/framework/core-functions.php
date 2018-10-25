<?php
/**
 * Core theme functions - VERY IMPORTANT!!
 *
 * These functions are used throughout the theme and must be loaded
 * early on.
 *
 * Do not ever edit this file, if you need to make
 * adjustments, please use a child theme. If you aren't sure how, please ask!
 *
 * @package Total WordPress Theme
 * @subpackage Framework
 * @version 4.7.1
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/*-------------------------------------------------------------------------------*/
/* [ Table of contents ]
/*-------------------------------------------------------------------------------*

	# General / Core
	# Sanitize Data
	# Parse HTML
	# Grids / Entries
	# Content Blocks ( Entrys & Posts )
	# Taxonomy & Terms
	# Sliders
	# Images
	# Buttons
	# Search Functions
	# Lightbox
	# Post Galleries
	# PHP Helpers
	# Other

/*-------------------------------------------------------------------------------*/
/* [ General ]
/*-------------------------------------------------------------------------------*/

/**
 * Get Theme Branding
 *
 * @since 3.3.0
 */
function wpex_get_theme_branding() {
	$branding = WPEX_THEME_BRANDING;
	if ( $branding && 'disabled' != $branding ) {
		return $branding;
	}
}

/**
 * Get Theme Accent Color
 *
 * @since 4.4.1
 */
function wpex_get_custom_accent_color() {
	$custom_accent  = wpex_get_mod( 'accent_color' );
	if ( $custom_accent == '#3b86b0' || $custom_accent == '#4a97c2' ) {
		return; // Default accents
	}
	if ( $custom_accent ) {
		return $custom_accent;
	}
}

/**
 * Get Theme Accent Hover Color
 *
 * @since 4.5
 */
function wpex_get_custom_accent_color_hover() {
	$custom_accent = wpex_get_mod( 'accent_color_hover' );
	return $custom_accent ? $custom_accent : wpex_get_custom_accent_color();
}

/**
 * Return correct assets url for loading scripts
 *
 * @since 3.6.0
 */
if ( ! function_exists( 'wpex_asset_url' ) ) {
	function wpex_asset_url( $part = '' ) {
		return WPEX_THEME_URI . '/assets/' . $part;
	}
}

/**
 * Returns array of recommended plugins
 *
 * @since 3.3.3
 */
function wpex_recommended_plugins() {
	return apply_filters( 'wpex_recommended_plugins', array(
		'js_composer'          => array(
			'name'             => 'WPBakery Page Builder',
			'slug'             => 'js_composer',
			'version'          => WPEX_VC_SUPPORTED_VERSION,
			'source'           => WPEX_FRAMEWORK_DIR_URI . 'plugins/js_composer.zip',
			'required'         => false,
			'force_activation' => false,
		),
		'templatera'           => array(
			'name'             => 'Templatera',
			'slug'             => 'templatera',
			'source'           => WPEX_FRAMEWORK_DIR_URI . 'plugins/templatera.zip',
			'version'          => '1.1.12',
			'required'         => false,
			'force_activation' => false,
		),
		'revslider'            => array(
			'name'             => 'Slider Revolution',
			'slug'             => 'revslider',
			'version'          => '5.4.8',
			'source'           => WPEX_FRAMEWORK_DIR_URI . 'plugins/revslider.zip',
			'required'         => false,
			'force_activation' => false,
		),
		'contact-form-7'       => array(
			'name'             => 'Contact Form 7',
			'slug'             => 'contact-form-7',
			'required'         => false,
			'force_activation' => false,
		),
	) );
}

/**
 * Get theme license
 *
 * Please purchase a legal copy of the theme and don't just hack this
 * function. First of all if you hack it, you won't get updates because
 * there is added validation on our updates API so it won't work.
 * And second, a lot of time and resources has gone into the development
 * of this awesome theme, purchasing a valid license is the right thing to do.
 *
 * @since 4.5
 */
function wpex_get_theme_license() {
	$license = '';
	if ( is_multisite() && ! is_main_site() ) {
		switch_to_blog( get_network()->site_id );
		$license = get_option( 'active_theme_license' );
		restore_current_blog();
	}
	return $license ? $license : get_option( 'active_theme_license' );
}

/**
 * Verify active license
 *
 * @since 4.5.4
 */
function wpex_verify_active_license( $license = '' ) {
	$license = $license ? $license : wpex_get_theme_license();
	if ( ! $license ) {
		return;
	}
	$args = array(
		'market'  => 'envato',
		'license' => $license,
		'verify'  => 1,
	);
	if ( get_option( 'active_theme_license_dev', false ) ) {
		$args['dev'] = '1';
	}
	$remote_url = add_query_arg( $args, 'https://wpexplorer-themes.com/deactivate-license/' );
	$remote_response = wp_remote_get( $remote_url, array( 'timeout' => 5 ) );
	if ( ! is_wp_error( $remote_response ) ) {
		$result = json_decode( wp_remote_retrieve_body( $remote_response ) );
		if ( 'inactive' == $result ) {
			delete_option( 'active_theme_license' );
			delete_option( 'active_theme_license_dev' );
			return false;
		}
	}
	return true;
}

/**
 * Helper function for resizing images using the WPEX_Image_Resize class
 *
 * @since 4.0
 */
function wpex_image_resize( $args ) {
	$new = TotalTheme\ResizeImage::getInstance();
	return $new->process( $args );
}

/**
 * Returns current URL
 *
 * @since 4.0
 */
function wpex_get_current_url() {
	global $wp;
	if ( $wp ) {
		return home_url( add_query_arg( array(), $wp->request ) );
	}
}

/**
 * Returns correct ID
 *
 * Fixes some issues with posts page and 3rd party plugins that use custom pages for archives
 * such as WooCommerce. So we can correctly get post_meta values
 *
 * @since 4.0
 */
function wpex_get_current_post_id() {

	// Default value is empty
	$id = '';

	// If singular get_the_ID
	if ( is_singular() ) {
		$id = get_queried_object_id();
		$id = $id ? $id : get_the_ID(); // backup
	}

	// Posts page
	elseif ( is_home() && $page_for_posts = get_option( 'page_for_posts' ) ) {
		$id = $page_for_posts;
	}

	// Apply filters and return
	return apply_filters( 'wpex_post_id', $id );

}

/**
 * Returns theme custom post types
 *
 * @since 1.3.3
 */
function wpex_theme_post_types() {
	$post_types = array( 'portfolio', 'staff', 'testimonials' );
	$post_types = array_combine( $post_types, $post_types );
	return apply_filters( 'wpex_theme_post_types', $post_types );
}

/**
 * Returns body font size
 * Used to convert EM values to PX values such as for responsive headings.
 *
 * @since 3.3.0
 */
function wpex_get_body_font_size() {
	$body_typo = wpex_get_mod( 'body_typography' );
	$font_size = ! empty( $body_typo['font-size'] ) ? $body_typo['font-size'] : 13;
	return apply_filters( 'wpex_get_body_font_size', $font_size );
}

/**
 * Echo the post URL
 *
 * @since 1.5.4
 */
function wpex_permalink( $post_id = '' ) {
	echo wpex_get_permalink( $post_id );
}

/**
 * Return the post URL
 *
 * @since 2.0.0
 */
function wpex_get_permalink( $post_id = '' ) {

	// If post ID isn't defined lets get it
	$post_id = $post_id ? $post_id : get_the_ID();

	// Check wpex_post_link custom field for custom link
	$redirect = wpex_get_post_redirect_link( $post_id );

	// If wpex_post_link custom field is defined return that otherwise return the permalink
	$permalink = $redirect ? $redirect : get_permalink( $post_id );

	// Apply filters and return
	return esc_url( apply_filters( 'wpex_permalink', $permalink ) );

}

/**
 * Get custom post link
 *
 * @since 4.1.2
 */
function wpex_get_post_redirect_link( $post_id = '' ) {
	$post_id = $post_id ? $post_id : get_the_ID();
	return get_post_meta( $post_id, 'wpex_post_link', true );
}


/**
 * Return custom permalink
 *
 * @since 2.0.0
 */
function wpex_get_custom_permalink() {
	if ( $custom_link = get_post_meta( get_the_ID(), 'wpex_post_link', true ) ) {
		$custom_link = ( 'home_url' == $custom_link ) ? home_url( '/' ) : $custom_link;
		return esc_url( $custom_link );
	}
}

/**
 * Returns correct site layout
 *
 * @since 4.0
 */
function wpex_site_layout( $post_id = '' ) {

	// Check URL
	if ( ! empty( $_GET['site_layout'] ) ) {
		return esc_html( $_GET['site_layout'] );
	}

	// Get layout from theme mod
	$layout = wpex_get_mod( 'main_layout_style', 'full-width' );

	// Get post ID
	$post_id = $post_id ? $post_id : wpex_get_current_post_id();

	// Check meta
	if ( $post_id && $meta = get_post_meta( $post_id, 'wpex_main_layout', true ) ) {
		$layout = $meta;
	}

	// Apply filters
	$layout = apply_filters( 'wpex_main_layout', $layout );

	// Sanitize layout => Can't be empty!!
	$layout = $layout ? $layout : 'full-width';

	// Return layout
	return $layout;

}

/**
 * Returns default content layout
 *
 * @since 4.5
 */
function wpex_get_default_content_area_layout() {
	$default = wpex_get_mod( 'content_layout' );
	return $default ? $default : 'right-sidebar';
}

/**
 * Returns correct content area layout
 *
 * @since 4.0
 */
function wpex_content_area_layout( $post_id = '' ) {

	if ( ! empty( $_GET['post_layout'] ) ) {
		return esc_html( $_GET['post_layout'] );
	}

	$default  = wpex_get_default_content_area_layout();
	$class    = $default;
	$post_id  = $post_id ? $post_id : wpex_get_current_post_id();
	$instance = '';

	// Singular checks // Must use the post_id check to prevent issues
	// with custom pages like Events Calendar, 404 page, etc
	if ( $post_id ) {

		// Check meta first to override and return (prevents filters from overriding meta)
		if ( $post_id && $meta = get_post_meta( $post_id, 'wpex_post_layout', true ) ) {
			return $meta;
		}

		// Get post type
		$post_type = get_post_type();
		$instance  = 'singular_' . $post_type;

		// Singular Page
		if ( 'page' == $post_type ) {

			// Get page layout setting value
			$class = wpex_get_mod( 'page_single_layout' );

			// Get page template layouts
			if ( $page_template = get_page_template_slug( $post_id ) ) {

				// Blog template
				if ( $page_template == 'templates/blog.php' ) {
					$class = wpex_get_mod( 'blog_archives_layout' );
				}

				// Landing Page
				elseif ( $page_template == 'templates/landing-page.php' ) {
					$class = 'full-width';
				}

				// No Sidebar
				elseif ( $page_template == 'templates/no-sidebar.php' ) {
					$class = 'full-width';
				}

				// Left Sidebar
				elseif ( $page_template == 'templates/left-sidebar.php' ) {
					$class = 'left-sidebar';
				}

				// Right Sidebar
				elseif ( $page_template == 'templates/right-sidebar.php' ) {
					$class = 'right-sidebar';
				}

			}

		}

		// Singular Post
		elseif ( 'post' == $post_type ) {
			$class = wpex_get_mod( 'blog_single_layout' );
		}

		// Attachment
		elseif ( 'attachment' == $post_type ) {
			$class = 'full-width';
		}

		// Templatera
		elseif ( 'templatera' == $post_type ) {
			return 'full-width'; // Always return full-width
		}

	} // End singular

	// 404 page => must check before archives due to WP bug with pagination
	elseif ( is_404() ) {
		$instance = '404';
		if ( ! wpex_get_mod( 'error_page_content_id' ) ) {
			$class = 'full-width';
		}
	}

	// Home
	elseif ( is_home() ) {
		$instance = 'home';
		$class = wpex_get_mod( 'blog_archives_layout' );
	}

	// Search => MUST BE BEFORE TAX CHECK, WP returns true for is_tax on search results
	elseif ( is_search() ) {
		$instance = 'search';
		$class = get_theme_mod( 'search_layout' );
	}

	// Define tax instance
	elseif ( is_tax() ) {
		$instance = 'tax'; // Used for filter - do NOT remove!!
	}

	// Define post type archive instance
	elseif( is_post_type_archive() ) {
		$instance = 'post_type_archive';
	}

	// Blog Query => Must come before category check
	elseif ( wpex_is_blog_query() ) {
		$instance = 'wpex_is_blog_query';
		$class = wpex_get_mod( 'blog_archives_layout' );

		// Extra check for categories with custom meta
		if ( is_category() ) {
			$instance = 'category';
			$class = wpex_get_mod( 'blog_archives_layout' );
			$term  = get_query_var( 'cat' );
			if ( $term_data = get_option( "category_$term" ) ) {
				if ( ! empty( $term_data['wpex_term_layout'] ) ) {
					$class = $term_data['wpex_term_layout'];
				}
			}
		}

	}

	// All else
	else {
		$class = $default;
	}

	// Apply filters
	$class = apply_filters( 'wpex_post_layout_class', $class, $instance );

	// Class should never be empty
	if ( empty( $class ) ) {
		$class = $default;
	}

	// Apply filters and return
	return $class;

}

/**
 * Get index loop type
 *
 * @since 4.5
 */
function wpex_get_index_loop_type() {
	$loop_type = wpex_is_blog_query() ? 'blog' : get_post_type();
	return apply_filters( 'wpex_get_index_loop_type', $loop_type );
}

/**
 * Returns the correct sidebar ID
 *
 * @since  1.0.0
 */
function wpex_get_sidebar( $sidebar = 'sidebar', $post_id = '' ) {
	$instance    = '';
	$is_singular = is_singular();
	$type        = $is_singular ? get_post_type() : '';

	// Page Sidebar
	if ( $is_singular ) {

		$instance = 'singular_' . $type;

		// Pages
		if ( 'page' == $type ) {

			if ( wpex_get_mod( 'pages_custom_sidebar', true ) && ! is_page_template( 'templates/blog.php' ) ) {
				$sidebar = 'pages_sidebar';
			}

		}

		// Posts
		elseif ( 'post' == $type ) {

			if ( wpex_get_mod( 'blog_custom_sidebar', false ) ) {
				$sidebar = 'blog_sidebar';
			}

		}

	// Archives
	} else {

		$instance = 'archive';

		// Search Sidebar
		if ( is_search() ) {
			$instance = 'search';
			if ( wpex_get_mod( 'search_custom_sidebar', true ) ) {
				$sidebar = 'search_sidebar';
			}
		}

		// Blog sidebar
		elseif ( wpex_get_mod( 'blog_custom_sidebar', false ) && wpex_is_blog_query() ) {
			$instance = 'wpex_is_blog_query';
			$sidebar = 'blog_sidebar';
		}

		// 404
		elseif ( is_404() ) {
			$instance = '404';
			if ( wpex_get_mod( 'pages_custom_sidebar', true ) ) {
				$sidebar = 'pages_sidebar';
			}
		}

	}

	/***
	 * FILTER    => Add filter for tweaking the sidebar display via child theme's
	 * IMPORTANT => Must be added before meta options so that it doesn't take priority
	 ***/
	$sidebar = apply_filters( 'wpex_get_sidebar', $sidebar, $instance );

	// Get current post id
	$post_id = $post_id ? $post_id : wpex_get_current_post_id();

	// Check meta option after filter so it always overrides
	if ( $meta = get_post_meta( $post_id, 'sidebar', true ) ) {
		$sidebar = $meta;
	}

	// Check term meta after filter so it always overrides
	// get_term_meta introduced in WP 4.4.0
	if ( function_exists( 'get_term_meta' ) ) {

		if ( $is_singular ) {

			if ( 'page' != $type ) {
			
				$meta = '';
				$taxonomies = get_object_taxonomies( $type );
				
				foreach( $taxonomies as $taxonomy ) {
					if ( $meta ) break; // stop loop we found a custom sidebar
					$terms = get_the_terms( get_the_ID(), $taxonomy );
					if ( $terms ) {
						foreach ( $terms as $term ) {
							if ( $meta ) break; // stop loop we found a custom sidebar
							$meta = get_term_meta( $term->term_id, 'wpex_sidebar', true );
						}
					}
				}

				if ( $meta ) {
					$sidebar = $meta;
				}

			}

		}

		// Taxonomies
		elseif ( is_tax() || is_category() || is_tag() ) {
			$term_id = get_queried_object()->term_id;
			if ( $term_id && $meta = get_term_meta( $term_id, 'wpex_sidebar', true ) ) {
				$sidebar = $meta;
			}
		}

	}

	// Never show empty sidebar
	if ( $sidebar && ! is_active_sidebar( $sidebar ) ) {
		$sidebar = 'sidebar';
	}

	// Return the correct sidebar
	return $sidebar;

}

/**
 * Returns the correct classname for any specific column grid
 *
 * @since 1.0.0
 */
function wpex_grid_class( $col = '4' ) {
	return apply_filters( 'wpex_grid_class', 'span_1_of_'. $col );
}

/**
 * Returns the correct gap class
 *
 * @since 1.0.0
 */
function wpex_gap_class( $gap = '' ) {
	return apply_filters( 'wpex_gap_class', 'gap-'. $gap );
}

/**
 * Outputs a theme heading
 *
 * @since 1.3.3
 */
function wpex_heading( $args = array() ) {

	// Define output
	$output = '';

	// Default tag
	$tag = esc_html( wpex_get_mod( 'theme_heading_tag' ) );
	$tag = $tag ? $tag : 'div';

	// Defaults
	$defaults = array(
		'echo'          => true,
		'apply_filters' => '',
		'content'       => '',
		'tag'           => $tag,
		'classes'       => array(),
	);

	// Add filters if defined
	if ( ! empty( $args['apply_filters'] ) ) {
		$args = apply_filters( 'wpex_heading_'. $args['apply_filters'], $args );
	}

	// Parse args
	$args = wp_parse_args( $args, $defaults );

	// Extract args
	extract( $args );

	// Return if text is empty
	if ( ! $content ) {
		return;
	}

	// Sanitize args
	$tag = esc_attr( $tag );

	// Add custom classes
	$add_classes = $classes;
	$classes     = array(
		'theme-heading',
		wpex_get_mod( 'theme_heading_style' )
	);
	if ( $add_classes && is_array( $add_classes ) ) {
		$classes = array_merge( $classes, $add_classes );
	}

	// Turn classes into space seperated string
	$classes = implode( ' ', $classes );

	// Open heading tag
	$output .= '<' . $tag . ' class="' . esc_attr( $classes ) . '">';

		// Heading inner text
		$output .= '<span class="text">' . $content . '</span>';

	// Close heading tag
	$output .= '</' . $tag . '>';

	if ( $echo ) {
		echo $output;
	} else {
		return $output;
	}

}

/**
 * Provides translation support for plugins such as WPML
 *
 * @since 1.3.3
 * @todo Remove this function? I don't think it's needed...or make more use of it.
 */
if ( ! function_exists( 'wpex_element' ) ) {
	function wpex_element( $element ) {

		// Rarr
		if ( 'rarr' == $element ) {
			if ( is_rtl() ) {
				return '&larr;';
			} else {
				return '&rarr;';
			}
		}

		// Angle Right
		elseif ( 'angle_right' == $element ) {

			if ( is_rtl() ) {
				return '<span class="fa fa-angle-left"></span>';
			} else {
				return '<span class="fa fa-angle-right"></span>';
			}

		}

	}
}

/**
 * Returns correct hover animation class
 *
 * @since 2.0.0
 */
function wpex_hover_animation_class( $animation ) {
	return 'hvr hvr-'. $animation;
}

/**
 * Returns correct typography style class
 *
 * @since  2.0.2
 * @return string
 */
function wpex_typography_style_class( $style ) {
	$class = '';
	if ( $style
		&& 'none' != $style
		&& array_key_exists( $style, wpex_typography_styles() ) ) {
		$class = 'typography-'. $style;
	}
	return $class;
}

/**
 * Convert to array
 *
 * @since 2.0.0
 */
function wpex_string_to_array( $value = array() ) {

	// Return empty array if value is empty
	if ( empty( $value ) ) {
		return array();
	}

	// Check if array and not empty
	elseif ( ! empty( $value ) && is_array( $value ) ) {
		return $array;
	}

	// Create our own return
	else {

		// Define array
		$array = array();

		// Clean up value
		$items  = preg_split( '/\,[\s]*/', $value );

		// Create array
		foreach ( $items as $item ) {
			if ( strlen( $item ) > 0 ) {
				$array[] = $item;
			}
		}

		// Return array
		return $array;

	}

}

/**
 * Converts a dashicon into it's CSS
 *
 * @since 1.0.0
 */
function wpex_dashicon_css_content( $dashicon = '' ) {
	$css_content = 'f111';
	if ( $dashicon ) {
		$dashicons = wpex_get_dashicons_array();
		if ( isset( $dashicons[$dashicon] ) ) {
			$css_content = $dashicons[$dashicon];
		}
	}
	return $css_content;
}

/**
 * Returns correct Google Fonts URL if you want to change it to another CDN
 * such as the one in for China
 *
 * https://chineseseoshifu.com/blog/google-fonts-instable-in-china.html
 *
 * @since 3.3.2
 */
function wpex_get_google_fonts_url() {
	return esc_url( apply_filters( 'wpex_get_google_fonts_url', '//fonts.googleapis.com' ) );
}

/**
 * Returns array of widget areays
 *
 * @since 3.3.3
 */
function wpex_get_widget_areas() {
	global $wp_registered_sidebars;
	$widgets_areas = array();
	if ( ! empty( $wp_registered_sidebars ) ) {
		foreach ( $wp_registered_sidebars as $widget_area ) {
			$name = isset ( $widget_area['name'] ) ? $widget_area['name'] : '';
			$id = isset ( $widget_area['id'] ) ? $widget_area['id'] : '';
			if ( $name && $id ) {
				$widgets_areas[$id] = $name;
			}
		}
	}
	return $widgets_areas;
}

/*-------------------------------------------------------------------------------*/
/* [ Sanitize Data ]
/*-------------------------------------------------------------------------------*/

/**
 * Sanitize data via the TotalTheme\SanitizeData class
 *
 * @since 2.0.0
 */
function wpex_sanitize_data( $data = '', $type = '' ) {
	if ( $data && $type ) {
		$class = new TotalTheme\SanitizeData();
		return $class->parse_data( $data, $type );
	}
}

/*-------------------------------------------------------------------------------*/
/* [ Parse HTML ]
/*-------------------------------------------------------------------------------*/

/**
 * Takes an array of attributes and outputs them for HTML
 *
 * @since 3.4.0
 */
function wpex_parse_html( $tag = '', $attrs = array(), $content = '' ) {
	$attrs = wpex_parse_attrs( $attrs );
	$output = '<' . $tag . ' ' . $attrs . '>';
	if ( $content ) {
		$output .= $content;
	}
	$output .= '</' . $tag . '>';
	return $output;
}

/**
 * Parses an html data attribute
 *
 * @since 3.4.0
 */
function wpex_parse_attrs( $attrs = null ) {

	if ( ! $attrs || ! is_array( $attrs ) ) {
		return $attrs;
	}

	// Define output
	$output = '';

	// Loop through attributes
	foreach ( $attrs as $key => $val ) {

		// If the attribute is an array convert to string
		if ( is_array( $val ) ) {
			$val = array_filter( $val, 'trim' ); // Remove extra space
			$val = implode( ' ', $val );
		}

		// Val required => no need for empty attributes unless it's a data attribute
		if ( ! $val && strpos( $key, 'data' ) === false ) {
			continue;
		}

		// Sanitize rel attribute
		if ( 'rel' == $key && 'nofollow' != $val ) {
			continue;
		}

		// Sanitize ID
		if ( 'id' == $key ) {
			$val = trim ( str_replace( '#', '', $val ) );
			$val = str_replace( ' ', '', $val );
		}

		// Sanitize targets
		if ( 'target' == $key ) {
			$val = ( strpos( $val, 'blank' ) !== false ) ? '_blank' : '';
		}

		// Add attribute to output
		if ( $val ) {
			if ( in_array( $key, array( 'download' ) ) ) {
				$output .= ' ' . trim( $val ); // Used for example on total button download attribute
			} else {
				$needle = ( 'data' == $key ) ? 'data-' : $key . '=';
				if ( strpos( $val, $needle ) !== false ) {
					$output .= ' ' . trim( $val ); // Already has tag added
				} else {
					if ( 'data-wpex-hover' == $key ) {
						$output .= " " . $key . "='" . $val . "'";
					} else {
						$output .= ' ' . $key . '="' . $val . '"';
					}
				}
			}
		}

		// Only add empty data attributes (such as data-no-retina)
		elseif ( strpos( $key, 'data-' ) !== false ) {
			$output .= ' ' . $key;
		}

	}

	// Return output
	return trim( $output );

}

/**
 * Output inline style tag based on attributes
 *
 * @since 4.6.5
 */
function wpex_parse_inline_style( $atts = array(), $add_style = true ) {
	if ( ! empty( $atts ) && is_array( $atts ) ) {
		$inline_style = new TotalTheme\ParseInlineStyle( $atts, $add_style );
		return $inline_style->return_style();
	}
}

/*-------------------------------------------------------------------------------*/
/* [ Grids/Entries ]
/*-------------------------------------------------------------------------------*/

/**
 * Returns correct classes for archive grid
 *
 * @since 3.6.0
 */
function wpex_get_archive_grid_class() {

	// Define class array
	$class = array( 'archive-grid', 'entries', 'clr' );

	// Add row class for multi column grid
	$class[] = 'wpex-row';

	// Apply filters
	$class = apply_filters( 'wpex_get_archive_grid_class', $class );

	// Return classes as a string
	return implode( ' ', $class );

}

/**
 * Returns correct grid columns for custom types
 *
 * @since 3.6.0
 */
function wpex_get_grid_entry_columns() {
	return apply_filters( 'wpex_get_grid_entry_columns', 1 );
}

/**
 * Returns correct classes for archive grid entries
 *
 * @since 3.6.0
 */
function wpex_get_archive_grid_entry_class() {

	// Define class array
	$class = array( 'cpt-entry', 'col', 'clr' );

	// Add columns class
	$columns = wpex_get_grid_entry_columns();
	if ( '1' != $columns ) {
		$class[] = wpex_grid_class( $columns );
		global $wpex_count;
		if ( $wpex_count ) {
			$class[] = 'col-'. $wpex_count;
		}
	} else {
		$class[] = 'span_1_of_1';
	}

	// Apply filters and return
	return apply_filters( 'wpex_get_archive_grid_entry_class', $class );

}

/*-------------------------------------------------------------------------------*/
/* [ Content Blocks ( Entrys & Posts ) ]
/*-------------------------------------------------------------------------------*/

/**
 * Returns array of blocks for the entry post type layout
 *
 * @since 3.2.0
 */
function wpex_entry_blocks() {
	$type = get_post_type();
	return apply_filters( 'wpex_'. $type .'_entry_blocks', array(
		'media'    => 'media',
		'title'    => 'title',
		'meta'     => 'meta',
		'content'  => 'content',
		'readmore' => 'readmore',
	), $type );
}

/**
 * Returns array of blocks for the single post type layout
 *
 * @since 3.2.0
 * @todo Update so all post types pass through the wpex_single_blocks filter. And update files so all post types use the wpex_single_blocks function. 
 */
function wpex_single_blocks( $post_type = '' ) {

	// Define empty blocks array
	$blocks = array();

	// Get type
	$type = $post_type ? $post_type : get_post_type();

	// Get correct blocks by post type
	if ( 'page' == $type ) {
		$blocks = wpex_get_mod( 'page_composer', array( 'content' ) );
	} elseif( 'post' == $type ) {
		return wpex_blog_single_layout_blocks();
	} elseif ( 'portfolio' == $type ) {
		if ( function_exists( 'wpex_portfolio_single_blocks' ) ) {
			return wpex_portfolio_single_blocks();
		}
	} elseif ( 'staff' == $type ) {
		if ( function_exists( 'wpex_staff_single_blocks' ) ) {
			return wpex_staff_single_blocks();
		}
	} elseif ( 'testimonials' == $type ) {
		if ( function_exists( 'wpex_testimonials_single_blocks' ) ) {
			return wpex_testimonials_single_blocks();
		}
	} else {
		$blocks = array( 'media', 'title', 'meta', 'post-series', 'content', 'page-links', 'share', 'comments' );
	}

	// Convert to array if not already (for customizer settings)
	if ( ! is_array( $blocks ) ) {
		$blocks = explode( ',', $blocks );
	}

	// Set keys equal to values for easier filter removal
	// MUST RUN BEFORE FILTERS !!!
	$blocks = $blocks ? array_combine( $blocks, $blocks ) : array();

	// Type specific filter //@todo remove extra filter and update snippets/docs
	// This one is deprecated used filter defined below
	$blocks = apply_filters( 'wpex_' . $type . '_single_blocks', $blocks, $type );

	// Needed because of plugins using archives such as bbPress - @todo deprecate previouos filter?
	$blocks = apply_filters( 'wpex_single_blocks', $blocks, $type );

	// Sanitize & return blocks
	return $blocks;

}

/**
 * Returns array of blocks for the entry meta
 *
 * @since 3.6.0
 */
function wpex_meta_blocks() {
	return apply_filters( 'wpex_meta_blocks', array( 'date', 'author', 'categories', 'comments' ), get_post_type() );
}

/*-------------------------------------------------------------------------------*/
/* [ Taxonomy & Terms ]
/*-------------------------------------------------------------------------------*/

/**
 * Get term thumbnail
 *
 * @since 2.1.0
 */
function wpex_get_term_thumbnail_id( $term_id = '' ) {

	// Default return
	$thumbnail_id = '';

	// Get term id if not defined and is tax
	$term_id = $term_id ? $term_id : get_queried_object()->term_id;

	// Get thumbnail ID from term
	if ( $term_id ) {

		// Woo Check first
		if ( function_exists( 'get_woocommerce_term_meta' )
			&& $woo_id = get_woocommerce_term_meta( $term_id, 'thumbnail_id', true )
		) {

			$thumbnail_id = $woo_id;

		} else {
				
			// Get data
			$term_data = get_option( 'wpex_term_data' );
			$term_data = ! empty( $term_data[ $term_id ] ) ? $term_data[ $term_id ] : '';
			
			// Return thumbnail ID
			if ( $term_data && ! empty( $term_data['thumbnail'] ) ) {
				return $term_data['thumbnail'];
			}

		}

	}

	// Apply filters and return
	return apply_filters( 'wpex_get_term_thumbnail_id', $thumbnail_id );

}

/**
 * Returns 1st term ID
 *
 * @since 3.2.0
 */
function wpex_get_first_term_id( $post_id = '', $taxonomy = 'category', $terms = '' ) {
	if ( ! taxonomy_exists( $taxonomy ) ) {
		return;
	}
	$post_id = $post_id ? $post_id : get_the_ID();
	if ( class_exists( 'WPSEO_Primary_Term' ) ) {
		$primary_term = new WPSEO_Primary_Term( $taxonomy, $post_id );
		$primary_term = $primary_term->get_primary_term();
		if ( $primary_term ) {
			$get_primary_term = get_term( $primary_term, $taxonomy );
			$terms = array( $get_primary_term );
		}
	}
	$terms = $terms ? $terms : wp_get_post_terms( $post_id, $taxonomy );
	if ( ! is_wp_error( $terms ) && ! empty( $terms[0] ) ) {
		return $terms[0]->term_id;
	}
}

/**
 * Returns 1st term name
 *
 * @since 3.2.0
 */
function wpex_get_first_term_name( $post_id = '', $taxonomy = 'category', $terms = '' ) {
	if ( ! taxonomy_exists( $taxonomy ) ) {
		return;
	}
	$post_id = $post_id ? $post_id : get_the_ID();
	if ( class_exists( 'WPSEO_Primary_Term' ) ) {
		$primary_term = new WPSEO_Primary_Term( $taxonomy, $post_id );
		$primary_term = $primary_term->get_primary_term();
		if ( $primary_term ) {
			$get_primary_term = get_term( $primary_term, $taxonomy );
			$terms = array( $get_primary_term );
		}
	}
	$terms = $terms ? $terms : wp_get_post_terms( $post_id, $taxonomy );
	if ( ! is_wp_error( $terms ) && ! empty( $terms[0] ) ) {
		return $terms[0]->name;
	}
}

/**
 * Returns 1st taxonomy of any taxonomy with a link
 *
 * @since 3.2.0
 */
function wpex_get_first_term_link( $post_id = '', $taxonomy = 'category', $terms = '' ) {
	if ( ! taxonomy_exists( $taxonomy ) ) {
		return;
	}
	$post_id = $post_id ? $post_id : get_the_ID();
	if ( class_exists( 'WPSEO_Primary_Term' ) ) {
		$primary_term = new WPSEO_Primary_Term( $taxonomy, $post_id );
		$primary_term = $primary_term->get_primary_term();
		if ( $primary_term ) {
			$get_primary_term = get_term( $primary_term, $taxonomy );
			$terms = array( $get_primary_term );
		}
	}
	$terms = $terms ? $terms : wp_get_post_terms( $post_id, $taxonomy );
	if ( ! is_wp_error( $terms ) && ! empty( $terms[0] ) ) {
		$attrs = array(
			'href'  => esc_url( get_term_link( $terms[0], $taxonomy ) ),
			'class' => 'term-' . $terms[0]->term_id,
			'title' => esc_attr( $terms[0]->name ),
		);
		return wpex_parse_html( 'a', $attrs, esc_html( $terms[0]->name ) );
	}
}

/**
 * Echos 1st taxonomy of any taxonomy with a link
 *
 * @since 2.0.0
 */
function wpex_first_term_link( $post_id = '', $taxonomy = 'category' ) {
	echo wpex_get_first_term_link( $post_id, $taxonomy );
}

/**
 * Returns a list of terms for specific taxonomy
 *
 * @since 2.1.3
 */
function wpex_get_list_post_terms( $taxonomy = 'category', $show_links = true ) {
	return wpex_list_post_terms( $taxonomy, $show_links, false );
}

/**
 * List terms for specific taxonomy
 *
 * @since 1.6.3
 */
function wpex_list_post_terms( $taxonomy = 'category', $show_links = true, $echo = true ) {

	// Make sure taxonomy exists
	if ( ! taxonomy_exists( $taxonomy ) ) {
		return;
	}

	// Get terms
	$list_terms = array();
	$terms      = wp_get_post_terms( get_the_ID(), $taxonomy );

	// Return if no terms are found
	if ( ! $terms ) {
		return;
	}

	// Loop through terms
	foreach ( $terms as $term ) {

		if ( $show_links ) {

			$attrs = array(
				'href'  => esc_url( get_term_link( $term->term_id, $taxonomy ) ),
				'title' => esc_attr( $term->name ),
				'class' => 'term-' . $term->term_id,
			);

			$list_terms[] = wpex_parse_html( 'a', $attrs, esc_html( $term->name ) );

		} else {

			$attrs = array(
				'class' => 'term-' . $term->term_id,
			);

			$list_terms[] = wpex_parse_html( 'span', $attrs, esc_html( $term->name ) );

		}
	}

	// Turn into comma seperated string
	if ( $list_terms && is_array( $list_terms ) ) {
		$list_terms = implode( ', ', $list_terms );
	} else {
		return;
	}

	// Apply filters (can be used to change the comas to something else)
	$list_terms = apply_filters( 'wpex_list_post_terms', $list_terms, $taxonomy );

	// Echo terms
	if ( $echo ) {
		echo $list_terms;
	} else {
		return $list_terms;
	}

}

/**
 * Returns the "category" taxonomy for a given post type
 *
 * @since 2.0.0
 */
function wpex_get_post_type_cat_tax( $post_type = '' ) {

	// Get the post type
	$post_type = $post_type ? $post_type : get_post_type();

	// Return taxonomy
	if ( 'post' == $post_type ) {
		$tax = 'category';
	} elseif ( 'portfolio' == $post_type ) {
		$tax = 'portfolio_category';
	} elseif ( 'staff' == $post_type ) {
		$tax = 'staff_category';
	} elseif ( 'testimonials' == $post_type ) {
		$tax = 'testimonials_category';
	} elseif ( 'product' == $post_type ) {
		$tax = 'product_cat';
	} elseif ( 'tribe_events' == $post_type ) {
		$tax = 'tribe_events_cat';
	} elseif ( 'download' == $post_type ) {
		$tax = 'download_category';
	} else {
		$tax = false;
	}

	// Apply filters & return
	return apply_filters( 'wpex_get_post_type_cat_tax', $tax );

}

/**
 * Retrieve all term data
 *
 * @since 2.1.0
 */
function wpex_get_term_data() {
	return get_option( 'wpex_term_data' );
}

/*-------------------------------------------------------------------------------*/
/* [ Sliders ]
/*-------------------------------------------------------------------------------*/

/**
 * Returns correct slider settings
 *
 * @since 4.4.1
 */
function wpex_get_post_slider_settings( $args = array() ) {
	$defaults = array(
		'filter_tag'      => 'wpex_slider_data',
		'fade'            => ( 'fade' == wpex_get_mod( 'post_slider_animation', 'slide' ) ) ? 'true' : 'false',
		'auto-play'       => ( wpex_get_mod( 'post_slider_autoplay', false ) ) ? 'true' : 'false',
		'buttons'         => ( wpex_get_mod( 'post_slider_dots', false ) ) ? 'true' : 'false',
		'loop'            => ( wpex_get_mod( 'post_slider_loop', true ) ) ? 'true' : 'false',
		'arrows'          => ( wpex_get_mod( 'post_slider_arrows', true ) ) ? 'true' : 'false',
		'fade-arrows'     => ( wpex_get_mod( 'post_slider_arrows_on_hover', true ) ) ? 'true' : 'false',
		'animation-speed' => intval( wpex_get_mod( 'post_slider_animation_speed', 600 ) ),
	);
	if ( wpex_get_mod( 'post_slider_thumbnails', apply_filters( 'wpex_post_gallery_slider_has_thumbnails', true ) ) ) {
		$defaults['thumbnails']        = 'true';
		$defaults['thumbnails-height'] = intval( wpex_get_mod( 'post_slider_thumbnail_height', '60' ) );
		$defaults['thumbnails-width']  = intval( wpex_get_mod( 'post_slider_thumbnail_width', '60' ) );
	}
	$args = wp_parse_args( $args, $defaults );
	return apply_filters( $args['filter_tag'], $args );
}

/**
 * Returns data attributes for post sliders
 *
 * @since 4.3
 * @todo rename to wpex_get_post_slider_settings
 */
function wpex_get_slider_data( $args = array() ) {
	$args = wpex_get_post_slider_settings( $args );
	if ( ! $args ) {
		return;
	}
	unset( $args['filter_tag'] ); // not needed for loop
	extract( $args );
	$return = '';
	foreach ( $args as $key => $val ) {
		$return .= ' data-' . esc_attr( $key ) . '="' . esc_attr( $val ) . '"';
	}
	return $return;
}

/**
 * Echos data attributes for post sliders
 *
 * @since 2.0.0
 */
function wpex_slider_data( $args = '' ) {
	echo wpex_get_slider_data( $args );
}

/*-------------------------------------------------------------------------------*/
/* [ Images ]
/*-------------------------------------------------------------------------------*/

/**
 * Echo animation classes for entries
 *
 * @since 1.1.6
 */
function wpex_entry_image_animation_classes() {
	if ( $classes = wpex_get_entry_image_animation_classes() ) {
		echo ' ' . $classes;
	}
}

/**
 * Returns animation classes for entries
 *
 * @since 1.1.6
 */
function wpex_get_entry_image_animation_classes() {

	// Empty by default
	$classes = '';

	// Get post type
	$type = get_post_type( get_the_ID() );
	$type = ( 'post' == $type ) ? 'blog' : $type;

	// Get blog classes
	if ( $animation = wpex_get_mod( $type . '_entry_image_hover_animation' ) ) {
		$classes = 'wpex-image-hover '. $animation;
	}

	// Apply filters
	return apply_filters( 'wpex_entry_image_animation_classes', $classes );

}

/**
 * Returns attachment data
 *
 * @since 2.0.0
 */
function wpex_get_attachment_data( $attachment = '', $return = 'array' ) {

	// Initial checks
	if ( ! $attachment || 'none' == $return ) {
		return;
	}

	// Sanitize return value
	$return = $return ? $return : 'array';

	// Return data
	if ( 'array' == $return ) {
		return array(
			'url'         => get_post_meta( $attachment, '_wp_attachment_url', true ),
			'src'         => wp_get_attachment_url( $attachment ),
			'alt'         => get_post_meta( $attachment, '_wp_attachment_image_alt', true ),
			'title'       => get_the_title( $attachment ),
			'caption'     => get_post_field( 'post_excerpt', $attachment ),
			'description' => get_post_field( 'post_content', $attachment ),
			'video'       => esc_url( get_post_meta( $attachment, '_video_url', true ) ),
		);
	} elseif ( 'url' == $return ) {
		return get_post_meta( $attachment, '_wp_attachment_url', true );
	} elseif ( 'src' == $return ) {
		return get_post_meta( $attachment, '_wp_attachment_url', true );
	} elseif ( 'alt' == $return ) {
		return get_post_meta( $attachment, '_wp_attachment_image_alt', true );
	} elseif ( 'title' == $return ) {
		return get_the_title( $attachment );
	} elseif ( 'caption' == $return ) {
		return get_post_field( 'post_excerpt', $attachment );
	} elseif ( 'description' == $return ) {
		return get_post_field( 'post_content', $attachment );
	} elseif ( 'video' == $return ) {
		return esc_url( get_post_meta( $attachment, '_video_url', true ) );
	}

	// Set alt to title if alt not defined => Removed in v4.0
	//$array['alt'] = $array['alt'] ? $array['alt'] : $array['title'];

}

/**
 * Checks if a featured image has a caption
 *
 * @since 2.0.0
 */
function wpex_featured_image_caption( $post_id = '' ) {
	$post_id = $post_id ? $post_id : get_the_ID();
	return apply_filters( 'wpex_featured_image_caption', get_post_field( 'post_excerpt', get_post_thumbnail_id( $post_id ) ), $post_id );
}

/**
 * Echo lightbox image URL
 *
 * @since 2.0.0
 */
function wpex_lightbox_image( $attachment = '' ) {
	echo wpex_get_lightbox_image( $attachment );
}

/**
 * Returns lightbox image URL.
 *
 *  @since 2.0.0
 */
function wpex_get_lightbox_image( $attachment = '' ) {

	// Get attachment if empty (in standard WP loop)
	if ( ! $attachment ) {
		if ( 'attachment' == get_post_type() ) {
			$attachment = get_the_ID();
		} else {
			if ( $meta = get_post_meta( get_the_ID(), 'wpex_lightbox_thumbnail', true ) ) {
				$attachment = $meta;
			} else {
				$attachment = get_post_thumbnail_id();
			}
		}
	}

	// If the attachment is an ID lets get the URL
	if ( is_numeric( $attachment ) ) {
		$image = '';
	} elseif ( is_array( $attachment ) ) {
		return $attachment[0];
	} else {
		return $attachment;
	}

	if ( $filtered_image = apply_filters( 'wpex_get_lightbox_image', null, $attachment ) ) {
		return $filtered_image;
	}

	// Sanitize data
	$image = wpex_get_post_thumbnail_url( array(
		'attachment' => $attachment,
		'image'      => $image,
		'size'       => apply_filters( 'wpex_get_lightbox_image_size', 'lightbox' ),
		'retina'     => false, // no need to create retina image for this
	) );

	// Return escaped image
	return esc_url( $image );
}

/**
 * Placeholder Image
 *
 * @since 2.1.0
 */
function wpex_placeholder_img_src() {
	return apply_filters( 'wpex_placeholder_img_src', wpex_asset_url( '/images/placeholder.png' ) );
}

/**
 * Blank Image
 *
 * @since 2.1.0
 */
function wpex_blank_img_src() {
	return esc_url( WPEX_THEME_URI .'/images/slider-pro/blank.png' );
}

/**
 * Returns correct image hover classnames
 *
 * @since 2.0.0
 */
function wpex_image_hover_classes( $style = '' ) {
	if ( ! $style ) {
		return;
	}
	$classes   = array( 'wpex-image-hover' );
	$classes[] = $style;
	return implode( ' ', $classes );
}

/**
 * Returns correct image rendering class
 *
 * @since 2.0.0
 */
function wpex_image_rendering_class( $rendering ) {
	return 'image-rendering-'. $rendering;
}

/**
 * Returns correct image filter class
 *
 * @since 2.0.0
 */
function wpex_image_filter_class( $filter ) {
	if ( ! $filter || 'none' == $filter ) {
		return;
	}
	return 'image-filter-'. $filter;
}

/*-------------------------------------------------------------------------------*/
/* [ Buttons ]
/*-------------------------------------------------------------------------------*/

/**
 * Returns Font Awesome icons corresponding to post formats
 *
 * @since 3.6.0
 */
function wpex_get_post_format_icon( $format = '' ) {

	$icon = 'fa fa-file-text-o';

	// Get post format
	$format = $format ? $format : get_post_format();

	// Video
	if ( 'video' == $format ) {
		$icon = 'fa fa-video-camera';
	} elseif ( 'audio' == $format ) {
		$icon = 'fa fa-music';
	} elseif ( 'gallery' == $format ) {
		$icon = 'fa fa-file-photo-o';
	} elseif ( 'quote' == $format ) {
		$icon = 'fa fa-quote-left';
	}

	// Apply filters for child theme editing and return
	return esc_attr( apply_filters( 'wpex_post_format_icon', $icon ) );
}

/**
 * Echos Font Awesome icons corresponding to post formats
 *
 * @since 1.4.0
 */
function wpex_post_format_icon( $format ) {
	echo wpex_get_post_format_icon( $format );
}

/*-------------------------------------------------------------------------------*/
/* [ Buttons ]
/*-------------------------------------------------------------------------------*/

/**
 * Returns correct social button class
 *
 * @since 3.0.0
 */
function wpex_get_social_button_class( $style = 'default' ) {
	$style = $style ? $style : 'default';

	// Default style
	if ( 'default' == $style ) {
		$style = apply_filters( 'wpex_default_social_button_style', 'flat-rounded' );
	}

	// No style
	elseif ( 'none' == $style ) {
		$style = 'wpex-social-btn-no-style';
	}

	// Minimal
	elseif ( 'minimal' == $style ) {
		$style = 'wpex-social-btn-minimal wpex-social-color-hover';
	} elseif ( 'minimal-rounded' == $style ) {
		$style = 'wpex-social-btn-minimal wpex-social-color-hover wpex-semi-rounded';
	} elseif ( 'minimal-round' == $style ) {
		$style = 'wpex-social-btn-minimal wpex-social-color-hover wpex-round';
	}

	// Flat
	elseif ( 'flat' == $style ) {
		$style = 'wpex-social-btn-flat wpex-social-color-hover wpex-bg-gray';
	} elseif ( 'flat-rounded' == $style ) {
		$style = 'wpex-social-btn-flat wpex-social-color-hover wpex-semi-rounded';
	} elseif ( 'flat-round' == $style ) {
		$style = 'wpex-social-btn-flat wpex-social-color-hover wpex-round';
	}

	// Flat Color
	elseif ( 'flat-color' == $style ) {
		$style = 'wpex-social-btn-flat wpex-social-bg';
	} elseif ( 'flat-color-rounded' == $style ) {
		$style = 'wpex-social-btn-flat wpex-social-bg wpex-semi-rounded';
	} elseif ( 'flat-color-round' == $style ) {
		$style = 'wpex-social-btn-flat wpex-social-bg wpex-round';
	}

	// 3D
	elseif ( '3d' == $style ) {
		$style = 'wpex-social-btn-3d';
	} elseif ( '3d-color' == $style ) {
		$style = 'wpex-social-btn-3d wpex-social-bg';
	}

	// Black
	elseif ( 'black' == $style ) {
		$style = 'wpex-social-btn-black';
	} elseif ( 'black-rounded' == $style ) {
		$style = 'wpex-social-btn-black wpex-semi-rounded';
	} elseif ( 'black-round' == $style ) {
		$style = 'wpex-social-btn-black wpex-round';
	}

	// Black + Color Hover
	elseif ( 'black-ch' == $style ) {
		$style = 'wpex-social-btn-black-ch wpex-social-bg-hover';
	} elseif ( 'black-ch-rounded' == $style ) {
		$style = 'wpex-social-btn-black-ch wpex-social-bg-hover wpex-semi-rounded';
	} elseif ( 'black-ch-round' == $style ) {
		$style = 'wpex-social-btn-black-ch wpex-social-bg-hover wpex-round';
	}

	// Graphical
	elseif ( 'graphical' == $style ) {
		$style = 'wpex-social-bg wpex-social-btn-graphical';
	} elseif ( 'graphical-rounded' == $style ) {
		$style = 'wpex-social-bg wpex-social-btn-graphical wpex-semi-rounded';
	} elseif ( 'graphical-round' == $style ) {
		$style = 'wpex-social-bg wpex-social-btn-graphical wpex-round';
	}

	// Rounded
	elseif ( 'bordered' == $style ) {
		$style = 'wpex-social-btn-bordered wpex-social-border wpex-social-color';
	} elseif ( 'bordered-rounded' == $style ) {
		$style = 'wpex-social-btn-bordered wpex-social-border wpex-semi-rounded wpex-social-color';
	} elseif ( 'bordered-round' == $style ) {
		$style = 'wpex-social-btn-bordered wpex-social-border wpex-round wpex-social-color';
	}

	// Apply filters & return style
	return apply_filters( 'wpex_get_social_button_class', 'wpex-social-btn '. $style );
}

/**
 * Returns correct theme button classes based on args
 *
 * @since 3.2.0
 */
function wpex_get_button_classes( $style = '', $color = '', $size = '', $align = '' ) {

	// Extract if style is an array of arguments
	if ( is_array( $style ) ) {
		extract( $style );
	}

	// Main classes
	if ( 'plain-text' == $style ) {
		$classes = 'theme-txt-link';
	} elseif ( $style ) {
		$classes = 'theme-button '. $style;
	} else {
		$classes = 'theme-button';
	}

	// Color
	if ( $color ) {
		$classes .= ' '. $color;
	}

	// Size
	if ( $size ) {
		$classes .= ' '. $size;
	}

	// Align
	if ( $align ) {
		$classes .= ' align-'. $align;
	}

	// Apply filters and return classes
	return apply_filters( 'wpex_get_theme_button_classes', $classes, $style, $color, $size, $align );
}


/**
 * Returns correct CSS for custom button color based on style
 *
 * @since  4.3.2
 */
function wpex_get_button_custom_color_css( $style = '', $color ='' ) {

	// default style is always technically "flat"
	$style = $style ? $style : 'flat';

	// Background
	if ( in_array( $style, array( 'flat', 'graphical', 'three-d' ) ) ) {
		return 'background:' . $color . ';';
	}

	// Outline
	if ( in_array( $style, array( 'outline', 'minimal-border' ) ) ) {
		return 'border-color:' . $color . ';color:' . $color . ';';
	}

	// Plain text
	if ( 'plain-text' == $style ) {
		return 'color:' . $color . ';';
	}


}

/*-------------------------------------------------------------------------------*/
/* [ Search Functions ]
/*-------------------------------------------------------------------------------*/

/**
 * Defines your default search results page style
 *
 * @since 1.5.4
 */
function wpex_search_results_style() {
	return apply_filters( 'wpex_search_results_style', wpex_get_mod( 'search_style', 'default' ) );
}

/*-------------------------------------------------------------------------------*/
/* [ Lightbox ]
/*-------------------------------------------------------------------------------*/

/**
 * Returns array of ilightbox Skins
 *
 * @since 2.0.0
 */
function wpex_ilightbox_skins() {
	return TotalTheme\iLightbox::skins();
}

/**
 * Returns lightbox skin
 *
 * @since 1.3.3
 */
function wpex_ilightbox_skin() {
	return TotalTheme\iLightbox::active_skin();
}

/**
 * Enqueues lightbox stylesheet
 *
 * @since 1.3.3
 */
function wpex_enqueue_ilightbox_skin( $skin = null ) {
	return TotalTheme\iLightbox::enqueue_style( $skin );
}

// Deprecated functions
function wpex_ilightbox_stylesheet( $skin = null ) {
	return; // This function is no longer needed and shouldn't be used
}

/*-------------------------------------------------------------------------------*/
/* [ Post Galleries ]
/*-------------------------------------------------------------------------------*/

/**
 * Check if the post has a gallery
 *
 * @since 3.0.0
 */
function wpex_post_has_gallery( $post_id = '' ) {
	$post_id = $post_id ? $post_id : wpex_get_current_post_id();
	if ( get_post_meta( $post_id, '_easy_image_gallery', true ) ) {
		return true;
	}
}

/**
 * Retrieve attachment IDs
 *
 * @since 1.0.0
 */
function wpex_get_gallery_ids( $post_id = '' ) {
	$attachment_ids = '';
	$post_id = $post_id ? $post_id : wpex_get_current_post_id();
	if ( class_exists( 'WC_product' ) && 'product' == get_post_type( $post_id ) ) {
		$product = new WC_product( $post_id );
		if ( $product && method_exists( $product, 'get_gallery_image_ids' ) ) {
    		$attachment_ids = $product->get_gallery_image_ids();
    	}
	}
	$attachment_ids = $attachment_ids ? $attachment_ids : get_post_meta( $post_id, '_easy_image_gallery', true );
	if ( $attachment_ids ) {
		$attachment_ids = is_array( $attachment_ids ) ? $attachment_ids : explode( ',', $attachment_ids );
		return array_values( array_filter( $attachment_ids, 'wpex_sanitize_gallery_id' ) );
	}
}

/**
 * Make sure an ID exists and is an attachement
 *
 * @since 1.0.0
 */
function wpex_sanitize_gallery_id( $id = '' ) {
	if ( 'attachment' == get_post_type( $id ) ) {
		return $id;
	}
}

/**
 * Get array of gallery image urls
 *
 * @since 3.5.0
 */
function wpex_get_gallery_images( $post_id = '', $size = 'full' ) {
	$post_id = $post_id ? $post_id : wpex_get_current_post_id();
	$ids     = wpex_get_gallery_ids( $post_id );
	if ( ! $ids ) {
		return;
	}
	$images = array();
	foreach ( $ids as $id ) {
		$img_url = wpex_image_resize( array(
			'attachment' => $id,
			'size'       => $size,
			'return'     => 'url',
		) );
		if ( $img_url ) {
			$images[] = $img_url;
		}
	}
	return $images;
}

/**
 * Return gallery count
 *
 * @since 1.0.0
 */
function wpex_gallery_count( $post_id = '' ) {
	$ids = wpex_get_gallery_ids( $post_id );
	return count( $ids );
}

/**
 * Check if lightbox is enabled
 *
 * @since 1.0.0
 */
function wpex_gallery_is_lightbox_enabled( $post_id = '' ) {
	$post_id = $post_id ? $post_id : wpex_get_current_post_id();
	return ( 'on' == get_post_meta( $post_id, '_easy_image_gallery_link_images', true ) ) ?  true : false;
}

/*-------------------------------------------------------------------------------*/
/* [ PHP Helpers ]
/*-------------------------------------------------------------------------------*/

/**
 * Inserts a new key/value before the key in the array.
 *
 * @param $key  The key to insert before.
 * @param $array  An array to insert in to.
 * @param $new_key  The key/array to insert.
 * @param $new_value  An value to insert.
 * @return array
 */
function wpex_array_insert_before( $key, array $array, $new_key, $new_value = null ) {
	if ( array_key_exists( $key, $array ) ) {
		$new = array();
		foreach( $array as $k => $value ) {
			if ( $k === $key ) {
				if ( is_array( $new_key ) && count( $new_key ) > 0) {
					$new = array_merge( $new, $new_key );
				} else {
					$new[$new_key] = $new_value;
				}
			}
			$new[$k] = $value;
		}
		return $new;
	}
	return false;
}

/**
 * Inserts a new key/value after the key in the array.
 *
 * @param $key  The key to insert after.
 * @param $array  An array to insert in to.
 * @param $new_key  The key/array to insert.
 * @param $new_value  An value to insert.
 *
 * @return array
 */
 
function wpex_array_insert_after( $key, array  $array, $new_key, $new_value = null ) {
	if ( array_key_exists( $key, $array ) ) {
		$new = array();
		foreach( $array as $k => $value ) {
			$new[$k] = $value;
			if ( $k === $key ) {
				if (is_array( $new_key ) && count( $new_key ) > 0) {
					$new = array_merge( $new, $new_key );
				} else {
					$new[$new_key] = $new_value;
				}
			}
		}
		return $new;
	}
	return false;
}

/*-------------------------------------------------------------------------------*/
/* [ Other ]
/*-------------------------------------------------------------------------------*/

/**
 * Check user access
 *
 * @since 4.0
 */
function wpex_user_can_access( $check, $custom_callback = '' ) {

	// Logged in acccess
	if ( 'logged_in' == $check ) {
		
		return is_user_logged_in();

	}

	// Logged out access
	elseif ( 'logged_out' == $check ) {
		
		return is_user_logged_in() ? false : true;

	}

	// Custom Access
	elseif ( 'custom' == $check ) {

		if ( ! is_callable( $custom_callback ) ) {
			return true;
		}
		
		return call_user_func( $custom_callback );

	}

	// Return true if all else fails
	return true;

}

/**
 * Display user social links
 *
 * @since 4.0
 */
function wpex_get_user_social_links( $user_id = '', $display = 'icons', $attr = '' ) {

	if ( ! $user_id ) {
		return;
	}

	$output = '';

	$settings = wpex_get_user_social_profile_settings_array();

	foreach ( $settings as $id => $val ) {

		if ( $url = get_the_author_meta( 'wpex_' . $id, $user_id ) ) {

			$link_content = '';

			$label = isset( $val['label'] ) ? $val['label'] : $val; // Fallback for pre 4.5

			$default_attr = array(
				'href'  => esc_url( $url ),
				'class' => '',
			);

			$attrs = apply_filters( 'wpex_get_user_social_link_attrs', wp_parse_args( $attr, $default_attr ), $id );

			if ( 'icons' == $display ) {

				if ( isset( $val['icon'] ) ) {

					$link_content = '<span class="'. esc_attr( $val['icon'] ) .'" aria-hidden="true"></span>';

					if ( $label ) {
						$link_content .= '<span class="screen-reader-text">' . esc_html( $label ) . '</span>';
					}

				}

				$attrs['class'] .= ' wpex-' . $id;

			} elseif ( $label ) {

				$link_content = strip_tags( $label );

			}

			if ( $link_content ) {

				$output .= wpex_parse_html( 'a', $attrs, $link_content );

			}

		}

	}

	return apply_filters( 'wpex_get_user_social_links', $output );

}

/**
 * Get star rating
 *
 * @since 4.0
 */
function wpex_get_star_rating( $rating = '', $post_id = '' ) {

	// Post id
	$post_id = $post_id ? $post_id : get_the_ID();

	// Define rating
	$rating = $rating ? $rating : get_post_meta( $post_id, 'wpex_post_rating', true );

	// Return if no rating
	if ( ! $rating ) {
		return false;
	}

	// Sanitize
	else {
		$rating = abs( $rating );
	}

	$output = '';

	// Star fonts
	$full_star  = '<span class="fa fa-star" aria-hidden="true"></span>';
	$half_star  = '<span class="fa fa-star-half-full" aria-hidden="true"></span>';
	$empty_star = '<span class="fa fa-star-o" aria-hidden="true"></span>';

	// Integers
	if ( ( is_numeric( $rating ) && ( intval( $rating ) == floatval( $rating ) ) ) ) {
		$output = str_repeat( $full_star, $rating );
		if ( $rating < 5 ) {
			$output .= str_repeat( $empty_star, 5 - $rating );
		}

	// Fractions
	} else {
		$rating = intval( $rating );
		$output = str_repeat( $full_star, $rating );
		$output .= $half_star;
		if ( $rating < 5 ) {
			$output .= str_repeat( $empty_star, 4 - $rating );
		}
	}

	// Add screen-reader text
	$output .= '<span class="screen-reader-text">' . esc_html__( 'Rating', 'total' ) . ': ' . esc_html( $rating ) . '</span>';

	// Return output
	return apply_filters( 'wpex_get_star_rating', $output );

}

/**
 * Returns string version of WP core get_post_class
 *
 * @since 3.5.0
 */
function wpex_get_post_class( $class = '', $post_id = null ) {
	return 'class="' . implode( ' ', get_post_class( $class, $post_id ) ) . '"';
}

/**
 * Check if the header supports aside content
 *
 * @since 3.2.0
 */
function wpex_disable_google_services() {
	return apply_filters( 'wpex_disable_google_services', wpex_get_mod( 'disable_gs', false ) );
}

/**
 * Minify CSS
 *
 * @since 1.6.3
 */
function wpex_minify_css( $css = '' ) {

	// Return if no CSS
	if ( ! $css ) return;

	// Normalize whitespace
	$css = preg_replace( '/\s+/', ' ', $css );

	// Remove ; before }
	$css = preg_replace( '/;(?=\s*})/', '', $css );

	// Remove space after , : ; { } */ >
	$css = preg_replace( '/(,|:|;|\{|}|\*\/|>) /', '$1', $css );

	// Remove space before , ; { }
	$css = preg_replace( '/ (,|;|\{|})/', '$1', $css );

	// Strips leading 0 on decimal values (converts 0.5px into .5px)
	$css = preg_replace( '/(:| )0\.([0-9]+)(%|em|ex|px|in|cm|mm|pt|pc)/i', '${1}.${2}${3}', $css );

	// Strips units if value is 0 (converts 0px to 0)
	$css = preg_replace( '/(:| )(\.?)0(%|em|ex|px|in|cm|mm|pt|pc)/i', '${1}0', $css );

	// Trim
	$css = trim( $css );

	// Return minified CSS
	return $css;

}

/**
 * Allow to remove method for an hook when, it's a class method used and class doesn't have global for instanciation
 *
 * @since 3.4.0
 */
function wpex_remove_class_filter( $hook_name = '', $class_name ='', $method_name = '', $priority = 0 ) {
	global $wp_filter;

	// Make sure class exists
	if ( ! class_exists( $class_name ) ) {
		return false;
	}

	// Take only filters on right hook name and priority
	if ( ! isset($wp_filter[$hook_name][$priority] ) || ! is_array( $wp_filter[$hook_name][$priority] ) ) {
		return false;
	}

	// Loop on filters registered
	foreach( (array) $wp_filter[$hook_name][$priority] as $unique_id => $filter_array ) {

		// Test if filter is an array ! (always for class/method)
		// @todo consider using has_action instead
		// @link https://make.wordpress.org/core/2016/09/08/wp_hook-next-generation-actions-and-filters/
		if ( isset( $filter_array['function'] ) && is_array( $filter_array['function'] ) ) {

			// Test if object is a class, class and method is equal to param !
			if ( is_object( $filter_array['function'][0] )
				&& get_class( $filter_array['function'][0] )
				&& get_class( $filter_array['function'][0] ) == $class_name
				&& $filter_array['function'][1] == $method_name
			) {
				if ( isset( $wp_filter[$hook_name] ) ) {
					// WP 4.7
					if ( is_object( $wp_filter[$hook_name] ) ) {
						unset( $wp_filter[$hook_name]->callbacks[$priority][$unique_id] );
					}
					// WP 4.6
					else {
						unset( $wp_filter[$hook_name][$priority][$unique_id] );
					}
				}
			}

		}

	}
	return false;
}