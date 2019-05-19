<?php
/**
 * Visual Composer Countdown
 *
 * @package Total WordPress Theme
 * @subpackage VC Functions
 * @version 4.8
 */

if ( ! class_exists( 'VCEX_Countdown_Shortcode' ) ) {

	class VCEX_Countdown_Shortcode {

		/**
		 * Main constructor
		 *
		 * @since 3.5.3
		 */
		public function __construct() {
			add_shortcode( 'vcex_countdown', array( $this, 'output' ) );
			vc_lean_map( 'vcex_countdown', array( $this, 'map' ) );
			vc_add_shortcode_param( 'vcex_timezones', array( $this, 'timezones' ) );
		}

		/**
		 * Enqueue scripts
		 *
		 * @since 4.3
		 */
		public function enqueue_scripts( $atts ) {

			wp_enqueue_script(
				'countdown',
				wpex_asset_url( 'js/dynamic/countdown.js' ),
				array( 'jquery' ),
				'2.1.0',
				true
			);

			if ( wpex_vc_is_inline() || $atts['timezone'] ) {

				wp_enqueue_script(
					'moment-with-locales',
					wpex_asset_url( 'js/dynamic/moment-with-locales.min.js' ),
					array( 'jquery' ),
					'2.10.0',
					true
				);

				wp_enqueue_script(
					'moment-timezone-with-data',
					wpex_asset_url( 'js/dynamic/moment-timezone-with-data.min.js' ),
					array( 'jquery' ),
					'2.10.0',
					true
				);

			}
				
		}

		/**
		 * Shortcode output => Get template file and display shortcode
		 *
		 * @since 3.5.3
		 */
		public function output( $atts, $content = null ) {
			ob_start();
			include( locate_template( 'vcex_templates/vcex_countdown.php' ) );
			return ob_get_clean();
		}

		/**
		 * Map shortcode to VC
		 *
		 * @since 3.5.3
		 */
		public function map() {
			return array(
				'name' => __( 'Countdown', 'total' ),
				'description' => __( 'Animated countdown clock', 'total' ),
				'base' => 'vcex_countdown',
				'icon' => 'vcex-countdown vcex-icon ticon ticon-clock-o',
				'category' => wpex_get_theme_branding(),
				'params' => array(
					// General
					array(
						'type' => 'textfield',
						'admin_label' => true,
						'heading' => __( 'Extra class name', 'total' ),
						'description' => __( 'Style particular content element differently - add a class name and refer to it in custom CSS.', 'total' ),
						'param_name' => 'el_class',
					),
					array(
						'type' => 'vcex_visibility',
						'heading' => __( 'Visibility', 'total' ),
						'param_name' => 'visibility',
					),
					vcex_vc_map_add_css_animation(),
					array(
						'type' => 'vcex_timezones',
						'heading' => __( 'Time Zone', 'total' ),
						'param_name' => 'timezone',
						'description' => __( 'If a time zone is not selected the time zone will be based on the visitors computer time.', 'total' ),
					),
					array(
						'type' => 'dropdown',
						'heading' => __( 'End Month', 'total' ),
						'param_name' => 'end_month',
						'value' => array( '1', '2', '3', '4', '5', '6', '7', '8', '9', '10', '11', '12' ),
					),
					array(
						'type' => 'dropdown',
						'heading' => __( 'End Day', 'total' ),
						'param_name' => 'end_day',
						'value' => array( '1', '2', '3', '4', '5', '6', '7', '8', '9', '10', '11', '12', '13', '14', '15', '16', '17', '18', '19', '20', '21', '22', '23', '24', '25', '26', '27', '28', '29', '30', '31' ),
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'End Year', 'total' ),
						'param_name' => 'end_year',
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'End Time', 'total' ),
						'param_name' => 'end_time',
						'description' => __( 'Enter your custom end time in military format. Example if your event starts at 1:30pm enter 13:30', 'total' ),
					),
					// Typography
					array(
						'type' => 'colorpicker',
						'heading' => __( 'Color', 'total' ),
						'param_name' => 'color',
						'group' => __( 'Typography', 'total' ),
					),
					array(
						'type' => 'vcex_font_family_select',
						'heading' => __( 'Font Family', 'total' ),
						'param_name' => 'font_family',
						'group' => __( 'Typography', 'total' ),
					),
					array(
						'type' => 'vcex_responsive_sizes',
						'target' => 'font-size',
						'heading' => __( 'Font Size', 'total' ),
						'param_name' => 'font_size',
						'group' => __( 'Typography', 'total' ),
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Line Height', 'total' ),
						'param_name' => 'line_height',
						'group' => __( 'Typography', 'total' ),
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Letter Spacing', 'total' ),
						'param_name' => 'letter_spacing',
						'group' => __( 'Typography', 'total' ),
					),
					array(
						'type' => 'vcex_ofswitch',
						'std' => 'false',
						'heading' => __( 'Italic', 'total' ),
						'param_name' => 'italic',
						'group' => __( 'Typography', 'total' ),
					),
					array(
						'type' => 'vcex_font_weight',
						'heading' => __( 'Font Weight', 'total' ),
						'param_name' => 'font_weight',
						'group' => __( 'Typography', 'total' ),
					),
					array(
						'type' => 'vcex_text_alignments',
						'heading' => __( 'Text Align', 'total' ),
						'param_name' => 'text_align',
						'group' => __( 'Typography', 'total' ),
					),
					// Translations
					array(
						'type' => 'textfield',
						'heading' => __( 'Days', 'total' ),
						'param_name' => 'days',
						'group' =>  __( 'Strings', 'total' ),
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Hours', 'total' ),
						'param_name' => 'hours',
						'group' =>  __( 'Strings', 'total' ),
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Minutes', 'total' ),
						'param_name' => 'minutes',
						'group' =>  __( 'Strings', 'total' ),
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Seconds', 'total' ),
						'param_name' => 'seconds',
						'group' =>  __( 'Strings', 'total' ),
					),
				)
			);
		}
		
		/**
		 * Return array of timezones
		 *
		 * @since 4.5.4
		 */
		public function timezones( $settings, $value ) {

			$output = '<select name="'
					. $settings['param_name']
					. '" class="wpb_vc_param_value wpb-input wpb-select vcex-chosen '
					. $settings['param_name']
					. ' ' . $settings['type'] .'">';

			$output .= '<option value="" '. selected( $value, '', false ) .'>&mdash;</option>';
			
			if ( function_exists( 'timezone_identifiers_list' ) ) {

				$zones = timezone_identifiers_list();

				foreach ( $zones as $zone ) {

					$output .= '<option value="'. esc_attr( $zone )  .'" '. selected( $value, $zone, false ) .'>'. esc_attr( $zone ) .'</option>';

				}

			}

			$output .= '</select>';

			return $output;

		}

	}
}
new VCEX_Countdown_Shortcode;