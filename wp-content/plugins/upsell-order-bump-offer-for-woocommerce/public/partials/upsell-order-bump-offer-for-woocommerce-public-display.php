<?php
/**
 * Provide a public-facing view for the plugin.
 *
 * This file is used to markup the public-facing aspects of the plugin.
 *
 * @link       https://makewebbetter.com/?utm_source=MWB-orderbump-backend&utm_medium=MWB-Site-backend&utm_campaign=MWB-backend
 * @since      1.0.0
 *
 * @package    Upsell_Order_Bump_Offer_For_Woocommerce
 * @subpackage Upsell_Order_Bump_Offer_For_Woocommerce/public/partials
 */

$mwb_ubo_global_options = get_option( 'mwb_ubo_global_options', mwb_ubo_lite_default_global_options() );

// Check enability of the plugin at settings page. By default plugin will be enabled.
$mwb_bump_enable_plugin = ! empty( $mwb_ubo_global_options['mwb_bump_enable_plugin'] ) ? $mwb_ubo_global_options['mwb_bump_enable_plugin'] : 'on';

// Get all saved bumps.
$mwb_ubo_bump_callback          = Upsell_Order_Bump_Offer_For_Woocommerce::$mwb_upsell_bump_list_callback_function;
$mwb_ubo_offer_array_collection = Upsell_Order_Bump_Offer_For_Woocommerce::$mwb_ubo_bump_callback();

if ( 'on' !== $mwb_bump_enable_plugin || empty( $mwb_ubo_offer_array_collection ) ) {
	return;
}

// Token to fetch order bumps again.
$fetch_order_bumps = true;

$encountered_bump_ids_array        = array();
$encountered_bump_tarket_key_array = array();

// WIW-CC : First check from session and perform validations.
if ( null !== WC()->session->get( 'encountered_bump_array' ) && is_array( WC()->session->get( 'encountered_bump_array' ) ) ) {

	$fetch_order_bumps = false;

	$encountered_bump_ids_array        = WC()->session->get( 'encountered_bump_array' );
	$encountered_bump_tarket_key_array = WC()->session->get( 'encountered_bump_tarket_key_array' );

	// For Each Order Bump Ids array from session.
	foreach ( $encountered_bump_ids_array as $key => $value ) {

		$encountered_order_bump_id = $value;

		$session_validations = mwb_ubo_order_bump_session_validations( $encountered_order_bump_id, $mwb_ubo_offer_array_collection, $mwb_ubo_global_options );

		// When session validations fail.
		if ( false === $session_validations ) {

			$fetch_order_bumps = true;

			// In case offer is already added then remove the offer product.
			if ( null !== WC()->session->get( "bump_offer_status_index_$key" ) ) {

				WC()->cart->remove_cart_item( WC()->session->get( "bump_offer_status_index_$key" ) );

				WC()->session->__unset( "bump_offer_status_index_$key" );
			}

			// Destroy encountered bump array session.
			WC()->session->__unset( 'encountered_bump_array' );

			break;
		}
	}
}

if ( $fetch_order_bumps && method_exists( 'Upsell_Order_Bump_Offer_For_Woocommerce_Public', 'fetch_order_bump_from_collection' ) ) {

	$encountered_bump_result = Upsell_Order_Bump_Offer_For_Woocommerce_Public::fetch_order_bump_from_collection( $mwb_ubo_offer_array_collection, $mwb_ubo_global_options );

	// WIW-CC : Will return array of Order Bumps Ids.
	$encountered_bump_ids_array = ! empty( $encountered_bump_result['encountered_bump_array'] ) ? $encountered_bump_result['encountered_bump_array'] : array();

	// WIW-CC : Will return array of Order Bumps Target Ids.
	$encountered_bump_tarket_key_array = ! empty( $encountered_bump_result['mwb_upsell_bump_target_key'] ) ? $encountered_bump_result['mwb_upsell_bump_target_key'] : array();
}

// When empty or not array return.
if ( empty( $encountered_bump_ids_array ) || ! is_array( $encountered_bump_ids_array ) ) {

	return;
}

// Set Session whenever Order Bump Ids are fetched from collection.
if ( null === WC()->session->get( 'encountered_bump_array' ) ) {

	WC()->session->set( 'encountered_bump_array', $encountered_bump_ids_array );
	WC()->session->set( 'encountered_bump_tarket_key_array', $encountered_bump_tarket_key_array );

	// Add Order Bump Offer View Count for the respective Order Bump.
	foreach ( $encountered_bump_ids_array as $order_bump_id ) {

		$sales_by_bump = new Mwb_Upsell_Order_Bump_Report_Sales_By_Bump( $order_bump_id );
		$sales_by_bump->add_offer_view_count();
	}
}

/**===========================================
		Order bump html section start
===========================================*/
?><div class="wrapup_order_bump">
<?php

// For Each Order Bump Ids array.
foreach ( $encountered_bump_ids_array as $key => $value ) {

	$encountered_order_bump_id = $value;

	if ( ! empty( $encountered_bump_tarket_key_array ) ) {

		$encountered_respective_target_key = ! empty( $encountered_bump_tarket_key_array[ $key ] ) ? $encountered_bump_tarket_key_array[ $key ] : '';
	}

	/**
	 * Passing bump id as key ( 2nd param ) also, so that the index is set according to bump id.
	 * So that right session index is set and right order bumps remain checked.
	 */
	mwb_ubo_analyse_and_display_order_bump( $encountered_order_bump_id, $encountered_order_bump_id, $encountered_respective_target_key );
}

?>
</div>
<?php
/**===========================================
		Order bump html section ends
===========================================*/
