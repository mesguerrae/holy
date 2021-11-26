<?php

/**
 *
 */
namespace admin;

use includes\Api;
use admin\events\CreateProductOnAlegra;
use admin\events\CreateContact;
use admin\events\CreateInvoice;
use admin\events\SendPayment;

class loadBulks
{
	protected $api;

	function __construct()
	{

		#hook que coloca la accion de crear usuario alegra
		add_filter( 'bulk_actions-users', array($this, 'alegra_contact_action') );
		#hook que recibe la accion de crear usuario alegra
		add_filter( 'handle_bulk_actions-users', array($this, 'contact_action_handler') , 10, 3 );
		#hook que coloca mensaje de succes al crear ususario alegra
		add_action( 'admin_notices', array($this,'contact_action_admin_notice') );


		#hook que coloca la accion de generar factura  en ordenes
		add_filter( 'bulk_actions-edit-shop_order', array($this, 'alegra_invoice_action'), 20, 1 );
		#hook que recibe la accion de generar factura en ordenes
		add_filter( 'handle_bulk_actions-edit-shop_order', array($this, 'invoice_action_handler') , 10, 3 );
		#hook que coloca mensaje de succes al procesar facturas
		add_action( 'admin_notices', array($this,'noniva_invoice_action_admin_notice') );

		#hook que coloca opcion de crear productos en alegra
		add_filter( 'bulk_actions-edit-product', array($this, 'register_product_action') );
		#hook que procesa los productos que se deben crear en alegra
		add_filter( 'handle_bulk_actions-edit-product', array($this, 'product_handler') , 10, 3 );

		#hook que actualiza el stock con alegra
		add_filter( 'handle_bulk_actions-edit-product', array($this, 'product_handler_stock') , 10, 3 );
		#hook que coloca mensaje de success al procesar productos en alegra
		add_action( 'admin_notices', array($this,'product_action_admin_notice') );
		#hook que actualiza el inventario en alegra al crear orden
		//add_action('woocommerce_thankyou', array($this, 'send_invoice_alegra'), 10 ,1 );
		#agregar campo en admin ordenes
		add_filter( 'manage_edit-shop_order_columns', array($this,'alegra_shop_order_column'), 20 );
		#agregrar data de campo order id en listado de ordenes
		add_action( 'manage_shop_order_posts_custom_column' , array($this,'alegra_custom_orders_list_column_content'), 20, 2 );

		#agregar campo en admin ordenes
		add_filter( 'manage_edit-product_columns', array($this,'alegra_product_column'), 20 );
		#agregrar data de campo order id en listado de ordenes
		add_action( 'manage_product_posts_custom_column' , array($this,'alegra_custom_product_list_column_content'), 20, 2 );
		#hook que agrega configuracion del plugin en woocommerce
		add_filter( 'woocommerce_general_settings', array($this,'add_alegra_config') );

		#hook que envia a alegra el pago de las ordenes pendientes de pago
		add_action( 'woocommerce_order_status_changed', array( $this, 'send_order_payment' ), 20, 4 );

		#agrega campo id alegra productos
		add_action( 'woocommerce_variation_options_pricing', array( $this, 'alegra_item_id_field'), 10, 3 );

		#guardar campo de id item alegra
		add_action( 'woocommerce_save_product_variation', array( $this, 'alegra_item_id_save_field'), 10, 2 );

		//agrega campo id alegra productos simples
		add_action('woocommerce_product_options_general_product_data',  array( $this,'woocommerce_product_alegra_item_id_simple') );
		// guardar campo de id item alegra producto simple
		add_action('woocommerce_process_product_meta', array( $this, 'woocommerce_product_alegra_item_id_save') );


		$this->api = new Api();

	}

	public function woocommerce_product_alegra_item_id_simple(){

		global $woocommerce, $post;

		$product = wc_get_product($post->ID);

		if ( $product->is_type( 'variable' ) ) {
			return;
		}

		echo '<div class="product_custom_field">';
			woocommerce_wp_text_input(
				array(
					'id' => 'alegra_item_id',
					'placeholder' => 'Id item de alegra',
					'label' => __('Id item de alegra', 'woocommerce'),
					'desc_tip' => 'true',
					'value' => get_post_meta( $post->ID, 'alegra_item_id', true )
				)
			);
		echo '</div>';

	}

	public function woocommerce_product_alegra_item_id_save($post_id){

		$woocommerce_custom_product_text_field = $_POST['alegra_item_id'];

    	if (!empty($woocommerce_custom_product_text_field))
        	update_post_meta($post_id, 'alegra_item_id', esc_attr($woocommerce_custom_product_text_field));
	}

