<?php

use PhpOffice\PhpSpreadsheet\Spreadsheet;

use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

use PhpOffice\PhpSpreadsheet\IOFactory;

use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

use PhpOffice\PhpSpreadsheet\Worksheet\MemoryDrawing;
use PhpOffice\PhpSpreadsheet\Style\Fill;

//FRONT FILTERS
add_filter('wp_nav_menu_items', 'new_nav_menu_items');

add_shortcode('wholesale_request_form', 'custom_wholesale_form');

add_filter('woocommerce_before_add_to_cart_form', 'show_wholesale_promotions');

add_action( 'woocommerce_before_add_to_cart_button', 'hidden_field_before_add_to_cart_button', 5 );

add_action( 'woocommerce_thankyou', 'add_thank_you_wholesale_message' );

add_action( 'init', 'remove_filters_change_quantity' );

add_action( 'woocommerce_after_order_notes', 'add_custom_checkout_hidden_field' );

add_action( 'woocommerce_checkout_update_order_meta', 'save_custom_checkout_hidden_field' );

add_action( 'woocommerce_order_details_before_order_table', 'display_voucher_in_customer_order', 10 );

add_action( 'init',  'send_wholesales_payment_voucher' );

add_filter( 'woocommerce_review_order_before_submit' , 'add_shipping_message_wholesales');

add_filter('woocommerce_locate_template', 'woocommerce_wholesaler_template', 10, 3);

add_action('parse_request', 'download_masive_products_excel', 0);

add_filter( 'woocommerce_add_cart_item_data', 'save_selected_discount_price', 99, 2 );

add_filter('woocommerce_get_price_html', 'custom_price_WPA111772', 10, 2);


function custom_price_WPA111772($price, $product) {
    if (!is_user_logged_in()) return $price;

    if (has_role_WPA111772('wholesaler')){

        return wc_price($product->get_regular_price());

    }
    return $price;
}


function has_role_WPA111772($role = '',$user_id = null){
    if ( is_numeric( $user_id ) )
        $user = get_user_by( 'id',$user_id );
    else
        $user = wp_get_current_user();

    if ( empty( $user ) )
        return false;

    return in_array( $role, (array) $user->roles );
}


function new_nav_menu_items($items)
{

    //$homelink = '<li class="home"><a href="' . home_url('/solicitud-mayoristas') . '">' . __('Mayoristas') . '</a></li>';

    $items = $items;

    return $items;
}

function custom_wholesale_form()
{

    ob_start();

    wholesales_registration_function();

    return ob_get_clean();
}

function wholesales_registration_function()
{
    if (isset($_POST['submit_wholesale_request'])) {


        // sanitize user form input
        global $email, $first_name, $last_name, $phone, $reg_errors;

        $email      = sanitize_email($_POST['email']);
        $first_name = sanitize_text_field($_POST['fname']);
        $last_name  = sanitize_text_field($_POST['lname']);
        $phone      = sanitize_text_field($_POST['phone']);
        $url = sanitize_text_field($_POST['url']);

        registration_validation($email, $first_name, $last_name, $phone, $url);


        // call @function complete_registration to create the user
        // only when no WP_error is found
        /*complete_registration(
        $email,
        $first_name,
        $last_name,
        $phone
        );*/

        if(count($reg_errors->errors) > 0){

            return registration_form();

        }

        if (sendRequestWholesaleEmail($email, $first_name, $last_name, $phone, $url)) {

            add_action('form_message', 'write_here_show_success_messages');

        }

        $location = get_site_url() . '/mayoristas?success=true';

        wp_redirect($location, 301);
    }

    registration_form($email, $first_name, $last_name, $phone, $url);


}


function registration_validation($email, $phone, $first_name, $last_name, $url)
{

    global $reg_errors;

    $reg_errors = new WP_Error;

    if (empty($email)) {
        $reg_errors->add('field', 'El correo electronico es requerido.');
    }

    if (empty($phone)) {
        $reg_errors->add('field', 'El telefono  es requerido.');
    }

    if (empty($first_name)) {
        $reg_errors->add('field', 'Los nombres son requeridos.');
    }

    if (empty($last_name)) {
        $reg_errors->add('field', 'Los apellidos son requeridos.');
    }

    if (!is_email($email)) {
        $reg_errors->add('email', 'El correo suministrado ya se encuentra en uso.');
    }

    if (!filter_var($url, FILTER_VALIDATE_URL)) {
        $reg_errors->add('email', 'La url no es valida.');
    }

    if (is_wp_error($reg_errors)) {

        foreach ($reg_errors->get_error_messages() as $error) {

            echo '<div>';
            echo '<strong>ERROR</strong>:';
            echo $error . '<br/>';
            echo '</div>';

        }
    }

}

