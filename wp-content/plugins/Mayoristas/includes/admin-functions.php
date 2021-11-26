<?php

add_filter( 'woocommerce_general_settings', 'add_success_wholesales_message' );

add_filter( 'woocommerce_reports_get_order_report_query',  'report_filter' );

add_action('parse_request', 'download_masive_products', 0);

add_action('parse_request', 'create_wholesale_user', 0);

add_action('init', 'add_endpoint');

add_filter( 'woocommerce_email_classes', 'add_wholesales_notification_order_woocommerce_email' );//_is_wholesales_order

add_filter('manage_edit-shop_order_columns', 'alter_order_columns');

add_action('manage_shop_order_posts_custom_column', 'alter_order_number_columns', 10, 2);


function alter_order_columns($columns) {

    $new_columns = ( is_array($columns) ) ? $columns : array();

    unset($new_columns['order_number']);

    $res_array = array_slice($new_columns, 0, 1, true) + array("custom_order_number" => "Order Number") +  array_slice($new_columns, 1, count($new_columns)-1, true);

    return $res_array;
}


function alter_order_number_columns($column) {

    global $post, $woocommerce, $the_order;

    if ($column === 'custom_order_number') {

      $buyer = '';

      if ( $the_order->get_billing_first_name() || $the_order->get_billing_last_name() ) {

        $buyer = trim( sprintf( _x( '%1$s %2$s', 'full name', 'woocommerce' ), $the_order->get_billing_first_name(), $the_order->get_billing_last_name() ) );

      } elseif ( $the_order->get_billing_company() ) {

        $buyer = trim( $the_order->get_billing_company() );

      } elseif ( $the_order->get_customer_id() ) {
          $user  = get_user_by( 'id', $the_order->get_customer_id() );

          $buyer = ucwords( $user->display_name );
      }

      $buyer = apply_filters( 'woocommerce_admin_order_buyer_name', $buyer, $the_order );

      if ( $the_order->get_status() === 'trash' ) {

          echo '<strong>#' . esc_attr( $the_order->get_order_number() ) . ' ' . esc_html( $buyer ) . '</strong>';

      } else {

          echo '<a href="#" class="order-preview" data-order-id="' . absint( $the_order->get_id() ) . '" title="' . esc_attr( __( 'Preview', 'woocommerce' ) ) . '">' . esc_html( __( 'Preview', 'woocommerce' ) ) . '</a>';

          echo '<a href="' . esc_url( admin_url( 'post.php?post=' . absint( $the_order->get_id() ) ) . '&action=edit' ) . '" class="order-view"><strong>#' . esc_attr( $the_order->get_order_number() ) . ' ' . esc_html( $buyer ) . '</strong></a>';

      }

      if(get_post_meta( $the_order->get_id(), '_is_wholesales_order', true )){

          echo '<span class="dashicons dashicons-groups" style="color:#0073aa;"></span>';
      }

      if(get_post_meta( $the_order->get_id(), '_has_wholesales_payment_support', true )){

          echo '<span class="dashicons dashicons-cart" style="color:#0073aa;"></span>';
      }





    }
}


function add_wholesales_notification_order_woocommerce_email( $email_classes ) {

    require( 'class-wc-wholesales-order-email.php' );

    $email_classes['WC_Wholesales_Notification_Order_Email'] = new WC_Wholesales_Notification_Order_Email();

    return $email_classes;

}

