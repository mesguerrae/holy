<?php

class Affiliates_Controller {

    public function __construct() {
        if ( isset($_POST['action']) ) {
            switch($_POST['action']) {
                case "create":
                    $this->create_affiliate();
                    break;
                case "update":
                    $this->update_affiliate( $_GET['affiliate'] );
                    break;
                case "convert":
                    $this->convert_to_affiliate( $_POST['user_id'] );
                    break;
                case "delete":
                    $this->delete_affiliate( $_GET['affiliate'] );
                    break;
            }
        }
    }

    /**
     * Create
     */
    public function create_affiliate() {
     
        $userdata = array(
            'user_login'    => $_POST['user_login'],
            'user_email'    => $_POST['user_email'],
            'first_name'    => $_POST['first_name'],
            'last_name'     => $_POST['last_name'],
            'display_name'  => $_POST['first_name'] . ' ' . $_POST['last_name'],
            'user_pass'     => $_POST['user_pass'],
            'role'          => 'as_affiliate'
        );

        $email_exists = get_user_by('email', $_POST['user_email']);
        $username_exists = get_user_by('login', $_POST['user_login']);

        if ($email_exists || $username_exists) {
            wp_redirect( admin_url('admin.php?page=add-affiliate&notice=user_exists') );
        }

        $user_id = wp_insert_user( $userdata );

        if ( ! is_wp_error($user_id) ) { // Success
            add_user_meta($user_id, 'as_affiliate_date_registered', date("Y-m-d H:i:s"), true);
            add_user_meta($user_id, 'as_affiliate_status', $_POST['as_affiliate_status'], true);
            add_user_meta($user_id, 'as_affiliate_rate', $_POST['as_affiliate_rate'], true);
            add_user_meta($user_id, 'as_affiliate_notes', $_POST['as_affiliate_notes'], true);
            add_user_meta($user_id, 'as_affiliate_woo_coupons', $_POST['as_affiliate_woo_coupons'] );
            
            wp_redirect( admin_url('admin.php?page=edit-affiliate&affiliate=' . $user_id . '&created=true' ) );
        }
    }

    /**
     * Update
     */
    public function update_affiliate($affiliate_id) {
        // Get affliate
        $affiliate = $this->get_affiliate( $affiliate_id );

        // update wp_user
        $updated = wp_update_user(array(
            'ID'            => $affiliate_id,
            'user_email'    => $_POST['user_email'],
            'first_name'    => $_POST['first_name'],
            'last_name'     => $_POST['last_name'],
            'user_pass'     => $_POST['user_pass'] == '' ? null : $_POST['user_pass']
        ));

        if ( ! is_wp_error($updated) ) {
            // update usermeta
            update_user_meta( $affiliate_id, 'as_affiliate_date_registered', $_POST['as_affiliate_date_registered'] );
            update_user_meta( $affiliate_id, 'as_affiliate_status', $_POST['as_affiliate_status'] );
            update_user_meta( $affiliate_id, 'as_affiliate_rate', $_POST['as_affiliate_rate'] );
            update_user_meta( $affiliate_id, 'as_affiliate_notes', $_POST['as_affiliate_notes'] );
            update_user_meta( $affiliate_id, 'as_affiliate_woo_coupons', $_POST['as_affiliate_woo_coupons'] );

            wp_redirect( admin_url('admin.php?page=edit-affiliate&affiliate=' . $affiliate_id . '&updated=true' ) );
        }
    }

    /**
     * Delete
     * caution: This will delete user account and usermeta.
     */
    public function delete_affiliate($affiliate_id) {

        if ( wp_delete_user($affiliate_id) ) {
            wp_redirect( admin_url('admin.php?page=list-affiliates' ) );
        }
    }

    /**
     * Convert to affiliate
     * Turns a regular WordPress user into an affiliate
     */
    public function convert_to_affiliate($user_id) {

        $user = get_user_by( 'ID', $user_id[0] );

        if ($user) {
            // add affiliate role
            $user->add_role('as_affiliate');

            // metadata
            add_user_meta($user->ID, 'as_affiliate_date_registered', date("Y-m-d H:i:s"), true);
            add_user_meta($user->ID, 'as_affiliate_status', $_POST['as_affiliate_status'], true);
            add_user_meta($user->ID, 'as_affiliate_rate', $_POST['as_affiliate_rate'], true);
            add_user_meta($user->ID, 'as_affiliate_notes', $_POST['as_affiliate_notes'], true);
            add_user_meta($user->ID, 'as_affiliate_woo_coupons', $_POST['as_affiliate_woo_coupons']);

            wp_redirect( admin_url('admin.php?page=edit-affiliate&affiliate=' . $user->ID . '&updated=true' ) );
        } else {
            wp_redirect( admin_url('admin.php?page=list-affiliates&notice=error' ) );
        }
    }

    /**
     * Get all affiliates
     */
    public static function get_affiliates() {
        $user_query = new WP_User_Query( array( 'role__in' => 'as_affiliate' ) );
        return $user_query->results;
    }

    /**
     * Get Affiliate
     */
    public static function get_affiliate($affiliate_id) {
        global $wpdb;

        $query_string = "
            SELECT
                u.ID,
                u.user_login,
                u.user_email,
                u.user_url,
                u.user_registered,
                max( CASE WHEN um.meta_key = 'first_name' and u.ID = um.user_id THEN um.meta_value END ) as first_name,
                max( CASE WHEN um.meta_key = 'last_name' and u.ID = um.user_id THEN um.meta_value END ) as last_name,
                max( CASE WHEN um.meta_key = 'as_affiliate_date_registered' and u.ID = um.user_id THEN meta_value END ) as affiliate_date_registered,
                max( CASE WHEN um.meta_key = 'as_affiliate_status' and u.ID = um.user_id THEN um.meta_value END ) as status,
                max( CASE WHEN um.meta_key = 'as_affiliate_rate' and u.ID = um.user_id THEN meta_value END ) as rate,
                max( CASE WHEN um.meta_key = 'as_affiliate_notes' and u.ID = um.user_id THEN meta_value END ) as notes,
                max( CASE WHEN um.meta_key = 'as_affiliate_earnings' and u.ID = um.user_id THEN meta_value END ) as earnings,
                max( CASE WHEN um.meta_key = 'as_affiliate_woo_coupons' and u.ID = um.user_id THEN meta_value END ) as woocommerce_coupons
            FROM 
                $wpdb->users u
                INNER JOIN $wpdb->usermeta um on u.ID = um.user_id
            WHERE
                ID = $affiliate_id
        ";

        $results = $wpdb->get_results($query_string, OBJECT);

        // unserialize woocommerce coupons
        if ( $results[0]->woocommerce_coupons ) {
            foreach ($results as $result) {
                $result->woocommerce_coupons = unserialize( $result->woocommerce_coupons );
            }
        }

        return $results[0];
    }

    /**
     * Get affiliate referrals
     */
    public static function get_affiliate_referrals($affiliate_id) {
        global $wpdb;
        $table = $wpdb->prefix . 'affiliatesuite_referrals';
        $query_string = "SELECT reference, products, amount, currency, status, channel, date FROM $table WHERE affiliate_id = $affiliate_id";
    
        $referrals = $wpdb->get_results($query_string, OBJECT);

        if ($referrals) {
            foreach ($referrals as $referral) {
                $referral->products = unserialize($referral->products);
            }
        }

        return $referrals;
    }
}

new Affiliates_Controller();