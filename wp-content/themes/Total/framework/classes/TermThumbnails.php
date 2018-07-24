<?php
/**
 * Adds thumbnail options to taxonomies
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
class TermThumbnails {

	/**
	 * Main constructor
	 *
	 * @since 2.1.0
	 */
	public function __construct() {

		// Admin functions
		if ( is_admin() ) {
			add_action( 'admin_init', array( $this, 'admin_init' ) );
		}

		// Display term thumbnail on the front-end if enabled
		if ( ! is_admin() && apply_filters( 'wpex_enable_term_page_header_image', true ) ) {
			add_filter( 'wpex_page_header_style', array( $this, 'page_header_style' ) );
			add_filter( 'wpex_page_header_background_image', array( $this, 'page_header_bg' ) );
		}

	}

	/**
	 * Get things started in the backend to add/save the settings
	 *
	 * @since 2.1.0
	 */
	public function admin_init() {

		// Get taxonomies
		$taxonomies = apply_filters( 'wpex_thumbnail_taxonomies', get_taxonomies( array(
			'public' => true,
		) ) );

		// Return if no taxonomies are defined
		if ( ! $taxonomies ) {
			return;
		}

		// Loop through taxonomies
		foreach ( $taxonomies as $taxonomy ) {

			// Add forms
			add_action( $taxonomy . '_add_form_fields', array( $this, 'add_form_fields' ), 10 );
			add_action( $taxonomy . '_edit_form_fields', array( $this, 'edit_form_fields' ), 10, 2 );
			
			// Add columns
			if ( 'product_cat' != $taxonomy ) {
				add_filter( 'manage_edit-' . $taxonomy . '_columns', array( $this, 'admin_columns' ) );
				add_filter( 'manage_' . $taxonomy . '_custom_column', array( $this, 'admin_column' ), 10, 3 );
			}

			// Save forms
			add_action( 'created_' . $taxonomy, array( $this, 'save_forms' ), 10, 3 );
			add_action( 'edit_' . $taxonomy, array( $this, 'save_forms' ), 10, 3 );

		}

	}

	/**
	 * Add Thumbnail field to add form fields
	 *
	 * @since 2.1.0
	 */
	public function add_form_fields( $taxonomy ) {

		// Enqueue media for media selector
		wp_enqueue_media(); ?>

		<div class="form-field">

			<label for="display_type"><?php esc_html_e( 'Page Header Thumbnail', 'total' ); ?></label>

			<select id="wpex_term_page_header_image" name="wpex_term_page_header_image" class="postform">
				<option value=""><?php esc_html_e( 'Default', 'total' ); ?></option>
				<option value="false"><?php esc_html_e( 'No', 'total' ); ?></option>
				<option value="true"><?php esc_html_e( 'Yes', 'total' ); ?></option>
			</select>

		</div>

		<?php
		// Options not needed for Woo
		if ( 'product_cat' != $taxonomy ) : ?>

			<div class="form-field">

				<label for="term-thumbnail"><?php esc_html_e( 'Thumbnail', 'total' ); ?></label>

				<div>

					<div id="wpex-term-thumbnail" style="float:left;margin-right:10px;">
						<img class="wpex-term-thumbnail-img" src="<?php echo wpex_placeholder_img_src(); ?>" width="60px" height="60px" />
					</div>

					<input type="hidden" id="wpex_term_thumbnail" name="wpex_term_thumbnail" />

					<button type="submit" class="wpex-remove-term-thumbnail button"><?php esc_html_e( 'Remove image', 'total' ); ?></button>
					<button type="submit" class="wpex-add-term-thumbnail button"><?php esc_html_e( 'Upload/Add image', 'total' ); ?></button>

					<script>

						// Only show the "remove image" button when needed
						if ( ! jQuery( '#wpex_term_thumbnail' ).val() ) {
							jQuery( '.wpex-remove-term-thumbnail' ).hide();
						}

						// Uploading files
						var file_frame;
						jQuery( document ).on( 'click', '.wpex-add-term-thumbnail', function( event ) {

							event.preventDefault();

							// If the media frame already exists, reopen it.
							if ( file_frame ) {
								file_frame.open();
								return;
							}

							// Create the media frame.
							file_frame = wp.media.frames.downloadable_file = wp.media({
								title    : '<?php esc_html_e( 'Choose an image', 'total' ); ?>',
								button   : {
									text : '<?php esc_html_e( 'Use image', 'total' ); ?>',
								},
								multiple : false
							});

							// When an image is selected, run a callback.
							file_frame.on( 'select', function() {
								attachment = file_frame.state().get( 'selection' ).first().toJSON();
								jQuery( '#wpex_term_thumbnail' ).val( attachment.id );
								jQuery( '.wpex-term-thumbnail-img' ).attr( 'src', attachment.url );
								jQuery( '.wpex-remove-term-thumbnail' ).show();
							});

							// Finally, open the modal.
							file_frame.open();

						});

						jQuery( document ).on( 'click', '.wpex-remove-term-thumbnail', function( event ) {
							jQuery( '.wpex-term-thumbnail' ).attr( 'src', '<?php echo wpex_placeholder_img_src(); ?>' );
							jQuery( '#wpex_term_thumbnail' ).val( '' );
							jQuery( '.wpex-remove-term-thumbnail' ).hide();
							return false;
						});

					</script>

				</div>

				<div class="clear"></div>

			</div>

		<?php endif; ?>

	<?php
	}

	/**
	 * Add Thumbnail field to edit form fields
	 *
	 * @since 2.1.0
	 */
	public function edit_form_fields( $term, $taxonomy ) {

		// Enqueue media for media selector
		wp_enqueue_media();

		// Get current taxonomy
		$term_id  = $term->term_id;

		// Get term data
		$term_data = wpex_get_term_data();

		// Get page header setting
		$page_header_bg = isset( $term_data[$term_id]['page_header_bg'] ) ? $term_data[$term_id]['page_header_bg'] : ''; ?>

		<tr class="form-field">

			<th scope="row" valign="top"><label><?php esc_html_e( 'Page Header Thumbnail', 'total' ); ?></label></th>

			<td>
				<select id="wpex_term_page_header_image" name="wpex_term_page_header_image" class="postform">
					<option value="" <?php selected( $page_header_bg, '' ); ?>><?php esc_html_e( 'Default', 'total' ); ?></option>
					<option value="false" <?php selected( $page_header_bg, 'false' ); ?>><?php esc_html_e( 'No', 'total' ); ?></option>
					<option value="true" <?php selected( $page_header_bg, 'true' ); ?>><?php esc_html_e( 'Yes', 'total' ); ?></option>
				</select>
			</td>

		</tr>

		<?php
		// Options not needed for Woo
		if ( 'product_cat' != $taxonomy ) :

			// Get thumbnail
			$thumbnail_id  = isset( $term_data[$term_id]['thumbnail'] ) ? $term_data[$term_id]['thumbnail'] : '';
			if ( $thumbnail_id ) {
				$thumbnail_src = wp_get_attachment_image_src( $thumbnail_id, 'thumbnail', false );
				$thumbnail_url = ! empty( $thumbnail_src[0] ) ? $thumbnail_src[0] : '';
			} ?>

			<tr class="form-field">

				<th scope="row" valign="top">
					<label for="term-thumbnail"><?php esc_html_e( 'Thumbnail', 'total' ); ?></label>
				</th>

				<td>

					<div id="wpex-term-thumbnail" style="float:left;margin-right:10px;">
						<?php if ( ! empty( $thumbnail_url ) ) { ?>
							<img class="wpex-term-thumbnail-img" src="<?php echo esc_url( $thumbnail_url ); ?>" width="60px" height="60px" />
						<?php } else { ?>
							<img class="wpex-term-thumbnail-img" src="<?php echo esc_url( wpex_placeholder_img_src() ); ?>" width="60px" height="60px" />
						<?php } ?>
					</div>

					<input type="hidden" id="wpex_term_thumbnail" name="wpex_term_thumbnail" value="<?php echo esc_attr( $thumbnail_id ); ?>" />

					<button type="submit" class="wpex-remove-term-thumbnail button"<?php if ( ! $thumbnail_id ) echo 'style="display:none;"'; ?>>
						<?php esc_html_e( 'Remove image', 'total' ); ?>
					</button>

					<button type="submit" class="wpex-add-term-thumbnail button">
						<?php esc_html_e( 'Upload/Add image', 'total' ); ?>
					</button>

					<script type="text/javascript">

						// Uploading files
						var file_frame;

						jQuery( document ).on( 'click', '.wpex-add-term-thumbnail', function( event ) {

							event.preventDefault();

							// If the media frame already exists, reopen it.
							if ( file_frame ) {
								file_frame.open();
								return;
							}

							// Create the media frame.
							file_frame = wp.media.frames.downloadable_file = wp.media({
								title    : '<?php esc_html_e( 'Choose an image', 'total' ); ?>',
								button   : {
									text : '<?php esc_html_e( 'Use image', 'total' ); ?>',
								},
								multiple : false
							} );

							// When an image is selected, run a callback.
							file_frame.on( 'select', function() {
								attachment = file_frame.state().get( 'selection' ).first().toJSON();
								jQuery( '#wpex_term_thumbnail' ).val( attachment.id );
								jQuery( '.wpex-term-thumbnail-img' ).attr( 'src', attachment.url );
								jQuery( '.wpex-remove-term-thumbnail' ).show();
							} );

							// Finally, open the modal.
							file_frame.open();

						} );

						jQuery( document ).on( 'click', '.wpex-remove-term-thumbnail', function( event ) {
							jQuery( '.wpex-term-thumbnail-img' ).attr( 'src', '<?php echo wpex_placeholder_img_src(); ?>' );
							jQuery( '#wpex_term_thumbnail' ).val( '' );
							jQuery( '.wpex-remove-term-thumbnail' ).hide();
							return false;
						} );
					</script>

					<div class="clear"></div>

				</td>

			</tr>

		<?php endif; ?>

		<?php

	}

	/**
	 * Adds the thumbnail to the database
	 *
	 * @since 2.1.0
	 */
	public function add_term_data( $term_id, $key, $data ) {

		// Validate data
		if ( empty( $term_id ) || empty( $data ) || empty( $key ) ) {
			return;
		}

		// Get thumbnails
		$term_data = get_option( 'wpex_term_data' );

		// Add to options
		$term_data[$term_id][$key] = $data;

		// Update option
		update_option( 'wpex_term_data', $term_data );

	}

	/**
	 * Deletes the thumbnail from the database
	 *
	 * @since 2.1.0
	 */
	public function remove_term_data( $term_id, $key ) {

		// Validate data
		if ( empty( $term_id ) || empty( $key ) ) {
			return;
		}

		// Get thumbnails
		$term_data = get_option( 'wpex_term_data' );

		// Add to options
		if ( isset( $term_data[$term_id][$key] ) ) {
			unset( $term_data[$term_id][$key] );
		}

		// Update option
		update_option( 'wpex_term_data', $term_data );
		
	}

	/**
	 * Update thumbnail value
	 *
	 * @since 2.1.0
	 */
	public function update_thumbnail( $term_id, $thumbnail_id ) {

		// Add thumbnail
		if ( ! empty( $thumbnail_id ) ) {
			$this->add_term_data( $term_id, 'thumbnail', $thumbnail_id );
		}

		// Delete thumbnail
		else {
			$this->remove_term_data( $term_id, 'thumbnail' );
		}

	}

	/**
	 * Update page header image option
	 *
	 * @since 2.1.0
	 */
	public function update_page_header_img( $term_id, $display ) {
		
		// Add option
		if ( ! empty( $display ) ) {
			$this->add_term_data( $term_id, 'page_header_bg', $display );
		}

		// Remove option
		else {
			$this->remove_term_data( $term_id, 'page_header_bg' );
		}

	}

	/**
	 * Save Forms
	 *
	 * @since 2.1.0
	 */
	public function save_forms( $term_id, $tt_id = '', $taxonomy = '' ) {
		if ( isset( $_POST['wpex_term_thumbnail'] ) ) {
			$this->update_thumbnail( $term_id, $_POST['wpex_term_thumbnail'] );
		}
		if ( isset( $_POST['wpex_term_page_header_image'] ) ) {
			$this->update_page_header_img( $term_id, $_POST['wpex_term_page_header_image'] );
		}
	}

	/**
	 * Thumbnail column added to category admin.
	 *
	 * @since 2.1.0
	 */
	public function admin_columns( $columns ) {
		$columns['wpex-term-thumbnail-col'] = esc_attr__( 'Thumbnail', 'total' );
		return $columns;
	}

	/**
	 * Thumbnail column value added to category admin.
	 *
	 * @since 2.1.0
	 */
	public function admin_column( $columns, $column, $id ) {

		// Add thumbnail to columns
		if ( 'wpex-term-thumbnail-col' == $column ) {
			if ( $thumbnail_id = $this->get_term_thumbnail_id( $id, 'thumbnail_id', true ) ) {
				$image = wp_get_attachment_image_src( $thumbnail_id, 'thumbnail' );
				$image = $image[0];
			} else {
				$image = wpex_placeholder_img_src();
			}
			$columns .= '<img src="' . esc_url( $image ) . '" alt="' . esc_attr__( 'Thumbnail', 'total' ) . '" class="wp-post-image" height="48" width="48" />';
		}

		// Return columns
		return $columns;

	}

	/**
	 * Retrieve term thumbnail
	 *
	 * @since 2.1.0
	 */
	public function get_term_thumbnail_id( $term_id = null ) {

		// Get term id if not defined and is tax
		$term_id = $term_id ? $term_id : get_queried_object()->term_id;

		// Return if no term id
		if ( ! $term_id ) {
			return;
		}

		// Get data
		$term_data = get_option( 'wpex_term_data' );
		$term_data = ! empty( $term_data[ $term_id ] ) ? $term_data[ $term_id ] : '';

		// Return thumbnail ID
		if ( $term_data && ! empty( $term_data['thumbnail'] ) ) {
			return $term_data['thumbnail'];
		}
		
	}

	/**
	 * Check if the term page header should have a background image
	 *
	 * @since 2.1.0
	 */
	public function term_has_page_header_bg( $term_id = '' ) {

		// Get term id if not defined and is tax
		$term_id = $term_id ? $term_id : get_queried_object()->term_id;

		// True by default
		$bool = true;

		// Woo Cats

		// Get Options and extract thumbnail
		$term_data = get_option( 'wpex_term_data' );

		// Return thumbnail ID
		if ( ! empty( $term_data[$term_id]['page_header_bg'] ) ) {
			$option = $term_data[$term_id]['page_header_bg'];
			$bool   = ( 'true' == $option ) ? true : $bool;
			$bool   = ( 'false' == $option ) ? false : $bool;
		}

		// Apply filters
		$bool = apply_filters( 'wpex_term_has_page_header_bg', $bool );

		// Return bool
		return $bool;
		
	}

	/**
	 * Check if the term page header should have a background image
	 *
	 * @since 2.1.0
	 */
	public function page_header_style( $style ) {
		if ( $this->is_tax_archive()
			&& wpex_term_page_header_image_enabled()
			&& $term_thumbnail = wpex_get_term_thumbnail_id()
		) {
			$style = 'background-image';
		}
		return $style;
	}

	/**
	 * Sets correct page header background
	 *
	 * @since 2.1.0
	 */
	public function page_header_bg( $image ) {
		if ( $this->is_tax_archive()
			&& wpex_term_page_header_image_enabled()
			&& $term_thumbnail = wpex_get_term_thumbnail_id()
		) {
			$image = wp_get_attachment_image_src( $term_thumbnail, 'full' );
			$image = $image[0];
		}
		return $image;
	}

	/**
	 * Check if on a tax archive
	 *
	 * @since 2.1.0
	 */
	public function is_tax_archive() {
		if ( ! is_search() && ( is_tax() || is_category() || is_tag() ) ) {
			return true;
		}
	}

}
new TermThumbnails();