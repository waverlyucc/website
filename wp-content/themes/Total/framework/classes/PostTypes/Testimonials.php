<?php
/**
 * Testimonials Post Type Configuration file
 *
 * @package Total WordPress Theme
 * @subpackage Portfolio Functions
 * @version 4.8
 */

namespace TotalTheme;

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// The class
class Testimonials {

	/**
	 * Get things started
	 *
	 * @since 2.0.0
	 */
	public function __construct() {

		// Adds the testimonials post type
		add_action( 'init', array( $this, 'register_post_type' ), 0 );

		// Adds the testimonials taxonomies
		if ( wpex_is_mod_enabled( wpex_get_mod( 'testimonials_categories', true ) ) ) {
			add_action( 'init', array( $this, 'register_categories' ), 0 );
		}

		// Register testimonials sidebar
		if ( wpex_get_mod( 'testimonials_custom_sidebar', true ) ) {
			add_filter( 'wpex_register_sidebars_array', array( $this, 'register_sidebar' ), 10 );
		}

		// Add image sizes
		add_filter( 'wpex_image_sizes', array( $this, 'add_image_sizes' ) );

		// Add testimonial VC modules
		add_filter( 'vcex_builder_modules', array( $this, 'vc_modules' ) );

		/*-------------------------------------------------------------------------------*/
		/* -  Admin only actions/filters
		/*-------------------------------------------------------------------------------*/
		if ( is_admin() ) {

			// Adds columns in the admin view for taxonomies
			add_filter( 'manage_edit-testimonials_columns', array( $this, 'edit_columns' ) );
			add_action( 'manage_testimonials_posts_custom_column', array( $this, 'column_display' ), 10, 2 );

			// Allows filtering of posts by taxonomy in the admin view
			add_action( 'restrict_manage_posts', array( $this, 'tax_filters' ) );

			// Add new image sizes tab
			add_filter( 'wpex_image_sizes_tabs', array( $this, 'image_sizes_tabs' ), 10 );

			// Add meta settings
			add_filter( 'wpex_metabox_array', array( $this, 'add_meta' ), 5, 2 );

		}

		/*-------------------------------------------------------------------------------*/
		/* -  Front-End only actions/filters
		/*-------------------------------------------------------------------------------*/
		else {

			// Display testimonials sidebar for testimonials
			if ( wpex_get_mod( 'testimonials_custom_sidebar', true ) ) {
				add_filter( 'wpex_get_sidebar', array( $this, 'display_sidebar' ), 10 );
			}

			// Alter the default page title
			add_action( 'wpex_page_header_title_args', array( $this, 'alter_title' ), 10 );

			// Alter the post layouts for testimonials posts and archives
			add_filter( 'wpex_post_layout_class', array( $this, 'layouts' ), 10 );

			// Posts per page
			add_action( 'pre_get_posts', array( $this, 'posts_per_page' ) );

			// Single next/prev visibility
			add_filter( 'wpex_has_next_prev', array( $this, 'next_prev' ), 10, 2 );

			// Alter previous post link title
			add_filter( 'wpex_prev_post_link_title', array( $this, 'prev_post_link_title' ), 10, 2 );

			// Alter next post link title
			add_filter( 'wpex_next_post_link_title', array( $this, 'next_post_link_title' ), 10, 2 );

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
	public function register_post_type() {

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
				'name'               => $name,
				'singular_name'      => $singular_name,
				'add_new'            => __( 'Add New', 'total' ),
				'add_new_item'       => __( 'Add New Item', 'total' ),
				'edit_item'          => __( 'Edit Item', 'total' ),
				'new_item'           => __( 'Add New Testimonials Item', 'total' ),
				'view_item'          => __( 'View Item', 'total' ),
				'search_items'       => __( 'Search Items', 'total' ),
				'not_found'          => __( 'No Items Found', 'total' ),
				'not_found_in_trash' => __( 'No Items Found In Trash', 'total' )
			),
			'public'          => true,
			'capability_type' => 'post',
			'has_archive'     => $has_archive ? true : false,
			'menu_icon'       => 'dashicons-'. $menu_icon,
			'menu_position'   => 20,
			'rewrite'         => array(
				'slug'        => $slug,
				'with_front'  => false
			),
			'supports'        => array(
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
	public function register_categories() {

		// Define and sanitize options
		$name = wpex_get_mod( 'testimonials_cat_labels');
		$name = $name ? esc_html( $name ) : __( 'Testimonials Categories', 'total' );
		$slug = wpex_get_mod( 'testimonials_cat_slug' );
		$slug = $slug ? esc_html( $slug ) : 'testimonials-category';

		// Define args and apply filters
		$args = apply_filters( 'wpex_taxonomy_testimonials_category_args', array(
			'labels' => array(
				'name'                       => $name,
				'singular_name'              => $name,
				'menu_name'                  => $name,
				'search_items'               => __( 'Search', 'total' ),
				'popular_items'              => __( 'Popular', 'total' ),
				'all_items'                  => __( 'All', 'total' ),
				'parent_item'                => __( 'Parent', 'total' ),
				'parent_item_colon'          => __( 'Parent', 'total' ),
				'edit_item'                  => __( 'Edit', 'total' ),
				'update_item'                => __( 'Update', 'total' ),
				'add_new_item'               => __( 'Add New', 'total' ),
				'new_item_name'              => __( 'New', 'total' ),
				'separate_items_with_commas' => __( 'Separate with commas', 'total' ),
				'add_or_remove_items'        => __( 'Add or remove', 'total' ),
				'choose_from_most_used'      => __( 'Choose from the most used', 'total' ),
			),
			'public'            => true,
			'show_in_nav_menus' => true,
			'show_ui'           => true,
			'show_tagcloud'     => true,
			'hierarchical'      => true,
			'query_var'         => true,
			'rewrite'           => array(
				'slug'          => $slug,
				'with_front'    => false
			),
		) );

		// Register the testimonials category taxonomy
		register_taxonomy( 'testimonials_category', array( 'testimonials' ), $args );

	}

	/**
	 * Adds columns to the WP dashboard edit screen
	 *
	 * @since 2.0.0
	 */
	public function edit_columns( $columns ) {
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
	public function column_display( $column, $post_id ) {
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
	public function tax_filters() {
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
	 * Registers a new custom testimonials sidebar
	 *
	 * @since 4.2.2
	 */
	public function register_sidebar( $sidebars ) {
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
	public function display_sidebar( $sidebar ) {
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
	public function alter_title( $args ) {
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
	public function layouts( $class ) {
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
	public function posts_per_page( $query ) {
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
	public function image_sizes_tabs( $array ) {
		$array['testimonials'] = wpex_get_testimonials_name();
		return $array;
	}

	/**
	 * Adds image sizes for the testimonials to the image sizes panel
	 *
	 * @since 2.0.0
	 */
	public function add_image_sizes( $sizes ) {
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
	public function next_prev( $display, $post_type ) {
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
	public function prev_post_link_title( $title, $post_type ) {
		if ( 'testimonials' == $post_type ) {
			$title = '<span class="ticon ticon-angle-double-left"></span>' . esc_html__( 'Previous', 'total' );
		}
		return $title;
	}

	/**
	 * Alter next post link title
	 *
	 * @since 2.0.0
	 */
	public function next_post_link_title( $title, $post_type ) {
		if ( 'testimonials' == $post_type ) {
			$title = esc_html__( 'Next', 'total' ) . '<span class="ticon ticon-angle-double-right"></span>';
		}
		return $title;
	}

	/**
	 * Adds testimonials meta options
	 *
	 * @since 3.5.3
	 */
	public function add_meta( $array, $post ) {
		$obj = get_post_type_object( 'testimonials' );
		$array['testimonials'] = array(
			'title'                   => $obj->labels->singular_name,
			'post_type'               => array( 'testimonials' ),
			'settings'                => array(
				'testimonial_author'  => array(
					'title'           => __( 'Author', 'total' ),
					'description'     => __( 'Enter the name of the author for this testimonial.', 'total' ),
					'id'              => 'wpex_testimonial_author',
					'type'            => 'text',
				),
				'testimonial_company' => array(
					'title'           => __( 'Company', 'total' ),
					'description'     => __( 'Enter the name of the company for this testimonial.', 'total' ),
					'id'              => 'wpex_testimonial_company',
					'type'            => 'text',
				),
				'testimonial_url'     => array(
					'title'           => __( 'Company URL', 'total' ),
					'description'     => __( 'Enter the URL for the company for this testimonial.', 'total' ),
					'id'              => 'wpex_testimonial_url',
					'type'            => 'text',
				),
				'post_rating'         => array(
					'title'           => __( 'Rating', 'total' ),
					'description'     => __( 'Enter a rating for this testimonial.', 'total' ),
					'id'              => 'wpex_post_rating',
					'type'            => 'number',
					'max'             => '10',
					'min'             => '1',
					'step'            => '0.1',
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
	public function vc_modules( $modules ) {
		$modules[] = 'testimonials_grid';
		$modules[] = 'testimonials_carousel';
		$modules[] = 'testimonials_slider';
		return $modules;
	}

}
new Testimonials;