function sendRequestWholesaleEmail($email, $first_name, $last_name, $phone, $url)
{

    $status = false;

    $name = sprintf('%s %s', $first_name, $last_name);

    $to = get_option( 'wholesales_email' );

    $subject = "Solicitud Mayorista: " . $email;

    $content = "<strong>Name</strong>: {$name}<br>" . "<strong>Email</strong>: {$email}" . "<br><strong>Phone:</strong>: {$phone}" . "<br><strong>Url o intagram</strong>: {$url}<br><button><a href='" . get_site_url() . "/wholesales/createuser?name=" . $name . "&email=" . $email . "&phone=" . $phone . "' target='_blank'>ACEPTAR</a></button><br><button><a href='" . get_site_url() . "/wholesales/denegateuser?name=" . $name . "&email=" . $email . "&phone=" . $phone . "' target='_blank'>DENEGAR</a></button>";

    $headers = array(
        'Reply-To' => $name . '<' . $email . '>'
    );

    if (send_custom_email($to,$subject,$content,'Solicitud para crear usuario mayorista')) {

        $status = true;
    }

    return $status;
}

function set_html_content_type()
{

    return 'text/html';
}

function registration_form(){

    $is_pdf_active = get_option( 'wholesales_pdf', 1 );

    $is_excel_active = get_option( 'wholesales_excel', 1 );

    if($is_pdf_active){

        $url_custom_pdf = get_home_url().'?action=wholesales_catelog_shop&id=full';

        $label_pdf = 'Descargar catalogo mayoristas (PDF)';

        echo '<div class="gmwcp_button mobile-catalog-button">
            <a href="'. $url_custom_pdf.'" class="button secondary" target="_blank">'.$label_pdf.'</a>
        </div>';

    }

    if($is_excel_active){

        $url_custom_excel = get_home_url().'/generate_wholesales_excel';

        $label_excel = 'Descargar catalogo mayoristas (EXCEL)';



        echo '<div class="gmwcp_button catalog-excel-button mobile-catalog-button">
            <a href="'. $url_custom_excel.'" class="button secondary excel-catalog-button" target="_blank">'.$label_excel.'</a>
        </div>';

    }

    $user = wp_get_current_user();

    if (is_user_logged_in() && in_array('wholesaler', (array) $user->roles)) {

        $txt = '<h2>Ya estas logueado como mayorista, disfruta de los mejores descuentos haciendo click <a href="'.get_site_url().'/tienda'.'">AQUI</a>!</h2>';

        echo $txt; return;

    }else if(is_user_logged_in() && !in_array('wholesaler', (array) $user->roles)){

        $txt = '<h3>Solicita la creacion de una nueva cuenta mayorista para obtener mejores precios!</h3>
            <p>Si ya tienes una cuenta creada como cliente debes utilizar otro correo electrónico.</p>
        ';

    }else{

        $txt = getLoginForm();
    }


    $is_pdf_active = get_option( 'wholesales_pdf', 1 );

    $is_excel_active = get_option( 'wholesales_excel', 1 );

    $desktop_pdf_button = '';

    $desktop_excel_button = '';

    if ($is_pdf_active) {

        $label = 'Descargar catalogo mayoristas (PDF)';

        $url_custom = get_home_url().'?action=wholesales_catelog_shop&id=full';

        $desktop_pdf_button = '<div class="gmwcp_button desktop-catalog-button"><a href="'. $url_custom.'" class="button secondary" target="_blank">'.$label.'</a></div>';
    }

    if ($is_pdf_active) {

        $url_custom_excel = get_home_url().'/generate_wholesales_excel';

        $label_excel = 'Descargar catalogo mayoristas (EXCEL)';

        $desktop_excel_button = '<div class="gmwcp_button desktop-catalog-button catalog-excel-button"><a href="'. $url_custom_excel.'" class="button secondary excel-catalog-button" target="_blank">'.$label_excel.'</a></div>';
    }






    echo '
            <div class="container-box">
                <div class="left">
                    '.$txt.$desktop_pdf_button.$desktop_excel_button.'

                </div>

            <div class="right">'
    ;

    echo '
        <h2>FORMULARIO DE SOLICITUD MAYORISTAS</h2>
            <p>Envíanos la solicitud para ser un mayorista de nosotros ingresando el siguente formulario, recibirás a tu correo una confirmación en caso de ser aprobada tu solicitud.</p>
            ';

    if ($_GET['success'] == 'true') {

        echo '<strong>TU SOLICITUD FUE ENVIADA CON EXITO!</strong>';

    } else if ($_GET['success'] == 'false') {

        echo '<strong>ALGO FALLO EN EL ENVIO DE LA SOLICITUD, VUELVE A INTENTARLO</strong>';
    }

    echo '
        <div class="wholesale-form ">
        <form action="' . $_SERVER['REQUEST_URI'] . '" method="post">

            <div>
            <label for="email">' . __('Correo electronico') . ' <strong>*</strong></label>
            <input type="text" name="email" value="' . (isset($_POST['email']) ? $email : null) . '">
            </div>

            <div>
            <label for="firstname">' . __('Nombres') . '</label>
            <input type="text" name="fname" value="' . (isset($_POST['fname']) ? $first_name : null) . '">
            </div>

            <div>
            <label for="website">' . __('Apellidos') . '</label>
            <input type="text" name="lname" value="' . (isset($_POST['lname']) ? $last_name : null) . '">
            </div>

            <div>
            <label for="website">' . __('Telefono') . '</label>
            <input type="text" name="phone" value="' . (isset($_POST['phone']) ? $phone : null) . '">
            </div>

            <div>
            <label for="website">' . __('Url o instagram del sitio') . '</label>
            <input type="text" name="url" value="' . (isset($_POST['url']) ? $url : null) . '">
            </div>
                <br>
            <input type="submit" name="submit_wholesale_request" value="Enviar solicitud"/>
        </form>
        </div>
        ';




    echo '
        </div>
    </div>
    ';


    /*if (!is_user_logged_in()) {
        echo '<h2>LOGIN MAYORISTAS</h2>';
        wp_login_form();
    } else if (in_array('wholesaler', (array) $user->roles)) {
        echo '<h4>Ya estas logueado como mayorista!</h4>';
    }*/
    echo '
        <style>
        .left{

            pag
        }

        .container-box:after {
            clear: both;
        }

        .container-box > div {
            min-height: 200px;
            float: left;
            width: 50%;
            padding: 20px;
        }

        @media all and (max-width: 500px) {
            .container-box > div {
                float: none;
                width: 100%;
            }
        }
        div {
            margin-bottom:2px;
        }

        input{
            margin-bottom:4px;

        }
        h2,p,h3{
            text-align: center;
        }

        form{

            max-width:500px;
        }

        .wholesale-form {
            display: table;
            width: 100%;
        }
        form {
            //text-align: center;
            vertical-align: middle;
            width:100%;
        }

        #loginform{


        }

        .excel-catalog-button{

            background-color: #148F77 !important;
        }

        .catalog-button{
            float: left;
        }

        @media screen and (max-width: 600px) {
            .desktop-catalog-button {
                display: none;
            }
        }

        @media screen and (min-width: 600px) {
            .mobile-catalog-button {
                display: none;
            }
        }

        .mobile-catalog-button a {
            width: 100%;
            text-transform: uppercase;
        }

        .desktop-catalog-button a {
            width: 100%;
            text-transform: uppercase;
        }
        </style>
        ';

}

