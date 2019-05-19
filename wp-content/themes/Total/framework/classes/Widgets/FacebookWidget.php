<?php
/**
 * Facebook Page widget
 *
 * @package Total WordPress Theme
 * @subpackage Widgets
 * @version 4.8
 */

namespace TotalTheme;

// Prevent direct file access
if ( ! defined ( 'ABSPATH' ) ) {
	exit;
}

// Start class
class FacebookWidget extends WidgetBuilder {
	private $args;

	/**
	 * Register widget with WordPress.
	 *
	 * @since 1.0'
	 */
	public function __construct() {

		$this->args = array(
			'id_base' => 'wpex_facebook_page_widget',
			'name'    => $this->branding() . __( 'Facebook Page', 'total' ),
			'options' => array(
				'customize_selective_refresh' => true,
			),
			'fields'  => array(
				array(
					'id'    => 'title',
					'label' => __( 'Title', 'total' ),
					'type'  => 'text',
				),
				array(
					'id'    => 'facebook_url',
					'label' => __( 'Facebook Page URL', 'total' ),
					'type'  => 'text',
					'std'   => ''
				),
				array(
					'id'      => 'language',
					'label'   => __( 'Language Locale', 'total' ),
					'type'    => 'text',
					'default' => 'en_US'
				),
				array(
					'id'      => 'tabs',
					'label'   => __( 'Tabs', 'total' ),
					'type'    => 'select',
					'choices' => array(
						''                => __( '— None —', 'total' ),
						'timeline'        => __( 'Timeline', 'total' ),
						'events'          => __( 'Events', 'total' ),
						'timeline,events' => __( 'Timeline & Events', 'total' ),
					),
				),
				array(
					'id'    => 'small_header',
					'label' => __( 'Use small header', 'total' ),
					'type'  => 'checkbox',
				),
				array(
					'id'    => 'hide_cover',
					'label' => __( 'Hide Cover Photo', 'total' ),
					'type'  => 'checkbox',
				),
				array(
					'id'      => 'show_facepile',
					'label'   => __( 'Show Faces', 'total' ),
					'type'    => 'checkbox',
					'default' => 'on',
				),
			),
		);

		$this->create_widget( $this->args );

	}

	/**
	 * Front-end display of widget.
	 *
	 * @see WP_Widget::widget()
	 * @since 1.0
	 *
	 * @param array $args     Widget arguments.
	 * @param array $instance Saved values from database.
	 */
	public function widget( $args, $instance ) {

		// Parse and extract widget settings
		extract( $this->parse_instance( $instance ) );

		// Before widget hook
		echo wp_kses_post( $args['before_widget'] );

		// Display widget title
		$this->widget_title( $args, $instance );

		// Show nothing in customizer to keep it fast
		if ( function_exists( 'is_customize_preview' ) && is_customize_preview() ) {

			esc_html_e( 'Facebook widget does not display in the Customizer because it can slow things down.', 'total' );

		} elseif ( $facebook_url ) {

			$attrs = array(
				'class'                      => 'fb-page',
				'data-href'                  => esc_url( do_shortcode( $facebook_url ) ),
				'data-small-header'          => esc_attr( $small_header ),
				'data-adapt-container-width' => 'true',
				'data-hide-cover'            => esc_attr( $hide_cover ),
				'data-show-facepile'         => esc_attr( $show_facepile ),
				'data-width'                 => 500,
			);

			if ( $tabs ) {
				$attrs['data-tabs'] = $tabs;
			}

			echo wpex_parse_html( 'div', $attrs ); ?>

			<div id="fb-root"></div>
			<script>(function(d, s, id) {
				var js, fjs = d.getElementsByTagName(s)[0];
				if (d.getElementById(id)) return;
				js = d.createElement(s); js.id = id;
				js.async=true; js.src = "//connect.facebook.net/<?php echo esc_html( $language ); ?>/sdk.js#xfbml=1&version=v2.5&appId=944726105603358";
				fjs.parentNode.insertBefore(js, fjs);
			} ( document, 'script', 'facebook-jssdk' ) );</script>

		<?php }

		// After widget hook
		echo wp_kses_post( $args['after_widget'] );

	}

}
register_widget( 'TotalTheme\FacebookWidget' );