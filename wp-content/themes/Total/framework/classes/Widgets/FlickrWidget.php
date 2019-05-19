<?php
/**
 * About widget
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
class FlickrWidget extends WidgetBuilder {
	private $args;

	/**
	 * Register widget with WordPress.
	 *
	 * @since 1.0
	 */
	public function __construct() {

		$this->args = array(
			'id_base' => 'wpex_flickr',
			'name'    => $this->branding() . __( 'Flickr', 'total' ),
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
					'id'    => 'id',
					'label' => __( 'Flickr ID', 'total' ),
					'type'  => 'text',
				),
				array(
					'id'      => 'display',
					'label'   => __( 'Display', 'total' ),
					'type'    => 'select',
					'choices' => array(
						'latest' => __( 'Latest', 'total' ),
						'random' => __( 'Random', 'total' ),
					),
					'default' => 'latest',
				),
				array(
					'id'      => 'number',
					'label'   => __( 'Number', 'total' ),
					'type'    => 'number',
					'default' => 8,
				),
				array(
					'id'          => 'tag',
					'label'       => __( 'Tags', 'total' ),
					'type'        => 'text',
					'description' => __( 'Enter a comma seperated list of tags to include.', 'total' ),
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

		// Display flickr feed if ID is defined
		if ( $id ) {

			$output .= '<div class="wpex-flickr-widget">';

			$url_args = array(
				'count'   => $number,
				'display' => $display,
				'size'    => 's',
				'layout'  => 'x',
				'source'  => 'user',
				'user'    => $id,
			);

			if ( ! empty( $tag ) ) {
				$url_args['tag']    = $tag;
				$url_args['source'] = 'user_tag';
			}

			$url = esc_url( add_query_arg( $url_args, 'https://www.flickr.com/badge_code_v2.gne' ) );

			$output .= '<script src="' . $url . '"></script>';

			$output .= '</div>';

		}

		// Close widget wrap
		$output .= '</div>';

		// Echo output
		echo $output;

		// After widget hook
		echo wp_kses_post( $args['after_widget'] );

	}

}
register_widget( 'TotalTheme\FlickrWidget' );