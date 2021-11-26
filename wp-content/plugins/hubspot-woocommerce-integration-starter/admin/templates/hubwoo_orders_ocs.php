<?php
	
	global $hubwoo;

	if( isset( $_POST["hubwoo-order-ocs-save"] ) ) {
		
		unset( $_POST["hubwoo-order-ocs-save"] );
		
		if( !isset( $_POST["hubwoo-order-ocs-sync-enable"] ) ) {

			$_POST["hubwoo-order-ocs-sync-enable"] = 'off';
		}

		if( empty( $_POST["hubwoo-order-ocs-since-date"] ) ) {

			$_POST["hubwoo-order-ocs-since-date"] = '';
		}

		if( empty( $_POST["hubwoo-order-ocs-upto-date"] ) ) {

			$_POST["hubwoo-order-ocs-upto-date"] = '';
		}

		if( !isset( $_POST["hubwoo-order-ocs-selected-status"] ) ) {

			$_POST["hubwoo-order-ocs-selected-status"] = 'wc-completed';
		}

		update_option( "hubwoo-order-ocs-sync-enable", $_POST["hubwoo-order-ocs-sync-enable"] );
		update_option( "hubwoo-order-ocs-since-date", $_POST["hubwoo-order-ocs-since-date"] );
		update_option( "hubwoo-order-ocs-upto-date", $_POST["hubwoo-order-ocs-upto-date"] );
		update_option( "hubwoo-order-ocs-selected-status", $_POST["hubwoo-order-ocs-selected-status"] );
	}

	$message = __( 'Congratulations! Your old orders are ready to be exported on HubSpot ', 'hubwoo' );

	if( 'on' == get_option( "hubwoo-order-ocs-sync-enable", "off" ) ) {

		$selected_order_status = get_option( "hubwoo-order-ocs-selected-status", 'wc-completed' );
		$message .= '<a id="hubwoo-run-order-ocs" href="javascript:void(0)" class="button button-primary">'.__( 'Sync Now', 'hubwoo' ).'</a>';
		$message .= '<span class="hubwoo_oauth_span"><label>'.__("Selected Order Status: ","hubwoo").$selected_order_status.'</label></span>';
		$hubwoo::hubwoo_notice( $message, 'update' );
	}
?>
<h2><?php _e("Export your old Orders to HubSpot","hubwoo")?></h2>
<div id="hubwoo-customer-setup-process" style="display:none;">
	<div class="popupwrap">
      <p><?php _e('We are syncing existing old wordpress users or customers over HubSpot. Please do not navigate or reload the page before our confirmation message.', 'hubwoo')?></p>
        <div class="hubwoo-customer-message-area">
        </div>
    </div>
</div>
<form action="" method="post" id="hubwoo-ocs-form">
	<div class="hubwoo-order-ocs">
		<table class="form-table">
			<tbody>
				<tr valign="top">
					<th scope="row" class="titledesc">
						<label for="hubwoo-order-ocs-sync-enable"><?php _e("Enable/Disable","hubwoo"); ?></label>
					</th>
					<td class="forminp forminp-text">
						<?php
							$sync_enable = get_option( "hubwoo-order-ocs-sync-enable", 'off' ); 
							$desc = __('Enable this feature to export old orders of store to HubSpot.','hubwoo');
							echo wc_help_tip( $desc );
						?>
						<input type="checkbox" name="hubwoo-order-ocs-sync-enable" <?php echo ($sync_enable == 'on')?"checked='checked'":""?> >
					</td>
				</tr>
				<tr valign="top">
					<th scope="row" class="titledesc">
						<label for="hubwoo-order-ocs-since-date"><?php _e("Orders from date","hubwoo"); ?></label>
					</th>
					<td class="forminp forminp-text">
					<?php 
					
						$since_date = get_option( "hubwoo-order-ocs-since-date", '' );

						if( empty( $since_date ) ) {

							$since_date = date("d-m-Y");
						}

						$desc = __('Orders since the selected date will be pushed to HubSpot. If left blank, current date will be used.','hubwoo');
						echo wc_help_tip( $desc );
					?>
						<input type="text" style="width: 200px;" class="hubwoo-date-picker" name="hubwoo-order-ocs-since-date" id="hubwoo-order-ocs-since-date" placeholder="<?php _e("Select order date","hubwoo")?>" value="<?php echo $since_date ?>"/>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row" class="titledesc">
						<label for="hubwoo-order-ocs-upto-date"><?php _e("Orders upto date","hubwoo"); ?></label>
					</th>
					<td class="forminp forminp-text">
					<?php 
					
						$upto_date = get_option( "hubwoo-order-ocs-upto-date", '' );

						if( empty( $upto_date ) ) {

							$upto_date = date("d-m-Y");
						}

						$desc = __('Upto which date you want to sync the order, select that date','hubwoo');
						echo wc_help_tip( $desc );
					?>
						<input type="text" style="width: 200px;" class="hubwoo-date-picker" name="hubwoo-order-ocs-upto-date" id="hubwoo-order-ocs-upto-date" placeholder="<?php _e("Select order date","hubwoo")?>" value="<?php echo $upto_date ?>"/>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row" class="titledesc">
						<label for="hubwoo-order-ocs-status"><?php _e("All orders with status","hubwoo"); ?></label>
					</th>
					<td class="forminp forminp-text">
					<?php

						$desc = __('Orders with selected status will be pushed to HubSpot. Default will be all completed orders.','hubwoo');
						echo wc_help_tip( $desc );
					?>
						<select id="hubwoo-order-ocs-status" name="hubwoo-order-ocs-selected-status" style="width: 200px;" >
							<?php

								$selected_order_status = get_option( "hubwoo-order-ocs-selected-status", 'wc-completed' );
								$wc_order_statuses = wc_get_order_statuses();
								
								foreach ( $wc_order_statuses as $key => $single_status ) {

									if( $selected_order_status == $key ) {

										?>
										<option selected value="<?php echo $key ?>"><?php echo $single_status ?></option>
										<?php
									}
									else {

										?>
										<option value="<?php echo $key ?>"><?php echo $single_status ?></option>
										<?php
									}
								}
							?>
						</select>
					</td>
				</tr>
			</tbody>
		</table>
	</div>
	<p class="submit">
		<input name="hubwoo-order-ocs-save" class="button-primary woocommerce-save-button hubwoo-save-button" type="submit" value="<?php _e( 'Save changes', 'hubwoo' ); ?>" />
	</p>
</form>