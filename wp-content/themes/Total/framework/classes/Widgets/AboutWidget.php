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
class AboutWidget extends WidgetBuilder {
	private $args;

	/**
	 * Register widget with WordPress.
	 *
	 * @since 1.0
	 */
	public function __construct() {

		$this->args = array(
			'id_base' => 'wpex_about',
			'name' => $this->branding() . __( 'About', 'total' ),
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
					'id'    => 'image',
					'label' => __( 'Image', 'total' ),
					'type'  => 'media_upload',
				),
				array(
					'id'      => 'img_size',
					'label'   => __( 'Image Size', 'total' ),
					'type'    => 'select',
					'choices' => 'intermediate_image_sizes',
					'exclude_custom' => true,
				),
				array(
					'id'      => 'img_style',
					'label'   => __( 'Image Style', 'total' ),
					'type'    => 'select',
					'choices' => array(
						'plain'   => __( 'Plain', 'total' ),
						'rounded' => __( 'Rounded', 'total' ),
						'round'   => __( 'Round', 'total' ),
					),
					'default' => 'plain',
				),
				array(
					'id'      => 'alignment',
					'label'   => __( 'Alignment', 'total' ),
					'type'    => 'select',
					'choices' => array(
						''       => __( 'Default', 'total' ),
						'left'   => __( 'Left', 'total' ),
						'center' => __( 'Center', 'total' ),
						'right'  => __( 'Right', 'total' ),
					),
				),
				array(
					'id'    => 'description',
					'label' => __( 'Description', 'total' ),
					'type'  => 'textarea',
				),
				array(
					'id'    => 'wpautop',
					'label' => __( 'Automatically add paragraphs', 'total' ),
					'type'  => 'checkbox',
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

		// Wrap classes
		$classes = 'wpex-about-widget wpex-clr';
		if ( $alignment ) {
			$classes .= ' text' . $alignment;
		}

		// Begin widget wrap
		$output .= '<div class="' . $classes . '">';

		// Sanitize image
		if ( is_numeric( $image ) ) {
			$img_size = $img_size ? $img_size : 'full';
			$image    = wp_get_attachment_image_url( $image, $img_size );
		}

		// Display the image
		if ( $image ) {

			// Image classes
			$img_class = ( 'round' == $img_style || 'rounded' == $img_style ) ? ' class="wpex-' . $img_style . '"' : '';

			$output .= '<div class="wpex-about-widget-image">';

				$output .= '<img src="' . esc_url( $image ) . '" alt="' . esc_attr( $title ) . '"' . $img_class . ' />';

			$output .= '</div>';

		}

		// Display the description
		if ( $description ) {

			$output .= '<div class="wpex-about-widget-description wpex-clr">';

				if ( 'on' == $wpautop ) {
					$output .= wpautop( wp_kses_post( $description ) );
				} else {
					$output .= wp_kses_post( $description );
				}

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
register_widget( 'TotalTheme\AboutWidget' );