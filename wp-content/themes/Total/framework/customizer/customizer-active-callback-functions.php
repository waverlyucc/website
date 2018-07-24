<?php
/**
 * Active callback functions for the customizer
 *
 * @package Total WordPress Theme
 * @subpackage Customizer
 * @version 4.4
 */

/*-------------------------------------------------------------------------------*/
/* [ Table of contents ]
/*-------------------------------------------------------------------------------*

	# Core
	# Page Header
	# Background
	# Togglebar
	# Topbar
	# Header
	# Logo
	# Menu
	# Blog
	# Portfolio
	# Staff
	# Testimonials

/*-------------------------------------------------------------------------------*/
/* [ Core ]
/*-------------------------------------------------------------------------------*/
function wpex_cac_responsive() {
	return get_theme_mod( 'responsive', true ) ? true : false;
}

function wpex_cac_container_layout_supports_max_width() {
	return ( 'full-width' == get_theme_mod( 'main_layout_style', 'full-width' ) && get_theme_mod( 'responsive', true ) ) ? true : false;
}

function wpex_cac_has_breadcrumbs() {
	return ( function_exists( 'yoast_breadcrumb' ) || get_theme_mod( 'breadcrumbs', true ) )  ? true : false;
}

function wpex_cac_has_footer_widgets() {
	return get_theme_mod( 'footer_widgets', true ) ? true : false;
}

function wpex_cac_supports_reveal() {
	return ( wpex_cac_has_footer_widgets() && ! wpex_cac_has_vertical_header() ) ? true : false;
}

function wpex_cac_has_footer_bottom() {
	return get_theme_mod( 'footer_bottom', true ) ? true : false;
}

function wpex_cac_hasnt_custom_social_share() {
	return get_theme_mod( 'social_share_shortcode' ) ? false : true;
}

function wpex_cac_has_theme_social_share_sites() {
	return ( wpex_social_share_sites() && wpex_cac_hasnt_custom_social_share() ) ? true : false;
}

/*-------------------------------------------------------------------------------*/
/* [ Page Header ]
/*-------------------------------------------------------------------------------*/
function wpex_cac_has_page_header() {
	return ( 'hidden' != get_theme_mod( 'page_header_style' ) ) ? true : false;
}

function wpex_cac_hasnt_page_header() {
	return ( 'hidden' == get_theme_mod( 'page_header_style' ) ) ? true : false;
}

function wpex_cac_has_page_header_title_background() {
	return ( get_theme_mod( 'page_header_background_img' ) ) ? true : false;
}

function wpex_cac_page_header_style_is_bg() {
	return ( 'background-image' == get_theme_mod( 'page_header_style' ) ) ? true : false;
}

/*-------------------------------------------------------------------------------*/
/* [ Togglebar ]
/*-------------------------------------------------------------------------------*/
function wpex_cac_has_togglebar_animation() {
	return ( get_theme_mod( 'toggle_bar', true ) && 'overlay' == get_theme_mod( 'toggle_bar_display', 'overlay' ) ) ? true : false;
}

/*-------------------------------------------------------------------------------*/
/* [ Topbar ]
/*-------------------------------------------------------------------------------*/
function wpex_cac_has_topbar() {
	return get_theme_mod( 'top_bar', true ) ? true : false;
}

function wpex_cac_has_topbar_social() {
	if ( get_theme_mod( 'top_bar', true ) && get_theme_mod( 'top_bar_social', true ) ) {
		return true;
	}
}

/*-------------------------------------------------------------------------------*/
/* [ Header ]
/*-------------------------------------------------------------------------------*/
function wpex_cac_header_supports_fixed_header() {
	$header_style = get_theme_mod( 'header_style' );
	$header_style = $header_style ? $header_style : 'one';
	return ( 'one' == $header_style || 'five' == $header_style ) ? true : false;
}


function wpex_cac_has_fixed_header() {
	return ( wpex_cac_header_supports_fixed_header() && 'disabled' != get_theme_mod( 'fixed_header_style' ) ) ? true : false;
}

function wpex_cac_has_fixed_header_logo() {
	return ( wpex_cac_has_fixed_header() && get_theme_mod( 'fixed_header_logo' ) ) ? true : false;
}

function wpex_cac_has_fixed_header_shrink() {
	$style = get_theme_mod( 'fixed_header_style' );
	return ( wpex_cac_header_supports_fixed_header() && ( 'shrink' == $style || 'shrink_animated' == $style ) ) ? true : false;
}