	public function alegra_item_id_save_field($variation_id, $i){

		$alegra_item_id = $_POST['alegra_item_id'][$i];

		if ( isset( $alegra_item_id ) ) update_post_meta( $variation_id, 'alegra_item_id', esc_attr( $alegra_item_id ) );
	}

	public function alegra_item_id_field( $loop, $variation_data, $variation ) {

		woocommerce_wp_text_input( array(
			'id' => 'alegra_item_id[' . $loop . ']',
			'wrapper_class' => 'form-row form-row-full',
			'label' => __( 'Id item alegra', 'woocommerce' ),
			'value' => get_post_meta( $variation->ID, 'alegra_item_id', true )
			)
		);

	 }

	public function alegra_custom_product_list_column_content($column, $post_id){

		switch ( $column ){
	        case 'alegra_id' :

		        $product = wc_get_product($post_id);

				if ($product->is_type( 'variable' )) {


					$available_variations = $product->get_available_variations();

					$ids = '';

					foreach ($available_variations as $key => $variation) {

						$item_id = get_post_meta( $variation['variation_id'], 'alegra_item_id', true );

						$ids .= " $item_id,";
					}

					if($ids != '')

		                echo $ids;

		            else
		                echo '<small>(<em>no value</em>)</small>';

		            break;

				}else{

					$item_id = get_post_meta( $post_id, 'alegra_item_id', true );


		            if(!empty($item_id))
		                echo $item_id;

		            else
		                echo '<small>(<em>no value</em>)</small>';

		            break;


				}

	    }
	}

	public function alegra_product_column($columns){

	    $reordered_columns = array();

	    foreach( $columns as $key => $column){
	        $reordered_columns[$key] = $column;
	        if( $key ==  'price' ){

	            $reordered_columns['alegra_id'] = __( 'ID Alegra','theme_domain');
	        }
	    }
	    return $reordered_columns;
	}

	public function alegra_custom_orders_list_column_content($column, $post_id){

		switch ( $column ){
	        case 'alegra_id' :

	            $invoice_id = get_post_meta( $post_id, 'alegra_invoice_id', true );
	            if(!empty($invoice_id))
	                echo $invoice_id;

	            else
	                echo '<small>(<em>no value</em>)</small>';

	            break;
	    }
	}

	public function alegra_shop_order_column($columns){

	    $reordered_columns = array();

	    // Inserting columns to a specific location
	    foreach( $columns as $key => $column){
	        $reordered_columns[$key] = $column;
	        if( $key ==  'order_status' ){
	            // Inserting after "Status" column
	            $reordered_columns['alegra_id'] = __( 'ID Alegra','theme_domain');
	        }
	    }
	    return $reordered_columns;
	}

	public function send_order_payment($order_id, $old_status, $new_status, $order){

		if ($order_id) {
			$order = wc_get_order( $order_id );
		}



		if ($new_status == 'processing') {

			wp_queue()->push( new CreateInvoice( $order_id ) );
			//wp_queue()->push( new SendPayment( $order_id ) );
		}


	}

	public function alegra_contact_action(){

		$bulk_actions['alegra_contact'] = __( 'Crear usuario alegra', 'alegra_contact');

  		return $bulk_actions;
	}

	public function contact_action_handler($redirect_to, $doaction, $urser_ids){

		global $wpdb;

		if ( $doaction !== 'alegra_contact' ) {
			return $redirect_to;
		}


		foreach ( $urser_ids as $user_id ) {

			wp_queue()->push( new CreateContact( $user_id ) );

		}

		$redirect_to = add_query_arg( 'alegra_concat_success', count( $urser_ids ), $redirect_to );

		return $redirect_to;
	}

	public function contact_action_admin_notice(){

		if ( ! empty( $_REQUEST['alegra_concat_success'] ) ) {
		    $emailed_count = intval( $_REQUEST['alegra_concat_success'] );
		    $result = 1;
		    printf( '<div id="message" class="updated fade">Usuarios en proceso de creacion!</div>', $result );
		  }
	}



	public function add_alegra_config( $settings ) {

        $updated_settings = array();

       foreach ( $settings as $section ) {

        if ( isset( $section['id'] ) && 'general_options' == $section['id'] &&
           isset( $section['type'] ) && 'sectionend' == $section['type'] ) {

          $updated_settings[] = array(
            'name'     => __( 'Alegra intervalo ordenes con iva', 'alegra_order_inv' ),
            'desc_tip' => __( '' ),
            'id'       => 'alegra_order_inv',
            'type'     => 'number',
            'css'      => 'min-width:300px;',
            'std'      => '1',  // WC < 2.0
            'default'  => '5',  // WC >= 2.0
          );


        }

        $updated_settings[] = $section;
      }

      return $updated_settings;
    }