function getLoginForm(){



    return '
        <h2>LOGIN MAYORISTAS</h2>
        <p>Logueate aqui para obtener los mejores descuentos por ser mayorista con nosotros</p>
        <form name="loginform" id="loginform" action="'.get_site_url().'/wp-login.php" method="post">

            <p class="login-username">
                <label for="user_login">Nombre de usuario o dirección de correo</label>
                <input type="text" name="log" id="user_login" class="input" value="" size="20">
            </p>
            <p class="login-password">
                <label for="user_pass">Contraseña</label>
                <input type="password" name="pwd" id="user_pass" class="input" value="" size="20">
            </p>

            <p class="login-remember"><label><input name="rememberme" type="checkbox" id="rememberme" value="forever"> Recuérdame</label></p>
            <p class="login-submit">
                <input type="submit" name="wp-submit" id="wp-submit" class="button button-primary" value="Acceder">
                <input type="hidden" name="redirect_to" value="http://localhost/holy/solicitud-mayoristas/">
            </p>

        </form>

    ';

    //


}

function show_wholesale_promotions(){
    setlocale(LC_MONETARY, 'en_US');
    global $product;

    $user = wp_get_current_user();

    if (in_array('wholesaler', (array) $user->roles)) {

        wp_enqueue_script('wholesale_product_script', plugins_url('product.js', '/dynamic-price-and-discounts-for-woocommerce/assets/js/product.js'), array(
            'jquery'
        ),'1.8');

        wp_enqueue_style('wholesale-discount-price', plugins_url('product_wholesale_price.css', '/dynamic-price-and-discounts-for-woocommerce/assets/css/product_wholesale_price.css'));

        $id = $product->get_id();

        $_product = wc_get_product( $id );

        $data = get_post_meta($id, 'phoen_woocommerce_discount_mode',true);

        if($data != ''){

            $discount_type = get_post_meta($id, 'phoen_woocommerce_discount_type',true);

            if ($discount_type  == 'price') {

                echo '<div class="radio-button_wrapper">';

                foreach ($data as $key => $value) {

                    $discountPrice = $_product->get_regular_price() - $value['discount_price'];

                    echo '<div class="radio-button">
                              <div class="radio-button_inner">
                                <input class="wholesales-price-type" type="radio" id="option1" name="price_discount" value="'.$discountPrice.'">

                                <label class="radio-button_label" for=""> <h4>'.$value['label'].'</h4>Ordenes entre '.
                                    money_format('%.0n',$value['min_price']).' y '.money_format('%.0n',$value['max_price']).
                                    ' : '.str_replace(',','.',money_format('%.0n',$discountPrice)).
                                ' C/U (APLICA MINIMO CON '.(isset($value['qty']) ? $value['qty'] : 1).' UNIDADES)</label>
                              </div>
                        </div>';

                }

                echo '</div>';

                echo '<div class="customer-saving"><strong>TU GANANCIA SERÁ: </strong><br><span id="utility">$0</span></div><br>';

            }else{

                $minimunAmount = get_option( 'wholesales_required_amount', 1 );

                $suggestedPrice = get_post_meta( $id ,'_suggested_price', 1 );

                echo '<strong>¡IMPORTANTE!</strong>';

                echo '<p> NO OLVIDES QUE EL TOTAL DE TUS COMPRAS DEBE SER DE MINIMO <strong>'.str_replace(',','.',money_format('%.0n',$minimunAmount)).'</strong> PARA QUE LOS DESCUENTOS SE APLIQUEN</p>';
                echo '<div class="">';

                if ($suggestedPrice != null) {

                    echo '<strong>PRECIO SUGERIDO DE VENTA</strong><br>'.str_replace(',','.',money_format('%.0n',$suggestedPrice)).' x Unidad<br><br>';
                }


                echo '</div>';

                echo '<table style="width:100%" id="wholesales-discounts">
                      <tr>
                        <th>Cantidad</th>
                        <th>Precio</th>
                      </tr>
                      <tbody>';

                foreach ($data as $key => $value) {

                    $discountPrice = $_product->get_price() - $value['discount'];

                    echo '<tr>
                            <td class="discount">'.$value['min_val'].' - '.$value['max_val'].'</td>
                            <td class="discount-price">'. str_replace(',','.',money_format('%.0n',$discountPrice)) .' x Unidad</td>
                        </tr>';
                }


                echo'
                    </tbody>
                    </table>';


                echo '<strong>TU GANANCIA SERÁ: </strong><br><span id="utility">$0</span><br><br>';

            }
        }
    }
}

