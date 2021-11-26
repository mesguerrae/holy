<?php

/*
Plugin Name:            WooCommerce Payu Custom Inventory
Description:            WooCommerce Payu Custom Inventory, Contiene funcionalidades de GA

Author:                 Nicolas Lopez

Version:                0.0.1

License: GPLv2 or later
*/


/*

1. Agregar campo de precio y check de venta al por mayor  y permitir ajustar el precio del producto OK
2. Agregar campo boleano si o no si es una venta al por mayor o no en reporte excel OK
3. Agregar precio real a vista de producto (SOLO APLICA A ADMINISTRADOR) OK
4. en vista de ordenes para Administrador: ver todas las ordenes, vendedor: ver ordenes de la pagina + ordenes al por mayor hechas por el. OK
5. En el reporte de ventas, solo tener en cuenta ventas de la pagina, el usuario que hizo la venta y precio real. OK
6. Agregar a las ordenes con estado crédito, la opción de ingresar abonos, estos abonos deben mostrarse en las notas de la orden y agregar una tabla dentro de la orden  con el listado de todos los abonos y la deuda restante. (SOLO APLICA A ADMINISTRADOR) OK
7. Cuando los abonos se completen, el estado de la orden pasara a completado automáticamente.  (SOLO APLICA A ADMINISTRADOR). OK
https://remicorson.com/mastering-woocommerce-products-custom-fields/
*/

include "helper/helper.php";
class CustomInventory{

    protected $helper;

    function __construct(){

        $this->helper = new Helper();

        add_action('woocommerce_new_order_item', array( $this ,'custome_add_to_cart'),1,10);

        add_action( 'admin_enqueue_scripts', array( $this ,'wpdocs_selectively_enqueue_admin_script') );

        add_action( 'woocommerce_process_shop_order_meta', array( $this ,'admin_order_adduser'), 10 , 1 );

        add_filter( 'pre_get_posts', array( $this ,'filter_woocommerce_shop_order_search_fields') );

        add_action( 'init', array( $this ,'register_credit_order_statuses') );

        add_filter( 'wc_order_statuses', array( $this, 'credit_wc_order_statuses') );

        add_action( 'add_meta_boxes', array( $this, 'mv_add_meta_boxes') );

        add_action( 'save_post', array($this, 'mv_save_wc_order_other_fields'), 10, 1 );

        add_action('woocommerce_admin_order_totals_after_tax', array( $this, 'custom_admin_order_totals_deposit'), 10, 1 );

        add_action( 'woocommerce_product_options_general_product_data', array( $this,'add_real_price_fields') );

        add_action( 'woocommerce_process_product_meta', array( $this,'add_real_price_fields_save') );

        add_filter( 'manage_edit-product_columns', array( $this, 'custom_product_column') ,11);

        add_action( 'manage_product_posts_custom_column' , array( $this, 'custom_product_list_column_content'), 10, 2 );
        #Campo de identificacion en checkout
        //add_filter( 'woocommerce_checkout_fields' , array ( $this , 'user_identification_checkout_fields') );
        /*add_filter( 'woocommerce_localisation_address_formats', array( $this, 'admin_localisation_address_formats'), 50, 1 );

        add_filter( 'woocommerce_formatted_address_replacements', array( $this, 'custom_formatted_address_replacements'), 10, 2 );

        add_filter('woocommerce_order_formatted_billing_address', array( $this, 'add_woocommerce_order_fields'), 10, 2);
*/

        add_action( 'woocommerce_admin_order_data_after_shipping_address', array ( $this , 'user_identification_field_display_admin_order_meta'), 10, 1 );

        #filtro de categorias en vista de categoria, solo se muestra la categoria actual
        add_filter('woocommerce_product_categories_widget_dropdown_args', array ( $this ,'widget_product_categories_list_args'), 10, 1);

        add_filter('woocommerce_product_categories_widget_args', array ( $this ,'widget_product_categories_list_args'), 10, 1);

        add_filter( 'woocommerce_email_classes', array( $this, 'add_antifraud_notification_order_woocommerce_email') );

        //add_action( 'woocommerce_order_status_changed', array( $this, 'action_woocommerce_order_status_changed'), 10, 4 );

        add_action( 'woocommerce_order_status_changed', array( $this, 'action_woocommerce_order_status_changed_ga'), 10, 4 );

        #product percent sorting

        //add_filter('woocommerce_default_catalog_orderby', array( $this, 'misha_default_catalog_orderby'));
        
        add_filter( 'woocommerce_default_catalog_orderby_options', array( $this, 'custom_woocommerce_catalog_orderby' ));
        
        add_filter( 'woocommerce_catalog_orderby', array( $this, 'custom_woocommerce_catalog_orderby' ));

        add_filter( 'woocommerce_get_catalog_ordering_args', array( $this, 'custom_woocommerce_get_catalog_ordering_args' ));

        add_action('woocommerce_update_product', array( $this, 'sv_woo_calc_my_discount_quickedit'));

        #hide shipping methos when free is available

        add_filter( 'woocommerce_package_rates', array( $this, 'hide_shipping_when_free_is_available'), 100 );

        add_shortcode( 'ga-canceled-order', array( $this, 'ga_canceled_order' ));

        add_action( 'in_admin_footer', array( $this,  'action_in_admin_footer'), 10, 1 ); 

    

    }

