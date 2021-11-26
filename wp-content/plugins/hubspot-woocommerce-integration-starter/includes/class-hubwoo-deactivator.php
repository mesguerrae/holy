<?php

/**
 * Fired during plugin deactivation
 *
 * @link       https://makewebbetter.com/
 * @since      1.0.0
 *
 * @package    hubspot-woocommerce-integration-starter
 * @subpackage hubspot-woocommerce-integration-starter/includes
 */

/**
 * Fired during plugin deactivation.
 *
 * This class defines all code necessary to run during the plugin's deactivation.
 *
 * @since      1.0.0
 * @package    hubspot-woocommerce-integration-starter
 * @subpackage hubspot-woocommerce-integration-starter/includes
 * @author     makewebbetter <webmaster@makewebbetter.com>
 */
class Hubwoo_Deactivator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function deactivate() {
		
		wp_clear_scheduled_hook( 'hubwoo_starter_cron_schedule' );
		wp_clear_scheduled_hook( 'hubwoo_starter_check_realtime_cron' );
		wp_clear_scheduled_hook( 'hubwoo_starter_check_licence_daily' );
		unlink( WC_LOG_DIR.'hubwoo-starter-logs.log' );
	}	
}