	public function send_invoice_alegra($order_id){
		if (!$order_id) {
	        return;
	    }

	    wp_queue()->push( new CreateInvoice( $order_id ) );
		//wp_queue()->push( new UpdateProductStockOnAlegra( $order_id ) );
		return;

	}

	public function alegra_invoice_action($bulk_actions){

		$bulk_actions['alegra_invoice'] = __( 'Generar factura alegra', 'woocommerce');

  		return $bulk_actions;
	}

	public function invoice_action_handler($redirect_to, $doaction, $post_ids){

		global $wpdb;

		if ( $doaction !== 'alegra_invoice' ) {
			return $redirect_to;
		}


		foreach ( $post_ids as $order_id ) {

			wp_queue()->push( new CreateInvoice( $order_id ) );

		}

		$redirect_to = add_query_arg( 'bulk_non_inva_invoice_posts', count( $post_ids ), $redirect_to );

		return $redirect_to;
	}

	public function noniva_invoice_action_admin_notice(){

		if ( ! empty( $_REQUEST['bulk_non_inva_invoice_posts'] ) ) {
		    $emailed_count = intval( $_REQUEST['bulk_non_inva_invoice_posts'] );
		    $result = 1;
		    printf( '<div id="message" class="updated fade">Realize la tarea</div>', $result );
		  }
	}

	public function register_product_action(){

		$bulk_actions['create_product'] = __( 'Crear producto alegra', 'non_iva_invoice');

		$bulk_actions['update_stock_alegra'] = __( 'Actualizar stock alegra', 'non_iva_invoice');

  		return $bulk_actions;
	}

