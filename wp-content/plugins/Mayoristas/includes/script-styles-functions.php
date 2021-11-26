<?php
function phoen_scripts_for_discount()
{
    
    wp_enqueue_script('jquery-ui-accordion');
    
    wp_enqueue_script('phoen-select2-js-discount', plugin_dir_url(__FILE__) . 'assets/js/select2.min.js');
    
    wp_enqueue_style('phoen-select2-css-discount', plugin_dir_url(__FILE__) . 'assets/css/select2.min.css');
    
    wp_enqueue_style('phoen-new-css-discount', plugin_dir_url(__FILE__) . 'assets/css/phoen_new_add_backend.css');
    
    wp_enqueue_style('phoen-jquery-ui-discount', plugin_dir_url(__FILE__) . 'assets/css/admin_jquery_css_backend.css');
    
    wp_enqueue_script('jquery-ui-datepicker');
    
}