function wpex_supports_fixed_header_logo_retina_height() {
	return ( wpex_cac_has_fixed_header_logo() && ! wpex_cac_has_fixed_header_shrink() ) ? true : false;
}

function wpex_cac_has_vertical_header() {
	return ( in_array( get_theme_mod( 'header_style' ) , array( 'six', 'seven' ) ) ) ? true : false;
}

function wpex_cac_hasnt_vertical_header() {
	return ( 'six' == get_theme_mod( 'header_style' ) ) ? false : true;
}

function wpex_cac_header_supports_fixed_menu() {
	$header_style = get_theme_mod( 'header_style' );
	$header_style = $header_style ? $header_style : 'one';
	if ( 'two' == $header_style
		|| 'three' == $header_style
		|| 'four' == $header_style
	) {
		return true;
	}
	return false;
}

/*-------------------------------------------------------------------------------*/
/* [ Logo ]
/*-------------------------------------------------------------------------------*/
function wpex_cac_has_image_logo() {
	return get_theme_mod( 'custom_logo' ) ? true : false;
}

function wpex_cac_supports_fixed_header_logo() {
	return ( wpex_cac_has_fixed_header() && wpex_cac_has_image_logo() ) ? true : false;
}

function wpex_cac_hasnt_custom_logo() {
	return get_theme_mod( 'custom_logo' ) ? false : true;
}

/*-------------------------------------------------------------------------------*/
/* [ Menu ]
/*-------------------------------------------------------------------------------*/
function wpex_cac_has_mobile_menu() {
	return ( 'disabled' != get_theme_mod( 'mobile_menu_style' ) ) ? true : false;
}

function wpex_cac_is_mobile_toggle_fixed_top() {
	return ( 'fixed_top' == get_theme_mod( 'mobile_menu_toggle_style' ) ) ? true : false;
}

function wpex_cac_is_mobile_fixed_or_navbar() {
	$style = get_theme_mod( 'mobile_menu_toggle_style' );
	return ( 'fixed_top' == $style || 'navbar' == $style ) ? true : false;
}

function wpex_cac_is_mobile_navbar() {
	return ( 'navbar' == get_theme_mod( 'mobile_menu_toggle_style' ) ) ? true : false;
}

function wpex_cac_mobile_menu_is_sidr() {
	return ( 'sidr' == get_theme_mod( 'mobile_menu_style', 'sidr' ) ) ? true : false;
}

function wpex_cac_mobile_menu_is_full_screen() {
	if ( 'full_screen' == get_theme_mod( 'mobile_menu_style', 'sidr' ) ) {
		return true;
	}
	return false;
}

function wpex_cac_mobile_menu_is_toggle() {
	if ( 'toggle' == get_theme_mod( 'mobile_menu_style', 'sidr' ) ) {
		return true;
	}
	return false;
}

function wpex_cac_has_mobile_menu_icons() {
	$style = get_theme_mod( 'mobile_menu_toggle_style', 'icon_buttons' );
	if ( 'disabled' != get_theme_mod( 'mobile_menu_style' )
		&& ( 'icon_buttons' == $style || 'icon_buttons_under_logo' == $style )
	) {
		return true;
	}
	return false;
}

function wpex_cac_has_menu_dropdown_top_border() {
	return get_theme_mod( 'menu_dropdown_top_border', false );
}

function wpex_cac_has_menu_pointer() {
	if ( get_theme_mod( 'menu_dropdown_style' ) ) {
		return false;
	} elseif ( 'one' != get_theme_mod( 'header_style' ) ) {
		return false;
	} elseif ( get_theme_mod( 'menu_flush_dropdowns' ) ) {
		return false;
	}
	return true;
}

/*-------------------------------------------------------------------------------*/
/* [ Blog ]
/*-------------------------------------------------------------------------------*/
function wpex_cac_blog_single_has_page_header() {
	return get_theme_mod( 'post_singular_page_title', true ) ? true : false;
}
function wpex_cac_blog_page_header_custom_text() {
	return ( wpex_cac_blog_single_has_page_header() && 'custom_text' == get_theme_mod( 'blog_single_header', 'custom_text' ) ) ? true : false;
}

