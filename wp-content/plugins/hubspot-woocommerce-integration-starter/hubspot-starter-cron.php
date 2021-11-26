<?php

require_once("../../../wp-load.php");
require_once("../../../wp-config.php");
require_once("../../../wp-blog-header.php");

global $wpdb;
global $woocommerce;

require_once( dirname( __FILE__ ) . '/admin/class-hubwoo-admin.php' );
require_once( dirname( __FILE__ ) . '/includes/class-hubwoo-customer.php' );
require_once( dirname( __FILE__ ) . '/includes/class-hubwoo-contact-properties.php' );
require_once( dirname( __FILE__ ) . '/includes/class-hubwoo-connection-manager.php' );
require_once( dirname( __FILE__ ) . '/includes/class-hubwoo-property-callbacks.php' );
require_once( dirname( __FILE__ ) . '/includes/class-hubwoo-guest-orders-manager.php' );

$hubwoo_plugin_admin = new Hubwoo_Admin( 'hubwoo' , '2.0.1' );
$hubwoo_plugin_admin->hubwoo_starter_cron_schedule();