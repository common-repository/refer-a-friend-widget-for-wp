<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Admin Menu
 */
add_action( 'admin_menu', 'wp_ib_settings' );

/**
 * Ajax
 */
add_action( 'wp_ajax_ajax_actions', 'wp_ib_ajax_actions' );
add_action( 'wp_ajax_nopriv_ajax_actions', 'wp_ib_ajax_actions' );


/**
 * Create Admin Menu Page
 */
if ( ! function_exists( 'wp_ib_settings' ) ) {
	function wp_ib_settings() {
		$menu_page = add_menu_page( "Invitebox", "Invitebox", 'manage_options', IB_PLUGIN_TABLE_NAME . '-settings', "wp_ib_opt" );
		add_action( 'admin_print_styles-' . $menu_page, 'wp_ib_admin_css' );
		add_action( 'admin_print_scripts-' . $menu_page, 'wp_ib_admin_js' );
	}
}

/**
 * Enqueue Styles
 */
if ( ! function_exists( 'wp_ib_admin_css' ) ) {
	function wp_ib_admin_css() {
		wp_enqueue_style( 'admin_css', IB_PLUGIN_URL . '/css/admin.css' );
	}
}

/**
 * Enqueue Scripts
 */
if ( ! function_exists( 'wp_ib_admin_js' ) ) {
	function wp_ib_admin_js() {
		wp_register_script( 'admin_js', IB_PLUGIN_URL . '/js/admin_js.js', array( 'jquery' ), '', true );
		wp_localize_script( 'admin_js', 'ajaxparams', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) );
		wp_enqueue_script( 'admin_js' );
	}
}

/**
 * Ajax
 */
if ( ! function_exists( 'wp_ib_ajax_actions' ) ) {
	function wp_ib_ajax_actions() {
		$user_ID       = get_current_user_id();
		$function_name = 'wp_ib_' . $_REQUEST['sub_action'];
		$function_name( $user_ID );
		exit;
	}
}

/**
 * Admin View
 */
