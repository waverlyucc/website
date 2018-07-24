<?php
/**
 * Page Settings Metabox
 * Developed & Designed exclusively for the Total WordPress theme
 * Do not copy, re-sell or reproduce!
 *
 * @package Total WordPress Theme
 * @subpackage Framework
 * @version 4.7
 */

namespace TotalTheme;

use WP_Query;

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Start class
class MetaBoxes {
	public $post_types;

	/**
	 * Register this class with the WordPress API
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		add_action( 'admin_init', array( $this, 'admin_init' ) );
	}

	/**
	 * Start things up on init so we can hook into the various filters
	 *
	 * @since 1.0.0
	 */
	public function admin_init() {

		if ( ! apply_filters( 'wpex_metaboxes', true ) ) {
			return;
		}

		// Post types to add the metabox to
		$this->post_types = apply_filters( 'wpex_main_metaboxes_post_types', array(
			'post'         => 'post',
			'page'         => 'page',
			'portfolio'    => 'portfolio',
			'staff'        => 'staff',
			'testimonials' => 'testimonials',
			'page'         => 'page',
			'product'      => 'product',
		) );

		// Loop through post types and add metabox to corresponding post types
		if ( $this->post_types ) {
			foreach( $this->post_types as $key => $val ) {
				add_action( 'add_meta_boxes_'. $val, array( $this, 'post_meta' ), 11 );
			}
		}

		// Save meta
		add_action( 'save_post', array( $this, 'save_meta_data' ) );

		// Load scripts for the metabox
		add_action( 'admin_enqueue_scripts', array( $this, 'load_scripts' ) );

	}

	/**
	 * The function responsible for creating the actual meta box.
	 *
	 * @since 1.0.0
	 */
	public function post_meta( $post ) {

		// Disable on footer builder
		$footer_builder_page = wpex_get_mod( 'footer_builder_page_id' );
		if ( 'page' == get_post_type( $post->ID ) && $footer_builder_page == $post->ID ) {
			return;
		}

		// Add metabox
		add_meta_box(
			'wpex-metabox',
			esc_html__( 'Settings', 'total' ),
			array( $this, 'display_meta_box' ),
			$post->post_type,
			'normal',
			'high'
		);

	}

