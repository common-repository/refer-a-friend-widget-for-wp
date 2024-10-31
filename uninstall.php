<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( !defined( 'WP_UNINSTALL_PLUGIN' ) )
	exit;

delete_option('wp_ib_errorcode');
delete_option('wp_ib_isresponse');
delete_option('wp_ib_show_options');
delete_option('wp_ib_version');

// For site options in multisite
delete_site_option('wp_ib_errorcode');
delete_site_option('wp_ib_isresponse');
delete_site_option('wp_ib_show_options');
delete_site_option('wp_ib_version');

//drop a custom db table
global $wpdb;
$table_db_name = IB_PLUGIN_DB_TABLE_NAME;
$result = $wpdb->query( "DROP TABLE IF EXISTS {$wpdb->prefix}invitebox" );