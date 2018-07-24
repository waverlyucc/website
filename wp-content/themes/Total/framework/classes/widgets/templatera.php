<?php
/**
 * Templatera Widget
 *
 * Learn more: http://codex.wordpress.org/Widgets_API
 *
 * @package Total WordPress Theme
 * @subpackage Widgets
 * @version 4.6.5
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Start class
if ( ! class_exists( 'WPEX_Templatera_Widget' ) ) {

	class WPEX_Templatera_Widget extends WP_Widget {

		/**
		 * Register widget with WordPress.
		 *
		 * @since 3.3.5
		 */
		public function __construct() {
			$branding = wpex_get_theme_branding();
			$branding = $branding ? $branding . ' - ' : '';
			parent::__construct(
				'wpex_templatera',
				$branding . __( 'Templatera', 'total' ),
				array(
					'customize_selective_refresh' => true,
				)
			);
		}

		/**
		 * Front-end display of widget.
		 *
		 * @see WP_Widget::widget()
		 * @since 3.3.5
		 *
		 *
		 * @param array $args     Widget arguments.
		 * @param array $instance Saved values from database.
		 */
		public function widget( $args, $instance ) {

			// Set vars for widget usage
			$title    = isset( $instance['title'] ) ? apply_filters( 'widget_title', $instance['title'] ) : '';
			$template = isset( $instance['template'] ) ? $instance['template'] : '';

			// Template required
			if ( ! $template ) {
				return;
			}

			// Define output
			$output = '';

			// Before widget WP hook
			$output .= $args['before_widget'];

			// Display title if defined
			if ( $title ) {
				$output .= $args['before_title'] . $title . $args['after_title'];
			}

			// Add inline styles
			$custom_css = esc_attr( get_post_meta( $template, '_wpb_shortcodes_custom_css', true ) );
			
			if ( ! empty( $custom_css ) ) {
				
				$output .= '<style data-type="vc_shortcodes-custom-css">';

					$output .= wpex_minify_css( $custom_css );

				$output .= '</style>';

			}

			// Output html
			$output .= '<div class="wpex-templatera-widget-content clr">' . do_shortcode( '[templatera id="' . $template . '"]' ) . '</div>';

			// After widget WP hook
			$output .= $args['after_widget'];

			echo $output; ?>

		<?php
		}

		/**
		 * Sanitize widget form values as they are saved.
		 *
		 * @see WP_Widget::update()
		 * @since 3.3.5
		 *
		 * @param array $new_instance Values just sent to be saved.
		 * @param array $old_instance Previously saved values from database.
		 *
		 * @return array Updated safe values to be saved.
		 */
		public function update( $new_instance, $old_instance ) {
			$instance             = $old_instance;
			$instance['title']    = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
			$instance['template'] = ( ! empty( $new_instance['template'] ) ) ? intval( $new_instance['template'] ) : '';
			return $instance;
		}

		/**
		 * Back-end widget form.
		 *
		 * @see WP_Widget::form()
		 * @since 3.3.5
		 *
		 * @param array $instance Previously saved values from database.
		 */
		public function form( $instance ) {

			extract( wp_parse_args( ( array ) $instance, array(
				'title'    => '',
				'template' => '',
			) ) ); ?>

			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Title', 'total' ); ?></label>
				<input class="widefat" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
			</p>

			<?php
			// Get templates
			$template_ids = new WP_Query( array(
				'post_type'      => 'templatera',
				'posts_per_page' => -1,
				'fields'         => 'ids',
				'no_found_rows'  => true,
			) );

			if ( $template_ids->have_posts() ) : ?>

				<p>
					<label for="<?php echo esc_attr( $this->get_field_id( 'template' ) ); ?>"><?php esc_html_e( 'Template', 'total' ); ?></label>
					<br />
					<select class='wpex-select' name="<?php echo esc_attr( $this->get_field_name( 'template' ) ); ?>" style="width:100%;">
						<?php
						// Loop through templates
						foreach ( $template_ids->posts as $template_id ) : ?>
							<option value="<?php echo esc_attr( $template_id ); ?>" <?php selected( $template, $template_id ); ?>><?php echo  esc_html( get_the_title( $template_id ) ); ?></option>
						<?php endforeach; ?>
						<?php $template_ids = null; wp_reset_postdata(); ?>
					</select>
				</p>

			<?php else : ?>

				<p><?php esc_html_e( 'No templates found.', 'total' ); ?></p>

			<?php endif; ?>

		<?php
		}

	}

}
register_widget( 'WPEX_Templatera_Widget' );