	/**
	 * Enqueue scripts and styles needed for the metaboxes
	 *
	 * @since 1.0.0
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

		// Enqueue metabox css
		wp_enqueue_style(
			'wpex-post-metabox',
			wpex_asset_url( 'css/wpex-metabox.css' ),
			array(),
			WPEX_THEME_VERSION
		);

		// Enqueue media js
		wp_enqueue_media();

		// Enqueue color picker
		wp_enqueue_style( 'wp-color-picker' );
		wp_enqueue_script( 'wp-color-picker' );

		// Load alpa color picker if Nextgen is not active because it breaks things
		$has_alpha_color_picker = ! class_exists( 'C_NextGEN_Bootstrap' );
		if ( apply_filters( 'wpex_alpha_color_picker', $has_alpha_color_picker ) ) {

			wp_enqueue_script( 'wp-color-picker-alpha',
				wpex_asset_url( 'js/dynamic/wp-color-picker-alpha.min.js' ),
				array( 'wp-color-picker' ),
				'1.0.0',
				true
			);

		}

		// Enqueue metabox js
		wp_enqueue_script(
			'wpex-post-metabox',
			wpex_asset_url( 'js/dynamic/wpex-metabox.js' ),
			array( 'jquery', 'wp-color-picker' ),
			WPEX_THEME_VERSION,
			true
		);

		wp_localize_script( 'wpex-post-metabox', 'wpexMB', array(
			'reset'  => esc_html__(  'Reset Settings', 'total' ),
			'cancel' => esc_html__(  'Cancel Reset', 'total' ),
		) );

	}

	/**
	 * Renders the content of the meta box.
	 *
	 * @since 1.0.0
	 */
	public function display_meta_box( $post ) {

		// Add an nonce field so we can check for it later.
		wp_nonce_field( 'wpex_metabox', 'wpex_metabox_nonce' );

		// Get current post data
		$post_id   = $post->ID;
		$post_type = get_post_type();

		// Get tabs
		$tabs = $this->meta_array( $post );

		// Empty notice
		$empty_notice = '<p>'. esc_html__( 'No meta settings available for this post type or user.', 'total' ) .'</p>';

		// Make sure tabs aren't empty
		if ( empty( $tabs ) ) {
			echo $empty_notice; return;
		}

		// Store tabs that should display on this specific page in an array for use later
		$active_tabs = array();
		foreach ( $tabs as $tab ) {
			$tab_post_type = isset( $tab['post_type'] ) ? $tab['post_type'] : '';
			if ( ! $tab_post_type ) {
				$display_tab = true;
			} elseif ( in_array( $post_type, $tab_post_type ) ) {
				$display_tab = true;
			} else {
				$display_tab = false;
			}
			if ( $display_tab ) {
				$active_tabs[] = $tab;
			}
		}

		// No active tabs
		if ( empty( $active_tabs ) ) {
			echo $empty_notice;
			return;
		}

		$tabs_output = '';

		$tabs_output .= '<ul class="wp-tab-bar">';

			$count=0;

			foreach ( $active_tabs as $tab ) {

				$count++;

				$active_class = ( '1' == $count ) ? ' class="wp-tab-active"' : '';
				$tab_title = $tab['title'] ? $tab['title'] : esc_html__( 'Other', 'total' );

				$tabs_output .= '<li' . $active_class . '>';
					
					$tabs_output .= '<a href="javascript:;" data-tab="#wpex-mb-tab-' . $count . '">';

						if ( isset( $tab['icon'] ) ) {

							$tabs_output .= '<span class="' . esc_attr( $tab['icon'] ) .'"></span>';

						}

						$tabs_output .= esc_html( $tab_title );

					$tabs_output .= '</a>';

				$tabs_output .= '</li>';

			}

		$tabs_output .= '</ul>';

		echo $tabs_output;

		// Output tab sections
		$count=0;
		foreach ( $active_tabs as $tab ) {
			$count++; ?>
			<div id="wpex-mb-tab-<?php echo $count; ?>" class="wp-tab-panel clr">
				<table class="form-table">
					<?php
					foreach ( $tab['settings'] as $setting ) {

						$meta_id     = $setting['id'];
						$title       = $setting['title'];
						$hidden      = isset( $setting['hidden'] ) ? $setting['hidden'] : false;
						$type        = isset( $setting['type'] ) ? $setting['type'] : 'text';
						$default     = isset( $setting['default'] ) ? $setting['default'] : '';
						$description = isset( $setting['description'] ) ? $setting['description'] : '';
						$meta_value  = get_post_meta( $post_id, $meta_id, true );
						$meta_value  = $meta_value ? $meta_value : $default; ?>

						<tr<?php if ( $hidden ) echo ' style="display:none;"'; ?> id="<?php echo esc_attr( $meta_id ); ?>_tr">

							<th>
								<label for="wpex_main_layout"><strong><?php echo $title; ?></strong></label>
								<?php
								// Display field description
								if ( $description ) { ?>
									<p class="wpex-mb-description"><?php echo $description; ?></p>
								<?php } ?>
							</th>

							<?php
							// Text Field
							if ( 'text' == $type ) { ?>

								<td><input class="wpex-input" name="<?php echo esc_attr( $meta_id ); ?>" type="text" value="<?php echo esc_attr( $meta_value ); ?>"></td>

							<?php
							}

							// Button Group
							if ( 'button_group' == $type ) {

								$options = isset ( $setting['options'] ) ? $setting['options'] : '';

								if ( is_array( $options ) ) { ?>

									<td>

										<div class="wpex-mb-btn-group">

											<?php foreach ( $options as $option_value => $option_name ) {

												$class = 'wpex-mb-btn wpex-mb-' . esc_attr( $option_value );

												if ( $option_value == $meta_value ) {
													$class .= ' active';
												}  ?>

												<button type="button" class="<?php echo esc_attr( $class ); ?>" data-value="<?php echo esc_attr( $option_value ); ?>"><?php echo esc_html( $option_name ); ?></button>

											<?php } ?>

											<input name="<?php echo esc_attr( $meta_id ); ?>" type="hidden" value="<?php echo esc_attr( $meta_value ); ?>" class="wpex-mb-hidden">

										</div>

									</td>

								<?php }

							}

							// Enable Disable button group
							if ( 'button_group_ed' == $type ) {

								$options = isset ( $setting['options'] ) ? $setting['options'] : '';

								if ( is_array( $options ) ) { ?>

									<td>

										<div class="wpex-mb-btn-group">
											
											<?php
											// Default
											$active = ! $meta_value ? 'wpex-mb-btn wpex-default active' : 'wpex-mb-btn wpex-default'; ?>

											<button type="button" class="<?php echo esc_attr( $active ); ?>" data-value=""><?php echo esc_html_e( 'Default', 'total' ); ?></button>

											<?php
											// Enable
											$active = ( $options['enable'] == $meta_value ) ? 'wpex-mb-btn wpex-on active' : 'wpex-mb-btn wpex-on'; ?>

											<button type="button" class="<?php echo esc_attr( $active ); ?>" data-value="<?php echo esc_attr( $options['enable'] ); ?>"><?php echo esc_html_e( 'Enable', 'total' ); ?></button>

											<?php
											// Disable
											$active = ( $options['disable'] == $meta_value ) ? 'wpex-mb-btn wpex-off active' : 'wpex-mb-btn wpex-off'; ?>

											<button type="button" class="<?php echo esc_attr( $active ); ?>" data-value="<?php echo esc_attr( $options['disable'] ); ?>"><?php echo esc_html_e( 'Disable', 'total' ); ?></button>

											<input name="<?php echo esc_attr( $meta_id ); ?>" type="hidden" value="<?php echo esc_attr( $meta_value ); ?>" class="wpex-mb-hidden">

										</div>

									</td>

								<?php }

							}

							// Date Field
							elseif ( 'date' == $type ) {

								wp_enqueue_script( 'jquery-ui' );

								wp_enqueue_script( 'jquery-ui-datepicker', array( 'jquery-ui' ) );

								wp_enqueue_style(
									'jquery-ui-datepicker-style',
									'//ajax.googleapis.com/ajax/libs/jqueryui/1.10.4/themes/smoothness/jquery-ui.css'
								); ?>

								<td><input class="wpex-input wpex-date-meta" name="<?php echo esc_attr( $meta_id ); ?>" type="text" value="<?php echo esc_attr( $meta_value ); ?>"></td>

							<?php }

							// Number Field
							elseif ( 'number' == $type ) {

								$step = isset( $setting['step'] ) ? $setting['step'] : '1';
								$min  = isset( $setting['min'] ) ? $setting['min'] : '1';
								$max  = isset( $setting['max'] ) ? $setting['max'] : '10'; ?>

								<td>
									<input class="wpex-input" name="<?php echo esc_attr( $meta_id ); ?>" type="number" value="<?php echo esc_attr( $meta_value ); ?>" step="<?php echo floatval( $step ); ?>" min="<?php echo floatval( $min ); ?>" max="<?php echo floatval( $max ); ?>">
								</td>

							<?php }

							// HTML Text
							elseif ( 'text_html' == $type ) { ?>

								<td><input class="wpex-input" name="<?php echo esc_attr( $meta_id ); ?>" type="text" value="<?php echo esc_html( $meta_value ); ?>"></td>

							<?php }

							// Link field
							elseif ( 'link' == $type ) {

								// Sanitize
								$meta_value = ( 'home_url' == $meta_value ) ? esc_html( $meta_value ) : esc_url( $meta_value ); ?>

								<td><input class="wpex-input" name="<?php echo esc_attr( $meta_id ); ?>" type="text" value="<?php echo $meta_value; ?>"></td>

							<?php }

							// Textarea Field
							elseif ( 'textarea' == $type ) {
								$rows = isset ( $setting['rows'] ) ? absint( $setting['rows'] ) : 4; ?>

								<td>
									<textarea rows="<?php echo esc_attr( $rows ); ?>" cols="1" name="<?php echo esc_attr( $meta_id ); ?>" type="text" class="wpex-mb-textarea"><?php echo esc_textarea( $meta_value ); ?></textarea>
								</td>

							<?php }

							// Code Field
							elseif ( 'code' == $type ) {
								$rows = isset ( $setting['rows'] ) ? absint( $setting['rows'] ) : 1; ?>

								<td>
									<pre><textarea rows="<?php echo esc_attr( $rows ); ?>" cols="1" name="<?php echo esc_attr( $meta_id ); ?>" type="text" class="wpex-mb-textarea-code"><?php echo $meta_value; ?></textarea></pre>
								</td>

							<?php }

							// Checkbox
							elseif ( 'checkbox' == $type ) {

								$meta_value = ( 'on' != $meta_value ) ? false : true; ?>
								<td><input name="<?php echo esc_attr( $meta_id ); ?>" type="checkbox" <?php checked( $meta_value, true, true ); ?>></td>

							<?php }

							// Select
							elseif ( 'select' == $type ) {

								$options = isset ( $setting['options'] ) ? $setting['options'] : '';

								if ( ! empty( $options ) ) { ?>

									<td><select id="<?php echo esc_attr( $meta_id ); ?>" name="<?php echo esc_attr( $meta_id ); ?>">
									
									<?php foreach ( $options as $option_value => $option_name ) { ?>
										
										<option value="<?php echo esc_attr( $option_value ); ?>" <?php selected( $meta_value, $option_value, true ); ?>><?php echo esc_attr( $option_name ); ?></option>

									<?php } ?>

									</select></td>

								<?php }

							}

							// Color
							elseif ( 'color' == $type ) { ?>

								<td><input name="<?php echo esc_attr( $meta_id ); ?>" type="text" value="<?php echo esc_attr( $meta_value ); ?>" class="wpex-mb-color-field" data-alpha="true"></td>

							<?php }

							// Image
							elseif ( 'image' == $type ) {

								// Validate data if array - old Redux cleanup
								if ( is_array( $meta_value ) ) {
									if ( ! empty( $meta_value['url'] ) ) {
										$meta_value = $meta_value['url'];
									} else {
										$meta_value = '';
									}
								} ?>
								
								<td>
									<div class="wpex-image-select">
										<input class="wpex-input" type="text" name="<?php echo esc_attr( $meta_id ); ?>" value="<?php echo esc_attr( $meta_value ); ?>">
										<input class="wpex-mb-uploader button-secondary" name="<?php echo esc_attr( $meta_id ); ?>" type="button" value="<?php esc_html_e( 'Upload', 'total' ); ?>" />
									</div>
									<div class="wpex-img-holder">
										<?php if ( $meta_value ) {
											if ( is_numeric( $meta_value ) && wp_attachment_is_image( $meta_value ) ) {
												echo wp_get_attachment_image( $meta_value, 'thumbnail' );
											} else {
												echo '<img src="'. $meta_value . '" />';
											}
										} ?>
									</div>
								</td>

							<?php }

							// Media
							elseif ( 'media' == $type ) {

								// Validate data if array - old Redux cleanup
								if ( is_array( $meta_value ) ) {
									if ( ! empty( $meta_value['url'] ) ) {
										$meta_value = $meta_value['url'];
									} else {
										$meta_value = '';
									}
								} ?>

								<td>
									<div class="uploader">
									<input class="wpex-input" type="text" name="<?php echo esc_attr( $meta_id ); ?>" value="<?php echo esc_attr( $meta_value ); ?>">
									<input class="wpex-mb-uploader button-secondary" name="<?php echo esc_attr( $meta_id ); ?>" type="button" value="<?php esc_html_e( 'Upload', 'total' ); ?>" />
									</div>
								</td>

							<?php }

							// Editor
							elseif ( 'editor' == $type ) {
								$teeny= isset( $setting['teeny'] ) ? $setting['teeny'] : false;
								$rows = isset( $setting['rows'] ) ? $setting['rows'] : '10';
								$media_buttons= isset( $setting['media_buttons'] ) ? $setting['media_buttons'] : true; ?>
								<td><?php wp_editor( $meta_value, $meta_id, array(
									'textarea_name' => $meta_id,
									'teeny'         => $teeny,
									'textarea_rows' => $rows,
									'media_buttons' => $media_buttons,
								) ); ?></td>
							<?php } ?>
						</tr>

					<?php } ?>
				</table>
			</div>
		<?php } ?>

		<div class="wpex-mb-reset">
			<a class="button button-secondary wpex-reset-btn"><?php esc_html_e( 'Reset Settings', 'total' ); ?></a>
			<div class="wpex-reset-checkbox"><input type="checkbox" name="wpex_metabox_reset"> <?php esc_html_e( 'Are you sure? Check this box, then update your post to reset all settings.', 'total' ); ?></div>
		</div>

		<div class="clear"></div>

	<?php }

