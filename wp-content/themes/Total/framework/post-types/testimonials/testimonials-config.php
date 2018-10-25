<?php
/**
 * Testimonials Post Type Configuration file
 *
 * @package Total WordPress Theme
 * @subpackage Testimonials
 * @version 4.5.4.2
 */

// The class
class WPEX_Testimonials_Config {

	/**
	 * Get things started
	 *
	 * @since 2.0.0
	 */
	public function __construct() {

		// Helper functions
		require_once WPEX_FRAMEWORK_DIR .'post-types/testimonials/testimonials-helpers.php';

		// Adds the testimonials post type
		add_action( 'init', array( 'WPEX_Testimonials_Config', 'register_post_type' ), 0 );

		// Adds the testimonials taxonomies
		if ( wpex_is_mod_enabled( wpex_get_mod( 'testimonials_categories', true ) ) ) {
			add_action( 'init', array( 'WPEX_Testimonials_Config', 'register_categories' ), 0 );
		}

		// Register testimonials sidebar
		if ( wpex_get_mod( 'testimonials_custom_sidebar', true ) ) {
			add_filter( 'wpex_register_sidebars_array', array( 'WPEX_Testimonials_Config', 'register_sidebar' ), 10 );
		}

		// Add image sizes
		add_filter( 'wpex_image_sizes', array( 'WPEX_Testimonials_Config', 'add_image_sizes' ) );

		// Add testimonial VC modules
		add_filter( 'vcex_builder_modules', array( 'WPEX_Testimonials_Config', 'vc_modules' ) );

		/*-------------------------------------------------------------------------------*/
		/* -  Admin only actions/filters
		/*-------------------------------------------------------------------------------*/
		if ( is_admin() ) {

			// Adds columns in the admin view for taxonomies
			add_filter( 'manage_edit-testimonials_columns', array( 'WPEX_Testimonials_Config', 'edit_columns' ) );
			add_action( 'manage_testimonials_posts_custom_column', array( 'WPEX_Testimonials_Config', 'column_display' ), 10, 2 );

			// Allows filtering of posts by taxonomy in the admin view
			add_action( 'restrict_manage_posts', array( 'WPEX_Testimonials_Config', 'tax_filters' ) );

			// Create Editor for altering the post type arguments
			add_action( 'admin_menu', array( 'WPEX_Testimonials_Config', 'add_page' ) );
			add_action( 'admin_init', array( 'WPEX_Testimonials_Config', 'register_page_options' ) );
			add_action( 'admin_notices', array( 'WPEX_Testimonials_Config', 'setting_notice' ) );
			add_action( 'admin_print_styles-testimonials_page_wpex-testimonials-editor', array( 'WPEX_Testimonials_Config','css' ) );

			// Add new image sizes tab
			add_filter( 'wpex_image_sizes_tabs', array( 'WPEX_Testimonials_Config', 'image_sizes_tabs' ), 10 );

			// Add meta settings
			add_filter( 'wpex_metabox_array', array( 'WPEX_Testimonials_Config', 'add_meta' ), 5, 2 );
		
		}

		/*-------------------------------------------------------------------------------*/
		/* -  Front-End only actions/filters
		/*-------------------------------------------------------------------------------*/
		else {

			// Display testimonials sidebar for testimonials
			if ( wpex_get_mod( 'testimonials_custom_sidebar', true ) ) {
				add_filter( 'wpex_get_sidebar', array( 'WPEX_Testimonials_Config', 'display_sidebar' ), 10 );
			}

			// Alter the default page title
			add_action( 'wpex_page_header_title_args', array( 'WPEX_Testimonials_Config', 'alter_title' ), 10 );

			// Alter the post layouts for testimonials posts and archives
			add_filter( 'wpex_post_layout_class', array( 'WPEX_Testimonials_Config', 'layouts' ), 10 );

			// Posts per page
			add_action( 'pre_get_posts', array( 'WPEX_Testimonials_Config', 'posts_per_page' ) );

			// Single next/prev visibility
			add_filter( 'wpex_has_next_prev', array( 'WPEX_Testimonials_Config', 'next_prev' ), 10, 2 );

			// Alter previous post link title
			add_filter( 'wpex_prev_post_link_title', array( 'WPEX_Testimonials_Config', 'prev_post_link_title' ), 10, 2 );

			// Alter next post link title
			add_filter( 'wpex_next_post_link_title', array( 'WPEX_Testimonials_Config', 'next_post_link_title' ), 10, 2 );

		}
		
	} // End construct

