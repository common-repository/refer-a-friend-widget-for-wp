<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

// Execute wp_ib_campaign_script if order processed
add_action('woocommerce_thankyou', 'wp_ib_campaign_script');


function wp_ib_campaign_script(){

	$wp_ib_select_campaign_woo = get_option('wp_ib_select_campaign_woo');

	if(!empty($wp_ib_select_campaign_woo)){
		foreach($wp_ib_select_campaign_woo as $ib_id){
			echo "<script type='text/javascript'>
					(function() {
						var ibl = document.createElement('script');
						ibl.type = 'text/javascript'; ibl.async = true;
						ibl.src = ('https:' == document.location.protocol ? 'https://' : 'http://') + 'invitebox.com/invitation-camp/{$ib_id}/invitebox-landing.js?hash='+escape(window.location.hash);
						var s = document.getElementsByTagName('script')[0];
						s.parentNode.insertBefore(ibl, s);
					})();
				 </script>";
		}
	}
}