function add_success_wholesales_message( $settings ) {

    $updated_settings = array();

   foreach ( $settings as $section ) {

    if ( isset( $section['id'] ) && 'general_options' == $section['id'] &&
       isset( $section['type'] ) && 'sectionend' == $section['type'] ) {

      $updated_settings[] = array(
        'name'     => __( 'Texto pagina gracias mayoristas', 'wholesales_success_message' ),
        'desc_tip' => __( 'Texto success page mayoristas' ),
        'id'       => 'wholesales_success_message',
        'type'     => 'textarea',
        'css'      => 'min-width:300px;',
        'std'      => '1',  // WC < 2.0
        'default'  => 'Comunicate con nosotros',  // WC >= 2.0
      );

    $updated_settings[] = array(
        'name'     => __( 'Minimo para obtener descuento mayoristas', 'wholesales_required_amount' ),
        'desc_tip' => __( 'Minimo para obtener descuento mayoristas' ),
        'id'       => 'wholesales_required_amount',
        'type'     => 'text',
        'css'      => 'min-width:300px;',
        'std'      => '1',  // WC < 2.0
        'default'  => '400000',  // WC >= 2.0
      );
    $updated_settings[] = array(
        'name'     => __( 'Email mayoristas', 'wholesales_email' ),
        'desc_tip' => __( 'Email mayoristas' ),
        'id'       => 'wholesales_email',
        'type'     => 'text',
        'css'      => 'min-width:300px;',
        'std'      => '1',  // WC < 2.0
        'default'  => 'aaaaaa@aaaaa.com',  // WC >= 2.0
      );

     $updated_settings[] = array(
        'name'     => __( 'Mensaje de rechazo email mayoristas', 'wholesales_failed_message' ),
        'desc_tip' => __( 'Mensaje de rechazo email mayoristas' ),
        'id'       => 'wholesales_failed_message',
        'type'     => 'textarea',
        'css'      => 'min-width:300px;',
        'std'      => '1',  // WC < 2.0
        'default'  => 'Hola, Tu solicitud para ser un mayorista fue rechazada, para mas informacion comunicate con nosotros al 3013156284',  // WC >= 2.0
      );

    $updated_settings[] = array(
        'name'    => __( 'Mayoristas - activar TC', 'woocommerce' ),
        'desc'    => __( 'Permite activar tarjeta de credito para compras mayoristas', 'woocommerce' ),
        'id'      => 'wholesales_credit_cart_payment',
        'css'     => 'min-width:150px;',
        'std'     => 'left', // WooCommerce < 2.0
        'default' => 'left', // WooCommerce >= 2.0
        'type'    => 'select',
        'options' => array(
          'yes'        => __( 'Si', 'woocommerce' ),
          'no'       => __( 'No', 'woocommerce' ),

        ),
        'desc_tip' =>  true,
      );

    $updated_settings[] = array(
        'name'    => __( 'Mayoristas - activar EFECTIVO', 'woocommerce' ),
        'desc'    => __( 'Permite activar pagos en EFECTIVO para compras mayoristas', 'woocommerce' ),
        'id'      => 'wholesales_cash_payment',
        'css'     => 'min-width:150px;',
        'std'     => 'left', // WooCommerce < 2.0
        'default' => 'left', // WooCommerce >= 2.0
        'type'    => 'select',
        'options' => array(
          'yes'        => __( 'Si', 'woocommerce' ),
          'no'       => __( 'No', 'woocommerce' ),

        ),
        'desc_tip' =>  true,
      );

    $updated_settings[] = array(
        'name'    => __( 'Mayoristas - activar PSE', 'woocommerce' ),
        'desc'    => __( 'Permite activar PSE para compras mayoristas', 'woocommerce' ),
        'id'      => 'wholesales_bank_transfer_payment',
        'css'     => 'min-width:150px;',
        'std'     => 'left', // WooCommerce < 2.0
        'default' => 'left', // WooCommerce >= 2.0
        'type'    => 'select',
        'options' => array(
          'yes'        => __( 'Si', 'woocommerce' ),
          'no'       => __( 'No', 'woocommerce' ),

        ),
        'desc_tip' =>  true,
      );

      $updated_settings[] = array(
        'name'    => __( 'Mayoristas - activar PDF', 'woocommerce' ),
        'desc'    => __( 'Permite activar el boton de catalogo PDF', 'woocommerce' ),
        'id'      => 'wholesales_pdf',
        'css'     => 'min-width:150px;',
        'std'     => 'left', // WooCommerce < 2.0
        'default' => 'left', // WooCommerce >= 2.0
        'type'    => 'select',
        'options' => array(
            1       => __( 'Si', 'woocommerce' ),
            0       => __( 'No', 'woocommerce' ),

        ),
        'desc_tip' =>  true,
      );

      $updated_settings[] = array(
        'name'    => __( 'Mayoristas - activar EXCEL', 'woocommerce' ),
        'desc'    => __( 'Permite activar el boton de catalogo EXCEL', 'woocommerce' ),
        'id'      => 'wholesales_excel',
        'css'     => 'min-width:150px;',
        'std'     => 'left', // WooCommerce < 2.0
        'default' => 'left', // WooCommerce >= 2.0
        'type'    => 'select',
        'options' => array(
          1        => __( 'Si', 'woocommerce' ),
          0       => __( 'no', 'woocommerce' ),

        ),
        'desc_tip' =>  true,
      );
    }

    $updated_settings[] = $section;
  }

  return $updated_settings;
}

function report_filter($query){

    $query['where'] = $query['where'] . ' AND NOT EXISTS (SELECT * FROM wp_postmeta WHERE post_id = posts.ID AND meta_key = \'_is_wholesales_order\')';

    return $query;

}

