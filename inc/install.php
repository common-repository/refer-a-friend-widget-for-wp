<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}


if( !function_exists('wp_ib_install') ){
	function wp_ib_install(){
		global $wpdb;

		$installed_ver = get_option( IB_PLUGIN_VERSION_KEY );

		if ( $installed_ver != IB_PLUGIN_VERSION_NUM ) {

			$table_name = IB_PLUGIN_TABLE_NAME;
			$table_db_name = IB_PLUGIN_DB_TABLE_NAME;
			$charset_collate = $wpdb->get_charset_collate();

			$sql = "CREATE TABLE IF NOT EXISTS {$table_db_name} (
					  `id` bigint(20) NOT NULL AUTO_INCREMENT,
					  `name` text NOT NULL,
					  `pkey` varchar(255) NOT NULL,
					  `secret_key` varchar(255) NOT NULL,
					  `url` bigint(20) NOT NULL,
					  `is_default` tinyint(1) NOT NULL DEFAULT '0',
					  PRIMARY KEY (`id`)
					) $charset_collate;";

			require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

			dbDelta( $sql );

			/* if table created */
			if($wpdb->get_var("SHOW TABLES LIKE '{$table_db_name}'") == $table_db_name){

				/* add plugin version option */
				add_option(IB_PLUGIN_VERSION_KEY, IB_PLUGIN_VERSION_NUM);

				/* update table data */
				wp_ib_db_update();
			}
		}
	}
}
if( !function_exists('wp_ib_db_update') ){
	function wp_ib_db_update(){
		global $wpdb;

		$table_name = IB_PLUGIN_DB_TABLE_NAME;
		$wp_ib_secret_key = get_option('wp_ib_secret_key');
		$wp_ib_show_options = get_option('wp_ib_show_options');
		$count = $wpdb->get_var("SELECT COUNT(*) FROM {$table_name}  WHERE `secret_key` IS NOT NULL");
		if($wp_ib_secret_key && $count == 0) {
			$ch = curl_init();
			curl_setopt ($ch, CURLOPT_URL, "http://invitebox.com/invitation-camp/wordpress/?skey=" . $wp_ib_secret_key);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt ($ch, CURLOPT_HTTPGET, true);
			$result = curl_exec($ch);
			$response = json_decode($result);
			$error_code = curl_errno($ch);
			if($response->success == true && $error_code == 0){
				$wp_ib_name = $response->campaign;
				$wp_ib_key = $response->pkey;
				$wp_ib_secret_key = $response->secret_key;
				$wp_ib_url = $response->id;
				$wp_ib_is_default = ($wp_ib_show_options != 'one_page') ? 1 : 0;

				$sql = "INSERT INTO {$table_name} (`name`, `pkey`, `secret_key`, `url`, `is_default`) VALUES (%s, %s, %s, %d, %d);";
				$sql = $wpdb->prepare($sql, $wp_ib_name, $wp_ib_key, $wp_ib_secret_key, $wp_ib_url, $wp_ib_is_default);
				$result = $wpdb->query($sql);
			}

			/* delete old options fields*/
			delete_option('wp_ib_key');
			delete_option('wp_ib_secret_key');
			delete_option('wp_ib_url');
			delete_option('wp_ib_show_options');
		}

		update_option(IB_PLUGIN_VERSION_KEY, IB_PLUGIN_VERSION_NUM);
	}
}

if( !function_exists('wp_ib_update_db_check') ){
	function wp_ib_update_db_check() {
		if ( get_site_option( IB_PLUGIN_VERSION_KEY ) != IB_PLUGIN_VERSION_NUM ) {
			wp_ib_install();
		}
	}
}