	/*-------------------------------------------------------------------------------*/
	/* -  Start Class Functions
	/*-------------------------------------------------------------------------------*/
	
	/**
	 * Register post type
	 *
	 * @since 2.0.0
	 */
	public static function register_post_type() {

		// Get values and sanitize
		$name          = wpex_get_testimonials_name();
		$singular_name = wpex_get_testimonials_singular_name();
		$has_archive   = wpex_get_mod( 'testimonials_has_archive', false );

		$default_slug  = $has_archive ? 'testimonials' : 'testimonial';
		$slug          = wpex_get_mod( 'testimonials_slug' );
		$slug          = $slug ? esc_html( $slug ) : $default_slug;

		$menu_icon     = wpex_get_testimonials_menu_icon();

		// Register the post type
		register_post_type( 'testimonials', apply_filters( 'wpex_testimonials_args', array(
			'labels' => array(
				'name' => $name,
				'singular_name' => $singular_name,
				'add_new' => __( 'Add New', 'total' ),
				'add_new_item' => __( 'Add New Item', 'total' ),
				'edit_item' => __( 'Edit Item', 'total' ),
				'new_item' => __( 'Add New Testimonials Item', 'total' ),
				'view_item' => __( 'View Item', 'total' ),
				'search_items' => __( 'Search Items', 'total' ),
				'not_found' => __( 'No Items Found', 'total' ),
				'not_found_in_trash' => __( 'No Items Found In Trash', 'total' )
			),
			'public' => true,
			'capability_type' => 'post',
			'has_archive' => $has_archive ? true : false,
			'menu_icon' => 'dashicons-'. $menu_icon,
			'menu_position' => 20,
			'rewrite' => array(
				'slug' => $slug,
				'with_front' => false
			),
			'supports' => array(
				'title',
				'editor',
				'excerpt',
				'thumbnail',
				'comments',
				'custom-fields',
				'revisions',
				'author',
				'page-attributes',
			),
		) ) );

	}

	/**
	 * Register Testimonials category
	 *
	 * @since 2.0.0
	 */
	public static function register_categories() {

		// Define and sanitize options
		$name = wpex_get_mod( 'testimonials_cat_labels');
		$name = $name ? esc_html( $name ) : __( 'Testimonials Categories', 'total' );
		$slug = wpex_get_mod( 'testimonials_cat_slug' );
		$slug = $slug ? esc_html( $slug ) : 'testimonials-category';

		// Define args and apply filters
		$args = apply_filters( 'wpex_taxonomy_testimonials_category_args', array(
			'labels' => array(
				'name' => $name,
				'singular_name' => $name,
				'menu_name' => $name,
				'search_items' => __( 'Search', 'total' ),
				'popular_items' => __( 'Popular', 'total' ),
				'all_items' => __( 'All', 'total' ),
				'parent_item' => __( 'Parent', 'total' ),
				'parent_item_colon' => __( 'Parent', 'total' ),
				'edit_item' => __( 'Edit', 'total' ),
				'update_item' => __( 'Update', 'total' ),
				'add_new_item' => __( 'Add New', 'total' ),
				'new_item_name' => __( 'New', 'total' ),
				'separate_items_with_commas' => __( 'Separate with commas', 'total' ),
				'add_or_remove_items' => __( 'Add or remove', 'total' ),
				'choose_from_most_used' => __( 'Choose from the most used', 'total' ),
			),
			'public' => true,
			'show_in_nav_menus' => true,
			'show_ui' => true,
			'show_tagcloud' => true,
			'hierarchical' => true,
			'query_var' => true,
			'rewrite' => array( 'slug' => $slug, 'with_front' => false ),
		) );

		// Register the testimonials category taxonomy
		register_taxonomy( 'testimonials_category', array( 'testimonials' ), $args );

	}

