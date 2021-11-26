<?php

/*
Plugin Name:            WooCommerce Servientrega Add-ons
Description:            WooCommerce Servientrega Add-ons

Author:                 Nicolas Lopez

Version:                0.0.1

License: GPLv2 or later
*/

define('ROOT', ABSPATH );

include 'helper/helper.php';

include 'events/GenerateGuide.php';

include 'lib/PDFMerger/PDFMerger.php';

require_once ROOT. '/vendor/autoload.php';

class ServientregaAddons{

    protected $helper;

    function __construct(){

        $this->helper = new ServientregaAddonsHelper();

        add_filter( 'bulk_actions-edit-shop_order', array( $this, 'downloads_bulk_pdf') , 20, 1 );

        add_filter( 'handle_bulk_actions-edit-shop_order', array( $this, 'downloads_handle_bulk_action_edit_shop_order'), 10, 3 );

        add_filter( 'handle_bulk_actions-edit-shop_order', array( $this, 'generate_guide_bulk'), 10, 3 );

        add_action( 'add_meta_boxes', array( $this, 'add_meta_boxesws') );


        add_filter( 'manage_edit-shop_order_columns', array( $this, 'custom_shop_order_column'), 20 );


        add_action( 'manage_shop_order_posts_custom_column' , array( $this, 'custom_orders_list_column_content'), 20, 2 );


        add_action('rest_api_init', array( $this, 'register_notification_url' ));

        add_filter( 'woocommerce_email_order_details', array( $this, 'add_order_shipping_orderdetail'), 10, 4 );

        add_filter( 'woocommerce_email_styles', array($this,'add_shipping_button_style'), 9999, 2 );

        add_filter( 'woocommerce_my_account_my_orders_actions', array($this, 'sv_add_my_account_order_actions'), 10, 2 );

        add_action( 'woocommerce_after_account_orders', array($this, 'action_after_account_orders_js'));

        add_action('wp_ajax_new_servientrega_guide', array($this,'ajax_generate_servientrega_guide'));

        add_filter( 'bulk_actions-edit-shop_order', array( $this, 'complete_orders_bulk') , 20, 1 );

        add_action('wp_ajax_ajax_confirm_orders', array($this,'ajax_confirm_orders'));

        add_filter( 'handle_bulk_actions-edit-shop_order', array( $this, 'handle_bulk_action_confirm_orders'), 10, 3 );

    }

    public function handle_bulk_action_confirm_orders( $redirect_to, $action, $post_ids){

        if ( $action !== 'mark_completed_ajax' )
            return $redirect_to;

        $orders = '';

        foreach( $post_ids as $order_id ) {

            $orders .= $order_id.'|';

        }

        $redirect_to = add_query_arg( 'order_confirmed', substr($orders,0,-1), $redirect_to );

        return $redirect_to;
    }

    public function complete_orders_bulk($actions){

        unset($actions['mark_completed']);

        $actions['mark_completed_ajax'] = __( 'Cambiar estado a completado (NEW)', 'woocommerce' );

        return $actions;
    }

    public function ajax_confirm_orders(){

        $order_id = $_POST['order'];

        $order = wc_get_order($order_id);

        if (!$order->meta_exists('downloaded_guide')) {

            return 'fail';
        }

        $status = 'completed';

        $order->update_status( $status, __( 'Order status changed by bulk edit:', 'woocommerce' ), true );

        do_action( 'woocommerce_order_edit_status', $order_id, $status );

        $response = 'ok';

        echo $response;


    }

    public function ajax_generate_servientrega_guide(){

        $order_id = $_POST['order'];

        $order = wc_get_order($order_id);

        $response = 'ok';

        if(!Shipping_Servientrega_WC::generate_guide($order_id,'processing','processing',$order)){

            $response = 'ok';

            //http_response_code(200);

        }else{

            $response = 'fail';

            //http_response_code(500);
        }
        //echo $order_id;

        //header( "Content-Type: application/json" );
        echo $response;


    }

    public function downloads_bulk_pdf( $actions ){

        $actions['servientrega_shipping_addon'] = __( 'Descargar Guias PDF', 'woocommerce' );

        $actions['servientrega_generate_guide'] = __( 'Generar Guias', 'woocommerce' );

        return $actions;


    }

