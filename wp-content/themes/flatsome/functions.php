<?php
/**
 * Flatsome functions and definitions
 *
 * @package flatsome
 */

require get_template_directory() . '/inc/init.php';

/**
 * Note: It's not recommended to add any custom code here. Please use a child theme so that your customizations aren't lost during updates.
 * Learn more here: http://codex.wordpress.org/Child_Themes
 */


function ds_checkout_analytics( $order_id ) {
	$order = new WC_Order( $order_id );
	$currency = $order->get_order_currency();
	$total = $order->get_total();
	$date = $order->order_date;
	?>

<!-- Global site tag (gtag.js) - Google Ads: 774715967 -->
<script async src="https://www.googletagmanager.com/gtag/js?id=AW-774715967"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());
  gtag('config', 'AW-774715967');
</script>
	<!-- Paste Tracking Code Under Here -->
<!-- Event snippet for Compra conversion page -->
<script>
  gtag('event', 'conversion', {
      'send_to': 'AW-774715967/8iMHCL_BwOgBEL_0tPEC',
      'value': <?php echo $total ?>,
      'currency': 'COP',
      'transaction_id': ''
  });
</script>

	<!-- End Tracking Code -->
	<?php	
}
add_action( 'woocommerce_thankyou', 'ds_checkout_analytics' );



add_action('flatsome_after_body_open', 'tag_manager_function');

function tag_manager_function(){

    echo '
    <!-- Google Tag Manager (noscript) -->
    <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-WWCN6W5"
        height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
    <!-- End Google Tag Manager (noscript) -->';
}


function mode_maintenance(){
    if(!current_user_can('edit_themes') || !is_user_logged_in()){
        wp_die('<div style="text-align:center;"><img src="https://www.holycosmetics.com.co/wp-content/uploads/2018/11/Holy_Cosmetics_Stroke-1-01.png" width="230" style="text-align:center;" height="100"></img><div style="border:solid 1px grey;"><h1 style="color:#e7c5a8; text-align:center; text-transform:uppercase;">Sitio en Mantenimiento</h1><p style="text-align:center; font-size:18px;">Estamos trabajando para mejorar ¡en breve estaremos online!</p></div>', 'Sitio en Mantenimiento</div>', array( ‘response’ => 503 ));
    }
}
//add_action('init', 'mode_maintenance');

function add_meta_tags() {
?>
  <link rel="shortcut icon" href="https://www.holycosmetics.com.co/wp-content/uploads/2020/06/cropped-LOGO-HOLY-COSMETICS-32x32.png" sizes="32x32" />
<?php }
add_action('wp_head', 'add_meta_tags');

if( !function_exists('redirect_404_to_homepage') ){

    add_action( 'template_redirect', 'redirect_404_to_homepage' );

    function redirect_404_to_homepage(){
       if(is_404()):
            wp_safe_redirect( home_url('/') );
            exit;
        endif;
    }
}

if ( ! function_exists( 'wp_body_open' ) ) {
    function wp_body_open() {
        do_action( 'wp_body_open' );
    }
}

if ( function_exists( 'wp_body_open' ) ) {
    wp_body_open();
} else {
    do_action( 'wp_body_open' );
}

add_action( 'wp_footer', 'ga_datalayer' );

function ga_datalayer() {

    if (is_user_logged_in()) {

        $user = wp_get_current_user();

        $email =  $user->user_email;

        $email_first = explode('@', $email);

        $id = $user->ID;

        $datalayer_user_id = hash('sha256',$email.md5($email_first[0]));

        ?>

        <script>
            window.dataLayer = window.dataLayer || [];
            window.dataLayer.push({
                userId : '<?php echo $datalayer_user_id; ?>'
            })
        </script>
        <?php
    }
}

add_action( 'init', 'stop_heartbeat', 1 );

function stop_heartbeat() {
    wp_deregister_script('heartbeat');
}


add_action('woocommerce_update_product', 'sv_woo_calc_my_discount_quickedit');
function sv_woo_calc_my_discount_quickedit( $product_id ) {

    $product = wc_get_product( $product_id );


	if ($product->is_type( 'simple' )) {

		$regular  =  $product->get_regular_price();

		$sale = $product->get_sale_price();
	}
	elseif($product->is_type('variable')){

		$regular  =  $product->get_variation_regular_price( 'max', true );

		$sale = $product->get_variation_sale_price( 'max', true );
	}


    $discount = ($sale == '') ? 0 : round( 100 - ( $sale / $regular * 100), 2 );

    update_post_meta( $product_id, '_discount_amount', $discount );

}


if(!is_admin()) {
    wp_deregister_script('jquery');
    wp_register_script('jquery', 'https://cdnjs.cloudflare.com/ajax/libs/jquery/2.2.4/jquery.min.js', false, false, true);
    wp_enqueue_script('jquery');
}