	/**
	 * Save metabox data
	 *
	 * @since 1.0.0
	 */
	public function save_meta_data( $post_id ) {

		// Get array of settings to save
		$tabs = $this->meta_array( get_post( $post_id ) );

		// No tabs so lets bail
		if ( ! $tabs ) {
			return;
		}

		/*
		 * We need to verify this came from our screen and with proper authorization,
		 * because the save_post action can be triggered at other times.
		 */

		// Check if our nonce is set.
		if ( ! isset( $_POST['wpex_metabox_nonce'] ) ) {
			return;
		}

		// Verify that the nonce is valid.
		if ( ! wp_verify_nonce( $_POST['wpex_metabox_nonce'], 'wpex_metabox' ) ) {
			return;
		}

		// If this is an autosave, our form has not been submitted, so we don't want to do anything.
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

		/* OK, it's safe for us to save the data now. Now we can loop through fields */

		// Check reset field
		$reset = isset( $_POST['wpex_metabox_reset'] ) ? $_POST['wpex_metabox_reset'] : '';

		// Loop through tabs
		$settings = array();
		foreach( $tabs as $tab ) {
			foreach ( $tab['settings'] as $setting ) {
				$settings[] = $setting;
			}
		}

		// Loop through settings and validate
		foreach ( $settings as $setting ) {

			$id = $setting['id'];

			if ( 'on' == $reset ) {
				delete_post_meta( $post_id, $id );
				continue;
			}

			// Vars
			$value = isset( $_POST[ $id ] ) ? $_POST[ $id ] : '';
			$type  = isset ( $setting['type'] ) ? $setting['type'] : 'text';

			if ( 'checkbox' == $type ) {

				$value = $value ? 'on' : null;

			} elseif ( $value ) {

				// Validate text
				if ( 'text' == $type || 'text_html' == $type ) {
					$value = wp_kses_post( $value ); // @todo change this?
				}

				// Validate date
				elseif ( 'date' == $type ) {
					$value = strtotime( wp_strip_all_tags( $value ) );
				}

				// Validate textarea
				elseif ( 'textarea' == $type ) {
					$value = esc_html( $value );
				}

				// Links
				elseif ( 'link' == $type ) {
					$value = esc_url( $value );
				}

				// Validate select
				elseif ( 'select' == $type ) {
					if ( 'default' == $value ) {
						$value = '';
					} else {
						$value = wp_strip_all_tags( $value );
					}
				}

				// Validate media
				elseif ( 'media' == $type || 'image' == $type ) {

					// Move old wpex_post_self_hosted_shortcode_redux to wpex_post_self_hosted_media
					if ( 'wpex_post_self_hosted_media' == $id && empty( $value )
						&& $old = get_post_meta( $post_id, 'wpex_post_self_hosted_shortcode_redux', true )
					) {
						$value = $old;
						delete_post_meta( $post_id, 'wpex_post_self_hosted_shortcode_redux' );
					}

				}

				// Validate editor
				elseif ( 'editor' == $type ) {

					$value = ( '<p><br data-mce-bogus="1"></p>' == $value ) ? '' : $value;

				}

			}

			// Update meta value
			if ( $value ) {
				update_post_meta( $post_id, $id, $value );
			}

			// Delete meta
			else {
				delete_post_meta( $post_id, $id );
			}

		}

	}