	/**
	 * Adds columns to the WP dashboard edit screen
	 *
	 * @since 2.0.0
	 */
	public static function edit_columns( $columns ) {
		if ( taxonomy_exists( 'testimonials_category' ) ) {
			$columns['testimonials_category'] = esc_html__( 'Category', 'total' );
		}
		return $columns;
	}
	
	/**
	 * Adds columns to the WP dashboard edit screen
	 *
	 * @since 2.0.0
	 */
	public static function column_display( $column, $post_id ) {
		switch ( $column ) :
			case 'testimonials_category':
				if ( $category_list = get_the_term_list( $post_id, 'testimonials_category', '', ', ', '' ) ) {
					echo $category_list;
				} else {
					echo '&mdash;';
				}
			break;
		endswitch;
	}

	/**
	 * Adds taxonomy filters to the testimonials admin page
	 *
	 * @since 2.0.0
	 */
	public static function tax_filters() {
		global $typenow;
		if ( 'testimonials' == $typenow && taxonomy_exists( 'testimonials_category' ) ) {
			$current_tax_slug   = isset( $_GET['testimonials_category'] ) ? esc_html( $_GET['testimonials_category'] ) : false;
			$tax_obj            = get_taxonomy( 'testimonials_category' );
			$tax_name           = $tax_obj->labels->name;
			$terms              = get_terms( 'testimonials_category' );
			if ( count( $terms ) > 0 ) {
				echo '<select name="testimonials_category" id="testimonials_category" class="postform">';
				echo '<option value="">'. $tax_name . '</option>';
				foreach ( $terms as $term ) {
					echo '<option value=' . $term->slug, $current_tax_slug == $term->slug ? ' selected="selected"' : '', '>' . $term->name .' (' . $term->count .')</option>';
				}
				echo "</select>";
			}
		}
	}

	/**
	 * Add sub menu page for the Testimonials Editor
	 *
	 * @since 2.0.0
	 */
	public static function add_page() {
		$wpex_testimonials_editor = add_submenu_page(
			'edit.php?post_type=testimonials',
			__( 'Post Type Editor', 'total' ),
			__( 'Post Type Editor', 'total' ),
			'administrator',
			'wpex-testimonials-editor',
			array( 'WPEX_Testimonials_Config', 'create_admin_page' )
		);
		add_action( 'load-'. $wpex_testimonials_editor, array( 'WPEX_Testimonials_Config', 'flush_rewrite_rules' ) );
	}

	/**
	 * Flush re-write rules
	 *
	 * @since 3.3.0
	 */
	public static function flush_rewrite_rules() {
		$screen = get_current_screen();
		if ( $screen->id == 'testimonials_page_wpex-testimonials-editor' ) {
			flush_rewrite_rules();
		}
	}

	/**
	 * Function that will register the testimonials editor admin page
	 *
	 * @since 2.0.0
	 */
	public static function register_page_options() {
		register_setting( 'wpex_testimonials_options', 'wpex_testimonials_editor', array( 'WPEX_Testimonials_Config', 'sanitize' ) );
	}

	/**
	 * Displays saved message after settings are successfully saved
	 *
	 * @since 2.0.0
	 */
	public static function setting_notice() {
		settings_errors( 'wpex_testimonials_editor_page_notices' );
	}

