<?php
/**
 * Customizer => Top Bar
 *
 * @package Total WordPress Theme
 * @subpackage Customizer
 * @version 4.5.5
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// General
$this->sections['wpex_topbar_general'] = array(
	'title' => __( 'General', 'total' ),
	'panel' => 'wpex_topbar',
	'settings' => array(
		array(
			'id' => 'top_bar',
			'default' => true,
			'control' => array(
				'label' => __( 'Enable', 'total' ),
				'type' => 'checkbox',
				'desc' => __( 'If you disable this option we recommend you go to the Customizer Manager and disable the section as well so the next time you work with the Customizer it will load faster.', 'total' ),
			),
		),
		array(
			'id' => 'top_bar_sticky',
			'default' => false,
			'transport' => 'postMessage',
			'control' => array(
				'label' => __( 'Sticky', 'total' ),
				'type' => 'checkbox',
				'desc' => __( 'Disabled in Customizer for optimization reasons. Please save and test live site.', 'total' ),
			),
			'control_display' => array(
				'check' => 'top_bar',
				'value' => 'true',
			),
		),
		array(
			'id' => 'top_bar_sticky_mobile',
			'default' => true,
			'transport' => 'postMessage',
			'control' => array(
				'label' => __( 'Sticky Mobile Support', 'total' ),
				'type' => 'checkbox',
				'desc' => __( 'Disabled in Customizer for optimization reasons. Please save and test live site.', 'total' ),
			),
			'control_display' => array(
				'check' => 'top_bar',
				'value' => 'true',
			),
		),
		array(
			'id' => 'top_bar_fullwidth',
			'default' => false,
			'transport' => 'postMessage',
			'control' => array(
				'label' => __( 'Full-Width', 'total' ),
				'type' => 'checkbox',
				'desc' => __( 'Not used in Boxed style layout.', 'total' ),
			),
			'control_display' => array(
				'check' => 'top_bar',
				'value' => 'true',
			),
		),
		array(
			'id' => 'top_bar_visibility',
			'transport' => 'postMessage',
			'default' => 'always-visible',
			'control' => array(
				'label' => __( 'Visibility', 'total' ),
				'type' => 'select',
				'choices' => $choices_visibility,
			),
			'control_display' => array(
				'check' => 'top_bar',
				'value' => 'true',
			),
		),
		array(
			'id' => 'top_bar_style',
			'transport' => 'partialRefresh',
			'default' => 'one',
			'control' => array(
				'label' => __( 'Style', 'total' ),
				'type' => 'select',
				'choices' => array(
					'one' => __( 'Left Content & Right Social', 'total' ),
					'two' => __( 'Left Social & Right Content', 'total' ),
					'three' => __( 'Centered Content & Social', 'total' ),
				),
			),
			'control_display' => array(
				'check' => 'top_bar',
				'value' => 'true',
			),
		),
		// main styling
		array(
			'id' => 'top_bar_bg',
			'transport' => 'postMessage',
			'control' => array(
				'type' => 'color',
				'label' => __( 'Background', 'total' ),
			),
			'control_display' => array(
				'check' => 'top_bar',
				'value' => 'true',
			),
			'inline_css' => array(
				'target' => array(
					'#top-bar-wrap',
					'.wpex-top-bar-sticky',
				),
				'alter' => 'background-color',
			),
		),
		array(
			'id' => 'top_bar_border',
			'transport' => 'postMessage',
			'control' => array(
				'type' => 'color',
				'label' => __( 'Borders', 'total' ),
			),
			'control_display' => array(
				'check' => 'top_bar',
				'value' => 'true',
			),
			'inline_css' => array(
				'target' => '#top-bar-wrap',
				'alter' => 'border-color',
			),
		),
		array(
			'id' => 'top_bar_text',
			'transport' => 'postMessage',
			'control' => array(
				'type' => 'color',
				'label' => __( 'Color', 'total' ),
			),
			'control_display' => array(
				'check' => 'top_bar',
				'value' => 'true',
			),
			'inline_css' => array(
				'target' => array(
					'#top-bar-wrap',
					'#top-bar-content strong',
				),
				'alter' => 'color',
			),
		),
		// link colors
		array(
			'id' => 'top_bar_link_color',
			'transport' => 'postMessage',
			'control' => array(
				'type' => 'color',
				'label' => __( 'Link Color', 'total' ),
			),
			'control_display' => array(
				'check' => 'top_bar',
				'value' => 'true',
			),
			'inline_css' => array(
				'target' => array(
					'#top-bar-content a',
					'#top-bar-social-alt a',
				),
				'alter' => 'color',
			),
		),
		array(
			'id' => 'top_bar_link_color_hover',
			'transport' => 'postMessage',
			'control' => array(
				'type' => 'color',
				'label' => __( 'Link Color: Hover', 'total' ),
			),
			'control_display' => array(
				'check' => 'top_bar',
				'value' => 'true',
			),
			'inline_css' => array(
				'target' => array(
					'#top-bar-content a:hover',
					'#top-bar-social-alt a:hover',
				),
				'alter' => 'color',
			),
		),
		// Padding
		array(
			'id' => 'top_bar_top_padding',
			'transport' => 'postMessage',
			'control' => array(
				'type' => 'text',
				'label' => __( 'Top Padding', 'total' ),
				'description' => $pixel_desc,
			),
			'control_display' => array(
				'check' => 'top_bar',
				'value' => 'true',
			),
			'inline_css' => array(
				'target' => '#top-bar',
				'alter' => 'padding-top',
				'sanitize' => 'px',
			),
		),
		array(
			'id' => 'top_bar_bottom_padding',
			'transport' => 'postMessage',
			'control' => array(
				'type' => 'text',
				'label' => __( 'Bottom Padding', 'total' ),
				'description' => $pixel_desc,
			),
			'control_display' => array(
				'check' => 'top_bar',
				'value' => 'true',
			),
			'inline_css' => array(
				'target' => '#top-bar',
				'alter' => 'padding-bottom',
				'sanitize' => 'px',
			),
		),
	),
);

/*-----------------------------------------------------------------------------------*/
/* - TopBar => Content
/*-----------------------------------------------------------------------------------*/
$this->sections['wpex_topbar_content'] = array(
	'title' => __( 'Content', 'total' ),
	'panel' => 'wpex_topbar',
	'settings' => array(
		array(
			'id' => 'top_bar_content',
			'transport' => 'partialRefresh',
			'default' => '<span class="wpex-inline">[font_awesome icon="phone"] 1-800-987-654</span>

<span class="wpex-inline">[font_awesome icon="envelope"] admin@totalwptheme.com</span>

<span class="wpex-inline">[font_awesome icon="user"] [wp_login_url text="User Login" logout_text="Logout"]</span>',
			'control' => array(
				'label' => __( 'Content', 'total' ),
				'type' => 'wpex-textarea',
				'rows' => 25,
				'description' => $post_id_content_desc,
			),
			'control_display' => array(
				'check' => 'top_bar',
				'value' => 'true',
			),
		),
	),
);

