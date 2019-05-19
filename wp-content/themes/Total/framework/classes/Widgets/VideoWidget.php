<?php
/**
 * Video widget
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
class VideoWidget extends WidgetBuilder {
	private $args;

	/**
	 * Register widget with WordPress.
	 *
	 * @since 1.0
	 */
	public function __construct() {

		$this->args = array(
			'id_base' => 'wpex_video',
			'name'    => $this->branding() . __( 'Video', 'total' ),
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
					'id'          => 'video_url',
					'label'       => __( 'Video URL', 'total' ),
					'type'        => 'url',
					'description' => __( 'Enter in a video URL that is compatible with WordPress\'s built-in oEmbed feature.', 'total' ) . '<a href="http://codex.wordpress.org/Embeds" target="_blank">' . __( 'Learn More', 'total' ) . '</a></span>'
				),
				array(
					'id'    => 'video_description',
					'label' => __( 'Description', 'total' ),
					'type'  => 'textarea',
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

		// Show video
		if ( $video_url )  {

			$output .= '<div class="responsive-video-wrap clr">';

				$output .= wp_oembed_get( $video_url, array(
					'width' => 270
				) );

			$output .= '</div>';

		} else {

			$output .= esc_html__( 'You forgot to enter a video URL.', 'total' );

		}

		// Show video description if field isn't empty
		if ( $video_description ) {

			$output .= '<div class="wpex-video-widget-description">' . wp_kses_post( $video_description ) . '</div>';

		}

		// Echo output
		echo $output;

		// After widget hook
		echo wp_kses_post( $args['after_widget'] );

	}

}
register_widget( 'TotalTheme\VideoWidget' );