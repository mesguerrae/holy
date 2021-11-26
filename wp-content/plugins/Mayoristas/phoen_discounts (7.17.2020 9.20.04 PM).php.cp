<?php

/*
 ** Plugin Name:Mayoristas

 ** Description: Plugin hecho para todas las promociones y gestion de mayoristas. 
 
 ** Version: 1.0.0
 
 ** Author: Nicolas Lopez
 
 ** Text Domain:Descuento Mayoristas

 
 ** WC requires at least: 2.6.0
 
 ** WC tested up to: 3.6.1
 
 **/

if (!defined('ABSPATH'))
    exit;

require_once 'admin/admin.php';


if (in_array('woocommerce/woocommerce.php', apply_filters('active_plugins', get_option('active_plugins')))) {
    
    $gen_settings = get_option('phoe_disc_value');
    
    $enable_disc = isset($gen_settings['enable_disc']) ? $gen_settings['enable_disc'] : '';
    
    define('PHOEN_DPADPLUGURL', plugins_url("/", __FILE__));
    
    define('PHOEN_DPADPLUGDIRPATH', plugin_dir_path(__FILE__));
    
    
    
    function phoe_dpad_menu_disc()
    {
        
        add_menu_page('Phoeniixx_Discounts', __('Discounts', 'phoeniixx_woocommerce_discount'), 'nosuchcapability', 'Phoeniixx_Discounts', NULL, PHOEN_DPADPLUGURL . 'assets/images/aa2.png', '57.1');
        
        add_submenu_page('Phoeniixx_Discounts', 'Phoeniixx_Disc_settings', 'Settings', 'manage_options', 'Phoeniixx_Disc_settings', 'Phoen_dpad_settings_func');
        
    }
    
    //add_action('admin_menu', 'phoe_dpad_menu_disc');
    
    function phoen_scripts_for_discount()
    {
        
        wp_enqueue_script('jquery-ui-accordion');
        
        wp_enqueue_script('phoen-select2-js-discount', plugin_dir_url(__FILE__) . 'assets/js/select2.min.js');
        
        wp_enqueue_style('phoen-select2-css-discount', plugin_dir_url(__FILE__) . 'assets/css/select2.min.css');
        
        wp_enqueue_style('phoen-new-css-discount', plugin_dir_url(__FILE__) . 'assets/css/phoen_new_add_backend.css');
        
        wp_enqueue_style('phoen-jquery-ui-discount', plugin_dir_url(__FILE__) . 'assets/css/admin_jquery_css_backend.css');
        
        wp_enqueue_script('jquery-ui-datepicker');
        
    }
    
    add_action('admin_head', 'phoen_scripts_for_discount');
    
    function Phoen_dpad_settings_func()
    {
        
        $gen_settings = get_option('phoe_disc_value');
        
        $enable_disc = isset($gen_settings['enable_disc']) ? $gen_settings['enable_disc'] : '';
        
?>
           
            <div id="profile-page" class="wrap">
        
                <?php
        
        if (isset($_GET['tab'])) {
            $tab = sanitize_text_field($_GET['tab']);
            
        }
        
        else {
            
            $tab = "";
            
        }
        
?>
               <h2> <?php
        _e('Settings', 'phoen-dpad');
?></h2>
                
                <?php
        $tab = (isset($_GET['tab'])) ? $_GET['tab'] : '';
?>
               
                <h2 class="nav-tab-wrapper woo-nav-tab-wrapper">
                
                    
                    <a class="nav-tab <?php
        if ($tab == 'phoeniixx_rule') {
            echo esc_html("nav-tab-active");
        }
?>" href="?page=Phoeniixx_Disc_settings&amp;tab=phoeniixx_rule"><?php
        _e('Discounts', 'phoen-dpad');
?><span class="phoen_oopw"> <?php
        _e('New', 'phoen-dpad');
?></span></a>
                    
                    <a class="nav-tab <?php
        if ($tab == 'phoeniixx_setting') {
            echo esc_html("nav-tab-active");
        }
?>" href="?page=Phoeniixx_Disc_settings&amp;tab=phoeniixx_setting"><?php
        _e('Settings', 'phoen-dpad');
?></a>
                    
                    <a class="nav-tab <?php
        if ($tab == 'phoeniixx_premium') {
            echo esc_html("nav-tab-active");
        }
?>" href="?page=Phoeniixx_Disc_settings&amp;tab=phoeniixx_premium"><span class="phoen_mine"><?php
        _e('Premium', 'phoen-dpad');
?></span></a>                
                    
                </h2>
                
            </div>
            
            <?php
        
        if ($tab == 'phoeniixx_setting') {
            
            include_once(PHOEN_DPADPLUGDIRPATH . 'includes/phoeniixx_discount_pagesetting.php');
            
        } elseif ($tab == 'phoeniixx_premium') {
            
            include_once(PHOEN_DPADPLUGDIRPATH . 'includes/phoen_premium_setting.php');
            
        } elseif ($tab == 'phoeniixx_rule' || $tab == '') {
            
            include_once(PHOEN_DPADPLUGDIRPATH . 'includes/phoeniixx_rule.php');
        }
        
    }
    
    register_activation_hook(__FILE__, 'phoe_dpad_activation_func');
    
    function phoe_dpad_activation_func()
    {
        
        $phoe_disc_value = array(
            
            'enable_disc' => 1,
            'coupon_disc' => 1
            
        );
        
        update_option('phoe_disc_value', $phoe_disc_value);
        
    }
    
    if ($enable_disc == "1") {
        
        include_once(PHOEN_DPADPLUGDIRPATH . 'includes/phoeniixx_discount_productplugin.php');
        
    }
    
    function phoen_dpad_calculate_extra_fee($cart_object)
    {  
        
        /* echo '<pre>';
        print_r($cart_object);
        echo '</pre>';die(); */
        
        if (($coupon_disc == 1) && !empty($cart_object->applied_coupons)) {
            
        } else {
            
            $num_phoen = '';
            
            $phoenuuy = '';

            foreach ($cart_object->cart_contents as $key => $value) {

                $type_discount = get_post_meta($value['product_id'], 'phoen_woocommerce_discount_type', true);

                $val = get_post_meta($value['product_id'], 'phoen_woocommerce_discount_mode', true);

                if ($type_discount == 'price' && isset($value['wholesales_price_discount']) && !empty($val)) {

                    $orgPrice = intval($value['data']->get_price());

                    $selected_discount = $value['wholesales_price_discount'];

                    for ($i = 0; $i < count($val); $i++) {

                        if (($orgPrice - $val[$i]['discount_price']) == $selected_discount) {
                            
                            $qty = $val[$i]['qty'];

                            break;
                        }

                    }

                    if ($value['quantity'] >= $qty) {

                        $value['data']->set_price($selected_discount);

                    }else{

                        $cart_object->remove_cart_item($key);

                        $name = $value['data']->get_name();

                        $url = get_permalink( $value['product_id']);

                        wc_add_notice( __( 'La cantidad minima para aplicar a este precio mayorista es de '.$qty. ' unidades (<a href="'.$url.'">'.$name.'</a>).', 'woocommerce' ), 'error' );

                    }

                    break;

                }else{

                    $gen_settings = get_option('phoe_disc_value');
        
                    $minimunAmount = get_option( 'wholesales_required_amount', 1 );
                    
                    $coupon_disc = isset($gen_settings['coupon_disc']) ? $gen_settings['coupon_disc'] : '';
                        
                    foreach ($cart_object->cart_contents as $key => $value) {

                        $currentTotalPrice =  $currentTotalPrice + ($value['data']->get_price() * intval($value['quantity']));
                        
                    }

                    if ($minimunAmount > $currentTotalPrice) {
                        
                        return;
                    }             
                    
                    $old_price = $value['data']->get_price();
                    
                    $num_phoen = '';

                    if (!empty($val)) {

                        for ($i = 0; $i < count($val); $i++) {
                            
                            $quantity = intval($value['quantity']);
                            
                            $orgPrice = intval($value['data']->get_price());
                            
                            $phoen_minval = isset($val[$i]['min_val']) ? $val[$i]['min_val'] : "";
                            
                            $phoen_maxval = isset($val[$i]['max_val']) ? $val[$i]['max_val'] : "";
                            
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
                            
                            
                            if (((($current_date >= $phoen_from) && ($current_date <= $phoen_to)) || $product_never_expire == '1') && $phoenvar === 5) {
                                
                                if (($quantity >= $phoen_minval) && ($quantity <= $phoen_maxval)) {
                                    
                                    $type = isset($val[$i]['type']) ? $val[$i]['type'] : '';
                                    
                                    if ($type == 'percentage') {
                                        
                                        $percent = (100 - $val[$i]['discount']) / 100;
                                        
                                        // $pp=$orgPrice;
                                        
                                        $new_prc_value = $orgPrice *= $percent;
                                        
                                        $value['data']->set_price($new_prc_value);
                                        
                                        $num_phoen = 87;
                                        
                                        break;
                                        
                                    } else {
                                        
                                        $new_prc_value = $orgPrice -= $val[$i]['discount'];
                                        
                                        $value['data']->set_price($new_prc_value);
                                        
                                        $num_phoen = 87;
                                        
                                        break;
                                        
                                    }
                                    
                                }
                                
                            }
                            
                        }
                    }
                    
                    
                    $product_id_min = $value['product_id'];
                    
                    $gen_settings = get_option('phoen_backend_array');
                    
                    $product_data         = $gen_settings['data'];

                    $product_never_expire = $gen_settings['never_expire'];
                    
                    $product_user_role = $gen_settings['user_role'];
                    
                    
                    for ($i = 0; $i < count($product_data); $i++) {
                        
                        $quantity = intval($value['quantity']);
                        
                        $orgPrice = intval($value['data']->get_price());
                        
                        $phoen_minval = isset($product_data[$i]['min_val']) ? $product_data[$i]['min_val'] : "";
                        
                        $phoen_maxval = isset($product_data[$i]['max_val']) ? $product_data[$i]['max_val'] : "";
                        
                        $phoen_from = isset($gen_settings['from']) ? $gen_settings['from'] : "";
                        
                        $phoen_from = strtotime($phoen_from);
                        
                        $phoen_to = isset($gen_settings['to']) ? $gen_settings['to'] : "";
                        
                        $phoen_to = strtotime($phoen_to);
                        
                        $current_date = strtotime(date("d-m-Y"));
                        
                        $crr_user_roles = wp_get_current_user()->roles;
                        
                        $user_role = isset($crr_user_roles[0]) ? $crr_user_roles[0] : '';
                        
                        $phoen_user_role = isset($product_user_role) ? $product_user_role : "";
                        
                        if (!empty($phoen_user_role)) {
                            
                            if (in_array($user_role, $phoen_user_role)) {
                                $phoenuuy = 5;
                            } else {
                                $phoenuuy = 2;
                            }
                            
                        } else {
                            $phoenuuy = 5;
                        }
                        
                        $terms = get_the_terms($value['product_id'], 'product_cat');

                        foreach ($terms as $term) {
                            $product_cat_id[] = $term->term_id;
                        }
                        $cat_list = $gen_settings['cat_list'];
                        
                        $product_user_role = $gen_settings['user_role'];
                        
                        
                        if (is_array($product_cat_id) && !empty($cat_list)) {
                            
                            $capabilities = array_intersect($cat_list, $product_cat_id);
                            
                        } elseif (empty($cat_list)) {
                            
                            $capabilities = '50';
                            
                        }
                        
                        
                        
                        if (((($current_date >= $phoen_from) && ($current_date <= $phoen_to)) || $product_never_expire === '1') && $phoenuuy === 5 && $num_phoen !== 87 && (!empty($capabilities) || $capabilities == "50")) {
                            
                            if (($quantity >= $phoen_minval) && ($quantity <= $phoen_maxval)) {
                                
                                $type = isset($product_data[$i]['type']) ? $product_data[$i]['type'] : '';
                                
                                if ($type == 'percentage') {
                                    
                                    $percent = (100 - $product_data[$i]['discount']) / 100;
                                    
                                    // $pp=$value['data']->price ;
                                    
                                    $new_prc_value = $orgPrice *= $percent;
                                    
                                    $value['data']->set_price($new_prc_value);
                                    
                                    break;
                                    
                                } else {
                                    
                                    $new_prc_value = $orgPrice -= $product_data[$i]['discount'];
                                    
                                    $value['data']->set_price($new_prc_value);
                                    
                                    break;
                                    
                                }
                                
                            }
                            
                        }
                        
                    }
                }
                
            }

            //validate_all_cart_contents();
            
        }

    }
    
    
    function phoen_dpad_filter_item_price($price, $values)
    {
        global $woocommerce;

        setlocale(LC_MONETARY, 'en_US');

        $minimunAmount = get_option( 'wholesales_required_amount', 1 );
        
        $new_prod_val = get_post_meta($values['product_id']);
        
        $ret_val = "0";
        
        $num_phoen = "0";
        $terms     = get_the_terms($values['product_id'], 'product_cat');

        foreach ($terms as $term) {

            $product_cat_id[] = $term->term_id;
            
        }
        
        
        $val = get_post_meta($values['product_id'], 'phoen_woocommerce_discount_mode', true);
        
        $quantity = intval($values['quantity']);

        if (!empty($val)) {

            $discount_type = get_post_meta($values['product_id'],'phoen_woocommerce_discount_type', true);

            if ($discount_type == 'price') {
                
                $curr = get_woocommerce_currency_symbol();
                    
                //return $curr.$values['line_total'];

                return str_replace(',','.',money_format('%.0n',$values['wholesales_price_discount']));

            }else{

                if ($minimunAmount > $values['line_total']) {

                    $curr = get_woocommerce_currency_symbol();
                    
                    //return $curr.$values['line_total'];

                    return str_replace(',','.',money_format('%.0n',$values['line_total']));
                }

                for ($i = 0; $i < count($val); $i++) {
                
                    $phoen_minval = isset($val[$i]['min_val']) ? $val[$i]['min_val'] : "";
                    
                    $phoen_maxval = isset($val[$i]['max_val']) ? $val[$i]['max_val'] : "";
                    
                    $phoen_from = isset($val[$i]['from']) ? $val[$i]['from'] : "";
                    
                    $phoen_from = strtotime($phoen_from);
                    
                    $phoen_to = isset($val[$i]['to']) ? $val[$i]['to'] : "";
                    
                    $phoen_to = strtotime($phoen_to);
                    
                    $current_date = strtotime(date("d-m-Y"));
                    
                    $crr_user_roles = wp_get_current_user()->roles;
                    
                    $user_role = isset($crr_user_roles[0]) ? $crr_user_roles[0] : "";
                    
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
                    
                    if (((($current_date >= $phoen_from) && ($current_date <= $phoen_to)) || $product_never_expire == '1') && $phoenvar === 5) {
                        
                        if (($quantity >= $phoen_minval) && ($quantity <= $phoen_maxval)) {
                            
                            $ret_val = 1;
                            
                            $num_phoen = 87;
                            
                        }
                        
                    }
                    
                }
            }

        }
            
            
        
        
        $product_id_min = $values['product_id'];
        
        $gen_settings = get_option('phoen_backend_array');
        
        
        $product_data = $gen_settings['data'];
        
        
        for ($i = 0; $i < count($product_data); $i++) {
            
            $phoen_minval = isset($product_data[$i]['min_val']) ? $product_data[$i]['min_val'] : "";
            
            $phoen_maxval = isset($product_data[$i]['max_val']) ? $product_data[$i]['max_val'] : "";
            
            $phoen_from = isset($gen_settings['from']) ? $gen_settings['from'] : "";
            
            $phoen_from = strtotime($phoen_from);
            
            $phoen_to = isset($gen_settings['to']) ? $gen_settings['to'] : "";
            
            $phoen_to = strtotime($phoen_to);
            
            $current_date = strtotime(date("d-m-Y"));
            
            $crr_user_roles = wp_get_current_user()->roles;
            
            $user_role = isset($crr_user_roles[0]) ? $crr_user_roles[0] : '';
            
            $product_user_role = $gen_settings['user_role'];
            
            $phoen_user_role = isset($product_user_role) ? $product_user_role : "";
            
            if (!empty($phoen_user_role)) {
                
                if (in_array($user_role, $phoen_user_role)) {
                    $phoenvar = 5;
                } else {
                    $phoenvar = 2;
                }
                
            } else {
                $phoenvar = 5;
            }
            
            $product_never_expire = $gen_settings['never_expire'];
            
            $cat_list = $gen_settings['cat_list'];
            
            if (!empty($cat_list)) {
                
                $capabilities = array_intersect($cat_list, $product_cat_id);
                
            } else {
                
                $capabilities = '50';
                
            }
            
            
            if (((($current_date >= $phoen_from) && ($current_date <= $phoen_to)) || $product_never_expire === '1') && $phoenvar === 5 && $num_phoen !== 87 && (!empty($capabilities) || $capabilities == "50")) {
                
                if (($quantity >= $phoen_minval) && ($quantity <= $phoen_maxval)) {
                    
                    
                    
                    $ret_val = 1;
                    
                }
                
            }
            
        }
        
        
        
        $curr = get_woocommerce_currency_symbol();
        
        $old_price1 = "";
        
        $old_price = "";
        
        global $product;
        
        $plan = wc_get_product($values['product_id']);
        
        $name = get_post($values['product_id']);
        
        $_product = wc_get_product($values['product_id']);
        
        if ($_product && $_product instanceof WC_Product_Variable && $values['variation_id']) {
            $variations = $plan->get_available_variation($values['variation_id']);
            
            if ($variations['display_regular_price'] != '') {
                
                //$old_price1 = $curr . $variations['display_regular_price'];

                $old_price1 = str_replace(',','.',money_format('%.0n',$variations['display_regular_price']));

                
               
                
            }
            
            if ($variations['display_price'] != '') {
                
                //$old_price1 = $curr . $variations['display_price'];

                $old_price1 = str_replace(',','.',money_format('%.0n',$variations['display_price']));
                
            }
        } else {
            if ($new_prod_val['_regular_price'][0] != '') {
                //$old_price1 = $curr . $new_prod_val['_regular_price'][0];

                $old_price1 = str_replace(',','.',money_format('%.0n',$new_prod_val['_regular_price'][0]));
                
            }
            if ($new_prod_val['_sale_price'][0] != '') {
                //$old_price1 = $curr . $new_prod_val['_sale_price'][0];

                $old_price1 = $curr . $new_prod_val['_sale_price'][0];
                
            }
            
        }
        $gen_settings = get_option('phoe_disc_value');
        
        $coupon_disc = isset($gen_settings['coupon_disc']) ? $gen_settings['coupon_disc'] : '';
        
        if ((($coupon_disc == 1) && (!(empty($woocommerce->cart->applied_coupons)))) || ($ret_val == 0)) {
            return "<span class='discount-info' title=''>" . "<span class='old-price' >$old_price1</span></span>";
            
        } else {
        
            $price  = $curr .($values['line_total']/$values['quantity']);
            
            return "<span class='discount-info' title=''>" . "<span class='old-price' style='color:red; text-decoration:line-through;'>$old_price1</span> " . "<span class='new-price' > $price</span></span>";
            
            
        }
    }
    
    function phoen_dpad_filter_subtotal_price($price, $values)
    {
        
        global $woocommerce;

        setlocale(LC_MONETARY, 'en_US');
        
        $amt = '';
        
        $type_curr = '';
        
        $num_phoen = '';

        $quantity = intval($values['quantity']);
        
        $curr = get_woocommerce_currency_symbol();
        
        $val = get_post_meta($values['product_id'], 'phoen_woocommerce_discount_mode', true);
        
        $gen_settings = get_option('phoe_disc_value');
        
        $coupon_disc = isset($gen_settings['coupon_disc']) ? $gen_settings['coupon_disc'] : '';
        if (!empty($val)) {

            $discount_type = get_post_meta($values['product_id'],'phoen_woocommerce_discount_type', true);

            if ($discount_type == 'price') {

                $curr = get_woocommerce_currency_symbol();
                    
                //return $curr.$values['line_total'];

                return str_replace(',','.',money_format('%.0n',$values['line_total']));

            }else{

                $minimunAmount = get_option( 'wholesales_required_amount', 1 );

                if ($minimunAmount > $values['line_total']) {
                    
                    $curr = get_woocommerce_currency_symbol();
                    
                    //return $curr.$values['line_total'];

                    return str_replace(',','.',money_format('%.0n',$values['line_total']));
                }
        
                for ($i = 0; $i < count($val); $i++) {
                    
                    $quantity     = intval($values['quantity']);
                    $phoen_minval = isset($val[$i]['min_val']) ? $val[$i]['min_val'] : "";
                    $phoen_maxval = isset($val[$i]['max_val']) ? $val[$i]['max_val'] : "";
                    
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
                    
                    if (((($current_date >= $phoen_from) && ($current_date <= $phoen_to)) || $product_never_expire === '1') && $phoenvar === 5) {
                        
                        if (($quantity >= $phoen_minval) && ($quantity <= $phoen_maxval)) {
                            
                            $amt = isset($val[$i]['discount']) ? $val[$i]['discount'] : '';
                            
                            $type = isset($val[$i]['type']) ? $val[$i]['type'] : '';
                            
                            if ($type == 'percentage') {
                                
                                $type_curr = "[" . $amt . "% Discount]";
                                
                                $num_phoen = 87;
                                break;
                            }
                            
                            else {
                                
                                $type_curr = "[" . $curr . $amt . " de descuento por cada unidad]";
                                
                                $num_phoen = 87;
                                break;
                            }
                            
                        }
                        
                    }
                    
                }
            }
        }
        
        $product_id_min = $values['product_id'];
        
        $gen_settings = get_option('phoen_backend_array');
        
        $product_data = $gen_settings['data'];
        
        $product_user_role = $gen_settings['user_role'];
        
        
        for ($i = 0; $i < count($product_data); $i++) {
            
            $phoen_minval = isset($product_data[$i]['min_val']) ? $product_data[$i]['min_val'] : "";
            
            $phoen_maxval = isset($product_data[$i]['max_val']) ? $product_data[$i]['max_val'] : "";
            
            $phoen_from = isset($gen_settings['from']) ? $gen_settings['from'] : "";
            
            $phoen_from = strtotime($phoen_from);
            
            $phoen_to = isset($gen_settings['to']) ? $gen_settings['to'] : "";
            
            $phoen_to = strtotime($phoen_to);
            
            $current_date = strtotime(date("d-m-Y"));
            
            $crr_user_roles = wp_get_current_user()->roles;
            
            $user_role = isset($crr_user_roles[0]) ? $crr_user_roles[0] : '';
            
            $phoen_user_role = isset($product_user_role) ? $product_user_role : "";
            
            if (!empty($phoen_user_role)) {
                
                if (in_array($user_role, $phoen_user_role)) {
                    $phoenvar = 5;
                } else {
                    $phoenvar = 2;
                }
                
            } else {
                $phoenvar = 5;
            }
            $product_never_expire = $gen_settings['never_expire'];
            
            $terms = get_the_terms($values['product_id'], 'product_cat');
            foreach ($terms as $term) {
                $product_cat_id[] = $term->term_id;
                
            }
            $cat_list = $gen_settings['cat_list'];
            
            $product_user_role = $gen_settings['user_role'];
            
            
            if (!empty($cat_list)) {
                
                $capabilities = array_intersect($cat_list, $product_cat_id);
                
            } else {
                
                $capabilities = '50';
                
            }
            
            
            if (((($current_date >= $phoen_from) && ($current_date <= $phoen_to)) || $product_never_expire === '1') && $phoenvar === 5 && $num_phoen !== 87 && (!empty($capabilities) || $capabilities == "50")) {
                
                if (($quantity >= $phoen_minval) && ($quantity <= $phoen_maxval)) {
                    
                    $amt = isset($product_data[$i]['discount']) ? $product_data[$i]['discount'] : '';
                    
                    $type = isset($product_data[$i]['type']) ? $product_data[$i]['type'] : '';
                    
                    if ($type == 'percentage') {
                        
                        $type_curr = "[" . $amt . "% Discount]";
                        break;
                    }
                    
                    else {
                        
                        $type_curr = "[" . $curr . $amt . " de descuento por cada unidad]";
                        break;
                    }
                    
                }
                
            }
            
        }
        
        if (($coupon_disc == 1) && (!(empty($woocommerce->cart->applied_coupons)))) {
            return "<span class='discount-info' title='$type_curr'>" . "<span>$price</span></span>";
            
        } else {
            
            return "<span class='discount-info' title='$type_curr'>" . "<span>$price</span>" . "<span class='new-price' style='color:red;'> $type_curr</span></span>";
            
        }
    }
    
    
    
    function phoen_dpad_filter_subtotal_order_price($price, $values, $order)
    {
        
        global $woocommerce;

        setlocale(LC_MONETARY, 'en_US');

        $minimunAmount = get_option( 'wholesales_required_amount', 1 );

        //var_dump($values['line_total']);Exit;
        if ($minimunAmount > $values['line_total']) {
            
            $curr = get_woocommerce_currency_symbol();
            
            //return $curr.$values['line_total'];

            return str_replace(',','.',money_format('%.0n',$values['line_total']));
        }

        $amt = '';
        
        $type_curr = '';
        
        $curr = get_woocommerce_currency_symbol();
        
        $val = get_post_meta($values['product_id'], 'phoen_woocommerce_discount_mode', true);
        
        $quantity = intval(isset($values['item_meta']['_qty'][0]) ? $values['item_meta']['_qty'][0] : '');
        
        $gen_settings = get_option('phoe_disc_value');
        
        $coupon_disc = isset($gen_settings['coupon_disc']) ? $gen_settings['coupon_disc'] : '';
        
        for ($i = 0; $i < count($val); $i++) {
            $phoen_minval = isset($val[$i]['min_val']) ? $val[$i]['min_val'] : "";
            $phoen_maxval = isset($val[$i]['max_val']) ? $val[$i]['max_val'] : "";
            
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
            
            $product_never_expire = isset($val[$i]['never_expire']) ? $val[$i]['never_expire'] : '';
            if (isset($product_never_expire)) {
                if (((($current_date >= $phoen_from) && ($current_date <= $phoen_to)) || $product_never_expire === '1') && $phoenvar === 5) {
                    
                    if (($quantity >= $phoen_minval) && ($quantity <= $phoen_maxval)) {
                        
                        $amt = isset($val[$i]['discount']) ? $val[$i]['discount'] : '';
                        
                        $type = isset($val[$i]['type']) ? $val[$i]['type'] : '';
                        
                        if ($type == 'percentage') {
                            
                            $type_curr = "[" . $amt . "% Discount]";
                            $num_phoen = 87;
                            
                            break;
                        }
                        
                        else {
                            
                            $type_curr = "[" . $curr . $amt . " de descuento por cada unidad]";
                            
                            $num_phoen = 87;
                            
                            break;
                        }
                    }
                    
                }
            }
            
        }
        
        
        $product_id_min = $values['product_id'];
        
        $gen_settings = get_option('phoen_backend_array');
        
        $product_data = $gen_settings['data'];
        
        $product_user_role = $gen_settings['user_role'];
        
        
        for ($i = 0; $i < count($product_data); $i++) {
            
            $phoen_minval = isset($product_data[$i]['min_val']) ? $product_data[$i]['min_val'] : "";
            
            $phoen_maxval = isset($product_data[$i]['max_val']) ? $product_data[$i]['max_val'] : "";
            
            $phoen_from = isset($gen_settings['from']) ? $gen_settings['from'] : "";
            
            $phoen_from = strtotime($phoen_from);
            
            $phoen_to = isset($gen_settings['to']) ? $gen_settings['to'] : "";
            
            $phoen_to = strtotime($phoen_to);
            
            $current_date = strtotime(date("d-m-Y"));
            
            $crr_user_roles = wp_get_current_user()->roles;
            
            $user_role = isset($crr_user_roles[0]) ? $crr_user_roles[0] : '';
            
            $phoen_user_role = isset($product_user_role) ? $product_user_role : "";
            
            if (!empty($phoen_user_role)) {
                
                if (in_array($user_role, $phoen_user_role)) {
                    $phoenvar = 5;
                } else {
                    $phoenvar = 2;
                }
                
            } else {
                $phoenvar = 5;
            }
            $product_never_expire = $gen_settings['never_expire'];
            
            
            $terms = get_the_terms($values['product_id'], 'product_cat');
            foreach ($terms as $term) {
                $product_cat_id[] = $term->term_id;
                
            }
            $cat_list = $gen_settings['cat_list'];
            
            $product_user_role = $gen_settings['user_role'];
            
            if (!empty($cat_list)) {
                
                $capabilities = array_intersect($cat_list, $product_cat_id);
                
            } else {
                
                $capabilities = '50';
                
            }
            
            if (isset($num_phoen)) {
                if (((($current_date >= $phoen_from) && ($current_date <= $phoen_to)) || $product_never_expire === '1') && $phoenvar === 5 && $num_phoen !== 87 && (!empty($capabilities) || $capabilities == "50")) {
                    
                    if (($quantity >= $phoen_minval) && ($quantity <= $phoen_maxval)) {
                        
                        $amt = isset($product_data[$i]['discount']) ? $product_data[$i]['discount'] : '';
                        
                        $type = isset($product_data[$i]['type']) ? $product_data[$i]['type'] : '';
                        
                        if ($type == 'percentage') {
                            
                            $type_curr = "[" . $amt . "% Discount]";
                            break;
                        }
                        
                        else {
                            
                            $type_curr = "[" . $curr . $amt . " de descuento por cada unidad]";
                            break;
                        }
                    }
                    
                }
                
            }
            
        }
        
        $discount_type = get_post_meta($order->get_id());
        
        if (($coupon_disc == 1) && (!(empty($discount_type['_cart_discount'][0])))) {
            return "<span class='discount-info' title='$type_curr'>" . "<span>$price</span></span>";
            
        } else {
            
            return "<span class='discount-info' title='$type_curr'>" . "<span>$price</span>" . "<span class='new-price' style='color:red;'> $type_curr</span></span>";
            
        }
    }
    
    function wholesale_role()
    {
        
        
        if (role_exists('wholesaler'))
            return;
        
        add_role('wholesaler', 'Mayorista', array(
            'read' => false,
            'delete_posts' => false
        ));
        
        
        
    }
    
    function role_exists($role)
    {
        
        if (!empty($role)) {
            return $GLOBALS['wp_roles']->is_role($role);
        }
        
        return false;
    }
    
    
    if ($enable_disc == "1") {
        add_action('woocommerce_before_calculate_totals', 'phoen_dpad_calculate_extra_fee', 1, 1);
        
        add_filter('woocommerce_cart_item_price', 'phoen_dpad_filter_item_price', 10, 2);
        
        add_filter('woocommerce_cart_item_subtotal', 'phoen_dpad_filter_subtotal_price', 10, 2);
        
        add_filter('woocommerce_checkout_item_subtotal', 'phoen_dpad_filter_subtotal_price', 10, 2);
        
        add_filter('woocommerce_order_formatted_line_subtotal', 'phoen_dpad_filter_subtotal_order_price', 10, 3);
        
        add_action('admin_init', 'wholesale_role');
        
        add_filter('woocommerce_locate_template', 'woocommerce_wholesaler_template', 10, 3);
        
        add_filter('woocommerce_payment_gateways', 'wholesales_add_gateway_class');
        
        add_action('plugins_loaded', 'wholesales_init_gateway_class');
        
        add_filter('wp_nav_menu_items', 'new_nav_menu_items');
        
        add_shortcode('wholesale_request_form', 'custom_wholesale_form');

        add_filter('woocommerce_before_add_to_cart_form', 'show_wholesale_promotions');

        add_action( 'woocommerce_before_add_to_cart_button', 'hidden_field_before_add_to_cart_button', 5 );

        add_filter( 'woocommerce_general_settings', 'add_success_wholesales_message' );

        add_action( 'woocommerce_thankyou', 'add_thank_you_wholesale_message' );

        add_filter( 'woocommerce_package_rates', 'sb_woocommerce_set_free_shipping_for_certain_users', 10, 2);

        add_filter( 'woocommerce_email_order_details',  'add_order_wholesales_orderdetail', 10, 4 );

        add_filter( 'woocommerce_review_order_before_submit' , 'add_shipping_message_wholesales');

        add_action('woocommerce_check_cart_items', 'validate_all_cart_contents');

        add_filter( 'woocommerce_add_cart_item_data', 'save_selected_discount_price', 99, 2 );

        add_action( 'init', 'remove_filters_change_quantity' );

        add_action( 'woocommerce_after_order_notes', 'add_custom_checkout_hidden_field' );

        add_action( 'woocommerce_checkout_update_order_meta', 'save_custom_checkout_hidden_field' );

        add_action( 'woocommerce_order_details_before_order_table', 'display_voucher_in_customer_order', 10 );

        add_action( 'init',  'send_wholesales_payment_voucher' );

        add_filter( 'woocommerce_reports_get_order_report_query',  'report_filter' );

    }

    function report_filter($query){

        $query['where'] = $query['where'] . ' AND NOT EXISTS (SELECT * FROM wp_postmeta WHERE post_id = posts.ID AND meta_key = \'_is_wholesales_order\')';

        return $query;
        
    }

    function send_wholesales_payment_voucher(){

        if ($_REQUEST['action']=='send_wholesales_voucher') {

            if ($_FILES['fileToUpload']['size'] <= 5000000 && isset($_POST['order_id'])) {   

                $order_id = $_POST['order_id']; 

                $voucher_name = time() . $_FILES['fileToUpload']['name'];
                
                $upload = wp_upload_bits($voucher_name , null, file_get_contents($_FILES['fileToUpload']['tmp_name']));

                if (isset($upload['file'])) {

                    $to = get_option( 'wholesales_email' );

                    $message = 'Link de la orden:';

                    $url = admin_url("/post.php?post=".$order_id.'&action=edit');

                    $message .= '<a  href="'.$url.'">'.$order_id.'</a>';

                    $subject = 'MAYORISTAS: Comprobante de pago para la orden - '.$order_id;

                    if(send_custom_email($to,$subject,$message,'Comprobante de pago', array($upload['file']))){

                        unlink($upload['file']);

                        wc_add_notice('Se envio el comprobante exitosamente.');
                    }

                }
                
            }else{

                wc_add_notice('El archivo debe pesar menos de 5MB.', 'error');
            }

        }

    }

    function display_voucher_in_customer_order($order){


        $order_id = method_exists( $order, 'get_id' ) ? $order->get_id() : $order->id;

        $payment_method = $order->get_payment_method();

        if ($payment_method == 'wholesales') {
            echo '
                <h2>Envia tu comprobante de pago aqui:</h2><br>
                <form action="' . $_SERVER['REQUEST_URI'] . '" method="post" enctype="multipart/form-data">
                    <input type="hidden" name="action" value="send_wholesales_voucher">
                    <input type="hidden" name="order_id" value="'.$order_id.'">
                    <input type="file" name="fileToUpload" id="fileToUpload" accept=".jpg,.png,.pdf"><br>
                    <input type="submit" value="Enviar comprobante" name="submit">
                </form>
            ';
        }

    }


    function save_custom_checkout_hidden_field( $order_id ) {
        
        if ( ! empty( $_POST['wholesales'] ) ) {
        
            update_post_meta( $order_id, '_is_wholesales_order', 1 );
        
        }
    }

    function add_custom_checkout_hidden_field( $checkout ) {

        global $woocommerce;
        
        $items = $woocommerce->cart->get_cart();

        foreach($items as $item => $values) { 
            
            if (isset($values['wholesales_price_discount'])) {
                
                echo '<div id="user_link_hidden_checkout_field">
                        <input type="hidden" class="input-hidden" name="wholesales" id="wholesales" value="1">
                </div>';

                break;
            }
        } 

        
    }

    function remove_filters_change_quantity(){
        global $wp_filter;
        $user = wp_get_current_user();

        if (in_array('wholesaler', (array) $user->roles)) {
            
            unset( $wp_filter["woocommerce_cart_item_name"]);
        }
    }


    function save_selected_discount_price( $cart_item_data, $product_id ) {
     
        if( isset( $_POST['product_discount'] ) && $_POST['product_discount'] != 0 ) {
            $cart_item_data[ "wholesales_price_discount" ] = $_POST['product_discount'];     
        }
        return $cart_item_data;
         
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

                        $orgPrice = intval($cart_content_product['data']->get_price());
                        
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

                        if (((($current_date >= $phoen_from) && ($current_date <= $phoen_to)) || $product_never_expire == '1') && $phoenvar === 5) {
			    
                            if ((($total >= $phoen_minval) && ($total <= $phoen_maxval)) || $total < $phoen_minval) {
					
                                $founded_price = $orgPrice - $val[$i]['discount_price'];

                                $current_price = $cart_content_product['wholesales_price_discount'];

                                if ($founded_price > $current_price) {


                                    $selected_discount = $orgPrice - $cart_content_product['wholesales_price_discount'];
				    //echo $orgPrice;echo '<br>';
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
   

    function add_shipping_message_wholesales(){

        $user = wp_get_current_user();

        if (in_array('wholesaler', (array) $user->roles)) {
        
            setlocale(LC_MONETARY, 'en_US');

            echo __('<br><strong>IMPORTANTE</strong><br>El costo del envío será cobrado al recibir su pedido.<br><br>');
            
            $minimunAmount = get_option( 'wholesales_required_amount', 1 );
            
//            echo '<p> NO OLVIDES QUE EL TOTAL DE TUS COMPRAS DEBE SER DE MINIMO <strong>'.str_replace(',','.',money_format('%.0n',$minimunAmount)).'</strong> PARA QUE LOS DESCUENTOS SE APLIQUEN</p>';
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

    function add_thank_you_wholesale_message() {

        $user = wp_get_current_user();
        
        if (in_array('wholesaler', (array) $user->roles)) {


            $text = get_option( 'wholesales_success_message', 1 );

            $content = '';

            $content .= '<h3>¡IMPORTANTE!</h3>';

            $content .= '<p style="text-align: justify;text-transform: uppercase;">'.$text.'</p>';

            if ($text != '') {

                echo $content;
            }
        }
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
        }

        $updated_settings[] = $section;
      }

      return $updated_settings;
    }

    function hidden_field_before_add_to_cart_button(){

        echo '<input type="hidden" name="product_discount" id="product_discount" value="">';
    }

    function show_wholesale_promotions(){
        setlocale(LC_MONETARY, 'en_US');
        global $product;

        $user = wp_get_current_user();
        
        if (in_array('wholesaler', (array) $user->roles)) {

            wp_enqueue_script('wholesale_product_script', plugins_url('product.js', '/dynamic-price-and-discounts-for-woocommerce/assets/js/product.js'), array(
                'jquery'
            ),'1.8');

            wp_enqueue_style('wholesale-discount-price', plugins_url('product_wholesale_price.css', '/dynamic-price-and-discounts-for-woocommerce/assets/css/product_wholesale_price.css'));

            $id = $product->get_id();

            $_product = wc_get_product( $id );

            $data = get_post_meta($id, 'phoen_woocommerce_discount_mode',true);

            if($data != ''){

                $discount_type = get_post_meta($id, 'phoen_woocommerce_discount_type',true);

                if ($discount_type  == 'price') {

                    echo '<div class="radio-button_wrapper">';
                    
                    foreach ($data as $key => $value) {

                        $discountPrice = $_product->get_price() - $value['discount_price'];

                        echo '<div class="radio-button">
                                  <div class="radio-button_inner">
                                    <input class="wholesales-price-type" type="radio" id="option1" name="price_discount" value="'.$discountPrice.'">

                                    <label class="radio-button_label" for=""> <h4>'.$value['label'].'</h4>Ordenes entre '.
                                        money_format('%.0n',$value['min_price']).' y '.money_format('%.0n',$value['max_price']).
                                        ' : '.str_replace(',','.',money_format('%.0n',$discountPrice)).
                                    ' C/U (APLICA MINIMO CON '.(isset($value['qty']) ? $value['qty'] : 1).' UNIDADES)</label>
                                  </div>
                            </div>';
                        
                    }

                    echo '</div>';

                }else{

                    $minimunAmount = get_option( 'wholesales_required_amount', 1 );

                    $suggestedPrice = get_post_meta( $id ,'_suggested_price', 1 );
                
                    echo '<strong>¡IMPORTANTE!</strong>';

                    echo '<p> NO OLVIDES QUE EL TOTAL DE TUS COMPRAS DEBE SER DE MINIMO <strong>'.str_replace(',','.',money_format('%.0n',$minimunAmount)).'</strong> PARA QUE LOS DESCUENTOS SE APLIQUEN</p>';
                    echo '<div class="">';

                    if ($suggestedPrice != null) {
                    
                        echo '<strong>PRECIO SUGERIDO DE VENTA</strong><br>'.str_replace(',','.',money_format('%.0n',$suggestedPrice)).' x Unidad<br><br>';
                    }

                    
                    echo '</div>';

                    echo '<table style="width:100%" id="wholesales-discounts">
                          <tr>
                            <th>Cantidad</th>
                            <th>Precio</th> 
                          </tr>
                          <tbody>';

                    foreach ($data as $key => $value) {
                        
                        $discountPrice = $_product->get_price() - $value['discount'];

                        echo '<tr>
                                <td class="discount">'.$value['min_val'].' - '.$value['max_val'].'</td>
                                <td class="discount-price">'. str_replace(',','.',money_format('%.0n',$discountPrice)) .' x Unidad</td> 
                            </tr>';
                    }

                       
                    echo'
                        </tbody>     
                        </table>';
                    

                    echo '<strong>TU GANANCIA SERÁ: </strong><br><span id="utility">$0</span><br><br>';  

                }
            }
        }
    }
        
    function custom_wholesale_form()
    {
        
        ob_start();
        
        wholesales_registration_function();
        
        return ob_get_clean();
    }
    
    function wholesales_init_gateway_class()
    {
        
        include('includes/methods/Wholesales.php');
    }
    
    function wholesales_add_gateway_class($gateways)
    {
        
        $gateways[] = 'WC_Wholesales_Payment_Gateway'; // your class name is here
        
        return $gateways;
    }
    
    
    
    function woocommerce_wholesaler_template($template, $template_name, $template_path)
    {
        
        $user = wp_get_current_user();
        
        /*if(in_array( 'wholesaler', (array) $user->roles)){
        
        $template = plugin_dir_path( __FILE__ ) . 'includes/template/form-checkout-whoesaler.php'; 
        echo $template = plugin_dir_path( __FILE__ ) . 'includes/template/form-checkout-whoesaler.php';
        }  */
        
        remove_action('woocommerce_before_checkout_form', 'woocommerce_checkout_coupon_form', 10);
        
        add_filter('woocommerce_enable_order_notes_field', '__return_false');
        
        //add_filter('woocommerce_checkout_fields', 'unset_unwanted_checkout_fields');
        
        add_filter('woocommerce_available_payment_gateways', 'disable_payment_methods');
        
        return $template;
        
    }
    
    function disable_payment_methods($available_gateways)
    {
        
        global $woocommerce;
        
        $user = wp_get_current_user();
        
        if (in_array('wholesaler', (array) $user->roles)) {

            $bank_transfer = get_option( 'wholesales_bank_transfer_payment', 1 );

            $credit_cart = get_option( 'wholesales_credit_cart_payment', 1 );

            $cash = get_option( 'wholesales_cash_payment', 1 );

            if ($bank_transfer == 'no') {
                
                unset($available_gateways['payu_trasferencia_bancaria']);

            }

            if ($credit_cart == 'no') {
                
                unset($available_gateways['payu_tc']);

            }

            if ($cash == 'no') {
                
                unset($available_gateways['payu_efectivo']);
            }

            if ($bank_transfer == 'no' && $credit_cart == 'no' &&  $cash == 'no') {
                
                wp_enqueue_script('wholesale_script', plugins_url('wholesale.js', '/dynamic-price-and-discounts-for-woocommerce/assets/js/wholesale.js'), array(
                    'jquery'
                ));
                wp_enqueue_style('wholesale-style', plugins_url('checkout.css', '/dynamic-price-and-discounts-for-woocommerce/assets/css/checkout.css'));
            }
            
            
        } else {
            
            unset($available_gateways['wholesales']);
            
        }
        
        
        return $available_gateways;
        
    }
    
    function unset_unwanted_checkout_fields($fields)
    {
        
        $user = wp_get_current_user();
        
        if (in_array('wholesaler', (array) $user->roles)) {
            
            // list of the billing field keys to remove
            /*$billing_keys = array(
            'billing_company',
            'billing_phone',
            'billing_address_1',
            'billing_address_2',
            'billing_city',
            'billing_postcode',
            'billing_country',
            'billing_state',
            );
            
            foreach( $billing_keys as $key ) {
            unset( $fields['billing'][$key] );
            }*/
            
            
        }
        
        return $fields;
    }
    
    function new_nav_menu_items($items)
    {
        
        //$homelink = '<li class="home"><a href="' . home_url('/solicitud-mayoristas') . '">' . __('Mayoristas') . '</a></li>';
        
        $items = $items;
        
        return $items;
    }
    
    function registration_form()
    {
        $user = wp_get_current_user();

        $url_custom = get_home_url().'?action=wholesales_catelog_shop&id=full';
        
        $label = 'Descargar catalogo mayoristas';
        
        /*echo '<div class="gmwcp_button mobile-catalog-button">
            <a href="'. $url_custom.'" class="button secondary" target="_blank">'.$label.'</a>
        </div>';*/


        if (is_user_logged_in() && in_array('wholesaler', (array) $user->roles)) {
            
            $txt = '<h2>Ya estas logueado como mayorista, disfruta de los mejores descuentos haciendo click <a href="'.get_site_url().'/tienda'.'">AQUI</a>!</h2>';

            echo $txt; return;

        }else if(is_user_logged_in() && !in_array('wholesaler', (array) $user->roles)){

            $txt = '<h3>Solicita la creacion de una nueva cuenta mayorista para obtener mejores precios!</h3>
                <p>Si ya tienes una cuenta creada como cliente debes utilizar otro correo electrónico.</p>
            ';

        }else{

            $txt = getLoginForm();
        }        

        echo '
                <div class="container-box">
                    <div class="left">
                        '.$txt.'
                    </div>
                    <div class="right">'
        ;

        
        echo '
            <h2>FORMULARIO DE SOLICITUD MAYORISTAS</h2>
               <p>Envíanos la solicitud para ser un mayorista de nosotros ingresando el siguente formulario, recibirás a tu correo una confirmación en caso de ser aprobada tu solicitud.</p>
               ';
        
        if ($_GET['success'] == 'true') {
            echo '<strong>TU SOLICITUD FUE ENVIADA CON EXITO!</strong>';
        } else if ($_GET['success'] == 'false') {
            echo '<strong>ALGO FALLO EN EL ENVIO DE LA SOLICITUD, VUELVE A INTENTARLO</strong>';
        }
        
        echo ' 
            <div class="wholesale-form "> 
            <form action="' . $_SERVER['REQUEST_URI'] . '" method="post">
            
                <div>
                <label for="email">' . __('Correo electronico') . ' <strong>*</strong></label>
                <input type="text" name="email" value="' . (isset($_POST['email']) ? $email : null) . '">
                </div>
                 
                <div>
                <label for="firstname">' . __('Primer nombre') . '</label>
                <input type="text" name="fname" value="' . (isset($_POST['fname']) ? $first_name : null) . '">
                </div>
                 
                <div>
                <label for="website">' . __('Segundo nombre') . '</label>
                <input type="text" name="lname" value="' . (isset($_POST['lname']) ? $last_name : null) . '">
                </div>

                <div>
                <label for="website">' . __('Telefono') . '</label>
                <input type="text" name="phone" value="' . (isset($_POST['phone']) ? $phone : null) . '">
                </div>

                <div>
                <label for="website">' . __('Url o instagram del sitio') . '</label>
                <input type="text" name="url" value="' . (isset($_POST['url']) ? $url : null) . '">
                </div>
                 <br>
                <input type="submit" name="submit_wholesale_request" value="Enviar solicitud"/>
            </form>
            </div>
            ';




        echo '
            </div>
        </div>
        ';

        
        /*if (!is_user_logged_in()) {
            echo '<h2>LOGIN MAYORISTAS</h2>';
            wp_login_form();
        } else if (in_array('wholesaler', (array) $user->roles)) {
            echo '<h4>Ya estas logueado como mayorista!</h4>';
        }*/
        echo '
            <style>
            .left{

                pag
            }

            .container-box:after {
                clear: both;
            }

            .container-box > div {
                min-height: 200px;
                float: left;
                width: 50%;
                padding: 20px;
            }

            @media all and (max-width: 500px) {
                .container-box > div {
                    float: none;
                    width: 100%;
                }
            }
            div {
              margin-bottom:2px;
            }
             
            input{
                margin-bottom:4px;

            }
            h2,p,h3{
                text-align: center;
            }

            form{

                max-width:500px;
            }

            .wholesale-form {
               display: table;
               width: 100%;
            }
            form {
               //text-align: center;
               vertical-align: middle;
               width:100%;
            }

            #loginform{

                
            }

            @media screen and (max-width: 600px) {
              .desktop-catalog-button {
                display: none;
              }
            }

            @media screen and (min-width: 600px) {
              .mobile-catalog-button {
                display: none;
              }
            }
    
            .mobile-catalog-button a {
                width: 100%;
                text-transform: uppercase;
            }

            .desktop-catalog-button a {
                width: 100%;
                text-transform: uppercase;
            }
            </style>
            ';
       
    }

    function getLoginForm(){

        $label = 'Descargar catalogo mayoristas';

        $url_custom = get_home_url().'?action=wholesales_catelog_shop&id=full';

        return '  
            <h2>LOGIN MAYORISTAS</h2>
            <p>Logueate aqui para obtener los mejores descuentos por ser mayorista con nosotros</p>
            <form name="loginform" id="loginform" action="'.get_site_url().'/wp-login.php" method="post">
    
                <p class="login-username">
                    <label for="user_login">Nombre de usuario o dirección de correo</label>
                    <input type="text" name="log" id="user_login" class="input" value="" size="20">
                </p>
                <p class="login-password">
                    <label for="user_pass">Contraseña</label>
                    <input type="password" name="pwd" id="user_pass" class="input" value="" size="20">
                </p>
                
                <p class="login-remember"><label><input name="rememberme" type="checkbox" id="rememberme" value="forever"> Recuérdame</label></p>
                <p class="login-submit">
                    <input type="submit" name="wp-submit" id="wp-submit" class="button button-primary" value="Acceder">
                    <input type="hidden" name="redirect_to" value="http://localhost/holy/solicitud-mayoristas/">
                </p>
                
            </form>

        ';
        
        //<div class="gmwcp_button desktop-catalog-button"><a href="'. $url_custom.'" class="button secondary" target="_blank">'.$label.'</a></div>
            

    }
    
    function registration_validation($email, $phone, $first_name, $last_name)
    {
        
        global $reg_errors;
        
        $reg_errors = new WP_Error;
        
        if (empty($username) || empty($phone) || empty($first_name) || empty($last_name)) {
            $reg_errors->add('field', 'Required form field is missing');
        }
        
        if (!is_email($email)) {
            $reg_errors->add('email', 'Email Already in use');
        }
        
        if (is_wp_error($reg_errors)) {
            
            foreach ($reg_errors->get_error_messages() as $error) {
                
                echo '<div>';
                echo '<strong>ERROR</strong>:';
                echo $error . '<br/>';
                echo '</div>';
                
            }
        }
        
    }
    
    function complete_registration()
    {
        global $reg_errors, $username, $password, $email, $website, $first_name, $last_name, $nickname, $bio;
        if (1 > count($reg_errors->get_error_messages())) {
            $userdata = array(
                'user_login' => $username,
                'user_email' => $email,
                'user_pass' => $password,
                'user_url' => $website,
                'first_name' => $first_name,
                'last_name' => $last_name,
                'nickname' => $nickname,
                'description' => $bio
            );
            $user     = wp_insert_user($userdata);
            echo 'Registration complete. Goto <a href="' . get_site_url() . '/wp-login.php">login page</a>.';
        }
    }
    
    function wholesales_registration_function()
    {
        if (isset($_POST['submit_wholesale_request'])) {
            registration_validation($_POST['email'], $_POST['fname'], $_POST['lname'], $_POST['phone']);
            
            // sanitize user form input
            global $email, $first_name, $last_name, $phone;
            $email      = sanitize_email($_POST['email']);
            $first_name = sanitize_text_field($_POST['fname']);
            $last_name  = sanitize_text_field($_POST['lname']);
            $phone      = sanitize_text_field($_POST['phone']);
            $url = sanitize_text_field($_POST['url']);
            
            // call @function complete_registration to create the user
            // only when no WP_error is found
            /*complete_registration(
            $email,
            $first_name,
            $last_name,
            $phone
            );*/
            if (sendRequestWholesaleEmail($email, $first_name, $last_name, $phone, $url)) {
                
                add_action('form_message', 'write_here_show_success_messages');
                
            }
            
            $location = get_site_url() . '/solicitud-mayoristas?success=true';
            
            wp_redirect($location, 301);
        }
        
        registration_form($email, $first_name, $last_name, $phone, $url);
        
        
    }

    function write_here_show_success_messages($success_msg)
    {
        echo '<div class="form-success">';
        echo '<span>EXITO</span><br/>';
        echo '</div>';
    }


    function sendRequestWholesaleEmail($email, $first_name, $last_name, $phone, $url)
    {
        
        $status = false;
        
        $name = sprintf('%s %s', $first_name, $last_name);
        
        $to = get_option( 'wholesales_email' );
        
        $subject = "Solicitud Mayorista: " . $email;
        
        $content = "<strong>Name</strong>: {$name}<br>" . "<strong>Email</strong>: {$email}" . "<br><strong>Phone:</strong>: {$phone}" . "<br><strong>Url o intagram</strong>: {$url}<br><button><a href='" . get_site_url() . "/wholesales/createuser?name=" . $name . "&email=" . $email . "&phone=" . $phone . "' target='_blank'>ACEPTAR</a></button><br><button><a href='" . get_site_url() . "/wholesales/denegateuser?name=" . $name . "&email=" . $email . "&phone=" . $phone . "' target='_blank'>DENEGAR</a></button>";
        $headers = array(
            'Reply-To' => $name . '<' . $email . '>'
        );
        if (send_custom_email($to,$subject,$content,'Solicitud para crear usuario mayorista')) {
            
            $status = true;
        }
        
        return $status;
    }

    function set_html_content_type()
    {
        
        return 'text/html';
    }
    
}


add_action('parse_request', 'create_wholesale_user', 0);

add_action('parse_request', 'download_masive_products', 0);

add_action('init', 'add_endpoint');

function add_endpoint()
{

    add_rewrite_endpoint('wholesales', EP_PERMALINK | EP_PAGES, true);

    add_rewrite_endpoint('downloadProducts/wholesales', EP_PERMALINK | EP_PAGES, true);

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

function wc_get_variable_product_stock_quantity( $output = 'raw', $product_id = 0 ){
    global $wpdb, $product;

    // Get the product ID (can be defined)
    $product_id = $product_id > 0 ? $product_id : get_the_id();

    // Check and get the instance of the WC_Product Object
    $product = is_a( $product, 'WC_Product' ) ? $product : wc_get_product($product_id);

    // Only for variable product type
    if( $product->is_type('variable') ){

        // Get the stock quantity sum of all product variations (children)
        $stock_quantity = $wpdb->get_var("
            SELECT SUM(pm.meta_value)
            FROM {$wpdb->prefix}posts as p
            JOIN {$wpdb->prefix}postmeta as pm ON p.ID = pm.post_id
            WHERE p.post_type = 'product_variation'
            AND p.post_status = 'publish'
            AND p.post_parent = '$product_id'
            AND pm.meta_key = '_stock'
            AND pm.meta_value IS NOT NULL
        ");

        // Preparing formatted output
        if ( $stock_quantity > 0 ) {
            $html = '<p class="stock in-stock">'. sprintf( __("%s in stock", "woocommerce"), $stock_quantity ).'</p>';
        } else {
            if ( is_numeric($stock_quantity) )
                $html = '<p class="stock out-of-stock">' . __("Out of stock", "woocommerce") . '</p>';
            else
                $html = '';
        }

        // Different output options
        if( $output == 'echo_html' )
            echo $html;
        elseif( $output == 'return_html' )
            return $html;
        else
            return $stock_quantity;
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

                wp_redirect( admin_url("/user-edit.php?user_id=".$user_id) );

                die();
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



/////////////////////////////////////////////////////


// Add column (working)
add_filter( 'manage_edit-shop_order_columns', 'custom_woo_columns_function' );
function custom_woo_columns_function( $columns ) {
    $new_columns = ( is_array( $columns ) ) ? $columns : array();
    unset( $new_columns[ 'order_actions' ] );

    // all of your columns will be added before the actions column
    $new_columns['paid_date'] = 'Fecha pago';


    return $new_columns;
}

// Change order of columns ==> changed (working)
add_action( 'manage_shop_order_posts_custom_column', 'custom_woo_admin_value', 2 );
function custom_woo_admin_value( $column ) {
    global $post, $the_order;

    if ( empty( $the_order ) || $the_order->get_id() != $post->ID ) {
        $the_order = wc_get_order( $post->ID );
    }

    if ( $column == 'paid_date' ) {
        $paid_date = get_post_meta($post->ID, '_paid_date',true);
        $myDateTime = DateTime::createFromFormat('Y-m-d H:i:s', $paid_date);
        echo empty($paid_date) ? '' : /*date("d/m/Y H:m", strtotime($paid_date))*/$myDateTime->format('Y-m-d H:i');
    }
}

// Sort by custom column ==> changed (working)
add_filter( "manage_edit-shop_order_sortable_columns", 'custom_woo_admin_sort' );
function custom_woo_admin_sort( $columns )
{
    $custom = array(
        'paid_date'    => '_paid_date',
    );
    return wp_parse_args( $custom, $columns );
}

// Make '_shipping_postcode' metakey searchable in the shop orders list
add_filter( 'woocommerce_shop_order_search_fields', 'shipping_postcode_searchable_field', 10, 1 );
function shipping_postcode_searchable_field( $meta_keys ){
    $meta_keys[] = '_paid_date';
    return $meta_keys;
}

// Filter wp_nav_menu() to add additional links and other output


//$gen_settings = get_option('phoe_disc_value');
    
  //  $enable_disc = isset($gen_settings['enable_disc']) ? $gen_settings['enable_disc'] : '';


//[wholesale_request_form]    - solicitud-mayoristas

add_action( 'admin_head', 'my_custom_admin_styles' );
function my_custom_admin_styles() {

    // HIDE "New Order" button when current user don't have 'manage_options' admin user role capability
    if( ! current_user_can( 'manage_options' ) ):
    ?>
        <style>
            .post-type-shop_order #wpbody-content > div.wrap > a.page-title-action{
                display: none !important;
            }
        </style>
    <?php
    
    endif;
    
}
add_filter( 'woocommerce_register_post_type_shop_order','remove_add_new_order_admin' );
function remove_add_new_order_admin($fields) {

    if( ! current_user_can( 'manage_options' ) ){
            $fields['capabilities'] = array(
                    'create_posts' => false,
            );
         }
        return $fields;
}

add_filter( 'bulk_actions-edit-shop_order', 'remove_bulk_options_cs', 120 );

function remove_bulk_options_cs($options){

    if( ! current_user_can( 'manage_options' ) &&  in_array( 'admin_junior', $user_roles, true ) ){
    
        unset($options['mark_processing']);
        unset($options['mark_completed']);
        unset($options['mark_completed_ajax']);
        
    }
    
    $user_id = get_current_user_id();
    
    $user = get_userdata( $user_id );
    
    $user_roles = $user->roles;

    if ( in_array( 'admin_junior', $user_roles, true ) ) {

            unset($options['mark_processing']);
            unset($options['woe_export_selected_orders']);
            unset($options['woe_mark_exported']);
            unset($options['woe_unmark_exported']);
            unset($options['mark_on-hold']);
            unset($options['alegra_invoice']);
            unset($options['complete_only_proccess']);
        
            
    }   
    return $options;

}

add_filter( 'emoji_svg_url', '__return_false' );

function add_admin_scripts( $hook ) {

    $user_id = get_current_user_id();
    
    $user = get_userdata( $user_id );
    
    $user_roles = $user->roles;

    if ( $hook == 'post.php' &&  ( !current_user_can( 'manage_options' ) || in_array( 'admin_junior', $user_roles, true ))) {
    
        wp_enqueue_script('disable-proccessing-status', plugin_dir_url(__FILE__) . 'assets/js/admin-status.js',array('jquery'),'1.2');
    
    }
    
}
add_action('admin_enqueue_scripts','add_admin_scripts',10,1);



#hook que coloca la accion de generar factura  en ordenes
add_filter( 'bulk_actions-edit-shop_order',  'cs_order_complete_option') ;
#hook que recibe la accion de generar factura en ordenes
add_filter( 'handle_bulk_actions-edit-shop_order', 'cs_order_complete_handler' , 10, 3 );
#hook que coloca mensaje de succes al procesar facturas
add_action( 'admin_notices', 'cs_order_complete_notice');

function cs_order_complete_option(){

    $bulk_actions['complete_only_proccess'] = __( 'Cambiar estado a completado(cs)', 'woocommerce');

    return $bulk_actions;
}

function cs_order_complete_handler($redirect_to, $doaction, $post_ids){

    global $wpdb;

    if ( $doaction !== 'complete_only_proccess' ) {
        return $redirect_to;
    }

    $new_status = 'completed';

    foreach ( $post_ids as $id ) {

        $order = wc_get_order( $id );

        $changed = 0;

        if ($order->get_status() == 'processing') {
            
            $order->update_status( $new_status, __( 'Order status changed by bulk edit:', 'woocommerce' ), true );
    
            do_action( 'woocommerce_order_edit_status', $id, $new_status );
    
            $changed++;
        }
    }

    $redirect_to = add_query_arg( 'cs_completed_orders', $changed , $redirect_to );
    
    return $redirect_to;
}

function cs_order_complete_notice(){

    if ( ! empty( $_REQUEST['cs_completed_orders'] ) ) {
        $emailed_count = intval( $_REQUEST['cs_completed_orders'] );
        $result = 1;
        printf( '<div id="message" class="updated fade">Se cambiaron %s ordenes a completado</div>', $emailed_count );
      }
}