    public function action_in_admin_footer( $array ) { 
        echo do_shortcode( '[ga-canceled-order]' );
    }


    public function ga_canceled_order() {

        include WP_PLUGIN_DIR . '/custom-inventory/template/ga-canceled-orders.php';
    }

    public function hide_shipping_when_free_is_available( $rates ) {
        $free = array();
        foreach ( $rates as $rate_id => $rate ) {
            if ( 'free_shipping' === $rate->method_id ) {
                $free[ $rate_id ] = $rate;
                break;
            }
        }
        return ! empty( $free ) ? $free : $rates;
    }

    public function replace_accent($str){

        $unwanted_array = array(    'Š'=>'S', 'š'=>'s', 'Ž'=>'Z', 'ž'=>'z', 'À'=>'A', 'Á'=>'A', 'Â'=>'A', 'Ã'=>'A', 'Ä'=>'A', 'Å'=>'A', 'Æ'=>'A', 'Ç'=>'C', 'È'=>'E', 'É'=>'E',
                            'Ê'=>'E', 'Ë'=>'E', 'Ì'=>'I', 'Í'=>'I', 'Î'=>'I', 'Ï'=>'I', 'Ñ'=>'N', 'Ò'=>'O', 'Ó'=>'O', 'Ô'=>'O', 'Õ'=>'O', 'Ö'=>'O', 'Ø'=>'O', 'Ù'=>'U',
                            'Ú'=>'U', 'Û'=>'U', 'Ü'=>'U', 'Ý'=>'Y', 'Þ'=>'B', 'ß'=>'Ss', 'à'=>'a', 'á'=>'a', 'â'=>'a', 'ã'=>'a', 'ä'=>'a', 'å'=>'a', 'æ'=>'a', 'ç'=>'c',
                            'è'=>'e', 'é'=>'e', 'ê'=>'e', 'ë'=>'e', 'ì'=>'i', 'í'=>'i', 'î'=>'i', 'ï'=>'i', 'ð'=>'o', 'ñ'=>'n', 'ò'=>'o', 'ó'=>'o', 'ô'=>'o', 'õ'=>'o',
                            'ö'=>'o', 'ø'=>'o', 'ù'=>'u', 'ú'=>'u', 'û'=>'u', 'ý'=>'y', 'þ'=>'b', 'ÿ'=>'y' );
        return strtr( $str, $unwanted_array );
    }

     public function action_woocommerce_order_status_changed_ga( $order_id, $old_status, $new_status, $order ) {


        if ($new_status != 'processing') {

            return;
        }

        $url = 'https://www.google-analytics.com/collect';

        $order_total = $order->get_total() - $order->get_shipping_total();

        $user = $order->get_user();

        $email =  $user->user_email;

        $email_first = explode('@', $email);

        $id = $user->ID;

        $datalayer_user_id = hash('sha256',$email.md5($email_first[0]));

        $body =  "v=1&t=event&tid=UA-130946509-2&cid=$datalayer_user_id&ec=Pedido%20Aprobado&ea=Pedido&el=$order_id&ev=$order_total";

        $response = wp_remote_post( $url, array(
            'method' => 'POST',
            'timeout' => 45,
            'redirection' => 5,
            'httpversion' => '1.0',
            'blocking' => true,
            'headers' => array(),
            'body' =>  $body,
            'cookies' => array()
            )
        );

    }