	/**
	 * Sanitizes input and saves theme_mods
	 *
	 * @since 2.0.0
	 */
	public static function sanitize( $options ) {

		// Save values to theme mod
		if ( ! empty ( $options ) ) {

			// Checkboxes
			$checkboxes = array(
				'testimonials_categories',
				'testimonials_custom_sidebar',
				'testimonials_search',
			);
			foreach ( $checkboxes as $checkbox ) {
				if ( ! empty( $options[$checkbox] ) ) {
					remove_theme_mod( $checkbox );  // All are enabled by default
				} else {
					set_theme_mod( $checkbox, false );
				}
				unset( $options[$checkbox] );
			}

			// Not checkboxes
			foreach( $options as $key => $value ) {
				if ( $value ) {
					set_theme_mod( $key, $value );
				} else {
					remove_theme_mod( $key );
				}
			}

			if ( ! empty( $options['testimonials_has_archive'] ) ) {
				set_theme_mod( 'testimonials_has_archive', true );
			} else {
				remove_theme_mod( 'testimonials_has_archive' );
			}

			// Add notice
			add_settings_error(
				'wpex_testimonials_editor_page_notices',
				esc_attr( 'settings_updated' ),
				__( 'Settings saved and rewrite rules flushed.', 'total' ),
				'updated'
			);

		}

		// Lets delete the options as we are saving them into theme mods
		$options = '';
		return $options;

	}