	/**
	 * Get menus
	 *
	 * @since 4.3
	 */
	public function get_menus() {
		$menus = array( esc_html__( 'Default', 'total' ) );
		$get_menus = get_terms( 'nav_menu', array( 'hide_empty' => true ) );
		foreach ( $get_menus as $menu) {
			$menus[$menu->term_id] = $menu->name;
		}
		return $menus;
	}

	/**
	 * Get title styles
	 *
	 * @since 4.3
	 */
	public function get_title_styles() {
		return apply_filters( 'wpex_title_styles', array(
			''                 => esc_html__( 'Default', 'total' ),
			'centered'         => esc_html__( 'Centered', 'total' ),
			'centered-minimal' => esc_html__( 'Centered Minimal', 'total' ),
			'background-image' => esc_html__( 'Background Image', 'total' ),
			'solid-color'      => esc_html__( 'Solid Color & White Text', 'total' ),
		) );
	}

	/**
	 * Get widget areas
	 *
	 * @since 4.3
	 */
	public function get_widget_areas() {
		$widget_areas = array( esc_html__( 'Default', 'total' ) );
		$widget_areas = $widget_areas + wpex_get_widget_areas();
		return $widget_areas;
	}

	/**
	 * Get templatera templates
	 *
	 * @since 4.3
	 */
	public function get_templatera_templates() {
		$templates = array( esc_html__( 'Default', 'total' ) );
		if ( ! post_type_exists( 'templatera' ) ) {
			return $templates;
		}
		$get_templates = new WP_Query( array(
			'posts_per_page' => -1,
			'post_type'      => 'templatera',
			'fields'         => 'ids',
		) );
		if ( $get_templates = $get_templates->posts ) {
			foreach ( $get_templates as $template ) {
				$templates[$template] = wp_strip_all_tags( get_the_title( $template ) );
			}
		}
		return $templates;
	}

