<?php

if (!function_exists('add_action')) {
    echo 'Hi there!  I\'m just a plugin, not much I can do when called directly.';
    exit;
}

class NTA_Whatsapp_Woocommerce {

    public function __construct() {
        $woo_button_setting = get_option('nta_wa_woobutton_setting');
        $availableAccount = $this->check_Available_Account();

        if ($availableAccount == 0 || $woo_button_setting == false || !isset($woo_button_setting['nta_woo_button_status'])) {
            return;
        } else {
            if ($woo_button_setting['nta_woo_button_position'] == 'after_atc') {
                add_action('woocommerce_after_add_to_cart_button', [$this, 'insert_wa_woobutton']);
            } elseif ($woo_button_setting['nta_woo_button_position'] == 'before_atc') {
                add_action('woocommerce_before_add_to_cart_button', [$this, 'insert_wa_woobutton']);
            } elseif ($woo_button_setting['nta_woo_button_position'] == 'after_short_description') {
                add_filter('woocommerce_short_description', [$this, 'showAfterShortDescription']);
            } elseif ($woo_button_setting['nta_woo_button_position'] == 'after_long_description') {
                add_filter('the_content', [$this, 'showAfterLongDescription']);
            }
        }
    }

    public function check_Available_Account() {
        $args = array(
            'post_type' => 'whatsapp-accounts',
        );
        $account_list = get_posts($args);

        foreach ($account_list as $account) {
            $get_data = get_post_meta($account->ID, 'nta_whatsapp_accounts', true);

            if ($get_data['wo_active'] == 'active') {
                return 1;
            }
        }
        return 0;
    }

    public function showAfterShortDescription($post_excerpt) {
        if (!is_single()) {
            return $post_excerpt;
        }
        $btn_content = '';
        $query = new WP_Query('post_type=whatsapp-accounts');
        $account_list = $query->posts;
        $account_list_view = array();
        foreach ($account_list as $account) {
            $get_data = get_post_meta($account->ID, 'nta_whatsapp_accounts', true);

            if ($get_data['wo_active'] == 'active') {
                $account_list_view[$account->ID] = array(
                    'account_id' => $account->ID,
                    'wo_position' => $get_data['wo_position'],
                );
            }
        }
        usort($account_list_view, function($first, $second) {
            return $first['wo_position'] > $second['wo_position'];
        });

        foreach ($account_list_view as $row) {
            $btn_content .= '<div class="nta-woo-products-button">' . do_shortcode('[njwa_button id="' . $row['account_id'] . '"]') . '</div>';
        }
        return $post_excerpt . $btn_content;
    }

    public function showAfterLongDescription($content) {
        if ('product' !== get_post_type() || !is_single()) {
            return $content;
        }
        $btn_content = '';
        $query = new WP_Query('post_type=whatsapp-accounts');
        $account_list = $query->posts;
        $account_list_view = array();
        foreach ($account_list as $account) {
            $get_data = get_post_meta($account->ID, 'nta_whatsapp_accounts', true);

            if ($get_data['wo_active'] == 'active') {
                $account_list_view[$account->ID] = array(
                    'account_id' => $account->ID,
                    'wo_position' => $get_data['wo_position'],
                );
            }
        }
        usort($account_list_view, function($first, $second) {
            return $first['wo_position'] > $second['wo_position'];
        });

        foreach ($account_list_view as $row) {
            $btn_content .= '<div class="nta-woo-products-button">' . do_shortcode('[njwa_button id="' . $row['account_id'] . '"]') . '</div>';
        }
        $btn = '<a href="#">hello</a>';
        return $content . $btn_content;
    }

    public function insert_wa_woobutton() {
        $query = new WP_Query('post_type=whatsapp-accounts');
        $account_list = $query->posts;
        $account_list_view = array();
        foreach ($account_list as $account) {
            $get_data = get_post_meta($account->ID, 'nta_whatsapp_accounts', true);

            if ($get_data['wo_active'] == 'active') {
                $account_list_view[$account->ID] = array(
                    'account_id' => $account->ID,
                    'wo_position' => $get_data['wo_position'],
                );
            }
        }
        usort($account_list_view, function($first, $second) {
            return $first['wo_position'] > $second['wo_position'];
        });

        foreach ($account_list_view as $row) {
            echo '<div class="nta-woo-products-button">' . do_shortcode('[njwa_button id="' . $row['account_id'] . '"]') . '</div>';
        }
    }

}
