<?php

	global $hubwoo;

	if( isset( $_POST['hubwoo_save_gensettings'] ) ) {
		unset( $_POST['hubwoo_save_gensttings'] );
		if( !isset( $_POST[ 'hubwoo-selected-order-status' ] ) ) {
			$_POST['hubwoo-selected-order-status'] =  array();
		}
		if( !isset( $_POST[ 'hubwoo-selected-user-roles' ] ) ) {
			$_POST['hubwoo-selected-user-roles'] =  array();
		}
		foreach( $_POST as $key => $value ) {
			update_option( $key, $value );
		}
		$message = __("Settings saved","hubwoo");
		$hubwoo->hubwoo_notice( $message, 'success' );
	}
	if ( isset( $_POST['hubwoo_save_pluginsettings'] ) ) {
		woocommerce_update_options( Hubwoo_Admin::hubwoo_more_general_settings() );
		$message = __("Settings saved","hubwoo");
		$hubwoo->hubwoo_notice( $message, 'success' );
	}
	if ( isset( $_POST["hubwoo_save_optinsettings"] ) ) {
		woocommerce_update_options( Hubwoo_Admin::hubwoo_checkout_optin_settings() );
		$message = __("Settings saved","hubwoo");
		$hubwoo->hubwoo_notice( $message, 'success' );
	}
?>

<div class="hubwoo-settings-header hubwoo-common-header">
	<h2><?php _e("General Settings","hubwoo") ?></h2>
</div>
<div class="hubwoo-settings-container">

	<div class="hubwoo-general-settings">
		<form action="" method="post">
			<h2><?php _e("Orders and User Roles Settings for Real-time sync on HubSpot","hubwoo")?></h2>
			<div class="hubwoo-order-status">
				<label for="hubwoo-selected-order-status"><?php _e("Sync orders with status","hubwoo"); ?></label>
				<?php
					$desc = __('The orders with selected statuses will be synced to HubSpot. Default will be all order statuses.','hubwoo');
					echo wc_help_tip( $desc );
				?>
				<select multiple="multiple" id="hubwoo-order-statuses" name="hubwoo-selected-order-status[]" data-placeholder="<?php esc_attr_e( 'Select Order Statuses', 'hubwoo' ); ?>">
				<?php

					$selected_order_statuses = get_option( "hubwoo-selected-order-status", array() );

					$wc_order_statuses = wc_get_order_statuses();

					foreach ( $selected_order_statuses as $single_status ) {

						if( array_key_exists( $single_status, $wc_order_statuses ) ) {

							echo '<option value="'.$single_status. '" selected="selected">'.$wc_order_statuses[$single_status].'</option>';
						}
					}
				?>
				</select>
			</div>
			<div class="hubwoo-user-roles">
				<label for="hubwoo-selected-user-roles"><?php _e("Sync users with role","hubwoo"); ?></label>
				<?php
					$desc = __('The users with selected roles will be synced on HubSpot. Default will be all user roles.','hubwoo');
					echo wc_help_tip( $desc );
				?>
				<select multiple="multiple" id="hubwoo-selected-user-roles" name="hubwoo-selected-user-roles[]" data-placeholder="<?php esc_attr_e( 'Select User Roles', 'hubwoo' ); ?>">
				<?php

					$selected_user_roles = get_option( "hubwoo-selected-user-roles", array() );

					$wc_user_roles = $hubwoo->hubwoo_get_user_roles();

					foreach ( $selected_user_roles as $single_role ) {

						if( array_key_exists( $single_role, $wc_user_roles ) ) {

							echo '<option value="'.$single_role. '" selected="selected">'.$wc_user_roles[$single_role].'</option>';
						}
					}
				?>
				</select>
			</div>
			<p class="submit">
				<input type="submit" class="button button-primary" name="hubwoo_save_gensettings" value="<?php _e("Save settings","hubwoo") ?>">
			</p>
		</form>
	</div>

	<div class="hubwoo-general-settings">
		<form action="" method="post">
			<?php woocommerce_admin_fields( Hubwoo_Admin::hubwoo_more_general_settings() ); ?>
			<p class="submit">
				<input type="submit" class="button button-primary" name="hubwoo_save_pluginsettings" value="<?php _e("Save settings","hubwoo") ?>">
			</p>
		</form>
	</div>

	<div class="hubwoo-general-settings">
		<form action="" method="post">
			<?php woocommerce_admin_fields( Hubwoo_Admin::hubwoo_checkout_optin_settings() ); ?>
			<p class="submit">
				<input type="submit" class="button button-primary" name="hubwoo_save_optinsettings" value="<?php _e("Save settings","hubwoo") ?>">
			</p>
		</form>
	</div>
</div> 