function hidden_field_before_add_to_cart_button(){

    echo '<input type="hidden" name="product_discount" id="product_discount" value="">';
}

function add_thank_you_wholesale_message() {

    $user = wp_get_current_user();

    if (in_array('wholesaler', (array) $user->roles)) {


        $text = get_option( 'wholesales_success_message', 1 );

        $content = '';

        $content .= '<h3>¡IMPORTANTE!</h3>';

        $content .= '<p style="text-align: justify;text-transform: uppercase;">'.$text.'</p>';

        if ($text != '') {

            echo $content;
        }
    }
}

function remove_filters_change_quantity(){
    global $wp_filter;
    $user = wp_get_current_user();

    if (in_array('wholesaler', (array) $user->roles)) {

        unset( $wp_filter["woocommerce_cart_item_name"]);
    }
}

function add_custom_checkout_hidden_field( $checkout ) {

    global $woocommerce;

    $items = $woocommerce->cart->get_cart();

    foreach($items as $item => $values) {

        if (isset($values['wholesales_price_discount'])) {

            echo '<div id="user_link_hidden_checkout_field">
                    <input type="hidden" class="input-hidden" name="wholesales" id="wholesales" value="1">
            </div>';

            break;
        }
    }


}

function save_custom_checkout_hidden_field( $order_id ) {

    if ( ! empty( $_POST['wholesales'] ) ) {

        update_post_meta( $order_id, '_is_wholesales_order', 1 );

    }
}

