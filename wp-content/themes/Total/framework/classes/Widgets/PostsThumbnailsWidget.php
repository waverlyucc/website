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
class PostsThumbnailsWidget extends WidgetBuilder {
	private $args;

	/**
	 * Register widget with WordPress.
	 *
	 * @since 1.0
	 */
	public function __construct() {

		$this->args = array(
			'id_base' => 'wpex_recent_posts_thumb',
			'name'    => $this->branding() . __( 'Posts With Thumbnails', 'total' ),
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
					'default' => 3,
				),
				array(
					'id'      => 'style',
					'label'   => __( 'Style', 'Total' ),
					'type'    => 'select',
					'default' => 'default',
					'choices' => array(
						'default' => __( 'Small Image', 'total' ),
						'fullimg' => __( 'Full Image', 'total' )
					),
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
					'id'          => 'excerpt_length',
					'label'       => __( 'Excerpt Length', 'total' ),
					'type'        => 'number',
					'default'     => 0,
					'description' => __( 'Enter a value to display an excerpt with chose number of words.', 'total' ),
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
					'id'    => 'add_img_width',
					'label' => __( 'Apply width?', 'total' ),
					'type'  => 'checkbox',
					'description' => __( 'By default the image width value is used for cropping only. Check this box to actually alter your image size to the defined width.', 'total' ),
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
				array(
					'id'    => 'date',
					'label' => __( 'Disable Date?', 'total' ),
					'type'  => 'checkbox',
				),
				array(
					'id'    => 'thumbnail_query',
					'label' => __( 'Post With Thumbnails Only?', 'total' ),
					'type'  => 'checkbox',
					'std'   => 'on',
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

		// Query posts
		$query_args = array(
			'post_type'      => $post_type,
			'posts_per_page' => $number,
			'no_found_rows'  => true,
			'tax_query'      => array(
				'relation' => 'AND',
			),
		);

		// Query by thumbnail meta_key
		if ( $thumbnail_query ) {
			$query_args['meta_key'] = '_thumbnail_id';
		}

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

		// If there are posts loop through them
		if ( $wpex_query->have_posts() ) :

			// Begin entries output
			$output .= '<ul class="wpex-widget-recent-posts wpex-clr style-' . esc_attr( $style ) . '">';

					// Loop through posts
					while ( $wpex_query->have_posts() ) : $wpex_query->the_post();

						// Check thumb
						$has_thumb = has_post_thumbnail();

						// Li classes
						$li_classes = 'wpex-widget-recent-posts-li clr';
						if ( ! $has_thumb ) {
							$li_classes .= ' wpex-no-thumb';
						}

						// Output entry
						$output .= '<li class="' . $li_classes . '">';

							// Get post data
							$permalink = wpex_get_permalink();
							$esc_title = wpex_get_esc_title();

							// Display thumbnail
							if ( $has_thumb ) :

								// Inline CSS
								$inline_css = '';
								if ( $add_img_width && $img_width ) {
									$inline_css = ' style="width:' . intval( $img_width ) . 'px"';
								}

								// Thumb chasses
								$thumb_classes = 'wpex-widget-recent-posts-thumbnail';
								if ( $img_hover = esc_attr( $img_hover ) ) {
									$thumb_classes .= ' ' . wpex_image_hover_classes( $img_hover );
								}

								$output .= '<a href="' . $permalink . '" title="' . $esc_title . '" class="' . $thumb_classes . '"' . $inline_css . '>';

									$output .= wpex_get_post_thumbnail( array(
										'size'   => $img_size,
										'width'  => $img_width,
										'height' => $img_height,
										'crop'   => $img_crop_location,
									) );

								$output .= '</a>';

							endif;

							$output .= '<div class="details clr">';

								$output .= '<a href="' . $permalink . '" class="wpex-widget-recent-posts-title">' . esc_html( get_the_title() ) . '</a>';

								// Display date if enabled
								if ( '1' != $date ) :

									$output .= '<div class="wpex-widget-recent-posts-date">' . get_the_date() . '</div>';

								endif;

								// Display excerpt
								if ( intval( $excerpt_length ) && 0 !== $excerpt_length ) {

									$excerpt = wpex_get_excerpt( array(
										'length'          => $excerpt_length,
										'context'         => 'wpex_recent_posts_thumb_widget',
										'custom_excerpts' => false,
									) );

									if ( $excerpt ) {

										$output .= '<div class="wpex-widget-recent-posts-excerpt">' . $excerpt . '</div>';

									}

								}

							$output .= '</div>';

						$output .= '</li>';

					endwhile;

			$output .= '</ul>';

			// Reset post data
			wp_reset_postdata();

		endif;

		// Echo output
		echo $output;

		echo wp_kses_post( $args['after_widget'] );

	}

}
register_widget( 'TotalTheme\PostsThumbnailsWidget' );