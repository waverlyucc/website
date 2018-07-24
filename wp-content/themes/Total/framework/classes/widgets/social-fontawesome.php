<?php
/**
 * Font Awesome social widget
 *
 * Learn more: http://codex.wordpress.org/Widgets_API
 *
 * @package Total WordPress Theme
 * @subpackage Widgets
 * @version 4.5.3
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Start class
if ( ! class_exists( 'WPEX_Fontawesome_Social_Widget' ) ) {

	class WPEX_Fontawesome_Social_Widget extends WP_Widget {
		
		public $social_services_array = array();

		/**
		 * Register widget with WordPress.
		 *
		 * @since 1.0.0
		 */
		public function __construct() {

			// Declare social services array
			$this->social_services_array = apply_filters( 'wpex_social_widget_profiles', array(
				'twitter' => array(
					'name' => 'Twitter',
					'url'  => '',
				),
				'facebook' => array(
					'name' => 'Facebook',
					'url'  => '',
				),
				'instagram' => array(
					'name' => 'Instagram',
					'url'  => '',
				),
				'google-plus' => array(
					'name' => 'GooglePlus',
					'url'  => '',
				),
				'linkedin' => array(
					'name' => 'LinkedIn',
					'url'  => '',
				),
				'pinterest' => array(
					'name' => 'Pinterest',
					'url'  => '',
				),
				'etsy' => array(
					'name' => 'Etsy',
					'url'  => '',
				),
				'yelp' => array(
					'name' => 'Yelp',
					'url'  => '',
				),
				'tripadvisor' => array(
					'name' => 'Tripadvisor',
					'url'  => '',
				),
				'dribbble' => array(
					'name' => 'Dribbble',
					'url'  => '',
				),
				'flickr' => array(
					'name' => 'Flickr',
					'url'  => '',
				),
				'vk' => array(
					'name' => 'VK',
					'url'  => '',
				),
				'github' => array(
					'name' => 'GitHub',
					'url'  => '',
				),
				'tumblr' => array(
					'name' => 'Tumblr',
					'url'  => '',
				),
				'skype' => array(
					'name' => 'Skype',
					'url'  => '',
				),
				'whatsapp' => array(
					'name' => 'Whatsapp',
					'url' => '',
				),
				'trello' => array(
					'name' => 'Trello',
					'url'  => '',
				),
				'foursquare' => array(
					'name' => 'Foursquare',
					'url'  => '',
				),
				'renren' => array(
					'name' => 'RenRen',
					'url'  => '',
				),
				'xing' => array(
					'name' => 'Xing',
					'url'  => '',
				),
				'vimeo-square' => array(
					'name' => 'Vimeo',
					'url'  => '',
				),
				'vine' => array(
					'name' => 'Vine',
					'url'  => '',
				),
				'youtube' => array(
					'name' => 'Youtube',
					'url'  => '',
				),
				'twitch' => array(
					'name' => 'Twitch',
					'url'  => '',
				),
				'houzz' => array(
					'name' => 'Houzz',
					'url' => '',
				),
				'spotify' => array(
					'name' => 'Spotify',
					'url' => '',
				),
				'rss' => array(
					'name' => 'RSS',
					'url'  => '',
				),
			) );

			// Define widget
			$branding = wpex_get_theme_branding();
			$branding = $branding ? $branding . ' - ' : '';
			parent::__construct(
				'wpex_fontawesome_social_widget',
				$branding . esc_attr__( 'Social Profiles (Font Icons)', 'total' ),
				array(
					'description' => __( 'Displays your social profile links via retina ready font icons with many different styles to choose from (recommended).', 'total' ),
					'customize_selective_refresh' => true,
				)
			);

			// Load scripts for drag and drop
			add_action( 'admin_print_scripts-widgets.php', array( $this, 'scripts' ) );

		}

		/**
		 * Front-end display of widget.
		 *
		 * @see WP_Widget::widget()
		 * @since 1.0.0
		 *
		 *
		 * @param array $args Widget arguments.
		 * @param array $instance Saved values from database.
		 */
		public function widget( $args, $instance ) {

			// Get social services and 
			$social_services = isset( $instance['social_services'] ) ? $instance['social_services'] : '';

			// Return if no services defined
			if ( ! $social_services ) {
				return;
			}

			// Define vars
			$output        = '';
			$title         = isset( $instance['title'] ) ? apply_filters( 'widget_title', $instance['title'] ) : '';
			$description   = isset( $instance['description'] ) ? $instance['description'] : '';
			$style         = isset( $instance['style'] ) ? $instance['style'] : '';
			$type          = isset( $instance['type'] ) ? $instance['type'] : '';
			$target        = isset( $instance['target'] ) ? $instance['target'] : '';
			$size          = isset( $instance['size'] ) ? intval( $instance['size'] ) : '';
			$font_size     = isset( $instance['font_size'] ) ? $instance['font_size'] : '';
			$border_radius = isset( $instance['border_radius'] ) ? $instance['border_radius'] : '';
			$align         = isset( $instance['align'] ) ? $instance['align'] : 'left';
			$nofollow      = isset( $instance['nofollow'] ) ? $instance['nofollow'] : false;

			// Parse style
			$style = $this->parse_style( $style, $type ); // Fallback for OLD styles pre-3.0.0

			// Sanitize vars
			$size          = $size ? wpex_sanitize_data( $size, 'px' ) : '';
			$font_size     = $font_size ? wpex_sanitize_data( $font_size, 'font_size' ) : '';
			$border_radius = $border_radius ? wpex_sanitize_data( $border_radius, 'border_radius' ) : '';

			// Wrapper style
			$ul_style = '';
			if ( $font_size ) {
				$ul_style .= ' style="font-size:'. esc_attr( $font_size ) .';"';
			}

			// Inline style
			$add_style = '';
			if ( $size ) {
				$add_style .= 'height:'. $size .';width:'. $size .';line-height:'. $size .';';
			}
			if ( $border_radius ) {
				$add_style .= 'border-radius:'. $border_radius .';';
			}
			if ( $add_style ) {
				$add_style = ' style="' . esc_attr( $add_style ) . '"';
			}

			// Before widget hook
			$output .= $args['before_widget'];

			// Display title
			if ( $title ) {
				$output .= $args['before_title'];

					$output .= $title;

				$output .= $args['after_title'];
			}

			$output .= '<div class="wpex-fa-social-widget clr text'. esc_attr( $align ) .'">';
		
				// Description
				if ( $description ) :

					$output .= '<div class="desc clr">';

						$output .= wp_kses_post( $description );

					$output .= '</div>';
				
				endif;

				$output .= '<ul'. $ul_style .'>';

					// Original Array
					$social_services_array = $this->social_services_array;

					// Loop through each item in the array
					foreach( $social_services as $key => $val ) :

						$link = ! empty( $social_services[$key]['url'] ) ? do_shortcode( $social_services[$key]['url'] ) : null;

						if ( $link ) {

							$name     = $social_services_array[$key]['name'];
							$nofollow = ( $nofollow || isset( $social_services_array[$key]['nofollow'] ) ) ? 'nofollow' : '';

							$l_attrs = array(
								'href'   => esc_url( $link ),
								'title'  => esc_attr( $name ),
								'class'  => 'wpex-'. esc_attr( $key ),
								'rel'    => $nofollow,
								'target' => $target,
								'style'  => $add_style,
							);

							$l_attrs['class'] .= ' '. wpex_get_social_button_class( $style );

							$key  = 'vimeo-square' == $key ? 'vimeo' : $key;
							$icon = 'youtube' == $key ? 'youtube-play' : $key;
							$icon = 'bloglovin' == $key ? 'heart' : $icon;
							$icon = 'vimeo-square' == $key ? 'vimeo' : $icon;

							$output .= '<li>';
							
								$output .= '<a '. wpex_parse_attrs( $l_attrs ) . '>';

										$output .= '<span class="fa fa-'. esc_attr( $icon ) .'" aria-hidden="true"></span>';

										$output .= '<span class="screen-reader-text">' . esc_attr( $name ) . '</span>';

								$output .= '</a>';

							$output .= '</li>';

						}

					endforeach;

				$output .= '</ul>';

			$output .= '</div>';

			// After widget hook
			$output .= $args['after_widget'];

			// Echo output
			echo $output;

		}

		/**
		 * Parses style attribute for fallback styles
		 *
		 * @since 3.0.0
		 */
		public function parse_style( $style = '', $type = '' ) {
			if ( 'color' == $style && 'flat' == $type ) {
				return 'flat-color';
			}
			if ( 'color' == $style && 'graphical' == $type ) {
				return 'graphical-rounded';
			}
			if ( 'black' == $style && 'flat' == $type ) {
				return 'black-rounded';
			}
			if ( 'black' == $style && 'graphical' == $type ) {
				return 'black-rounded';
			}
			if ( 'black-color-hover' == $style && 'flat' == $type ) {
				return 'black-ch-rounded';
			}
			if ( 'black-color-hover' == $style && 'graphical' == $type ) {
				return 'black-ch-rounded';
			}
			return $style;
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
		public function update( $new, $old ) {

			// Sanitize data
			$instance = $old;
			$instance['title']           = ! empty( $new['title'] ) ? strip_tags( $new['title'] ) : null;
			$instance['description']     = ! empty( $new['description'] ) ? $new['description'] : null;
			$instance['style']           = ! empty( $new['style'] ) ? strip_tags( $new['style'] ) : 'flat-color';
			$instance['target']          = ! empty( $new['target'] ) ? strip_tags( $new['target'] ) : 'blank';
			$instance['size']            = ! empty( $new['size'] ) ? strip_tags( $new['size'] ) : '';
			$instance['align']           = ! empty( $new['align'] ) ? strip_tags( $new['align'] ) : 'left';
			$instance['border_radius']   = ! empty( $new['border_radius'] ) ? strip_tags( $new['border_radius'] ) : '';
			$instance['font_size']       = ! empty( $new['font_size'] ) ? strip_tags( $new['font_size'] ) : '';
			$instance['nofollow']        = ! empty( $new['nofollow'] ) ? 'on' : '';
			$instance['social_services'] = $new['social_services'];

			// Remove deprecated param
			$instance['type'] = null;

			// Return instance
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

			$instance = wp_parse_args( ( array ) $instance, array(
				'title'           => '',
				'description'     => '',
				'style'           => 'flat-color',
				'type'            => '',
				'font_size'       => '',
				'border_radius'   => '',
				'target'          => 'blank',
				'size'            => '',
				'social_services' => $this->social_services_array,
				'align'           => 'left',
				'nofollow'        => '',
			) ); ?>
			
			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Title', 'total' ); ?>:</label> 
				<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $instance['title'] ); ?>" />
			</p>

			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'description' ) ); ?>"><?php esc_html_e( 'Description','total' ); ?>:</label>
				<textarea class="widefat" rows="5" cols="20" id="<?php echo esc_attr( $this->get_field_id( 'description' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'description' ) ); ?>"><?php echo wpex_sanitize_data( $instance['description'], 'html' ); ?></textarea>
			</p>

			<?php
			// Styles
			$social_styles = wpex_social_button_styles();

			// Parse style
			$style = $this->parse_style( $instance['style'], $instance['type'] ); ?>

			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'style' ) ); ?>"><?php esc_html_e( 'Style', 'total' ); ?>:</label>
				<br />
				<select class="wpex-widget-select" name="<?php echo esc_attr( $this->get_field_name( 'style' ) ); ?>" id="<?php echo esc_attr( $this->get_field_id( 'style' ) ); ?>">
					<?php foreach ( $social_styles as $key => $val ) { ?>
						<option value="<?php echo esc_attr( $key ); ?>" <?php selected( $style, $key ) ?>><?php echo strip_tags( $val ); ?></option>
					<?php } ?>
				</select>
			</p>
			
			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'target' ) ); ?>"><?php esc_html_e( 'Link Target', 'total' ); ?>:</label>
				<br />
				<select class="wpex-widget-select" name="<?php echo esc_attr( $this->get_field_name( 'target' ) ); ?>" id="<?php echo esc_attr( $this->get_field_id( 'target' ) ); ?>">
					<option value="blank" <?php selected( $instance['target'], 'blank' ) ?>><?php esc_html_e( 'Blank', 'total' ); ?></option>
					<option value="self" <?php selected( $instance['target'], 'self' ) ?>><?php esc_html_e( 'Self', 'total' ); ?></option>
				</select>
			</p>

			<p>
				<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'nofollow' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'nofollow' ) ); ?>" type="checkbox" <?php checked( 'on', $instance['nofollow'], true ); ?> />
				<label for="<?php echo esc_attr( $this->get_field_id( 'nofollow' ) ); ?>"><?php esc_html_e( 'Add nofollow attribute to links.','total' ); ?></label>
			</p>

			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'align' ) ); ?>"><?php esc_attr_e( 'Align', 'total' ); ?>:</label>
				<br />
				<select class='wpex-widget-select' name="<?php echo esc_attr( $this->get_field_name( 'align' ) ); ?>" id="<?php echo esc_attr( $this->get_field_id( 'align' ) ); ?>">
					<option value="left" <?php selected( $instance['align'], 'left' ); ?>><?php esc_attr_e( 'Left', 'total' ); ?></option>
					<option value="center" <?php selected( $instance['align'], 'center' ); ?>><?php esc_attr_e( 'Center', 'total' ); ?></option>
					<option value="right" <?php selected( $instance['align'], 'right' ); ?>><?php esc_attr_e( 'Right', 'total' ); ?></option>
				</select>
			</p>
			
			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'size' ) ); ?>"><?php esc_html_e( 'Dimensions', 'total' ); ?>:</label>
				<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'size' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'size' ) ); ?>" type="text" value="<?php echo esc_attr( $instance['size'] ); ?>" />
				<small><?php esc_html_e( 'Example:', 'total' ); ?> 40px</small>
			</p>

			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'font_size' ) ); ?>"><?php esc_html_e( 'Icon Size', 'total' ); ?>:</label>
				<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'font_size' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'font_size' ) ); ?>" type="text" value="<?php echo esc_attr( $instance['font_size'] ); ?>" />
				<small><?php esc_html_e( 'Example:', 'total' ); ?> 18px</small>
			</p>

			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'border_radius' ) ); ?>"><?php esc_html_e( 'Border Radius', 'total' ); ?></label>
				<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'border_radius' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'border_radius' ) ); ?>" type="text" value="<?php echo esc_attr( $instance['border_radius'] ); ?>" />
				<small><?php esc_html_e( 'Example:', 'total' ); ?> 4px</small>
			</p>

			<?php
			$field_id_services   = $this->get_field_id( 'social_services' );
			$field_name_services = $this->get_field_name( 'social_services' ); ?>
			
			<label for="<?php echo esc_attr( $this->get_field_id( 'social_services' ) ); ?>"><?php esc_attr_e( 'Social Links', 'total' ); ?>:</label>

			<small style="display:block;padding-top:5px;"><?php esc_html_e( 'You can click and drag & drop your items to re-order them.', 'total' ); ?></small>
			
			<ul id="<?php echo esc_attr( $field_id_services ); ?>" class="wpex-social-widget-services-list">
				<input type="hidden" id="<?php echo esc_attr( $field_name_services ); ?>" value="<?php echo esc_attr( $field_name_services ); ?>" class="wpex-social-widget-services-hidden-field" />
				<?php
				// Social array
				$social_services_array = $this->social_services_array;
				
				// Get current services display
				$display_services = isset ( $instance['social_services'] ) ? $instance['social_services'] : '';
				
				// Loop through social services to display inputs
				foreach( $display_services as $key => $val ) :

					$url  = ! empty( $display_services[$key]['url'] ) ? $display_services[$key]['url'] : null;
					$name = $social_services_array[$key]['name'];

					// Set icon
					$icon = 'vimeo-square' == $key ? 'vimeo' : $key;
					$icon = 'youtube'      == $key ? 'youtube-play' : $icon;
					$icon = 'vimeo-square' == $key ? 'vimeo' : $icon; ?>

					<li id="<?php echo esc_attr( $field_id_services ); ?>_0<?php echo esc_attr( $key ); ?>">
						<p>
							<label for="<?php echo esc_attr( $field_id_services ); ?>-<?php echo esc_attr( $key ); ?>-name"><span class="fa fa-<?php echo esc_attr( $icon ); ?>"></span><?php echo strip_tags( $name ); ?>:</label>
							<input type="hidden" id="<?php echo esc_attr( $field_id_services ); ?>-<?php echo esc_attr( $key ); ?>-name" name="<?php echo esc_attr( $field_name_services .'['.$key.'][name]' ); ?>" value="<?php echo esc_attr( $name ); ?>">
							<input type="text" id="<?php echo esc_attr( $field_id_services ); ?>-<?php echo esc_attr( $key ); ?>-url" name="<?php echo esc_attr( $field_name_services .'['.$key.'][url]' ); ?>" value="<?php echo esc_attr( $url ); ?>" class="widefat" />
						</p>
					</li>

				<?php endforeach; ?>
			
			</ul>
			
		<?php
		}

		/**
		 * Load scripts for this widget
		 *
		 */
		public function scripts( $hook ) {

			// CSS
			wp_enqueue_style(
				'wpex-social-widget',
				wpex_asset_url( 'css/wpex-social-widget.css' ),
				false,
				WPEX_THEME_VERSION
			);

			// JS
			wp_enqueue_script(
				'wpex-social-widget',
				wpex_asset_url( 'js/dynamic/wpex-social-widget.js' ),
				array( 'jquery' ),
				WPEX_THEME_VERSION,
				true
			);

		}

	}

}
register_widget( 'WPEX_Fontawesome_Social_Widget' );