<?php
/**
 * Header Customizer Options
 *
 * @package Total WordPress Theme
 * @subpackage Customizer
 * @version 4.8
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/*-----------------------------------------------------------------------------------*/
/* - Header => General
/*-----------------------------------------------------------------------------------*/
$this->sections['wpex_header_general'] = array(
	'title' => __( 'General', 'total' ),
	'panel' => 'wpex_header',
	'settings' => array(
		array(
			'id' => 'enable_header',
			'default' => true,
			'control' => array(
				'label' => __( 'Enable', 'total' ),
				'type' => 'checkbox',
			),
		),
		array(
			'id' => 'full_width_header',
			'transport' => 'postMessage',
			'control' => array(
				'label' => __( 'Full-Width', 'total' ),
				'type' => 'checkbox',
			),
			'control_display' => array(
				'check' => 'main_layout_style',
				'value' => 'full-width',
			),
		),
		array(
			'id' => 'header_style',
			'default' => 'one',
			'control' => array(
				'label' => __( 'Style', 'total' ),
				'type' => 'select',
				'choices' => wpex_get_header_styles(),
			),
		),
		array(
			'id' => 'vertical_header_style',
			'transport' => 'postMessage',
			'default' => '',
			'control' => array(
				'label' => __( 'Vertical Header Style', 'total' ),
				'type' => 'select',
				'choices' => array(
					'' => __( 'Default', 'total' ),
					'fixed' => __( 'Fixed', 'total' ),
				),
			),
			'control_display' => array(
				'check' => 'header_style',
				'value' => array( 'six', 'vertical-2' ),
			),
		),
		array(
			'id' => 'vertical_header_width',
			'transport' => 'refresh',
			'default' => '',
			'control' => array(
				'label' => __( 'Vertical Header Width (in pixels)', 'total' ),
				'type' => 'text',
				'description' => __( 'Default', 'total' ) . ' : 280px',
			),
			'control_display' => array(
				'check' => 'header_style',
				'value' => array( 'six', 'vertical-2' ),
			),
		),
		array(
			'id' => 'header_top_padding',
			'transport' => 'postMessage',
			'control' => array(
				'type' => 'text',
				'label' => __( 'Top Padding', 'total' ),
				'description' => $pixel_desc,
			),
			'inline_css' => array(
				'target' => array(
					'#site-header #site-header-inner',
					'#site-header.overlay-header #site-header-inner',
				),
				'alter' => 'padding-top',
				'sanitize' => 'px',
			),
		),
		array(
			'id' => 'header_bottom_padding',
			'transport' => 'postMessage',
			'control' => array(
				'type' => 'text',
				'label' => __( 'Bottom Padding', 'total' ),
				'description' => $pixel_desc,
			),
			'inline_css' => array(
				'target' => array(
					'#site-header-inner',
					'#site-header.overlay-header #site-header-inner',
				),
				'alter' => 'padding-bottom',
				'sanitize' => 'px',
			),
		),
		array(
			'id' => 'header_background',
			'transport' => 'postMessage',
			'control' => array(
				'label' => __( 'Background', 'total' ),
				'type' => 'color',
			),
			'inline_css' => array(
				'target' => array(
					'#site-header',
					'#site-header-sticky-wrapper',
					'#site-header-sticky-wrapper.is-sticky #site-header',
					'.footer-has-reveal #site-header',
					'#searchform-header-replace',
					'body.wpex-has-vertical-header #site-header',
				),
				'alter' => 'background-color',
			),
		),
		array(
			'id' => 'header_background_image',
			'control' => array(
				'type' => 'media',
				'mime_type' => 'image',
				'label' => __( 'Background Image', 'total' ),
			),
		),
		array(
			'id' => 'header_background_image_style',
			'default' => '',
			'control' => array(
				'label' => __( 'Background Image Style', 'total' ),
				'type'  => 'select',
				'choices' => $bg_styles,
			),
		),
		/*** Aside ***/
		array(
			'id' => 'header_aside_heading',
			'control' => array(
				'type' => 'wpex-heading',
				'label' => __( 'Aside', 'total' ),
			),
			'control_display' => array(
				'check' => 'header_style',
				'value' => array( 'two', 'three', 'four' ),
			),
		),
		array(
			'id' => 'header_aside_visibility',
			'transport' => 'postMessage',
			'default' => 'visible-desktop',
			'control' => array(
				'label' => __( 'Visibility', 'total' ),
				'type' => 'select',
				'choices' => $choices_visibility,
			),
			'control_display' => array(
				'check'      => 'header_style',
				'value'      => array( 'two', 'three', 'four' ),
			),
		),
		array(
			'id' => 'header_flex_items',
			'transport' => 'postMessage',
			'control' => array(
				'label' => __( 'Vertical Align Aside Content', 'total' ),
				'type' => 'checkbox',
			),
			'control_display' => array(
				'check' => 'header_style',
				'value' => 'two',
			),
		),
		array(
			'id' => 'header_aside_search',
			'transport' => 'partialRefresh',
			'default' => true,
			'control' => array(
				'label' => __( 'Header Aside Search', 'total' ),
				'type' => 'checkbox',
			),
			'control_display' => array(
				'check' => 'header_style',
				'value' => 'two',
			),
		),
		array(
			'id' => 'header_aside',
			'transport' => 'partialRefresh',
			'control' => array(
				'label' => __( 'Header Aside Content', 'total' ),
				'type' => 'textarea',
				'description' => $post_id_content_desc,
			),
			'control_display' => array(
				'check'      => 'header_style',
				'value'      => array( 'two', 'three', 'four' ),
			),
		),
	)
);

