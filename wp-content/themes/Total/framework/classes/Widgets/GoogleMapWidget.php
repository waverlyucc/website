<?php
/**
 * Google Map widget
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
class GoogleMapWidget extends WidgetBuilder {
	private $args;

	/**
	 * Register widget with WordPress.
	 *
	 * @since 1.0
	 */
	public function __construct() {

		$this->args = array(
			'id_base' => 'wpex_gmap_widget',
			'name'    => $this->branding() . __( 'Google Map', 'total' ),
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
					'id'    => 'description',
					'label' => __( 'Description', 'total' ),
					'type'  => 'textarea',
				),
				array(
					'id'       => 'embed_code',
					'label'    => __( 'Embed Code', 'total' ),
					'type'     => 'textarea',
					'sanitize' => 'google_map',
				),
				array(
					'id'    => 'height',
					'label' => __( 'Height', 'total' ),
					'type'  => 'text',
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

		// Define widget output
		$output = '';

		$output .= '<div class="wpex-gmap-widget wpex-clr">';

			if ( $description ) {

				$output .= '<div class="wpex-gmap-widget-description wpex-clr">';

					$output .= wpautop( wp_kses_post( $description ) );

				$output .= '</div>';

			}

			if ( $embed_code ) {

				// Parse size
				if ( $height && is_numeric( $height ) ) {

					$embed_code = preg_replace( '/height="[0-9]*"/', 'height="' . $height . '"', $embed_code );

				}

				$output .= '<div class="wpex-gmap-widget-embed wpex-clr">';

					$output .= do_shortcode( wpex_sanitize_data( $embed_code, 'google_map' ) );

				$output .= '</div>';

			}

		$output .= '</div>';

		// Echo output
		echo $output;

		// After widget hook
		echo wp_kses_post( $args['after_widget'] );

	}

}
register_widget( 'TotalTheme\GoogleMapWidget' );