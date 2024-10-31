<?php
/*
  Plugin Name: Wordpress InviteBox Plugin
  Description: Add InviteBox-powered referral program to your WordPress blog
  Version: 1.4.1
  Plugin URI: http://invitebox.com/
 */

if (!defined( 'ABSPATH' )) {
	exit; // Exit if accessed directly
}

/**
 * Constants
 */
global $wpdb;

if (!defined( 'IB_PLUGIN_DIR' ))
	define( 'IB_PLUGIN_DIR', untrailingslashit( plugin_dir_path( __FILE__ ) ) );

if (!defined( 'IB_PLUGIN_URL' ))
	define( 'IB_PLUGIN_URL', untrailingslashit( plugins_url( '', __FILE__ ) ) );

if (!defined('IB_PLUGIN_VERSION_KEY'))
	define('IB_PLUGIN_VERSION_KEY', 'wp_ib_version');

if (!defined('IB_PLUGIN_VERSION_NUM'))
	define('IB_PLUGIN_VERSION_NUM', '1.4.0');

if (!defined('IB_PLUGIN_TABLE_NAME'))
	define('IB_PLUGIN_TABLE_NAME', 'invitebox');

if (!defined('IB_PLUGIN_DB_TABLE_NAME'))
	define('IB_PLUGIN_DB_TABLE_NAME', $wpdb->prefix . 'invitebox');


if (is_admin()){

	/**
	 * Install Wordpress InviteBox
	 */
	require_once( IB_PLUGIN_DIR . '/inc/install.php' );

	register_activation_hook( __FILE__, 'wp_ib_install' );

	add_action( 'plugins_loaded', 'wp_ib_update_db_check' );

	/**
	 * Admin Page
	 */
	require_once( IB_PLUGIN_DIR . '/admin/admin.php' );

	/**
	 * Page / Post Invitebox Button
	 */
	require_once( IB_PLUGIN_DIR . '/admin/button.php' );

	if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
		require_once( IB_PLUGIN_DIR . '/plugins/woocommerce/admin-settings.php' );
	}

}else{
	/**
	 * Show Invitebox Script on Pages
	 */
	require_once( IB_PLUGIN_DIR . '/inc/invitebox.php' );

	if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
		require_once( IB_PLUGIN_DIR . '/plugins/woocommerce/functions.php' );
	}
}