    public function downloads_handle_bulk_action_edit_shop_order( $redirect_to, $action, $post_ids ){

        if ( $action !== 'servientrega_shipping_addon' )
            return $redirect_to;

        $args = array(
         'status' => 'processing'
        );

        $paths = array();

        $upload_dir = get_option( 'url_cdn', 1 );

        $upload_dir   = wp_upload_dir();
        
        $upload_dir = $upload_dir['basedir'];

        $user = wp_get_current_user();

        $allowed_roles = array('administrator');

        $isAdmin = false;

        if( array_intersect($allowed_roles, $user->roles ) ) {

            $isAdmin = true;

        }

        foreach( $post_ids as $order_id ) {

            $servientrega_guide = $upload_dir.'/woocommerce-shipping-servientrega/attachments/guide-'.$order_id.'.pdf';

            $downloaded = get_post_meta( $order_id, 'downloaded_guide', true );

            $validUser = ($downloaded == 1 && !$isAdmin) ? false : true;

            if ( file_exists($servientrega_guide)  && $validUser) {

                array_push($paths, $servientrega_guide);

                update_post_meta($order_id, 'downloaded_guide', 1);

            }else{

                continue;
            }

            $invoice_attachment =  get_post_meta($order_id, '_bewpi_invoice_pdf_path', true);

            if (!empty( $invoice_attachment )) {

                $invoice_path = $upload_dir.'/woocommerce-pdf-invoices/attachments/'.$invoice_attachment;
                
                if ( file_exists ($invoice_path ) && $validUser) {

                    array_push($paths, $invoice_path);
                }
            }else {

                continue;
            }

        }

        ob_clean();

        $pdf = new PDFMerger;

        foreach ($paths as $value) {
            try {
            
        
                $pdf->addPDF( $value , 'all' );

            } catch(\Exception $e){
                continue;
            }
        }

        $pdf->merge('D', 'result.pdf');

        

        

        exit;

    }


    public function generate_guide_bulk($redirect_to, $action, $post_ids){

        if ( $action !== 'servientrega_generate_guide' )
            return $redirect_to;

        $orders = '';

        foreach( $post_ids as $order_id ) {

            //wp_queue()->push( new GenerateGuide( $order_id ), 120 );

            //$order_id = $this->order_id;

            //$order = wc_get_order($order_id);

            //$response = '';

            $alegra_id = get_post_meta( $order_id, 'alegra_invoice_id', true );

            if ($alegra_id == '' && $alegra_id == false) {

                continue;
            }

            $orders .= $order_id.'|';

            /*if(!Shipping_Servientrega_WC::generate_guide($order_id,'processing','processing',$order)){

                $response .= 'ORDEN '.$post_id.' FALLO<br>';

            }else{

                $response .= 'ORDEN '.$post_id.' SE GENERO<br>';
            }*/

            $redirect_to = add_query_arg( 'order_guide', substr($orders,0,-1), $redirect_to );

        }

        echo '<div id="message" class="updated fade">';
        echo $response;
        echo '</div>';

       return $redirect_to;
    }

    public function add_meta_boxesws(){

        $order_id = isset($_GET['post']) ? $_GET['post'] : false;

        $guide_servientrega = get_post_meta($order_id, 'guide_servientrega', true);

        if (empty($guide_servientrega) ){

            add_meta_box( 'custom_order_meta_box', __( 'Generar guia' ),
            array( $this, 'custom_metabox_content'), 'shop_order', 'side', 'default');

        }

    }