	/**
	 * Output for the actual Testimonials Editor admin page
	 *
	 * @since 2.0.0
	 */
	public static function create_admin_page() {

		// Delete option as we are using theme_mods instead
		delete_option( 'wpex_testimonials_editor' ); ?>

		<div class="wrap">
			
			<h2><?php esc_html_e( 'Post Type Editor', 'total' ); ?></h2>
			
			<form method="post" action="options.php">
				
				<?php settings_fields( 'wpex_testimonials_options' ); ?>
				
				<table class="form-table">
					
					<tr valign="top" id="wpex-main-page-select">
						<th scope="row"><?php esc_html_e( 'Main Page', 'total' ); ?></th>
						<td><?php
						// Display dropdown of pages to select from
						wp_dropdown_pages( array(
							'echo'             => true,
							'selected'         => wpex_get_mod( 'testimonials_page' ),
							'name'             => 'wpex_testimonials_editor[testimonials_page]',
							'show_option_none' => esc_html__( 'None', 'total' ),
							'exclude'          => get_option( 'page_for_posts' ),
						) ); ?><p class="description"><?php esc_html_e( 'Used for breadcrumbs.', 'total' ); ?></p></td>
					</tr>
					
					<tr valign="top">
						<th scope="row"><?php esc_html_e( 'Admin Icon', 'total' ); ?></th>
						<td>
							<?php
							// Mod
							$mod = wpex_get_mod( 'testimonials_admin_icon', null );
							$mod = 'format-status' == $mod ? '' : $mod;
							// Dashicons list
							$dashicons = wpex_get_dashicons_array(); ?>
							<div id="wpex-dashicon-select" class="wpex-clr">
								<?php foreach ( $dashicons as $key => $val ) :
									$value = 'format-status' == $key ? '' : $key;
									$class = $mod == $value ? 'button-primary' : 'button-secondary'; ?>
									<a href="#" data-value="<?php echo esc_attr( $value ); ?>" class="<?php echo esc_attr( $class ); ?>"><span class="dashicons dashicons-<?php echo $key; ?>"></span></a>
								<?php endforeach; ?>
							</div>
							<input type="hidden" name="wpex_testimonials_editor[testimonials_admin_icon]" id="wpex-dashicon-select-input" value="<?php echo esc_attr( $mod ); ?>"></td>
						</td>
					</tr>

					<tr valign="top" id="wpex-auto-archive-enable">
						<th scope="row"><?php esc_html_e( 'Enable Auto Archive', 'total' ); ?></th>
						<?php
						$mod = wpex_get_mod( 'testimonials_has_archive', false );
						$mod = $mod ? 'on' : false; ?>
						<td><input type="checkbox" name="wpex_testimonials_editor[testimonials_has_archive]" <?php checked( $mod, 'on' ); ?> /> <span class="description"><?php esc_html_e( 'Disabled by default so you can create your archive page using a page builder.', 'total' ); ?></span></td>
					</tr>
					
					<tr valign="top">
						<th scope="row"><?php esc_html_e( 'Enable Custom Sidebar', 'total' ); ?></th>
						<?php
						// Get checkbox value
						$mod = wpex_get_mod( 'testimonials_custom_sidebar', 'on' );
						$mod = ( $mod && 'off' != $mod ) ? 'on' : 'off'; // sanitize ?>
						<td><input type="checkbox" name="wpex_testimonials_editor[testimonials_custom_sidebar]" value="<?php echo esc_attr( $mod ); ?>" <?php checked( $mod, 'on' ); ?> /></td>
					</tr>
					
					<tr valign="top">
						<th scope="row"><?php esc_html_e( 'Include In Search', 'total' ); ?></th>
						<?php
						// Get checkbox value
						$mod = wpex_get_mod( 'testimonials_search', 'on' );
						$mod = ( $mod && 'off' != $mod ) ? 'on' : 'off'; // sanitize ?>
						<td><input type="checkbox" name="wpex_testimonials_editor[testimonials_search]" value="<?php echo esc_attr( $mod ); ?>" <?php checked( $mod, 'on' ); ?> /></td>
					</tr>
					
					<tr valign="top">
						<th scope="row"><?php esc_html_e( 'Post Type: Name', 'total' ); ?></th>
						<td><input type="text" name="wpex_testimonials_editor[testimonials_labels]" value="<?php echo wpex_get_mod( 'testimonials_labels' ); ?>" /></td>
					</tr>
					
					<tr valign="top">
						<th scope="row"><?php esc_html_e( 'Post Type: Singular Name', 'total' ); ?></th>
						<td><input type="text" name="wpex_testimonials_editor[testimonials_singular_name]" value="<?php echo wpex_get_mod( 'testimonials_singular_name' ); ?>" /></td>
					</tr>
					
					<tr valign="top">
						<th scope="row"><?php esc_html_e( 'Post Type: Slug', 'total' ); ?></th>
						<td><input type="text" name="wpex_testimonials_editor[testimonials_slug]" value="<?php echo wpex_get_mod( 'testimonials_slug' ); ?>" /></td>
					</tr>
					
					<tr valign="top" id="wpex-categories-enable">
						<th scope="row"><?php esc_html_e( 'Enable Categories', 'total' ); ?></th>
						<?php
						// Get checkbox value
						$mod = wpex_get_mod( 'testimonials_categories', 'on' );
						$mod = wpex_is_mod_enabled( $mod ) ? 'on' : 'off'; // sanitize ?>
						<td><input type="checkbox" name="wpex_testimonials_editor[testimonials_categories]" value="<?php echo esc_attr( $mod ); ?>" <?php checked( $mod, 'on' ); ?> /></td>
					</tr>
					
					<tr valign="top" id="wpex-categories-label">
						<th scope="row"><?php esc_html_e( 'Categories: Label', 'total' ); ?></th>
						<td><input type="text" name="wpex_testimonials_editor[testimonials_cat_labels]" value="<?php echo wpex_get_mod( 'testimonials_cat_labels' ); ?>" /></td>
					</tr>
					
					<tr valign="top" id="wpex-categories-slug">
						<th scope="row"><?php esc_html_e( 'Categories: Slug', 'total' ); ?></th>
						<td><input type="text" name="wpex_testimonials_editor[testimonials_cat_slug]" value="<?php echo wpex_get_mod( 'testimonials_cat_slug' ); ?>" /></td>
					</tr>

				</table>

				<?php submit_button(); ?>

			</form>

			<script>

				( function( $ ) {

					"use strict";

					$( document ).ready( function() {
						
						// Dashicons
						var $buttons = $( '#wpex-dashicon-select a' ),
							$input   = $( '#wpex-dashicon-select-input' );
						$buttons.click( function() {
							var $activeButton = $( '#wpex-dashicon-select a.button-primary' );
							$activeButton.removeClass( 'button-primary' ).addClass( 'button-secondary' );
							$( this ).addClass( 'button-primary' );
							$input.val( $( this ).data( 'value' ) );
							return false;
						} );

						// Show/hide main page select if auto archive is enabled
						var $mainPage = $( '#wpex-main-page-select' ),
							$autoArchive = $( '#wpex-auto-archive-enable input' );

						if ( $autoArchive.is( ":checked" ) ) {
							$mainPage.hide();
						}
						$( $autoArchive ).change(function () {
							if ( $( this ).is( ":checked" ) ) {
								$mainPage.hide();
							} else {
								$mainPage.show();
							}
						} );
						
						// Categories enable/disable
						var $catsEnable   = $( '#wpex-categories-enable input' ),
							$CatsTrToHide = $( '#wpex-categories-label, #wpex-categories-slug' );
						
						if ( ! $catsEnable.is( ":checked" ) ) {
							$CatsTrToHide.hide();
						}
						
						$( $catsEnable ).change(function () {
							if ( $( this ).is( ":checked" ) ) {
								$CatsTrToHide.show();
							} else {
								$CatsTrToHide.hide();
							}
						} );

					} );

				} ) ( jQuery );

			</script>

		</div>

	<?php }

