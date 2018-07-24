<?php
/**
 * Creates a gallery metabox for WordPress
 *
 * Credits:
 * http://wordpress.org/plugins/easy-image-gallery/
 * https://github.com/woothemes/woocommerce
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
class GalleryMetabox {
	private $post_types;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {

		// Post types to add the metabox to
		$this->post_types = apply_filters( 'wpex_gallery_metabox_post_types', array( 'post', 'page' ) );

		// Add metabox to corresponding post types
		foreach( $this->post_types as $key => $val ) {
			add_action( 'add_meta_boxes_' . $val, array( $this, 'add_meta' ), 20 );
		}

		// Save metabox
		add_action( 'save_post', array( $this, 'save_meta' ) );

		// Load needed scripts
		add_action( 'admin_enqueue_scripts', array( $this, 'load_scripts' ) );

	}

	/**
	 * Adds the gallery metabox
	 *
	 * @since 1.0.0
	 */
	public function add_meta( $post ) {
		add_meta_box(
			'wpex-gallery-metabox',
			esc_html__( 'Image Gallery', 'total' ),
			array( $this, 'render' ),
			$post->post_type,
			'normal',
			'high'
		);
	}

	/**
	 * Render the gallery metabox
	 *
	 * @since 1.0.0
	 */
	public function render() {
		global $post; ?>
		<div id="wpex_gallery_images_container">
			<ul class="wpex_gallery_images">
				<?php
				$image_gallery = get_post_meta( $post->ID, '_easy_image_gallery', true );
				$attachments = array_filter( explode( ',', $image_gallery ) );
				if ( $attachments ) {
					foreach ( $attachments as $attachment_id ) {
						if ( wp_attachment_is_image ( $attachment_id  ) ) {
							echo '<li class="image" data-attachment_id="' . $attachment_id . '"><div class="attachment-preview"><div class="thumbnail">
										' . wp_get_attachment_image( $attachment_id, 'thumbnail' ) . '</div>
										<a href="#" class="wpex-gmb-remove" title="' . esc_html__( 'Remove image', 'total' ) . '"><div class="media-modal-icon"></div></a>
									</div></li>';
						}
					}
				} ?>
			</ul>
			<input type="hidden" id="image_gallery" name="image_gallery" value="<?php echo esc_attr( $image_gallery ); ?>" />
			<?php wp_nonce_field( 'easy_image_gallery', 'easy_image_gallery' ); ?>
		</div>
		<p class="add_wpex_gallery_images hide-if-no-js">
			<a href="#" class="button-primary"><?php esc_html_e( 'Add/Edit Images', 'total' ); ?></a>
		</p>
		<p>
			<label for="easy_image_gallery_link_images">
				<input type="checkbox" id="easy_image_gallery_link_images" value="on" name="easy_image_gallery_link_images"<?php echo checked( get_post_meta( get_the_ID(), '_easy_image_gallery_link_images', true ), 'on', false ); ?> /> <?php esc_html_e( 'Enable Lightbox for this gallery?', 'total' )?>
			</label>
		</p>
	<?php
	}

	/**
	 * Render the gallery metabox
	 *
	 * @since 1.0.0
	 */
	public function save_meta( $post_id ) {

		// Check nonce
		if ( ! isset( $_POST[ 'easy_image_gallery' ] )
			|| ! wp_verify_nonce( $_POST[ 'easy_image_gallery' ], 'easy_image_gallery' )
		) {
			return;
		}

		// Check auto save
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}
		
		// Check the user's permissions.
		if ( isset( $_POST['post_type'] ) && 'page' == $_POST['post_type'] ) {

			if ( ! current_user_can( 'edit_page', $post_id ) ) {
				return;
			}

		} else {

			if ( ! current_user_can( 'edit_post', $post_id ) ) {
				return;
			}
			
		}

		if ( isset( $_POST[ 'image_gallery' ] ) && !empty( $_POST[ 'image_gallery' ] ) ) {
			$attachment_ids = sanitize_text_field( $_POST['image_gallery'] );
			// Turn comma separated values into array
			$attachment_ids = explode( ',', $attachment_ids );
			// Clean the array
			$attachment_ids = array_filter( $attachment_ids  );
			// Return back to comma separated list with no trailing comma. This is common when deleting the images
			$attachment_ids =  implode( ',', $attachment_ids );
			update_post_meta( $post_id, '_easy_image_gallery', $attachment_ids );
		} else {
			// Delete gallery
			delete_post_meta( $post_id, '_easy_image_gallery' );
		}

		// link to larger images
		if ( isset( $_POST[ 'easy_image_gallery_link_images' ] ) ) {
			update_post_meta( $post_id, '_easy_image_gallery_link_images', $_POST[ 'easy_image_gallery_link_images' ] );
		} else {
			update_post_meta( $post_id, '_easy_image_gallery_link_images', 'off' );
		}

		// Add action
		do_action( 'wpex_save_gallery_metabox', $post_id );

	}

	/**
	 * Load needed scripts
	 *
	 * @since 3.6.0
	 */
	public function load_scripts( $hook ) {

		// Only needed on these admin screens
		if ( $hook != 'edit.php' && $hook != 'post.php' && $hook != 'post-new.php' ) {
			return;
		}

		// Get global post
		global $post;

		// Return if post is not object
		if ( ! is_object( $post ) ) {
			return;
		}

		// Return if wrong type or is VC live editor
		if ( ! in_array( $post->post_type, $this->post_types ) || wpex_vc_is_inline() ) {
			return;
		}

		// CSS
		wp_enqueue_style(
			'total-gmb-css',
			wpex_asset_url( 'css/wpex-gallery-metabox.css' ),
			false,
			WPEX_THEME_VERSION
		);

		// Load jquery sortable
		wp_enqueue_script( 'jquery-ui-sortable' );

		// Load metabox script
		wp_enqueue_script(
			'wpex-gmb-js',
			wpex_asset_url( 'js/dynamic/wpex-gallery-metabox.js' ),
			array( 'jquery', 'jquery-ui-sortable' ),
			WPEX_THEME_VERSION,
			true
		);

		// Localize metabox script
		wp_localize_script( 'wpex-gmb-js', 'wpexGmb', array(
			'title'  => esc_html__( 'Add Images to Gallery', 'total' ),
			'button' => esc_html__( 'Add to gallery', 'total' ),
			'remove' => esc_html__( 'Remove image', 'total' ),
		) );

	}

}

// Class needed only in the admin
new GalleryMetabox;