    public function does_url_exists($url) {
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_NOBODY, true);
    curl_exec($ch);
    $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    //echo $code;exit;
    curl_close($ch);
//echo $code;exit;
    if ($code == 200) {
        $status = true;
    } else {
        $status = false;
    }

//    curl_close($ch);
    return $status;
}

    public function custom_metabox_content(){
        $post_id = isset($_GET['post']) ? $_GET['post'] : false;
        if(! $post_id ) return;

        ?>
            <p><a href="?post=<?php echo $post_id; ?>&action=edit&generate_guide=true" class="button"><?php _e('Generar guia'); ?></a></p>
        <?php

        if ( isset( $_GET['generate_guide'] ) && ! empty( $_GET['generate_guide'] ) ) {

            $order = wc_get_order($post_id);

            Shipping_Servientrega_WC::generate_guide($post_id,'processing','processing',$order);

            //header('Location: '.$_SERVER['REQUEST_URI']);
        }
    }

    public function custom_shop_order_column($columns){

        $reordered_columns = array();

        // Inserting columns to a specific location
        foreach( $columns as $key => $column){
            $reordered_columns[$key] = $column;
            if( $key ==  'order_status' ){
                // Inserting after "Status" column
                $reordered_columns['servientrega-guide'] = __( 'Servientrega Guia','theme_domain');
            }
        }
        return $reordered_columns;
    }

    public function custom_orders_list_column_content( $column, $post_id ){

        switch ( $column ){
            case 'servientrega-guide' :

                $guide_servientrega = get_post_meta($post_id, 'guide_servientrega', true);

                if (!empty($guide_servientrega) ){

                    $is_downloaded = get_post_meta($post_id,'downloaded_guide', true);

                    $is_downloaded_text = ($is_downloaded == 1) ? "(Descargada)" : '';

                    echo $guide_servientrega.'<br>'.$is_downloaded_text;

                }
                // Testing (to be removed) - Empty value case
                else
                    echo '<small>(<em>no value</em>)</small>';

                break;
        }
    }

    public function register_notification_url(){

        register_rest_route('v1','servientrega_shipping', array(
          array(
              'methods'         => WP_REST_Server::CREATABLE,
              'callback'        => array( $this, 'set_new_shipping_status' ),
              /*'permission_callback' => function () {
                return current_user_can( 'edit_others_posts' );
              },*/
              'args' => array(
                'guide' => array(
                  'required' => true,
                  'type' => 'string',
                ),
                'status' => array(
                  'required' => true,
                  'type' => 'string',
                ),
                'date' => array(
                  'required' => true,
                  'type' => 'string',
                ),
                'user' => array(
                  'required' => true,
                  'type' => 'string',
                ),
                'password' => array(
                  'required' => true,
                  'type' => 'string',
                ),
              )
            )
          )
        );
    }


    public function set_new_shipping_status($args){

        //echo "hola";exit;
        //var_dump($args['status']);


        global $wpdb;

        $user = get_user_by( 'login', $args['user'] );

        $pass = $args['password'];

        if ( $user && wp_check_password( $pass, $user->data->user_pass, $user->ID) ){

            $results = $wpdb->get_results( "select post_id, meta_key from $wpdb->postmeta where meta_value = '".$args['guide']."' and meta_key = 'guide_servientrega'", ARRAY_A );

            //var_dump($results);Exit;

        }
        else{

           //echo "Nope";
        }

    }

    public function add_order_shipping_orderdetail( $order, $sent_to_admin, $plain_text, $email  ){


        if ($order->get_status() == 'completed') {

            $guide =  get_post_meta($order->get_id(), 'guide_servientrega', true);

            if ($guide != '') {

                echo '<strong style="margin-top:15px;">Tu número de seguimiento es: '.$guide.'</strong><br>';
                echo '<a href="https://www.servientrega.com/wps/portal/Colombia/transacciones-personas/rastreo-envios/detalle?id='.$guide.'"><button class="button-shipping">RASTREA TU PEDIDO AQUI</button></a><br><br>';
            }


        }

        //return $total_rows;

    }

    public function add_shipping_button_style($css, $email){

        $css .= '
              .button-shipping{
                background-color:    #e7c5a8;
                background:    linear-gradient(#e7c5a8, #e7c5a8);
                border-radius: 5px;
                padding:       8px 20px;
                color:         #ffffff;
                text-align:    center;
              }
           ';

        return $css;

    }

    public function  sv_add_my_account_order_actions( $actions, $order ) {

        if ($order->get_status() == 'completed') {

            $guide =  get_post_meta($order->get_id(), 'guide_servientrega', true);

            if ($guide != '') {

                $actions['shipping_guide'] = array(
                    'url'  => 'https://www.servientrega.com/wps/portal/Colombia/transacciones-personas/rastreo-envios/detalle?id='.$guide,
                    'name' => 'Sigue tu pedido',
                    'target' => '_blank'
                );
            }

        }

        return $actions;
    }

    public function action_after_account_orders_js() {

        $action_slug = 'shipping_guide';

        ?>

        <script>

        jQuery(function($){

            $('a.<?php echo $action_slug; ?>').each( function(){

                $(this).attr('target','_blank');

            })

        });

        </script>

        <?php
    }
}


