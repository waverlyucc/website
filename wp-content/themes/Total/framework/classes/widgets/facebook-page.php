<?php
/**
 * Facebook Page Widget
 *
 * Learn more: http://codex.wordpress.org/Widgets_API
 *
 * @package Total WordPress Theme
 * @subpackage Widgets
 * @version 4.7
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Start class
if ( ! class_exists( 'WPEX_Facebook_Page_Widget' ) ) {

	class WPEX_Facebook_Page_Widget extends WP_Widget {
		
		/**
		 * Register widget with WordPress.
		 *
		 * @since 3.2.0
		 */
		public function __construct() {
			$branding = wpex_get_theme_branding();
			$branding = $branding ? $branding . ' - ' : '';
			parent::__construct(
				'wpex_facebook_page_widget',
				$branding . __( 'Facebook Page', 'total' ),
				array(
					'customize_selective_refresh' => true,
				)
			);
		}

		/**
		 * Front-end display of widget.
		 *
		 * @see WP_Widget::widget()
		 * @since 3.2.0
		 *
		 *
		 * @param array $args     Widget arguments.
		 * @param array $instance Saved values from database.
		 */
		public function widget( $args, $instance ) {

			// Set vars for widget usage
			$title         = isset( $instance['title'] ) ? apply_filters( 'widget_title', $instance['title'] ) : '';
			$facebook_url  = isset( $instance['facebook_url'] ) ? $instance['facebook_url'] : '';
			$language      = ! empty( $instance['language'] ) ? $instance['language'] : 'en_US';
			$small_header  = ! empty( $instance['small_header'] ) ? 'true' : 'false';
			$hide_cover    = ! empty( $instance['hide_cover'] ) ? 'true' : 'false';
			$show_facepile = ( ! isset( $instance['show_facepile'] ) || ! empty( $instance['show_facepile'] ) ) ? 'true' : 'false';
			$tabs          = ! empty( $instance['tabs'] ) ? $instance['tabs'] : 'false';

			// Before widget WP hook
			echo $args['before_widget'];

			// Display title if defined
			if ( $title ) {
				echo $args['before_title'] . $title . $args['after_title'];
			} ?>

			<?php
			// Show nothing in customizer to keep it fast
			if ( function_exists( 'is_customize_preview' ) && is_customize_preview() ) :

				esc_html_e( 'Facebook widget does not display in the Customizer because it can slow things down.', 'total' );

			elseif ( $facebook_url ) :

				$attrs = array(
					'class'                      => 'fb-page',
					'data-href'                  => esc_url( do_shortcode( $facebook_url ) ),
					'data-small-header'          => esc_attr( $small_header ),
					'data-adapt-container-width' => 'true',
					'data-hide-cover'            => esc_attr( $hide_cover ),
					'data-show-facepile'         => esc_attr( $show_facepile ),
					'data-width'                 => 500,
				);

				if ( $tabs ) {
					$attrs['data-tabs'] = $tabs;
				}

				echo wpex_parse_html( 'div', $attrs ); ?>

				<div id="fb-root"></div>
				<script>(function(d, s, id) {
					var js, fjs = d.getElementsByTagName(s)[0];
					if (d.getElementById(id)) return;
					js = d.createElement(s); js.id = id;
					js.async=true; js.src = "//connect.facebook.net/<?php echo esc_html( $language ); ?>/sdk.js#xfbml=1&version=v2.5&appId=944726105603358";
					fjs.parentNode.insertBefore(js, fjs);
				} ( document, 'script', 'facebook-jssdk' ) );</script>

			<?php endif; ?>

			<?php
			// After widget WP hook
			echo $args['after_widget']; ?>
			
		<?php
		}

		/**
		 * Sanitize widget form values as they are saved.
		 *
		 * @see WP_Widget::update()
		 * @since 3.2.0
		 *
		 * @param array $new_instance Values just sent to be saved.
		 * @param array $old_instance Previously saved values from database.
		 *
		 * @return array Updated safe values to be saved.
		 */
		public function update( $new_instance, $old_instance ) {
			$instance                  = $old_instance;
			$instance['title']         = ( ! empty( $new_instance['title'] ) ) ? wp_strip_all_tags( $new_instance['title'] ) : '';
			$instance['facebook_url']  = ( ! empty( $new_instance['facebook_url'] ) ) ? wp_strip_all_tags( $new_instance['facebook_url'] ) : '';
			$instance['language']      = ( ! empty( $new_instance['language'] ) ) ? wp_strip_all_tags( $new_instance['language'] ) : 'en_US';
			$instance['small_header']  = ( ! empty( $new_instance['small_header'] ) ) ? true : false;
			$instance['hide_cover']    = ( ! empty( $new_instance['hide_cover'] ) ) ? true : false;
			$instance['show_facepile'] = ( ! empty( $new_instance['show_facepile'] ) ) ? true : false;
			$instance['tabs']          = ( ! empty( $new_instance['tabs'] ) ) ? wp_strip_all_tags( $new_instance['tabs']  ) : '';
			return $instance;
		}

		/**
		 * Back-end widget form.
		 *
		 * @see WP_Widget::form()
		 * @since 3.2.0
		 *
		 * @param array $instance Previously saved values from database.
		 */
		public function form( $instance ) {

			extract( wp_parse_args( ( array ) $instance, array(
				'title'         => '',
				'facebook_url'  => '',
				'language'      => 'en_US',
				'small_header'  => false,
				'hide_cover'    => false,
				'show_facepile' => true,
				'tabs'          => '',
			) ) ); ?>

			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Title', 'total' ); ?></label>
				<input class="widefat" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
			</p>

			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'facebook_url' ) ); ?>"><?php esc_html_e( 'Facebook Page URL', 'total' ); ?></label>
				<input class="widefat" name="<?php echo esc_attr( $this->get_field_name( 'facebook_url' ) ); ?>" type="text" value="<?php echo esc_attr( $facebook_url ); ?>" />
			</p>

			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'language' ) ); ?>"><?php esc_html_e( 'Language Locale', 'total' ); ?></label>
				<input class="widefat" name="<?php echo esc_attr( $this->get_field_name( 'language' ) ); ?>" type="text" value="<?php echo esc_attr( $language ); ?>" />
			</p>

			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'tabs' ) ); ?>"><?php esc_html_e( 'Tabs', 'total' ); ?></label>
				<br />
				<select class='wpex-select' name="<?php echo esc_attr( $this->get_field_name( 'tabs' ) ); ?>" style="width:100%;">
					<option value="" <?php selected( $tabs, 0, true ); ?>><?php esc_html_e( '— None —', 'total' ); ?></option>
					<option value="timeline" <?php selected( $tabs, 'timeline', true ); ?>><?php esc_html_e( 'Timeline', 'total' ); ?></option>
					<option value="events" <?php selected( $tabs, 'events', true ); ?>><?php esc_html_e( 'Events', 'total' ); ?></option>
					<option value="timeline,events" <?php selected( $tabs, 'messages', true ); ?>><?php esc_html_e( 'Timeline & Events', 'total' ); ?></option>
				</select>
			</p>

			<p>
				<input name="<?php echo esc_attr( $this->get_field_name( 'small_header' ) ); ?>" type="checkbox" <?php checked( $small_header, true, true ); ?> />
				<label for="<?php echo esc_attr( $this->get_field_id( 'small_header' ) ); ?>"><?php esc_html_e( 'Use small header', 'total' ); ?></label>
			</p>

			<p>
				<input name="<?php echo esc_attr( $this->get_field_name( 'hide_cover' ) ); ?>" type="checkbox" <?php checked( $hide_cover, true, true ); ?> />
				<label for="<?php echo esc_attr( $this->get_field_id( 'hide_cover' ) ); ?>"><?php esc_html_e( 'Hide Cover Photo', 'total' ); ?></label>
			</p>

			<p>
				<input name="<?php echo esc_attr( $this->get_field_name( 'show_facepile' ) ); ?>" type="checkbox" <?php checked( $show_facepile, true, true ); ?> />
				<label for="<?php echo esc_attr( $this->get_field_id( 'show_facepile' ) ); ?>"><?php esc_html_e( 'Show Faces', 'total' ); ?></label>
			</p>
			
		<?php
		}
	}

}
register_widget( 'WPEX_Facebook_Page_Widget' );