	/**
	 * Settings Array
	 *
	 * @since 1.0.0
	 */
	private function meta_array( $post = null ) {

		// Prefix
		$prefix = 'wpex_';

		// Define array
		$array = array();

		// Header styles
		$header_styles = array(
			'' => esc_html__( 'Default', 'total' ),
		);
		$header_styles = $header_styles + wpex_get_header_styles();

		// BG Styles
		$bg_img_styles = wpex_get_bg_img_styles();

		// Main Tab
		$array['main'] = array(
			'title' => esc_html__( 'Main', 'total' ),
			'settings' => array(
				'post_link' => array(
					'title' => esc_html__( 'Redirect', 'total' ),
					'id' => $prefix . 'post_link',
					'type' => 'link',
					'description' => esc_html__( 'Enter a URL to redirect this post or page.', 'total' ),
				),
				'main_layout' =>array(
					'title' => esc_html__( 'Site Layout', 'total' ),
					'type' => 'select',
					'id' => $prefix . 'main_layout',
					'description' => esc_html__( 'This option should only be used in very specific cases since there is a global setting available in the Customizer.', 'total' ),
					'options' => array(
						'' => esc_html__( 'Default', 'total' ),
						'full-width' => esc_html__( 'Full-Width', 'total' ),
						'boxed' => esc_html__( 'Boxed', 'total' ),
					),
				),
				'post_layout' => array(
					'title' => esc_html__( 'Content Layout', 'total' ),
					'type' => 'select',
					'id' => $prefix . 'post_layout',
					'description' => esc_html__( 'Select your custom layout for this page or post content.', 'total' ),
					'options' => wpex_get_post_layouts(),
				),
				'singular_template' => array(
					'title' => esc_html__( 'Dynamic Template', 'total' ),
					'type' => 'select',
					'id' => $prefix . 'singular_template',
					'description' => esc_html__( 'Select a dynamic templatera template to override this page. If selected it will disable the front-end editor.', 'total' ),
					'options' => $this->get_templatera_templates(),
				),
				'sidebar' => array(
					'title' => esc_html__( 'Sidebar', 'total' ),
					'type' => 'select',
					'id' => 'sidebar',
					'description' => esc_html__( 'Select your a custom sidebar for this page or post.', 'total' ),
					'options' => $this->get_widget_areas(),
				),
				'disable_toggle_bar' => array(
					'title' => esc_html__( 'Toggle Bar', 'total' ),
					'id' => $prefix . 'disable_toggle_bar',
					'type' => 'select',
					'description' => esc_html__( 'Enable or disable this element on this page or post.', 'total' ),
					'options' => array(
						'' => esc_html__( 'Default', 'total' ),
						'enable' => esc_html__( 'Enable', 'total' ),
						'on' => esc_html__( 'Disable', 'total' ),
					),
				),
				'disable_top_bar' => array(
					'title' => esc_html__( 'Top Bar', 'total' ),
					'id' => $prefix . 'disable_top_bar',
					'type' => 'select',
					'description' => esc_html__( 'Enable or disable this element on this page or post.', 'total' ),
					'options' => array(
						'' => esc_html__( 'Default', 'total' ),
						'enable' => esc_html__( 'Enable', 'total' ),
						'on' => esc_html__( 'Disable', 'total' ),
					),
				),
				'disable_breadcrumbs' => array(
					'title' => esc_html__( 'Breadcrumbs', 'total' ),
					'id' => $prefix . 'disable_breadcrumbs',
					'type' => 'select',
					'description' => esc_html__( 'Enable or disable this element on this page or post.', 'total' ),
					'options' => array(
						'' => esc_html__( 'Default', 'total' ),
						'enable' => esc_html__( 'Enable', 'total' ),
						'on' => esc_html__( 'Disable', 'total' ),
					),
				),
				'disable_social' => array(
					'title' => esc_html__( 'Social Share', 'total' ),
					'id' => $prefix . 'disable_social',
					'type' => 'select',
					'description' => esc_html__( 'Enable or disable this element on this page or post.', 'total' ),
					'options' => array(
						'' => esc_html__( 'Default', 'total' ),
						'enable' => esc_html__( 'Enable', 'total' ),
						'on' => esc_html__( 'Disable', 'total' ),
					),
				),
				'secondary_thumbnail' => array(
					'title' => esc_html__( 'Secondary Image', 'total' ),
					'id' => $prefix . 'secondary_thumbnail',
					'type' => 'image',
					'description' => esc_html__( 'Used for the secondary Image Swap overlay style.', 'total' ),
				),
			),
		);

		// Header Tab
		$array['header'] = array(
			'title' => esc_html__( 'Header', 'total' ),
			'settings' => array(
				'disable_header' => array(
					'title' => esc_html__( 'Header', 'total' ),
					'id' => $prefix . 'disable_header',
					'type' => 'select',
					'description' => esc_html__( 'Enable or disable this element on this page or post.', 'total' ),
					'options' => array(
						'' => esc_html__( 'Default', 'total' ),
						'enable' => esc_html__( 'Enable', 'total' ),
						'on' => esc_html__( 'Disable', 'total' ),
					),
				),
				'header_style' => array(
					'title' => esc_html__( 'Header Style', 'total' ),
					'id' => $prefix . 'header_style',
					'type' => 'select',
					'description' => esc_html__( 'Override default header style.', 'total' ),
					'options' => $header_styles,
				),
				'sticky_header' => array(
					'title' => esc_html__( 'Sticky Header', 'total' ),
					'id' => $prefix . 'sticky_header',
					'type' => 'select',
					'description' => esc_html__( 'Enable or disable this element on this page or post.', 'total' ),
					'options' => array(
						'' => esc_html__( 'Default', 'total' ),
						'enable' => esc_html__( 'Enable', 'total' ),
						'disable' => esc_html__( 'Disable', 'total' ),
					),
				),
				'logo_scroll_top' => array(
					'title' => esc_html__( 'Scroll Up When Clicking Logo', 'total' ),
					'id' => $prefix . 'logo_scroll_top',
					'type' => 'select',
					'description' => esc_html__( 'Enable or disable this element on this page or post.', 'total' ),
					'options' => array(
						'' => esc_html__( 'Default', 'total' ),
						'enable' => esc_html__( 'Enable', 'total' ),
						'disable' => esc_html__( 'Disable', 'total' ),
					),
				),
				'custom_menu' => array(
					'title' => esc_html__( 'Custom Menu', 'total' ),
					'type' => 'select',
					'id' => $prefix . 'custom_menu',
					'description' => esc_html__( 'Select a custom menu for this page or post.', 'total' ),
					'options' => $this->get_menus(),
				),
				'overlay_header' => array(
					'title' => esc_html__( 'Overlay Header', 'total' ),
					'description' => esc_html__( 'Enable or disable this element on this page or post.', 'total' ),
					'id' => $prefix . 'overlay_header',
					'type' => 'select',
					'options' => array(
						'' => esc_html__( 'Disable', 'total' ),
						'on' => esc_html__( 'Enable', 'total' ),
					),
				),
				'overlay_header_style' => array(
					'title' => esc_html__( 'Overlay Header Style', 'total' ),
					'type' => 'select',
					'id' => $prefix . 'overlay_header_style',
					'description' => esc_html__( 'Select your overlay header style', 'total' ),
					'options' => wpex_header_overlay_styles(),
					'default' => '',
				),
				'overlay_header_background' => array(
					'title' => esc_html__( 'Overlay Header Background', 'total' ),
					'id' => $prefix . 'overlay_header_background',
					'description' => esc_html__( 'Select a color to enable a background for your header (optional)', 'total' ),
					'type' => 'color',
				),
				'overlay_header_dropdown_style' => array(
					'title' => esc_html__( 'Overlay Header Dropdown Style', 'total' ),
					'type' => 'select',
					'id' => $prefix . 'overlay_header_dropdown_style',
					'description' => esc_html__( 'Select your overlay header style', 'total' ),
					'options' => wpex_get_menu_dropdown_styles(),
					'default' => 'black',
				),
				'overlay_header_font_size' => array(
					'title' => esc_html__( 'Overlay Header Menu Font Size', 'total'),
					'id' => $prefix . 'overlay_header_font_size',
					'description' => esc_html__( 'Enter a size in px.', 'total' ),
					'type' => 'number',
					'max' => '99',
					'min' => '8',
				),
				'overlay_header_logo' => array(
					'title' => esc_html__( 'Overlay Header Logo', 'total'),
					'id' => $prefix . 'overlay_header_logo',
					'type' => 'image',
					'description' => esc_html__( 'Select a custom logo (optional) for the overlay header.', 'total' ),
				),
				'overlay_header_logo_retina' => array(
					'title' => esc_html__( 'Overlay Header Logo: Retina', 'total'),
					'id' => $prefix . 'overlay_header_logo_retina',
					'type' => 'image',
					'description' => esc_html__( 'Retina version for the overlay header custom logo.', 'total' ),
				),
				'overlay_header_retina_logo_height' => array(
					'title' => esc_html__( 'Overlay Header Retina Logo Height', 'total'),
					'id' => $prefix . 'overlay_header_logo_retina_height',
					'description' => esc_html__( 'Enter a size in px.', 'total' ),
					'type' => 'number',
					'max' => '999',
					'min' => '1',
				),
			),
		);

		// Title Tab
		$array['title'] = array(
			'title' => esc_html__( 'Title', 'total' ),
			'settings' => array(
				'disable_title' => array(
					'title' => esc_html__( 'Title', 'total' ),
					'id' => $prefix . 'disable_title',
					'type' => 'select',
					'description' => esc_html__( 'Enable or disable this element on this page or post.', 'total' ),
					'options' => array(
						'' => esc_html__( 'Default', 'total' ),
						'enable' => esc_html__( 'Enable', 'total' ),
						'on' => esc_html__( 'Disable', 'total' ),
					),
				),
				'post_title' => array(
					'title' => esc_html__( 'Custom Title', 'total' ),
					'id' => $prefix . 'post_title',
					'type' => 'text',
					'description' => esc_html__( 'Alter the main title display.', 'total' ),
				),
				'disable_header_margin' => array(
					'title' => esc_html__( 'Title Margin', 'total' ),
					'id' => $prefix . 'disable_header_margin',
					'type' => 'select',
					'description' => esc_html__( 'Enable or disable this element on this page or post.', 'total' ),
					'options' => array(
						'' => esc_html__( 'Enable', 'total' ),
						'on' => esc_html__( 'Disable', 'total' ),
					),
				),
				'post_subheading' => array(
					'title' => esc_html__( 'Subheading', 'total' ),
					'type' => 'text_html',
					'id' => $prefix . 'post_subheading',
					'description' => esc_html__( 'Enter your page subheading. Shortcodes & HTML is allowed.', 'total' ),
				),
				'post_title_style' => array(
					'title' => esc_html__( 'Title Style', 'total' ),
					'type' => 'select',
					'id' => $prefix . 'post_title_style',
					'description' => esc_html__( 'Select a custom title style for this page or post.', 'total' ),
					'options' => $this->get_title_styles(),
				),
				'post_title_background_color' => array(
					'title' => esc_html__( 'Background Color', 'total' ),
					'description' => esc_html__( 'Select a color.', 'total' ),
					'id' => $prefix .'post_title_background_color',
					'type' => 'color',
					'hidden' => true,
				),
				'post_title_background_redux' => array(
					'title' => esc_html__( 'Background Image', 'total'),
					'id' => $prefix . 'post_title_background_redux',
					'type' => 'image',
					'description' => esc_html__( 'Select a custom header image for your main title.', 'total' ),
					'hidden' => true,
				),
				'post_title_height' => array(
					'title' => esc_html__( 'Background Height', 'total' ),
					'type' => 'text',
					'id' => $prefix . 'post_title_height',
					'description' => esc_html__( 'Select your custom height for your title background. Default is 400px.', 'total' ),
					'hidden' => true,
				),
				'post_title_background_style' => array(
					'title' => esc_html__( 'Background Style', 'total' ),
					'type' => 'select',
					'id' => $prefix . 'post_title_background_image_style',
					'description' => esc_html__( 'Select the style.', 'total' ),
					'options' => $bg_img_styles,
				),
				'post_title_background_position' => array(
					'title' => esc_html__( 'Background Position', 'total' ),
					'type' => 'text',
					'id' => $prefix . 'post_title_background_position',
					'description' => esc_html__( 'Enter a custom position for your background image.', 'total' ),
				),
				'post_title_background_overlay' => array(
					'title' => esc_html__( 'Background Overlay', 'total' ),
					'type' => 'select',
					'id' => $prefix . 'post_title_background_overlay',
					'description' => esc_html__( 'Select an overlay for the title background.', 'total' ),
					'options' => array(
						'' => esc_html__( 'None', 'total' ),
						'dark' => esc_html__( 'Dark', 'total' ),
						'dotted' => esc_html__( 'Dotted', 'total' ),
						'dashed' => esc_html__( 'Diagonal Lines', 'total' ),
						'bg_color' => esc_html__( 'Background Color', 'total' ),
					),
					'hidden' => true,
				),
				'post_title_background_overlay_opacity' => array(
					'id' => $prefix . 'post_title_background_overlay_opacity',
					'type' => 'number',
					'title' => esc_html__( 'Background Overlay Opacity', 'total' ),
					'description' => esc_html__( 'Enter a custom opacity for your title background overlay.', 'total' ),
					'default' => '',
					'hidden' => true,
					'step' => 0.01,
					'min' => 0,
					'max' => 1,
				),
			),
		);

		// Slider tab
		$array['slider'] = array(
			'title' => esc_html__( 'Slider', 'total' ),
			'settings' => array(
				'post_slider_shortcode' => array(
					'title' => esc_html__( 'Slider Shortcode', 'total' ),
					'type' => 'code',
					'id' => $prefix . 'post_slider_shortcode',
					'description' => esc_html__( 'Enter a slider shortcode here to display a slider at the top of the page.', 'total' ),
				),
				'post_slider_shortcode_position' => array(
					'title' => esc_html__( 'Slider Position', 'total' ),
					'type' => 'select',
					'id' => $prefix . 'post_slider_shortcode_position',
					'description' => esc_html__( 'Select the position for the slider shortcode.', 'total' ),
					'options' => array(
						'' => esc_html__( 'Default', 'total' ),
						'below_title' => esc_html__( 'Below Title', 'total' ),
						'above_title' => esc_html__( 'Above Title', 'total' ),
						'above_menu' => esc_html__( 'Above Menu (Header 2 or 3)', 'total' ),
						'above_header' => esc_html__( 'Above Header', 'total' ),
						'above_topbar' => esc_html__( 'Above Top Bar', 'total' ),
					),
				),
				'post_slider_bottom_margin' => array(
					'title' => esc_html__( 'Slider Bottom Margin', 'total' ),
					'description' => esc_html__( 'Enter a bottom margin for your slider in pixels', 'total' ),
					'id' => $prefix . 'post_slider_bottom_margin',
					'type' => 'text',
				),
				'contain_post_slider' => array(
					'title' => esc_html__( 'Contain Slider?', 'total' ),
					'id' => $prefix . 'contain_post_slider',
					'type' => 'select',
					'description' => esc_html__( 'Adds the container wrapper around the slider to center it with the rest of the content.', 'total' ),
					'options' => array(
						'' => esc_html__( 'Disable', 'total' ),
						'on' => esc_html__( 'Enable', 'total' ),
					),
				),
				'disable_post_slider_mobile' => array(
					'title' => esc_html__( 'Slider On Mobile', 'total' ),
					'id' => $prefix . 'disable_post_slider_mobile',
					'type' => 'select',
					'description' => esc_html__( 'Enable or disable slider display for mobile devices.', 'total' ),
					'options' => array(
						'' => esc_html__( 'Enable', 'total' ),
						'on' => esc_html__( 'Disable', 'total' ),
					),
				),
				'post_slider_mobile_alt' => array(
					'title' => esc_html__( 'Slider Mobile Alternative', 'total' ),
					'type' => 'media',
					'id' => $prefix . 'post_slider_mobile_alt',
					'description' => esc_html__( 'Select an image.', 'total' ),
					'type' => 'image',
				),
				'post_slider_mobile_alt_url' => array(
					'title' => esc_html__( 'Slider Mobile Alternative URL', 'total' ),
					'id' => $prefix . 'post_slider_mobile_alt_url',
					'description' => esc_html__( 'URL for the mobile slider alternative.', 'total' ),
					'type' => 'text',
				),
				'post_slider_mobile_alt_url_target' => array(
					'title' => esc_html__( 'Slider Mobile Alternative URL Target', 'total' ),
					'id' => $prefix . 'post_slider_mobile_alt_url_target',
					'description' => esc_html__( 'Select your link target window.', 'total' ),
					'type' => 'select',
					'options' => array(
						'' => esc_html__( 'Same Window', 'total' ),
						'blank' => esc_html__( 'New Window', 'total' ),
					),
				),
			),
		);

		// Background tab
		$array['background'] = array(
			'title' => esc_html__( 'Background', 'total' ),
			'settings' => array(
				'page_background_color' => array(
					'title' => esc_html__( 'Background Color', 'total' ),
					'description' => esc_html__( 'Select a color.', 'total' ),
					'id' => $prefix . 'page_background_color',
					'type' => 'color',
				),
				'page_background_image_redux' => array(
					'title' => esc_html__( 'Background Image', 'total' ),
					'id' => $prefix . 'page_background_image_redux',
					'description' => esc_html__( 'Select an image.', 'total' ),
					'type' => 'image',
				),
				'page_background_image_style' => array(
					'title' => esc_html__( 'Background Style', 'total' ),
					'type' => 'select',
					'id' => $prefix . 'page_background_image_style',
					'description' => esc_html__( 'Select the style.', 'total' ),
					'options' => $bg_img_styles,
				),
			),
		);

		// Footer tab
		$array['footer'] = array(
			'title' => esc_html__( 'Footer', 'total' ),
			'settings' => array(
				'disable_footer' => array(
					'title' => esc_html__( 'Footer', 'total' ),
					'id' => $prefix . 'disable_footer',
					'type' => 'select',
					'description' => esc_html__( 'Enable or disable this element on this page or post.', 'total' ),
					'options' => array(
						'' => esc_html__( 'Default', 'total' ),
						'enable' => esc_html__( 'Enable', 'total' ),
						'on' => esc_html__( 'Disable', 'total' ),
					),
				),
				'disable_footer_widgets' => array(
					'title' => esc_html__( 'Footer Widgets', 'total' ),
					'id' => $prefix . 'disable_footer_widgets',
					'type' => 'select',
					'description' => esc_html__( 'Enable or disable this element on this page or post.', 'total' ),
					'options' => array(
						'' => esc_html__( 'Enable', 'total' ),
						'on' => esc_html__( 'Disable', 'total' ),
					),
				),
				'footer_reveal' => array(
					'title' => esc_html__( 'Footer Reveal', 'total' ),
					'description' => esc_html__( 'Enable the footer reveal style. The footer will be placed in a fixed postion and display on scroll. This setting is for the "Full-Width" layout only and desktops only.', 'total' ),
					'id' => $prefix . 'footer_reveal',
					'type' => 'select',
					'options' => array(
						'' => esc_html__( 'Default', 'total' ),
						'on' => esc_html__( 'Enable', 'total' ),
						'off' => esc_html__( 'Disable', 'total' ),
					),
				),
				'footer_bottom' => array(
					'title' => esc_html__( 'Footer Bottom', 'total' ),
					'description' => esc_html__( 'Enable the footer bottom area (copyright section).', 'total' ),
					'id' => $prefix . 'footer_bottom',
					'type' => 'select',
					'options' => array(
						'' => esc_html__( 'Default', 'total' ),
						'on' => esc_html__( 'Enable', 'total' ),
						'off' => esc_html__( 'Disable', 'total' ),
					),
				),
			),
		);

		// Callout Tab
		$array['callout'] = array(
			'title' => esc_html__( 'Callout', 'total' ),
			//'icon' => 'dashicons dashicons-megaphone',
			'settings' => array(
				'disable_footer_callout' => array(
					'title' => esc_html__( 'Callout', 'total' ),
					'id' => $prefix . 'disable_footer_callout',
					'type' => 'select',
					'description' => esc_html__( 'Enable or disable this element on this page or post.', 'total' ),
					'options' => array(
						'' => esc_html__( 'Default', 'total' ),
						'enable' => esc_html__( 'Enable', 'total' ),
						'on' => esc_html__( 'Disable', 'total' ),
					),
				),
				'callout_link' => array(
					'title' => esc_html__( 'Callout Link', 'total' ),
					'id' => $prefix . 'callout_link',
					'type' => 'link',
					'description' => esc_html__( 'Enter a valid link.', 'total' ),
				),
				'callout_link_txt' => array(
					'title' => esc_html__( 'Callout Link Text', 'total' ),
					'id' => $prefix . 'callout_link_txt',
					'type' => 'text',
					'description' => esc_html__( 'Enter your text.', 'total' ),
				),
				'callout_text' => array(
					'title' => esc_html__( 'Callout Text', 'total' ),
					'id' => $prefix . 'callout_text',
					'type' => 'editor',
					'rows' => '5',
					'teeny' => true,
					'media_buttons' => false,
					'description' => esc_html__( 'Override the default callout text and if your callout box is disabled globally but you have content here it will still display for this page or post.', 'total' ),
				),
			),
		);

		// Media tab
		$array['media'] = array(
			'title' => esc_html__( 'Media', 'total' ),
			'post_type' => array( 'post' ),
			'settings' => array(
				'post_media_position' => array(
					'title' => esc_html__( 'Media Display/Position', 'total' ),
					'id' => $prefix . 'post_media_position',
					'type' => 'select',
					'description' => esc_html__( 'Select your preferred position for your post\'s media (featured image or video).', 'total' ),
					'options' => array(
						'' => esc_html__( 'Default', 'total' ),
						'above' => esc_html__( 'Full-Width Above Content', 'total' ),
						'hidden' => esc_html__( 'None (Do Not Display Featured Image/Video)', 'total' ),
					),
				),
				'post_oembed' => array(
					'title' => esc_html__( 'oEmbed URL', 'total' ),
					'description' => esc_html__( 'Enter a URL that is compatible with WP\'s built-in oEmbed feature. This setting is used for your video and audio post formats.', 'total' ) .'<br /><a href="http://codex.wordpress.org/Embeds" target="_blank">'. esc_html__( 'Learn More', 'total' ) .' &rarr;</a>',
					'id' => $prefix . 'post_oembed',
					'type' => 'text',
				),
				'post_self_hosted_shortcode_redux' => array(
					'title' => esc_html__( 'Self Hosted', 'total' ),
					'description' => esc_html__( 'Insert your self hosted video or audio URL here.', 'total' ) .'<br /><a href="http://make.wordpress.org/core/2013/04/08/audio-video-support-in-core/" target="_blank">'. esc_html__( 'Learn More', 'total' ) .' &rarr;</a>',
					'id' => $prefix . 'post_self_hosted_media',
					'type' => 'media',
				),
				'post_video_embed' => array(
					'title' => esc_html__( 'Embed Code', 'total' ),
					'description' => esc_html__( 'Insert your embed/iframe code.', 'total' ),
					'id' => $prefix . 'post_video_embed',
					'type' => 'code',
					'rows' => 4,
				),
			),
		);

		// Staff Tab
		if ( WPEX_STAFF_IS_ACTIVE ) {

			$staff_meta_array = wpex_staff_social_meta_array();
			$staff_meta_array['position'] = array(
				'title' => esc_html__( 'Position', 'total' ),
				'id'    => $prefix . 'staff_position',
				'type'  => 'text',
			);
			$obj = get_post_type_object( 'staff' );
			$tab_title= $obj->labels->singular_name;
			$array['staff'] = array(
				'title'     => $tab_title,
				'post_type' => array( 'staff' ),
				'settings'  => $staff_meta_array,
			);

		}

		// Portfolio Tab
		if ( WPEX_PORTFOLIO_IS_ACTIVE ) {

			$obj= get_post_type_object( 'portfolio' );
			$tab_title = $obj->labels->singular_name;
			$array['portfolio'] = array(
				'title' => $tab_title,
				'post_type' => array( 'portfolio' ),
				'settings' => array(
					'featured_video' => array(
						'title' => esc_html__( 'oEmbed URL', 'total' ),
						'description' => esc_html__( 'Enter a URL that is compatible with WP\'s built-in oEmbed feature. This setting is used for your video and audio post formats.', 'total' ) .'<br /><a href="http://codex.wordpress.org/Embeds" target="_blank">'. esc_html__( 'Learn More', 'total' ) .' &rarr;</a>',
						'id' => $prefix .'post_video',
						'type' => 'text',
					),
					'post_video_embed' => array(
						'title' => esc_html__( 'Embed Code', 'total' ),
						'description' => esc_html__( 'Insert your embed/iframe code.', 'total' ),
						'id' => $prefix . 'post_video_embed',
						'type' => 'code',
						'rows' => 4,
					),
				),
			);

		}

		// Apply filter & return settings array
		return apply_filters( 'wpex_metabox_array', $array, $post );

	}

}
new MetaBoxes();