    public function action_woocommerce_order_status_changed( $order_id, $old_status, $new_status, $order ) {


        if ($new_status != 'processing') {

            return;
        }

        $url = 'https://www.google-analytics.com/batch';

        $order_total = $order->get_total();

        $order_shipping_total = $order->get_shipping_total();

        $order_tax = $order->get_total_tax();

        $user = $order->get_user();

        $email =  $user->user_email;

        $email_first = explode('@', $email);

        $id = $user->ID;

        $datalayer_user_id = hash('sha256',$email.md5($email_first[0]));


        $body =  array(
            "v=1&t=transaction&tid=UA-130946509-2&cid=$datalayer_user_id&ti=$order_id&ta=Holy%20Cosmetics&tr=$order_total&ts=$order_shipping_total&tt=$order_tax&cu=COP",
            );

        $items = $order->get_items();

        foreach ($items as  $item) {

            $name = $this->replace_accent(ucfirst(strtolower ($item->get_name())));

            $item_total = $item->get_total();

            $item_qty = $item->get_quantity();

            $product_id = $item->get_product_id();

            $categories = $item->get_product()->get_categories();

            $categories = str_replace(array(', '),'|', strip_tags($categories));

            $body[] = "v=1&t=item&tid=UA-130946509-2&cid=$datalayer_user_id&ti=$order_id&in=$name&ip=$item_total&iq=$item_qty&ic=$product_id&iv=$categories&cu=COP";

        }

        $response = wp_remote_post( $url, array(
            'method' => 'POST',
            'timeout' => 45,
            'redirection' => 5,
            'httpversion' => '1.0',
            'blocking' => true,
            'headers' => array(),
            'body' =>  implode("\n",$body),
            'cookies' => array()
            )
        );

    }


    public function add_antifraud_notification_order_woocommerce_email( $email_classes ) {

        require( 'admin/class-wc-antifraud-order-email.php' );

        $email_classes['WC_Antifraud_Notification_Order_Email'] = new WC_Antifraud_Notification_Order_Email();

        return $email_classes;

    }

    public function custome_add_to_cart($item_id, $item, $order_id) {
        global $woocommerce;

        if ($_POST['apply_wholesale'] != "false" && is_admin() && isset($_POST['apply_wholesale'])) {
            foreach($_POST['data'] as $data){

                if ($data['id'] == $item->get_product_id()) {

                    $new_product_price = $data['price'];

                    break;

                }elseif($data['id'] == $item->get_variation_id()){

                    $new_product_price = $data['price'];

                    break;
                }
            }

            $product_quantity = (int) $item->get_quantity();

            $new_line_item_price = $new_product_price * $product_quantity;

            $item->set_subtotal( $new_line_item_price );

            $item->set_total( $new_line_item_price );

            $item->calculate_taxes();

            $item->save();

            $actually_due = get_post_meta( $order_id, '_total_due', true );

            $actually_due = ($actually_due == null) ? 0 : $actually_due;

            $order = wc_get_order( $order_id );

            $order->update_meta_data( '_wholesale', 'Yes' );

            $order->update_meta_data( '_total_due', ($actually_due + $new_line_item_price) );

            $order->save();
        }


    }

    public function wpdocs_selectively_enqueue_admin_script( $hook ) {

        wp_enqueue_script( 'wc-admin-order-meta-boxes', plugin_dir_url( __FILE__ ) . 'meta-boxes-order.min.js', array( 'wc-admin-meta-boxes', 'wc-backbone-modal', 'selectWoo', 'wc-clipboard' ), WC_VERSION );
    }

    public function admin_order_adduser($order_id){

        if (is_admin()) {

            $current_user = wp_get_current_user();

            $order = wc_get_order( $order_id );

            $order->update_meta_data( '_admin_user', $current_user->user_login );

            $order->save();
        }

    }

    public function filter_woocommerce_shop_order_search_fields($query){

        global $pagenow;

        $qv = &$query->query_vars;

        //$_order = new WC_Order( $post->ID ); // here

        if ( $pagenow == 'edit.php' &&

            isset($qv['post_type']) && $qv['post_type'] == 'shop_order') {

            $current_user = wp_get_current_user();

            $allowed_roles = array('administrator');

            if( !array_intersect($allowed_roles, $current_user->roles ) /*&& $_order->get_status() == 'credit'*/ ) {

                $meta_query = array(
                    'meta_query' => array(
                        'relation' => 'OR',
                         array(
                          'key' => '_admin_user',
                          'compare' => 'NOT EXISTS' // doesn't work
                         ),
                         array(
                          'key' => '_admin_user',
                          'value' => $current_user->user_login,
                          'compare'=>'=',
                         )
                     )
                );
                $query->set('meta_query',$meta_query);
            }

        }

        return $query;
    }

