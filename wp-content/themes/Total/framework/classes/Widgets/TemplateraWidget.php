<?php
/**
 * Templatera widget
 *
 * @package Total WordPress Theme
 * @subpackage Widgets
 * @version 4.8.4
 */

namespace TotalTheme;

// Prevent direct file access
if ( ! defined ( 'ABSPATH' ) ) {
	exit;
}

add_filter( 'wpex_templatera_widget_content', '\wptexturize'                       );
add_filter( 'wpex_templatera_widget_content', '\convert_smilies', 20               );
add_filter( 'wpex_templatera_widget_content', '\convert_chars'                     );
add_filter( 'wpex_templatera_widget_content', '\wpautop'                           );
add_filter( 'wpex_templatera_widget_content', '\shortcode_unautop'                 );
add_filter( 'wpex_templatera_widget_content', '\do_shortcode', 11                  ); // runs after wpautop and shortcode_unautop
add_filter( 'wpex_templatera_widget_content', '\prepend_attachment'                );
add_filter( 'wpex_templatera_widget_content', '\wp_make_content_images_responsive' );

// Start class
class TemplateraWidget extends WidgetBuilder {
	private $args;

	/**
	 * Register widget with WordPress.
	 *
	 * @since 1.0
	 */
	public function __construct() {

		$this->args = array(
			'id_base' => 'wpex_templatera',
			'name'    => $this->branding() . __( 'Templatera', 'total' ),
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
					'id'        => 'template',
					'label'     => __( 'Template', 'total' ),
					'type'      => 'select',
					'choices'   => 'posts',
					'post_type' => 'templatera',
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

		// Get template content
		$temp_post = $template ? get_post( $template ) : '';

		if ( $temp_post ) {

			// Add inline styles
			$custom_css = esc_attr( get_post_meta( $template, '_wpb_shortcodes_custom_css', true ) );

			if ( ! empty( $custom_css ) ) {

				$output .= '<style data-type="vc_shortcodes-custom-css">';

					$output .= wpex_minify_css( $custom_css );

				$output .= '</style>';

			}

			// Output html
			$output .= '<div class="wpex-templatera-widget-content clr">' . apply_filters( 'wpex_templatera_widget_content', $temp_post->post_content ) . '</div>';

		}

		// Echo output
		echo $output;

		// After widget hook
		echo wp_kses_post( $args['after_widget'] );

	}

}
register_widget( 'TotalTheme\TemplateraWidget' );