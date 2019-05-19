<?php
/**
 * Newsletter widget
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
class NewsletterWidget extends WidgetBuilder {
	private $args;

	/**
	 * Register widget with WordPress.
	 *
	 * @since 1.0
	 */
	public function __construct() {

		$this->args = array(
			'id_base' => 'wpex_mailchimp',
			'name'    => $this->branding() . __( 'Newsletter Form', 'total' ),
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
					'id'    => 'heading',
					'label' => __( 'Heading', 'total' ),
					'type'  => 'text',
					'std'   => __( 'Newsletter', 'total' ),
				),
				array(
					'id'          => 'form_action',
					'label'       => __( 'Form Action URL', 'total' ),
					'type'        => 'text',
					'description' => '<a href="https://wpexplorer-themes.com/total/docs/mailchimp-form-action-url/" target="_blank">' . __( 'Learn more', 'total' ) . '&rarr;</a>',
				),
				array(
					'id'    => 'description',
					'label' => __( 'Description', 'total' ),
					'type'  => 'textarea',
				),
				array(
					'id'    => 'name_field',
					'label' => __( 'Display First Name Field?', 'total' ),
					'type'  => 'checkbox',
				),
				array(
					'id'      => 'name_placeholder_text',
					'label'   => __( 'First Name Placeholder Text', 'total' ),
					'type'    => 'text',
					'default' => __( 'First name', 'total' ),
				),
				array(
					'id'    => 'last_name_field',
					'label' => __( 'Display Last Name Field?', 'total' ),
					'type'  => 'checkbox',
				),
				array(
					'id'      => 'last_name_placeholder_text',
					'label'   => __( 'First Name Placeholder Text', 'total' ),
					'type'    => 'text',
					'default' => __( 'Last name', 'total' ),
				),
				array(
					'id'      => 'placeholder_text',
					'label'   => __( 'Email Input Placeholder Text', 'total' ),
					'type'    => 'text',
					'default' => __( 'Your email address', 'total' ),
				),
				array(
					'id'      => 'button_text',
					'label'   => __( 'Button Text', 'total' ),
					'type'    => 'text',
					'default' => __( 'Subscribe', 'total' ),
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

		$output .= '<div class="wpex-newsletter-widget wpex-clr">';

			// Display the heading
			if ( $heading ) {

				$output .= ' <h4 class="wpex-newsletter-widget-heading">' . wp_kses_post( $heading ) . '</h4>';

			}

			// Display the description
			if ( $description ) {

				$output .= '<div class="wpex-newsletter-widget-description">';

					$output .= wp_kses_post( $description );

				$output .= '</div>';

			}

			$output .= '<form action="'. esc_attr( $form_action ) .'" method="post" id="mc-embedded-subscribe-form" name="mc-embedded-subscribe-form" class="validate" target="_blank" novalidate>';

				// Name field
				if ( $name_field ) {

					$output .= '<label>';

						$output .= '<span class="screen-reader-text">' . esc_html( $name_placeholder_text ) . '</span>';

						$output .= '<input type="text" placeholder="' . esc_attr( $name_placeholder_text ) . '" name="FNAME" id="mce-FNAME" autocomplete="off">';

					$output .= '</label>';

				}

				// Lastname field
				if ( $last_name_field ) {

					$output .= '<label>';

						$output .= '<span class="screen-reader-text">' . esc_html( $last_name_placeholder_text ) . '</span>';

						$output .= '<input type="text" placeholder="' . esc_attr( $last_name_placeholder_text ) . '" name="LNAME" id="mce-LNAME" autocomplete="off">';

					$output .= '</label>';

				}

				$output .= '<label>';

					$output .= '<span class="screen-reader-text">' . esc_html( $placeholder_text ) . '</span>';

					$output .= '<input type="email" name="EMAIL" placeholder="' . esc_attr( $placeholder_text ) . '" autocomplete="off">';

				$output .= '</label>';

				$output .= apply_filters( 'wpex_mailchimp_widget_form_extras', null );

				$output .= '<button type="submit" value="" name="subscribe">' . strip_tags( $button_text ) . '</button>';

			$output .= '</form>';

		$output .= '</div>';

		// Echo output
		echo $output;

		// After widget hook
		echo wp_kses_post( $args['after_widget'] );

	}

}
register_widget( 'TotalTheme\NewsletterWidget' );