/*-----------------------------------------------------------------------------------*/
/* - Header => Logo
/*-----------------------------------------------------------------------------------*/
$this->sections['wpex_header_logo'] = array(
	'title' => __( 'Logo', 'total' ),
	'panel' => 'wpex_header',
	'settings' => array(
		array(
			'id' => 'logo_top_margin',
			'transport' => 'postMessage',
			'control' => array(
				'type' => 'text',
				'label' => __( 'Top Margin', 'total' ),
				'description' => $pixel_desc,
			),
			'inline_css' => array(
				'target' => '#site-logo',
				'alter' => 'padding-top',
				'sanitize' => 'px',
			),
		),
		array(
			'id' => 'logo_bottom_margin',
			'transport' => 'postMessage',
			'control' => array(
				'type' => 'text',
				'label' => __( 'Bottom Margin', 'total' ),
				'description' => $pixel_desc,
			),
			'inline_css' => array(
				'target' => '#site-logo',
				'alter' => 'padding-bottom',
				'sanitize' => 'px',
			),
		),
		array(
			'id' => 'logo_color',
			'transport' => 'postMessage',
			'control' => array(
				'type' => 'color',
				'label' => __( 'Color', 'total' ),
				'active_callback' => 'wpex_cac_hasnt_custom_logo',
			),
			'inline_css' => array(
				'target' => '#site-logo a.site-logo-text',
				'alter' => 'color',
			),
		),
		array(
			'id' => 'logo_hover_color',
			'transport' => 'postMessage',
			'control' => array(
				'type' => 'color',
				'label' => __( 'Hover Color', 'total' ),
				'active_callback' => 'wpex_cac_hasnt_custom_logo',
			),
			'inline_css' => array(
				'target' => '#site-logo a.site-logo-text:hover',
				'alter' => 'color',
			),
		),
		array(
			'id' => 'custom_logo',
			'control' => array(
				'label' => __( 'Image Logo', 'total' ),
				'type' => 'media',
				'mime_type' => 'image'
			),
		),
		array(
			'id' => 'logo_height',
			'control' => array(
				'label' => __( 'Height', 'total' ),
				'type' => 'text',
				'description' => __( 'Used for retina and image height attribute tag.', 'total' ),
				'active_callback' => 'wpex_cac_has_image_logo',
			),
		),
		array(
			'id' => 'apply_logo_height',
			'default' => false,
			'control' => array(
				'label' => __( 'Apply Height', 'total' ),
				'type' => 'checkbox',
				'description' => __( 'Check this box to apply your logo height to the image. Useful for displaying large logos at a smaller size.', 'total' ),
				'active_callback' => 'wpex_cac_has_image_logo',
			),
		),
		array(
			'id' => 'logo_width',
			'transport' => 'postMessage',
			'control' => array(
				'label' => __( 'Width', 'total' ),
				'description' => __( 'Used for image width attribute tag.', 'total' ),
				'type' => 'text',
				'active_callback' => 'wpex_cac_has_image_logo',
			),
		),
		array(
			'id' => 'retina_logo',
			'default' => '',
			'control' => array(
				'label' => __( 'Retina Image Logo', 'total' ),
				'type' => 'media',
				'mime_type' => 'image',
				'active_callback' => 'wpex_cac_has_image_logo',
			),
		),
		array(
			'id' => 'logo_max_width',
			'transport' => 'postMessage',
			'control' => array(
				'label' => __( 'Logo Max Width: Desktop', 'total' ),
				'type' => 'text',
				'description' => __( 'Screens 960px wide and greater.', 'total' ),
				'active_callback' => 'wpex_cac_has_image_logo',
			),
			'inline_css' => array(
				'media_query' => '(min-width: 960px)',
				'target' => '#site-logo img',
				'alter' => 'max-width',
			),
		),
		array(
			'id' => 'logo_max_width_tablet_portrait',
			'transport' => 'postMessage',
			'control' => array(
				'label' => __( 'Logo Max Width: Tablet Portrait', 'total' ),
				'type' => 'text',
				'description' => __( 'Screens 768px-959px wide.', 'total' ),
				'active_callback' => 'wpex_cac_has_image_logo',
			),
			'inline_css' => array(
				'media_query' => '(min-width: 768px) and (max-width: 959px)',
				'target' => '#site-logo img',
				'alter' => 'max-width',
			),
		),
		array(
			'id' => 'logo_max_width_phone',
			'transport' => 'postMessage',
			'control' => array(
				'label' => __( 'Logo Max Width: Phone', 'total' ),
				'type' => 'text',
				'description' => __( 'Screens smaller than 767px wide.', 'total' ),
				'active_callback' => 'wpex_cac_has_image_logo',
			),
			'inline_css' => array(
				'media_query' => '(max-width: 767px)',
				'target' => '#site-logo img',
				'alter' => 'max-width',
			),
		),
	)
);

/*-----------------------------------------------------------------------------------*/
/* - Header => Logo Icon
/*-----------------------------------------------------------------------------------*/
$this->sections['wpex_header_logo_icon'] = array(
	'title' => __( 'Logo Icon', 'total' ),
	'panel' => 'wpex_header',
	'settings' => array(
		array(
			'id' => 'logo_icon',
			'transport' => 'postMessage',
			'default' => 'none',
			'control' => array(
				'label' => __( 'Icon Select', 'total' ),
				'type' => 'wpex-fa-icon-select',
				'active_callback' => 'wpex_cac_hasnt_custom_logo',
			),
		),
		array(
			'id' => 'logo_icon_color',
			'transport' => 'postMessage',
			'control' => array(
				'type' => 'color',
				'label' => __( 'Logo Icon Color', 'total' ),
				'active_callback' => 'wpex_cac_hasnt_custom_logo',
			),
			'inline_css' => array(
				'target' => '#site-logo-fa-icon',
				'alter' => 'color',
			),
		),
		array(
			'id' => 'logo_icon_right_margin',
			'transport' => 'postMessage',
			'control' => array(
				'type' => 'text',
				'label' => __( 'Logo Icon Right Margin', 'total' ),
				'description' => $pixel_desc,
				'active_callback' => 'wpex_cac_hasnt_custom_logo',
			),
			'inline_css' => array(
				'target' => '#site-logo-fa-icon',
				'alter' => 'margin-right',
			),
		),
	)
);

