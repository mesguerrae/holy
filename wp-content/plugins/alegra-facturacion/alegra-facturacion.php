<?php
/*
Plugin Name:            Alegra facturacion
Description:            Alegra facturacion

Author:					Nicolas Lopez

Version:				0.0.1

License: GPLv2 or later
*/

//TODO: mejorar logica de cron, colocarla en otro lado, enviar parametro custom en creacion de producto

use includes\Api;

define('ROOT', ABSPATH );
////

add_action( 'plugins_loaded', function(){
    wp_queue()->cron();
});

require_once('includes/Api.php');
require_once ROOT. '/vendor/autoload.php';
require_once('admin/admin.php');
require_once('admin/events/create-products.php');
require_once('admin/events/create-contact.php');
require_once('admin/events/create-invoice.php');
require_once('admin/events/send-payment.php');
require_once('admin/crons/get-products.php');

//wp_queue_install_tables();




if(!function_exists('get_variation_data_from_variation_id')){
    
    function get_variation_data_from_variation_id( $item_id ) {
        
        $_product = new WC_Product_Variation( $item_id );
        
        $variation_data = $_product->get_variation_attributes();
        
        $variation_detail = woocommerce_get_formatted_variation( $variation_data, true );
        
        return $variation_detail;
    }
}

function alegra_log($message, $log) { 

    if(is_array($message)) { 
        $message = json_encode($message); 
    } 

    $file = fopen(plugin_dir_path(__FILE__)."admin/log/".$log."_".date('Y-m-d').".log","a"); 

    fwrite($file, "\n" . date('Y-m-d h:i:s') . " :: " . $message); 

    fclose($file); 

}


