<?php

class NTA_Whatsapp_Widget extends WP_Widget {

    public function __construct() {
        parent::__construct(
                'nta-whatsapp-widget', 'NhaTrangArt Whatsapp', array('description' => 'Nha Trang Art Whatsapp Contact Form')
        );
        add_action('widgets_init', function() {
            register_widget('NTA_Whatsapp_Widget');
        });


        add_action('wp_enqueue_scripts', function() {
            wp_register_style('nta-css-widget', NTA_WHATSAPP_PLUGIN_URL . 'assets/css/style.css');
            wp_enqueue_style('nta-css-widget');

            wp_register_script('nta-js-widget', NTA_WHATSAPP_PLUGIN_URL . 'assets/js/main.js', ['jquery']);
            wp_localize_script('nta-js-widget', 'nta-test', [
                'url' => admin_url('admin-ajax.php')
            ]);
            wp_enqueue_script('nta-js-widget');
        });
    }

    public function form($instance) {
        
        //$title = (isset($instance['title']) && !empty($instance['title'])) ? apply_filters('widget_title', $instance['title']) : __('TP Weather Widget', 'tp-weather');
        //$unit = (isset($instance['unit']) && !empty($instance['unit'])) ? $instance['unit'] : 'celsisus';
        //require (TP_WEATHER_PLUGIN_DIR . 'views/tp-weather-widget-form.php');
    }

    public function update($new_instance, $old_instance) {
        //$instance = [];
        //$instance['title'] = (isset($new_instance['title']) && !empty($new_instance['title'])) ? apply_filters('widget_title', $new_instance['title']) : __('TP Weather Widget', 'tp-weather');
        //$instance['unit'] = (isset($new_instance['unit']) && !empty($new_instance['unit'])) ? $new_instance['unit'] : 'celsisus';
        //return $instance;
    }

    public function widget($args, $instance) {
        //require (TP_WEATHER_PLUGIN_DIR . 'views/tp-weather-widget-view.php');
        require(NTA_WHATSAPP_PLUGIN_DIR . 'views/nta-whatsapp-widget-view.php');	
        
    }

}
