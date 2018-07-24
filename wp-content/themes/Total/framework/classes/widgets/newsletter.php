<?php
/**
 * Recent Posts w/ Thumbnails
 *
 * @package Total WordPress Theme
 * @subpackage Widgets
 * @version 4.7
 */

// Prevent direct file access
if ( ! defined ( 'ABSPATH' ) ) {
	exit;
}

// Start widget class
if ( ! class_exists( 'WPEX_Newsletter_Widget' ) ) {

	class WPEX_Newsletter_Widget extends WP_Widget {

		/**
		 * Register widget with WordPress.
		 *
		 * @since 1.0.0
		 */
		public function __construct() {
			$branding = wpex_get_theme_branding();
			$branding = $branding ? $branding . ' - ' : '';
			parent::__construct(
				'wpex_mailchimp',
				$branding . __( 'MailChimp Newsletter', 'total' ),
				array(
					'customize_selective_refresh' => true,
				)
			);
		}

		/**
		 * Front-end display of widget.
		 *
		 * @see WP_Widget::widget()
		 * @since 1.0.0
		 *
		 *
		 * @param array $args     Widget arguments.
		 * @param array $instance Saved values from database.
		 */
		public function widget( $args, $instance ) {
			$title                 = isset( $instance['title'] ) ? apply_filters( 'widget_title', $instance['title'] ) : '';
			$heading               = isset( $instance['heading'] ) ? $instance['heading'] : esc_html__( 'NEWSLETTER','total' );
			$email_holder_txt      = ! empty( $instance['placeholder_text'] ) ? $instance['placeholder_text'] : '';
			$email_holder_txt      = $email_holder_txt ? $email_holder_txt : esc_html__( 'Your email address', 'total' );
			$name_field            = ! empty( $instance['name_field'] ) ? true : false;
			$name_holder_txt       = ! empty( $instance['name_placeholder_text'] ) ? $instance['name_placeholder_text'] : '';
			$name_holder_txt       = $name_holder_txt ? $name_holder_txt : esc_html__( 'First name', 'total' );
			$last_name_field       = ! empty( $instance['last_name_field'] ) ? true : false;
			$last_name_holder_txt  = ! empty( $instance['last_name_placeholder_text'] ) ? $instance['last_name_placeholder_text'] : '';
			$last_name_holder_txt  = $last_name_holder_txt ? $last_name_holder_txt : esc_html__( 'Last last_name', 'total' );
			$button_text           = ! empty( $instance['button_text'] ) ? $instance['button_text'] : esc_html__( 'Subscribe', 'total' );
			$form_action           = isset( $instance['form_action'] ) ? $instance['form_action'] : '';
			$description           = isset( $instance['description'] ) ? $instance['description'] : '';

			// Before widget hook
			echo $args['before_widget']; ?>

				<?php
				// Display widget title
				if ( $title ) {
					echo $args['before_title'] . $title . $args['after_title'];
				} ?>

				<?php if ( $form_action ) { ?>

					<div class="wpex-newsletter-widget wpex-clr">

						<?php
						// Display the heading
						if ( $heading ) { ?>

							<h4 class="wpex-newsletter-widget-heading"><?php echo wp_kses_post( $heading ); ?></h4>

						<?php } ?>

						<?php
						// Display the description
						if ( $description ) { ?>

							<div class="wpex-newsletter-widget-description">
								<?php echo wp_kses_post( $description ); ?>
							</div>

						<?php } ?>

							<form action="<?php echo esc_attr( $form_action ); ?>" method="post" id="mc-embedded-subscribe-form" name="mc-embedded-subscribe-form" class="validate" target="_blank" novalidate>

								<?php
								// Name field
								if ( $name_field ) : ?>
									<label>
										<span class="screen-reader-text"><?php echo esc_html( $name_holder_txt ); ?></span>
										<input type="text" value="<?php echo esc_attr( $name_holder_txt ); ?>" onfocus="if(this.value==this.defaultValue)this.value='';" onblur="if(this.value=='')this.value=this.defaultValue;" name="FNAME" id="mce-FNAME" autocomplete="off">
									</label>
								<?php endif; ?>

								<?php
								// Lastname field
								if ( $last_name_field ) : ?>
									<label>
										<span class="screen-reader-text"><?php echo esc_html( $last_name_holder_txt ); ?></span>
										<input type="text" value="<?php echo esc_attr( $last_name_holder_txt ); ?>" onfocus="if(this.value==this.defaultValue)this.value='';" onblur="if(this.value=='')this.value=this.defaultValue;" name="LNAME" id="mce-LNAME" autocomplete="off">
									</label>
								<?php endif; ?>
								
								<label>
									<span class="screen-reader-text"><?php echo esc_html( $email_holder_txt ); ?></span>
									<input type="email" value="<?php echo esc_attr( $email_holder_txt ); ?>" onfocus="if(this.value==this.defaultValue)this.value='';" onblur="if(this.value=='')this.value=this.defaultValue;" name="EMAIL" id="mce-EMAIL" autocomplete="off">
								</label>

								<?php echo apply_filters( 'wpex_mailchimp_widget_form_extras', null ); ?>

								<button type="submit" value="" name="subscribe"><?php echo strip_tags( $button_text ); ?></button>

							</form>

					</div><!-- .mailchimp-widget -->

				<?php } else { ?>

					<?php esc_html_e( 'Please enter your Mailchimp form action link.', 'total' ); ?>

				<?php } ?>

			<?php
			// After widget hook
			echo $args['after_widget'];
		}

		/**
		 * Sanitize widget form values as they are saved.
		 *
		 * @see WP_Widget::update()
		 * @since 1.0.0
		 *
		 * @param array $new_instance Values just sent to be saved.
		 * @param array $old_instance Previously saved values from database.
		 *
		 * @return array Updated safe values to be saved.
		 */
		public function update( $new_instance, $old_instance ) {
			$instance = $old_instance;
			$instance['title'] = ! empty( $new_instance['title'] ) ? strip_tags( $new_instance['title'] ) : '';
			$instance['heading'] = ! empty( $new_instance['heading'] ) ? wp_kses_post( $new_instance['heading'] ) : '';
			$instance['description'] = ! empty( $new_instance['description'] ) ? wp_kses_post( $new_instance['description'] ) : '';
			$instance['form_action'] = ! empty( $new_instance['form_action'] ) ? strip_tags( $new_instance['form_action'] ) : '';
			$instance['placeholder_text'] = ! empty( $new_instance['placeholder_text'] ) ? strip_tags( $new_instance['placeholder_text'] ) : '';
			$instance['button_text'] = ! empty( $new_instance['button_text'] ) ? strip_tags( $new_instance['button_text'] ) : '';
			$instance['name_field'] = isset( $new_instance['name_field'] ) ? 1 : 0;
			$instance['name_placeholder_text'] = ! empty( $new_instance['name_placeholder_text'] ) ? strip_tags( $new_instance['name_placeholder_text'] ) : '';
			$instance['last_name_field'] = isset( $new_instance['last_name_field'] ) ? 1 : 0;
			$instance['last_name_placeholder_text'] = ! empty( $new_instance['last_name_placeholder_text'] ) ? strip_tags( $new_instance['last_name_placeholder_text'] ) : '';
			return $instance;
		}

		/**
		 * Back-end widget form.
		 *
		 * @see WP_Widget::form()
		 * @since 1.0.0
		 *
		 * @param array $instance Previously saved values from database.
		 */
		public function form( $instance ) {

			extract( wp_parse_args( ( array ) $instance, array(
				'title'                      => '',
				'heading'                    => '',
				'description'                => '',
				'form_action'                => '',
				'placeholder_text'           => __( 'Your email address', 'total' ),
				'button_text'                => __( 'Subscribe', 'total' ),
				'name_placeholder_text'      => __( 'First name', 'total' ),
				'name_field'                 => 0,
				'last_name_placeholder_text' => __( 'Last name', 'total' ),
				'last_name_field'            => 0,
			) ) ); ?>

			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Title', 'total' ); ?>:</label>
				<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
			</p>

			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'heading' ) ); ?>"><?php esc_html_e( 'Heading', 'total' ); ?>:</label>
				<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'heading' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'heading' ) ); ?>" type="text" value="<?php echo esc_attr( $heading ); ?>" />
			</p>

			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'form_action' ) ); ?>"><?php esc_html_e( 'Form Action', 'total' ); ?>:</label>
				<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'form_action' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'form_action' ) ); ?>" type="text" value="<?php echo esc_attr( $form_action ); ?>" />
				<span style="display:block;padding:5px 0" class="description">
					<a href="https://wpexplorer-themes.com/total/docs/mailchimp-form-action-url/" target="_blank"><?php esc_html_e( 'Learn more', 'total' ); ?>&rarr;</a>
				</span>
			</p>

			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'description' ) ); ?>"><?php esc_html_e( 'Description:','total' ); ?></label>
				<textarea class="widefat" rows="5" cols="20" id="<?php echo esc_attr( $this->get_field_id( 'description' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'description' ) ); ?>"><?php echo wp_kses_post( $description ); ?></textarea>
			</p>

			<p>
				<input id="<?php echo esc_attr( $this->get_field_id( 'name_field' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'name_field' ) ); ?>" <?php checked( $name_field, 1, true ); ?> type="checkbox" />
				<label for="<?php echo esc_attr( $this->get_field_id( 'name_field' ) ); ?>"><?php esc_html_e( 'Display First Name Field?', 'total' ); ?></label>
			</p>

			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'name_placeholder_text' ) ); ?>"><?php esc_html_e( 'First Name Placeholder Text', 'total' ); ?>:</label>
				<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'name_placeholder_text' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'name_placeholder_text' ) ); ?>" type="text" value="<?php echo esc_attr( $name_placeholder_text ); ?>" />
			</p>

			<p>
				<input id="<?php echo esc_attr( $this->get_field_id( 'last_name_field' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'last_name_field' ) ); ?>" <?php checked( $last_name_field, 1, true ); ?> type="checkbox" />
				<label for="<?php echo esc_attr( $this->get_field_id( 'last_name_field' ) ); ?>"><?php esc_html_e( 'Display Last Name Field?', 'total' ); ?></label>
			</p>

			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'last_name_placeholder_text' ) ); ?>"><?php esc_html_e( 'Last Name Placeholder Text', 'total' ); ?>:</label>
				<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'last_name_placeholder_text' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'last_name_placeholder_text' ) ); ?>" type="text" value="<?php echo esc_attr( $last_name_placeholder_text ); ?>" />
			</p>

			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'placeholder_text' ) ); ?>"><?php esc_html_e( 'Email Input Placeholder Text', 'total' ); ?>:</label>
				<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'placeholder_text' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'placeholder_text' ) ); ?>" type="text" value="<?php echo esc_attr( $placeholder_text ); ?>" />
			</p>

			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'button_text' ) ); ?>"><?php esc_html_e( 'Button Text', 'total' ); ?>:</label>
				<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'button_text' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'button_text' ) ); ?>" type="text" value="<?php echo esc_attr( $button_text ); ?>" />
			</p>

		<?php

		}

	}

}
register_widget( 'WPEX_Newsletter_Widget' );