if ( ! function_exists( 'wp_ib_opt' ) ) {
	function wp_ib_opt() {
		$all_campaigns = wp_ib_get_all_campaigns(); ?>

		<div class="wrap invitebox-wrap">
			<div id="icon-themes" class="icon32"></div>
			<h1><?php _e( 'Invitebox Settings', 'wp_invitebox' ); ?></h1>

			<?php if ( isset( $_GET['message'] ) && $_GET['message'] != '' && isset( $_GET['success'] ) ) { ?>
				<div id="message">
					<div class="notice notice-<?php echo ( $_GET['success'] == 'true' ) ? 'success' : 'error'; ?>">
						<p><?php echo $_GET['message']; ?></p></div>
				</div>
			<?php } ?>

			<fieldset>
				<form name="wp_ib_option_form" id="id-form" method="post">
					<div class="invitebox-options">
						<label for="wp_ib_default_campaign" class="wp_ib_default_campaign_label">
							<span class="title"><?php _e( 'Default Campaign for all pages', 'wp_invitebox' ); ?></span><br/>

							<select id="wp_ib_default_campaign" name="wp_ib_default_campaign">
								<option value=""><?php _e( 'No Default Campaign', 'wp_invitebox' ); ?></option>
								<?php if ( $all_campaigns ) {
									foreach ( $all_campaigns as $campaign ) { ?>
										<option value="<?php echo $campaign->secret_key; ?>" <?php echo $campaign->is_default == 0 ? '' : 'selected="selected"'; ?>><?php echo $campaign->name; ?></option>
									<?php
									}
								}?>
							</select>
						</label>
						<div class="notification-message">
							<?php _e( "This option is only for use with the widget in popup or badge mode." , 'wp_invitebox' ); ?><br/>
							<?php _e( "This campaign will be appear in all posts and pages, except those pages where you have added the widget script manually." , 'wp_invitebox' ); ?>
						</div>
					</div>
					<hr/>

					<h2 class="title"><?php _e( 'Campaigns', 'wp_invitebox' ); ?></h2>
					<table class="wp-campaign-table widefat">
						<thead>
						<tr>
							<th scope="col" id="col-name" class="manage-column column-name">
								<?php _e( 'Campaign Name', 'wp_invitebox' ); ?>
								<div class="notification-message">
									( <?php _e( 'No need to fill this field', 'wp_invitebox' ); ?> )
								</div>
							</th>
							<th scope="col" id="col-sec_key" class="manage-column column-sec_key">
								<?php _e( 'Campaign Secret Key', 'wp_invitebox' ); ?>
							</th>
							<th scope="col" id="col-buttons" class="column-buttons"></th>
						</tr>
						</thead>

						<tbody id="the-campaigns-list">
						<?php if ( $all_campaigns ) {
							foreach ( $all_campaigns as $campaign ) { ?>
								<tr id="campaign_<?php echo $campaign->id; ?>">
									<td class="campaign_name">
										<input type="text" name="campaign_name" value="<?php echo $campaign->name; ?>" disabled/>
									</td>
									<td class="campaign_secret_key">
										<input type="text" name="campaign_secret_key" value="<?php echo $campaign->secret_key; ?>" disabled/>
									</td>
									<th class="campaign_buttons">
										<input type="submit" value="Remove" class="button button-secondary button-remove"/>
									</th>
								</tr>
							<?php
							}
						} ?>
						</tbody>

						<tfoot>
						<tr>
							<td colspan="3">
								<button class="button" id="add_new_campaign_button"><?php _e( 'Add New Campaign', 'wp_invitebox' ); ?></button>
							</td>
						</tr>
						</tfoot>
					</table>
					<p class="notification-message"><?php _e( 'The secret key can be found in your InviteBox account under "Campaign -> Integration -> Show API Settings".', 'wp_invitebox' ); ?>
						<a href="<?php echo IB_PLUGIN_URL . '/images/see_attachement.png'; ?>" target="_blank">(<?php _e('See attached screenshot'); ?>)</a>
					</p>
                    <?php if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {?>
                        <hr/>
                        <h2 class="title"><?php _e( 'WooCommerce', 'wp_invitebox' ); ?></h2>
                        <p>
                            <a href="<?php echo admin_url('admin.php?page=wc-settings&tab=wp_ib_settings_tab');?>"><?php _e( 'Click here', 'wp_invitebox' ); ?></a> <?php _e( 'to configure your WooCommerce integration.', 'wp_invitebox' ); ?>
                        </p>
                    <?php }?>
				</form>
			</fieldset>
		</div>
		<hr/>
	<?php
	}
}

/**
 * Get All Campaigns
 */
if ( ! function_exists( 'wp_ib_get_all_campaigns' ) ) {
	function wp_ib_get_all_campaigns() {
		global $wpdb;
		$table_db_name = IB_PLUGIN_DB_TABLE_NAME;
		if ( $wpdb->get_var( "SHOW TABLES LIKE '{$table_db_name}'" ) == $table_db_name ) {
			$result = $wpdb->get_results( "SELECT * FROM {$table_db_name};" );
			if ( null != $result ) {
				return $result;
			}
		}

		return false;
	}
}

/****************** Ajax Functions ******************/

/**
 * Add Campaign
 */
if ( ! function_exists( 'wp_ib_add_campaign' ) ) {
	function wp_ib_add_campaign() {
		$user_ID = get_current_user_id();

		if ( '' != $user_ID && current_user_can( 'manage_options' ) ) {
			$secret_key = sanitize_text_field( $_POST['secret_key'] );
			$ch         = curl_init();
			curl_setopt( $ch, CURLOPT_URL, "http://invitebox.com/invitation-camp/wordpress/?skey=" . $secret_key );
			curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
			curl_setopt( $ch, CURLOPT_HTTPGET, true );
			$result     = curl_exec( $ch );
			$response   = json_decode( $result );
			$error_code = curl_errno( $ch );

			update_option( "wp_ib_errorcode", $error_code );
			update_option( "wp_ib_isresponse", false );
			if ( ( $response->success == true ) && ( $error_code == 0 ) ) {
				$db_update_result = wp_ib_db_add_campaign( $response );
				if ( $db_update_result['success'] == true ) {
					update_option( "wp_ib_isresponse", true );
				}
				$response->message = $db_update_result['message'];
				$response->success = $db_update_result['success'];
			}
			curl_close( $ch );

			/* messages */
			$wp_ib_errorcode = get_option( "wp_ib_errorcode" );
			if ( $wp_ib_errorcode == 0 && isset( $db_update_result['success'] ) ) {
				$response->message = $db_update_result['message'];
			} elseif ( $wp_ib_errorcode == 0 ) {
				$response->message = __( "Error! Invalid secret key.", "wp_invitebox" );
			} else {
				$response->message = __( "Server error!", "wp_invitebox" );
			}

			echo json_encode( $response );
		}
	}
}