function display_voucher_in_customer_order($order){


    $order_id = method_exists( $order, 'get_id' ) ? $order->get_id() : $order->id;

    $payment_method = $order->get_payment_method();

    if ($payment_method == 'wholesales') {
        echo '
            <h2>Envia tu comprobante de pago aqui:</h2><br>
            <form action="' . $_SERVER['REQUEST_URI'] . '" method="post" enctype="multipart/form-data">
                <input type="hidden" name="action" value="send_wholesales_voucher">
                <input type="hidden" name="order_id" value="'.$order_id.'">
                <input type="file" name="fileToUpload" id="fileToUpload" accept=".jpg,.png,.pdf"><br>
                <input type="submit" value="Enviar comprobante" name="submit">
            </form>
        ';
    }

}


function send_wholesales_payment_voucher(){

    if ($_REQUEST['action']=='send_wholesales_voucher') {

        if ($_FILES['fileToUpload']['size'] <= 5000000 && isset($_POST['order_id'])) {

            $order_id = $_POST['order_id'];

            update_post_meta( $_POST['order_id'], '_has_wholesales_payment_support', 1);

            $voucher_name = time() . $_FILES['fileToUpload']['name'];

            $upload = wp_upload_bits($voucher_name , null, file_get_contents($_FILES['fileToUpload']['tmp_name']));

            if (isset($upload['file'])) {

                $to = get_option( 'wholesales_email' );

                $message = 'Link de la orden:';

                $url = admin_url("/post.php?post=".$order_id.'&action=edit');

                $message .= '<a  href="'.$url.'">'.$order_id.'</a>';

                $subject = 'MAYORISTAS: Comprobante de pago para la orden - '.$order_id;

                if(send_custom_email($to,$subject,$message,'Comprobante de pago', array($upload['file']))){

                    unlink($upload['file']);

                    wc_add_notice('Se envio el comprobante exitosamente.');
                }

            }

        }else{

            wc_add_notice('El archivo debe pesar menos de 5MB.', 'error');
        }

    }

}

function add_shipping_message_wholesales(){

    $user = wp_get_current_user();

    if (in_array('wholesaler', (array) $user->roles)) {

        setlocale(LC_MONETARY, 'en_US');

        echo __('<br><strong>IMPORTANTE</strong><br>El costo del envío será cobrado al recibir su pedido.<br><br>');

        $minimunAmount = get_option( 'wholesales_required_amount', 1 );

//            echo '<p> NO OLVIDES QUE EL TOTAL DE TUS COMPRAS DEBE SER DE MINIMO <strong>'.str_replace(',','.',money_format('%.0n',$minimunAmount)).'</strong> PARA QUE LOS DESCUENTOS SE APLIQUEN</p>';
    }


}


function woocommerce_wholesaler_template($template, $template_name, $template_path)
{

    $user = wp_get_current_user();

    /*if(in_array( 'wholesaler', (array) $user->roles)){

    $template = plugin_dir_path( __FILE__ ) . 'includes/template/form-checkout-whoesaler.php';
    echo $template = plugin_dir_path( __FILE__ ) . 'includes/template/form-checkout-whoesaler.php';
    }  */

    remove_action('woocommerce_before_checkout_form', 'woocommerce_checkout_coupon_form', 10);

    add_filter('woocommerce_enable_order_notes_field', '__return_false');

    //add_filter('woocommerce_checkout_fields', 'unset_unwanted_checkout_fields');

    add_filter('woocommerce_available_payment_gateways', 'disable_payment_methods');

    return $template;

}

