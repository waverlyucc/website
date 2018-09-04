<?php
/**
 * Plugin Name: Ultimate Dashboard
 * Plugin URI: https://ultimatedashboard.io/
 * Description: Ultimate Dashboard gives you full control over your WordPress Dashboard. Remove the default Dashboard Widgets and and create your own for a better user experience.
 * Version: 2.2
 * Author: MapSteps
 * Author URI: https://mapsteps.com/
 * Text Domain: ultimatedashboard
 */

// exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

// Plugin constants
define( 'ULTIMATE_DASHBOARD_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'ULTIMATE_DASHBOARD_PLUGIN_URL', plugin_dir_url( __FILE__ ) );

// Textdomain
add_action( 'plugins_loaded', 'udb_textdomain' );
function udb_textdomain() {
	load_plugin_textdomain( 'ultimatedashboard', false, plugin_basename( dirname( __FILE__ ) ) . '/languages' ); 
}

// Admin Scripts
add_action( 'admin_enqueue_scripts', 'udb_admin_scripts' );
function udb_admin_scripts() {

	global $pagenow, $typenow;

	// Widget edit screen & create a new Widget screen
	if( ( $pagenow == 'post.php' || $pagenow == 'post-new.php' ) && $typenow == 'udb_widgets' ) {

		wp_register_style( 'font-awesome',  ULTIMATE_DASHBOARD_PLUGIN_URL . 'assets/css/font-awesome.min.css', false, '4.7.0' );
		wp_enqueue_style( 'font-awesome' );

		wp_register_style( 'ultimate-dashboard-cpt', ULTIMATE_DASHBOARD_PLUGIN_URL . 'assets/css/ultimate-dashboard-cpt.css', false, '' );
		wp_enqueue_style( 'ultimate-dashboard-cpt' );

		wp_register_script( 'ultimate-dashboard-cpt', ULTIMATE_DASHBOARD_PLUGIN_URL . 'assets/js/ultimate-dashboard-cpt.js', array('jquery'), '', true );
		wp_enqueue_script( 'ultimate-dashboard-cpt' );
	}

	// Dashboard Widget Overview & Settings
	if( $pagenow == 'edit.php' && $typenow == 'udb_widgets' ) {

		wp_register_style( 'font-awesome',  ULTIMATE_DASHBOARD_PLUGIN_URL . 'assets/css/font-awesome.min.css', false, '4.7.0' );
		wp_enqueue_style( 'font-awesome' );

		wp_register_style( 'ultimate-dashboard-settings', ULTIMATE_DASHBOARD_PLUGIN_URL . 'assets/css/ultimate-dashboard-settings.css', false, '' );
		wp_enqueue_style( 'ultimate-dashboard-settings' );

	}

	// WordPress Dashboard
	if( $pagenow == 'index.php' ) {

		wp_register_style( 'font-awesome',  ULTIMATE_DASHBOARD_PLUGIN_URL . 'assets/css/font-awesome.min.css', false, '4.7.0' );
		wp_enqueue_style( 'font-awesome' );

		wp_register_style( 'ultimate-dashboard-index', ULTIMATE_DASHBOARD_PLUGIN_URL . 'assets/css/ultimate-dashboard-index.css', false, '' );
		wp_enqueue_style( 'ultimate-dashboard-index' );

	}

}

// Action Links
add_filter( 'plugin_action_links_' . plugin_basename(__FILE__), 'udb_add_action_links' );

function udb_add_action_links( $links ) {

	$settings = array( '<a href="' . admin_url( 'edit.php?post_type=udb_widgets&page=settings' ) . '">'. __( 'Settings', 'ultimatedashboard' ) .'</a>' );
	return array_merge( $links, $settings );

}

// Required Files
require_once ULTIMATE_DASHBOARD_PLUGIN_DIR . 'inc/ultimate-dashboard-init.php';