function download_masive_products(){

    global $wp;

    $endpoint_vars = $wp->query_vars;

    if ($wp->request == 'downloadProducts/wholesales') {

        //if( current_user_can('editor') || current_user_can('administrator') ) {

            $args = array(
            //    'category' => array( 34 ),
                'orderby'  => 'name',
                'posts_per_page' => -1,
                'post_status' => 'publish'
            );
            //var_dump($args);exit;
            $products = wc_get_products( $args );

            $f = fopen('php://memory', 'w');

            $titles = array('product_id',  'product_name',  'product_price','special_price','inventory','min_val','max_val','discount','type','from','to','user_role','never_expire', 'discount_type', 'qty','label');

            fputcsv($f,$titles,';');

            foreach ($products as $product) {


                if ($product->is_type( 'simple' )) {
                    $sale_price     =  $product->get_sale_price();
                    $regular_price  =  $product->get_regular_price();
                    $stock = $product->get_stock_quantity();
                }
                elseif($product->is_type('variable')){
                    $sale_price     =  $product->get_variation_sale_price( 'min', true );
                    $regular_price  =  $product->get_variation_regular_price( 'max', true );
                    $stock = wc_get_variable_product_stock_quantity('raw',$product->get_id());
                }

                $discount_type = get_post_meta($product->get_id(), 'phoen_woocommerce_discount_type', true);


                if (empty(get_post_meta($product->get_id(), 'phoen_woocommerce_discount_mode',true))) {

                    $data = array($product->get_id(), $product->get_name(),$regular_price,$sale_price  , $stock ,0,0,0,'amount',null,null,'wholesaler',1);

                    fputcsv($f,$data,';');

                }else{

                    if (empty($discount_type) || $discount_type == 'qty') {

                        $discount_type = 'qty';

                        foreach (get_post_meta($product->get_id(), 'phoen_woocommerce_discount_mode', true) as $key => $discounts) {

                            $data = array($product->get_id(), $product->get_name(),$regular_price,$sale_price,$stock ,$discounts['min_val'],$discounts['max_val'],$discounts['discount'],$discounts['type'],$discounts['from'],$discounts['to'],implode(',', $discounts['user_role']),$discounts['never_expire'], $discount_type);

                            fputcsv($f,$data,';');

                        }

                    }else{

                        foreach (get_post_meta($product->get_id(), 'phoen_woocommerce_discount_mode', true) as $key => $discounts) {

                            $data = array($product->get_id(), $product->get_name(),$regular_price,$sale_price,$stock ,$discounts['min_price'],$discounts['max_price'],$discounts['discount_price'],'',$discounts['from'],$discounts['to'],implode(',', $discounts['user_role']),$discounts['never_expire'], $discount_type, $discounts['qty'],$discounts['label']);

                            fputcsv($f,$data,';');

                        }
                    }
                }
            }

            fseek($f, 0);

            header('Content-Type: application/csv');

            header('Content-Disposition: attachment; filename="productos.csv";');

            fpassthru($f);

            die();
        //}
    }


}


function create_wholesale_user(){

    global $wp;

    $endpoint_vars = $wp->query_vars;

    if ($wp->request == 'wholesales/createuser') {

        if ($_GET['email']) {

            $email_address = $_GET['email'];

            $phone = $_GET['phone'];

            $name = $_GET['name'];



            if( null == username_exists( $email_address ) ) {

                $password = wp_generate_password( 12, true );

                $user_id = wp_create_user ( $email_address, $password, $email_address );

                wp_update_user(

                array(
                    'ID'       => $user_id,
                    'nickname' => $email_address
                  )
                );

                $user = new WP_User( $user_id );

                $user->set_role( 'wholesaler' );

                $user->save();

                wp_update_user( array( 'ID' => $user_id, 'first_name' => $name) );

                update_user_meta( $user_id, 'billing_phone', $phone );

                $subject = '¡Bienvenido Mayorista!';

                $content = 'Hola '.$name.', Ahora puedes realizar tus compras por mayor, no olvides estar logueado antes de hacer tus compras para que sean efectivos los precios especiales.';

                $content .= '<br><br>';

                $content .= '<strong>Tu contraseña es: '.$password.'</strong>';

                $content .= '<br><br>';

                $content .= 'Te recomendamos cambiar tu contraseña a una de tu preferencia.';

                send_custom_email($email_address,$subject,$content,'¡Bienvenido!');

                header("Location: ". admin_url("/user-edit.php?user_id=".$user_id) );

                exit;
            }
        }

    }else if ($wp->request == 'wholesales/denegateuser'){

        if ($_GET['email']) {

            $email_address = $_GET['email'];

            $subject = '¡Ups lo sentimos!';

            $content = get_option('wholesales_failed_message');

            send_custom_email($email_address,$subject,$content,'Información:');
        }

        wp_redirect( admin_url() );

        die();
    }


}

function add_endpoint()
{

    add_rewrite_endpoint('wholesales', EP_PERMALINK | EP_PAGES, true);

    add_rewrite_endpoint('downloadProducts/wholesales', EP_PERMALINK | EP_PAGES, true);

}