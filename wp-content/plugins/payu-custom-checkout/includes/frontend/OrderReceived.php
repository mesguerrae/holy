<?php

/**
 * 
 */


add_filter( 'woocommerce_get_order_item_totals', 'add_order_status_orderdetail', 30, 3 );

function add_order_status_orderdetail( $total_rows, $order, $tax_display ) {

    $gran_total = $total_rows['order_total'];
    
    unset( $total_rows['order_total'] );

    $total_rows['recurr_not'] = array(
        'label' => __( 'Estado del pedido:', 'woocommerce' ),
        'value' => '<strong>'.strtoupper(wc_get_order_status_name($order->get_status())).'</strong>',
    );

    $total_rows['order_total'] = $gran_total;

    return $total_rows;
}

add_filter( 'woocommerce_thankyou_order_received_text', 'payu_print_order_status', 20, 2 );
 
function payu_print_order_status( $thank_you_title, $order ){
 
	return 'Estado: <strong>' . strtoupper(wc_get_order_status_name($order->get_status())).'</strong>';
 
}




add_action( 'woocommerce_thankyou_payu_efectivo', 'custom_content_thankyou_efectivo', 10, 1 );

function custom_content_thankyou_efectivo( $order_id ) {

    $order = wc_get_order($order_id);

    $payment_method = $order->get_payment_method();

    if ($payment_method && $payment_method == 'payu_efectivo') {

        $order_pdf = $order->get_meta('pdf');

        echo '<h2 class="woocommerce-order-details__title">ECUENTRA LA INFORMACION DE PAGO AQUI</h2>';

        echo '<a href="'.$order_pdf.'" target="_blank"><button class="button alt">DESCARGA TU RECIBO AQUI</button></a>';

        echo "<script>window.open('".$order_pdf."','_blank');</script>";

    }
}


 ?>