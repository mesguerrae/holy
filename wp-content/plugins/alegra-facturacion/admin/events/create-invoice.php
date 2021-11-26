<?php
namespace admin\events;
use \WP_Queue\Job;
use includes\Api;
use admin\events\CreateProductOnAlegra;
use admin\events\CreateContact;
use admin\events\SendPayment;

class CreateInvoice extends \WP_Queue\Job
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
        
        

	    $tz = date_default_timezone_get();

        date_default_timezone_set( 'America/Bogota' );

        $order_id = $this->order_id;
        
        $order = wc_get_order($order_id);

        $items = $order->get_items();

        alegra_log('----------CREAR FACTURA : '.$order_id.'------------','invoices');


        if($this->isGiftCardOrder($items)) {
            echo 1;exit;

            alegra_log('----------ORDEN GIFT CARD : '.$order_id.'------------','invoices');

            return;
        }

        if (get_post_meta($order_id, 'alegra_invoice_id', true) != '') {

            return;
        }

        //equivalencia de envio en woocommerce y alegra
        $shippingArray = [

            61  => 1,
            33  => 2,
            36  => 3,
            59  => 4,
            35  => 5,
            60  => 6,
            56  => 7,
            69  => 8,
            71  => 9
        ];

        $withTax = false;

        $numOrder = get_option('alegra_order_inv');

        $currentNumOrder = get_option('current_alegra_order_inv');


        if ($currentNumOrder == '') {

            add_option('current_alegra_order_inv', 1);

        }else if($currentNumOrder < $numOrder){

            $currentNumOrder++;

            update_option('current_alegra_order_inv', $currentNumOrder);

        }else if($currentNumOrder == $numOrder){

            $withTax = true;

            update_option('current_alegra_order_inv', 1);

        }

        $user_id = get_post_meta($order_id, '_customer_user', true);

        $currentAlegraId = get_user_meta( $user_id, 'alegra_contact_id', true );
	    alegra_log('----EXISTE CONTATO : '.$currentAlegraId.'--------','invoices');
        if($currentAlegraId == ''){

            $contact = new CreateContact( $user_id );

            $currentAlegraId = $contact->handle();
            alegra_log('-----CONTACTO CREADO ALEGRA: '.$currentAlegraId.'------','invoices');

        }



        $shipping_method = reset($order->get_items( 'shipping' ));

        $shipping_method_id = $shipping_method['method_id'];

        $shippingData = $shipping_method->get_data();

        $shippingTotal = $shippingData['total'];

        $shippingId = $shippingData['instance_id'];

        $shippingName = $shippingData['method_title'];

        $status = $order->get_status();

        $invoiceItems = array();

        $invoiceData['date']    = date('Y-m-d');

        $invoiceData['dueDate'] =  date('Y-m-d', strtotime("+10 day"));

        $invoiceData['status']   = 'open';

        $invoiceData['client']  = $currentAlegraId;

        

        foreach ($items as $key => $orderItem) {

            if($this->isGiftCardItem($orderItem))
                continue;

            $product = $orderItem->get_product();
            
            foreach($orderItem['item_meta'] as $key => $val){
                
                $possibleVariatinId = $this->get_product_variation_id($key, $val, $orderItem->get_product_id());

                if($possibleVariatinId != NULL)
                    break;

            }

            if($orderItem->is_type('variable') || $orderItem->get_variation_id() != 0){

                $alegraItemId = get_post_meta($orderItem->get_variation_id(),'alegra_item_id', true);

            }else if(!is_null($possibleVariatinId)){                

                $alegraItemId = get_post_meta($possibleVariatinId,'alegra_item_id', true);
            }else{

                $alegraItemId = get_post_meta($orderItem->get_product_id(),'alegra_item_id', true);

            }

            if($alegraItemId == ''){

                $product = new CreateProductOnAlegra( $orderItem->get_product_id() );

                $product->handle();

                if($variationId == 0){

                    $alegraItemId = get_post_meta($orderItem->get_product_id(),'alegra_item_id', true);

                }else{

                    $alegraItemId = get_post_meta($variationId,'alegra_item_id', true);

                }

            }

            $discountPercentage = 0;
		
	    $itemPrice = $orderItem->get_total();

            if($orderItem->get_subtotal() > $orderItem->get_total()){

                $discount = (($orderItem->get_total() * 100) / $orderItem->get_subtotal()) -100;

                $totalDiscount = $discount * (-1);

                $discountPercentage = (float) str_replace(',', '.', $totalDiscount );

		$itemPrice = $orderItem->get_subtotal();
            }

            $tax = array();

            if ($withTax) {

                $tax = array(
                    'id' => 3
                );
            }

            $invoiceItems[] = array(
                'id'            => $alegraItemId,
                'description'   => $orderItem->get_name(),
                'price'         => ($itemPrice / $orderItem->get_quantity()),
                'quantity'      => $orderItem->get_quantity(),
                'discount'      => $discountPercentage,
                'tax'           => array($tax)
            );


        }

        //agregar shipping item
        $invoiceItems[] = array(
            'id'            => $shippingArray[$shippingId],
            'description'   => $shippingName,
            'price'         => $shippingTotal,
            'quantity'      => 1,
            'discount'      => 0,
            'tax'           => array($tax)
        );

        if ($withTax) {

            $this->calculateItemTax($invoiceItems, $order->get_total());

        }

        $invoiceData['items']  = $invoiceItems;
        alegra_log('----------send data------------','invoices');
        alegra_log($invoiceData, 'invoices');
        $uri = 'invoices';
    
        $result = $this->api->post($uri,$invoiceData);
        alegra_log('----------result data------------','invoices');
        alegra_log($result, 'invoices');
        update_post_meta($order_id,'alegra_response', $result);
        $result = json_decode($result);
	    date_default_timezone_set( $tz );
        if (isset($result->id)) {

            add_post_meta($order_id,'alegra_invoice_id', $result->id);

            add_post_meta($order_id,'alegra_total_paid', $result->total);

            $payment = new SendPayment($order_id);

            $payment->handle();

        }

    }

    public function get_product_variation_id( $attribute, $value, $product_id = 0 ) {

        global $wpdb;

        if ( $product_id == 0 )
            $product_id = get_the_id();

        return $wpdb->get_var( "

            SELECT p.ID
            FROM {$wpdb->prefix}posts as p
            JOIN  {$wpdb->prefix}postmeta as pm ON p.ID = pm.post_id
            WHERE pm.meta_key = 'attribute_$attribute'
            AND pm.meta_value LIKE '$value'
            AND p.post_parent = $product_id

        " );
    }

    public function calculateItemTax(&$items, $orderTotal){

        foreach ($items as $key => $item) {

            $items[$key]['price'] = round(($items[$key]['price'] / 1.19),1);

        }

        return $items;
    }

    public function isGiftCardOrder($items) 
    {
        $result = true;

        foreach ($items as $key => $orderItem) {
            $isGift = $orderItem->get_product()->get_meta('_gift_card');
            if($isGift != 'yes') {
                $result = false;
                break;
            }
        }

        return $result;
    }

    public function isGiftCardItem($item)
    {
        return $item->get_product()->get_meta('_gift_card') == 'yes' ? true : false;
    }
}
