<?php
/**
 * Widget Builder Class makes it easier to build custom widgets for WP
 *
 * @package Total WordPress Theme
 * @subpackage Framework
 * @version 4.8
 */

namespace TotalTheme;

use WP_Query;

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class WidgetBuilder extends \WP_Widget {

	/**
	 * Widget name.
	 *
	 * @var string
	 */
	public $name = '';

	/**
	 * Widget id_base.
	 *
	 * @var string
	 */
	public $id_base = '';

	/**
	 * Widget fields.
	 *
	 * @var array
	 */
	private $fields = array();

	/**
	 * Return correct branding string.
	 *
	 * @since 4.8
	 *
	 * @access public
	 */
	public function branding() {
		$branding = wpex_get_theme_branding();
		return $branding ? $branding . ' - ' : '';
	}

	/**
	 * Create Widget.
	 *
	 * @since 4.8
	 *
	 * @access public
	 * @param array @args Widget arguments.
	 * @return void
	 */
	public function create_widget( $args ) {

		// Set widget vars
		$this->name    = wp_strip_all_tags( $args['name'] );
		$this->id_base = wp_strip_all_tags( $args['id_base'] );
		$this->options = isset( $args['options'] ) ? $args['options'] : '';
		$this->fields  = $args['fields'];

		// Add filter to options
		$this->options = apply_filters( $this->id_base . '_widget_options', $this->options );

		// Call WP_Widget to create the widget
		parent::__construct(
			$this->id_base,
			$this->name,
			$this->options
		);

	}

	/**
	 * Return default values.
	 *
	 * @since 4.8
	 *
	 * @access   public
	 * @return   array $defaults Returns the default arguments for this widget.
	 */
	public function get_defaults() {
		if ( empty( $this->fields ) || ! is_array( $this->fields ) ) {
			return;
		}
		$defaults = array();
		foreach ( $this->fields as $field ) {
			if ( empty( $field['default'] ) && isset( $field['choices'] ) && is_array( $field['choices'] ) ) {
				reset( $field['choices'] );
				$field['default'] = key( $field['choices'] );
			}
			$defaults[$field['id']] = isset( $field['default'] ) ? $field['default'] : '';
		}
		return $defaults;
	}

	/**
	 * Parse insance for live output.
	 *
	 * @since 4.8
	 *
	 * @access   public
	 * @return   array $instance Returns the current widget instance.
	 */
	public function parse_instance( $instance ) {
		$defaults = $this->get_defaults();
		$instance = wp_parse_args( $instance, $defaults );
		foreach ( $instance as $k => $v ) {
			if ( empty( $v ) && isset( $defaults[$k] ) ) {
				$instance[$k] = $defaults[$k];
			}
		}
		return $instance;
	}

	/**
	 * Output widget title
	 *
	 * @since 4.8
	 *
	 * @access   public
	 * @return   array $instance Returns the current widget instance.
	 */
	public function widget_title( $args, $instance ) {
		if ( ! empty( $instance['title'] ) ) {
			echo $args['before_title'] . apply_filters( 'widget_title', $instance['title'] ) . $args['after_title'];
		}
	}

	/**
	 * Sanitize widget form values as they are saved.
	 *
	 * @see WP_Widget::update()
	 * @since 4.8
	 *
	 * @param array $new_instance Values just sent to be saved.
	 * @param array $old_instance Previously saved values from database.
	 *
	 * @return array Updated safe values to be saved.
	 */
	public function update( $new_instance, $old_instance ) {
		$instance = $old_instance;

		foreach ( $this->fields as $field ) {

			$field_id   = $field['id'];
			$field_type = $field['type'];
			$default    = isset( $field['default'] ) ? $field['default'] : '';

			if ( isset( $new_instance[$field_id] ) ) {

				if ( 'checkbox' == $field_type ) {

					$instance[$field_id] = (bool) true;

				} elseif ( 'select' == $field_type ) {

					$array_to_check = array();

					if ( is_array( $field['choices'] ) ) {
						$array_to_check = $field['choices'];
					} else {
						$method = 'choices_' . $field['choices'];
						if ( method_exists( $this, $method ) ) {
							$array_to_check = $this->$method( $field );
						}
					}

					$instance[$field_id] = ( array_key_exists( $new_instance[$field_id], $array_to_check ) ? $new_instance[$field_id] : $default );

				} else {

					$sanitize = isset( $field['sanitize'] ) ? $field['sanitize'] : $field_type;

					if ( 'text' == $field_type || 'image' == $field_type || 'media_upload' == $field_type ) {
						$sanitize = 'text_field';
					}

					$instance[$field_id] = \wpex_sanitize_data( $new_instance[$field_id], $sanitize );

				}

			} else {

				if ( 'checkbox' == $field_type ) {
					$instance[$field_id] = (bool) false;
				} else {
					$instance[$field_id] = '';
				}

			}

		}

		return $instance;

	}

	/**
	 * Back-end widget form.
	 *
	 * @since 4.8
	 *
	 * @see WP_Widget::form()
	 *
	 * @param array $instance Previously saved values from database.
	 */
	public function form( $instance ) {

		foreach ( $this->fields as $field ) {
			$id             = $field['id'];
			$field['class'] = 'widefat';
			$field['id']    = $this->get_field_id( $id );
			$field['name']  = $this->get_field_name( $id );
			if ( empty( $instance ) ) {
				$default = isset( $field['std'] ) ? $field['std'] : ''; // new instance
				$default = isset( $field['default'] ) ? $field['default'] : $default;
			} else {
				$default = isset( $field['default'] ) ? $field['default'] : ''; // already saved instance
			}
			$field['value'] = isset( $instance[$id] ) ? $instance[$id] : $default;
			$this->add_field( $field );
		}

	}

	/**
	 * Adds a new field to the admin form.
	 *
	 * @since 4.8
	 *
	 * @access public
	 * @param array $field Field parameters.
	 * @return string
	 */
	public function add_field( $field ) {

		$type = isset( $field['type' ] ) ? $field['type' ] : '';

		$method_name = 'field_' . $type;
		$description = '';

		if ( method_exists( $this, $method_name ) ) {

			if ( isset( $field['description'] ) ) {
				$description = '<br /><small class="description" style="display:block;padding:6px 0 0;clear:both;">' . $field['description'] . '</small>';
			}

			echo '<p>' . $this->$method_name( $field ) . $description . '</p>';

		}

	}

	/**
	 * Return field label for admin form.
	 *
	 * @since 4.8
	 *
	 * @access private
	 * @return string
	 */
	private function field_label( $field, $semicolon = true ) {

		$output = '<label for="' . esc_attr( $field['id'] ). '">';

			$output .= esc_html( $field['label'] );

			if ( $semicolon ) {
				$output .= ':';
			}

		$output .= '</label>';

		return $output;
	}

	/**
	 * Return text field for admin form.
	 *
	 * @since 4.8
	 *
	 * @access private
	 * @return string
	 */
	private function field_text( $field, $output = '' ) {

		$output .= $this->field_label( $field );

		$output .= '<input type="text"';

		if ( isset( $field['class'] ) ) {
			$output .= ' class="' . esc_attr( $field['class'] ) . '"';
		}

		$default = isset( $field['default'] ) ? $field['default'] : '';
		$value   = isset( $field['value'] ) ? $field['value'] : $default;

		$output .= ' id="' . esc_attr( $field['id'] ) . '" name="' . esc_attr( $field['name'] ) . '" value="' . esc_attr( $value ) . '"';

		if ( isset( $field['size'] ) ) {
			$output .= ' size="' . esc_attr( $field['size'] ) . '" ';
		}

		$output .= ' />';

		return $output;

	}

	/**
	 * Return url field for admin form.
	 *
	 * @since 4.8
	 *
	 * @access private
	 * @return string
	 */
	private function field_url( $field, $output = '' ) {

		$output .= $this->field_label( $field );

		$output .= '<input type="url"';

		if ( isset( $field['class'] ) ) {
			$output .= ' class="' . esc_attr( $field['class'] ) . '"';
		}

		$default = isset( $field['default'] ) ? $field['default'] : '';
		$value   = isset( $field['value'] ) ? esc_url( $field['value'] ) : $default;

		$output .= ' id="' . esc_attr( $field['id'] ) . '" name="' . esc_attr( $field['name'] ) . '" value="' . esc_attr( esc_url( $value ) ) . '" placeholder="http://"';

		if ( isset( $field['size'] ) ) {
			$output .= ' size="' . esc_attr( $field['size'] ) . '" ';
		}

		$output .= ' />';

		return $output;

	}

	/**
	 * Return textarea field for admin form.
	 *
	 * @since 4.8
	 *
	 * @access private
	 * @return string
	 */
	private function field_textarea( $field, $output = '' ) {

		$output .= $this->field_label( $field );

		$output .= '<textarea';

			if ( isset( $field['class'] ) ) {
				$output .= ' class="' . esc_attr( $field['class'] ) . '"';
			}

			$output .= ' id="' . esc_attr( $field['id'] ) . '" name="' . esc_attr( $field['name'] ) . '"';

			$rows = isset( $field['rows'] ) ? $field['rows'] : 5;
			$output .= ' rows="' . esc_attr( $rows ) . '"';

		$output .= '>';

		$default = isset( $field['default'] ) ? $field['default'] : '';
		$value   = isset( $field['value'] ) ? $field['value'] : $default;

		if ( $value ) {

			if ( isset( $field['sanitize'] ) ) {

				$output .= \wpex_sanitize_data( $value, $field['sanitize'] );

			} else {

				$output .= wp_kses_post( $value );

			}

		}

		$output .= '</textarea>';

		return $output;

	}

	/**
	 * Return media upload field for admin form.
	 *
	 * @since 4.8
	 *
	 * @access private
	 * @return string
	 */
	private function field_media_upload( $field, $output = '' ) {

		wp_enqueue_media();
		wp_enqueue_script(
			'wpex-media-upload-btn',
			wpex_asset_url( 'js/dynamic/admin/wpex-media-upload-btn.min.js' )
		);

		$output .= $this->field_label( $field );

		$output .= '<input type="text"';

		if ( isset( $field['class'] ) ) {
			$output .= ' class="' . esc_attr( $field['class'] ) . '"';
		}

		$default = isset( $field['default'] ) ? $field['default'] : '';
		$value   = isset( $field['value'] ) ? esc_attr( $field['value'] ) : $default;

		$output .= ' id="' . esc_attr( $field['id'] ) . '" name="' . esc_attr( $field['name'] ) . '" value="' . esc_attr( $value ) . '"';

		if ( isset( $field['size'] ) ) {
			$output .= ' size="' . esc_attr( $field['size'] ) . '" ';
		}

		$output .= ' />';

		$output .= '<input style="margin-top:8px;" class="wpex-upload-button button button-secondary" type="button" value="'. esc_html__( 'Upload/Select', 'total' ) .'" />';

		return $output;

	}

	/**
	 * Return select field for admin form.
	 *
	 * @since 4.8
	 *
	 * @access private
	 * @return string
	 */
	private function field_select( $field, $output = '' ) {

		if ( empty( $field['choices'] ) ) {
			return;
		}

		$choices = $field['choices'];

		if ( ! is_array( $choices ) ) {
			$method = 'choices_' . $choices;
			if ( method_exists( $this, $method ) ) {
				$field['choices'] = $this->$method( $field );
			}
		}

		$output .= $this->field_label( $field );

		$output .= '<select';

			if ( isset( $field['class'] ) ) {
				$output .= ' class="' . esc_attr( $field['class'] ) . '"';
			}

			$output .= ' id="' . esc_attr( $field['id'] ) . '" name="' . esc_attr( $field['name'] ) . '"';

		$output .= '>';

		$default = isset( $field['default'] ) ? $field['default'] : '';
		$value   = isset( $field['value'] ) ? $field['value'] : $default;

		if ( is_array( $field['choices'] ) ) {

			foreach( $field['choices'] as $id => $label ) {

				$output .= '<option value="' . $id . '" '. selected( $value, $id, false ) . '>' .  $label . '</option>';

			}

		}

		$output .= '</select>';

		return $output;

	}

	/**
	 * Return checkbox field for admin form.
	 *
	 * @since 4.8
	 *
	 * @access private
	 * @return string
	 */
	private function field_checkbox( $field, $output = '' ) {

		$output .= '<input type="checkbox"';

			if ( isset( $field['class'] ) ) {
				$output .= ' class="' . esc_attr( $field['class'] ) . '"';
			}

			$default = isset( $field['default'] ) ? $field['default'] : 'off';
			$value   = isset( $field['value'] ) ? $field['value'] : $default;

			$output .= ' id="' . esc_attr( $field['id'] ) . '" name="' . esc_attr( $field['name'] ) . '"';

			$output .= ' ' . checked( (bool) $value, true, false );

		$output .= ' />';

		$output .= $this->field_label( $field, false );

		return $output;

	}

	/**
	 * Return number field for admin form.
	 *
	 * @since 4.8
	 *
	 * @access private
	 * @return string
	 */
	private function field_number( $field, $output = '' ) {

		$output .= $this->field_label( $field );

		$output .= '<input type="number"';

		if ( isset( $field['class'] ) ) {
			$output .= ' class="' . esc_attr( $field['class'] ) . '"';
		}

		$default = isset( $field['default'] ) ? $field['default'] : '';
		$value   = isset( $field['value'] ) ? floatval( $field['value'] ) : $default;
		$min     = isset( $field['min'] ) ? $field['min'] : '';
		$max     = isset( $field['max'] ) ? $field['max'] : '';
		$step    = isset( $field['step'] ) ? $field['step'] : '';

		$output .= ' id="' . esc_attr( $field['id'] ) . '" name="' . esc_attr( $field['name'] ) . '" value="' . esc_attr( $value ) . '"';

		$output .= ' min="' . esc_attr( $min ) . '" ';
		$output .= ' max="' . esc_attr( $max ) . '" ';
		$output .= ' step="' . esc_attr( $step ) . '" ';

		$output .= ' />';

		return $output;

	}

	/**
	 * Return post_types choices for admin form.
	 *
	 * @since 4.8
	 *
	 * @access private
	 * @return string
	 */
	private function choices_post_types() {
		return wpex_get_post_types( 'wpex_recent_posts_thumb_widget', array( 'attachment' ) );
	}

	/**
	 * Return taxonomies choices for admin form.
	 *
	 * @since 4.8
	 *
	 * @access private
	 * @return string
	 */
	private function choices_taxonomies() {

		$taxonomies = array( '' => '-' );

		$get_taxonomies = get_taxonomies( array(
			'public' => true,
		), 'objects' );

		foreach ( $get_taxonomies as $get_taxonomy ) {
			$taxonomies[ $get_taxonomy->name ] = ucfirst( $get_taxonomy->labels->singular_name );
		}

		return $taxonomies;

	}

	/**
	 * Return query_orderby choices for admin form.
	 *
	 * @since 4.8
	 *
	 * @access private
	 * @return string
	 */
	private function choices_query_orderby() {
		return array(
			'date'          => __( 'Date', 'total' ),
			'title'         => __( 'Title', 'total' ),
			'modified'      => __( 'Modified', 'total' ),
			'author'        => __( 'Author', 'total' ),
			'rand'          => __( 'Random', 'total' ),
			'comment_count' => __( 'Comment Count', 'total' ),
		);
	}

	/**
	 * Return query_order choices for admin form.
	 *
	 * @since 4.8
	 *
	 * @access private
	 * @return string
	 */
	private function choices_query_order() {
		return array(
			'desc' => __( 'Descending', 'total' ),
			'asc'  => __( 'Ascending', 'total' ),
		);
	}

	/**
	 * Return categories choices for admin form.
	 *
	 * @since 4.8
	 *
	 * @access private
	 * @return string
	 */
	private function choices_categories() {
		$choices = array(
			'' => __( 'All', 'total' ),
		);
		$terms = get_terms( 'category' );
		if ( $terms ) {
			foreach ( $terms as $term ) {
				$choices[ $term->term_id ] = $term->name;
			}
		}
		return $choices;
	}

	/**
	 * Return intermediate_image_sizes choices for admin form.
	 *
	 * @since 4.8
	 *
	 * @access private
	 * @return string
	 */
	private function choices_intermediate_image_sizes( $field ) {
		if ( isset( $field['exclude_custom'] ) ) {
			$sizes = array( '' => __( 'Default', 'total' ) );
		} else {
			$sizes = array( 'wpex-custom' => __( 'Custom', 'total' ) );
		}
		$get_sizes = array_keys( wpex_get_thumbnail_sizes() );
		$sizes = $sizes + array_combine( $get_sizes, $get_sizes );
		return $sizes;
	}

	/**
	 * Return image_crop_locations choices for admin form.
	 *
	 * @since 4.8
	 *
	 * @access private
	 * @return string
	 */
	private function choices_image_crop_locations() {
		return wpex_image_crop_locations();
	}

	/**
	 * Return image_hovers choices for admin form.
	 *
	 * @since 4.8
	 *
	 * @access private
	 * @return string
	 */
	private function choices_image_hovers() {
		return wpex_image_hovers();
	}

	/**
	 * Return menus choices for admin form.
	 *
	 * @since 4.8
	 *
	 * @access private
	 * @return string
	 */
	private function choices_menus() {

		$menus = array();

		$get_menus = get_terms( 'nav_menu', array(
			'hide_empty' => false,
		) );

		if ( ! empty( $get_menus ) ) {
			foreach ( $get_menus as $menu ) {
				$menus[$menu->term_id] = $menu->name;
			}
		}

		return $menus;

	}

	/**
	 * Return posts choices for admin form.
	 *
	 * @since 4.8
	 *
	 * @access private
	 * @return string
	 */
	private function choices_posts( $field ) {

		$posts = array();

		$ids = new WP_Query( array(
			'post_type'      => $field['post_type'],
			'posts_per_page' => -1,
			'fields'         => 'ids',
			'no_found_rows'  => true,
		) );

		if ( $ids->have_posts() ) {
			foreach ( $ids->posts as $post_id ) {
				$posts[$post_id] = get_post_field( 'post_title', $post_id, 'raw' );
			}
		}

		return $posts;

	}

	/**
	 * Return grid_columns choices for admin form.
	 *
	 * @since 4.8
	 *
	 * @access private
	 * @return string
	 */
	private function choices_grid_columns() {
		return wpex_grid_columns();
	}

	/**
	 * Return grid_gaps choices for admin form.
	 *
	 * @since 4.8
	 *
	 * @access private
	 * @return string
	 */
	private function choices_grid_gaps() {
		return wpex_column_gaps();
	}

	/**
	 * Return link_target choices for admin form.
	 *
	 * @since 4.8
	 *
	 * @access private
	 * @return string
	 */
	private function choices_link_target() {
		return array(
			'_self' => __( 'Current window', 'total' ),
			'_blank' => __( 'New window', 'total' ),
		);
	}

}