<?php

/**
 * Calculate affiliate all times earnings
 * @param int $affiliate_id
 */
function calculate_affiliate_all_time_earnings($affiliate_id) {
    global $wpdb;
    $referrals_table = $wpdb->prefix . "affiliatesuite_referrals";
    $query = "SELECT SUM(amount) as total_earned FROM $referrals_table WHERE affiliate_id = $affiliate_id 
                AND (status='unpaid' OR status='paid')";
    $results = $wpdb->get_results($query);

    if ($results) {
        return $results[0]->total_earned;
    } else {
        return 0;
    }
}

/**
 * Get affiliate week report
 * @param int $affiliate_id
 */
function as_get_affiliate_week_report($affiliate_id=0) {

    if ( wp_doing_ajax() && isset($_POST['affiliate_id']) ) {
        $affiliate_id = $_POST['affiliate_id'];
    }

    $seven_days_ago = date('Y-m-d H:m:s', strtotime('- 1 week'));

    global $wpdb;
    $referrals_table = $wpdb->prefix . 'affiliatesuite_referrals';
    $query = "SELECT DATE_FORMAT(date, '%Y-%m-%d') as day, COUNT(id) as referrals_count, SUM(amount) as day_total
                FROM $referrals_table WHERE affiliate_id = $affiliate_id
                AND date >= '$seven_days_ago'
                AND (status='unpaid' OR status='paid')
                GROUP BY DATE_FORMAT(date, '%Y-%m-%d')";
    
    $referrals = $wpdb->get_results($query);

    if ($referrals) {
        // Fomarting data
        $report_data = array();

        foreach ($referrals as $referral) {
            $report_data[$referral->day] = array(
                'referrals_count' => $referral->referrals_count,
                'day_total'       => $referral->day_total
            ); 
        }

        // Make sure to include all days of the week
        for ($i=1; $i <= 7; $i++) {  // Seven days of week
            $week_day = date('Y-m-d', strtotime("-$i days"));
            
            if ( ! isset($report_data[$week_day]) ) {
                $report_data[$week_day] = array(
                    'referrals_count' => 0,
                    'day_total'       => 0
                );
            }
        }

        // Order by date asc
        uksort($report_data, function($a, $b) {
            return strtotime($a) - strtotime($b);
        });

    } else {
        $report_data = false;
    }

    if ( wp_doing_ajax() ) {
        echo json_encode($report_data);
        wp_die();
    } else {
        return $report_data;
    }
}

/**
 * Get affiliate (current) month report
 * @param int $affiliate_id
 */
function as_get_affiliate_month_report($affiliate_id=0, $date_start='', $date_end='') {
    
    // WooCommerce updates order status via ajax, checking for affiliate_id prevents it to use these values
    if ( wp_doing_ajax() && isset($_POST['affiliate_id']) && !is_checkout() ) {
        $affiliate_id = $_POST['affiliate_id'];
        $date_start = $_POST['date_start'];
        $date_end = $_POST['date_end'];
    }

    global $wpdb;
    $referrals_table = $wpdb->prefix . 'affiliatesuite_referrals';
    $query = "SELECT DATE_FORMAT(date, '%Y-%m-%d') as day, COUNT(id) as referrals_count, SUM(amount) as day_total
                FROM $referrals_table WHERE affiliate_id = $affiliate_id
                AND date >= '$date_start' AND date <= '$date_end'
                AND (status='unpaid' OR status='paid')
                GROUP BY DATE_FORMAT(date, '%Y-%m-%d')";
    
    $referrals = $wpdb->get_results($query);

    if ($referrals) {
        // Fomarting data
        $report_data = array();

        foreach ($referrals as $referral) {
            $report_data[$referral->day] = array(
                'referrals_count' => $referral->referrals_count,
                'day_total'       => $referral->day_total
            ); 
        }

        // Order by date asc
        uksort($report_data, function($a, $b) {
            return strtotime($a) - strtotime($b);
        });

    } else {
        $report_data = false;
    }

    if ( wp_doing_ajax() && !is_checkout() ) {
        echo json_encode($report_data);
        wp_die();
    } else {
        return $report_data;
    }
}

/**
 * Get affiliate previous month report
 * @param int $affiliate_id
 */
