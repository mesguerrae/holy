<?php

require ABSPATH . '/vendor/autoload.php';

use Automattic\WooCommerce\Client;


/**
 * Class Drafts_List_Table.
 *
 * @since 0.1.0
 * @package Admin_Table_Tut
 * @see WP_List_Table
 */
class WC_Admin_List_Orders_Table_Orders extends \WP_List_Table {

    private $per_page = 25;
    private $total_orders;
    private $coupon;
    private $resume_data;
    private $net_revenue;
    private $avg_order_value;
    private $base_url = 'http://local.holy.com/';
    //private $base_url = 'http://holycosmetics.local.com:8080/';
   
    private $first_key  = 'ck_526e99a0813b843ad45cc62c0b3c248623c8e4cd';
    private $second_key = 'cs_20576c29c313f5b1c04c30866e7e67bf242b85a0';

	public function __construct() {
    
        parent::__construct(
            [
                'singular' => __( 'Order with coupon', 'sp' ),
                'plural' => __( 'Orders with coupon', 'sp' ),
                'ajax' => false
            ]
        );
        
        $this->coupon = get_the_author_meta( 'selected_admin_coupon', get_current_user_id() );
        if($this->coupon == '') {
            echo '<h3>' . __('NO coupon configured') . '</h3>';
            die();
        }
        $this->resume_data = $this->get_order_pagination_data();
        $this->total_orders = $this->resume_data->totals->orders_count;
        $this->net_revenue = $this->resume_data->totals->net_revenue;
        $this->avg_order_value = $this->resume_data->totals->avg_order_value;
        $this->total_orders = $this->resume_data->totals->orders_count;
    
    }


    function extra_tablenav( $which ) {
        global $woocommerce;
    

        if ( $which == "top" ){

            echo '<form id="search-coupon-form" method="GET" class="form-inline ">';

            echo '<input type="hidden" name="page" value="order-with-coupons"/>';
            echo '<label for="coupon" class="formbuilder-select-label">Coupon</label>';
            echo '<select  id="coupon" name="coupon">';
            echo '<option value="">Seleccione un cupon</option>';
            foreach(explode(',', $this->coupon) as $coupon_id){
        
                $c = new WC_Coupon($coupon_id);
                echo '<option value="'.$coupon_id.'" '. (($coupon_id == $_GET['coupon']) ? 'selected' : '') .'>' . $c->code. '</option>';
            }
               
            echo '</select>';

            
            
            echo '

                    <div class="formbuilder-select form-group field-status_is">
                        <label for="status_is" class="formbuilder-select-label">Order status</label>
                        <select class="form-control" name="status_is" id="status_is">
                            <option value="" id="status_is-0">Select order status</option>
                            <option value="processing" '.(('processing' == $_GET['status_is']) ? 'selected' : '').'>Processing</option>
                            <option value="completed" '.(('completed' == $_GET['status_is']) ? 'selected' : '').'>Completed</option>
                            
                            ';
                            
                            /*foreach(wc_get_order_statuses() as $status => $label) {
                                echo '<option value="'.$status.'" '.(($status == $_GET['status_is']) ? 'selected' : '').'>'.$label.'</option>';
                            }*/
                            
            echo '
                            
                        </select>
                    </div>
                    <div class="formbuilder-text form-group field-after">
                        <label for="after" class="formbuilder-text-label">From</label>
                        <input type="date" class="form-control" name="after" access="false" id="after" value="'. $_GET['after'].'">
                    </div>
                    <div class="formbuilder-text form-group field-before">
                        <label for="before" class="formbuilder-text-label">To</label>
                        <input type="date" class="form-control" name="before" access="false" id="before" value="'. $_GET['before'].'">
                    </div>

            ';

            echo '<button type="submit" id="search-buttom" >Buscar</button>';
            echo "</form>";

            if($_GET['coupon'] == '') {
                echo '<h3>' . __('Seleccione un cupon') . '</h3>';
                die();
            }

            if($this->total_orders == 0) {
                echo '<h3>' . __('No se encontraron ordenes') . '</h3>';
                die();
            }

            echo "<h3> Order Totals: $".number_format($this->net_revenue, 0, '.', ',' )."</h3>";
            echo "<h4> Order Count total: ".$this->total_orders."</h4>";
            echo "<h4> Order Average per order: $".number_format($this->avg_order_value, 0, '.', ',' )."</h4>";

           
        }


    
    }



    function get_columns() {

        return array(
            'order_date'=>__('Date', 'woocommerce'),
            'order_number'=>__('Order', 'woocommerce'),
            'order_status'=>__('Status', 'woocommerce'),
            'order_customer'=>__('Customer', 'woocommerce'),
            //'order_products'=>__('Products', 'woocommerce'),
            'order_items_sold'=>__('Items sold', 'woocommerce'),
            'order_coupons' => __('Coupons', 'woocommerce'),
            'shipping_cost' => __('Shipping', 'woocommerce'),
            'order_total' => __('Total', 'woocommerce')
        );

    }
    

