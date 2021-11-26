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

require_once ABSPATH. '/vendor/autoload.php';

require_once 'admin/admin.php';

require_once 'includes/helper-functions.php';

require_once 'includes/script-styles-functions.php';

require_once 'includes/email-functions.php';

require_once 'includes/admin-functions.php';

require_once 'includes/front-functions.php';

require_once 'includes/cart-functions.php';


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

            if ($discount_type == 'price' && isset($values['wholesales_price_discount'])) {

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
        // CUSTOM
        add_action('admin_init', 'wholesale_role');

        #PAYMENT METHOD
        add_filter('woocommerce_payment_gateways', 'wholesales_add_gateway_class');
        #PAYMENT METHOD
        add_action('plugins_loaded', 'wholesales_init_gateway_class');


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

}

///// TODO REFACTORIZACION FUNCIONES EXTRA
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

    $bulk_actions['complete_only_proccess'] = __( 'Cambiar estado a completado(OLD)', 'woocommerce');

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