function disable_payment_methods($available_gateways)
{

    global $woocommerce;

    $user = wp_get_current_user();

    if (in_array('wholesaler', (array) $user->roles)) {

        $bank_transfer = get_option( 'wholesales_bank_transfer_payment', 1 );

        $credit_cart = get_option( 'wholesales_credit_cart_payment', 1 );

        $cash = get_option( 'wholesales_cash_payment', 1 );

        if ($bank_transfer == 'no') {

            unset($available_gateways['payu_trasferencia_bancaria']);

        }

        if ($credit_cart == 'no') {

            unset($available_gateways['payu_tc']);

        }

        if ($cash == 'no') {

            unset($available_gateways['payu_efectivo']);
        }

        if ($bank_transfer == 'no' && $credit_cart == 'no' &&  $cash == 'no') {

            wp_enqueue_script('wholesale_script', plugins_url('wholesale.js', '/dynamic-price-and-discounts-for-woocommerce/assets/js/wholesale.js'), array(
                'jquery'
            ));
            wp_enqueue_style('wholesale-style', plugins_url('checkout.css', '/dynamic-price-and-discounts-for-woocommerce/assets/css/checkout.css'));
        }


    } else {

        unset($available_gateways['wholesales']);

    }


    return $available_gateways;

}

function unset_unwanted_checkout_fields($fields)
    {

        $user = wp_get_current_user();

        if (in_array('wholesaler', (array) $user->roles)) {

            // list of the billing field keys to remove
            /*$billing_keys = array(
            'billing_company',
            'billing_phone',
            'billing_address_1',
            'billing_address_2',
            'billing_city',
            'billing_postcode',
            'billing_country',
            'billing_state',
            );

            foreach( $billing_keys as $key ) {
            unset( $fields['billing'][$key] );
            }*/


        }

        return $fields;
    }

function complete_registration()
{
    global $reg_errors, $username, $password, $email, $website, $first_name, $last_name, $nickname, $bio;
    if (1 > count($reg_errors->get_error_messages())) {
        $userdata = array(
            'user_login' => $username,
            'user_email' => $email,
            'user_pass' => $password,
            'user_url' => $website,
            'first_name' => $first_name,
            'last_name' => $last_name,
            'nickname' => $nickname,
            'description' => $bio
        );
        $user     = wp_insert_user($userdata);
        echo 'Registration complete. Goto <a href="' . get_site_url() . '/wp-login.php">login page</a>.';
    }
}

function write_here_show_success_messages($success_msg){
    echo '<div class="form-success">';
    echo '<span>EXITO</span><br/>';
    echo '</div>';
}