$class = new ServientregaAddons();

function add_order_number_start_setting( $settings ) {

$updated_settings = array();

   foreach ( $settings as $section ) {
//    echo get_option( 'url_cdn', 1 );
    // at the bottom of the General Options section
    if ( isset( $section['id'] ) && 'general_options' == $section['id'] &&
       isset( $section['type'] ) && 'sectionend' == $section['type'] ) {

      $updated_settings[] = array(
        'name'     => __( 'URL CDN', 'url_cdn' ),
        'desc_tip' => __( 'Url del cdn' ),
        'id'       => 'url_cdn',
        'type'     => 'text',
        'css'      => 'min-width:300px;',
        'std'      => '1',  // WC < 2.0
        'default'  => '1',  // WC >= 2.0
        'desc'     => __( 'cdn.*****.com', 'url_cdn' ),
      );
    }

    $updated_settings[] = $section;
  }

  return $updated_settings;
}
add_filter( 'woocommerce_general_settings', 'add_order_number_start_setting' );




// define the woocommerce_order_formatted_shipping_address callback
function filter_woocommerce_order_formatted_shipping_address( $array, $order ) {
    // make filter magic happen here...

    $address['first_name'] =  $array['first_name'];
    $address['last_name'] =  $array['last_name'];
    $address['id_type'] = $order->get_meta('billing_identification_type_');
    $address['id_number'] = $order->get_meta('billing_identification_number_');
    $address['company'] =  $array['company'];
    $address['address_1'] =  $array['address_1'];
    $address['address_2'] =  $array['address_2'];
    $address['city'] =  $array['city'];
    $address['state'] =  $array['state'];
    $address['postcode'] =  $array['postcode'];
    $address['country'] =  $array['country'];
    $address['phone'] =   $order->billing_phone;

    return $address;
};

// add the filter
add_filter( 'woocommerce_order_formatted_shipping_address', 'filter_woocommerce_order_formatted_shipping_address', 10, 3 );


// define the woocommerce_order_formatted_shipping_address callback
function filter_woocommerce_order_formatted_billing_address( $array, $order ) {

    $newIdType = $order->get_meta('billing_identification_type_');

    $newIdNumber = $order->get_meta('billing_identification_number_');

    $oldIdType = $order->get_meta('identification_type_');

    $oldIdNumber =  $order->get_meta('identification_number_');

    $idType = ($newIdType == '') ? $oldIdType : $newIdType;

    $idNumber = ($newIdNumber == '') ? $oldIdNumber : $newIdNumber;
    // make filter magic happen here...
    $address['first_name'] =  $array['first_name'];
    $address['last_name'] =  $array['last_name'];
    $address['id_type'] = $idType;
    $address['id_number'] = $idNumber;
    $address['company'] =  $array['company'];
    $address['address_1'] =  $array['address_1'];
    $address['address_2'] =  $array['address_2'];
    $address['city'] =  $array['city'];
    $address['state'] =  $array['state'];
    $address['postcode'] =  $array['postcode'];
    $address['country'] =  $array['country'];
    $address['phone'] =  $array['phone'];
    //print_r($address);exit;
    return $address;
};

// add the filter
add_filter( 'woocommerce_order_formatted_billing_address', 'filter_woocommerce_order_formatted_billing_address', 10, 3 );


add_filter( 'woocommerce_localisation_address_formats', function( $formats ){

    $formats[ 'default' ] = '
        {name}
        {id_number}
        {id_type}
        {phone}
        {company}
        {address_1}
        {address_2}
        {city}
        {state}
        {postcode}
        {country}
    ';

    return $formats;
} );


add_filter( 'woocommerce_formatted_address_replacements', function( $replacements, $args ){
    $replacements['{id_type}'] = $args['id_type'];
    $replacements['{id_number}'] = $args['id_number'];
    $replacements['{phone}'] = $args['phone'];
    //$replacements['{phone}'] = $args['phone'];
    return $replacements;
}, 10, 2 );

