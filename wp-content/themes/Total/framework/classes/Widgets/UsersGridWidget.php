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
class UsersGridWiget extends WidgetBuilder {
	private $args;

	/**
	 * Register widget with WordPress.
	 *
	 * @since 1.0
	 */
	public function __construct() {

		$this->args = array(
			'id_base' => 'wpex_users_grid',
			'name' => $this->branding() . __( 'Users', 'total' ),
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
					'id'          => 'class',
					'label'       => __( 'Custom Class', 'total' ),
					'type'        => 'text',
					'description' => __( 'Optional classname for styling purposes.', 'total' ),
				),
				array(
					'id'      => 'order',
					'label'   => __( 'Order', 'total' ),
					'type'    => 'select',
					'choices' => 'query_order',
					'default' => 'ASC',
				),
				array(
					'id'      => 'orderby',
					'label'   => __( 'Orderby', 'total' ),
					'type'    => 'select',
					'choices' => array(
						'ID'           => esc_html__( 'ID', 'total' ),
						'login'        => esc_html__( 'Login', 'total' ),
						'nicename'     => esc_html__( 'Nicename', 'total' ),
						'email'        => esc_html__( 'Email', 'total' ),
						'url'          => esc_html__( 'URL', 'total' ),
						'registered'   => esc_html__( 'Registered', 'total' ),
						'display_name' => esc_html__( 'Display Name', 'total' ),
						'post_count'   => esc_html__( 'Post Count', 'total' ),
					),
					'default' => 'login',
				),
				array(
					'id'      => 'columns',
					'label'   => __( 'Columns', 'total' ),
					'type'    => 'select',
					'choices' => 'grid_columns',
					'default' => '4',
				),
				array(
					'id'      => 'columns_gap',
					'label'   => __( 'Column Gap', 'total' ),
					'type'    => 'select',
					'choices' => 'grid_gaps',
					'default' => '10',
				),
				array(
					'id'      => 'img_size',
					'label'   => __( 'Image Size', 'total' ),
					'type'    => 'text',
					'default' => '70',
				),
				array(
					'id'      => 'img_hover',
					'label'   => __( 'Image Hover', 'total' ),
					'type'    => 'select',
					'choices' => 'image_hovers',
				),
				array(
					'id'    => 'admins',
					'label' => __( 'Include Administrators?', 'total' ),
					'type'  => 'checkbox',
					'std'   => 'on',
				),
				array(
					'id'    => 'editors',
					'label' => __( 'Include Editors?', 'total' ),
					'type'  => 'checkbox',
					'std'   => 'on',
				),
				array(
					'id'    => 'authors',
					'label' => __( 'Include Authors?', 'total' ),
					'type'  => 'checkbox',
					'std'  => 'on',
				),
				array(
					'id'    => 'contributors',
					'label' => __( 'Include Contributors?', 'total' ),
					'type'  => 'checkbox',
					'std'   => 'on',
				),
				array(
					'id'    => 'subscribers',
					'label' => __( 'Include Subscribers?', 'total' ),
					'type'  => 'checkbox',
				),
				array(
					'id'    => 'link_to_posts',
					'label' => __( 'Link to user posts page?', 'total' ),
					'type'  => 'checkbox',
					'std'   => 'on',
				),
				array(
					'id'    => 'show_name',
					'label' => __( 'Display Name?', 'total' ),
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

		// Query users
		$query_args = array(
			'orderby' => $orderby,
			'order'   => $order,
		);
		$role_in = array();
		if ( $admins ) {
			$role_in[] = 'administrator';
		}
		if ( $authors ) {
			$role_in[] = 'author';
		}
		if ( $contributors ) {
			$role_in[] = 'contributor';
		}
		if ( $subscribers ) {
			$role_in[] = 'subscriber';
		}
		if ( $role_in ) {
			$query_args['role__in'] = $role_in;
		}

		$get_users = get_users( $query_args );

		if ( $get_users ) {

			$output .= '<ul class="wpex-users-widget wpex-row ' . wpex_gap_class( $columns_gap ) . ' clr">';

				$count=0;

				foreach ( $get_users as $user ) :

					$count++;
					$classes = 'nr-col clr';
					$classes .= ' ' . wpex_grid_class( $columns );
					$classes .= ' col-' . $count;

					$output .= '<li class="' . $classes . '">';

						// Open link tag
						if ( $link_to_posts ) {

							$output .= '<a href="' . esc_url( get_author_posts_url( $user->ID, $user->user_nicename ) ) . '" title="' . esc_attr( $user->display_name ) . ' ' . esc_html__( 'Archive', 'total' ) . '">';

						}

						// Display avatar
						$output .= '<div class="wpex-users-widget-avatar ' . wpex_image_hover_classes( $img_hover ) . '">';

							$output .= get_avatar( $user->ID, $img_size, '', $user->display_name );

						$output .= '</div>';

						// Display name
						if ( $show_name ) {

							$output .= '<div class="wpex-users-widget-name entry-title">';

								$output .= esc_html( $user->display_name );

							$output .= '</div>';

						}

						// Close link
						if ( $link_to_posts ) {
							$output .= '</a>';
						}

					$output .= '</li>';

				// Clear columns
				if ( $columns == $count ) {
					$count = 0;
				}

				// End loop
				endforeach;

			// Close ul wrap
			$output .= '</ul>';

		}

		// Echo output
		echo $output;

		// After widget hook
		echo wp_kses_post( $args['after_widget'] );

	}

}
register_widget( 'TotalTheme\UsersGridWiget' );