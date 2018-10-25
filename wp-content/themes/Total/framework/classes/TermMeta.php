<?php
/**
 * Class for easily adding term meta settings
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

// Term meta required, introduced in WP 4.4.0
if ( ! function_exists( 'get_term_meta' ) ) {
	return;
}

// Start Class
class TermMeta {

	/**
	 * Main constructor
	 *
	 * @since 3.3.3
	 */
	public function __construct() {

		// Register meta options
		// Not needed since it only is used for sanitization which we do last
		//add_action( 'init', array( $this, 'register_meta' ) );

		// Admin init
		add_action( 'admin_init', array( $this, 'meta_form_fields' ), 40 );
		
	}

	/**
	 * Array of meta options
	 *
	 * @since 3.3.3
	 */
	public function meta_options() {

		// Get array of widget areas
		$widget_areas = array( esc_html__( 'Default', 'total' ) );
		$widget_areas = $widget_areas + wpex_get_widget_areas();

		// Return meta array
		return apply_filters( 'wpex_term_meta_options', array(

			// Redirect
			'wpex_redirect' => array(
				'label'     => esc_html__( 'Redirect', 'total' ),
				'type'      => 'wp_dropdown_pages',
				'args'      => array(
					'sanitize_callback' => 'esc_html',
				),
			),

			// Sidebar select
			'wpex_sidebar' => array(
				'label'    => esc_html__( 'Sidebar', 'total' ),
				'type'     => 'select',
				'choices'  => $widget_areas,
				'args'      => array(
					'sanitize_callback' => 'esc_html',
				),
			),

		) );

	}

	/**
	 * Add meta form fields
	 *
	 * @since 3.3.3
	 */
	public function meta_form_fields() {

		// Get taxonomies
		$taxonomies = get_taxonomies( array(
			'public' => true,
		) );

		// Loop through taxonomies
		foreach ( $taxonomies as $taxonomy ) {

			// Add forms
			add_action( $taxonomy . '_edit_form_fields', array( $this, 'add_form_fields' ) );

			// Save forms
			add_action( 'edited_' . $taxonomy, array( $this, 'save_forms' ), 10, 3 );

		}

	}

	/**
	 * Register meta options
	 *
	 * @since 3.3.3
	 */
	public function register_meta() {

		// Define meta options array on init
		$meta_options = $this->meta_options();

		// Define meta args
		$args = array();

		// Loop through meta options
		foreach( $meta_options as $key => $val ) {
			$args = isset( $val['args'] ) ? $val['args'] : array();
			register_meta( 'term', $key, $args );
		}

	}	

	/**
	 * Adds new category fields
	 *
	 * @since 3.3.3
	 */
	public function add_form_fields( $tag ) {

		// Nonce
		wp_nonce_field( 'wpex_term_meta_nonce', 'wpex_term_meta_nonce' );

		// Get options
		$meta_options = $this->meta_options();

		// Loop through options
		foreach ( $meta_options as $key => $val ) {
			$this->meta_form_field( $key, $val, $tag );
		}

	}

	/**
	 * Saves meta fields
	 *
	 * @since 3.3.3
	 */
	public function save_forms( $term_id ) {

		// Make sure everything is secure
		if ( empty( $_POST['wpex_term_meta_nonce'] )
			|| ! wp_verify_nonce( $_POST['wpex_term_meta_nonce'], 'wpex_term_meta_nonce' )
		) {
			return;
		}

		// Get options
		$meta_options = $this->meta_options();

		// Loop through options
		foreach ( $meta_options as $key => $val ) {

			// Check option value
			$value = isset( $_POST[$key] ) ? $_POST[$key] : '';

			// Save setting
			if ( $value ) {
				update_term_meta( $term_id, $key, $value );
			}

			// Delete setting
			else {
				delete_term_meta( $term_id, $key );
			}

		}
		
	}

	/**
	 * Outputs the form field
	 *
	 * @since 3.3.3
	 */
	public function meta_form_field( $key, $val, $tag ) {

		// Get data
		$label    = isset( $val['label'] ) ? $val['label'] : '';
		$type     = isset( $val['type'] ) ? $val['type'] : 'text';
		$term_id  = $tag->term_id;
		$value    = get_term_meta( $term_id, $key, true );

		// Text
		if ( 'text' == $type ) { ?>

			<tr class="form-field">
				<th scope="row" valign="top"><label for="<?php echo esc_html( $key ); ?>"><?php echo esc_html( $label ); ?></label></th>
				<td><input type="text" name="<?php echo esc_html( $key ); ?>" value="<?php echo esc_html( $value ); ?>" /></td>
			</tr>

		<?php }

		// Select
		if ( 'select' == $type ) {

			$choices = isset( $val['choices'] ) ? $val['choices'] : '';

			if ( $choices ) { ?>

				<tr class="form-field">
					<th scope="row" valign="top"><label for="<?php echo esc_html( $key ); ?>"><?php echo esc_html( $label ); ?></label></th>
					<td>
						<select name="<?php echo esc_html( $key ); ?>">
							<?php foreach ( $choices as $key => $val ) : ?>
								<option value="<?php echo esc_attr( $key ); ?>" <?php selected( $value, $key ) ?>><?php echo esc_html( $val ); ?></option>
							<?php endforeach; ?>
						</select>
					</td>
				</tr>

			<?php }

		}

		// Select
		if ( 'wp_dropdown_pages' == $type ) {

			$args = array(
				'name'             => $key,
				'selected'         => $value,
				'show_option_none' => __( 'None', 'total' )
			); ?>

				<tr class="form-field">
					<th scope="row" valign="top"><label for="<?php echo esc_html( $key ); ?>"><?php echo esc_html( $label ); ?></label></th>
					<td><?php wp_dropdown_pages( $args ); ?></td>
				</tr>

		<?php }

	}

}
new TermMeta();