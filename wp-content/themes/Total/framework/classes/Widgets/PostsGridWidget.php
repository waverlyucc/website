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
class PostsGridWidget extends WidgetBuilder {
	private $args;

	/**
	 * Register widget with WordPress.
	 *
	 * @since 1.0
	 */
	public function __construct() {

		$this->args = array(
			'id_base' => 'wpex_recent_posts_thumb_grid',
			'name'    => $this->branding() . __( 'Posts Thumbnail Grid', 'total' ),
			'options' => array(
				'customize_selective_refresh' => true,
			),
			'fields' => array(
				array(
					'id'    => 'title',
					'label' => __( 'Title', 'Total' ),
					'type'  => 'text',
				),
				array(
					'id'      => 'number',
					'label'   => __( 'Number', 'total' ),
					'type'    => 'number',
					'default' => 6,
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
					'label'   => __( 'Column Gap', 'total' ),
					'type'    => 'select',
					'choices' => 'grid_gaps',
				),
				array(
					'id'       => 'post_type',
					'label'    => __( 'Post Type', 'total' ),
					'type'     => 'select',
					'choices'  => 'post_types',
					'default'  => 'post',
				),
				array(
					'id'      => 'taxonomy',
					'label'   => __( 'Query By Taxonomy', 'total' ),
					'type'    => 'select',
					'choices' => 'taxonomies',
				),
				array(
					'id'          => 'terms',
					'label'       => __( 'Include Terms', 'total' ),
					'type'        => 'text',
					'description' => __( 'Enter a comma seperated list of terms.', 'total' ),
				),
				array(
					'id'          => 'terms_exclude',
					'label'       => __( 'Exclude Terms', 'total' ),
					'type'        => 'text',
					'description' => __( 'Enter a comma seperated list of terms.', 'total' ),
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
					'id'      => 'img_hover',
					'label'   => __( 'Image Hover', 'total' ),
					'type'    => 'select',
					'choices' => 'image_hovers',
				),
				array(
					'id'      => 'img_size',
					'label'   => __( 'Image Size', 'total' ),
					'type'    => 'select',
					'default' => 'wpex-custom',
					'choices' => 'intermediate_image_sizes',
				),
				array(
					'id'    => 'img_width',
					'label' => __( 'Image Crop Width', 'total' ),
					'type'  => 'text',
				),
				array(
					'id'    => 'img_height',
					'label' => __( 'Image Crop Height', 'total' ),
					'type'  => 'text',
				),
				array(
					'id'      => 'img_crop_location',
					'label'   => __( 'Image Crop Location', 'total' ),
					'type'    => 'select',
					'choices' => 'image_crop_locations',
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

		// Start output
		$gap = $gap ? $gap : '5';
		$output .= '<ul class="wpex-recent-posts-thumb-grid wpex-row clr gap-' . $gap . '">';

			// Query args
			$query_args = array(
				'post_type'      => $post_type,
				'posts_per_page' => $number,
				'meta_key'       => '_thumbnail_id',
				'no_found_rows'  => true,
				'tax_query'      => array(
					'relation' => 'AND',
				),
			);

			// Order params - needs FALLBACK don't ever edit!
			if ( ! empty( $orderby ) ) {
				$query_args['order']   = $order;
				$query_args['orderby'] = $orderby;
			} else {
				$query_args['orderby'] = $order; // THIS IS THE FALLBACK
			}

			// Tax Query
			if ( ! empty( $taxonomy ) ) {

				// Include Terms
				if (  ! empty( $terms ) ) {

					// Sanitize terms and convert to array
					$terms = str_replace( ', ', ',', $terms );
					$terms = explode( ',', $terms );

					// Add to query arg
					$query_args['tax_query'][] = array(
						'taxonomy' => $taxonomy,
						'field'    => 'slug',
						'terms'    => $terms,
						'operator' => 'IN',
					);

				}

				// Exclude Terms
				if ( ! empty( $terms_exclude ) ) {

					// Sanitize terms and convert to array
					$terms_exclude = str_replace( ', ', ',', $terms_exclude );
					$terms_exclude = explode( ',', $terms_exclude );

					// Add to query arg
					$query_args['tax_query'][] = array(
						'taxonomy' => $taxonomy,
						'field'    => 'slug',
						'terms'    => $terms_exclude,
						'operator' => 'NOT IN',
					);

				}

			}

			// Exclude current post
			if ( is_singular() ) {
				$query_args['post__not_in'] = array( get_the_ID() );
			}

			// Query posts
			$wpex_query = new WP_Query( $query_args );

			// Set post counter variable
			$count=0;

			// Hover classes
			$img_hover_classes = wpex_image_hover_classes( $img_hover );
			$img_hover_classes = $img_hover_classes ? ' class="' . esc_attr( $img_hover_classes ) .'"' : '';

			// Loop through posts
			while ( $wpex_query->have_posts() ) : $wpex_query->the_post();

				// Add to counter variable
				$count++;

				$output .= '<li class="' . wpex_grid_class( $columns ) .' nr-col col-' . esc_attr( $count ) . '">';

					$output .= '<a href="' . wpex_get_permalink() .'"' . $img_hover_classes .'>';

						$output .= wpex_get_post_thumbnail( array(
							'size'   => $img_size,
							'width'  => $img_width,
							'height' => $img_height,
							'crop'   => $img_crop_location,
						) );

					$output .= '</a>';

				$output .= '</li>';

				// Reset counter to clear floats
				if ( $count == $columns ) {

					$count = '0';

				}

			// End loop
			endwhile;

			// Reset global query post data
			wp_reset_postdata();

		$output .= '</ul>';

		// Echo output
		echo $output;

		echo wp_kses_post( $args['after_widget'] );

	}

}
register_widget( 'TotalTheme\PostsGridWidget' );