<?php 
namespace admin\events;
use \WP_Queue\Job;
use includes\Api;


class SendPayment extends \WP_Queue\Job
{
    protected $order_id;

    protected $api;

    const SUCH_BANK = 2;

    const WHOLESALES_BANK = 5;

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

        $numOrder = get_option('alegra_order_inv');

        $currentNumOrder = get_option('current_alegra_order_inv');

        if ($currentNumOrder == $numOrder) {
            
             $withTax = true;

        }

        $order = wc_get_order($order_id);

        $items = $order->get_items();

        $shipping_method = @array_shift($order->get_shipping_methods());
                
        $shippingData = $shipping_method->get_data();
        
        $shippingTotal = $shippingData['total'];

        $total = 0;

        foreach ($items as $key => $orderItem) {

            $tax = array();

            if ($withTax) {
                
                $total += round(($orderItem->get_total() / 1.19),1);
            
            }else{
                $total += $orderItem->get_total();
            }

           

        }

        if ($withTax) {
            
            $total += round(($shippingTotal / 1.19),1);
            $total += ($total * 0.19);
        }else{

            $total += $shippingTotal;
        }

        //TODO verificar si ya se creo la orden en alegra y no ejecutar nada si ya se creo
        if (get_post_meta($order_id, 'alegra_invoice_id', true) == '') {

            return;
        }

        $alegraInvoiceId = get_post_meta($order_id, 'alegra_invoice_id', true);

        $paymentMethod = $order->get_payment_method();

        switch ($paymentMethod) {

            case 'payu_efectivo':
                $method = 'CASH';
                break;
            case 'payu_tc':
                $method = 'CREDIT_CARD';
                break;
            case 'payu_trasferencia_bancaria':
                $method = 'DEBIT_CARD';
                break;
            case 'wholesales':
                $method = 'DEBIT_TRANSFER_BANK';
                break;
            
            default:
                # code...
                break;
        }
        $invoiceData['date'] = date('Y-m-d');


        $total = get_post_meta($order_id, 'alegra_total_paid', true);

        $invoiceData['invoices'] = array(array(
                'amount'            => $total,
                'id'                => $alegraInvoiceId        
            )
        );

        $invoiceData['bankAccount'] = (get_post_meta($order_id, '_is_wholesales_order', true) == '') ? 2 : 5;
 

        alegra_log('----------send data payment:'.$alegraInvoiceId .'------------','sendpayment');
        alegra_log($invoiceData, 'sendpayment');
        $uri = 'payments';
        
        $result = $this->api->post($uri,$invoiceData);
        alegra_log('----------result data payment:'.$alegraInvoiceId .'------------','sendpayment');
        alegra_log($result, 'sendpayment');
        $result = json_decode($result);

        if (isset($result->id)) {
            
            add_post_meta($order_id,'alegra_invoice_payment_id', $result->id);
        }

        date_default_timezone_set( $tz );


    }
}