/*-----------------------------------------------------------------------------------*/
/* - Header => Fixed On Scroll
/*-----------------------------------------------------------------------------------*/
$this->sections['wpex_header_fixed'] = array(
	'title' => __( 'Sticky Header', 'total' ),
	'panel' => 'wpex_header',
	'settings' => array(
		array(
			'id' => 'fixed_header_style',
			'transport' => 'refresh',
			'default' => 'standard',
			'sanitize_callback' => 'esc_html',
			'control' => array(
				'label' => __( 'Style', 'total' ),
				'type' => 'select',
				'choices' => array(
					'disabled' => __( 'Disabled', 'total' ),
					'standard' => __( 'Standard', 'total' ),
					'shrink' => __( 'Shrink', 'total' ),
					'shrink_animated' => __( 'CSS3 Animated Shrink (Best with Image Logo)', 'total' ),
				),
				'active_callback' => 'wpex_cac_header_supports_fixed_header',
			),
		),
		array(
			'id' => 'fixed_header_start_position',
			'sanitize_callback' => 'esc_html',
			'control' => array(
				'label' => __( 'Sticky Start Position', 'total' ),
				'type' => 'text',
				'active_callback' => 'wpex_cac_header_supports_fixed_header',
				'description' => __( 'By default, the header becomes sticky as soon as you reach the header while scrolling. You can use this field to enter a number (in pixels) to offset the point at which the header becomes sticky (based on the top of the page) or the classname or ID of another element so that the header becomes sticky when it reaches that point (example: #my-custom-div).', 'total' ),
			),
		),
		array(
			'id' => 'fixed_header_shrink_start_height',
			'sanitize_callback' => 'absint',
			'default' => 60,
			'control' => array(
				'label' => __( 'Logo Start Height', 'total' ),
				'type' => 'number',
				'description' => __( 'In order to properly animate the header with CSS3 it is important to apply a fixed height to the header logo by default.', 'total' ),
				'active_callback' => 'wpex_cac_has_fixed_header_shrink',
			),
		),
		array(
			'id' => 'fixed_header_shrink_end_height',
			'default' => 50,
			'sanitize_callback' => 'absint',
			'control' => array(
				'label' => __( 'Logo Shrunk Height', 'total' ),
				'type' => 'number',
				'active_callback' => 'wpex_cac_has_fixed_header_shrink',
				'description' => __( 'Your shrink header height will be set to your Logo Shrunk Height plus 20px for a top and bottom padding of 10px.', 'total' ),
			),
		),
		array(
			'id' => 'fixed_header_mobile',
			'sanitize_callback' => 'esc_html',
			'control' => array(
				'label' => __( 'Mobile Support', 'total' ),
				'type' => 'checkbox',
				'active_callback' => 'wpex_cac_header_supports_fixed_header',
			),
		),
		array(
			'id' => 'fixed_header_opacity',
			'transport' => 'postMessage',
			'control' => array(
				'type' => 'number',
				'label' => __( 'Opacity', 'total' ),
				'active_callback' => 'wpex_cac_has_fixed_header',
				'input_attrs' => array(
					'min' => 0.1,
					'max' => 1,
					'step' => 0.1,
				),
			),
			'inline_css' => array(
				'target' => '.wpex-sticky-header-holder.is-sticky #site-header',
				'alter' => 'opacity',
			),
		),
		array(
			'id' => 'fixed_header_logo',
			'sanitize_callback' => 'absint',
			'control' => array(
				'label' => __( 'Custom Logo', 'total' ),
				'type' => 'media',
				'mime_type' => 'image',
				'active_callback' => 'wpex_cac_supports_fixed_header_logo',
				'description' => __( 'If this custom logo is a different size, for best results go to the Logo section and apply a custom height to your logo.', 'total' ),
			),
		),
		array(
			'id' => 'fixed_header_logo_retina',
			'sanitize_callback' => 'absint',
			'control' => array(
				'label' => __( 'Custom Logo Retina', 'total' ) .' '. __( 'Retina', 'total' ),
				'type' => 'media',
				'mime_type' => 'image',
				'active_callback' => 'wpex_cac_has_fixed_header_logo',
			),
		),
		array(
			'id' => 'fixed_header_logo_retina_height',
			'sanitize_callback' => 'absint',
			'control' => array(
				'label' => __( 'Custom Logo Retina Height', 'total' ),
				'type' => 'number',
				'active_callback' => 'wpex_supports_fixed_header_logo_retina_height',
			),
			'inline_css' => array(
				'target' => 'body.wpex-is-retina #site-header-sticky-wrapper.is-sticky #site-logo img',
				'alter' => 'height',
			),
		),
	)
);

