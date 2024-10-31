<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Wp_Ib_WC_Settings_Tab {
	/**
	 * Bootstraps the class and hooks required actions & filters.
	 *
	 */
	public static function init() {
		add_filter( 'woocommerce_settings_tabs_array', __CLASS__ . '::wp_ib_add_settings_tab', 50 );
		add_action( 'woocommerce_settings_tabs_wp_ib_settings_tab', __CLASS__ . '::wp_ib_settings_tab' );
		add_action( 'woocommerce_update_options_wp_ib_settings_tab', __CLASS__ . '::wp_ib_update_settings' );
	}


	/**
	 * Add a new settings tab to the WooCommerce settings tabs array.
	 *
	 * @param array $settings_tabs Array of WooCommerce setting tabs & their labels, excluding the Subscription tab.
	 * @return array $settings_tabs Array of WooCommerce setting tabs & their labels, including the Subscription tab.
	 */
	public static function wp_ib_add_settings_tab( $settings_tabs ) {
		$settings_tabs['wp_ib_settings_tab'] = __( 'Invitebox', 'wp_invitebox' );
		return $settings_tabs;
	}
	/**
	 * Uses the WooCommerce admin fields API to output settings via the @see woocommerce_admin_fields() function.
	 *
	 * @uses woocommerce_admin_fields()
	 * @uses self::wp_ib_get_settings()
	 */
	public static function wp_ib_settings_tab() {
		woocommerce_admin_fields( self::wp_ib_get_settings() );
	}
	/**
	 * Uses the WooCommerce options API to save settings via the @see woocommerce_update_options() function.
	 *
	 * @uses woocommerce_update_options()
	 * @uses self::wp_ib_get_settings()
	 */
	public static function wp_ib_update_settings() {
		woocommerce_update_options( self::wp_ib_get_settings() );
	}
	/**
	 * Get all the settings for this plugin for @see woocommerce_admin_fields() function.
	 *
	 * @return array Array of settings for @see woocommerce_admin_fields() function.
	 */
	public static function wp_ib_get_settings() {

		$all_campaigns = wp_ib_get_all_campaigns();
		$wp_ib_select_campaign_woo = array();

		if(!empty($all_campaigns)){
			foreach ($all_campaigns as $campaign){
				$wp_ib_select_campaign_woo[$campaign->url] = $campaign->name;
			}
		}
		$settings = array(
			'wp_ib_section_title' => array(
				'name'     => __( 'Conversions', 'wp_invitebox' ),
				'type'     => 'title',
				'desc'     => __( 'Select campaigns to track conversions. Conversion scripts will be included in WooCommerce \'thank you\' page.', 'wp_invitebox' ),
				'id'       => 'wp_ib_section_title'
			),
			'wp_ib_select_campaign_woo' => array(
				'name' => 'Track conversions',
				'type' => 'multiselect',
				'options' => $wp_ib_select_campaign_woo,
				'desc' => '',
				'id'   => 'wp_ib_select_campaign_woo'
			),
			'section_end' => array(
				'type' => 'sectionend',
				'id' => 'wp_ib_wc_settings_tab_section_end'
			)
		);
		return apply_filters( 'wp_ib_wc_settings_tab_settings', $settings );
	}
}
Wp_Ib_WC_Settings_Tab::init();