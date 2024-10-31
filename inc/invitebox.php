<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

add_action( 'wp_footer', 'wp_ib', 100 );

if ( ! function_exists( 'wp_ib' ) ) {
	function wp_ib( $content ) {
		echo wp_ib_format();
	}
}

if ( ! function_exists( 'wp_ib_format' ) ) {
	function wp_ib_format() {
		global $post;
		$output = '';
		if ( ! strpos( $post->post_content, 'invitebox-script' ) && ! strpos( $post->post_content, 'invitebox-track' ) ) {
			$default_campaign = wp_ib_get_default_campaign();
			if ( $default_campaign ) {
				$output = "<script id='invitebox-script' type='text/javascript'>
				            (function() {
				                var ib = document.createElement('script');
				                ib.type = 'text/javascript';
				                ib.async = true;
				                ib.src = ('https:' == document.location.protocol ? 'https://' : 'http://') + 'invitebox.com/invitation-camp/" . $default_campaign->url . "/invitebox.js?key=" . $default_campaign->pkey . "&jquery='+(typeof(jQuery)=='undefined');
				                var s = document.getElementsByTagName('script')[0];
				                s.parentNode.insertBefore(ib, s);
				            })();
				            </script>";
			}
		}

		return $output;
	}
}
if ( ! function_exists( 'wp_ib_get_default_campaign' ) ) {
	function wp_ib_get_default_campaign() {
		global $wpdb;
		$table_db_name = IB_PLUGIN_DB_TABLE_NAME;
		$result        = $wpdb->get_row( "SELECT `url`, `pkey` FROM {$table_db_name} WHERE `is_default`=1;" );
		if ( $result != null ) {
			return $result;
		}

		return false;
	}
}