/*-----------------------------------------------------------------------------------*/
/* - Header => Menu
/*-----------------------------------------------------------------------------------*/
$this->sections['wpex_header_menu'] = array(
	'title' => __( 'Menu', 'total' ),
	'panel' => 'wpex_header',
	'settings' => array(
		array(
			'id' => 'menu_arrow_down',
			'default' => false,
			'control' => array(
				'label' => __( 'Top Level Dropdown Icon', 'total' ),
				'type' => 'checkbox',
			),
		),
		array(
			'id' => 'menu_arrow_side',
			'default' => true,
			'control' => array(
				'label' => __( 'Second+ Level Dropdown Icon', 'total' ),
				'type' => 'checkbox',
			),
		),
		array(
			'id' => 'header_menu_disable_borders',
			'transport' => 'postMessage',
			'control' => array(
				'label' => __( 'Disable Menu Inner Borders', 'total' ),
				'type' => 'checkbox',
			),
			'control_display' => array(
				'check' => 'header_style',
				'value' => 'two',
			),
		),
		array(
			'id' => 'header_menu_center',
			'transport' => 'postMessage',
			'control' => array(
				'label' => __( 'Center Menu Items', 'total' ),
				'type' => 'checkbox',
			),
			'control_display' => array(
				'check' => 'header_style',
				'value' => 'two',
			),
		),
		array(
			'id' => 'menu_dropdown_top_border',
			//'transport' => 'postMessage', // Can't cause it has dependent options
			'default' => false,
			'control' => array(
				'label' => __( 'Dropdown Top Border', 'total' ),
				'type' => 'checkbox',
			),
			'control_display' => array(
				'check' => 'header_style',
				'value' => array( 'one', 'two', 'three', 'four', 'five', 'six' ),
			),
		),
		array(
			'id' => 'menu_flush_dropdowns',
			'default' => false,
			'control' => array(
				'label' => __( 'Flush Dropdowns', 'total' ),
				'type' => 'checkbox',
			),
			'control_display' => array(
				'check' => 'header_style',
				'value' => 'one',
			),
		),
		array(
			'id' => 'menu_dropdown_style',
			'transport' => 'postMessage',
			'control' => array(
				'label' => __( 'Dropdown Style', 'total' ),
				'type' => 'select',
				'choices' => wpex_get_menu_dropdown_styles(),
			),
		),
		array(
			'id' => 'menu_dropdown_dropshadow',
			'transport' => 'postMessage',
			'default' => '',
			'control' => array(
				'label' => __( 'Dropdown Dropshadow Style', 'total' ),
				'type' => 'select',
				'choices' => array(
					'' => __( 'None', 'total' ),
					'one' => __( 'One', 'total' ),
					'two' => __( 'Two', 'total' ),
					'three' => __( 'Three', 'total' ),
					'four' => __( 'Four', 'total' ),
					'five' => __( 'Five', 'total' ),
				),
			),
		),
		array(
			'id' => 'menu_li_left_margin',
			'transport' => 'postMessage',
			'control' => array(
				'type' => 'text',
				'label' => __( 'Menu Items Left Margin', 'total' ),
				'description' => __( 'Can be used to increase the spacing between your items. Value in pixels.', 'total' ),
			),
			'inline_css' => array(
				'target' => 'body .navbar-style-one .dropdown-menu > li.menu-item',
				'alter' => 'margin-left',
				'sanitize' => 'px',
			),
			'control_display' => array(
				'check' => 'header_style',
				'value' => 'one',
			),
		),
		array(
			'id' => 'menu_a_padding',
			'transport' => 'postMessage',
			'control' => array(
				'type' => 'text',
				'label' => __( 'Menu Items Left/Right Padding', 'total' ),
				'description' => __( 'Can be used to increase the spacing between your items. Value in pixels.', 'total' ),
			),
			'inline_css' => array(
				'target' => array(
					'body .navbar-style-two .dropdown-menu > li.menu-item > a',
					'body .navbar-style-three .dropdown-menu > li.menu-item > a',
					'body .navbar-style-four .dropdown-menu > li.menu-item > a',
					'body .navbar-style-five .dropdown-menu > li.menu-item > a',
				),
				'alter' => array( 'padding-left', 'padding-right' ),
				'sanitize' => 'px',
			),
			'control_display' => array(
				'check' => 'header_style',
				'value' => array( 'two', 'three', 'four', 'five' ),
			),
		),

		/*** Main Styling ***/
		array(
			'id' => 'menu_main_styling_heading',
			'control' => array(
				'type' => 'wpex-heading',
				'label' => __( 'Styling: Main', 'total' ),
			),
		),
		array(
			'id' => 'menu_background',
			'transport' => 'postMessage',
			'control' => array(
				'type' => 'color',
				'label' => __( 'Background', 'total' ),
			),
			'inline_css' => array(
				'target' => array(
					'#site-navigation-wrap',
					'#site-navigation-sticky-wrapper.is-sticky #site-navigation-wrap',
				),
				'alter' => 'background-color',
			),
		),
		array(
			'id' => 'menu_borders',
			'transport' => 'postMessage',
			'control' => array(
				'type' => 'color',
				'label' => __( 'Borders', 'total' ),
				'description' => __( 'Not all menus have borders, but this setting is for those that do', 'total' ),
			),
			'inline_css' => array(
				'target' => array(
					'#site-navigation > ul li',
					'#site-navigation a',
					'#site-navigation ul',
					'#site-navigation-wrap',
					'#site-navigation',
					'.navbar-style-six #site-navigation',
					'#site-navigation-sticky-wrapper.is-sticky #site-navigation-wrap',
				),
				'alter' => 'border-color',
			),
		),
		// Menu Link Colors
		array(
			'id' => 'menu_link_color',
			'transport' => 'postMessage',
			'control' => array(
				'type' => 'color',
				'label' => __( 'Link Color', 'total' ),
			),
			'inline_css' => array(
				'target' => '#site-navigation .dropdown-menu > li.menu-item > a',
				'alter' => 'color',
			),
		),
		array(
			'id' => 'menu_link_color_hover',
			'transport' => 'postMessage',
			'control' => array(
				'type' => 'color',
				'label' => __( 'Link Color: Hover', 'total' ),
			),
			'inline_css' => array(
				'target' => '#site-navigation .dropdown-menu > li.menu-item > a:hover,
							#site-navigation .dropdown-menu > li.menu-item.dropdown.sfHover > a',
				'alter' => 'color',
			),
		),
		array(
			'id' => 'menu_link_color_active',
			'transport' => 'postMessage',
			'control' => array(
				'type' => 'color',
				'label' => __( 'Link Color: Current Menu Item', 'total' ),
			),
			'inline_css' => array(
				'target' => '#site-navigation .dropdown-menu > li.menu-item.current-menu-item > a,
							#site-navigation .dropdown-menu > li.menu-item.current-menu-parent > a',
				'alter' => 'color',
				//'important' => true, // removed in 4.4.1 - causes issues with superfish hover settings
			),
		),
		// Link Background
		array(
			'id' => 'menu_link_background',
			'transport' => 'postMessage',
			'control' => array(
				'type' => 'color',
				'label' => __( 'Link Background', 'total' ),
			),
			'inline_css' => array(
				'target' => '#site-navigation .dropdown-menu > li.menu-item > a',
				'alter' => 'background-color',
			),
		),
		array(
			'id' => 'menu_link_hover_background',
			'transport' => 'postMessage',
			'control' => array(
				'type' => 'color',
				'label' => __( 'Link Background: Hover', 'total' ),
			),
			'inline_css' => array(
				'target' => '#site-navigation .dropdown-menu > li.menu-item > a:hover,
							#site-navigation .dropdown-menu > li.menu-item.dropdown.sfHover > a',
				'alter' => 'background-color',
			),
		),
		array(
			'id' => 'menu_link_active_background',
			'transport' => 'postMessage',
			'control' => array(
				'type' => 'color',
				'label' => __( 'Link Background: Current Menu Item', 'total' ),
			),
			'inline_css' => array(
				'target' => '#site-navigation .dropdown-menu > li.menu-item.current-menu-item > a,
							#site-navigation .dropdown-menu > li.menu-item.current-menu-parent > a',
				'alter' => 'background-color',
			),
		),
		// Link Inner
		array(
			'id' => 'menu_link_span_background',
			'transport' => 'postMessage',
			'control' => array(
				'type' => 'color',
				'label' => __( 'Link Inner Background', 'total' ),
			),
			'inline_css' => array(
				'target' => '#site-navigation .dropdown-menu > li.menu-item > a > span.link-inner',
				'alter' => 'background-color',
			),
		),
		array(
			'id' => 'menu_link_span_hover_background',
			'transport' => 'postMessage',
			'control' => array(
				'type' => 'color',
				'label' => __( 'Link Inner Background: Hover', 'total' ),
			),
			'inline_css' => array(
				'target' => '#site-navigation .dropdown-menu > li.menu-item > a:hover > span.link-inner,
							#site-navigation .dropdown-menu > li.menu-item.dropdown.sfHover > a > span.link-inner',
				'alter' => 'background-color',
			),
		),
		array(
			'id' => 'menu_link_span_active_background',
			'transport' => 'postMessage',
			'control' => array(
				'type' => 'color',
				'label' => __( 'Link Inner Background: Current Menu Item', 'total' ),
			),
			'inline_css' => array(
				'target' => '#site-navigation .dropdown-menu > li.menu-item.current-menu-item > a > span.link-inner,
							#site-navigation .dropdown-menu > li.menu-item.current-menu-parent > a > span.link-inner',
				'alter' => 'background-color',
			),
		),

		/**** Dropdown Styling ****/
		array(
			'id' => 'menu_dropdowns_styling_heading',
			'control' => array(
				'type' => 'wpex-heading',
				'label' => __( 'Styling: Dropdowns', 'total' ),
			),
		),

		// Menu Dropdowns
		array(
			'id' => 'dropdown_menu_background',
			'transport' => 'postMessage',
			'control' => array(
				'type' => 'color',
				'label' => __( 'Background', 'total' ),
			),
			'inline_css' => array(
				'target' => '#site-header #site-navigation .dropdown-menu ul.sub-menu',
				'alter' => 'background-color',
			),
		),
		// Pointer
		array(
			'id' => 'dropdown_menu_pointer_bg',
			'transport' => 'postMessage',
			'control' => array(
				'type' => 'color',
				'label' => __( 'Pointer Background', 'total' ),
			),
			'inline_css' => array(
				'target' => '.wpex-dropdowns-caret .dropdown-menu ul.sub-menu::after',
				'alter' => 'border-bottom-color',
			),
		),
		array(
			'id' => 'dropdown_menu_pointer_border',
			'transport' => 'postMessage',
			'control' => array(
				'type' => 'color',
				'label' => __( 'Pointer Border', 'total' ),
			),
			'inline_css' => array(
				'target' => '.wpex-dropdowns-caret .dropdown-menu ul.sub-menu::before',
				'alter' => 'border-bottom-color',
			),
		),
		// Borders
		array(
			'id' => 'dropdown_menu_borders',
			'transport' => 'postMessage',
			'control' => array(
				'type' => 'color',
				'label' => __( 'Dropdown Borders', 'total' ),
			),
			'inline_css' => array(
				'target' => array(
					'#site-header #site-navigation .dropdown-menu ul.sub-menu',
					'#site-header #site-navigation .dropdown-menu ul.sub-menu li.menu-item',
					'#site-header #site-navigation .dropdown-menu ul.sub-menu li.menu-item a',
				),
				'alter' => 'border-color',
			),
		),
		array(
			'id' => 'menu_dropdown_top_border_color',
			'transport' => 'postMessage',
			'control' => array(
				'type' => 'color',
				'label' => __( 'Top Border', 'total' ),
				'active_callback' => 'wpex_cac_has_menu_dropdown_top_border',
			),
			'inline_css' => array(
				'target' => array(
					'.wpex-dropdown-top-border #site-navigation .dropdown-menu li.menu-item ul.sub-menu',
					'#searchform-dropdown',
					'#current-shop-items-dropdown',
				),
				'alter' => 'border-top-color',
				'important' => true,
			),
		),
		// Link color
		array(
			'id' => 'dropdown_menu_link_color',
			'transport' => 'postMessage',
			'control' => array(
				'type' => 'color',
				'label' => __( 'Link Color', 'total' ),
			),
			'inline_css' => array(
				'target' => '#site-header #site-navigation .dropdown-menu ul.sub-menu > li.menu-item > a',
				'alter' => 'color',
			),
		),
		array(
			'id' => 'dropdown_menu_link_color_hover',
			'transport' => 'postMessage',
			'control' => array(
				'type' => 'color',
				'label' => __( 'Link Color: Hover', 'total' ),
			),
			'inline_css' => array(
				'target' => '#site-header #site-navigation .dropdown-menu ul.sub-menu > li.menu-item > a:hover',
				'alter' => 'color',
			),
		),
		array(
			'id' => 'dropdown_menu_link_hover_bg',
			'transport' => 'postMessage',
			'control' => array(
				'type' => 'color',
				'label' => __( 'Link Background: Hover', 'total' ),
			),
			'subtitle' => __( 'Select your custom hex color.', 'total' ),
			'inline_css' => array(
				'target' => '#site-header #site-navigation .dropdown-menu ul.sub-menu > li.menu-item > a:hover',
				'alter' => 'background-color',
			),
		),
		// Current item
		array(
			'id' => 'dropdown_menu_link_color_active',
			'transport' => 'postMessage',
			'control' => array(
				'type' => 'color',
				'label' => __( 'Link Color: Current Menu Item', 'total' ),
			),
			'inline_css' => array(
				'target' => '#site-header #site-navigation .dropdown-menu ul.sub-menu > li.menu-item.current-menu-item > a',
				'alter' => 'color',
			),
		),
		array(
			'id' => 'dropdown_menu_link_bg_active',
			'transport' => 'postMessage',
			'control' => array(
				'type' => 'color',
				'label' => __( 'Link Background: Current Menu Item', 'total' ),
			),
			'inline_css' => array(
				'target' => '#site-header #site-navigation .dropdown-menu ul.sub-menu > li.menu-item.current-menu-item > a',
				'alter' => 'background-color',
			),
		),
		// Mega menu
		array(
			'id' => 'mega_menu_title',
			'transport' => 'postMessage',
			'control' => array(
				'type' => 'color',
				'label' => __( 'Megamenu Subtitle Color', 'total' ),
			),
			'inline_css' => array(
				'target' => '#site-header #site-navigation .sf-menu > li.megamenu > ul.sub-menu > .menu-item-has-children > a',
				'alter' => 'color',
			),
		),
	)
);