    public function register_credit_order_statuses(){

        register_post_status( 'wc-credit', array(

            'label'                     => _x( 'Credito', 'Order status', 'woocommerce' ),
            'public'                    => true,
            'exclude_from_search'       => false,
            'show_in_admin_all_list'    => true,
            'show_in_admin_status_list' => true,
            'label_count'               => _n_noop( 'Invoiced <span class="count">(%s)</span>', 'Credito<span class="count">(%s)</span>', 'woocommerce' )

        ) );
    }

    public function credit_wc_order_statuses($order_statuses){

        $order_statuses['wc-credit'] = _x( 'Credito', 'Order status', 'woocommerce' );

        return $order_statuses;

    }

    public function mv_add_meta_boxes(){

        global $post;

        if ($post->post_type != 'shop_order') {
            return;
        }
        $_order = new WC_Order( $post->ID ); // here

        $current_user = wp_get_current_user();

        $allowed_roles = array('administrator');

        if( /*array_intersect($allowed_roles, $current_user->roles )  &&*/ $_order->get_status() == 'credit') {

            add_meta_box( 'mv_other_fields', __('Pago parcial','woocommerce'), array($this,'mv_add_other_fields_for_packaging'), 'shop_order', 'side', 'core' );
        }
    }

    public function mv_save_wc_order_other_fields( $post_id ) {

        global $wpdb;

        if ( ! isset( $_POST[ 'mv_other_meta_field_nonce' ] ) ) {
            return $post_id;
        }

        if ( $_POST[ 'partial_payment' ] == "" || $_POST[ 'partial_payment' ] == 0 ) {
            return $post_id;
        }

        $order_total = get_post_meta( $post_id, '_order_total', true );

        if ($order_total < (int)$_POST[ 'partial_payment' ]) {

            add_action( 'admin_notices', array( $this, 'error_notice') );

            return $post_id;
        }

        $sum_deposit = $this->helper->get_sum_deposit($post_id);

        $difference = (($sum_deposit + $_POST[ 'partial_payment']) > $order_total) ? true : false;

        if ( $difference) {

            add_action( 'admin_notices', array( $this, 'error_notice') );

            return $post_id;
        }


        $nonce = $_REQUEST[ 'mv_other_meta_field_nonce' ];

        if ( ! wp_verify_nonce( $nonce ) ) {
            return $post_id;
        }

        if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
            return $post_id;
        }

        if ( 'page' == $_POST[ 'post_type' ] ) {

            if ( ! current_user_can( 'edit_page', $post_id ) ) {
                return $post_id;
            }
        } else {

            if ( ! current_user_can( 'edit_post', $post_id ) ) {
                return $post_id;
            }
        }

        $this->helper->insert_deposit($post_id, $_POST[ 'partial_payment']);

        $current_user = wp_get_current_user();

        $note = __($current_user->user_login." agrego un abono de: ".number_format_i18n($_POST[ 'partial_payment']));

        $_order = new WC_Order( $post_id );

        $_order->add_order_note( $note );

        $total_deposit  = $this->helper->get_sum_deposit($post_id);

        $_order->update_meta_data( '_total_due', ($_order->get_total() - $total_deposit)  );

        if ($total_deposit == $order_total) {

             $_order->update_status('completed');

        }

        $_order->save();

