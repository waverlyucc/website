<?php
/**
 * About widget
 *
 * @package Total WordPress Theme
 * @subpackage Widgets
 * @version 4.8
 */

namespace TotalTheme;
use WP_Query;

// Prevent direct file access
if ( ! defined ( 'ABSPATH' ) ) {
	exit;
}

// Start class
class PostsWithFormatIcons extends WidgetBuilder {
	private $args;

	/**
	 * Register widget with WordPress.
	 *
	 * @since 1.0
	 */
	public function __construct() {

		$this->args = array(
			'id_base' => 'wpex_recent_posts_icons',
			'name' => $this->branding() . __( 'Posts With Icons', 'total' ),
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
					'id'      => 'number',
					'label'   => __( 'Number', 'total' ),
					'type'    => 'number',
					'default' => '5',
				),
				array(
					'id'      => 'order',
					'label'   => __( 'Order', 'total' ),
					'type'    => 'select',
					'choices' => 'query_order',
					'default' => 'DESC',
				),
				array(
					'id'      => 'orderby',
					'label'   => __( 'Order by', 'total' ),
					'type'    => 'select',
					'choices' => 'query_orderby',
					'default' => 'date',
				),
				array(
					'id'      => 'category',
					'label'   => __( 'Category', 'total' ),
					'type'    => 'select',
					'choices' => 'categories',
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

		// Query Args
		$query_args = array(
			'post_type'           => 'post',
			'posts_per_page'      => $number,
			'orderby'             => $orderby,
			'order'               => $order,
			'no_found_rows'       => true,
			'post__not_in'        => ( is_singular() ) ? array( get_the_ID() ) : NULL,
			'ignore_sticky_posts' => 1
		);

		// Query by category
		if ( ! empty( $category ) && 'all' != $category ) {
			$query_args['tax_query'] = array( array(
				'taxonomy' => 'category',
				'field'    => 'id',
				'terms'    => $category,
			) );
		}

		// Get posts
		$wpex_query = new WP_Query( $query_args );

		// Loop through posts
		if ( $wpex_query->have_posts() ) {

			$output .= '<ul class="widget-recent-posts-icons clr">';

				while ( $wpex_query->have_posts() ) : $wpex_query->the_post();

					$output .= '<li class="clr">';

						$output .= '<a href="' . wpex_get_permalink() . '"><span class="' . wpex_get_post_format_icon() . '"></span>';

							$output .= esc_html( get_the_title() );

						$output .= '</a>';

					$output .= '</li>';

				endwhile;

			$output .= '</ul>';

			// Reset post data
			wp_reset_postdata();

		}

		// Echo output
		echo $output;

		// After widget hook
		echo wp_kses_post( $args['after_widget'] );

	}

}
register_widget( 'TotalTheme\PostsWithFormatIcons' );