	/**
	 * Post Type Editor CSS
	 *
	 * @since 3.3.0
	 */
	public static function css() { ?>
	
		<style type="text/css">
			#wpex-dashicon-select { max-width: 800px; }
			#wpex-dashicon-select a { display: inline-block; margin: 2px; padding: 0; width: 32px; height: 32px; line-height: 32px; text-align: center; }
			#wpex-dashicon-select a .dashicons,
			#wpex-dashicon-select a .dashicons-before:before { line-height: inherit; }
		</style>

	<?php }

	/**
	 * Registers a new custom testimonials sidebar
	 *
	 * @since 4.2.2
	 */
	public static function register_sidebar( $sidebars ) {
		$obj            = get_post_type_object( 'testimonials' );
		$post_type_name = $obj->labels->name;
		$sidebars['testimonials_sidebar'] = $post_type_name . ' ' . esc_html__( 'Sidebar', 'total' );
		return $sidebars;
	}

	/**
	 * Alter main sidebar to display testimonials sidebar
	 *
	 * @since 2.0.0
	 */
	public static function display_sidebar( $sidebar ) {
		if ( is_singular( 'testimonials') || wpex_is_testimonials_tax() || is_post_type_archive( 'testimonials' ) ) {
			$sidebar = 'testimonials_sidebar';
		}
		return $sidebar;
	}

	/**
	 * Alters the default page title
	 *
	 * @since 2.0.0
	 */
	public static function alter_title( $args ) {
		if ( is_singular( 'testimonials' ) ) {
			if ( ! wpex_get_mod( 'testimonials_labels' )
				&& $author = get_post_meta( get_the_ID(), 'wpex_testimonial_author', true )
			) {
				$title = sprintf( esc_html__( 'Testimonial by: %s', 'total' ), $author );
			} else {
				$title = single_post_title( '', false );
			}
			$args['string']   = $title;
			$args['html_tag'] = 'h1';
		}
		return $args;
	}

	/**
	 * Alter the post layouts for testimonials posts and archives
	 *
	 * @since 2.0.0
	 */
	public static function layouts( $class ) {
		if ( is_singular( 'testimonials' ) ) {
			$class = wpex_get_mod( 'testimonials_single_layout' );
		} elseif ( wpex_is_testimonials_tax() || is_post_type_archive( 'testimonials' ) ) {
			$class = wpex_get_mod( 'testimonials_archive_layout', 'full-width' );
		}
		return $class;
	}

	/**
	 * Archive posts per page
	 *
	 * @since 4.4
	 */
	public static function posts_per_page( $query ) {
		if ( is_admin() || ! $query->is_main_query() ) {
			return;
		}
		if ( wpex_is_testimonials_tax() || is_post_type_archive( 'testimonials' ) ) {
			$query->set( 'posts_per_page', wpex_get_mod( 'testimonials_archive_posts_per_page', '12' ) );
		}
	}

