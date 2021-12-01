<?php
/**
 * Plugin Name: WooCommerce Multi-Step Checkout Modified
 * Description: Nice multi-step checkout modified
 * Version: 1.19
 * Author: Modified
 * License: GPL2
 *
 * Text Domain: wp-multi-step-checkout-mod
 * Domain Path: /languages/
 *
 * WC requires at least: 2.3.0
 * WC tested up to: 3.6
 * Requires PHP: 5.2.4
 *
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

if ( ! class_exists( 'WPMultiStepCheckout' ) ) :
/**
 * Main WPMultiStepCheckout Class
 *
 * @class WPMultiStepCheckout
 */
final class WPMultiStepCheckout {
    public $version = '1.32';
    public $options = array();

    protected static $_instance = null;

    public $theme = '';

    /**
     * Main WPMultiStepCheckout Instance
     *
     * Ensures only one instance of WPMultiStepCheckout is loaded or can be loaded
     *
     * @static
     * @return WPMultiStepCheckout - Main instance
     */
    public static function instance() {
        if ( is_null( self::$_instance ) ) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    /**
      * Cloning is forbidden.
      */
    public function __clone() {
         _doing_it_wrong( __FUNCTION__, __( 'An error has occurred. Please reload the page and try again.' ), '1.0' );
    }

    /**
     * Unserializing instances of this class is forbidden.
     */
    public function __wakeup() {
        _doing_it_wrong( __FUNCTION__, __( 'An error has occurred. Please reload the page and try again.' ), '1.0' );
    }

    /**
     * WPMultiStepCheckout Constructor
     */
    public function __construct() {

        define('WMSC_PLUGIN_FILE', __FILE__);
        define('WMSC_PLUGIN_URL', plugins_url('/', __FILE__));
        define('WMSC_PLUGIN_PATH', plugin_dir_url('/', __FILE__));
        define('WMSC_VERSION', $this->version );

        if ( ! class_exists('woocommerce') ) {
          add_action( 'admin_notices', array($this, 'install_woocommerce_admin_notice' ) );
          return false;
        }

        if ( is_admin() ) {
            include_once 'includes/admin-side.php';
        }

        $this->update_14_version();
        // Replace the checkout template

        add_filter( 'woocommerce_locate_template', array( $this, 'woocommerce_locate_template' ), 10, 3 );

        add_action( 'woocommerce_checkout_process',  array( $this, 'validate_id_email_phone'));



        // Enqueue the scripts for the frontend
        add_action( 'wp_enqueue_scripts', array( $this, 'wp_enqueue_scripts' ) );
        add_action( 'wp_head', array( $this, 'wp_head') );
        add_action( 'wp_head', array( $this, 'compatibilities'), 40 );
        add_action( 'after_setup_theme', array( $this, 'after_setup_theme' ) );
        add_action( 'init', array($this, 'load_plugin_textdomain' ) );
        add_action( 'wp_ajax_ga_event_purchase' , array( $this, 'ga_event_purchase' ) );
			  add_action( 'wp_ajax_nopriv_ga_event_purchase' , array( $this, 'ga_event_purchase' ) );

    }



    public function validate_id_email_phone(){

        $email = $_POST['billing_email'];

        if(!preg_match("/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix", $email)){

          wc_add_notice( 'Correo electronico invalido, porfavor corrigelo.', 'error');

        }

        $id = trim($_POST['billing_identification_number_']);

        if(!is_numeric($id)){

          wc_add_notice( 'La identificacion debe ser numérica.', 'error');
        }

        $phone = $_POST['billing_phone'];

        if(!is_numeric($phone) || strlen($phone) > 10){

          wc_add_notice( 'El telefono de facturación debe ser numérico no mayor a 10 digitos.', 'error');
        }

    }

    /**
     * Modify the default WooCommerce hooks
     */
    public function adjust_hooks() {

      //custom hooks

      add_filter('plugin_action_links_'.plugin_basename(__FILE__), array($this,'wpmc_plugin_settings_link'));
      add_action( 'woocommerce_custom_order_place_position', array($this,'output_payment_button') );
      add_filter( 'woocommerce_order_button_html', array($this,'remove_woocommerce_order_button_html') );
      add_action( 'woocommerce_custom_order_review', array($this,'woo_order_review') );

      // Remove login messages
      remove_action( 'woocommerce_before_checkout_form', 'woocommerce_checkout_login_form', 10 );
      remove_action( 'woocommerce_before_checkout_form', 'woocommerce_checkout_coupon_form', 10 );

      // Split the `Order` and the `Payment` tabs
      remove_action( 'woocommerce_checkout_order_review', 'woocommerce_order_review', 10 );
      remove_action( 'woocommerce_checkout_order_review', 'woocommerce_checkout_payment', 20 );
      add_action( 'wpmc-woocommerce_order_review', 'woocommerce_order_review', 20 );
      add_action( 'wpmc-woocommerce_checkout_payment', 'woocommerce_checkout_payment', 10 );

      // Split the `woocommerce_before_checkout_form`
      remove_action( 'woocommerce_before_checkout_form', 'woocommerce_checkout_login_form', 10 );
      remove_action( 'woocommerce_before_checkout_form', 'woocommerce_checkout_coupon_form', 10 );
      add_action( 'wpmc-woocommerce_checkout_login_form', 'woocommerce_checkout_login_form', 10 );
      add_action( 'wpmc-woocommerce_checkout_coupon_form', 'woocommerce_checkout_coupon_form', 10 );

      // Compatibility with the WooCommerce Germanized plugin
      if ( class_exists('WooCommerce_Germanized') ) {
          remove_action( 'init', 'woocommerce_gzd_checkout_load_ajax_relevant_hooks' );
          add_action( 'woocommerce_checkout_order_review', 'woocommerce_gzd_template_order_submit', 21 );
          add_action( 'wpmc-woocommerce_order_review', 'woocommerce_gzd_template_render_checkout_checkboxes', 10 );
          add_action( 'wpmc-woocommerce_order_review', 'woocommerce_gzd_template_checkout_set_terms_manually', 3 );
          add_filter( 'wc_gzd_checkout_params', array($this, 'wc_gzd_checkout_params' ));
      }


    }


    public function replace_accent($str){

        $unwanted_array = array(    'Š'=>'S', 'š'=>'s', 'Ž'=>'Z', 'ž'=>'z', 'À'=>'A', 'Á'=>'A', 'Â'=>'A', 'Ã'=>'A', 'Ä'=>'A', 'Å'=>'A', 'Æ'=>'A', 'Ç'=>'C', 'È'=>'E', 'É'=>'E',
                            'Ê'=>'E', 'Ë'=>'E', 'Ì'=>'I', 'Í'=>'I', 'Î'=>'I', 'Ï'=>'I', 'Ñ'=>'N', 'Ò'=>'O', 'Ó'=>'O', 'Ô'=>'O', 'Õ'=>'O', 'Ö'=>'O', 'Ø'=>'O', 'Ù'=>'U',
                            'Ú'=>'U', 'Û'=>'U', 'Ü'=>'U', 'Ý'=>'Y', 'Þ'=>'B', 'ß'=>'Ss', 'à'=>'a', 'á'=>'a', 'â'=>'a', 'ã'=>'a', 'ä'=>'a', 'å'=>'a', 'æ'=>'a', 'ç'=>'c',
                            'è'=>'e', 'é'=>'e', 'ê'=>'e', 'ë'=>'e', 'ì'=>'i', 'í'=>'i', 'î'=>'i', 'ï'=>'i', 'ð'=>'o', 'ñ'=>'n', 'ò'=>'o', 'ó'=>'o', 'ô'=>'o', 'õ'=>'o',
                            'ö'=>'o', 'ø'=>'o', 'ù'=>'u', 'ú'=>'u', 'û'=>'u', 'ý'=>'y', 'þ'=>'b', 'ÿ'=>'y' );
        return strtr( $str, $unwanted_array );
    }

    public function ga_event_purchase_old(){

      if(isset($_POST['order_id']) && $_POST['order_id'] != ''){

          $body = 'v=1&t=event&tid=UA-130946509-2&ec=Ecommerce&ea=Purchase&el=Purchase&dh=www.holycosmetics.com.co&dp=Transaction&dt=Transaction&ta=Holy Cosmetics&cu=COP&ta=Holy Cosmetics&pa=purchase';

          $order_id = $_POST['order_id'];

          $responseOrder = array();

          $order = wc_get_order( $order_id );
          #User data
          $user = $order->get_user();

          $email =  $user->user_email;

          $email_first = explode('@', $email);

          $datalayer_user_id = hash('sha256',$email.md5($email_first[0]));

          $body .= '&cid='.$datalayer_user_id;
          #orderdata

          $order_total = $order->get_total();

          $body .= '&ti='.$_POST['order_id'];

          $body .= '&tr='.str_replace(array(',', '.'), '', (int) $order_total);

          $body .= '&tt='.$order->get_total_tax();

          $body .= '&ts='.$order->get_shipping_total();

          $coupons = '';

          foreach( $order->get_coupon_codes() as $coupon_code ){

              $coupons .= $coupon_code.' ';
          }

          $body .= '&tcc='.$coupons;

          $payment_method = $order->get_payment_method();

          $items = $order->get_items();

          $i = 1;

          $products = array();

          foreach ($items as  $item) {

              $variation = '';

              if( $item->get_product()->is_type('variation') ){

                  $variation_attributes = $item->get_product()->get_variation_attributes();

                foreach($variation_attributes as $attribute_taxonomy => $term_slug ){

                    $taxonomy = str_replace('attribute_', '', $attribute_taxonomy );

                    $attribute_name = wc_attribute_label( $taxonomy, $product );

                    if( taxonomy_exists($taxonomy) ) {
                        $variation = get_term_by( 'slug', $term_slug, $taxonomy )->name;
                    } else {
                        $variation = $term_slug;
                    }
                }

                $parent_product = wc_get_product( $item->get_product()->get_parent_id() );

              }

              $name = ucfirst(strtolower ( $this->replace_accent($item->get_name())));

              $body .= '&pr'.$i.'id='.$item->get_product_id();

              $body .= '&pr'.$i.'nm='.$name;

              $body .= '&pr'.$i.'pr='.$item->get_total();

              $categories = ($item->get_product()->is_type('variation')) ? $parent_product->get_categories() : $item->get_product()->get_categories();

              $categories = str_replace(array(', '),',', strip_tags($categories));

              $category = explode(',', $categories)[0];

              $categories = ucfirst(strtolower ( $this->replace_accent($category)));

              $body .= '&pr'.$i.'ca='.$categories;

              $brand = ($item->get_product()->is_type('variation')) ? $parent_product->get_attribute('marca') : $item->get_product()->get_attribute('marca');

              $body .= '&pr'.$i.'br='.$brand;

              $body .= '&pr'.$i.'va='.$variation;

              $body .= '&pr'.$i.'qt='.$item->get_quantity();

              $body .= '&pr'.$i.'cd5='.$payment_method;

              $body .= '&pr'.$i.'cd1='.$datalayer_user_id;

              $i++;
          }

          $url = 'https://www.google-analytics.com/collect';



          $response = wp_remote_post( $url, array(
              'method' => 'POST',
              'timeout' => 45,
              'redirection' => 5,
              'httpversion' => '1.0',
              'blocking' => true,
              'headers' => array(),
              'body' =>  $body,
              'cookies' => array()
              )
          );

          update_post_meta($order_id, 'ga_purchase_json', $body);

          update_post_meta($order_id, 'ga_purchase_json_resoibse', print_r($response));

          echo 1;

          wp_die();
      }

    }

    public function ga_event_purchase(){

      if(isset($_POST['order_id']) && $_POST['order_id'] != ''){

          $order_id = $_POST['order_id'];

          $order = wc_get_order( $order_id );

          $eventData['event'] = 'transaction';
          
          $eventData['ecommerce']['purchase'] = [];
          #orderdata

          $coupons = '';

          foreach( $order->get_coupon_codes() as $coupon_code ){

              $coupons .= $coupon_code.' ';
          }

          $eventData['ecommerce']['purchase']['actionField'] = [
            'id' => $order_id,                     
            'affiliation' => 'Holy Cosmetics',
            'revenue' => ($order->get_total() - $order->get_shipping_total() - $order->get_total_tax()),
            'tax' => '0',
            'shipping' =>  $order->get_shipping_total(),
            'coupon' =>  $coupons      

          ];

          $payment_method = $order->get_payment_method();

          $items = $order->get_items();

          $products = array();

          foreach ($items as  $item) {

              $variation = '';

              if( $item->get_product()->is_type('variation') ){

                  $variation_attributes = $item->get_product()->get_variation_attributes();

                foreach($variation_attributes as $attribute_taxonomy => $term_slug ){

                    $taxonomy = str_replace('attribute_', '', $attribute_taxonomy );

                    $product = $item->get_product();

                    $attribute_name = wc_attribute_label( $taxonomy, $product );

                    if( taxonomy_exists($taxonomy) ) {
                        $variation = get_term_by( 'slug', $term_slug, $taxonomy )->name;
                    } else {
                        $variation = $term_slug;
                    }
                }

                $parent_product = wc_get_product( $item->get_product()->get_parent_id() );

              }

              $name = ucfirst(strtolower ( $this->replace_accent($item->get_name())));

              $categories = ($item->get_product()->is_type('variation')) ? $parent_product->get_categories() : $item->get_product()->get_categories();

              $categories = str_replace(array(', '),',', strip_tags($categories));

              $category = explode(',', $categories)[0];

              $categories = ucfirst(strtolower ( $this->replace_accent($category)));


              $brand = ($item->get_product()->is_type('variation')) ? $parent_product->get_attribute('marca') : $item->get_product()->get_attribute('marca');


              $products[] = [
                'name' => $name,
                'id' => $item->get_product_id(),
                'price' =>  $item->get_total(),
                'brand' => $brand,
                'category' => $categories,
                'variant' => $variation,
                'quantity' => $item->get_quantity(),
                'coupon' => $coupons,
                'metodoDePago' => $payment_method
              ];


          }

          $eventData['ecommerce']['purchase']['products'] = $products;

          $body = json_encode($eventData);

          update_post_meta($order_id, 'ga_purchase_json', $body);

          echo $body;

          wp_die();
      }

    }


    /**
     * Custom functions
     */

    public function wpmc_plugin_settings_link($links) {
        $action_links = array(
            'settings' => '<a href="' . admin_url( 'admin.php?page=wmsc-settings' ) . '" aria-label="' . esc_attr__( 'View plugin\'s settings', 'wp-multi-step-checkout' ) . '">' .       esc_html__(  'Settings', 'wp-multi-step-checkout' ) . '</a>',
        );
        return array_merge( $action_links, $links );
    }

    public function output_payment_button() {
        $order_button_text = apply_filters( 'woocommerce_order_button_text', __( 'Place order', 'woocommerce' ) );
        echo '<input type="submit" class="button alt" name="woocommerce_checkout_place_order" id="place_order" value="' . esc_attr( $order_button_text ) . '" data-value="' . esc_attr( $order_button_text ) . '" />';
    }

    public function remove_woocommerce_order_button_html() {
        return '';
    }

    public function woo_order_review() {
        include 'includes/template/checkout-review.php';
    }



    /**
     * Load the form-checkout.php template from this plugin
     */
    public function woocommerce_locate_template( $template, $template_name, $template_path ){

      $user = wp_get_current_user();
      $gen_settings = get_option('phoe_disc_value');
      $enable_disc=isset($gen_settings['enable_disc'])?$gen_settings['enable_disc']:'';
      if( 'checkout/form-checkout.php' != $template_name )
        return $template;


      if (!in_array( 'wholesaler', (array) $user->roles ) || $enable_disc != "1" ) {
         $template = plugin_dir_path( __FILE__ ) . 'includes/form-checkout.php';
          $this->adjust_hooks();
      }

      return $template;
    }

    /**
     * Enqueue the JS and CSS assets
     */
    public function wp_enqueue_scripts() {
        $options = get_option('wmsc_options');
        $keyboard_nav = (isset($options['keyboard_nav']) && $options['keyboard_nav'] ) ? true : false;

        $u = plugins_url('/', __FILE__) . 'assets/'; // URL of the assets folder
        $v = $this->version; // this plugin's version
        $d = array( 'jquery' ); // dependencies
        $w = false; // where? in footer?
        $p = ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ? '' : '.min';

        // Load scripts
        wp_register_script( 'wpmc', $u . 'js/script.min.js', $d, $v, $w );
        wp_localize_script( 'wpmc', 'WPMC', array('keyboard_nav' => $keyboard_nav ));
        wp_register_style ( 'wpmc', $u.'css/style-progress'.$p.'.css',  array(), $v );
        if ( is_checkout() ) {
            wp_enqueue_script ( 'wpmc' );
            wp_enqueue_style  ( 'wpmc' );
        }
        wp_deregister_script('wc-checkout');
        wp_register_script('wc-checkout', $u . "js/checkout.js",
        array( 'jquery', 'woocommerce', 'wc-country-select', 'wc-address-i18n' ), $v, TRUE);
        wp_enqueue_script('wc-checkout');
    }


    /**
     * Change the main color
     */
    public function wp_head() {
        if ( ! is_checkout() ) return;
        $options = get_option('wmsc_options');
        $color = (isset($options['main_color'])) ? $options['main_color'] : '#1e85be';

      ?>
      <style type="text/css">
      .wpmc-tabs-wrapper .wpmc-tab-item.current::before {
          border-bottom-color: <?php echo $color; ?>;
      }
      .wpmc-tabs-wrapper .wpmc-tab-item.current .wpmc-tab-number {
          border-color: <?php echo $color; ?>
      }
      </style>
      <?php
    }


    /**
     * Compatibilities with themes
     */
    public function compatibilities() {
      if ( ! is_checkout() ) return;

      if ( $this->theme('storefront')) { ?>
        <style type="text/css">
          #order_review, #order_review_heading { float: left; width: 100%; }
        </style>
      <?php }

      if ( $this->theme('avada')) { ?>
        <style type="text/css">
          .wpmc-nav-wrapper { float: left; margin-top: 10px; }
          .woocommerce-checkout a.continue-checkout{display: none;}
          .woocommerce-error,.woocommerce-info,.woocommerce-message{padding:1em 2em 1em 3.5em;margin:0 0 2em;position:relative;background-color:#f7f6f7;color:#515151;border-top:3px solid #a46497;list-style:none outside;width:auto;word-wrap:break-word}.woocommerce-error::after,.woocommerce-error::before,.woocommerce-info::after,.woocommerce-info::before,.woocommerce-message::after,.woocommerce-message::before{content:' ';display:table}.woocommerce-error::after,.woocommerce-info::after,.woocommerce-message::after{clear:both}.woocommerce-error .button,.woocommerce-info .button,.woocommerce-message .button{float:right}.woocommerce-error li,.woocommerce-info li,.woocommerce-message li{list-style:none outside!important;padding-left:0!important;margin-left:0!important}.rtl.woocommerce .price_label,.rtl.woocommerce .price_label span{direction:ltr;unicode-bidi:embed}.woocommerce-message{border-top-color:#8fae1b}.woocommerce-info{border-top-color:#1e85be}.woocommerce-info::before{color:#1e85be}.woocommerce-error{border-top-color:#b81c23}.woocommerce-checkout .shop_table td, .woocommerce-checkout .shop_table th {padding: 10px}.woocommerce .single_add_to_cart_button, .woocommerce button.button {margin-top: 10px}
          .woocommerce .woocommerce-form-coupon-toggle { display: none; }
          .woocommerce .checkout_coupon { display: flex !important; }
        </style>
      <?php }


        if ( $this->theme('theretailer')) { ?>
          <style type="text/css">
          .wpmc-nav-buttons button.button { display: none !important; }
          .wpmc-nav-buttons button.button.current { display: inline-block !important; }
          </style>
        <?php }

      if ( $this->theme('Divi')) { ?>
        <style type="text/css">
            #wpmc-back-to-cart:after, #wpmc-prev:after { display: none; }
            #wpmc-back-to-cart:before, #wpmc-prev:before{ position: absolute; left: 1em; margin-left: 0em; opacity: 0; font-family: "ETmodules"; font-size: 32px; line-height: 1em; content: "\34"; -webkit-transition: all 0.2s; -moz-transition: all 0.2s; transition: all 0.2s; }
            #wpmc-back-to-cart:hover, #wpmc-prev:hover { padding-right: 0.7em; padding-left: 2em; left: 0.15em; }
            #wpmc-back-to-cart:hover:before, #wpmc-prev:hover:before { left: 0.2em; opacity: 1;}
        </style>
      <?php }

      if ( $this->theme('enfold')) { ?>
        <style type="text/css">
            .wpmc-footer-right { width: auto; }
        </style>
      <?php }

      if ( $this->theme('flatsome')) { ?>
        <style type="text/css">
            .processing::before, .loading-spin { content: none; }
            .wpmc-footer-right button.button { margin-right: 0; }
        </style>
      <?php }


      if ( $this->theme('bridge')) { ?>
        <style type="text/css">
            .woocommerce input[type="text"]:not(.qode_search_field), .woocommerce input[type="password"], .woocommerce input[type="email"], .woocommerce textarea, .woocommerce-page input[type="tel"], .woocommerce-page input[type="text"]:not(.qode_search_field), .woocommerce-page input[type="password"], .woocommerce-page input[type="email"], .woocommerce-page textarea, .woocommerce-page select { width: 100%; }
        .woocommerce-checkout table.shop_table { width: 100% !important; }
        </style>
      <?php }

      if ( $this->theme('zass')) { ?>
        <style type="text/css">form.checkout.woocommerce-checkout.processing:after {content: '';}.woocommerce form.checkout.woocommerce-checkout.processing:before {display: none;}</style>
      <?php }


      if ( defined( 'WPB_VC_VERSION' ) ) { ?>
        <style type="text/css">
            .woocommerce-checkout .wpb_column .vc_column-inner::after{clear:none !important; content: none !important;}
            .woocommerce-checkout .wpb_column .vc_column-inner::before{content: none !important;}
        </style>
      <?php }

      if ( class_exists('WooCommerce_Germanized') ) { ?>
        <style type="text/css"> #order_review_heading {display: block !important;} </style>
      <?php }

    }


    /**
     * Compatibilities with themes
     */
    public function after_setup_theme() {
        if ( $this->theme('Avada') ) {
            if ( function_exists('avada_woocommerce_before_checkout_form' ) ) {
                remove_action( 'woocommerce_before_checkout_form', 'avada_woocommerce_before_checkout_form' );
            }

            if ( function_exists( 'avada_woocommerce_checkout_after_customer_details' ) ) {
                remove_action( 'woocommerce_checkout_after_customer_details', 'avada_woocommerce_checkout_after_customer_details' );
            }

            if ( function_exists( 'avada_woocommerce_checkout_before_customer_details' ) ) {
                remove_action( 'woocommerce_checkout_before_customer_details', 'avada_woocommerce_checkout_before_customer_details' );
            }
            global $avada_woocommerce;

            if( ! empty( $avada_woocommerce ) ){
                remove_action( 'woocommerce_before_checkout_form', array( $avada_woocommerce, 'avada_top_user_container' ), 1 );
                remove_action( 'woocommerce_before_checkout_form', array( $avada_woocommerce, 'checkout_coupon_form' ), 10 );
                remove_action( 'woocommerce_before_checkout_form', array( $avada_woocommerce, 'before_checkout_form' )  );
                remove_action( 'woocommerce_after_checkout_form',  array( $avada_woocommerce, 'after_checkout_form' ) );
            }

        }

        if ( $this->theme('hestia-pro') ) {
            remove_action( 'woocommerce_before_checkout_form', 'hestia_coupon_after_order_table_js' );
        }
    }


    function wc_gzd_checkout_params($params) {
        $params['adjust_heading'] = false;
        return $params;
    }


    /**
     * Is $string theme active?
     */
    public function theme($string) {
        $string = strtolower($string);
        if (empty($this->theme)) {
            $this->theme = strtolower(get_template());
        }
        if (strpos($this->theme, $string ) !== false)
            return true;

        return false;
    }


    /**
     * Admin notice that WooCommerce is not activated
     */
    public function install_woocommerce_admin_notice() {
      ?>
      <div class="error">
          <p><?php _e( 'The WP Multi-Step Checkout plugin is enabled, but it requires WooCommerce in order to work.', 'Alert Message: WooCommerce require', 'wp-multi-step-checkout' ); ?></p>
      </div>
      <?php
    }


    /**
     * Load the textdomain
     */
	public function load_plugin_textdomain() {
		load_plugin_textdomain( 'wp-multi-step-checkout', false, plugin_basename( dirname( __FILE__ ) ) . '/languages' );
	}


    /**
     * Update options array for the 1.4 version
     */
    function update_14_version() {
        if ( ! $old_options = get_option('wpmc-settings') ) return;

        require_once 'includes/settings-array.php';
        $defaults = get_wmsc_settings('wp-multi-step-checkout');


        $new_options = array();
        foreach($defaults as $_key => $_value ) {
            if ( isset($old_options[$_key]) ) {
                $new_options[$_key] = $old_options[$_key][2];
            } else {
                $new_options[$_key] = $_value['value'];
            }
        }

        update_option('wmsc_options', $new_options);
        delete_option('wpmc-settings');
    }

}

endif;

/**
 * Returns the main instance of WPMultiStepCheckout
 *
 * @return WPMultiStepCheckout
 */
function WPMultiStepCheckout() {
    return WPMultiStepCheckout::instance();
}

WPMultiStepCheckout();