        //update_post_meta( $post_id, '_partial_payment', $_POST[ 'partial_payment' ] );
    }

    public function mv_add_other_fields_for_packaging(){

        global $post;

        $meta_field_data = $this->helper->get_sum_deposit($post->ID);

        echo '<input type="hidden" name="mv_other_meta_field_nonce" value="' . wp_create_nonce() . '">
        <p style="border-bottom:solid 1px #eee;padding-bottom:13px;">
        <input type="text" style="width:250px;";" name="partial_payment" placeholder="' . $meta_field_data . '" ></p>';


    }

    public function custom_admin_order_totals_deposit( $order_id ) {

        $label = __( 'Pago parcial total', 'woocommerce' );

        $label_due = __( 'Deuda', 'woocommerce' );

        $order_total = get_post_meta( $order_id, '_order_total', true );

        $sum_deposit = $this->helper->get_sum_deposit($order_id);

        $deposits = $this->helper->get_deposits($order_id);

        $i = 1;

        foreach ($deposits as $key => $value) {
            ?>
                <tr>
                    <td class="label">Deposito <?php echo $i; ?>:</td>
                    <td width="1%"></td>
                    <td class="deposit">$<?php echo number_format_i18n($value->deposit); ?></td>
                </tr>
            <?php
            $i++;
        }

        $apply_wholesale = get_post_meta( $order_id, '_wholesale', true );

        if ($apply_wholesale != null) {

             // Output
            ?>
                <tr>
                    <td class="label"><?php echo $label; ?>:</td>
                    <td width="1%"></td>
                    <td class="custom-total">$<?php echo number_format_i18n($sum_deposit); ?></td>
                </tr>
            <?php

            $due = $order_total - $sum_deposit;
            // Output
            ?>
                <tr>
                    <td class="label"><?php echo $label_due; ?>:</td>
                    <td width="1%"></td>
                    <td class="custom-total">$<?php echo number_format_i18n($due); ?></td>
                </tr>
            <?php
        }

    }

    public function error_notice() {
        ?>
        <div class="error notice">
            <p><?php _e( 'Error', 'custom_inventory_error' ); ?></p>
        </div>
        <?php
    }

    public function add_real_price_fields() {

        global $woocommerce, $post;

        $current_user = wp_get_current_user();

        $allowed_roles = array('administrator');

        if( array_intersect($allowed_roles, $current_user->roles ) ) {

            echo '<div class="options_group">';

            woocommerce_wp_text_input(
                array(
                    'id'          => '_real_price',
                    'label'       => __( 'Precio real', 'woocommerce' ),
                    'placeholder' => '$',
                    'desc_tip'    => 'true',
                    'description' => __( 'Ingrese el precio real.', 'woocommerce' )
                )
            );

            echo '</div>';
        }

    }

    public function add_real_price_fields_save($post_id){

        $real_price = $_POST['_real_price'];

        if( !empty( $real_price ) )

            update_post_meta( $post_id, '_real_price', esc_attr( $real_price ) );

    }

    public function custom_product_column($columns){


        $current_user = wp_get_current_user();

        $allowed_roles = array('administrator');

        if( array_intersect($allowed_roles, $current_user->roles ) ) {

            $columns['real_price'] = __( 'Precio real','woocommerce'); // title

            $columns['inventory'] = __( 'Inventario','woocommerce'); // title

            unset($columns['sku']);

            unset($columns['product_tag']);


        }

        return $columns;

    }

    public function custom_product_list_column_content($column, $product_id){

        setlocale(LC_MONETARY,"en_US");

        global $post;

        // HERE get the data from your custom field (set the correct meta key below)
        $real_price = get_post_meta( $product_id, '_real_price', true );

        switch ( $column )
        {
            case 'real_price' :
                echo money_format('%.2n', $real_price); // display the data
                break;

            case 'inventory':

                $product = wc_get_product($product_id);

                if ( $product->is_type( 'variable' ) ) {

                    $total = 0;

                    foreach ($product->get_available_variations() as  $variation) {

                            if (isset($variation['max_qty'])) {

                            $total = $total + ($variation['max_qty'] * $real_price);
                        }
                        //$total += ();
                        # code...
                    }

                    echo money_format('%.2n', $total);


                }else{

                    $total = $product->get_stock_quantity() * $real_price;

                    echo money_format('%.2n', $total);

                }
                break;
        }
    }

    public function user_identification_checkout_fields( $fields ) {

        /*$fields['billing']['billing_identification_number'] = array(
            'label'     => __('Identificacion', 'woocommerce'),
            'type'          => 'text',
            'placeholder'   => _x('Identificacion', 'placeholder', 'woocommerce'),
            'required'  => true,
            'class'     => array('form-row-first'),
            'enabled'   => true,
            //'order'     => 2,
            'show_in_email' => true,
            'show_in_order' => true,
            'priority'  => 30
        );

        $fields['billing']['billing_identification_type'] = array(
            'label'     => __('Tipo de identificacion', 'woocommerce'),
            'type'          => 'select',
            'placeholder'   => _x('Tipo de identificacion', 'placeholder', 'woocommerce'),
            'required'  => true,
            'class'     => array('form-row-wide'),
            'clear'     => true,
            'options'       => array(
                'blank'     => __( 'Select a day part', 'wps' ),
                'morning'   => __( 'In the morning', 'wps' ),
                'afternoon' => __( 'In the afternoon', 'wps' ),
                'evening'   => __( 'In the evening', 'wps' )
            )
        );*/



        return $fields;
   }

   public function my_custom_checkout_field_display_admin_order_meta($order){
        echo '<p><strong>'.__('Phone From Checkout Form').':</strong> ' . get_post_meta( $order->get_id(), '_shipping_phone', true ) . '</p>';
    }

    public function widget_product_categories_list_args( $args ) {

        $default_term_id = get_option( 'default_product_cat' );

        $cate = get_queried_object();

        $cateID = $cate->term_id;

        $term = get_term_children( $cateID, 'product_cat' );

        $term[] = $cateID;

        $args['include'] = $term;

        return $args;

    }

    public function verify_payment($order_id, $old_status, $new_status){

        $status = array();

        if (in_array($new_status, $status)) {
            # code...
        }

    }

    public function sv_woo_calc_my_discount_quickedit( $product_id ) {

        $product = wc_get_product( $product_id );


        if ($product->is_type( 'simple' )) {

            $regular  =  $product->get_regular_price();

            $sale = $product->get_sale_price();
        }
        elseif($product->is_type('variable')){

            $regular  =  $product->get_variation_regular_price( 'max', true );

            $sale = $product->get_variation_sale_price( 'max', true );
        }


        $discount = ($sale == '') ? 0 : round( 100 - ( $sale / $regular * 100), 2 );

        update_post_meta( $product_id, '_discount_amount', $discount );

    }


    public function custom_woocommerce_get_catalog_ordering_args( $args ) {
        $orderby_value = isset( $_GET['orderby'] ) ? wc_clean( $_GET['orderby'] ) : apply_filters( 'woocommerce_default_catalog_orderby', get_option( 'woocommerce_default_catalog_orderby' ) );
        if ( 'discount_percent' == $orderby_value ) {
            $args['orderby'] = 'meta_value';
            $args['order'] = 'DESC';
            $args['meta_key'] = '_discount_amount';
        }
        return $args;
    }

    public function custom_woocommerce_catalog_orderby( $sortby ) {
        $sortby['discount_percent'] = 'Descuento';
        return $sortby;
    }


    
    public function misha_default_catalog_orderby( $sort_by ) {
        return 'discount_percent';
    }

}

