<?php

/**
 * Fired during plugin activation
 *
 * @link       https://makewebbetter.com/
 * @since      1.0.0
 *
 * @package    hubspot-woocommerce-integration-starter
 * @subpackage hubspot-woocommerce-integration-starter/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    hubspot-woocommerce-integration-starter
 * @subpackage hubspot-woocommerce-integration-starter/includes
 * @author     makewebbetter <webmaster@makewebbetter.com>
 */

class Hubwoo_Activator {

	/**
	 * Create log file in the WC_LOG directory.
	 *
	 * Create a log file in the WooCommerce defined log directory
	 * and use the same for the logging purpose of our plugin.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {

		@fopen( WC_LOG_DIR.'hubwoo-starter-logs.log', 'a' );

		if ( !wp_next_scheduled ( 'hubwoo_starter_cron_schedule' ) ) {

            wp_schedule_event( time(), 'mwb-hubwoo-starter-5min', 'hubwoo_starter_cron_schedule' );
        }

        if ( !wp_next_scheduled ( 'hubwoo_starter_check_realtime_cron' ) ) {

            wp_schedule_event( time(), 'hourly', 'hubwoo_starter_check_realtime_cron' );
        }

        if ( !wp_next_scheduled ( 'hubwoo_starter_check_licence_daily' ) ) {

            wp_schedule_event( time(), 'daily', 'hubwoo_starter_check_licence_daily' );
        }     
	}
}