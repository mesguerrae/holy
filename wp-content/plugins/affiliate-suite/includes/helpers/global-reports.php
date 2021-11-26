<?php

/**
 * Get total comissions amount
 * @param string $date_start
 * @param string $date_end
 */

add_action( 'wp_ajax_as_get_total_comissions_amount', 'as_get_total_comissions_amount' );

function as_get_total_comissions_amount($date_start='', $date_end='') {

    if ( wp_doing_ajax() ) {
        $date_start = isset($_POST['date_start']) ? $_POST['date_start'] : '';
        $date_end = isset($_POST['date_end']) ? $_POST['date_end'] : '';
    }

    global $wpdb;
    $referrals_table = $wpdb->prefix . "affiliatesuite_referrals";
    $query = "SELECT SUM(amount) as amount FROM $referrals_table WHERE (status='unpaid' OR status='paid')";

    if ( $date_start ) {
        $query .= " AND date >= '$date_start'";
    }

    if ( $date_end ) {
        $query .= " AND date <= '$date_end'";
    }
    
    $results = $wpdb->get_results($query);
    
    if ($results) {
        $comissions_amount = $results[0]->amount;
    } else {
        $comissions_amount = 0;
    }

    if ( wp_doing_ajax() ) {
        echo json_encode($comissions_amount);
        wp_die();
    } else {
        return $comissions_amount;
    }
}

/**
 * Get affiliate week report
 */
add_action( 'wp_ajax_as_get_global_week_report', 'as_get_global_week_report' );

function as_get_global_week_report() {

    $seven_days_ago = date('Y-m-d H:m:s', strtotime('- 1 week'));

    global $wpdb;
    $referrals_table = $wpdb->prefix . 'affiliatesuite_referrals';
    $query = "SELECT DATE_FORMAT(date, '%Y-%m-%d') as day, COUNT(id) as referrals_count, SUM(amount) as day_total
                FROM $referrals_table
                WHERE date >= '$seven_days_ago'
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
 * Get global (current) month report
 */
add_action( 'wp_ajax_as_get_global_month_report', 'as_get_global_month_report' );

function as_get_global_month_report($date_start='', $date_end='') {
    
    // WooCommerce updates order status via ajax, checking for affiliate_id prevents it to use these values
    if ( wp_doing_ajax() && isset($_POST['affiliate_id']) && !is_checkout() ) {
        $date_start = $_POST['date_start'];
        $date_end = $_POST['date_end'];
    }

    global $wpdb;
    $referrals_table = $wpdb->prefix . 'affiliatesuite_referrals';
    $query = "SELECT DATE_FORMAT(date, '%Y-%m-%d') as day, COUNT(id) as referrals_count, SUM(amount) as day_total
                FROM $referrals_table
                WHERE date >= '$date_start' AND date <= '$date_end'
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
 * Get global previous month report
 * @param int $affiliate_id
 */
add_action( 'wp_ajax_as_get_global_previous_month_report', 'as_get_global_previous_month_report' );

function as_get_global_previous_month_report($date_start='', $date_end='') {
    
    if ( wp_doing_ajax() ) {
        $date_start = $_POST['date_start'];
        $date_end = $_POST['date_end'];
    }

    global $wpdb;
    $referrals_table = $wpdb->prefix . 'affiliatesuite_referrals';
    $query = "SELECT DATE_FORMAT(date, '%Y-%m-%d') as day, COUNT(id) as referrals_count, SUM(amount) as day_total
                FROM $referrals_table
                WHERE date >= '$date_start' AND date <= '$date_end'
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
 * Get global (current) year report
 */
add_action( 'wp_ajax_as_get_global_year_report', 'as_get_global_year_report' );

function as_get_global_year_report($date_start='', $date_end='') {
    
    if ( wp_doing_ajax() ) {
        $date_start = $_POST['date_start'];
        $date_end = $_POST['date_end'];
    }

    global $wpdb;
    $referrals_table = $wpdb->prefix . 'affiliatesuite_referrals';
    $query = "SELECT DATE_FORMAT(date, '%m') as month, COUNT(id) as referrals_count, SUM(amount) as month_total
                FROM $referrals_table
                WHERE date >= '$date_start' AND date <= '$date_end'
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