<?php

global $hubwoo;
// check for the settings save request with verify the 'hubwoo-settings' nonce.
if( isset( $_REQUEST['hubwoo_ocs_settings'] ) && ! empty( $_POST ) ) {

	// update the settings.
	woocommerce_update_options( Hubwoo_Admin::hubwoo_customers_sync_settings() );
}

// check if the api is entered.
$hapikey = HUBWOO_STARTER_CLIENT_ID;
$hseckey = HUBWOO_STARTER_SECRET_ID;

if( $hapikey && $hseckey ) {

	// if its a valid, api key and all, set lets congratulate them.
	if( $hubwoo->is_valid_client_ids_stored() ) {

		// add thickbox support for interactive setup.
		add_thickbox();
	?>
		<div id="hubwoo-customer-setup-process" style="display:none;">
			<div class="popupwrap">
	          <p><?php _e('We are syncing existing old wordpress users or customers over HubSpot. Please do not navigate or reload the page before our confirmation message.', 'hubwoo')?></p>
		        <div class="hubwoo-customer-message-area">
		        </div>
		    </div>
	    </div>
	    <?php
	    	// add Run Setup button 
	    $message = __( 'Congratulations! Your old users are ready to be updated over HubSpot. ', 'hubwoo' );

	    $selected_role = get_option( "hubwoo_starter_customers_role_settings", "administrator" );

		if( 'yes' == get_option("hubwoo_starter_customers_settings_enable","no") && !empty( $selected_role ) ) {
			
			$message .= '<a id="hubwoo-customers-run-setup" href="javascript:void(0)" class="button button-primary">'.__( 'Sync Now', 'hubwoo' ).'</a>';
			$message .= '<span class="hubwoo_oauth_span"><label>'.__("Selected Role: ","hubwoo").$selected_role.'</label></span>';
			$hubwoo->hubwoo_notice( $message, 'update' );
		}
	}
}
else {

	$message = __( 'Please provide your HubSpot key to get started.', 'hubwoo' );
	$hubwoo->hubwoo_notice( $message, 'update-nag' );
}

?>
<form action="" method="post" id="hubwoo-ocs-form">
	<?php 
		woocommerce_admin_fields( Hubwoo_Admin::hubwoo_customers_sync_settings() );
	?>
	<p class="submit">
		<input type="submit" class="button button-primary" name="hubwoo_ocs_settings" value="<?php echo __("Save Changes","hubwoo") ?>">
	</p>
</form>