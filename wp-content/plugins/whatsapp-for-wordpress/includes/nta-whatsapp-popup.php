<?php
if (!function_exists('add_action')) {
    echo 'Hi there!  I\'m just a plugin, not much I can do when called directly.';
    exit;
}

function get_back_time($account_info) {
    $todayDayOfWeek = current_time('l');
    $timeNow = current_time('H:i');
    $timeNow = new DateTime($timeNow);
//    echo 'TimeNow' . $timeNow->format('H:i');
    switch ($todayDayOfWeek) {
        case 'Monday':
            $getTimeWorking = explode("-", $account_info['nta_monday_working']);

            $start = new DateTime($getTimeWorking[0]);
            $end = new DateTime($getTimeWorking[1]);

            if ($account_info['nta_monday'] == 'checked') {
                $hours = $start->diff($timeNow);
                if ($timeNow >= $start && $timeNow <= $end) {
                    return 'online';
                } else if ($timeNow < $start) {
                    return $hours->format("%h:%i");
                }
            }
            return 'offline';
            break;
        case 'Tuesday':
            $getTimeWorking = explode("-", $account_info['nta_tuesday_working']);

            $start = new DateTime($getTimeWorking[0]);
            $end = new DateTime($getTimeWorking[1]);

            if ($account_info['nta_tuesday'] == 'checked') {
                $hours = $start->diff($timeNow);
                if ($timeNow >= $start && $timeNow <= $end) {
                    return 'online';
                } else if ($timeNow < $start) {
                    return $hours->format("%h:%i");
                }
            }
            return 'offline';
            break;
        case 'Wednesday':
            $getTimeWorking = explode("-", $account_info['nta_wednesday_working']);

            $start = new DateTime($getTimeWorking[0]);
            $end = new DateTime($getTimeWorking[1]);

            if ($account_info['nta_wednesday'] == 'checked') {
                $hours = $start->diff($timeNow);
                if ($timeNow >= $start && $timeNow <= $end) {
                    return 'online';
                } else if ($timeNow < $start) {
                    return $hours->format("%h:%i");
                }
            }
            return 'offline';
            break;
        case 'Thursday':
            $getTimeWorking = explode("-", $account_info['nta_thursday_working']);

            $start = new DateTime($getTimeWorking[0]);
            $end = new DateTime($getTimeWorking[1]);

            if ($account_info['nta_thursday'] == 'checked') {
                $hours = $start->diff($timeNow);
                if ($timeNow >= $start && $timeNow <= $end) {
                    return 'online';
                } else if ($timeNow < $start) {
                    return $hours->format("%h:%i");
                }
            }
            return 'offline';
            break;
        case 'Friday':
            $getTimeWorking = explode("-", $account_info['nta_friday_working']);

            $start = new DateTime($getTimeWorking[0]);
            $end = new DateTime($getTimeWorking[1]);

            if ($account_info['nta_friday'] == 'checked') {
                $hours = $start->diff($timeNow);
                if ($timeNow >= $start && $timeNow <= $end) {
                    return 'online';
                } else if ($timeNow < $start) {
                    return $hours->format("%h:%i");
                }
            }
            return 'offline';
            break;
        case 'Saturday':
            $getTimeWorking = explode("-", $account_info['nta_saturday_working']);

            $start = new DateTime($getTimeWorking[0]);
            $end = new DateTime($getTimeWorking[1]);

            if ($account_info['nta_saturday'] == 'checked') {
                $hours = $start->diff($timeNow);
                if ($timeNow >= $start && $timeNow <= $end) {
                    return 'online';
                } else if ($timeNow < $start) {
                    return $hours->format("%h:%i");
                }
            }
            return 'offline';
            break;
        case 'Sunday':
            $getTimeWorking = explode("-", $account_info['nta_sunday_working']);

            $start = new DateTime($getTimeWorking[0]);
            $end = new DateTime($getTimeWorking[1]);

            if ($account_info['nta_sunday'] == 'checked') {
                $hours = $start->diff($timeNow);
                if ($timeNow >= $start && $timeNow <= $end) {
                    return 'online';
                } else if ($timeNow < $start) {
                    return $hours->format("%h:%i");
                }
            }
            return 'offline';
            break;
        default:
            return 'offline';
    }
}

class NTA_Whatsapp_Popup {

    public function __construct() {
        $widget_setting = get_option('nta_whatsapp_setting');
        $availableAccount = $this->check_Available_Account_Widget();
       
        add_action('wp_enqueue_scripts', function() {
            wp_register_style('nta-css-popup', NTA_WHATSAPP_PLUGIN_URL . 'assets/css/style.css');
            wp_enqueue_style('nta-css-popup');

            wp_register_script('nta-js-popup', NTA_WHATSAPP_PLUGIN_URL . 'assets/js/main.js', ['jquery']);
            wp_localize_script('nta-js-popup', 'ntawaAjax', [
                'url' => admin_url('admin-ajax.php')
            ]);
            wp_enqueue_script('nta-js-popup');
        });

        //Check available account and setting to show widget
        if ($availableAccount && ((isset($widget_setting['nta_widget_status']) && $widget_setting['nta_widget_status'] == 'ON') || $widget_setting == false)) {
            add_action('wp_footer', [$this, 'show_popup_view']);
        }

        add_action('wp_head', [$this, 'popup_style_setting']);
    }

