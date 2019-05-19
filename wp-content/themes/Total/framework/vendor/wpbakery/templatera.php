<?php
/**
 * Visual Composer Templatera tweaks
 *
 * @package Total WordPress Theme
 * @subpackage Visual Composer
 * @version 4.4
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function wpex_templatera_remove_notices() {
	remove_action( 'admin_notices', 'templatera_notice' );
}
add_action( 'init', 'wpex_templatera_remove_notices' );

function wpex_add_templatera_front_end_button() {
	if ( ! function_exists( 'vc_frontend_editor' ) ) {
		return;
	}
	global $pagenow;
	$template_edit = 'post.php' == $pagenow && isset( $_GET['post'] ) && 'templatera' === get_post_type( $_GET['post'] );
	if ( ! $template_edit ) {
		return;
	}
	$front_end_url = vc_frontend_editor()->getInlineUrl(); ?>
	<script>
		( function ( $ ) {
			if ( typeof vc !== 'undefined' ) {
				vc.events.on( 'vc:access:backend:ready', function ( access ) {
					var vcSwitch = $( '.composer-switch' );
					if ( vcSwitch.length ) {
						vcSwitch.append( '<span class="vc_spacer"></span><a class="wpb_switch-to-front-composer" href="<?php echo $front_end_url; ?>">' + window.i18nLocale.main_button_title_frontend_editor + '</a>' );
					}
				} );
			}
		} ) ( window.jQuery );
	</script>
<?php }
add_action( 'admin_print_footer_scripts', 'wpex_add_templatera_front_end_button', 9999 );

function wpex_register_templatera() {
	register_post_type( 'templatera' );
}

function wpex_templatera_filter_post_type_args( $args, $post_type ) {
	if ( $post_type == 'templatera' ) {
		//$args['supports'] = array( 'title', 'editor', 'revisions' );
		$args['public']             = true;
		$args['publicly_queryable'] = true;
		$args['map_meta_cap']       = true;
	}
	return $args;
}

if ( wpex_vc_is_inline() ) {
	add_filter( 'register_post_type_args', 'wpex_templatera_filter_post_type_args', 10, 2 );
	add_action( 'init', 'wpex_register_templatera', 0 );
}