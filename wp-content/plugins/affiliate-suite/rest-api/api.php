<?php
/**
 * Rest Api Endpoints
 */

// Get all referrals
add_action( 'rest_api_init', function () {
    register_rest_route( 'affiliate-suite/v1', '/referrals', array(
        'methods' => 'GET',
        'callback' => 'as_api_get_referrals',
        'permission_callback' => '__return_true'
    ) );
} );

function as_api_get_referrals($request) {
    global $wpdb;
    $referrals_table = $wpdb->prefix . "affiliatesuite_referrals";
    $sql = "SELECT * FROM $referrals_table ORDER BY date DESC";

    $results = $wpdb->get_results($sql);
    return $results;
}

// Get an affiliate referrals
add_action( 'rest_api_init', function () {
    register_rest_route( 'affiliate-suite/v1', '/referrals/(?P<id>\d+)', array(
        'methods' => 'GET',
        'callback' => 'as_api_get_affiliate_referrals',
        'permission_callback' => '__return_true'
    ) );
} );

function as_api_get_affiliate_referrals($request) {
    global $wpdb;
    $referrals_table = $wpdb->prefix . "affiliatesuite_referrals";
    $sql = "SELECT * FROM $referrals_table WHERE affiliate_id='".$request['id']."' ORDER BY date DESC";

    $results = $wpdb->get_results($sql);
    return $results;
}