    public function check_Available_Account_Widget() {
        $args = array(
            'post_type' => 'whatsapp-accounts',
        );
        $account_list = get_posts($args);

        foreach ($account_list as $account) {
            $get_data = get_post_meta($account->ID, 'nta_whatsapp_accounts', true);

            if ($get_data['nta_active'] != 'none') {
                return 1;
            }
        }
        return 0;
    }

    public function show_popup_view() {
        $option = get_option('nta_whatsapp_setting');
        if (empty($option)) {
            $option['widget_name'] = 'Start a Conversation';
            $option['widget_description'] = 'Hi! Click one of our member below to chat on <strong>Whatsapp</strong>';
            $option['widget_label'] = 'Need Help? <strong>Chat with us</strong>';
            $option['widget_responseText'] = 'The team typically replies in a few minutes.';
        }else{
            $option['widget_responseText'] = getValueOrDefault($option['widget_responseText'], 'The team typically replies in a few minutes.');
        }

        //Show Account Data
        $query = new WP_Query('post_type=whatsapp-accounts');
        $account_list = $query->posts;
        $account_list_view = array();
        foreach ($account_list as $account) {
            $get_data = get_post_meta($account->ID, 'nta_whatsapp_accounts', true);

            if ($get_data['nta_active'] != 'none') {
                $account_list_view[$account->ID] = array(
                    'account_id' => $account->ID,
                    'post_title' => $account->post_title,
                    'nta_group_number' => $get_data['nta_group_number'],
                    'nta_predefined_text' => $get_data['nta_predefined_text'],
                    'nta_over_time' => $get_data['nta_over_time'],
                    //'nta_group_number' => $account->nta_group_number,
                    'nta_title' => $get_data['nta_title'],
                    'nta_active' => $get_data['nta_active'],
                    'nta_offline_text' => $get_data['nta_offline_text'],
                    'nta_sunday' => $get_data['nta_sunday'],
                    'nta_sunday_working' => $get_data['nta_sunday_working'],
                    'nta_monday' => $get_data['nta_monday'],
                    'nta_monday_working' => $get_data['nta_monday_working'],
                    'nta_tuesday' => $get_data['nta_tuesday'],
                    'nta_tuesday_working' => $get_data['nta_tuesday_working'],
                    'nta_wednesday' => $get_data['nta_wednesday'],
                    'nta_wednesday_working' => $get_data['nta_wednesday_working'],
                    'nta_thursday' => $get_data['nta_thursday'],
                    'nta_thursday_working' => $get_data['nta_thursday_working'],
                    'nta_friday' => $get_data['nta_friday'],
                    'nta_friday_working' => $get_data['nta_friday_working'],
                    'nta_saturday' => $get_data['nta_saturday'],
                    'nta_saturday_working' => $get_data['nta_saturday_working'],
                    'position' => $get_data['position'],
                    'avatar' => get_the_post_thumbnail_url($account->ID)
                );
            }
        }
        usort($account_list_view, function($first, $second) {
            return $first['position'] > $second['position'];
        });


        //Check redirect on mobile or desktop
        $link_to_app = 'web';
        if (wp_is_mobile()) {
            $link_to_app = 'api';
        }
        require(NTA_WHATSAPP_PLUGIN_DIR . 'views/nta-whatsapp-widget-view.php');
    }

    public function popup_style_setting() {
        $option = get_option('nta_whatsapp_setting');
        if (empty($option)) {
            $option['text_color'] = '#fff';
            $option['back_color'] = '#2db742';
            $option['widget_position'] = 'right';
        }
        ?>
        <style>
            .wa__stt_offline{
                pointer-events: none;
            }

            .wa__button_text_only_me .wa__btn_txt{
                padding-top: 16px !important;
                padding-bottom: 15px !important;
            }

            .wa__popup_content_item .wa__cs_img_wrap{
                width: 48px;
                height: 48px;
            }

            .wa__popup_chat_box .wa__popup_heading{
                background: <?php echo $option['back_color'] ?>;
            }

            .wa__btn_popup .wa__btn_popup_icon{
                background: <?php echo $option['back_color'] ?>;
            }

            .wa__popup_chat_box .wa__stt{
                border-left: 2px solid  <?php echo $option['back_color'] ?>;
            }

            .wa__popup_chat_box .wa__popup_heading .wa__popup_title{
                color: <?php echo $option['text_color'] ?>;
            }

            .wa__popup_chat_box .wa__popup_heading .wa__popup_intro{
                color: <?php echo $option['text_color'] ?>;
                opacity: 0.8;
            }

            .wa__popup_chat_box .wa__popup_heading .wa__popup_intro strong{

            }

            <?php if ($option['widget_position'] == 'left'): ?>
                .wa__btn_popup{
                    left: 30px;
                    right: unset;
                }

                .wa__btn_popup .wa__btn_popup_txt{
                    left: 100%;
                }

                .wa__popup_chat_box{
                    left: 25px;
                }
            <?php endif; ?>

        </style>

        <?php
    }

}