/*-----------------------------------------------------------------------------------*/
/* - TopBar => Social
/*-----------------------------------------------------------------------------------*/
$this->sections['wpex_topbar_social'] = array(
	'title' => __( 'Social', 'total' ),
	'panel' => 'wpex_topbar',
	'settings' => array(
		array(
			'id' => 'top_bar_social_alt',
			'transport' => 'partialRefresh',
			'control' => array(
				'label' => __( 'Social Alternative', 'total' ),
				'type' => 'textarea',
				'description' => __( 'Replaces the social links with your custom output.', 'total' ) . $post_id_content_desc,
			),
			'control_display' => array(
				'check' => 'top_bar',
				'value' => 'true',
			),
		),
		array(
			'id' => 'top_bar_social',
			'default' => true,
			'transport' => 'refresh', // Other items relly on this conditionally to show/hide
			'control' => array(
				'label' => __( 'Social', 'total' ),
				'type' => 'checkbox',
			),
			'control_display' => array(
				'check' => 'top_bar',
				'value' => 'true',
			),
		),
		array(
			'id' => 'top_bar_social_target',
			'default' => 'blank',
			'transport' => 'postMessage', // Doesn't need any js because you can't click links in the customizer anyway
			'control' => array(
				'label' => __( 'Social Link Target', 'total' ),
				'type' => 'select',
				'choices' => array(
					'blank' => __( 'New Window', 'total' ),
					'self' => __( 'Same Window', 'total' ),
				),
				'active_callback' => 'wpex_cac_has_topbar_social',
			),
		),
		array(
			'id' => 'top_bar_social_style',
			'default' => 'none',
			'transport' => 'partialRefresh',
			'control' => array(
				'label' => __( 'Social Style', 'total' ),
				'type' => 'select',
				'choices' => $social_styles,
				'active_callback' => 'wpex_cac_has_topbar_social',
			),
		),
		array(
			'id' => 'top_bar_social_color',
			'transport' => 'postMessage',
			'control' => array(
				'type' => 'color',
				'label' => __( 'Social Links Color', 'total' ),
				'description' => __( 'Applied only when the social style is set to "none".', 'total' ),
				'active_callback' => 'wpex_cac_has_topbar_social',
			),
			'inline_css' => array(
				'target' => '#top-bar-social a.wpex-social-btn-no-style',
				'alter' => 'color',
			),
		),
		array(
			'id' => 'top_bar_social_hover_color',
			'transport' => 'postMessage',
			'control' => array(
				'type' => 'color',
				'label' => __( 'Social Links Hover Color', 'total' ),
				'description' => __( 'Applied only when the social style is set to "none".', 'total' ),
				'active_callback' => 'wpex_cac_has_topbar_social',
			),
			'inline_css' => array(
				'target' => '#top-bar-social a.wpex-social-btn-no-style:hover',
				'alter' => 'color',
			),
		),
	),
);

// Social settings
$social_options = wpex_topbar_social_options();
foreach ( $social_options as $key => $val ) {
	$this->sections['wpex_topbar_social']['settings'][] = array(
		'id' => 'top_bar_social_profiles[' . $key .']',
		'transport' => 'partialRefresh',
		'sanitize_callback' => 'esc_html',
		'control' => array(
			'label' => esc_html( $val['label'], 'total' ),
			'type' => 'text',
			'active_callback' => 'wpex_cac_has_topbar_social',
		),
	);
}