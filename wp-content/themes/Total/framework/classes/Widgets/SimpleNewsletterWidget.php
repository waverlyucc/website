<?php
/**
 * Minimal Newsletter widget
 *
 * @package Total WordPress Theme
 * @subpackage Widgets
 * @since 4.8
 * @version 4.8
 */

namespace TotalTheme;

// Prevent direct file access
if ( ! defined ( 'ABSPATH' ) ) {
	exit;
}

// Start class
class SimpleNewsletterWiget extends WidgetBuilder {
	private $args;

	/**
	 * Register widget with WordPress.
	 *
	 * @since 1.0
	 */
	public function __construct() {

		$this->args = array(
			'id_base' => 'wpex_newsletter',
			'name'    => $this->branding() . __( 'Newsletter Form (Minimal)', 'total' ),
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
					'id'          => 'form_action',
					'label'       => __( 'Form Action URL', 'total' ),
					'type'        => 'text',
					'description' => '<a href="https://wpexplorer-themes.com/total/docs/mailchimp-form-action-url/" target="_blank">' . __( 'Learn more', 'total' ) . '&rarr;</a>',
				),
				array(
					'id'      => 'placeholder_text',
					'label'   => __( 'Placeholder Text', 'total' ),
					'type'    => 'text',
					'default' => __( 'Your email address', 'total' ),
				),
				array(
					'id'      => 'button_text',
					'label'   => __( 'Button Text', 'total' ),
					'type'    => 'text',
					'default' => __( 'Sign Up', 'total' ),
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

		$output .= '<form action="'. esc_attr( $form_action ) .'" method="post" class="validate" target="_blank" novalidate>';

			$output .= '<span class="screen-reader-text">' . esc_html( $placeholder_text ) . '</span>';

			$output .= '<input type="email" name="EMAIL" placeholder="' . esc_attr( $placeholder_text ) . '" autocomplete="off">';

			$output .= apply_filters( 'wpex_newsletter_widget_form_extras', null );

			$output .= '<button type="submit" value="" name="subscribe">' . strip_tags( $button_text ) . '</button>';

		$output .= '</form>';

		// Echo output
		echo $output;

		// After widget hook
		echo wp_kses_post( $args['after_widget'] );

	}

}
register_widget( 'TotalTheme\SimpleNewsletterWiget' );