/*-----------------------------------------------------------------------------------*/
/* - Header => Menu Search Form
/*-----------------------------------------------------------------------------------*/
$this->sections['wpex_menu_search'] = array(
	'title' => __( 'Menu Search', 'total' ),
	'panel' => 'wpex_header',
	'settings' => array(
		array(
			'id' => 'menu_search_style',
			'default' => 'drop_down',
			'control' => array(
				'label' => __( 'Style', 'total' ),
				'type' => 'select',
				'choices' => array(
					'disabled' => __( 'Disabled','total' ),
					'drop_down' => __( 'Drop Down','total' ),
					'overlay' => __( 'Site Overlay','total' ),
					'header_replace' => __( 'Header Replace','total' )
				),
				'description' => __( 'Vertical header styles only support the disabled and overlay styles.', 'total' ),
			),
		),
		array(
			'id' => 'search_dropdown_top_border',
			'transport' => 'postMessage',
			'control' => array(
				'label' => __( 'Drop Down Top Border', 'total' ),
				'type' => 'color',
			),
			'inline_css' => array(
				'target' => '#searchform-dropdown',
				'alter' => 'border-top-color',
				'important' => true,
			),
		),
	)
);

/*-----------------------------------------------------------------------------------*/
/* - Header => Fixed Menu
/*-----------------------------------------------------------------------------------*/
$this->sections['wpex_fixed_menu'] = array(
	'title' => __( 'Sticky Menu', 'total' ),
	'panel' => 'wpex_header',
	'settings' => array(
		array(
			'id' => 'fixed_header_menu',
			'default' => true,
			'control' => array(
				'label' => __( 'Sticky Header Menu', 'total' ),
				'type' => 'checkbox',
				'active_callback' => 'wpex_cac_header_supports_fixed_menu',
			),
		),
	)
);

