<?php 
namespace admin\events;
use \WP_Queue\Job;
use includes\Api;


class UpdateProductStockOnAlegra extends \WP_Queue\Job
{
    protected $order_id;
    protected $api;

    public function __construct( $order_id )
    {
        $this->order_id = $order_id;
        $this->api = new Api();
    }
    public function handle() {

        if (!$this->order_id) {
            return;
        }

        $order = wc_get_order($this->order_id);

        $items = $order->get_items();

        foreach ( $items as $item ) {

            var_dump($item);exit;
            $product_name = $item->get_name();
            
            $product_id = $item->get_product_id();
            
            $product_variation_id = $item->get_variation_id();

            $alegra_id = get_post_meta( $product_id , 'alegra_item_id', true );

            if(!$alegra_id){

                wp_queue()->push( new CreateProductOnAlegra( $product_id ) );

                continue;
            }

            $uri = 'items/'.$alegra_id;

            $productData = array();

            $productData['inventory'] = array(
                'unit'              => 'piece',
                'unitCost'          =>  1,
                'minQuantity'       =>1
                'maxQuantity'       =>  
            );

            $productJson = json_encode($productData);
            #se crea el producto en alegra  
            $result = $this->api->put($uri, $productData);
            echo $result;exit;
            $decodedResult =  json_decode($result);
        
        }
    }
}