function download_masive_products_excel(){

    global $wp;

    if ($wp->request == 'generate_wholesales_excel') {

        $products = get_product_phoen_discount();

        $titles = get_titles_from_products($products);

        $endpoint_vars = $wp->query_vars;

        $columns = count($titles['prices']);

        $initialColumnPosition = 10;

        $initialDataPosition = 1 + $initialColumnPosition;

        $initialColumnsDiscounts = 'D';

        $abc = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z');

        $finalColumnsDiscountsIndex = array_search($initialColumnsDiscounts, $abc) + $columns;

        $finalColumnsDiscounts = $abc[$finalColumnsDiscountsIndex];

        $finalColumnsCalculateDiscountsIndex = array_search($finalColumnsDiscounts, $abc) + $columns;

        $finalColumnsCalculateDiscounts = $abc[$finalColumnsCalculateDiscountsIndex - 1];

        $tableStyle = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK,
                    'color' => ['rgb' => 'C0C0C0'],
                ],
            ],
        ];

        $spread = new Spreadsheet();

        $sheet = $spread->getActiveSheet()->mergeCells("A1:C8");
        $sheet->setTitle("Hoja 1");

        #LOGO
        $drawing = new Drawing();
        $drawing->setName('Logo');
        $drawing->setDescription('Logo');
        $drawing->setCoordinates('A1');
        $drawing->setPath(dirname(__FILE__).'/images/logo-holy.jpg');
        //$drawing->setHeight(130);
        $drawing->setWidth(350);
        $drawing->setOffsetX(30); //pixels
        $drawing->setOffsetY(10); //pixels
        $drawing->setWorksheet($spread->getActiveSheet());

        #LABELS
        $sheet->setCellValue('D3', 'NOMBRE O RAZON SOCIAL');
        $sheet->setCellValue('D4', 'CEDULA O NIT');
        $sheet->setCellValue('D5', 'DIRECCION');
        $sheet->setCellValue('D6', 'CIUDAD');
        $sheet->setCellValue('D7', 'TELEFONO');
        $sheet->setCellValue('D8', 'CORREO ELECTRONICO');
        #TABLE TITLES
        $sheet->setCellValue('A'.$initialColumnPosition, 'PRODUCTO');
        $sheet->setCellValue('B'.$initialColumnPosition, 'IMAGEN');
        $sheet->setCellValue('C'.$initialColumnPosition, 'CANTIDAD');
        $spread->getActiveSheet()->getColumnDimension('A')->setWidth(20);
        $spread->getActiveSheet()->getColumnDimension('B')->setWidth(20);
        $spread->getActiveSheet()->getColumnDimension('C')->setWidth(20);

        $fieldIndex = 0;
        #DISCOUNT INFORMATIVE FIELDS

        for ($i = $initialColumnsDiscounts; $i < $finalColumnsDiscounts; $i++) {

            $sheet->setCellValue($i.$initialColumnPosition, $titles['prices'][$fieldIndex]);

            $spread->getActiveSheet()->getColumnDimension($i)->setWidth(20);

            $fieldIndex++;

        }

        $fieldIndex = 0;
        #DISCOUNT CALCULATE FIELDS
        for ($j = $i; $j <= $finalColumnsCalculateDiscounts; $j++) {

            $sheet->setCellValue($j.$initialColumnPosition, $titles['calculate'][$fieldIndex]);

            $spread->getActiveSheet()->getColumnDimension($j)->setWidth(20);

            $fieldIndex++;

        }

        $spread->getActiveSheet()->setAutoFilter("A$initialColumnPosition:$finalColumnsCalculateDiscounts$initialColumnPosition");

        $spread->getActiveSheet()->getStyle("A$initialColumnPosition:$j$initialColumnPosition")->getFont()->setBold( true );

        for ($i = 0; $i < count($products); $i++) { //rows

            $sheet->setCellValue('A'.($i+$initialDataPosition), $products[$i]['name']);
           
            if(file_exists( $products[$i]['image'])){
                ${'drawing'.$i} = new Drawing();
                ${'drawing'.$i}->setName('');
                ${'drawing'.$i}->setDescription('');
                ${'drawing'.$i}->setCoordinates('B'.($i+$initialDataPosition));
                ${'drawing'.$i}->setPath($products[$i]['image']);
                ${'drawing'.$i}->setHeight(70);
                ${'drawing'.$i}->setResizeProportional(true);
                ${'drawing'.$i}->setHeight(70);
                ${'drawing'.$i}->setOffsetX(30); //pixels
                ${'drawing'.$i}->setOffsetY(15); //pixels
                ${'drawing'.$i}->setWorksheet($spread->getActiveSheet());

            }

            //$sheet->setCellValue('C'.($i+$initialDataPosition), $products[$i]['price']);

            $discountQty = count($products[$i]['discounts']);
            #PRICE COLUMNS AND CALCULATE COLUMNS
            for ($k = 0; $k < $discountQty ; $k++) {

                $letter = $abc[$k + 3];

                $calculateLetter = $abc[$k + 3 + $discountQty];

                $sheet->setCellValue($letter.($i+$initialDataPosition), $products[$i]['discounts'][$k]['discount_price']);

                $row = $i+$initialDataPosition;

                $sheet->setCellValue($calculateLetter.($i+$initialDataPosition), '='.$letter.$row.'*C'.$row);

            }



            $spread->getActiveSheet()->getRowDimension($i+$initialDataPosition)->setRowHeight(70);

        }


        $totalsRows = $i+$initialDataPosition;

        $totalRowsCalculate = (int) $totalsRows - 1;

        for ($j = $finalColumnsDiscounts; $j <= $finalColumnsCalculateDiscounts; $j++) {

            $sheet->setCellValue($j.$totalsRows, "=SUM($j$initialDataPosition:$j$totalRowsCalculate)");

            $spread->getActiveSheet()->getStyle($j.$totalsRows)->getFont()->setSize(16)->setBold( true );


            $spread->getActiveSheet()->getStyle($j.$totalsRows)->applyFromArray(
                [
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'color' => ['rgb' => "C0C0C0"],
                    ],
                ]
            );


        }

        $totalLabelColumn = $abc[$finalColumnsCalculateDiscountsIndex];

        $sheet->setCellValue("$totalLabelColumn$totalsRows", 'TOTAL');


        //STYLING
         #TOTAL LABEL
        $spread->getActiveSheet()->getStyle("$totalLabelColumn$totalsRows")->applyFromArray($tableStyle);

        $spread->getActiveSheet()->getStyle("$totalLabelColumn$totalsRows")->applyFromArray(
            [
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'color' => ['rgb' => "C0C0C0"],
                ],
            ]
        );

        $spread->getActiveSheet()->getStyle("$totalLabelColumn$totalsRows")->getAlignment()->setWrapText(true);

        $spread->getActiveSheet()->getStyle("$totalLabelColumn$totalsRows")->getFont()->setSize(16)->setBold( true );

        $spread->getActiveSheet()->getStyle('D3')->getFont()->setSize(12)->setBold( true );
    $spread->getActiveSheet()->getStyle('D4')->getFont()->setSize(12)->setBold( true );
    $spread->getActiveSheet()->getStyle('D5')->getFont()->setSize(12)->setBold( true );
    $spread->getActiveSheet()->getStyle('D6')->getFont()->setSize(12)->setBold( true );
    $spread->getActiveSheet()->getStyle('D7')->getFont()->setSize(12)->setBold( true );
    $spread->getActiveSheet()->getStyle('D8')->getFont()->setSize(12)->setBold( true );


         #GENERAL
        $spread->getActiveSheet()->getStyle('A1:'.$finalColumnsCalculateDiscounts.$totalRowsCalculate)->getAlignment()->setWrapText(true);

        $spread->getActiveSheet()->getStyle("A$initialColumnPosition:$finalColumnsCalculateDiscounts$totalsRows")->applyFromArray($tableStyle);

        $spread->getActiveSheet()->getStyle("A$initialColumnPosition:$finalColumnsCalculateDiscounts$initialColumnPosition")->applyFromArray(
            [
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'color' => ['rgb' => "C0C0C0"],
                ],
            ]
        );

        $spread->getActiveSheet()->getStyle('A1:Z'.$totalRowsCalculate)->getAlignment()->setHorizontal('center');

        $finalMoneyFotmatColumn = $abc[$finalColumnsCalculateDiscountsIndex - 1];

        $spread->getActiveSheet()
        ->getStyle("D$initialColumnPosition:$finalMoneyFotmatColumn$totalsRows")
        ->getNumberFormat()
        ->setFormatCode(PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_CURRENCY_USD_SIMPLE);
        /*$writer = new Xlsx($spread);


        // Redirect output to a client’s web browser (Xlsx)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="mayoristas.xlsx"');
        header('Cache-Control: max-age=0');
        // If you're serving to IE 9, then the following may be needed
        header('Cache-Control: max-age=1');

        // If you're serving to IE over SSL, then the following may be needed
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
        header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
        header('Pragma: public'); // HTTP/1.0

        $writer->save('php://output');exit;*/
        /*header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="EXCEL_PEDIDOS_MAYORISTAS_HOLY_COSMETICS.xls"');
        header('Cache-Control: max-age=0');*/

        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spread, 'Xls');
        $uploads = wp_upload_dir();
		$upload_path = $uploads['basedir'];
        $writer->save($upload_path.'/EXCEL_PEDIDOS_MAYORISTAS_HOLY_COSMETICS.xls');
        echo 1;exit;
        //header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        //$fileName = 'EXCEL_PEDIDOS_MAYORISTAS_HOLY_COSMETICS.xls';
        //header('Content-Disposition: attachment; filename="'. urlencode($fileName).'"');
        //$writer->save('php://output');exit;

    }
}

function save_selected_discount_price( $cart_item_data, $product_id ) {

    if( isset( $_POST['product_discount'] ) && $_POST['product_discount'] != 0 ) {
        $cart_item_data[ "wholesales_price_discount" ] = $_POST['product_discount'];
    }
    return $cart_item_data;

}