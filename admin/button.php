<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

add_action('admin_head', 'wp_ib_fbutton');
add_action('admin_head', 'wp_ib_add_icon');
add_action('admin_enqueue_scripts', 'wp_ib_style');

add_filter("mce_external_plugins", "wp_ib_plugin");
add_filter('mce_buttons', 'wp_ib_button');


/**
 * Adding icon to post or page tinymce
 */
if( !function_exists('wp_ib_add_icon') ) {
	function wp_ib_add_icon() {
		global $typenow;
		// check user permissions
		if ( !current_user_can('edit_posts') && !current_user_can('edit_pages') ) {
			return;
		}

		// verify the post type
		if( ! in_array( $typenow, array( 'post', 'page' ) ) )
			return;

	}
}

/**
 * Enqueue Script
 */
 
if( !function_exists('wp_ib_plugin') ) {
	function wp_ib_plugin($plugin_array) {
		$plugin_array['invitebox_button'] = IB_PLUGIN_URL . '/js/invitebox_button.js';
		return $plugin_array;
	}
}

/**
 * Enqueue Style
 */
if(!function_exists('wp_ib_style')) {
	function wp_ib_style() {
		wp_enqueue_style('wp_ib', IB_PLUGIN_URL . '/css/style.css');
	}
}

/**
 * Add To MCE Buttons
 */
if( !function_exists('wp_ib_button') ) {
	function wp_ib_button($buttons) {
		array_push($buttons, "invitebox_button");
		return $buttons;
	}
}

if ( !function_exists('wp_ib_fbutton') ) {
	function wp_ib_fbutton( $content ) {
		echo wp_ib_for_button();
	}
}

if( !function_exists('wp_ib_for_button') ) {
	function wp_ib_for_button() {
		$all_campaigns = wp_ib_get_all_campaigns();
		$all_campaigns = $all_campaigns ? $all_campaigns : '';
		$output = "<script type='text/javascript'> var all_campaigns = " . json_encode( $all_campaigns ) . ";</script>";

		return $output;
	}
}