/**
 * Add Campaign to DB
 */
if ( ! function_exists( 'wp_ib_db_add_campaign' ) ) {
	function wp_ib_db_add_campaign( $response ) {
		global $wpdb;

		$table_db_name = IB_PLUGIN_DB_TABLE_NAME;
		$name          = $response->campaign;
		$key           = $response->pkey;
		$secret_key    = $response->secret_key;
		$url           = $response->id;

		$result = $wpdb->get_row( "SELECT * FROM {$table_db_name} WHERE `secret_key`='{$secret_key}';" );
		if ( $result == null ) {
			$query  = "INSERT INTO {$table_db_name} (`name`, `pkey`, `secret_key`, `url`, `is_default`) VALUES (%s, %s, %s, %d, %d);";
			$query  = $wpdb->prepare( $query, $name, $key, $secret_key, $url, 0 );
			$result = $wpdb->query( $query );
			if ( false === $result ) {
				$message = __( "You have an error in your SQL!", "wp_invitebox" );
				$success = false;
			} else {
				$message = __( "Campaign successfully added!", "wp_invitebox" );
				$success = true;
			}
		} else {
			$message = __( "You already have this campaign!", "wp_invitebox" );
			$success = false;
		}

		return array(
			'message' => $message,
			'success' => $success
		);
	}
}

/**
 * Remove Campaign
 */
if ( ! function_exists( 'wp_ib_remove' ) ) {
	function wp_ib_remove_campaign() {
		global $wpdb;

		$user_ID       = get_current_user_id();
		$table_db_name = IB_PLUGIN_DB_TABLE_NAME;
		$response      = array(
			'message' => __( 'Failed to remove this campaign!', 'wp_invitebox' ),
			'success' => false
		);

		if ( '' != $user_ID && current_user_can( 'manage_options' ) ) {
			$secret_key = trim( wp_strip_all_tags( $_POST['secret_key'] ) );
			if ( $secret_key ) {
				$query  = "DELETE FROM {$table_db_name} WHERE `secret_key`=%s";
				$query  = $wpdb->prepare( $query, $secret_key );
				$result = $wpdb->query( $query );
				if ( $result > 0 ) {
					$response['message'] = __( "Campaign has been deleted!", "wp_invitebox" );
					$response['success'] = true;
				}
			}
		}

		echo json_encode( $response );
	}
}

/**
 * Set Default Campaign
 */
if ( ! function_exists( 'wp_ib_set_default_campaign' ) ) {
	function wp_ib_set_default_campaign() {
		global $wpdb;

		$table_db_name               = IB_PLUGIN_DB_TABLE_NAME;
		$default_campaign_secret_key = wp_strip_all_tags( $_POST['default_campaign_secret_key'] );

		/* remove default campaign */
		$query  = "UPDATE {$table_db_name} SET `is_default`=%d";
		$query  = $wpdb->prepare( $query, 0 );
		$result = $wpdb->query( $query );

		/* set current campaign as default */
		if ( $default_campaign_secret_key != '' ) {
			$query  = "UPDATE {$table_db_name} SET `is_default`=%d WHERE `secret_key`=%s";
			$query  = $wpdb->prepare( $query, 1, $default_campaign_secret_key );
			$result = $wpdb->query( $query );
		}

		if ( false === $result ) {
			$message = __( "You have an error in your SQL!", "wp_invitebox" );
			$success = false;
		} else {
			$message = __( "Settings successfully saved!", "wp_invitebox" );
			$success = true;
		}

		$response = array( 'message' => $message, 'success' => $success );

		echo json_encode( $response );
	}
}


