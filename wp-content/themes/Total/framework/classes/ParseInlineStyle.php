<?php
/**
 * Output inline style tag based on input
 *
 * @package Total WordPress Theme
 * @subpackage Framework
 * @version 4.6.5
 */

namespace TotalTheme;

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Start Class
class ParseInlineStyle {
	private $style;
	private $add_style;

	/**
	 * Class Constructor
	 *
	 * @since 2.0.0
	 */
	public function __construct( $atts, $add_style ) {
		$this->style = array();
		$this->add_style = $add_style;

		// Loop through shortcode atts and run class methods
		foreach ( $atts as $key => $value ) {
			if ( ! empty( $value ) ) {
				$method = 'parse_' . $key;
				if ( method_exists( $this, $method ) ) {
					$this->$method( $value );
				}
			}
		}

	}

	/**
	 * Display
	 *
	 * @since 2.0.0
	 */
	private function parse_display( $value ) {
		$this->style[] = 'display:' . $value . ';';
	}

	/**
	 * Float
	 *
	 * @since 3.5.0
	 */
	private function parse_float( $value ) {
		if ( 'center' == $value ) {
			$this->style[] = 'margin-right:auto;margin-left:auto;float:none;';
		} else {
			$this->style[] = 'float:' . $value . ';';
		}
	}

	/**
	 * Width
	 *
	 * @since 2.0.0
	 */
	private function parse_width( $value ) {
		$this->style[] = 'width:' .  wpex_sanitize_data( $value, 'px-pct' )  . ';';
	}

	/**
	 * Min-Width
	 *
	 * @since 4.6.1
	 */
	private function parse_min_width( $value ) {
		$this->style[] = 'min-width:' .  wpex_sanitize_data( $value, 'font_size' )  . ';';
	}

	/**
	 * Background
	 *
	 * @since 2.0.0
	 */
	private function parse_background( $value ) {
		$this->style[] = 'background:' . $value . ';';
	}

	/**
	 * Background Image
	 *
	 * @since 2.0.0
	 */
	private function parse_background_image( $value ) {
		$this->style[] = 'background-image:url(' . esc_url( $value ) . ');';
	}

	/**
	 * Background Position
	 *
	 * @since 4.4
	 */
	private function parse_background_position( $value ) {
		$this->style[] = 'background-position:' . esc_attr( $value ) . ';';
	}

	/**
	 * Background Color
	 *
	 * @since 2.0.0
	 */
	private function parse_background_color( $value ) {
		$value = 'none' == $value ? 'transparent' : $value;
		$this->style[] = 'background-color:' . $value . ';';
	}

	/**
	 * Border
	 *
	 * @since 2.0.0
	 */
	private function parse_border( $value ) {
		$value = 'none' == $value ? '0' : $value;
		$this->style[] = 'border:' . $value . ';';
	}

	/**
	 * Border: Color
	 *
	 * @since 2.0.0
	 */
	private function parse_border_color( $value ) {
		$value = 'none' == $value ? 'transparent' : $value;
		$this->style[] = 'border-color:' . $value . ';';
	}

	/**
	 * Border: Bottom Color
	 *
	 * @since 2.0.0
	 */
	private function parse_border_bottom_color( $value ) {
		$value = 'none' == $value ? 'transparent' : $value;
		$this->style[] = 'border-bottom-color:' . $value . ';';
	}

	/**
	 * Border Width
	 *
	 * @since 2.0.0
	 */
	private function parse_border_width( $value ) {
		$this->style[] = 'border-width:' . $value . ';';
	}

	/**
	 * Border Style
	 *
	 * @since 2.0.0
	 */
	private function parse_border_style( $value ) {
		$this->style[] = 'border-style:' . $value . ';';
	}

	/**
	 * Border: Top Width
	 *
	 * @since 2.0.0
	 */
	private function parse_border_top_width( $value ) {
		$this->style[] = 'border-top-width:' .  wpex_sanitize_data( $value, 'px' )  . ';';
	}

	/**
	 * Border: Bottom Width
	 *
	 * @since 2.0.0
	 */
	private function parse_border_bottom_width( $value ) {
		$this->style[] = 'border-bottom-width:' .  wpex_sanitize_data( $value, 'px' )  . ';';
	}

	/**
	 * Margin
	 *
	 * @since 2.0.0
	 */
	private function parse_margin( $value ) {

		if ( $this->parse_trbl_property( $value, 'margin' ) ) {
			return;
		}

		$value          = ( 'none' == $value ) ? '0' : $value;
		$value          = is_numeric( $value ) ? $value  . 'px' : $value;
		$this->style[]  = 'margin:' . $value . ';';

	}

