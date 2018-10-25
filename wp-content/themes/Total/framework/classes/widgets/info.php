<?php
/**
 * Business Info Widget
 *
 * @package Total WordPress Theme
 * @subpackage Widgets
 * @version 4.5.5.1
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Start class
if ( ! class_exists( 'WPEX_Info_Widget' ) ) {

	class WPEX_Info_Widget extends WP_Widget {
		
		/**
		 * Register widget with WordPress.
		 */
		public function __construct() {
			$branding = wpex_get_theme_branding();
			$branding = $branding ? $branding . ' - ' : '';
			parent::__construct(
				'wpex_info_widget',
				$branding . __( 'Business Info', 'total' ),
				array(
					'customize_selective_refresh' => true,
				)
			);

		}

		/**
		 * Front-end display of widget.
		 *
		 * @see WP_Widget::widget()
		 *
		 * @param array $args     Widget arguments.
		 * @param array $instance Saved values from database.
		 */
		public function widget( $args, $instance ) {

			// Define output var
			$output = '';

			// Set vars for widget usage
			$title                 = isset( $instance['title'] ) ? apply_filters( 'widget_title', $instance['title'] ) : '';
			$address               = isset( $instance['address'] ) ? $instance['address'] : '';
			$phone_number          = isset( $instance['phone_number'] ) ? $instance['phone_number'] : '';
			$phone_number_mobile   = isset( $instance['phone_number_mobile'] ) ? $instance['phone_number_mobile'] : '';
			$phone_number_tel_link = isset( $instance['phone_number_tel_link'] ) ? $instance['phone_number_tel_link'] : '';
			$fax_number            = isset( $instance['fax_number'] ) ? $instance['fax_number'] : '';
			$email                 = isset( $instance['email'] ) ? $instance['email'] : '';
			$email_label           = isset( $instance['email_label'] ) ? $instance['email_label'] : '';

			// Before widget WP hook
			$output .= $args['before_widget'];

			// Display title if defined
			if ( $title ) {
				$output .= $args['before_title'];
					$output .= $title;
				$output .= $args['after_title']; 
			}

			$output .= '<div class="wpex-info-widget wpex-clr">';

				// Address
				if ( $address ) {

					$output .= '<div class="wpex-info-widget-address wpex-clr">';

						$output .= '<span class="fa fa-map-marker"></span>';

						$output .= wpautop( wp_kses_post( $address ) );

					$output .= '</div>';

				}

				// Phone number
				if ( $phone_number ) {

					$output .= '<div class="wpex-info-widget-phone wpex-clr">';

						$output .= '<span class="fa fa-phone"></span>';

						if ( 'on' == $phone_number_tel_link ) {

							$output .= '<a href="tel:'. strip_tags( $phone_number ) .'">'. strip_tags( $phone_number ) .'</a>';

						} else {

							$output .= strip_tags( $phone_number );

						}

					$output .= '</div>';

				}

				// Phone number mobile
				if ( $phone_number_mobile ) {

					$output .= '<div class="wpex-info-widget-phone-mobile wpex-clr">';

						$output .= '<span class="fa fa-mobile"></span>';

						if ( 'on' == $phone_number_tel_link ) {

							$output .= '<a href="tel:'. strip_tags( $phone_number_mobile ) .'">'. strip_tags( $phone_number_mobile ) .'</a>';

						} else {

							$output .= strip_tags( $phone_number_mobile );

						}

					$output .= '</div>';

				}

				// Fax number
				if ( $fax_number ) {

					$output .= '<div class="wpex-info-widget-fax wpex-clr">';

						$output .= '<span class="fa fa-fax"></span>';

						$output .= strip_tags( $fax_number );

					$output .= '</div>';

				}

				// Email
				if ( $email ) {

					// Sanitize email
					$sanitize_email = sanitize_email( $email );
					$is_email       = is_email( $sanitize_email );

					// Spam protect email address
					$protected_email = $is_email ? antispambot( $sanitize_email ) : $sanitize_email;

					// Sanitize & fallback for email label
					$email_label = ( ! $email_label && $is_email ) ? $protected_email : $email_label;

					// Email title attribute
					$title_attr = $email_label ? $email_label : __( 'Email Us', 'total' );

					// Email output
					$output .= '<div class="wpex-info-widget-email wpex-clr">';

						$output .= '<span class="fa fa-envelope"></span>';
						
						if ( $is_email ) {
							
							$output .= '<a href="mailto:'. $protected_email .'" title="'. esc_attr( $title_attr ) .'">'. strip_tags( $email_label ) .'</a>';

						} else {
							
							$parse_email_url = parse_url( $email );

							if ( ! empty( $parse_email_url['scheme'] ) ) {
								$output .= '<a href="'. esc_url( $email ) .'" title="'. $email_label .'">'. $email_label .'</a>';
							} else {
								$output .= strip_tags( $email_label );
							}

						}

					$output .= '</div>';

				}

			$output .= '</div>';

			// After widget WP hook
			$output .= $args['after_widget'];

			// Eco output
			echo $output;
			
		}

		/**
		 * Sanitize widget form values as they are saved.
		 *
		 * @see WP_Widget::update()
		 *
		 * @param array $new_instance Values just sent to be saved.
		 * @param array $old_instance Previously saved values from database.
		 *
		 * @return array Updated safe values to be saved.
		 */
		public function update( $new_instance, $old_instance ) {

			$instance = $old_instance;

			$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
			
			$instance['address'] = ( ! empty( $new_instance['address'] ) ) ? wp_kses_post( $new_instance['address'] ) : '';
			
			$instance['phone_number'] = ( ! empty( $new_instance['phone_number'] ) ) ? strip_tags( $new_instance['phone_number'] ) : '';
			$instance['phone_number_mobile'] = ( ! empty( $new_instance['phone_number_mobile'] ) ) ? strip_tags( $new_instance['phone_number_mobile'] ) : '';
			
			$instance['phone_number_tel_link'] = ( ! empty( $new_instance['phone_number_tel_link'] ) ) ? 'on' : 'off';
			
			$instance['fax_number'] = ( ! empty( $new_instance['fax_number'] ) ) ? strip_tags( $new_instance['fax_number'] ) : '';
			
			$instance['email'] = ( ! empty( $new_instance['email'] ) ) ? strip_tags( $new_instance['email'] ) : '';
			
			$instance['email_label'] = ( ! empty( $new_instance['email_label'] ) ) ? strip_tags( $new_instance['email_label'] ) : '';

			return $instance;
		}

		/**
		 * Back-end widget form.
		 *
		 * @see WP_Widget::form()
		 *
		 * @param array $instance Previously saved values from database.
		 */
		public function form( $instance ) {

			extract( wp_parse_args( $instance, array(
				'title'                 => '',
				'address'               => '',
				'phone_number'          => '',
				'phone_number_mobile'   => '',
				'fax_number'            => '',
				'email'                 => '',
				'email_label'           => '',
				'phone_number_tel_link' => '',
			) ) ); ?>

			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_attr_e( 'Title', 'total' ); ?></label>
				<input class="widefat" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
			</p>

			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'address' ) ); ?>">
				<?php esc_attr_e( 'Address', 'total' ); ?></label>
				<textarea rows="5" class="widefat" name="<?php echo esc_attr( $this->get_field_name( 'address' ) ); ?>" type="text"><?php echo wp_kses_post( $address ); ?></textarea>
			</p>

			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'phone_number' ) ); ?>"><?php esc_attr_e( 'Phone Number', 'total' ); ?></label>
				<input class="widefat" name="<?php echo esc_attr( $this->get_field_name( 'phone_number' ) ); ?>" type="text" value="<?php echo esc_attr( $phone_number ); ?>" />
			</p>

			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'phone_number_mobile' ) ); ?>"><?php esc_attr_e( 'Mobile Phone Number', 'total' ); ?></label>
				<input class="widefat" name="<?php echo esc_attr( $this->get_field_name( 'phone_number_mobile' ) ); ?>" type="text" value="<?php echo esc_attr( $phone_number_mobile ); ?>" />
			</p>

			<p>

			<input name="<?php echo esc_attr( $this->get_field_name( 'phone_number_tel_link' ) ); ?>" type="checkbox" value="on" <?php checked( $phone_number_tel_link, 'on', 'off' ); ?> />
				<label for="<?php echo esc_attr( $this->get_field_id( 'phone_number_tel_link' ) ); ?>"><?php esc_html_e( 'Add "tel" link to the phone number?', 'total' ); ?></label>
			</p>

			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'fax_number' ) ); ?>"><?php esc_attr_e( 'Fax Number', 'total' ); ?></label>
				<input class="widefat" name="<?php echo esc_attr( $this->get_field_name( 'fax_number' ) ); ?>" type="text" value="<?php echo esc_attr( $fax_number ); ?>" />
			</p>

			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'email' ) ); ?>"><?php esc_attr_e( 'Email', 'total' ); ?></label>
				<input class="widefat" name="<?php echo esc_attr( $this->get_field_name( 'email' ) ); ?>" type="text" value="<?php echo esc_attr( $email ); ?>" />
			</p>

			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'email_label' ) ); ?>"><?php esc_attr_e( 'Email Label', 'total' ); ?></label>
				<input class="widefat" name="<?php echo esc_attr( $this->get_field_name( 'email_label' ) ); ?>" type="text" value="<?php echo esc_attr( $email_label ); ?>" />
				<small><?php esc_attr_e( 'Will display your email by default if this field is empty.', 'total' ); ?></small>
			</p>

			
		<?php
		}

	}

}
register_widget( 'WPEX_Info_Widget' );