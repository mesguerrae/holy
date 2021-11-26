<?php 

/**
 * 
 */

namespace admin\crons;

use includes\Api;

class AlegraCron 
{
	
	function __construct()
	{
		
		
		add_filter('cron_schedules',array($this,'add_cron_time_options'));
		add_action( 'update_product_stock_alegra', array ( $this, 'update_product_stock_alegra_em') );
		$this->register_get_products_alegra_cron();
		$this->api = new Api();

	}

	public function register_get_products_alegra_cron() {

		if( !wp_next_scheduled( 'update_product_stock_alegra' ) ) {

			wp_schedule_event( time(), '2min', 'update_product_stock_alegra' );
		}
	}
	

	public function add_cron_time_options($schedules){
	    
		if(!isset($schedules["2min"])){
    
			$schedules["2min"] = array(
				'interval' => 2*60,
				'display' => __('Once every 1 minute'));
		}
		
		if(!isset($schedules["30min"])){
		
			$schedules["30min"] = array(
				'interval' => 30*60,
				'display' => __('Once every 30 minutes'));
		
		}
			    
	    return $schedules;
	}


	public function update_product_stock_alegra_em(){

		global $wpdb;

		$currentStart = get_option('alegra_update_stock_start', true);

		if (!$currentStart) {
			
			add_option('alegra_update_stock_start', 0);

			$currentStart = 0;
		}

		$data = array();

		$uri =  'items?start='.$currentStart;

		$result = $this->api->get($uri, $data);

		if (!empty($result)) {
			
			$alegraItems = json_decode($result);

			$updatedItems = array();
			
			alegra_log('----------UPDATE PRODUCT COUNT'.count($alegraItems).' START: '.$currentStart.'------------','products');

			foreach ($alegraItems as  $item) {
				
				$meta = $wpdb->get_results( "select post_id, meta_key from $wpdb->postmeta where meta_value = '$item->id' and meta_key = 'alegra_item_id'", ARRAY_A );

				if (is_array($meta) && !empty($meta) && isset($meta[0])) {

					$productId = $meta[0]['post_id'];
				}

				if(in_array($productId, $updatedItems)){
					continue;
				}

				$stock_status = 'instock';

				$alegraStock = $item->inventory->availableQuantity;
				
				$currentStock = get_post_meta($productId, '_stock', true);
				
				alegra_log('----------UPDATE PRODUCT '.$productId.'------------','products');

				alegra_log('ALEGRA STOCK:'. $alegraStock,'products');

				alegra_log('CURRENT STOCK:'. $currentStock,'products');

				if ($alegraStock <= 0 ) {

					alegra_log('ENTRE 1','products');
					
					$stock_status = 'outofstock';

					update_post_meta( $productId, '_manage_stock', 'yes' );

					update_post_meta($productId, '_stock', $alegraStock);

					update_post_meta( $productId, '_stock_status', wc_clean( $stock_status ) );

				}else if ($currentStock < $alegraStock) {

					alegra_log('ENTRE 2','products');
					
					$currentOnHoldProduct = $wpdb->get_row( "
							SELECT oim_product.meta_value as product_id, sum(oim_qty.meta_value) as qty, `order`.post_status FROM wp_woocommerce_order_items oi 
							join `wp_woocommerce_order_itemmeta` oim_qty on (oi.order_item_id = oim_qty. order_item_id and oim_qty.meta_key = '_qty')
							join `wp_woocommerce_order_itemmeta` oim_product on (oi.order_item_id = oim_product.order_item_id and oim_product.meta_key = '_product_id')
							join `wp_posts` as `order` on (oi.order_id = `order`.ID) 
							and `order`.post_status like '%on-hold' 
							where oim_product.meta_value = ".$productId."
							group by 1
						", ARRAY_A );

					$stock_status = 'instock';

					if (isset($currentOnHoldProduct['qty'])) {

						alegra_log('currentOnHoldProduct: '. $currentOnHoldProduct['qty'],'products');
						
						$stock = $alegraStock - $currentOnHoldProduct['qty'];

						if($stock == 0) {

							$stock_status = 'outofstock';

							alegra_log('updateProductStock('.$productId.'): zero ','products');
						}

						alegra_log('DIFERENCIA: '.  $stock,'products');
						
					
					}else{

						alegra_log('no existe registro','products');

						$stock = $alegraStock;
					}

					update_post_meta( $productId, '_manage_stock', 'yes' );

					update_post_meta($productId, '_stock', $stock);

					update_post_meta( $productId, '_stock_status', wc_clean( $stock_status ) );


				}else{

					alegra_log('ENTRE 3','products');

					$stock_status = 'instock';

					update_post_meta( $productId, '_manage_stock', 'yes' );

					update_post_meta($productId, '_stock', $alegraStock);

					update_post_meta( $productId, '_stock_status', wc_clean( $stock_status ) );
				}

				$updatedItems[] = $productId;

				wc_delete_product_transients( $productId ); 	
				
				$parent_id = wp_get_post_parent_id($productId);

				if($parent_id != 0 && $parent_id !== false) {

					$total_Stock = $this->get_stock_variations_from_product($parent_id);

					if($total_Stock > 0) {
						
						update_post_meta( $parent_id, '_stock_status', wc_clean( 'instock' ) );

					} else {
						update_post_meta( $parent_id, '_stock_status', wc_clean( 'outofstock' ) );
					}

					
				}

			}
			if (count($alegraItems) < 30) {
				
				update_option('alegra_update_stock_start', 0);
			
			}else{

				update_option('alegra_update_stock_start', ($currentStart + count($alegraItems)-1));
			}
			
		}
		
	}

	public function get_stock_variations_from_product($productId){
		$product = wc_get_product($productId);
		$variations = $product->get_available_variations();
		$stock = 0;
		foreach($variations as $variation){
			$variation_id = $variation['variation_id'];
			$variation_obj = wc_get_product($variation_id);
			$stock += $variation_obj->get_stock_quantity();
		}
		return $stock;
	}
}


$cron = new AlegraCron();

$cron->register_get_products_alegra_cron();