function wpex_cac_grid_blog_style() {
	$style = get_theme_mod( 'blog_style' );
	return ( 'grid-entry-style' == $style || 'grid' == $style ) ? true : false;
}

function wpex_cac_blog_style_left_thumb() {
	if ( get_theme_mod( 'post_singular_template', null ) ) {
		return false;
	}
	if ( 'thumbnail-entry-style' == get_theme_mod( 'blog_style' ) ) {
		return true;
	}
	return false;
}

function wpex_cac_blog_supports_equal_heights() {
	if ( get_theme_mod( 'post_singular_template', null ) ) {
		return false;
	}
	if ( wpex_cac_grid_blog_style() && 'masonry' != get_theme_mod( 'blog_grid_style' ) ) {
		return true;
	}
}

function wpex_cac_has_blog_related() {
	if ( get_theme_mod( 'post_singular_template', null ) ) {
		return false;
	}
	if ( strpos( get_theme_mod( 'blog_single_composer', 'related_posts' ), 'related_posts' ) !== false ) {
		return true;
	}
}

function wpex_cac_has_blog_meta() {
	if ( get_theme_mod( 'post_singular_template', null ) ) {
		return false;
	}
	if ( strpos( get_theme_mod( 'blog_single_composer', 'meta' ), 'meta' ) !== false ) {
		return true;
	}
	return false;
}

function wpex_cac_has_blog_entry_meta() {
	if ( get_theme_mod( 'post_singular_template', null ) ) {
		return false;
	}
	if ( strpos( get_theme_mod( 'blog_entry_composer', 'meta' ), 'meta' ) !== false ) {
		return true;
	}
	return false;
}

function wpex_cac_has_blog_single_media() {
	if ( get_theme_mod( 'post_singular_template', null ) ) {
		return false;
	}
	if ( strpos( get_theme_mod( 'blog_single_composer', 'featured_media' ), 'featured_media' ) !== false ) {
		return true;
	}
	return false;
}

function wpex_cac_has_blog_entry_media() {
	if ( strpos( get_theme_mod( 'blog_entry_composer', 'featured_media' ), 'featured_media' ) !== false ) {
		return true;
	}
	return false;
}

function wpex_cac_has_blog_entry_excerpt() {
	if ( strpos( get_theme_mod( 'blog_entry_composer', 'excerpt_content' ), 'excerpt_content' ) !== false ) {
		return true;
	}
	return false;
}

function wpex_cac_has_blog_entry_readmore() {
	if ( strpos( get_theme_mod( 'blog_entry_composer', 'readmore' ), 'readmore' ) !== false ) {
		return true;
	}
	return false;
}
function wpex_cac_post_single_hasnt_custom_template() {
	if ( ! get_theme_mod( 'post_singular_template' ) ) {
		return true;
	}
	return false;
}

/*-------------------------------------------------------------------------------*/
/* [ Pages ]
/*-------------------------------------------------------------------------------*/
function wpex_cac_page_single_hasnt_custom_template() {
	return get_theme_mod( 'page_singular_template' ) ? false : true;
}

/*-------------------------------------------------------------------------------*/
/* [ Portfolio ]
/*-------------------------------------------------------------------------------*/
function wpex_cac_has_portfolio_related() {
	if ( get_theme_mod( 'portfolio_singular_template', null ) ) {
		return false;
	}
	if ( strpos( get_theme_mod( 'portfolio_post_composer', 'related' ), 'related' ) !== false ) {
		return true;
	}
}

function wpex_cac_portfolio_single_hasnt_custom_template() {
	return get_theme_mod( 'portfolio_singular_template' ) ? false : true;
}

/*-------------------------------------------------------------------------------*/
/* [ Staff ]
/*-------------------------------------------------------------------------------*/
function wpex_cac_has_staff_related() {
	if ( get_theme_mod( 'staff_singular_template', null ) ) {
		return false;
	}
	if ( strpos( get_theme_mod( 'staff_post_composer', 'related' ), 'related' ) !== false ) {
		return true;
	}
}
function wpex_cac_staff_single_hasnt_custom_template() {
	return get_theme_mod( 'staff_singular_template', null ) ? false : true;
}

/*-------------------------------------------------------------------------------*/
/* [ Testimonials ]
/*-------------------------------------------------------------------------------*/
function wpex_cac_testimonials_single_hasnt_custom_template() {
	return get_theme_mod( 'testimonials_singular_template', null ) ? false : true;
}