	/**
	 * Adds a "testimonials" tab to the image sizes admin panel
	 *
	 * @since 3.3.2
	 */
	public static function image_sizes_tabs( $array ) {
		$array['testimonials'] = wpex_get_testimonials_name();
		return $array;
	}

	/**
	 * Adds image sizes for the testimonials to the image sizes panel
	 *
	 * @since 2.0.0
	 */
	public static function add_image_sizes( $sizes ) {
		$obj            = get_post_type_object( 'testimonials' );
		$post_type_name = $obj->labels->singular_name;
		$sizes['testimonials_entry'] = array(
			'label'   => sprintf( esc_html__( '%s Entry', 'total' ), $post_type_name ),
			'width'   => 'testimonials_entry_image_width',
			'height'  => 'testimonials_entry_image_height',
			'crop'    => 'testimonials_entry_image_crop',
			'section' => 'testimonials',
		);
		return $sizes;
	}

	/**
	 * Disables the next/previous links if disabled via the customizer.
	 *
	 * @since 2.0.0
	 */
	public static function next_prev( $display, $post_type ) {
		if ( 'testimonials' == $post_type ) {
			$display = wpex_get_mod( 'testimonials_next_prev', true ) ? true : false;
		}
		return $display;
	}

	/**
	 * Alter previous post link title
	 *
	 * @since 2.0.0
	 */
	public static function prev_post_link_title( $title, $post_type ) {
		if ( 'testimonials' == $post_type ) {
			$title = '<span class="fa fa-angle-double-left"></span>' . esc_html__( 'Previous', 'total' );
		}
		return $title;
	}
	
	/**
	 * Alter next post link title
	 *
	 * @since 2.0.0
	 */
	public static function next_post_link_title( $title, $post_type ) {
		if ( 'testimonials' == $post_type ) {
			$title = esc_html__( 'Next', 'total' ) . '<span class="fa fa-angle-double-right"></span>';
		}
		return $title;
	}

	/**
	 * Adds testimonials meta options
	 *
	 * @since 3.5.3
	 */
	public static function add_meta( $array, $post ) {
		$obj = get_post_type_object( 'testimonials' );
		$array['testimonials'] = array(
			'title' => $obj->labels->singular_name,
			'post_type' => array( 'testimonials' ),
			'settings' => array(
				'testimonial_author' => array(
					'title' => __( 'Author', 'total' ),
					'description' => __( 'Enter the name of the author for this testimonial.', 'total' ),
					'id' => 'wpex_testimonial_author',
					'type' => 'text',
				),
				'testimonial_company' => array(
					'title' => __( 'Company', 'total' ),
					'description' => __( 'Enter the name of the company for this testimonial.', 'total' ),
					'id' => 'wpex_testimonial_company',
					'type' => 'text',
				),
				'testimonial_url' => array(
					'title' => __( 'Company URL', 'total' ),
					'description' => __( 'Enter the URL for the company for this testimonial.', 'total' ),
					'id' => 'wpex_testimonial_url',
					'type' => 'text',
				),
				'post_rating' => array(
					'title' => __( 'Rating', 'total' ),
					'description' => __( 'Enter a rating for this testimonial.', 'total' ),
					'id' => 'wpex_post_rating', // Give it generic rating custom field that may be used on other types.
					'type' => 'number',
					'max' => '10',
					'min' => '1',
					'step' => '0.1',
				),
			),
		);
		return $array;
	}

	/**
	 * Add custom VC modules
	 *
	 * @since 3.5.3
	 */
	public static function vc_modules( $modules ) {
		$modules[] = 'testimonials_grid';
		$modules[] = 'testimonials_carousel';
		$modules[] = 'testimonials_slider';
		return $modules;
	}

}
new WPEX_Testimonials_Config;