function as_get_affiliate_previous_month_report($affiliate_id=0, $date_start='', $date_end='') {
    
    if ( wp_doing_ajax() ) {
        $affiliate_id = $_POST['affiliate_id'];
        $date_start = $_POST['date_start'];
        $date_end = $_POST['date_end'];
    }

    global $wpdb;
    $referrals_table = $wpdb->prefix . 'affiliatesuite_referrals';
    $query = "SELECT DATE_FORMAT(date, '%Y-%m-%d') as day, COUNT(id) as referrals_count, SUM(amount) as day_total
                FROM $referrals_table WHERE affiliate_id = $affiliate_id
                AND date >= '$date_start' AND date <= '$date_end'
                AND (status='unpaid' OR status='paid')
                GROUP BY DATE_FORMAT(date, '%Y-%m-%d')";
    
    $referrals = $wpdb->get_results($query);

    if ($referrals) {
        // Fomarting data
        $report_data = array();

        foreach ($referrals as $referral) {
            $report_data[$referral->day] = array(
                'referrals_count' => $referral->referrals_count,
                'day_total'       => $referral->day_total
            ); 
        }

        // Order by date asc
        uksort($report_data, function($a, $b) {
            return strtotime($a) - strtotime($b);
        });

    } else {
        $report_data = false;
    }

    if ( wp_doing_ajax() ) {
        echo json_encode($report_data);
        wp_die();
    } else {
        return $report_data;
    }
}

/**
 * Get affiliate (current) year report
 * @param int $affiliate_id
 */
function as_get_affiliate_year_report($affiliate_id=0, $date_start='', $date_end='') {
    
    if ( wp_doing_ajax() ) {
        $affiliate_id = $_POST['affiliate_id'];
        $date_start = $_POST['date_start'];
        $date_end = $_POST['date_end'];
    }

    global $wpdb;
    $referrals_table = $wpdb->prefix . 'affiliatesuite_referrals';
    $query = "SELECT DATE_FORMAT(date, '%m') as month, COUNT(id) as referrals_count, SUM(amount) as month_total
                FROM $referrals_table WHERE affiliate_id = $affiliate_id
                AND date >= '$date_start' AND date <= '$date_end'
                AND (status='unpaid' OR status='paid')
                GROUP BY DATE_FORMAT(date, '%m')";
    
    $referrals = $wpdb->get_results($query);

    if ($referrals) {
        // Fomarting data
        $report_data = array();

        foreach ($referrals as $referral) {
            $report_data[$referral->month] = array(
                'referrals_count' => $referral->referrals_count,
                'month_total'       => $referral->month_total
            ); 
        }

        // Order by date asc
        uksort($report_data, function($a, $b) {
            return strtotime($a) - strtotime($b);
        });

    } else {
        $report_data = false;
    }

    if ( wp_doing_ajax() ) {
        echo json_encode($report_data);
        wp_die();
    } else {
        return $report_data;
    }
}

/**
 * Get affiliate top products
 * @param int $affiliate_id
 */

function as_affiliate_top_products($affiliate_id=null) {

    if( wp_doing_ajax() ) {
        $affiliate_id = $_POST['affiliate_id'];
    }

    global $wpdb;
    $referrals_table = $wpdb->prefix . "affiliatesuite_referrals";
    $query = "SELECT products FROM $referrals_table WHERE affiliate_id = $affiliate_id";

    $results = $wpdb->get_results($query);

    if ($results) {
        foreach ($results as $result) {
            $result->products = unserialize($result->products);
        }

        $products = array();

        foreach ($results as $result) {
            foreach ($result->products as $product) {
                $products[$product['id']] = isset($products[$product['id']]) ? $products[$product['id']] + $product['qty'] : $product['qty'];
            }
        }

        arsort($products);

        if ( count($products) > 5 ) {
            $products = array_slice($products, 0, 5, true);
        }

        $woo_products = wc_get_products(array(
            'include' => array_keys($products)
        ));

        $top_products = array();

        foreach ($woo_products as $woo_product) {
            $top_products[$woo_product->get_name()] = $products[$woo_product->get_id()];
        }

        arsort($top_products);

    } else {
        $top_products = false;
    } 

    if ( wp_doing_ajax() ) {
        echo json_encode($top_products);
        wp_die();
    } else {
        return $top_products;
    }
}

/**
 * Returns a string of referral products
 * @param Object $referral
 * @param String $delimiter
 */
function as_referral_products_stringify($referral, $delimiter=' - ') {
    $products_str = '';
    $products_count = count($referral->products);

    foreach ( $referral->products as $key => $product ) {

        if ( $key == ($products_count - 1) ) {
            $products_str .= ' ' . $product['name'];
        } else {
            $products_str .= ' ' . $product['name'] . $delimiter;
        }
    }
    return $products_str;
}

/**
 * Returns a string of referral products
 * @param Array $products
 * @param String $delimiter
 */
function as_products_stringify($products, $delimiter=' - ') {
    $products_str = '';
    $products_count = count($products);

    foreach ( $products as $key => $product ) {

        if ( $key == ($products_count - 1) ) {
            $products_str .= ' ' . $product['name'];
        } else {
            $products_str .= ' ' . $product['name'] . $delimiter;
        }
    }
    return $products_str;
}