    function column_default($item, $column_name)
    {
        return $item[$column_name];
    }


    function get_sortable_columns() {

        return $sortable = array(
        );

    }

    function no_items() {
        _e( 'No orders available.' );
    }

    function prepare_items() {
        
        $this->_column_headers = array($this->get_columns(), [], $this->get_sortable_columns());

        $this->set_pagination_args( array(
            'total_items' => $this->total_orders,
            'per_page'    => $this->per_page
        ) );
       
        $this->items = $this->get_orders_api();

    }

    function display_rows() {
       
        $orders = $this->items;
     
        $columns = $this->get_columns();

        $this->get_orders_api();
     
        if(!empty($orders)){
            
            foreach($orders as $order){

                echo '<tr id="record_'.$order->order_id.'">';

                foreach ( $columns as $column_name => $column_display_name ) {
            
                    //Style attributes for each col
                    $class = "class='$column_name column-$column_name'";
                    $style = "";
                    if ( in_array( $column_name, $hidden ) ) $style = ' style="display:none;"';
                    $attributes = $class . $style;
            
                    //edit link
                    $items = '';
                    foreach($order->extended_info->products as $product) {
                        $items .= $product->name . '(' . $product->quantity . ') ';
                    }
                    //Display the cell
                    switch ( $column_name ) {
                        case "order_date":  echo '<td '.$attributes.'>'.stripslashes($order->date_created_gmt).'</td>';   break;
                        case "order_number": echo '<td '.$attributes.'>'.stripslashes($order->order_id).'</td>'; break;
                        case "order_status": echo '<td '.$attributes.'>'.wc_get_order_status_name($order->status).'</td>'; break;
                        case "order_customer": echo '<td '.$attributes.'>'.$order->extended_info->customer->first_name . ' ' . $order->extended_info->customer->last_name.'</td>'; break;
                        case "shipping_cost": echo '<td '.$attributes.'>$'.number_format(($order->total_sales - $order->net_total), 0, '.', ',' ).'</td>'; break;
                        case "order_items_sold": echo '<td '.$attributes.'>'.$order->num_items_sold.'<button class="order-product-items" items="'.$items.'"> <span class="dashicons dashicons-open-folder"></span> </button></td>'; break;
                        case "order_coupons": echo '<td '.$attributes.'>'.$order->extended_info->coupons[0]->code.'</td>'; break;
                        case "order_total": echo '<td '.$attributes.'>$'.number_format($order->net_total,  0, '.', ',' ).'</td>'; break;
                    }
                }
     
                echo '</tr>';
            }
        }
    }

    function get_order_pagination_data() {
      
        $woocommerce = new Client(
            $this->base_url, 
            $this->first_key, 
            $this->second_key,
            [
                'version' => 'wc-analytics/reports',
            ]
        );
        $params = array(
            'coupon_includes'   => $_GET['coupon'],
            'match'             => 'all',
            'fields[0]'         => 'orders_count',
            'fields[1]'         => 'net_revenue',
            'fields[2]'         =>  'avg_order_value',
            'per_page'          => $this->per_page,
            'after'             => $_GET['after'] == '' ? '2020-01-01T00:00:00' : $_GET['after'].'T00:00:00',
            'before'            => $_GET['before'] == '' ? date('Y-m-d').'T00:00:00' : $_GET['before'].'T23:59:59',
            'status_is'         => $_GET['status_is'] == '' ?  array('completed', 'processing') : str_replace('wc-', '', $_GET['status_is']),

        );
        
        return $woocommerce->get('orders/stats', $params);
    }

    function get_orders_api() {

        $woocommerce = new Client(
            $this->base_url,  
            $this->first_key , 
            $this->second_key ,
            [
                'version' => 'wc-analytics/reports',
            ]
        );

        $params = array(
            'coupon_includes'       => $_GET['coupon'],
            'per_page'              => $this->per_page,
            'page'                  => $_GET['paged'] ?? 1,
            'match'                 => 'all',
            'extended_info'         => true,
            'order'                 => 'desc',
            'orderby'               => 'date',
            'after'             => $_GET['after'] == '' ? '2020-01-01T00:00:00' : $_GET['after'].'T00:00:00',
            'before'            => $_GET['before'] == '' ? date('Y-m-d').'T00:00:00' : $_GET['before'].'T23:59:59',
            'status_is'         => $_GET['status_is'] == '' ?  array('completed', 'processing') : str_replace('wc-', '', $_GET['status_is'])

        ); 


        return $woocommerce->get('orders', $params);
    
    }

}

