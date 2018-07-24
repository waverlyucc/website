<?php
/**
 * Adds custom templates to VC for use
 *
 * @package Total WordPress Theme
 * @subpackage Visual Composer
 * @version 4.6
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Add default templates
function wpex_vc_default_templates() {

	/* Under Construction 1 */
	$data = array(
		'name'         => esc_html__( 'Under Construction 1', 'total' ),
		'weight'       => 0,
		'custom_class' => 'total-custom-vc-template',
		'content'      => '[vc_row full_width="stretch_row" full_height="yes" css=".vc_custom_1518549121179{background-color: #9ecdab !important;}" background="#9ecdab"][vc_column][vcex_divider color="#ffffff" icon="fa fa-star-o" width="60%" icon_color="#ffffff" icon_size="28px"][vcex_heading text="UNDER CONSTRUCTION" responsive_text="true" text_align="center" font_family="Monoton" font_size="60px" color="#ffffff" min_font_size="28px"][vcex_heading text="Something awesome this way comes. Stay tuned!" responsive_text="true" text_align="center" font_size="24px" min_font_size="16px" color="#ffffff" line_height="1.3"][vcex_spacing][vcex_newsletter_form mailchimp_form_action="http://wpexplorer.us1.list-manage1.com/subscribe/post?u=9b7568b7c032f9a6738a9cf4d&id=7056c37ddf" placeholder_text="Enter your email" submit_text="Submit" input_bg="#ffffff" input_color="#a3c9b2" input_border_radius="40px" input_font_size="16px" input_border="none" input_letter_spacing="1px" input_width="380px" submit_border_radius="40px" submit_bg="#a3c9b2" submit_hover_bg="#88a88f" input_padding="0px 20px" submit_position_right="20px"][vcex_spacing][vcex_social_links style="none" link_target="blank" align="center" twitter="https://twitter.com/WPExplorer" facebook="https://www.facebook.com/WPExplorerThemes/" googleplus="https://plus.google.com/+Wpexplorer" pinterest="https://www.pinterest.com/wpexplorer/" dribbble="https://dribbble.com/aj-clarke" size="24px" color="#ffffff" hover_color="#d8e7c6"][/vc_column][/vc_row]'
	);
	
	vc_add_default_templates( $data );

}
add_action( 'vc_load_default_templates_action', 'wpex_vc_default_templates' );