	/**
	 * Margin: Right
	 *
	 * @since 2.0.0
	 */
	private function parse_margin_right( $value ) {
		$this->style[] = 'margin-right:' .  wpex_sanitize_data( $value, 'px-pct' )  . ';';
	}

	/**
	 * Margin: Left
	 *
	 * @since 2.0.0
	 */
	private function parse_margin_left( $value ) {
		$this->style[] = 'margin-left:' .  wpex_sanitize_data( $value, 'px-pct' )  . ';';
	}

	/**
	 * Margin: Top
	 *
	 * @since 2.0.0
	 */
	private function parse_margin_top( $value ) {
		$this->style[] = 'margin-top:' .  wpex_sanitize_data( $value, 'px' )  . ';';
	}

	/**
	 * Margin: Bottom
	 *
	 * @since 2.0.0
	 */
	private function parse_margin_bottom( $value ) {
		$this->style[] = 'margin-bottom:' .  wpex_sanitize_data( $value, 'px' )  . ';';
	}

	/**
	 * Padding
	 *
	 * @since 2.0.0
	 */
	private function parse_padding( $value ) {

		if ( $this->parse_trbl_property( $value, 'padding' ) ) {
			return;
		}

		$value = 'none' == $value ? '0' : $value;
		$value = is_numeric( $value ) ? $value  . 'px' : $value;
		$this->style[] = 'padding:' . $value . ';';

	}

	/**
	 * Padding: Top
	 *
	 * @since 2.0.0
	 */
	private function parse_padding_top( $value ) {
		$this->style[] = 'padding-top:' .  wpex_sanitize_data( $value, 'px' )  . ';';
	}

	/**
	 * Padding: Bottom
	 *
	 * @since 2.0.0
	 */
	private function parse_padding_bottom( $value ) {
		$this->style[] = 'padding-bottom:' .   wpex_sanitize_data( $value, 'px' )  . ';';
	}

	/**
	 * Padding: Left
	 *
	 * @since 2.0.0
	 */
	private function parse_padding_left( $value ) {
		$this->style[] = 'padding-left:' .  wpex_sanitize_data( $value, 'px-pct' )  . ';';
	}

	/**
	 * Padding: Right
	 *
	 * @since 2.0.0
	 */
	private function parse_padding_right( $value ) {
		$this->style[] = 'padding-right:' .  wpex_sanitize_data( $value, 'px-pct' )  . ';';
	}

	/**
	 * Font-Size
	 *
	 * @since 2.0.0
	 */
	private function parse_font_size( $value ) {
		if ( $value && strpos( $value, '|' ) === false ) {
			if ( $value = wpex_sanitize_data( $value, 'font_size' ) ) {
				$this->style[] = 'font-size:' .  $value  . ';';
			}
		}
	}

	/**
	 * Font Weight
	 *
	 * @since 2.0.0
	 */
	private function parse_font_weight( $value ) {
		$this->style[] = 'font-weight:' .  wpex_sanitize_data( $value, 'font_weight' )  . ';';
	}

	/**
	 * Font Family
	 *
	 * @since   2.1.0
	 */
	private function parse_font_family( $value ) {
		if ( $value = wpex_sanitize_font_family( $value ) ) {
			$value = str_replace( '"', "'", $value );
			$this->style[] = 'font-family:' . $value . ';';
		}
	}

	/**
	 * Color
	 *
	 * @since 2.0.0
	 */
	private function parse_color( $value ) {
		$this->style[] = 'color:' .  $value  . ';';
	}

	/**
	 * Opacity
	 *
	 * @since 2.0.0
	 */
	private function parse_opacity( $value ) {
		if ( $opacity = wpex_sanitize_data( $value, 'opacity' ) ) {
			$this->style[] = 'opacity:' .  $opacity  . ';';
		}
	}

	/**
	 * Text Align
	 *
	 * @since 2.0.0
	 */
	private function parse_text_align( $value ) {
		if ( 'textcenter' == $value ) {
			$value = 'center';
		} elseif ( 'textleft' == $value ) {
			$value = 'left';
		} elseif ( 'textright' == $value ) {
			$value = 'right';
		}
		$this->style[]  = 'text-align:' . $value . ';';
	}

	/**
	 * Text Transform
	 *
	 * @since 2.0.0
	 */
	private function parse_text_transform( $value ) {
		$this->style[] = 'text-transform:' . $value . ';';
	}

	/**
	 * Letter Spacing
	 *
	 * @since 2.0.0
	 */
	private function parse_letter_spacing( $value ) {
		$this->style[] = 'letter-spacing:' .  wpex_sanitize_letter_spacing( $value )  . ';';
	}

	/**
	 * Line-Height
	 *
	 * @since 2.0.0
	 */
	private function parse_line_height( $value ) {
		$this->style[] = 'line-height:' . $value . ';';
	}