/*-----------------------------------------------------------------------------------*/
/* - Header => Mobile Menu
/*-----------------------------------------------------------------------------------*/
$this->sections['wpex_header_mobile_menu'] = array(
	'title' => __( 'Mobile Menu', 'total' ),
	'panel' => 'wpex_header',
	'settings' => array(
		// Breakpoint
		array(
			'id' => 'mobile_menu_breakpoint',
			'control' => array(
				'label' => __( 'Mobile Menu Breakpoint', 'total' ),
				'type' => 'text',
				'desc' => __( 'Default:', 'total' ) .' 959px'
			),
			'control_display' => array(
				'check' => 'header_style',
				'value' => array( 'one', 'two', 'three', 'four', 'five', 'seven' ),
			),
		),
		// Search
		array(
			'id' => 'mobile_menu_search',
			'default' => true,
			'control' => array(
				'label' => __( 'Mobile Menu Search', 'total' ),
				'type' => 'checkbox',
			),
		),
		/*** Mobile Menu > Toggle Style ***/
		array(
			'id' => 'mobile_menu_toggle_style',
			'default' => 'icon_buttons',
			'control' => array(
				'label' => __( 'Toggle Button Style', 'total' ),
				'type' => 'select',
				'active_callback' => 'wpex_cac_mobile_menu_toggle_style',
				'choices' => array(
					'icon_buttons' => __( 'Right Aligned Icon Button(s)', 'total' ),
					'icon_buttons_under_logo' => __( 'Under The Logo Icon Button(s)', 'total' ),
					'navbar' => __( 'Navbar', 'total' ),
					'fixed_top'  => __( 'Fixed Site Top', 'total' ),
					'custom'  => __( 'Custom', 'total' ),
				),
				'desc' => __( 'If you select "custom" the theme will load the needed code for your mobile menu which you can then open/close by adding any link to the page with the classname "mobile-menu-toggle".', 'total' )
			),
		),
		array(
			'id' => 'mobile_menu_navbar_position',
			'default' => 'wpex_hook_header_bottom',
			'control' => array(
				'label' => __( 'Menu Position', 'total' ),
				'type' => 'select',
				'active_callback' => 'wpex_cac_is_mobile_navbar',
				'choices' => array(
					'wpex_hook_header_bottom' => __( 'Header Bottom', 'total' ),
					'outer_wrap_before' => __( 'Top of site', 'total' ),
				),
			),
		),
		array(
			'id' => 'mobile_menu_toggle_fixed_top_bg',
			'transport' => 'postMessage',
			'control' => array(
				'label' => __( 'Toggle Background', 'total' ),
				'type' => 'color',
				'active_callback' => 'wpex_cac_is_mobile_fixed_or_navbar',
			),
			'inline_css' => array(
				'target' => '#wpex-mobile-menu-fixed-top, #wpex-mobile-menu-navbar',
				'alter' => 'background',
			),
		),
		array(
			'id' => 'mobile_menu_toggle_text',
			'transport' => 'postMessage',
			'default' => __( 'Menu', 'total' ),
			'control' => array(
				'label' => __( 'Toggle Text', 'total' ),
				'type' => 'text',
				'active_callback' => 'wpex_cac_is_mobile_fixed_or_navbar',
			),
		),
		/*** Mobile Menu > Style */
		array(
			'id' => 'mobile_menu_style',
			'default' => 'sidr',
			'control' => array(
				'label' => __( 'Mobile Menu Style', 'total' ),
				'type' => 'select',
				'choices' => wpex_get_mobile_menu_styles(),
			),
		),
		array(
			'id' => 'full_screen_mobile_menu_style',
			'default' => 'white',
			'transport' => 'postMessage',
			'control' => array(
				'label' => __( 'Style', 'total' ),
				'type' => 'select',
				'active_callback' => 'wpex_cac_mobile_menu_is_full_screen',
				'choices' => array(
					'white'	=> __( 'White', 'total' ),
					'black'	=> __( 'Black', 'total' ),
				),
			),
		),
		array(
			'id' => 'mobile_menu_sidr_direction',
			'default' => 'right',
			'control' => array(
				'label' => __( 'Direction', 'total' ),
				'type' => 'select',
				'active_callback' => 'wpex_cac_mobile_menu_is_sidr',
				'choices' => array(
					'right'	=> __( 'Right', 'total' ),
					'left'	=> __( 'Left', 'total' ),
				),
			),
		),
		array(
			'id' => 'mobile_menu_sidr_displace',
			'default' => false,
			'control' => array(
				'label' => __( 'Displace', 'total' ),
				'type' => 'checkbox',
				'active_callback' => 'wpex_cac_mobile_menu_is_sidr',
				'description' => __( 'Enable to display/push the site content over when opening the sidebar mobile menu.', 'total' ),
			),
		),
		/*** Mobile Menu > Mobile Icons Styling ***/
		array(
			'id' => 'mobile_menu_icons_styling',
			'control' => array(
				'type' => 'wpex-heading',
				'label' => __( 'Icons Styling', 'total' ),
				'active_callback' => 'wpex_cac_has_mobile_menu_icons',
			),
		),

		/* @deprecated in v4.5.5
		array(
			'id' => 'mobile_menu_icon_size',
			'transport' => 'postMessage',
			'control' => array(
				'type' => 'text',
				'label' => __( 'Font Size', 'total' ),
				'active_callback' => 'wpex_cac_has_mobile_menu_icons',
				'description' => $pixel_desc,
			),
			'inline_css' => array(
				'target' => '#mobile-menu a',
				'alter' => 'font-size',
				'sanitize' => 'px',
			),
		),*/

		array(
			'id' => 'mobile_menu_icon_color',
			'transport' => 'refresh',
			'control' => array(
				'type' => 'color',
				'label' => __( 'Color', 'total' ),
				'active_callback' => 'wpex_cac_has_mobile_menu_icons',
			),
			'inline_css' => array(
				'target' => '#mobile-menu a',
				'alter' => 'color',
			),
		),
		array(
			'id' => 'mobile_menu_icon_color_hover',
			'transport' => 'refresh',
			'control' => array(
				'type' => 'color',
				'label' => __( 'Color: Hover', 'total' ),
				'active_callback' => 'wpex_cac_has_mobile_menu_icons',
			),
			'inline_css' => array(
				'target' => '#mobile-menu a:hover',
				'alter' => 'color',
			),
		),

		/* @deprecated in v4.5.5
		array(
			'id' => 'mobile_menu_icon_background',
			'transport' => 'postMessage',
			'control' => array(
				'type' => 'color',
				'label' => __( 'Background', 'total' ),
				'active_callback' => 'wpex_cac_has_mobile_menu_icons',
			),
			'inline_css' => array(
				'target' => '#mobile-menu a',
				'alter' => 'background',
			),
		),
		array(
			'id' => 'mobile_menu_icon_background_hover',
			'transport' => 'postMessage',
			'control' => array(
				'type' => 'color',
				'label' => __( 'Background: Hover', 'total' ),
				'active_callback' => 'wpex_cac_has_mobile_menu_icons',
			),
			'inline_css' => array(
				'target' => '#mobile-menu a:hover',
				'alter' => 'background',
			),
		),
		array(
			'id' => 'mobile_menu_icon_border',
			'transport' => 'postMessage',
			'control' => array(
				'type' => 'color',
				'label' => __( 'Border', 'total' ),
				'active_callback' => 'wpex_cac_has_mobile_menu_icons',
			),
			'inline_css' => array(
				'target' => '#mobile-menu a',
				'alter' => 'border-color',
			),
		),
		array(
			'id' => 'mobile_menu_icon_border_hover',
			'transport' => 'postMessage',
			'control' => array(
				'type' => 'color',
				'label' => __( 'Border: Hover', 'total' ),
				'active_callback' => 'wpex_cac_has_mobile_menu_icons',
			),
			'inline_css' => array(
				'target' => '#mobile-menu a:hover',
				'alter' => 'border-color',
			),
		), */

		/*** Mobile Menu > Sidr ***/
		array(
			'id' => 'mobile_menu_sidr_styling',
			'control' => array(
				'type' => 'wpex-heading',
				'label' => __( 'Sidebar Menu Styling', 'total' ),
				'active_callback' => 'wpex_cac_mobile_menu_is_sidr',
			),
		),
		array(
			'id' => 'mobile_menu_sidr_background',
			'transport' => 'postMessage',
			'control' => array(
				'type' => 'color',
				'label' => __( 'Background', 'total' ),
				'active_callback' => 'wpex_cac_mobile_menu_is_sidr',
			),
			'inline_css' => array(
				'target' => '#sidr-main',
				'alter' => 'background-color',
			),
		),
		array(
			'id' => 'mobile_menu_sidr_borders',
			'transport' => 'postMessage',
			'control' => array(
				'type' => 'color',
				'label' => __( 'Borders', 'total' ),
				'active_callback' => 'wpex_cac_mobile_menu_is_sidr',
			),
			'inline_css' => array(
				'target' => '#sidr-main li, #sidr-main ul, .sidr-class-mobile-menu-searchform input, .sidr-class-mobile-menu-searchform',
				'alter' => 'border-color',
			),
		),
		array(
			'id' => 'mobile_menu_links',
			'transport' => 'postMessage',
			'control' => array(
				'type' => 'color',
				'label' => __( 'Color', 'total' ),
				'active_callback' => 'wpex_cac_mobile_menu_is_sidr',
			),
			'inline_css' => array(
				'target' => '#sidr-main,.sidr-class-wpex-close',
				'alter' => 'color',
			),
		),
		array(
			'id' => 'mobile_menu_links_hover',
			'transport' => 'postMessage',
			'control' => array(
				'type' => 'color',
				'label' => __( 'Links: Hover', 'total' ),
				'active_callback' => 'wpex_cac_mobile_menu_is_sidr',
			),
			'inline_css' => array(
				'target' => '.sidr a:hover,.sidr-class-menu-item-has-children.active > a',
				'alter' => 'color',
			),
		),

		/*** Mobile Menu > Toggle Menu ***/
		array(
			'id' => 'mobile_menu_toggle_styling',
			'control' => array(
				'type' => 'wpex-heading',
				'label' => __( 'Toggle Menu Styling', 'total' ),
				'active_callback' => 'wpex_cac_mobile_menu_is_toggle',
			),
		),
		array(
			'id' => 'toggle_mobile_menu_background',
			'transport' => 'postMessage',
			'control' => array(
				'type' => 'color',
				'label' => __( 'Background', 'total' ),
				'active_callback' => 'wpex_cac_mobile_menu_is_toggle',
			),
			'inline_css' => array(
				'target' => array(
					'.mobile-toggle-nav',
					'.wpex-mobile-toggle-menu-fixed_top .mobile-toggle-nav',
				),
				'alter' => 'background',
			),
		),
		array(
			'id' => 'toggle_mobile_menu_borders',
			'transport' => 'postMessage',
			'control' => array(
				'type' => 'color',
				'label' => __( 'Borders', 'total' ),
				'active_callback' => 'wpex_cac_mobile_menu_is_toggle',
			),
			'inline_css' => array(
				'target' => array(
					'.mobile-toggle-nav a',
					'.wpex-mobile-toggle-menu-fixed_top .mobile-toggle-nav a',
				),
				'alter' => 'border-color',
			),
		),
		array(
			'id' => 'toggle_mobile_menu_links',
			'transport' => 'postMessage',
			'control' => array(
				'type' => 'color',
				'label' => __( 'Links', 'total' ),
				'active_callback' => 'wpex_cac_mobile_menu_is_toggle',
			),
			'inline_css' => array(
				'target' => array(
					'.mobile-toggle-nav a',
					'.wpex-mobile-toggle-menu-fixed_top .mobile-toggle-nav a',
				),
				'alter' => 'color',
			),
		),
		array(
			'id' => 'toggle_mobile_menu_links_hover',
			'transport' => 'postMessage',
			'control' => array(
				'type' => 'color',
				'label' => __( 'Links: Hover', 'total' ),
				'active_callback' => 'wpex_cac_mobile_menu_is_toggle',
			),
			'inline_css' => array(
				'target' => array(
					'.mobile-toggle-nav a:hover',
					'.wpex-mobile-toggle-menu-fixed_top .mobile-toggle-nav a:hover',
				),
				'alter' => 'color',
			),
		),
	),
);