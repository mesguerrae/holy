<?php 

/**
 * 
 */
namespace public;

use includes\Api;
use admin\events\CreateProductOnAlegra;

class FrontEvents 
{
	protected $api;
	
	function __construct()
	{

		$this->api = new Api();

		add_action( 'woocommerce_thankyou', array($this, 'thankyou_test'), 4 );


	}

	public function thankyou_test($order_id){

		/*if (!$order_id) {
            return;
        }

        $order = wc_get_order($order_id);*/
        $uri =  'items';

        $data = array();

        $result = $this->api->get($uri, $data);

        




	}

	
}


$loadBulks =  new loadBulks();