	public function product_handler_stock($redirect_to, $doaction, $post_ids){

		if ( $doaction !== 'update_stock_alegra' ) {
			return $redirect_to;
		}

		global $wpdb;

		$api = new Api;

		foreach ( $post_ids as $productId ) {

			$product = wc_get_product($productId);

			if ($product->is_type( 'variable' )) {

				$available_variations = $product->get_available_variations();


				foreach ($available_variations as $key => $variation) {

					$productId = $variation['variation_id'];

					$alegra_id = get_post_meta($productId,'alegra_item_id',true);

					$uri =  'items/'.$alegra_id;

					$data = array();

			    	$result = $api->get($uri, $data);

			    	$result = json_decode($result);

			    	$alegraStock = $result->inventory->availableQuantity;

		            $currentStock = get_post_meta($productId, '_stock', true);

		            alegra_log('----------UPDATE PRODUCT '.$productId.'------------','products');

		            alegra_log('ALEGRA STOCK:'. $alegraStock,'products');

		            alegra_log('CURRENT STOCK:'. $currentStock,'products');
					
					if ($alegraStock <= 0) {

		                alegra_log('ENTRE 1','products');

						$stock_status = 'outofstock';

		                update_post_meta( $productId, '_manage_stock', 'yes' );

		                update_post_meta($productId, '_stock', $alegraStock);

		                update_post_meta( $productId, '_stock_status', wc_clean( $stock_status ) );

					}else if ($currentStock < $alegraStock) {

		                alegra_log('ENTRE 2','products');

		                $currentOnHoldProduct = $wpdb->get_row( "
		                        SELECT oim_product.meta_value as product_id, sum(oim_qty.meta_value) as qty, `order`.post_status FROM wp_woocommerce_order_items oi
		                        join `wp_woocommerce_order_itemmeta` oim_qty on (oi.order_item_id = oim_qty. order_item_id and oim_qty.meta_key = '_qty')
		                        join `wp_woocommerce_order_itemmeta` oim_product on (oi.order_item_id = oim_product.order_item_id and oim_product.meta_key = '_product_id')
		                        join `wp_posts` as `order` on (oi.order_id = `order`.ID)
		                        and `order`.post_status like '%on-hold'
		                        where oim_product.meta_value = ".$productId."
		                        group by 1
		                    ", ARRAY_A );

						$stock_status = 'instock';

		                if (isset($currentOnHoldProduct['qty'])) {

		                    alegra_log('currentOnHoldProduct: '. $currentOnHoldProduct['qty'],'products');

		                    $stock = $alegraStock - $currentOnHoldProduct['qty'];

							if($stock == 0) {

								$stock_status = 'outofstock';
		
								alegra_log('updateProductStock('.$productId.'): zero ','products');
							}

		                    alegra_log('DIFERENCIA: '.  $stock,'products');


		                }else{

		                    alegra_log('no existe registro','products');

		                    $stock = $alegraStock;
		                }

						update_post_meta( $productId, '_manage_stock', 'yes' );

						update_post_meta($productId, '_stock', $stock);

						update_post_meta( $productId, '_stock_status', wc_clean( $stock_status ) );


					}else{

		                alegra_log('ENTRE 3','products');

		                $stock_status = 'instock';

		                update_post_meta( $productId, '_manage_stock', 'yes' );

		                update_post_meta($productId, '_stock', $alegraStock);

		                update_post_meta( $productId, '_stock_status', wc_clean( $stock_status ) );
	            	}
	            }

			}else{

				$alegra_id = get_post_meta($productId,'alegra_item_id',true);

				$uri =  'items/'.$alegra_id;

				$data = array();

		    	$result = $api->get($uri, $data);

		    	$result = json_decode($result);

		    	$alegraStock = $result->inventory->availableQuantity;

	            $currentStock = get_post_meta($productId, '_stock', true);

	            alegra_log('----------UPDATE PRODUCT '.$productId.'------------','products');

	            alegra_log('ALEGRA STOCK:'. $alegraStock,'products');

	            alegra_log('CURRENT STOCK:'. $currentStock,'products');

				if ($alegraStock == 0) {

	                alegra_log('ENTRE 1','products');

					$stock_status = 'outofstock';

	                update_post_meta( $productId, '_manage_stock', 'yes' );

	                update_post_meta($productId, '_stock', $alegraStock);

	                update_post_meta( $productId, '_stock_status', wc_clean( $stock_status ) );

				}else if ($currentStock < $alegraStock) {

	                alegra_log('ENTRE 2','products');

	                $currentOnHoldProduct = $wpdb->get_row( "
	                        SELECT oim_product.meta_value as product_id, sum(oim_qty.meta_value) as qty, `order`.post_status FROM wp_woocommerce_order_items oi
	                        join `wp_woocommerce_order_itemmeta` oim_qty on (oi.order_item_id = oim_qty. order_item_id and oim_qty.meta_key = '_qty')
	                        join `wp_woocommerce_order_itemmeta` oim_product on (oi.order_item_id = oim_product.order_item_id and oim_product.meta_key = '_product_id')
	                        join `wp_posts` as `order` on (oi.order_id = `order`.ID)
	                        and `order`.post_status like '%on-hold'
	                        where oim_product.meta_value = ".$productId."
	                        group by 1
	                    ", ARRAY_A );

	                if (isset($currentOnHoldProduct['qty'])) {

	                    alegra_log('currentOnHoldProduct: '. $currentOnHoldProduct['qty'],'products');

	                    $stock = $alegraStock - $currentOnHoldProduct['qty'];

	                    alegra_log('DIFERENCIA: '.  $stock,'products');


	                }else{

	                    alegra_log('no existe registro','products');

	                    $stock = $alegraStock;
	                }

					$stock_status = 'instock';

	                update_post_meta( $productId, '_manage_stock', 'yes' );

	                update_post_meta($productId, '_stock', $stock);

	                update_post_meta( $productId, '_stock_status', wc_clean( $stock_status ) );


				}else{

	                alegra_log('ENTRE 3','products');

	                $stock_status = 'instock';

	                update_post_meta( $productId, '_manage_stock', 'yes' );

	                update_post_meta($productId, '_stock', $alegraStock);

	                update_post_meta( $productId, '_stock_status', wc_clean( $stock_status ) );
	            }

			}


		}

		$redirect_to = add_query_arg( 'create_product_alegra', count( $post_ids ), $redirect_to );

		return $redirect_to;
	}

	public function product_handler($redirect_to, $doaction, $post_ids){

		if ( $doaction !== 'create_product' ) {
			return $redirect_to;
		}

		foreach ( $post_ids as $post_id ) {

			wp_queue()->push( new CreateProductOnAlegra( $post_id ) );

		}

		$redirect_to = add_query_arg( 'create_product_alegra', count( $post_ids ), $redirect_to );

		return $redirect_to;
	}

	public function get_product_category($id){

		$primary_cat_id=get_post_meta($id,'_yoast_wpseo_primary_product_cat',true);
        if($primary_cat_id){
            $product_cat = get_term($primary_cat_id, 'product_cat');
        if(isset($product_cat->name))
            $category =  $product_cat->name;
        }
	}

	public function product_action_admin_notice(){

		if ( ! empty( $_REQUEST['create_product_alegra'] ) ) {
		    $emailed_count = intval( $_REQUEST['create_product_alegra'] );
		    $result = 1;
		    printf( '<div id="message" class="updated fade">Se estan creando los productos en alegra</div>', $result );
		  }
	}
}


$loadBulks =  new loadBulks();