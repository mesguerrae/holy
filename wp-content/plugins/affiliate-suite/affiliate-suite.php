<?php
/*
Plugin Name: Affiliate Suite
Plugin URI:  http://isaiassubero.com
Description: Run an affiliate marketing program on WordPress
Version:     1.7.1
Author:      Isaías Subero
Author URI:  http://isaiassubero.com
License:     GPL2
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Text Domain: affiliate-suite
Domain Path: /languages
*/

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Affiliate_Suite {

    public function __construct() {
        // Create database table
        register_activation_hook( __FILE__, array($this, 'activation_functions') );

        // Composer dependencies
        require __DIR__ . '/vendor/autoload.php';
        
        // Include Helpers functions
        include 'includes/helpers/affiliate.php';
        include 'includes/helpers/global-reports.php';

        // Include API
        include 'rest-api/api.php';

        // Enqueue admin scripts
        add_action( 'admin_enqueue_scripts', array($this, 'register_admin_scripts'), 10, 1 );
        
        // Create menu pages
        add_action( 'admin_menu', array($this, 'create_menu_pages') );

        // Plugin settings
        $this->settings_init();

        // Set affiliate reference cookie
        add_action( 'init', array($this, 'set_affiliate_cookie') );

        // Save custom order meta data (affiliate cookie ref)
        add_action( 'woocommerce_new_order', array($this, 'add_affiliate_ref_to_order'), 20, 2 );

        // Maybe add referral from WooCommerce
        add_action( 'woocommerce_order_status_changed', array($this, 'maybe_add_referral_woocommerce'), 30, 4 );

        // Change referral to status to rejected
        add_action( 'woocommerce_order_status_changed', array($this, 'update_referral_status_to_rejected'), 40, 4 );

        // Register shortcode
        add_shortcode( 'as_affiliate_dashboard', array($this, 'affiliate_dashboard_handler') );

        // Login redirect
        add_filter( 'login_redirect', array($this, 'login_redirect_handler'), 50, 3 );

        // New referral notificacion
        // add_action( 'as_referral_created', array($this, 'new_referral_notification'), 10, 2 );

        // Ajax functions
        add_action( 'wp_ajax_get_referrals', array($this, 'get_referrals') );
        add_action( 'wp_ajax_nopriv_get_referrals', array($this, 'get_referrals') );

        add_action( 'wp_ajax_recalculate_referrals_amounts', array($this, 'recalculate_referrals_amounts') );
        add_action( 'wp_ajax_nopriv_recalculate_referrals_amounts', array($this, 'recalculate_referrals_amounts') );

        add_action( 'wp_ajax_get_non_affiliates', array($this, 'get_non_affiliates') );
        add_action( 'wp_ajax_get_woocommerce_unbinded_coupons', array($this, 'get_woocommerce_unbinded_coupons') );

        add_action( 'wp_ajax_get_unimported_woocommerce_referrals', array($this, 'get_unimported_woocommerce_referrals') );
        add_action( 'wp_ajax_import_unimported_woocommerce_referrals', array($this, 'import_unimported_woocommerce_referrals') );
        
        add_action( 'wp_ajax_import_affiliatewp_referrals', array($this, 'import_affiliatewp_referrals') );
        add_action( 'wp_ajax_get_affiliates_ranking', array($this, 'get_affiliates_ranking') );

        add_action( 'wp_ajax_as_get_affiliate_week_report', 'as_get_affiliate_week_report' );
        add_action( 'wp_ajax_nopriv_as_get_affiliate_week_report','as_get_affiliate_week_report' );

        add_action( 'wp_ajax_as_get_affiliate_month_report', 'as_get_affiliate_month_report' );
        add_action( 'wp_ajax_nopriv_as_get_affiliate_month_report','as_get_affiliate_month_report' );

        add_action( 'wp_ajax_as_get_affiliate_previous_month_report', 'as_get_affiliate_previous_month_report' );
        add_action( 'wp_ajax_nopriv_as_get_affiliate_previous_month_report','as_get_affiliate_previous_month_report' );

        add_action( 'wp_ajax_as_get_affiliate_year_report', 'as_get_affiliate_year_report' );
        add_action( 'wp_ajax_nopriv_as_get_affiliate_year_report','as_get_affiliate_year_report' );
        
        add_action( 'wp_ajax_as_affiliate_top_products', 'as_affiliate_top_products' );
        add_action( 'wp_ajax_nopriv_as_affiliate_top_products', 'as_affiliate_top_products' );
        
        add_action( 'wp_ajax_export_csv_referrals', array($this, 'export_csv_referrals') );
        add_action( 'wp_ajax_nopriv_export_csv_referrals', array($this, 'export_csv_referrals') );

        add_action( 'wp_ajax_export_csv_referrals_woocommerce', array($this, 'export_csv_referrals_woocommerce') );
        add_action( 'wp_ajax_nopriv_export_csv_referrals_woocommerce', array($this, 'export_csv_referrals_woocommerce') );
    }

    /**
     * Activation functions
     * (runs only when plugin is activated)
     */
    public function activation_functions() {

        /* Create referrals database table */
		global $wpdb;
		$table_name = $wpdb->prefix . "affiliatesuite_referrals";
		$charset_collate = $wpdb->get_charset_collate();
		
		$sql = "CREATE TABLE $table_name (
			id mediumint(9) NOT NULL AUTO_INCREMENT,
			affiliate_id bigint(20) NOT NULL,
			reference text NOT NULL,
			products longtext NOT NULL,
			amount decimal(20,2) NOT NULL,
            currency char(3) NOT NULL,
			status text NOT NULL,
            channel text NOT NULL,
            date datetime NOT NULL,
			PRIMARY KEY  (id)
			) $charset_collate;";
		  
		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        dbDelta( $sql );
        
        /* Create 'as_affiliate' role */
        add_role(
            'as_affiliate',
            __('Affiliate', 'affiliate-suite'),
            array(
                'read'  => true
            )
        );
	}

    /**
	 * Register admin scripts
	 */
	public function register_admin_scripts($hook) {
        
        $pages = array(
            'toplevel_page_affiliate-suite',
            'afilliate-suite_page_list-affiliates',
            'admin_page_add-affiliate',
            'admin_page_edit-affiliate',
            'admin_page_single-affiliate-report',
            'afilliate-suite_page_affiliatesuite-export'
        );

		if ( in_array($hook, $pages) ) {

            // Moment.js
            wp_enqueue_script('moment', WP_PLUGIN_URL . '/affiliate-suite/node_modules/moment/moment.js', array('jquery'), null, false);

            // Chart.js
            wp_enqueue_script('chartjs', WP_PLUGIN_URL . '/affiliate-suite/node_modules/chart.js/dist/Chart.bundle.min.js', array('jquery'), null, false);

            // JQuery DataTables
            wp_enqueue_style('DataTables', 'https://cdn.datatables.net/1.10.19/css/jquery.dataTables.css');
            wp_enqueue_script('DataTables', 'https://cdn.datatables.net/1.10.19/js/jquery.dataTables.js', array('jquery'));

            // Flatpickr
            wp_enqueue_style('flatpickr', WP_PLUGIN_URL . '/affiliate-suite/node_modules/flatpickr/dist/flatpickr.min.css');
            wp_enqueue_script('flatpickr', WP_PLUGIN_URL . '/affiliate-suite/node_modules/flatpickr/dist/flatpickr.min.js');

            // MagicSuggest
            wp_enqueue_style('magicSuggest', WP_PLUGIN_URL . '/affiliate-suite/lib/magicsuggest/magicsuggest-min.css');
            wp_enqueue_script('magicSuggest', WP_PLUGIN_URL . '/affiliate-suite/lib/magicsuggest/magicsuggest-min.js', array('jquery'), null, false);

		    wp_enqueue_style( 'affiliate-suite', WP_PLUGIN_URL . '/affiliate-suite/assets/css/admin.css', null, $this->version  );

			wp_enqueue_script( 'affiliate-suite-admin-scripts', WP_PLUGIN_URL . '/affiliate-suite/assets/js/admin.js', array('jquery', 'moment'), $this->version, true );
			wp_localize_script('affiliate-suite-admin-scripts', 'affiliateSuite', array(
				'ajax_url' 		  => admin_url( 'admin-ajax.php' ),
                'currency'        => get_woocommerce_currency(),
                'currency_symbol' => get_woocommerce_currency_symbol()
            ));
            
		}
    }
    
    /**
     * Settins init
     */
    public function settings_init() {
        require 'includes/settings.php';
    }

    /**
     * Create admin menu page
     */
    public function create_menu_pages() {
        
        // Affiliates (Main admin page)
        add_menu_page(
			__('Affiliate Suite', 'affiliate-suite'),                       // page_title
			__('Afilliate Suite', 'affiliate-suite'),                       // menu_title
			'manage_options',                                               // capability
			'affiliate-suite',                                              // menu_slug
			'',                                                             // callback
			plugin_dir_url( __FILE__ ) . '/assets/icons8-people.png',       // icon_url
			25                                                              // position
        );

        // Global reports
        add_submenu_page(
            'affiliate-suite',                          // parent_slug
            __('Reports', 'affiliate-suite'),           // page_title
            __('Reports', 'affiliate-suite'),           // menu_title
            'manage_options',                           // capability
            'affiliate-suite',                          // menu_slug
            array($this, 'reports_submenu_callback')    // callback
        );
        
        // List affiliates
        add_submenu_page(
			'affiliate-suite',                              // parent_slug
			__('Affiliates', 'affiliate-suite'),            // page_title 
			__('Affiliates', 'affiliate-suite'),            // menu_title
			'manage_options',                               // capability
			'list-affiliates',                              // menu_slug
			array($this, 'affiliates_submenu_callback')     // callback
        );
        
        // Add affiliate
        add_submenu_page(
			null,                                           // parent_slug
			__('Register affiliate', 'affiliate-suite'),    // page_title 
			__('Register affiliate', 'affiliate-suite'),    // menu_title
			'manage_options',                               // capability
			'add-affiliate',                                // menu_slug
			array($this, 'add_affiliate_submenu_callback')  // callback
        );

        // Edit affiliate
        add_submenu_page(
            null,                                           // parent_slug
            __('Edit affiliate', 'affiliate-suite'),        // page_title
            __('Edit affiliate', 'affiliate-suite'),        // menu_title
            'manage_options',                               // capability
            'edit-affiliate',                               // menu_slug
            array($this, 'edit_affiliate_submenu_callback') // callback
        );

        // Single affiliate report
        add_submenu_page(
            null,                                            // parent_slug
            __('Affiliate Report', 'affiliate-suite'),       // page_title
            __('Affiliate Report', 'affiliate-suite'),       // menu_title
            'manage_options',                                // capability
            'single-affiliate-report',                       // menu_slug
            array($this, 'single_affiliate_report_callback') // callback
        );

        // Export
        add_submenu_page(
            'affiliate-suite',                      // parent_slug
            __('Export', 'affiliate-suite'),        // page_title
            __('Export', 'affiliate-suite'),        // menu_title
            'manage_options',                       // capability
            'affiliatesuite-export',                // menu_slug
            array($this, 'export_page_callback')    // callback
        );
    }

    public function reports_submenu_callback() {
        include 'includes/controllers/affiliates-controller.php';
        $referrals = $this->get_referrals();
        include 'includes/views/global-reports-submenu.php';
    }

    public function affiliates_submenu_callback() {
        include 'includes/controllers/affiliates-controller.php';
        $affiliates = Affiliates_Controller::get_affiliates();
        include 'includes/views/affiliates-submenu.php';
    }

    public function add_affiliate_submenu_callback() {
        include 'includes/controllers/affiliates-controller.php';
        include 'includes/views/add-affiliate-submenu.php';
    }

    public function edit_affiliate_submenu_callback() {
        include 'includes/controllers/affiliates-controller.php';
        $affiliate = Affiliates_Controller::get_affiliate($_GET['affiliate']);
        include 'includes/views/edit-affiliate-submenu.php';
    }

    public function single_affiliate_report_callback() {
        include 'includes/controllers/affiliates-controller.php';
        $affiliate = Affiliates_Controller::get_affiliate($_GET['affiliate']);
        $referrals = Affiliates_Controller::get_affiliate_referrals($affiliate->ID);
        include 'includes/views/single-affiliate-report.php';
    }

    public function export_page_callback() {
        include 'includes/views/export-page.php';
    }

    /**
     * Notices handler
     */
    public static function do_notices() {
        if ( ! isset($_GET['notice']) ) { return; }

        switch ($_GET['notice']) {
            case 'user_exists':
                echo '<div class="notice notice-error is-dismissible">';
                echo '<p>' . __( 'User already exists, please use the <b>convert existing user to affiliate</b> option instead.', 'affiliate-suite' ) . '</p>';
                echo '</div>';
                break;
        }
    }

    /**
     * Get users who are not affiliates
     */
    public static function get_non_affiliates() {
        $user_query = new WP_User_Query( array('role__not_in' => 'as_affiliate') );
        echo json_encode( $user_query->get_results() );
        wp_die();
    }

    /**
     * Get active channels
     */
    public static function get_active_channels() {
        $channels = get_option( 'affiliate_suite_channels' );
        if ( ! $channels ) {
            $channels = array();
        }
        return $channels;
    }

    /**
     * Get WooCommerce unbinded coupons
     * returns a list of coupons that haven't been assignend to any affiliate.
     */
    public static function get_woocommerce_unbinded_coupons() {
    
        $args = array(
            'posts_per_page'    => -1,
            'order_by'          => 'title',
            'order'             => 'asc',
            'post_type'         => 'shop_coupon',
            'post_status'       => 'publish'
        );

        $coupons = get_posts($args); // All WooCommerce Coupons (WP_Post objects)

        $coupon_codes = array();
        foreach ($coupons as $coupon) {
            $coupon_codes[] = strtolower($coupon->post_title);
        }

        // Get binded_coupons
        global $wpdb;

        $query_string = "SELECT meta_value FROM $wpdb->usermeta WHERE meta_key = 'as_affiliate_woo_coupons'";
        $results = $wpdb->get_results( $query_string, OBJECT );

        $binded_coupons = array(); // coupons assigned to some affiliate

        if ($results) {
            foreach($results as $row) {
                if ( $row->meta_value != null ) {
                    $taken_coupons = unserialize($row->meta_value);
                    foreach ($taken_coupons as $taken_coupon) {
                        $binded_coupons[] = $taken_coupon;
                    }
                }
            }
        }

        // filter
        $unbinded_coupons = array();

        foreach ($coupon_codes as $coupon_code) {
            if ( ! in_array($coupon_code, $binded_coupons) ) {
                $unbinded_coupons[] = $coupon_code;   
            }
        }

        if ( wp_doing_ajax() ) {
            echo json_encode($unbinded_coupons);
            wp_die();
        } else {
            return $unbinded_coupons;
        }
    }

    /**
     * Get WooCommerce order affiliate owner
     * Returns the user id of the affiliate for the order
     */
    public static function get_woocommerce_order_affiliate($order) {

        // Check if order has an affiliate ref (affiliate id cookie reference)
        $affiliate_ref = get_post_meta( $order->get_id(), 'affiliate_ref', true );

        if ( $affiliate_ref ) {
            return intval($affiliate_ref);
        }

        // Check coupons used in the order
        $order_coupons = $order->get_coupon_codes();

        // Check if these coupons belongs to any affiliate
        if ( $order_coupons ) {
            foreach ($order_coupons as $order_coupon) {
                global $wpdb;
                $query_string = "SELECT user_id, meta_value 
                                    FROM $wpdb->usermeta WHERE meta_key = 'as_affiliate_woo_coupons'
                                    AND meta_value LIKE '%$order_coupon%'";

                $results = $wpdb->get_results( $query_string, OBJECT );

                if ($results) {
                    foreach ($results as $result) {
                        $affiliate_coupons = unserialize($result->meta_value);

                        if ( array_intersect($order_coupons, $affiliate_coupons) ) {
                            return $result->user_id;
                        }
                    }
                } else {
                    return false;
                }

            } // end foreach
        } else {
            return false;
        }
    }

    /**
     * Get coupon affiliate
     * Returns the affiliate id binded to a woocommerce coupon
     */
    public static function get_coupon_affiliate($coupon_code) {
        global $wpdb;
        $query_string = "SELECT user_id, meta_value 
                            FROM $wpdb->usermeta WHERE meta_key = 'as_affiliate_woo_coupons'
                            AND meta_value LIKE '%$coupon_code%'";

        $row = $wpdb->get_row( $query_string, OBJECT );

        if ( $row ) {
            $affiliate_coupons = unserialize($row->meta_value);

            if ( in_array($coupon_code, $affiliate_coupons) ) {
                return (int)$row->user_id;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    /**
     * Serialize referral items (for storing in database)
     */
    public static function serialize_referral_items($order) {
        $items = array();
        foreach ($order->get_items() as $item) {
            
            $product = array(
                'id'    => $item->get_id(),
                'name'  => $item->get_name(),
                'qty'   => $item->get_quantity()
            );

            $items[] = $product;
        }
        $serialized_items = serialize($items);
        return $serialized_items;
    }

    /**
     * Set affiliate cookie
     */
    public function set_affiliate_cookie() {
        if ( ! isset( $_GET['ar'] ) ) {
            return;
        }

        // Validate the ID corresponds to an existing affiliate
        include 'includes/controllers/affiliates-controller.php';
        $user = Affiliates_Controller::get_affiliate( $_GET['ar'] );

        if ( $user ) {
            setcookie('affiliate_ref', $user->ID, time() + (86400 * 30), "/"); // 86400 = 1 day
        }
    }

    /**
     * Add affiliate ref to order
     */
    public function add_affiliate_ref_to_order( $order_id, $order ) {
        if ( isset( $_COOKIE['affiliate_ref'] ) ) {
            $affiliate_id = intval($_COOKIE['affiliate_ref']);
            $meta = add_post_meta( $order->get_id(), 'affiliate_ref', $affiliate_id );
        }
    }

    /**
     * Maybe add referral woocommerce
     * Checks if an affiliate coupon or an affiliate link were used
     * during the checkout and saves the referral for the affiliate.
     */
    public function maybe_add_referral_woocommerce($order_id, $status_transition_from, $status_transition_to, $order) {

        $register_referral_hook = get_option('affiliate_suite_register_referral_hook', 'processing');

        if ( $status_transition_to != $register_referral_hook  ) {
            return;
        }
        
        // Check if an affiliate coupon of link was used in the order
        $affiliate_id = $this->get_woocommerce_order_affiliate($order);
        
        if ( $affiliate_id ) {
            // Order items
            $items = $this->serialize_referral_items($order);

            // Referral date
            $referral_date = current_time("Y-m-d H:i:s");

            // Prepare referral args
            $args = array(
                'affiliate_id'  => $affiliate_id,
                'reference'     => $order->get_id(),
                'products'      => $items,
                'amount'        => $this->calculate_referral_comission($order, $affiliate_id),
                'currency'      => $order->get_currency(),
                'status'        => 'unpaid',
                'channel'       => 'woocommerce',
                'date'          => $referral_date
            );

            // Save referral for the affiliate
            $this->add_referral($args);
            
        }
    }

    /**
     * Update referral status to rejected
     * When a WooCommerce order changes it status to cancelled, failed or refunded.
     */
    public function update_referral_status_to_rejected($order_id, $status_transition_from, $status_transition_to, $order) {

        if ( $status_transition_to == 'cancelled' || $status_transition_to == 'failed' || $status_transition_to == 'refunded' ) {

            global $wpdb;
            $referrals_table = $wpdb->prefix . "affiliatesuite_referrals";

            // Check if order has a referral associated to it
            $referral = $wpdb->get_results( "SELECT id, affiliate_id, reference FROM $referrals_table WHERE reference = $order_id" );

            if ($referral) {
                $referrals_table = $wpdb->prefix . "affiliatesuite_referrals";
                $data = array( 'status' => 'rejected' );
                $where = array( 'reference' => $order_id );
                $wpdb->update( $referrals_table, $data, $where );
            }

        }
        
    }

    /**
     * Add referral
     * creates or updates a referral entry in the database
     * 
     * @param array $args
     * @param integer  $args[affiliate_id]
     * @param string   $args['reference']
     * @param string   $args['products]
     * @param float    $args['amount]
     * @param string   $args['currency]
     * @param string   $args['status]
     * @param string   $args['channel]
     * @param string   $args['date']
     * 
     */
    public static function add_referral($args) {

        $args = apply_filters('as_referral_args', $args);

        if ( isset($args['custom_handler']) && $args['custom_handler'] === true ) {
            // Referral will be added (or not) by a custom function
            $custom_result = do_action('as_add_referral_custom_handler', $args);
    
            // Return and don't continue with regular add_referral function
            return $custom_result;
        }

        global $wpdb;
        $table = $wpdb->prefix . "affiliatesuite_referrals";

        // check if referral already exists on database
        $exists = $wpdb->get_results("SELECT id, affiliate_id, reference FROM $table WHERE reference =" . $args['reference']);
        
        if ( $exists ) {
            // Update existing referral
            $referrals_table = $wpdb->prefix . "affiliatesuite_referrals";
            $data = array( 
                'affiliate_id'  => $args['affiliate_id'],
                'reference'     => $args['reference'],
                'products'      => $args['products'],
                'amount'        => $args['amount'],
                'currency'      => $args['currency'],
                'status'        => $args['status'],
                'channel'       => $args['channel'],
                'date'          => $args['date']
            );
            $where = array( 'reference' => $args['reference'] );
            
            $update = $wpdb->update( $referrals_table, $data, $where );
            
            if ( $update ) {
                // Hook referral updated
                do_action('as_referral_updated', $exists->id);
                return $exists->id;
            } else {
                return false;
            }
        }

        $referral = $wpdb->insert(
            $table,
            array(
                'affiliate_id'  => $args['affiliate_id'],
                'reference'     => $args['reference'],
                'products'      => $args['products'],
                'amount'        => $args['amount'],
                'currency'      => $args['currency'],
                'status'        => $args['status'],
                'channel'       => $args['channel'],
                'date'          => $args['date']
            )
        );

        if ($referral) {
            // Update total earnings
            $earnings = get_user_meta($args['affiliate_id'], 'as_affiliate_earnings', true);
            $earnings = $earnings ? $earnings + $args['amount'] : $args['amount'];
            update_user_meta($args['affiliate_id'], 'as_affiliate_earnings', $earnings);
            
            // the ID generated for the AUTO_INCREMENT
            $referral_id = $wpdb->insert_id;

            // Referrral created hook
            do_action('as_referral_created', $referral_id, $args );

            return $referral_id;
        } else {
            return false;
        }
    }

    /**
     * Calculate referral comission
     * returns the amount of the comission for the referral
     */
    public static function calculate_referral_comission($order, $affiliate_id, $affiliate_rate=null) {
        
        if ($affiliate_rate == null) {
            global $wpdb;
            $affiliate_rate = get_user_meta($affiliate_id, 'as_affiliate_rate', true);
        }

        $affiliate_rate = apply_filters( 'affiliate_rate', $affiliate_rate, $affiliate_id, $order );

        // Net sale
        $net_sale = $order->get_total() - $order->get_total_tax() - $order->get_shipping_total();

        // Comission base (amount over which comission percentage will be calculated, default is Net Sale)
        $comission_base = apply_filters('af_comission_base', $net_sale, $order);

        $comission = $comission_base * ($affiliate_rate / 100);
        return $comission;
    }

    /**
     * Recalculate referrals amount
     * Recalculates and UPDATES the amount values of existing referrals in the database
     */
    public function recalculate_referrals_amounts($affiliate_id=null, $date_start='', $date_end='') {

        // AJAX PARAMS
        $affiliate_id = $affiliate_id ? $affiliate_id : $_POST['affiliate_id']; 
        $date_start = $date_start ? $date_start : $_POST['date_start'];
        $date_end = $date_end ? $date_end : $_POST['date_end'];

        if ( $affiliate_id == null || $date_start =='' || $date_end =='' ) {
            return false;
        }

        // Get referrals
        $referrals = $this->get_referrals($affiliate_id, $date_start, $date_end);

        if ( ! $referrals ) {
            echo json_encode( __('Nothing to update.', 'affiliate-suite') );
            wp_die();
        }

        $orders_ids = array();
        foreach ($referrals as $referral) {
            $orders_ids[] = (int)$referral->reference;
        }
        
        // Get orders
        $orders = wc_get_orders(array(
            'post__in' => $orders_ids,
            'limit' => -1
        ));

        if ( ! $orders ) {
            echo json_encode( __('There was a problem getting the orders from the database.', 'affiliate-suite') );
            wp_die();
        }

        $orders_array = array();

        foreach ($orders as $order) {
            $orders_array[$order->get_id()] = array(
                'id' => $order->get_id(),
                'total' => $order->get_total(),
                'total_tax' => $order->get_total_tax(),
                'total_shipping' => $order->get_total_shipping(),
                'net_sale' => $order->get_total() - $order->get_total_tax() - $order->get_shipping_total(),
                'comission' => $this->calculate_referral_comission($order, $affiliate_id),
                'date' => $order->get_date_paid()
            );
        }

        // Update database
        global $wpdb;
        $referrals_table = $wpdb->prefix . 'affiliatesuite_referrals';
        $rows_updated = 0;
        
        foreach ($orders_array as $basic_order) {
            $newAmount = $basic_order['comission'];
            $reference = $basic_order['id'];

            $result = $wpdb->update(
                $referrals_table,
                array('amount' => $newAmount),
                array('reference' => $reference)
            );

            if ($result === false) {
                echo json_encode("Something went wrong. Order reference: $reference");
                wp_die();
            } else {
                $rows_updated == $rows_updated++;
            }
        }

        echo json_encode( "$rows_updated referrals have been updated. Reload this page to see the changes." );
        wp_die();
    }


    /**
     * Get referrals
     */
    public function get_referrals($affiliate_id=null, $date_start='', $date_end='', $limit='') {  

        if ( wp_doing_ajax() ) {
            $affiliate_id = isset($_POST['affiliate_id']) ? $_POST['affiliate_id'] : '';
            $date_start = isset($_POST['date_start']) ? $_POST['date_start'] : '';
            $date_end = isset($_POST['date_end']) ? $_POST['date_end'] : '';
            $limit = isset($_POST['limit']) ? $_POST['limit'] : '';
        }

        global $wpdb;
        $referrals_table = $wpdb->prefix . "affiliatesuite_referrals";
        
        // Build query string
        $query_str = "SELECT ar.affiliate_id, u.display_name as affiliate_name, ar.reference, ar.amount, ar.currency, ar.status, ar.date, ar.products
                        FROM $referrals_table ar
                        INNER JOIN $wpdb->users u ON ar.affiliate_id = u.ID";

        if ( $affiliate_id || $date_start || $date_end ) {
            $query_str .= " WHERE";
        }

        if ( $affiliate_id ) {
            $query_str .= " affiliate_id=$affiliate_id";
        }

        if ( $date_start ) {
            if ($affiliate_id) {
                $query_str .= " AND date >= '$date_start'";
            } else {
                $query_str .= " date >= '$date_start'";
            }
        }

        if ( $date_end ) {
            if ($affiliate_id || $date_start) {
                $query_str .= " AND date <= '$date_end'";
            } else {
                $query_str .= " date <= '$date_end'";
            }
        }

        // Recent orders first
        $query_str .=  " ORDER BY date DESC";

        // Limit
        if ( $limit ) {
            $query_str .=  " LIMIT $limit";
        }
        
        $referrals = $wpdb->get_results($query_str);

        if ($referrals) {
            foreach ($referrals as $referral) {
                $referral->products = unserialize($referral->products);
            }
        }

        return $referrals;

        if (wp_doing_ajax()) {
            echo json_encode($referrals);
            wp_die();
        } else {
            return $referrals;
        }

    }

    /**
     * Get orders by coupon code
     */
    public static function get_orders_by_coupon($coupon_code, $date_start='', $date_end='') {
        global $wpdb;
        $return_array = [];

        $query = "SELECT
                    p.ID AS order_id
                    FROM
                    {$wpdb->prefix}posts AS p
                    INNER JOIN {$wpdb->prefix}woocommerce_order_items AS woi ON p.ID = woi.order_id
                    WHERE
                    p.post_type = 'shop_order' AND
                    p.post_status IN ('" . implode("','", array_keys(wc_get_order_statuses())) . "') AND
                    woi.order_item_type = 'coupon' AND
                    woi.order_item_name = '$coupon_code'";

        if ( $date_start != '' && $date_end !='' ) {
            $query = $query . " AND DATE(p.post_date) BETWEEN '" . $date_start . " 00:00:00' AND '" . $date_end . " 23:00:00'";
        }

        $query .= ";";

        $orders = $wpdb->get_results($query);

        if (!empty($orders)) {
            foreach ($orders as $key => $order) {
                $order_id = $order->order_id;
                $objOrder = wc_get_order($order_id);
                $return_array[$key] = $objOrder;
            }
        }

        return $return_array;
    }

    /**
     * Get unimpoted WooCommerce referrals
     */
    public static function get_unimported_woocommerce_referrals($affiliate_id=0, $date_start='', $date_end='') {
        
        // Check if params are coming from ajax
        $affiliate_id = $affiliate_id != 0 ? $affiliate_id : $_POST['affiliate_id'];
        $date_start = $date_start != '' ? $date_start : $_POST['dateStart'];
        $date_end = $date_end != '' ? $date_end : $_POST['dateEnd'];

        // First: Get the affiliate coupons
        $coupons = array();

        require_once 'includes/controllers/affiliates-controller.php';
        $affiliate = Affiliates_Controller::get_affiliate($affiliate_id);
        $coupons = $affiliate->woocommerce_coupons;

        // Second: Query all orders that contains any of those coupons
        $orders = array();
        foreach ($coupons as $coupon) {
            $couponOrders = Affiliate_Suite::get_orders_by_coupon($coupon, $date_start, $date_end);

            if (!empty($couponOrders)) {
                $orders = array_merge($orders, $couponOrders);
            }
        }

        // Third: Get the existing referrals in Affiliate Suite for that user
        $affiliate_suite_referrals = Affiliates_Controller::get_affiliate_referrals($affiliate_id);

        // Filter already registered referrals in Affiiate Suite (get only un-imported)
        $existing_referrals = array();
        $referrals_to_import = array();

        foreach ($affiliate_suite_referrals as $as_referral) {
            array_push($existing_referrals, $as_referral->reference);
        }

        foreach ($orders as $order) {

            // Only this statuses will count for referrals
            $allowed_order_statuses = array('completed', 'processing');

            if ( ! in_array($order->get_status(), $allowed_order_statuses) ) {
                continue;
            }

            if ( ! in_array($order->get_id(), $existing_referrals) ) {
                array_push($referrals_to_import, array(
                    'id' => $order->get_id(),
                    'order_total' => $order->get_total(),
                    'date_paid' => $order->get_date_paid(),
                    'currency'  => $order->get_currency()
                ));
            }
        }

        if (wp_doing_ajax() && !isset($_POST['import'])) {
            echo json_encode($referrals_to_import);
            wp_die();
        } else {
            return $referrals_to_import;
        }
    }

    /**
     * Import un-imported woocommerce referrals
     * (AJAX)
     */
    public function import_unimported_woocommerce_referrals() {
        $ordersToImport = $this->get_unimported_woocommerce_referrals($_POST['affiliate_id'], $_POST['dateStart'], $_POST['dateEnd']);
        
        if (!empty($ordersToImport)) {

            $imported_referrals = array();

            foreach ($ordersToImport as $orderArray) {
                
                $order= wc_get_order((int)$orderArray['id']);
                $net_sale = $order->get_total() - $order->get_total_tax() - $order->get_shipping_total();

                // Format date
                $referral_date = date_create($order->get_date_paid());
                $formated_date = date_format($referral_date,"Y-m-d H:i:s");

                $referral_data = array(
                    'affiliate_id'  => (int)$_POST['affiliate_id'],
                    'reference'     => $order->get_order_number(),
                    'products'      => $this->serialize_referral_items($order),
                    'amount'        => $this->calculate_referral_comission($order, $_POST['affiliate_id']),
                    'currency'      => $order->get_currency(),
                    'status'        => 'unpaid',
                    'channel'       => 'woocommerce',
                    'date'          => $formated_date
                );

                $new_referral = $this->add_referral($referral_data);
                $imported_referrals[] = $new_referral;
            }

            echo json_encode($imported_referrals);
            wp_die();
        }
    }

    /**
     * Get unimported AffiliateWP referrals
     */
    public static function get_unimported_affiliatewp_referrals($user_id) {

        global $wpdb;
        $affiliate_wp_affiliates = $wpdb->prefix . 'affiliate_wp_affiliates';
        $affiliate_wp_referrals = $wpdb->prefix . 'affiliate_wp_referrals';
        
        $query = "SELECT awr.reference, awr.products, awr.amount, awr.currency, awr.status, awr.context, awr.date
                    FROM $affiliate_wp_referrals awr
                    INNER JOIN $affiliate_wp_affiliates awa on awr.affiliate_id = awa.affiliate_id
                    WHERE awa.user_id = $user_id
                    AND awr.context = 'woocommerce';";

        $affiliateWP_referrals = $wpdb->get_results( $query, OBJECT );

        // Unserialize products field
        if($affiliateWP_referrals) {
            foreach ($affiliateWP_referrals as $row) {
                $row->products = unserialize($row->products);
            }
        }

        // Existing referrals in Affiliate Suite
        require_once 'includes/controllers/affiliates-controller.php';
        $affiliate_suite_referrals = Affiliates_Controller::get_affiliate_referrals($user_id);

        // Filter already registered referrals in Affiiate Suite (get only un-imported)
        $existing_referrals = array();
        $referrals_to_import = array();

        foreach ($affiliate_suite_referrals as $as_referral) {
            array_push($existing_referrals, $as_referral->reference);
        }

        foreach ($affiliateWP_referrals as $awp_referral) {
            if ( ! in_array($awp_referral->reference, $existing_referrals) ) {
                array_push($referrals_to_import, $awp_referral);
            }
        }

        return $referrals_to_import;
    }

    /**
     * Import AffiliateWP referrals (via ajax)
     */
    public static function import_affiliatewp_referrals() {

        $referrals = Affiliate_Suite::get_unimported_affiliatewp_referrals($_POST['user_id']);

        global $wpdb;
        $referrals_table = $wpdb->prefix . 'affiliatesuite_referrals';

        // Prepare VALUES string for SQL query
        $values = '';

        foreach ($referrals as $referral) {
            
            $affiliate_id = $_POST['user_id'];
            $reference = $referral->reference;
            $amount = $referral->amount;
            $currency = "USD";
            $status = $referral->status;
            $channel = $referral->context;
            $date = $referral->date;

            $products = array();

            foreach ($referral->products as $product) {
                $product = array(
                    'id'    => $product['id'],
                    'name'  => $product['name'],
                    'qty'   => 1  // TODO: Get the real quantity
                );

                $products[] = $product;
            }

            $products = serialize($products);

            $values .= "( $affiliate_id, '$reference', '$products', $amount, '$currency', '$status', '$channel', '$date' ),";
            
        }

        $values = rtrim($values,',');

        $query_str = "INSERT INTO $referrals_table (affiliate_id, reference, products, amount, currency, status, channel, date) VALUES $values";

        $affected_rows = $wpdb->query( $query_str );

        if ($affected_rows) {
            
            // Recalculate and update total earned
            $total_earned = Affiliate_Suite::calculate_affiliate_total_earned($_POST['user_id']);
            update_user_meta( $_POST['user_id'], 'as_affiliate_earnings', $total_earned );

            if ( wp_doing_ajax() ) {
                echo json_encode($affected_rows);
                wp_die();
            } else {
                return $affected_rows;
            }
        } else {
            return false;
        }
        
    }

    /**
     * Calculate affiliate total earned
     */
    public static function calculate_affiliate_total_earned($affiliate_id) {
        global $wpdb;
        $table = $wpdb->prefix . 'affiliatesuite_referrals';
        $query_string = "SELECT SUM(amount) as total_earned 
                            FROM $table 
                            WHERE affiliate_id = $affiliate_id 
                            AND (status='unpaid' OR status='paid')";
        $results = $wpdb->get_results($query_string, OBJECT);

        return $results[0]->total_earned;
    }

    /**
     * Get affiliates ranking
     */
    public function get_affiliates_ranking($limit=5) {

        if ( wp_doing_ajax() ) {
            $limit = 5;
        }

        global $wpdb;
        $affiliatesuite_referrals = $wpdb->prefix . "affiliatesuite_referrals";

        $query = "SELECT ar.affiliate_id, u.display_name as affiliate_name, COUNT(ar.id) as referrals_count, SUM(ar.amount) as total_earned
                    FROM $affiliatesuite_referrals ar
                    INNER JOIN $wpdb->users u ON ar.affiliate_id = u.ID
                    WHERE (ar.status='unpaid' OR ar.status='paid')
                    GROUP BY u.display_name
                    ORDER BY SUM(ar.amount) DESC
                    LIMIT $limit";

        $results = $wpdb->get_results($query, OBJECT);

        if ( wp_doing_ajax() ) {
            echo json_encode($results);
            wp_die();
        } else {
            return $results;
        }
    }

    /**
     * Export referrals to CSV (Basic)
     */
    public function export_csv_referrals($affiliate_id=null, $date_start='', $date_end='') {

        if (wp_doing_ajax()) {
            $affiliate_id = $_POST['affiliate_to_export'] != 'all' ? $_POST['affiliate_to_export'] : null;
            $date_start = $_POST['date_start'] != '' ? $_POST['date_start'] . ' 00:00:00' : '' ;
            $date_end = $_POST['date_end'] != '' ? $_POST['date_end'] . ' 23:59:59' : '';
        }

        $referrals = $this->get_referrals($affiliate_id, $date_start, $date_end);

        if ( $referrals ) {
            // Open file
            $file = fopen( dirname(__FILE__) . "/tmp/referrals.csv", "w" );

            // output the column headings
            fputcsv($file, array('Affiliate', 'Reference', 'Products', 'Amount', 'status', 'date'));

            foreach ($referrals as $referral) {
                $csvRow = array(
                    $referral->affiliate_name,
                    $referral->reference,
                    as_referral_products_stringify($referral),
                    $referral->amount,
                    $referral->status,
                    $referral->date
                );

                fputcsv($file, $csvRow);
            }

            // Close file
            fclose($file);

            $exported = true;
        } else {
            $exported = false;
        }

        if (wp_doing_ajax()) {
            echo json_encode($exported);
            wp_die();
        } else {
            return $exported;
        }
    }

    /**
     * Export referrals to CSV (WooCommerce)
     */
    public function export_csv_referrals_woocommerce($affiliate_id=null, $date_start='', $date_end='') {

        if (wp_doing_ajax()) {
            $affiliate_id = $_POST['affiliate_to_export'] != 'all' ? $_POST['affiliate_to_export'] : null;
            $date_start = isset($_POST['date_start']) ? $_POST['date_start'] : $date_start;
            $date_end =  isset($_POST['date_end']) ? $_POST['date_end'] : $date_end;
        }

        $referrals = $this->get_referrals($affiliate_id, $date_start, $date_end);

        if ( $referrals ) {

            // Open file
            $file = fopen( dirname(__FILE__) . "/tmp/referrals-woocommerce.csv", "w" );

            // output the column headings
            fputcsv($file, array('Affiliate', 'Reference', 'order date paid', 'Net Sale', 'Discount', 'Shipping', 'Taxes', 'Total', 'Comission Amount', 'Affiliate Rate', 'Order Status', 'currency'));

            foreach ($referrals as $referral) {

                $referral_order = wc_get_order((int)$referral->reference);

                if ( $referral_order ) {

                    $net_sale = $referral_order->get_total() - $referral_order->get_shipping_total() - $referral_order->get_total_tax();

                    $csvRow = array(
                        $referral->affiliate_name,
                        $referral->reference,
                        $referral_order->get_date_paid() ? $referral_order->get_date_paid()->format('d-m-Y H:i:s') : '',
                        $net_sale,
                        $referral_order->get_discount_total(),
                        $referral_order->get_shipping_total(),
                        $referral_order->get_total_tax(),
                        $referral_order->get_total(),
                        $referral->amount,
                        ' - ',
                        $referral_order->get_status(),
                        $referral_order->get_currency()
                    );

                    // Filter order data
                    $csvRow = apply_filters( 'as_csv_referral_order_row', $csvRow );
    
                    fputcsv($file, $csvRow);
                }
            }

            // Close file
            fclose($file);

            $exported = true;
        } else {
            $exported = false;
        }

        if (wp_doing_ajax()) {
            echo json_encode($exported);
            wp_die();
        } else {
            return $exported;
        }
    }

    /**
     * New referral email notificacion
     */
    public function new_referral_notification($referral_id, $args) {
        
        include 'includes/controllers/affiliates-controller.php';
        $affiliate = Affiliates_Controller::get_affiliate($args['affiliate_id']);

        // Month total
        $first_day_of_month = date('Y-m-01') . " 00:00:00";
        $last_day_of_month = date("Y-m-t") . " 23:59:59";

        $month_referrals = as_get_affiliate_month_report($args['affiliate_id'], $first_day_of_month, $last_day_of_month);

        $month_total = 0;
        foreach ($month_referrals as $referral) {
            $month_total = $month_total + $referral['day_total'];
        }

        // Email data
        $to = $affiliate->user_email;
        $subject = "You've made a new sale!";
        $headers  = 'MIME-Version: 1.0' . "\r\n";
        $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";

        ob_start();
        include 'includes/emails/new-referral.php';
        $html_content = ob_get_contents();
        ob_end_clean();

        $css = file_get_contents( __DIR__ . '/includes/emails/email-styles.css');

        $emogrifier = new \Pelago\Emogrifier();
        $emogrifier->setHtml($html_content);
        $emogrifier->setCss($css);
        $html_message = $emogrifier->emogrify();
        
        // Send email
        wp_mail($to, $subject, $html_message, $headers);
    }

    /**
     * Affiliate Dashboard Shortcode handler
     */
    public function affiliate_dashboard_handler() {

        $user_id = get_current_user_id();

        if ( $user_id ) {
            include 'includes/controllers/affiliates-controller.php';
            $affiliate = Affiliates_Controller::get_affiliate($user_id);

            // Moment.js
            wp_enqueue_script('moment', WP_PLUGIN_URL . '/affiliate-suite/node_modules/moment/moment.js', array('jquery'), null, false);

            // Chart.js
            wp_enqueue_script('chartjs', WP_PLUGIN_URL . '/affiliate-suite/node_modules/chart.js/dist/Chart.bundle.min.js', array('jquery'), null, false);

            // jQuery DataTables
            wp_enqueue_style('DataTables', 'https://cdn.datatables.net/1.10.19/css/jquery.dataTables.css');
            wp_enqueue_script('DataTables', 'https://cdn.datatables.net/1.10.19/js/jquery.dataTables.js', array('jquery'));

            // Affiliate Suite scripts
            wp_enqueue_style( 'affiliate-suite', WP_PLUGIN_URL . '/affiliate-suite/assets/css/admin.css', null, '1.0.0'  );
            wp_enqueue_script( 'affiliate-suite-admin-scripts', WP_PLUGIN_URL . '/affiliate-suite/assets/js/admin.js', array('jquery', 'moment'), $this->version, true );
			wp_localize_script('affiliate-suite-admin-scripts', 'affiliateSuite', array(
				'ajax_url' 		  => admin_url( 'admin-ajax.php' ),
                'currency'        => get_woocommerce_currency(),
                'currency_symbol' => get_woocommerce_currency_symbol()
            ));

            ob_start();
            $referrals = $this->get_referrals( $affiliate->ID );
            include 'includes/shortcodes/affiliate-dashboard/affiliate-dashboard.php';
            $html = ob_get_contents();
            ob_end_clean();

            return $html;
        } else {
            $html = __('You must be logged in to see this page.', 'affiliate-suite');
            return $html;
        }
    }

    /**
     * Login redirect
     */
    public function login_redirect_handler( $redirect_to, $requested_redirect_to, $user ) {

        if ( in_array('as_affiliate', $user->roles ) ) {
            $redirect_page_id = get_option('affiliate_suite_login_redirect_page', 0);

            if ( $redirect_page_id ) {
                $redirect_to = get_page_link( $redirect_page_id );
            }
        }

        return $redirect_to;
    }
}

new Affiliate_Suite();