<?php global $hubwoo; ?>

<?php

if( isset( $_POST["hubwoo_starter_upgrade"] ) ) {

	unset( $_POST["hubwoo_starter_upgrade"] );

    delete_option( "hubwoo_starter_valid_license" );
    delete_option( "hubwoo_starter_license_key" );
}
elseif( isset( $_POST["hubwoo_enrollment_settings"] ) ) {

	unset( $_POST["hubwoo_enrollment_settings"] );
	if ( empty( $_POST["hubwoo_list_enrollment_order_actions"] ) ) {
		$_POST["hubwoo_list_enrollment_order_actions"] = array();	
	}
	if ( empty( $_POST["hubwoo_enrolled_order_lists"] ) ) {
		$_POST["hubwoo_enrolled_order_lists"] = array();	
	}
	if ( empty( $_POST["hubwoo_list_enrollment_customer_actions"] ) ) {
		$_POST["hubwoo_list_enrollment_customer_actions"] = array();	
	}
	if ( empty( $_POST["hubwoo_enrolled_customer_lists"] ) ) {
		$_POST["hubwoo_enrolled_customer_lists"] = array();	
	}
	foreach ( $_POST as $key => $value ) {
		update_option( $key, $value );
	}
}
?>

<div class="hubwoo-settings-header hubwoo-common-header">
	<h2><?php _e("Advanced Settings","hubwoo") ?></h2>
</div>
<div class="hubwoo-settings-container">
	<div class="hubwoo-advanced-settings">
		<form action="" method="post" id="hubwoopro">
			<table class="form-table">
				<tbody>
					<tr valign="top">
						<th scope="row" class="titledesc">
							<label for="hubwoo-upgrade-license"><?php _e("Update License","hubwoo"); ?></label>
						</th>
						<td class="forminp forminp-text">
							<?php 
								$desc = __('You can upgrade or change your license from here. Upgrading to newer one will remove the old license.','hubwoo');
								echo wc_help_tip( $desc );
							?>
							<span><?php _e("Update your license key.","hubwoo")?></span>
		                    <input type="submit" class="button button-primary" id="hubwoo_starter_upgrade" name="hubwoo_starter_upgrade" value="<?php _e("Click Here","hubwoo")?>">
						</td>
					</tr>
					<?php if( $hubwoo->is_setup_completed() ): ?>
						<tr valign="top">
							<th scope="row" class="titledesc">
								<label><?php _e("Update Options","hubwoo"); ?></label>
							</th>
							<td class="forminp forminp-text">
								<?php
									$desc = __('If your store changes are not getting updated over HubSpot, then click here to update HubSpot properties now.','hubwoo');
									echo wc_help_tip( $desc );
								?>
								<span><?php _e('Click to update HubSpot properties.','hubwoo') ?></span>
		                    	<a href="javascript:void(0)" class="button-primary" id="hubwoo_starter_up_date"><?php _e("Click Here","hubwoo")?></a>
		                	</td>
						</tr>
					<?php endif; ?>
				</tbody>
			</table>
		</form> 

		<form action="" method="post" id="hubwoo-enroll">

			<h2><?php _e( "Order Activity Static List Enrollment Settings", "hubwoo" );?></h2>
			<table class="form-table hubwoo-add-more-order-list-actions">
				<tr valign="top">
					<th scope="row" class="titledesc">
						<label><?php _e( "Action", "hubwoo" ); ?></label>
					</th>
					<th scope="row" class="titledesc">
						<label><?php _e( "Enroll in Static List", "hubwoo" ); ?></label>
					</th>
					<th></th>
				</tr>
				<?php echo $hubwoo::hubwoo_get_order_lists_and_actions() ?>
			</table>
			<div class="hubwoo-add-more-list-actions" style="text-align: center;">
				<a class="hubwoo-add-order-list-action" href="javascript:void(0)"><?php _e("Add", "hubwoo") ?></a>
			</div>

			<h2><?php _e("Customer Activity Static List Enrollment Settings", "hubwoo");?></h2>
			<table class="form-table hubwoo-add-more-customer-list-actions">
				<tr valign="top">
					<th scope="row" class="titledesc">
						<label><?php _e( "Action", "hubwoo" ); ?></label>
					</th>
					<th scope="row" class="titledesc">
						<label><?php _e( "Enroll in Static List", "hubwoo" ); ?></label>
					</th>
					<th></th>
				</tr>
				<?php echo $hubwoo::hubwoo_get_customer_lists_and_actions() ?>
			</table>
			<div class="hubwoo-add-more-list-actions" style="text-align: center;">
				<a class="hubwoo-add-customer-list-action" href="javascript:void(0)"><?php _e("Add", "hubwoo") ?></a>
			</div>

			<p class="submit">
				<input type="submit" class="button button-primary" name="hubwoo_enrollment_settings" value="<?php echo __("Save Settings","hubwoo") ?>">
			</p>
		</form>
	</div>
</div>