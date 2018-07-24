<?php
/**
 * Layout Panel
 *
 * @package Total WordPress Theme
 * @subpackage Customizer
 * @version 4.5.4
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// General
$this->sections['wpex_layout_general'] = array(
	'title' => __( 'General', 'total' ),
	'panel' => 'wpex_layout',
	'settings' => array(
		array(
			'id' => 'responsive',
			'default' => true,
			'control' => array(
				'label' => __( 'Responsiveness', 'total' ),
				'type' => 'checkbox',
				'desc' => __( 'If you are using the Visual Composer plugin, make sure to enable/disable the responsive settings at Settings->Visual composer as well.', 'total' ),
			),
		),
		array(
			'id' => 'container_max_width',
			'transport' => 'postMessage',
			'control' => array(
				'label' => __( 'Max Width', 'total' ),
				'type' => 'text',
				'desc' => __( 'Default:', 'total' ) .' 90%',
				'active_callback' => 'wpex_cac_container_layout_supports_max_width',
			),
			'inline_css' => array(
				'target' => 'body.wpex-responsive .container, body.wpex-responsive .vc_row-fluid.container',
				'alter' => 'max-width',
			),
		),
		array(
			'id' => 'content_layout',
			'default' => '',
			'control' => array(
				'label' => __( 'Content Layout', 'total' ),
				'type' => 'select',
				'choices' => $post_layouts,
				'desc' => __( 'Select your default content layout for your site. You can always browse to different tabs in the Customizer such as the blog tab to alter your layout specifically for your blog archives and posts.', 'total' ),
			),
		),
		array(
			'id' => 'main_layout_style',
			'default' => 'full-width',
			'control' => array(
				'label' => __( 'Site Layout Style', 'total' ),
				'type' => 'select',
				'choices' => array(
					'full-width' => __( 'Full Width','total' ),
					'boxed' => __( 'Boxed','total' )
				),
			),
		),
		array(
			'id' => 'boxed_dropdshadow',
			'transport' => 'postMessage',
			'control' => array(
				'label' => __( 'Boxed Layout Drop-Shadow', 'total' ),
				'type' => 'checkbox',
			),
			'control_display' => array(
				'check' => 'main_layout_style',
				'value' => array( 'boxed' ),
			),
		),
		array(
			'id' => 'boxed_padding',
			'transport' => 'postMessage',
			'control' => array(
				'type' => 'text',
				'label' => __( 'Outer Margin', 'total' ),
				'desc' => __( 'Default:', 'total' ) .' 40px 30px',
			),
			'control_display' => array(
				'check' => 'main_layout_style',
				'value' => array( 'boxed' ),
			),
			'inline_css' => array(
				'target' => '.boxed-main-layout #outer-wrap',
				'alter' => 'padding',
			),
		),
		array(
			'id' => 'boxed_wrap_bg',
			'transport' => 'postMessage',
			'control' => array(
				'type' => 'color',
				'label' => __( 'Inner Background', 'total' ),
			),
			'control_display' => array(
				'check' => 'main_layout_style',
				'value' => array( 'boxed' ),
			),
			'inline_css' => array(
				'target' => '.boxed-main-layout #wrap,.is-sticky #site-header',
				'alter' => 'background-color',
			),
		),
		array(
			'id' => 'site_frame_border',
			'transport' => 'postMessage',
			'control' => array(
				'label' => __( 'Enable Site Frame Border', 'total' ),
				'type' => 'checkbox',
				'desc' => __( 'If enabled it will add a 10px fixed color frame around your site content.', 'total' ),
			),
		),
		array(
			'id' => 'site_frame_border_color',
			'transport' => 'postMessage',
			'control' => array(
				'label' => __( 'Site Frame Border Color', 'total' ),
				'type' => 'color',
			),
			'inline_css' => array(
				'target' => '#wpex-sfb-l,#wpex-sfb-r,#wpex-sfb-t,#wpex-sfb-b',
				'alter' => 'background-color',
			),
			'control_display' => array(
				'check' => 'site_frame_border',
				'value' => 'true',
			),
		),
	),
);

// Desktop Widths
$this->sections['wpex_layout_desktop_widths'] = array(
	'title' => __( 'Desktop Widths', 'total' ),
	'panel' => 'wpex_layout',
	'desc' => __( 'For screens greater than or equal to 960px. Accepts both pixels or percentage values.', 'total' ),
	'settings' => array(
		array(
			'id' => 'main_container_width',
			'transport' => 'postMessage',
			'control' => array(
				'label' => __( 'Main Container Width', 'total' ),
				'type' => 'text',
				'desc' => __( 'Example:', 'total' ) .' 980px',
			),
			'inline_css' => array(
				'target' => '.full-width-main-layout .container,.full-width-main-layout .vc_row-fluid.container,.boxed-main-layout #wrap',
				'alter' => 'width',
			),
		),
		array(
			'id' => 'left_container_width',
			'transport' => 'postMessage',
			'control' => array(
				'label' => __( 'Content with Sidebar Width', 'total' ),
				'type' => 'text',
				'desc' => __( 'Example:', 'total' ) .' 69%',
			),
			'inline_css' => array(
				'media_query' => '(min-width: 960px)',
				'target' => 'body.has-sidebar .content-area',
				'alter' => array( 'width', 'max-width' ),
			),
		),
		array(
			'id' => 'sidebar_width',
			'transport' => 'postMessage',
			'control' => array(
				'label' => __( 'Sidebar Width', 'total' ),
				'type' => 'text',
				'desc' => __( 'Example:', 'total' ) .' 26%',
			),
			'inline_css' => array(
				'media_query' => '(min-width: 960px)',
				'target' => '#sidebar',
				'alter' => array( 'width', 'max-width' ),
			),
		),
	),
);

// Medium Screen Widths
$this->sections['wpex_layout_medium_widths'] = array(
	'title' => __( 'Medium Screens Widths', 'total' ),
	'panel' => 'wpex_layout',
	'desc' => __( 'For screens between 960px - 1280px. Such as landscape tablets and small monitors/laptops. Accepts both pixels or percentage values.', 'total' ),
	'settings' => array(
		array(
			'id' => 'tablet_landscape_main_container_width',
			'transport' => 'postMessage',
			'control' => array(
				'label' => __( 'Main Container Width', 'total' ),
				'type' => 'text',
				'desc' => __( 'Example:', 'total' ) .' 90%',
			),
			'inline_css' => array(
				'target' => '.full-width-main-layout .container,.full-width-main-layout .vc_row-fluid.container,.boxed-main-layout #wrap',
				'alter' => array( 'width', 'max-width' ),
				'media_query' => '(min-width: 960px) and (max-width: 1280px)',
			),
		),
		array(
			'id' => 'tablet_landscape_left_container_width',
			'transport' => 'postMessage',
			'control' => array(
				'label' => __( 'Content with Sidebar Width', 'total' ),
				'type' => 'text',
				'desc' => __( 'Example:', 'total' ) .' 69%',
			),
			'inline_css' => array(
				'target' => 'body.has-sidebar .content-area',
				'alter' => array( 'width', 'max-width' ),
				'media_query' => '(min-width: 960px) and (max-width: 1280px)',
			),
		),
		array(
			'id' => 'tablet_landscape_sidebar_width',
			'transport' => 'postMessage',
			'control' => array(
				'label' => __( 'Sidebar Width', 'total' ),
				'type' => 'text',
				'desc' => __( 'Example:', 'total' ) .' 26%',
			),
			'inline_css' => array(
				'target' => '#sidebar',
				'alter' => array( 'width', 'max-width' ),
				'media_query' => '(min-width: 960px) and (max-width: 1280px)',
			),
		),
	),
);

// Tablet Portrait Widths
$this->sections['wpex_layout_tablet_widths'] = array(
	'title' => __( 'Tablet Widths', 'total' ),
	'panel' => 'wpex_layout',
	'desc' => __( 'For screens between 768px - 959px. Such as portrait tablet. Accepts both pixels or percentage values.', 'total' ),
	'settings' => array(
		array(
			'id' => 'tablet_main_container_width',
			'transport' => 'postMessage',
			'control' => array(
				'label' => __( 'Main Container Width', 'total' ),
				'type' => 'text',
				'desc' => __( 'Example:', 'total' ) .' 90%',
			),
			'inline_css' => array(
				'target' => '.full-width-main-layout .container,.full-width-main-layout .vc_row-fluid.container,.boxed-main-layout #wrap',
				'alter' => array( 'width', 'max-width' ),
				'media_query' => '(min-width: 768px) and (max-width: 959px)',
				'important' => true,
			),
		),
		array(
			'id' => 'tablet_left_container_width',
			'transport' => 'postMessage',
			'control' => array(
				'label' => __( 'Content with Sidebar Width', 'total' ),
				'type' => 'text',
				'desc' => __( 'Example:', 'total' ) .' 100%',
			),
			'inline_css' => array(
				'target' => 'body.has-sidebar .content-area',
				'alter' => array( 'width', 'max-width' ),
				'media_query' => '(min-width: 768px) and (max-width: 959px)',
			),
		),
		array(
			'id' => 'tablet_sidebar_width',
			'transport' => 'postMessage',
			'control' => array(
				'label' => __( 'Sidebar Width', 'total' ),
				'type' => 'text',
				'desc' => __( 'Example:', 'total' ) .' 100%',
			),
			'inline_css' => array(
				'target' => '#sidebar',
				'alter' => array( 'width', 'max-width' ),
				'media_query' => '(min-width: 768px) and (max-width: 959px)',
			),
		),
	),
);

// Mobile Phone Widths
$this->sections['wpex_layout_phone_widths'] = array(
	'title' => __( 'Mobile Phone Widths', 'total' ),
	'panel' => 'wpex_layout',
	'desc' => __( 'For screens between 0 - 767px. Accepts both pixels or percentage values.', 'total' ),
	'settings' => array(
		array(
			'id' => 'mobile_portrait_main_container_width',
			'transport' => 'postMessage',
			'control' => array(
				'label' => __( 'Portrait: Main Container Width', 'total' ),
				'type' => 'text',
				'desc' => __( 'Example:', 'total' ) .' 90%',
			),
			'inline_css' => array(
				'target' => '.container',
				'alter' => array( 'width', 'max-width' ),
				'media_query' => '(max-width: 767px)',
				'important' => true,
			),
		),
		array(
			'id' => 'mobile_landscape_main_container_width',
			'transport' => 'postMessage',
			'control' => array(
				'label' => __( 'Landscape: Main Container Width', 'total' ),
				'type' => 'text',
				'desc' => __( 'Example:', 'total' ) .' 90%',
			),
			'inline_css' => array(
				'target' => '.container',
				'alter' => array( 'width', 'max-width' ),
				'media_query' => '(min-width: 480px) and (max-width: 767px)',
				'important' => true,
			),
		),
	),
);