$class = new CustomInventory();

/*add_action( 'init', 'my_activation' );

function my_activation() {

    if (! wp_next_scheduled ( 'cancel_onhold_orders' )) {
        wp_schedule_event(time(), 'hourly', 'cancel_onhold_orders');
    }
}

add_action('cancel_onhold_orders', 'do_this_hourly');
add_action( 'init', 'do_this_hourly' );
*/
function do_this_hourly() {

    global $wpdb;

    $unpaid_orders = $wpdb->get_col( $wpdb->prepare( "
                    SELECT posts.ID
                    FROM {$wpdb->posts} AS posts
                    WHERE posts.post_status in ('wc-on-hold', 'wc-pending')
                    AND posts.post_date < %s limit 30
            ", date( 'Y-m-d H:i:s', strtotime('-3 days') ) ) );

//  var_dump($unpaid_orders);exit;
    if ( $unpaid_orders ) {

        foreach ( $unpaid_orders as $unpaid_order ) {
//echo $unpaid_order;Exit;
            $order = wc_get_order( $unpaid_order );
    //var_dump($order);exit;
            if (!$order) {

                continue;
            }

            if ( 'checkout' === $order->get_created_via()) {
                //Cancel Order
                $order->update_status( 'cancelled', __( 'Unpaid order cancelled - time limit reached.', 'woocommerce' ) );

                /*foreach ($order->get_items() as $item_id => $item) {
                    $product = $item->get_product();
                    $qty = $item->get_quantity(); // Get the item quantity
                    wc_update_product_stock($product, $qty, 'increase');
                }*/
            }
        }
    }
}

if(is_admin()){

    //add_action( 'init', 'do_this_hourly' );
}
//do_this_hourly();
