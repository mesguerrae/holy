<?php

if ( !function_exists('get_wmsc_settings') ) {
function get_wmsc_settings($text_domain) {

    $account_url = admin_url('admin.php?page=wc-settings&tab=account');
    $no_login_screenshot = 'https://www.silkypress.com/wp-content/uploads/2018/09/multi-step-checkout-no-login.png';
    $wmsc_settings = array(
        /* General Settings */
        'label1' => array(
            'label' => __('Which Steps to show', $text_domain),
            'input_form' => 'header',
            'value' => '',
            'section' => 'general',
        ),
        'show_shipping_step' => array(
            'label' => __('Show the <code>Shipping</code> step', $text_domain),
            'input_form' => 'checkbox',
            'value' => true,
            'section' => 'general',
        ),
        'show_login_step' => array(
            'label' => __('Show the <code>Login</code> step', $text_domain),
            'input_form' => 'text',
            'value' => __('If you want to remove the login step, then make sure you have the “Enable customer registration on the Checkout page” checked and the “Display returning customer login reminder on the Checkout page” unchecked on the <a href="'.$account_url.'">WP Admin -> WooCommerce -> Settings -> Accounts</a> page. See <a href="'.$no_login_screenshot.'" target="_blank">this screenshot</a>.', $text_domain),
            'section' => 'general',
        ),
        'unite_billing_shipping' => array(
            'label' => __('Show the <code>Billing</code> and the <code>Shipping</code> steps together', $text_domain),
            'input_form' => 'checkbox',
            'value' => false,
            'section' => 'general',
        ),
        'unite_order_payment' => array(
            'label' => __('Show the <code>Order</code> and the <code>Payment</code> steps together', $text_domain),
            'input_form' => 'checkbox',
            'value' => false,
            'section' => 'general',
        ),

        'label3' => array(
            'label' => __('Functionality', $text_domain),
            'input_form' => 'header',
            'value' => '',
            'section' => 'general',
        ),
        'validation_per_step' => array(
            'label' => __('Validate the fields during each step', $text_domain),
            'description' => __('The default WooCommerce validation is done when clicking the Place Order button. With this option the validation is performed when trying to move to the next step', $text_domain),
            'input_form' => 'checkbox',
            'value' => true,
            'section' => 'general',
            'pro' => true,
        ),
        'clickable_steps' => array(
            'label' => __('Clickable Steps', $text_domain),
            'description' => __('The user can click on the steps in order to get to the next one.', $text_domain),
            'input_form' => 'checkbox',
            'value' => true,
            'section' => 'general',
            'pro' => true,
        ),
        'keyboard_nav' => array(
            'label' => __('Enable the keyboard navigation', $text_domain),
            'description' => __('Use the keyboard\'s left and right keys to move between the checkout steps', $text_domain),
            'input_form' => 'checkbox',
            'value' => false,
            'section' => 'general',
        ),

        'label2' => array(
            'label' => __('Additional Elements', $text_domain),
            'input_form' => 'header',
            'value' => '',
            'section' => 'general',
        ),
        'show_back_to_cart_button' => array(
            'label' => __('Show the <code>Back to Cart</code> button', $text_domain),
            'input_form' => 'checkbox',
            'value' => true,
            'section' => 'general',
        ),
        'registration_with_login' => array(
            'label' => __('Show registration form in the Login step', $text_domain),
            'input_form' => 'checkbox',
            'value' => true,
            'section' => 'general',
            'description' => __('The registration form will be shown next to the login form, it will not replace it', $text_domain),
            'pro' => true,
        ),
        'review_thumbnails' => array(
            'label' => __('Add product thumbnails to the Order Review section', $text_domain),
            'input_form' => 'checkbox',
            'value' => true,
            'section' => 'general',
            'pro' => true,
        ),

        /* Templates */
        'main_color' => array(
            'label' => __('Main Color', $text_domain),
            'input_form' => 'input_color',
            'value' => '#1e85be',
            'section' => 'design',
        ),
        'template' => array(
            'label' => __('Template', $text_domain),
            'input_form' => 'radio',
            'value' => __('default', $text_domain),
            'values' => array(
                'default'       => __('Default', $text_domain),
                'md'            => __('Material Design', $text_domain), 
                'breadcrumb'    => __('Breadcrumbs', $text_domain), 
            ),
            'section' => 'design',
            'pro' => true,
        ),
        'wpmc_buttons' => array(
            'label' => __('Use the plugin\'s buttons', $text_domain),
            'input_form' => 'checkbox',
            'value' => false,
            'description' => __('By default the plugin tries to use the theme\'s design for the buttons. If this fails, enable this option in order to use the plugin\'s button style', $text_domain),
            'section' => 'design',
            'pro' => true,
        ),

        /* Step Titles */
        't_login' => array(
            'label' => __('Login', $text_domain),
            'input_form' => 'input_text',
            'value' => __('Login', $text_domain),
            'section' => 'titles',
        ),
        't_billing' => array(
            'label' => __('Billing', $text_domain),
            'input_form' => 'input_text',
            'value' => __('Billing', $text_domain),
            'section' => 'titles',
        ),
        't_shipping' => array(
            'label' => __('Shipping', $text_domain),
            'input_form' => 'input_text',
            'value' => __('Shipping', $text_domain),
            'section' => 'titles',
        ),
        't_order' => array(
            'label' => __('Order', $text_domain),
            'input_form' => 'input_text',
            'value' => __('Order', $text_domain),
            'section' => 'titles',
        ),
        't_payment' => array(
            'label' => __('Payment', $text_domain),
            'input_form' => 'input_text',
            'value' => __('Payment', $text_domain),
            'section' => 'titles',
        ),
        't_back_to_cart' => array(
            'label' => __('Back to cart', $text_domain),
            'input_form' => 'input_text',
            'value' => _x('Back to cart', 'Frontend: button label', $text_domain),
            'section' => 'titles',
        ),
        't_skip_login' => array(
            'label' => __('Skip Login', $text_domain),
            'input_form' => 'input_text',
            'value' => _x('Skip Login', 'Frontend: button label', $text_domain),
            'section' => 'titles',
        ),
        't_previous' => array(
            'label' => __('Previous', $text_domain),
            'input_form' => 'input_text',
            'value' => _x('Previous', 'Frontend: button label', $text_domain),
            'section' => 'titles',
        ),
        't_next' => array(
            'label' => __('Next', $text_domain),
            'input_form' => 'input_text',
            'value' => _x('Next', 'Frontend: button label', $text_domain),
            'section' => 'titles',
        ),
        't_error' => array(
            'label' => __('Please fix the errors on this step before moving to the next step', $text_domain),
            'input_form' => 'input_text',
            'value' => __('Please fix the errors on this step before moving to the next step', 'Frontend: error message', $text_domain),
            'section' => 'titles',
            'description' => __('This is an error message shown in the frontend', $text_domain),
            'pro' => true,
        ),
        'c_sign' => array(
            'label' => __('AND sign', $text_domain),
            'input_form' => 'input_text',
            'value' => __('&', $text_domain), 
            'section' => 'titles',
            'description' => __('The sign between two unified steps. For example "Billing & Shipping"'),
        ),
        't_wpml' => array(
            'label' => __('Use WPML to translate the text on the Steps and Buttons', $text_domain),
            'input_form' => 'checkbox',
            'value' => false,
            'section' => 'titles',
            'description' => __('For a multilingual website the translations from WPML will be used instead of the ones in this form', $text_domain),
        ),


    );

    return $wmsc_settings;

}
}


?>
