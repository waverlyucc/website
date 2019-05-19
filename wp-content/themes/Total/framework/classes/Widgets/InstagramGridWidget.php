<?php
/**
 * Instagram Grid widget
 *
 * @package Total WordPress Theme
 * @subpackage Widgets
 * @version 4.8.3
 */

namespace TotalTheme;

// Prevent direct file access
if ( ! defined ( 'ABSPATH' ) ) {
	exit;
}

// Start class
class InstagramGridWidget extends WidgetBuilder {
	private $args;

	/**
	 * Register widget with WordPress.
	 *
	 * @since 1.0
	 */
	public function __construct() {

		$this->args = array(
			'id_base' => 'wpex_insagram_slider',
			'name'    => $this->branding() . __( 'Instagram Grid', 'total' ),
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
					'id'    => 'username',
					'label' => __( 'Username', 'total' ),
					'type'  => 'text',
					'description' => __( 'Important: The Instagram feed is refreshed every 2 hours to prevent your site from slowing down.', 'total' ),
				),
				array(
					'id'      => 'size',
					'label'   => __( 'Size', 'total' ),
					'type'    => 'select',
					'choices' => array(
						'thumbnail' => __( 'Thumbnail', 'total' ),
						'small'     => __( 'Small', 'total' ),
						'large'     => __( 'Large', 'total' ),
						'original'  => __( 'Original', 'total' ),
					),
					'default' => 'thumbnail',
				),
				array(
					'id'      => 'columns',
					'label'   => __( 'Columns', 'total' ),
					'type'    => 'select',
					'choices' => 'grid_columns',
					'default' => '3',
				),
				array(
					'id'      => 'gap',
					'label'   => __( 'Gap', 'total' ),
					'type'    => 'select',
					'choices' => 'grid_gaps',
					'default' => '10',
				),
				array(
					'id'    => 'responsive',
					'label' => __( 'Responsive', 'total' ),
					'type'  => 'checkbox',
					'std'   => 'on',
				),
				array(
					'id'          => 'number',
					'label'       => __( 'Number', 'total' ),
					'type'        => 'number',
					'default'     => 9,
					'description' => __( 'Max 12 items.', 'total' ),
				),
				array(
					'id'      => 'target',
					'label'   => __( 'Open links in', 'total' ),
					'type'    => 'select',
					'choices' => 'link_target',
					'default' => '_self',
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

		// Display notice for username not added
		if ( ! $username ) {

			$output .= '<p>' . esc_html__( 'Please enter an instagram username for your widget.', 'total' ) . '</p>';

		} else {

			// Get instagram images
			$media_array = wpex_fetch_instagram_feed( $username, $number );

			// Display error message
			if ( is_wp_error( $media_array ) ) {

				$output .= strip_tags( $media_array->get_error_message() );

			}

			// Display instagram feed
			elseif ( is_array( $media_array ) ) {

				$target = ( 'blank' == $target || '_blank' == $target ) ? ' target="_blank"' : '';

				$output .= '<div class="wpex-instagram-grid-widget wpex-clr">';

					$output .= '<ul class="wpex-clr wpex-row gap-' . esc_attr( $gap ) . '">';

					$count = 0;

					foreach ( $media_array as $item ) {

						$image = isset( $item['display_src'] ) ? $item['display_src'] : '';

						if ( 'thumbnail' == $size ) {
							$image = ! empty( $item['thumbnail_src'] ) ? $item['thumbnail_src'] : $image;
							$image = ! empty( $item['thumbnail'] ) ? $item['thumbnail'] : $image;
						} elseif ( 'small' == $size ) {
							$image = ! empty( $item['small'] ) ? $item['small'] : $image;
						} elseif ( 'large' == $size ) {
							$image = ! empty( $item['large'] ) ? $item['large'] : $image;
						} elseif ( 'original' == $size ) {
							$image = ! empty( $item['original'] ) ? $item['original'] : $image;
						}

						if ( $image ) {

							$count++;

							if ( strpos( $item['link'], 'http' ) === false ) {
								$item['link'] = str_replace( '//', 'https://', $item['link'] );
							}

							$classes = wpex_grid_class( $columns ) . ' clr count-' . esc_attr( $count );

							if ( $responsive && 'false' !== $responsive ) {
								$classes .= ' col';
							} else {
								$classes .= ' nr-col';
							}

							$output .= '<li class="' . $classes . '">';

								$output .= '<a href="' . esc_url( $item['link'] ) . '" title="' . esc_attr( $item['description'] ) . '"' . $target . '>';

										$output .= '<img src="' . esc_url( $image ) . '"  alt="' . esc_attr( $item['description'] ) . '" />';

									$output .= '</a>';

								$output .= '</li>';

							if ( $columns == $count ) {
								$count = 0;
							}

						}
					}

					$output .= '</ul>';

				$output .= '</div>';

			}

		}

		// Echo output
		echo $output;

		// After widget hook
		echo wp_kses_post( $args['after_widget'] );

	}

}
register_widget( 'TotalTheme\InstagramGridWidget' );