<?php

class Affiliate_Suite_Settings {

    function __construct() {
        add_action( 'admin_menu', array($this, 'add_settings_page') );

        add_action( 'admin_init', array($this, 'settings_init') );
    }

    /**
     * Add setting page
     */
    public function add_settings_page() {
        add_submenu_page(
            'affiliate-suite',                          // parent_slug
            __('Settings', 'affiliate-suite'),          // page_title
            __('Settings', 'affiliate-suite'),          // menu_title
            'manage_options',                           // capability
            'affiliate-suite-settings',                 // menu_slug
            array($this, 'settings_submenu_callback')    // callback
        );
    }

    /**
     * Setting page calback
     */
    public function settings_submenu_callback() {
        ?>
            <div class="wrap">
                <h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
                <form action="options.php" method="post">
                    <?php
                    // output security fields
                    settings_fields( 'affiliate-suite-settings' );
                    // output setting sections and their fields
                    do_settings_sections( 'affiliate-suite-settings' );
                    // output save settings button
                    submit_button( __('Save Settings', 'affiliate-suite') );
                    ?>
                </form>
            </div>
        <?php
    }

    /**
     * Register settings
     */
    public function settings_init() {

        // Register settings for "settings" page
        register_setting('affiliate-suite-settings', 'affiliate_suite_channels');
        register_setting('affiliate-suite-settings', 'affiliate_suite_register_referral_hook');
        register_setting('affiliate-suite-settings', 'affiliate_suite_login_redirect_page');

        // Register a section for the "settings" page
        add_settings_section(
            'affiliatesuite_settings',                  // id
            __('General Settings', 'affiliate-suite'),  // title
            array($this, 'general_settings_callback'),  // callback
            'affiliate-suite-settings'                  // page
        );

        // Register field in the "affiliatesuite_settings" section
        add_settings_field(
            'affiliatesuite_channels_field',            // id
            __('Channels', 'affiliate-suite'),          // title
            array($this, 'channels_field_callback'),    // callback
            'affiliate-suite-settings',                 // page
            'affiliatesuite_settings'                   // section
        );

        add_settings_field(
            'affiliatesuite_register_referral_hook_field',
            __('When referrals (comissions) should be calculated', 'affiliate-suite'),
            array($this, 'register_referral_hook_field_callback'),
            'affiliate-suite-settings',
            'affiliatesuite_settings'
        );

        add_settings_field(
            'affiliate_suite_login_redirect_page_field',
            __('Affiliate login redirect URL', 'affiliate-suite'),
            array($this, 'affiliate_login_redirect_page_callback'),
            'affiliate-suite-settings',
            'affiliatesuite_settings'
        );
    }

    /**
     * General settings (section) callback
     */
    public function general_settings_callback() {
        echo "<p>This is the general settings section.</p>";
    }

    /**
     * Channels field callback
     */
    public function channels_field_callback() {
        $channels = get_option('affiliate_suite_channels');
        
        if (!$channels) {
            $channels = array();
        }

        ?>
        <label>
            <input type="checkbox" name="affiliate_suite_channels[]" value="affiliate_link" <?php echo ( in_array('affiliate_link', $channels) ? 'checked' : null ) ?>>
            <?php _e('Affiliate link', 'affiliate-suite'); ?>
        </label>
        
        <br>

        <label>
            <input type="checkbox" name="affiliate_suite_channels[]" value="woocommerce" <?php echo ( in_array('woocommerce', $channels) ? 'checked' : null ) ?>>
            <?php _e('WooCommerce', 'affiliate-suite'); ?>
        </label>
        <?php
    }

    /**
     * Register referral hook callback 
     */
    public function register_referral_hook_field_callback() {
        $status_option = get_option('affiliate_suite_register_referral_hook', 'processing');
        $order_statuses = $this->get_formatted_order_statuses();
        ?>
        <select name="affiliate_suite_register_referral_hook">
            <?php foreach( $order_statuses as $key => $order_status ) : ?>
                <option value="<?= $key ?>" <?php selected( $status_option, $key ) ?>><?= $order_status ?></option>
            <?php endforeach; ?>
        </select>
        <?php
    }

    /**
     * Get order statuses without prefixes
     */
    public function get_formatted_order_statuses() {
        $order_statuses = wc_get_order_statuses();
        $formatted_statuses = array();

        foreach ( $order_statuses as $key => $status ) {
            $formatted_statuses[ str_replace( 'wc-', '', $key ) ] =  $status;
        }

        return $formatted_statuses;
    }

    /**
     * Affiliate login redirect url callback
     */
    public function affiliate_login_redirect_page_callback() {
        $redirect_page_id = get_option('affiliate_suite_login_redirect_page', 0);
        $pages = get_pages();
        ?>
        <select name="affiliate_suite_login_redirect_page">
            <option value="0" <?php selected( $redirect_page_id, 0 ) ?>><?= _e('Default redirect', 'affiliate-suite'); ?></option>
            <?php foreach( $pages as $page ) : ?>
                <option value="<?= $page->ID ?>" <?php selected( $redirect_page_id, $page->ID ) ?>><?= $page->post_title ?></option>
            <?php endforeach; ?>
        </select>
        <?php
    }

}

new Affiliate_Suite_Settings();