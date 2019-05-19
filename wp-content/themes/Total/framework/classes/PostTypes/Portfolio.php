<?php
/**
 * Portfolio Post Type Configuration file
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

class Portfolio {

	/**
	 * Get things started.
	 *
	 * @since 2.0.0
	 */
	public function __construct() {

		// Adds the portfolio post type
		add_action( 'init', array( $this, 'register_post_type' ), 0 );

		// Register portfolio tags if enabled
		if ( wpex_is_mod_enabled( wpex_get_mod( 'portfolio_tags', true ) ) ) {
			add_action( 'init', array( $this, 'register_tags' ), 0 );
		}

		// Register portfolio categories if enabled
		if ( wpex_is_mod_enabled( wpex_get_mod( 'portfolio_categories', true ) ) ) {
			add_action( 'init', array( $this, 'register_categories' ), 0 );
		}

		// Adds the portfolio custom sidebar
		if ( wpex_get_mod( 'portfolio_custom_sidebar', true ) ) {
			add_filter( 'wpex_register_sidebars_array', array( $this, 'register_sidebar' ), 10 );
		}

		// Add image sizes
		add_filter( 'wpex_image_sizes', array( $this, 'add_image_sizes' ), 10 );

		// Register translation strings
		add_filter( 'wpex_register_theme_mod_strings', array( $this, 'register_theme_mod_strings' ) );

		// Add portfolio VC modules
		add_filter( 'vcex_builder_modules', array( $this, 'vc_modules' ) );

		/*-------------------------------------------------------------------------------*/
		/* -  Admin only actions/filters
		/*-------------------------------------------------------------------------------*/
		if ( is_admin() ) {

			// Adds columns in the admin view for taxonomies
			add_filter( 'manage_edit-portfolio_columns', array( $this, 'edit_columns' ) );
			add_action( 'manage_portfolio_posts_custom_column', array( $this, 'column_display' ), 10, 2 );

			// Allows filtering of posts by taxonomy in the admin view
			add_action( 'restrict_manage_posts', array( $this, 'tax_filters' ) );

			// Add new image sizes tab
			add_filter( 'wpex_image_sizes_tabs', array( $this, 'image_sizes_tabs' ), 10 );

			// Add gallery metabox to portfolio
			add_filter( 'wpex_gallery_metabox_post_types', array( $this, 'add_gallery_metabox' ), 20 );

		}

		/*-------------------------------------------------------------------------------*/
		/* -  Front-End only actions/filters
		/*-------------------------------------------------------------------------------*/
		else {

			// Display correct sidebar for portfolio items
			if ( wpex_get_mod( 'portfolio_custom_sidebar', true ) ) {
				add_filter( 'wpex_get_sidebar', array( $this, 'display_sidebar' ), 10 );
			}

			// Alter the post layouts for portfolio posts and archives
			add_filter( 'wpex_post_layout_class', array( $this, 'layouts' ), 10 );

			// Archive posts per page
			add_action( 'pre_get_posts', array( $this, 'posts_per_page' ) );

			// Single next/prev visibility
			add_filter( 'wpex_has_next_prev', array( $this, 'next_prev' ), 10, 2 );

			// Tweak page header title
			add_filter( 'wpex_page_header_title_args', array( $this, 'alter_title' ), 10 );

		}

	}

	/*-------------------------------------------------------------------------------*/
	/* -  Start Class Functions
	/*-------------------------------------------------------------------------------*/

	/**
	 * Register post type.
	 *
	 * @since 2.0.0
	 */
	public function register_post_type() {

		// Get values and sanitize
		$name          = wpex_get_portfolio_name();
		$singular_name = wpex_get_portfolio_singular_name();
		$has_archive   = wpex_get_mod( 'portfolio_has_archive', false );

		$default_slug  = $has_archive ? 'portfolio' : 'portfolio-item';
		$slug          = wpex_get_mod( 'portfolio_slug' );
		$slug          = $slug ? $slug : $default_slug;

		$menu_icon     = wpex_get_portfolio_menu_icon();

		// Register the post type
		register_post_type( 'portfolio', apply_filters( 'wpex_portfolio_args', array(
			'labels' => array(
				'name' => $name,
				'singular_name' => $singular_name,
				'add_new' => __( 'Add New', 'total' ),
				'add_new_item' => __( 'Add New Item', 'total' ),
				'edit_item' => __( 'Edit Item', 'total' ),
				'new_item' => __( 'Add New Item', 'total' ),
				'view_item' => __( 'View Item', 'total' ),
				'search_items' => __( 'Search Items', 'total' ),
				'not_found' => __( 'No Items Found', 'total' ),
				'not_found_in_trash' => __( 'No Items Found In Trash', 'total' )
			),
			'public' => true,
			'capability_type' => 'post',
			'has_archive' => $has_archive ? true : false,
			'menu_icon' => 'dashicons-' . $menu_icon,
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
	 * Register Portfolio tags.
	 *
	 * @since 2.0.0
	 */
	public function register_tags() {

		// Define and sanitize options
		$name = wpex_get_mod( 'portfolio_tag_labels');
		$name = $name ? $name : __( 'Portfolio Tags', 'total' );
		$slug = wpex_get_mod( 'portfolio_tag_slug' );
		$slug = $slug ? $slug : 'portfolio-tag';

		// Define portfolio tag arguments
		$args = array(
			'labels' => array(
				'name' => $name,
				'singular_name' => $name,
				'menu_name' => $name,
				'search_items' => __( 'Search','total' ),
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
			'hierarchical' => false,
			'rewrite' => array( 'slug' => $slug, 'with_front' => false ),
			'query_var' => true,
		);

		// Apply filters
		$args = apply_filters( 'wpex_taxonomy_portfolio_tag_args', $args );

		// Register the portfolio tag taxonomy
		register_taxonomy( 'portfolio_tag', array( 'portfolio' ), $args );

	}

	/**
	 * Register Portfolio category.
	 *
	 * @since 2.0.0
	 */
	public function register_categories() {

		// Define and sanitize options
		$name = esc_html( wpex_get_mod( 'portfolio_cat_labels' ) );
		$name = $name ? $name : __( 'Portfolio Categories', 'total' );
		$slug = wpex_get_mod( 'portfolio_cat_slug' );
		$slug = $slug ? esc_html( $slug ) : 'portfolio-category';

		// Define args and apply filters
		$args = apply_filters( 'wpex_taxonomy_portfolio_category_args', array(
			'labels' => array(
				'name' => $name,
				'singular_name' => $name,
				'menu_name' => $name,
				'search_items' => __( 'Search','total' ),
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
			'rewrite' => array( 'slug' => $slug, 'with_front' => false ),
			'query_var' => true
		) );

		// Register the portfolio category taxonomy
		register_taxonomy( 'portfolio_category', array( 'portfolio' ), $args );

	}

	/**
	 * Adds columns to the WP dashboard edit screen.
	 *
	 * @since 2.0.0
	 */
	public function edit_columns( $columns ) {
		if ( taxonomy_exists( 'portfolio_category' ) ) {
			$columns['portfolio_category'] = esc_html__( 'Category', 'total' );
		}
		if ( taxonomy_exists( 'portfolio_tag' ) ) {
			$columns['portfolio_tag']      = esc_html__( 'Tags', 'total' );
		}
		return $columns;
	}


	/**
	 * Adds columns to the WP dashboard edit screen.
	 *
	 * @since 2.0.0
	 */
	public function column_display( $column, $post_id ) {

		switch ( $column ) :

			// Display the portfolio categories in the column view
			case 'portfolio_category':

				if ( $category_list = get_the_term_list( $post_id, 'portfolio_category', '', ', ', '' ) ) {
					echo $category_list;
				} else {
					echo '&mdash;';
				}

			break;

			// Display the portfolio tags in the column view
			case 'portfolio_tag':

				if ( $tag_list = get_the_term_list( $post_id, 'portfolio_tag', '', ', ', '' ) ) {
					echo $tag_list;
				} else {
					echo '&mdash;';
				}

			break;

		endswitch;

	}

	/**
	 * Adds taxonomy filters to the portfolio admin page.
	 *
	 * @since 2.0.0
	 */
	public function tax_filters() {
		global $typenow;
		$taxonomies = array( 'portfolio_category', 'portfolio_tag' );
		if ( 'portfolio' == $typenow ) {
			foreach ( $taxonomies as $tax_slug ) {
				if ( ! taxonomy_exists( $tax_slug ) ) {
					continue;
				}
				$current_tax_slug = isset( $_GET[$tax_slug] ) ? esc_html( $_GET[$tax_slug] ) : false;
				$tax_obj = get_taxonomy( $tax_slug );
				$tax_name = $tax_obj->labels->name;
				$terms = get_terms($tax_slug);
				if ( count( $terms ) > 0) {
					echo "<select name='$tax_slug' id='$tax_slug' class='postform'>";
					echo "<option value=''>$tax_name</option>";
					foreach ( $terms as $term ) {
						echo '<option value=' . $term->slug, $current_tax_slug == $term->slug ? ' selected="selected"' : '','>' . $term->name .' (' . $term->count .')</option>';
					}
					echo "</select>";
				}
			}
		}
	}

	/**
	 * Registers a new custom portfolio sidebar.
	 *
	 * @since 4.5
	 */
	public function register_sidebar( $sidebars ) {
		$obj            = get_post_type_object( 'portfolio' );
		$post_type_name = $obj->labels->name;
		$sidebars['portfolio_sidebar'] = $post_type_name . ' ' . esc_html__( 'Sidebar', 'total' );
		return $sidebars;
	}

	/**
	 * Alter main sidebar to display portfolio sidebar.
	 *
	 * @since 2.0.0
	 */
	public function display_sidebar( $sidebar ) {
		if ( is_singular( 'portfolio' ) || wpex_is_portfolio_tax() || is_post_type_archive( 'portfolio' ) ) {
			$sidebar = 'portfolio_sidebar';
		}
		return $sidebar;
	}

	/**
	 * Alter the post layouts for portfolio posts and archives.
	 *
	 * @since 2.0.0
	 */
	public function layouts( $layout_class ) {
		if ( is_singular( 'portfolio' ) ) {
			$layout_class = wpex_get_mod( 'portfolio_single_layout', 'full-width' );
		} elseif ( wpex_is_portfolio_tax() || is_post_type_archive( 'portfolio' ) ) {
			$layout_class = wpex_get_mod( 'portfolio_archive_layout', 'full-width' );
		}
		return $layout_class;
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
		if ( wpex_is_portfolio_tax() || $query->is_post_type_archive( 'portfolio' ) ) {
			$query->set( 'posts_per_page', absint( wpex_get_mod( 'portfolio_archive_posts_per_page', 12 ) ) );
		}
	}

	/**
	 * Adds a "portfolio" tab to the image sizes admin panel
	 *
	 * @since 3.3.2
	 */
	public function image_sizes_tabs( $array ) {
		$array['portfolio'] = wpex_get_portfolio_name();
		return $array;
	}

	/**
	 * Adds image sizes for the portfolio to the image sizes panel.
	 *
	 * @since 2.0.0
	 */
	public function add_image_sizes( $sizes ) {
		$obj            = get_post_type_object( 'portfolio' );
		$post_type_name = $obj->labels->singular_name;
		$sizes['portfolio_entry'] = array(
			'label'   => sprintf( esc_html__( '%s Entry', 'total' ), $post_type_name ),
			'width'   => 'portfolio_entry_image_width',
			'height'  => 'portfolio_entry_image_height',
			'crop'    => 'portfolio_entry_image_crop',
			'section' => 'portfolio',
		);
		$sizes['portfolio_post'] = array(
			'label'   => sprintf( esc_html__( '%s Post', 'total' ), $post_type_name ),
			'width'   => 'portfolio_post_image_width',
			'height'  => 'portfolio_post_image_height',
			'crop'    => 'portfolio_post_image_crop',
			'section' => 'portfolio',
		);
		$sizes['portfolio_related'] = array(
			'label'   => sprintf( esc_html__( '%s Post Related', 'total' ), $post_type_name ),
			'width'   => 'portfolio_related_image_width',
			'height'  => 'portfolio_related_image_height',
			'crop'    => 'portfolio_related_image_crop',
			'section' => 'portfolio',
		);
		return $sizes;
	}

	/**
	 * Adds the portfolio post type to the gallery metabox post types array.
	 *
	 * @since 2.0.0
	 */
	public function add_gallery_metabox( $types ) {
		$types[] = 'portfolio';
		return $types;
	}

	/**
	 * Disables the next/previous links if disabled via the customizer.
	 *
	 * @since 2.0.0
	 */
	public function next_prev( $display, $post_type ) {
		if ( 'portfolio' == $post_type ) {
			$display = wpex_get_mod( 'portfolio_next_prev', true ) ? true : false;
		}
		return $display;
	}

	/**
	 * Tweak the page header title args
	 *
	 * @since 2.1.0
	 */
	public function alter_title( $args ) {
		if ( is_singular( 'portfolio' ) ) {
			$blocks = wpex_portfolio_single_blocks();
			if ( is_array( $blocks ) && ! in_array( 'title', $blocks ) ) {
				$args['string']   = single_post_title( '', false );
				$args['html_tag'] = 'h1';
			}
		}
		return $args;
	}

	/**
	 * Register portfolio theme mod strings
	 *
	 * @since 2.1.0
	 */
	public function register_theme_mod_strings( $strings ) {
		if ( is_array( $strings ) ) {
			$strings['portfolio_labels'] = 'Portfolio';
			$strings['portfolio_singular_name'] = 'Portfolio Item';
		}
		return $strings;
	}

	/**
	 * Add custom VC modules
	 *
	 * @since 3.5.3
	 */
	public function vc_modules( $modules ) {
		$modules[] = 'portfolio_grid';
		$modules[] = 'portfolio_carousel';
		return $modules;
	}

}
new Portfolio;