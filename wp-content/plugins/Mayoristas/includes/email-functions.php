<?php

add_filter( 'woocommerce_email_order_details',  'add_order_wholesales_orderdetail', 10, 4 );


define("HTML_EMAIL_HEADERS", array('Content-Type: text/html; charset=UTF-8'));

function send_custom_email($email,  $subject, $message, $heading, $files = array()){

    $mailer = WC()->mailer();

    $wrapped_message = $mailer->wrap_message($heading, $message);

    $wc_email = new WC_Email;

    $html_message = $wc_email->style_inline($wrapped_message);

    if(wp_mail( $email, $subject, $html_message, HTML_EMAIL_HEADERS, $files)){

        return true;

    }else{

        return false;
    }
}

function add_order_wholesales_orderdetail( $order, $sent_to_admin, $plain_text, $email  ){

    if ($order->get_payment_method() == 'wholesales') {

        $text = get_option( 'wholesales_success_message', 1 );

        $content = '';

        $content .= '<h3>¡IMPORTANTE!</h3>';

        $content .= '<p style="text-align: justify;text-transform: uppercase;">'.$text.'</p>';

        if ($text != '') {

            echo $content;
        }

        $new_order_settings = get_option( 'woocommerce_new_order_settings', array() );
        
        $to = get_option( 'wholesales_email' );

        $message = 'Link de la orden:';

        $url = admin_url("/post.php?post=".$order->get_id().'&action=edit');

        $message .= '<a  href="'.$url.'">'.$order->get_id().'</a>';

        send_custom_email($to,  '¡Nueva orden de Mayorista! - '.$order->get_id(), $message, 'Info:');
    }
    
    //return $total_rows;

}