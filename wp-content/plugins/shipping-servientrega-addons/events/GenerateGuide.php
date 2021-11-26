<?php 

use \WP_Queue\Job;




class GenerateGuide extends \WP_Queue\Job
{
    protected $order_id;

    public function __construct( $order_id )
    {
        $this->order_id = $order_id;
    }
    public function handle() {

        if (!$this->order_id) {
            return;
        }

        $order_id = $this->order_id;
        
        $order = wc_get_order($order_id);

        $response = '';
        
        if(!Shipping_Servientrega_WC::generate_guide($order_id,'processing','processing',$order)){

            $response .= 'ORDEN '.$post_id.' FALLO<br>';

        }else{

            $response .= 'ORDEN '.$post_id.' SE GENERO<br>';
        }
        
    }
}   