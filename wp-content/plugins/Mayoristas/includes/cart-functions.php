<?php

add_filter( 'woocommerce_package_rates', 'sb_woocommerce_set_free_shipping_for_certain_users', 10, 2);

add_action('woocommerce_check_cart_items', 'validate_all_cart_contents');


function sb_woocommerce_set_free_shipping_for_certain_users( $rates, $package ) {

    get_currentuserinfo();

    $user = wp_get_current_user();

    if (!in_array('wholesaler', (array) $user->roles)) {

        return $rates;
    }

    global $current_user;

    $currentAmount = (Int) WC()->cart->cart_contents_total;

    $minimunAmount = get_option( 'wholesales_required_amount', 1 );

    if ($current_user->ID) {
        $user_roles = $current_user->roles;
        $user_role = array_shift($user_roles);

        if ($user_role == 'wholesaler') {

            $discount_type = 'qty';

            foreach (WC()->cart->cart_contents as $cart_content_product) {

                $product_id = $cart_content_product['product_id'];
                $discount_type = get_post_meta($product_id, 'phoen_woocommerce_discount_type',true);

            }

            if ($discount_type == 'price') {

                foreach ($rates as $key => $value) {
                    if ($value->method_id !=  'free_shipping') {
                        $rates[$key]->cost = 0;
                        break;
                    }
                }
            }else{

                if ($minimunAmount < $currentAmount ) {

                    /*foreach ($rates as $key => $value) {
                        if ($value->method_id ==  'free_shipping') {
                            unset($rates[$key]);
                            break;
                        }
                    }*/
                    foreach ($rates as $key => $value) {
                        if ($value->method_id !=  'free_shipping') {
                            $rates[$key]->cost = 0;
                            break;
                        }
                    }
                }/*else{
                    foreach ($rates as $key => $value) {
                        if ($value->method_id ==  'free_shipping') {
                            $freeshipping = $rates[$key];
                            $rates = array();
                            $rates[] = $freeshipping;
                            break;
                        }
                    }
                }*/


            }







        } else {
            foreach ($rates as $key => $value) {

                if ($value->method_id ==  'free_shipping') {
                    unset($rates[$key]);
                    break;
                }
            }

        }
    }else{

        foreach ($rates as $key => $value) {

            if ($value->method_id ==  'free_shipping') {
                unset($rates[$key]);
                break;
            }
        }
    }
    return $rates;
}


function validate_all_cart_contents(){

    global $woocommerce;
	
    $total = $woocommerce->cart->total;

    $user = wp_get_current_user();

    if (in_array('wholesaler', (array) $user->roles) && is_checkout()) {
 

        WC()->cart->calculate_totals();

        foreach ( WC()->cart->cart_contents as $cart_content_product ) {

            $val = get_post_meta($cart_content_product['product_id'], 'phoen_woocommerce_discount_mode', true);

            $val_type = get_post_meta($cart_content_product['product_id'], 'phoen_woocommerce_discount_type', true);

            if ($val && $val_type == 'price') {

                $error_products = array();

                for ($i = 0; $i < count($val); $i++) {
                    
                    $orgPrice = get_post_meta($cart_content_product ['product_id'] , '_price', true);

                    //$orgPrice = intval($cart_content_product['data']->get_price());
			
                    $phoen_minval = isset($val[$i]['min_price']) ? $val[$i]['min_price'] : "";

                    $phoen_maxval = isset($val[$i]['max_price']) ? $val[$i]['max_price'] : "";

                    $phoen_from = isset($val[$i]['from']) ? $val[$i]['from'] : "";

                    $phoen_from = strtotime($phoen_from);

                    $phoen_to = isset($val[$i]['to']) ? $val[$i]['to'] : "";

                    $phoen_to = strtotime($phoen_to);

                    $current_date = strtotime(date("d-m-Y"));

                    $crr_user_roles = wp_get_current_user()->roles;

                    $user_role = isset($crr_user_roles[0]) ? $crr_user_roles[0] : '';

                    $phoen_user_role = isset($val[$i]['user_role']) ? $val[$i]['user_role'] : "";

                    if (!empty($phoen_user_role)) {

                        if (in_array($user_role, $phoen_user_role)) {
                            $phoenvar = 5;
                        } else {
                            $phoenvar = 2;
                        }

                    } else {
                        $phoenvar = 5;
                    }
                    $product_never_expire = $val[$i]['never_expire'];
                    
                    $error_products = array();

                    if (((($current_date >= $phoen_from) && ($current_date <= $phoen_to)) || $product_never_expire == '1') && $phoenvar === 5) {
			
                        if ((($total >= $phoen_minval) && ($total <= $phoen_maxval)) || $total < $phoen_minval) {

                            $founded_price = $orgPrice - $val[$i]['discount_price'];

                            $current_price = $cart_content_product['wholesales_price_discount'];

                            if ($founded_price > $current_price) {

                                $selected_discount = $orgPrice - $cart_content_product['wholesales_price_discount'];

                                $founded_discount_id = array_search($selected_discount, array_column($val, 'discount_price'));

                                $founded_discount = $val[$founded_discount_id];

                                $range_min = $founded_discount['min_price'];

                                $range_max = $founded_discount['max_price'];

                                $error_products[] = array(
                                    'id' => $cart_content_product['product_id'],
                                    'product' => $cart_content_product['data']->get_name(),
                                    'url' => get_permalink( $cart_content_product['product_id']),
                                    'min' => $range_min,
                                    'max' => $range_max
                                );
				
                                break;
                            }

                        }

                    }

                }

                if (count($error_products)>0) {

                    setlocale(LC_MONETARY, 'en_US');

                    $error_text = '<ul>';

                    foreach ($error_products as $key => $product) {

                        $min_required_price = money_format('%.0n',$product['min']);

                        $error_text .= '<li><a href ="'.wc_get_cart_url().'">'.$product['product'].'</a>: El valor minimo de orden debe ser de <strong>'.  $min_required_price. '</strong> para aplicar el precio seleccionado.</li>';
                    }
                    echo '</ul><h2>Algunos productos no cumplen con el valor mínimo de compra para aplicar al precio de mayorista seleccionado:</h2><br>'.
                        $error_text
                        .'' ;



                    wc_add_notice(' ', 'error' );

                }

            }

        }
    }
}