	/**
	 * Line-Height with px sanitize
	 *
	 * @since 2.0.0
	 */
	private function parse_line_height_px( $value ) {
		$this->style[] = 'line-height:' .  wpex_sanitize_data( $value, 'px' )  . ';';
	}

	/**
	 * Height
	 *
	 * @since 2.0.0
	 */
	private function parse_height( $value ) {
		$this->style[] = 'height:' .  wpex_sanitize_data( $value, 'px-pct' )  . ';';
	}

	/**
	 * Height with px sanitize
	 *
	 * @since 2.0.0
	 */
	private function parse_height_px( $value ) {
		$this->style[] = 'height:' .  wpex_sanitize_data( $value, 'px' )  . ';';
	}

	/**
	 * Min-Height
	 *
	 * @since 2.0.0
	 */
	private function parse_min_height( $value ) {
		$this->style[] = 'min-height:' .  wpex_sanitize_data( $value, 'px-pct' )  . ';';
	}

	/**
	 * Border Radius
	 *
	 * @since 2.0.0
	 */
	private function parse_border_radius( $value ) {
		$this->style[] = 'border-radius:' .  wpex_sanitize_data( $value, 'border_radius' )  . ';';
	}

	/**
	 * Position: Top
	 *
	 * @since 2.0.0
	 */
	private function parse_top( $value ) {
		$this->style[] = 'top:' .  wpex_sanitize_data( $value, 'px-pct' )  . ';';
	}

	/**
	 * Position: Bottom
	 *
	 * @since 2.0.0
	 */
	private function parse_bottom( $value ) {
		$this->style[] = 'bottom:' .  wpex_sanitize_data( $value, 'px-pct' )  . ';';
	}

	/**
	 * Position: Right
	 *
	 * @since 2.0.0
	 */
	private function parse_right( $value ) {
		$this->style[] = 'right:' .  wpex_sanitize_data( $value, 'px-pct' )  . ';';
	}

	/**
	 * Position: Left
	 *
	 * @since 2.0.0
	 */
	private function parse_left( $value ) {
		$this->style[] = 'left:' .  wpex_sanitize_data( $value, 'px-pct' )  . ';';
	}

	/**
	 * Style
	 *
	 * @since 3.5.0
	 */
	private function parse_font_style( $value ) {
		$this->style[] = 'font-style:' .  esc_html( $value )  . ';';
	}

	/**
	 * Text Decoration
	 *
	 * @since 3.5.0
	 */
	private function parse_text_decoration( $value ) {
		$this->style[] = 'text-decoration:' .  esc_html( $value )  . ';';
	}

	/**
	 * Italic
	 *
	 * @since 4.3
	 */
	private function parse_italic( $value ) {
		if ( 'true' ==  $value || 'yes' == $value ) {
			$this->style[] = 'font-style:italic;';
		}
	}

	/**
	 * Animation delay
	 *
	 * @since 4.4
	 */
	private function parse_animation_delay( $value ) {
		$this->style[] = 'animation-delay:' . floatval( $value ) . 's;';
	}

	/**
	 * Transition Speed
	 *
	 * @since 4.4.1
	 */
	private function parse_transition_speed( $value ) {
		$this->style[] = 'transition-duration:' . floatval( $value ) . 's;';
	}

	/**
	 * Parse top/right/bottom/left fields
	 *
	 * @since 4.3
	 */
	private function parse_trbl_property( $value, $property ) {

		if ( ! function_exists( 'vcex_parse_multi_attribute' ) ) {
			return;
		}

		if ( false !== strpos( $value, ':' ) && $values = vcex_parse_multi_attribute( $value ) ) {

			// All values are the same
			if ( isset( $values['top'] )
				&& count( $values ) == 4
				&& count( array_unique( $values ) ) <= 1
			) {
				$value          = $values['top'];
				$value          = ( 'none' == $value ) ? '0' : $value;
				$value          = is_numeric( $value ) ? $value  . 'px' : $value;
				$this->style[]  = $property . ':' . $value . ';';
				return true;
			}

			// Values are different
			foreach ( $values as $k => $v ) {

				if ( 0 == $v ) {
					$v = '0px'; // 0px fix
				}

				if ( ! empty( $v ) ) {
					
					$method = 'parse_' . $property . '_' . $k;
					if ( method_exists( $this, $method ) ) {
						$this->$method( $v );
					}

				}

			}

			return true;

		}

	}

	/**
	 * Returns the styles
	 *
	 * @since 2.0.0
	 */
	public function return_style() {
		if ( ! empty( $this->style ) ) {
			$this->style = implode( false, $this->style );
			if ( $this->add_style ) {
				return ' style="' .  esc_attr( $this->style )  . '"';
			} else {
				return esc_attr( $this->style );
			}
		} else {
			return null;
		}
	}

} // End Class