<?php
/**
 * Plugin Name: Woo Resturant Cafe Menu
 * Description: Displays a WooCommerce-powered restaurant/cafe menu, either as a shortcode or as a standalone full-page menu fully isolated from the active theme.
 * Version: 0.1.0
 * Author: OpenAI
 * Text Domain: woo-resturant-cafe-menu
 * Domain Path: /languages
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'WRMP_VERSION', '0.1.0' );
define( 'WRMP_FILE', __FILE__ );
define( 'WRMP_PATH', plugin_dir_path( __FILE__ ) );
define( 'WRMP_URL', plugin_dir_url( __FILE__ ) );

require_once WRMP_PATH . 'includes/class-wrmp-plugin.php';

register_activation_hook( WRMP_FILE, array( 'WRMP_Plugin', 'activate' ) );
register_deactivation_hook( WRMP_FILE, array( 'WRMP_Plugin', 'deactivate' ) );

function wrmp() {
	return WRMP_Plugin::instance();
}

wrmp();
