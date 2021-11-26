<?php

class GMWCP_Cron {
	
	public function __construct () {

		add_action( 'init', array( $this, 'GMWCP_default' ) );
		
	}

	public function GMWCP_default(){
		$defalarr = array(
			'gmwcp_shop_display_location' => 'before',
			'gmwcp_single_display_location' => 'before',
			'gmpcp_background_color' => '#fff',
			'gmpcp_item_background_color' => '#000',
			
		);
		foreach ($defalarr as $keya => $valuea) {
			if (get_option( $keya )=='') {
				update_option( $keya, sanitize_text_field($valuea) );
			}
			
		}
		
	}
}

?>