<?php

use Servientrega\WebService;

class Shipping_Servientrega_WC extends WC_Shipping_Method_Shipping_Servientrega_WC
{
    public $servientrega;

    public function __construct($instance_id = 0)
    {
        parent::__construct($instance_id);
        $this->servientrega = new WebService($this->user, $this->password, $this->billing_code, $this->id_client, get_bloginfo('name'));
    }

    public function generate_guide($order_id, $old_status, $new_status, $order)
    {
        $instance = new self();

        //if( !$order->has_shipping_method($instance->id)) return;

        $guide_servientrega = get_post_meta($order_id, 'guide_servientrega', true);

        if (empty($guide_servientrega) &&  $new_status === 'processing'){

            $guide = $instance->guide($order);

            if ($guide == new stdClass())
                return false;

            if (!$guide->CargueMasivoExternoResult) return;

            $guide_number = $guide->envios->CargueMasivoExternoDTO->objEnvios->EnviosExterno->Num_Guia;

            if ($guide_number == '') {
                
                return false;
            }

            $cod_facturacion = $guide->envios->CargueMasivoExternoDTO->objEnvios->EnviosExterno->Ide_CodFacturacion;

            $id_archivo = $guide->envios->CargueMasivoExternoDTO->objEnvios->EnviosExterno->Id_ArchivoCargar;

            update_post_meta($order_id, 'guide_servientrega', $guide_number);

            update_post_meta($order_id, 'guide_servientrega_codfacturacion', $cod_facturacion);

            update_post_meta($order_id, 'guide_servientrega_response',json_encode ($guide));

	        update_post_meta($order_id, 'guide_servientrega_idarchivo', $id_archivo);

            $sticker = $instance->sticker($order);

            if (!$sticker) {
                
                return false;
            }

	        update_post_meta($order_id, 'sticker_servientrega_response',$sticker);

            $url_guide = get_option( 'url_cdn', 1 ).'/wp-content/uploads/woocommerce-shipping-servientrega/attachments/guide-'.$order_id.'.pdf';

            $guide_url = sprintf( __( 'Servientrega Código de seguimiento <a target="_blank" href="%1$s">' . $guide_number .'</a>. Guia: <a target="_blank" href="'.$url_guide.'">PDF</a>' ), "https://www.servientrega.com/wps/portal/Colombia/transacciones-personas/rastreo-envios/detalle?id=$guide_number" );

            $order->add_order_note($guide_url);

        }

    }

    public function sticker($order)
    {

        $order_id = $order->get_id();

        $guide_number = get_post_meta($order_id, 'guide_servientrega', true);
        
        $cod_facturacion = get_post_meta($order_id, 'guide_servientrega_codfacturacion', true);
        
        $id_archivo = get_post_meta($order_id, 'guide_servientrega_idarchivo', true);

        $params = [
            'num_Guia' => $guide_number,
            'num_GuiaFinal' => $guide_number,
            'sFormatoImpresionGuia' => 1,
            'Id_ArchivoCargar' => $id_archivo,
            'interno' => false,
            'bytesReport' => ''

            
        ];
        
        update_post_meta($order_id, 'guide_servientrega_request', json_encode($params));
 
        $resp = new stdClass;

        try{

            $wp_upload_dir = wp_upload_dir();

            if (!file_exists($wp_upload_dir['basedir'] . '/woocommerce-shipping-servientrega/attachments/')) {
                mkdir($wp_upload_dir['basedir'] . '/woocommerce-shipping-servientrega/attachments/', 0777, true);
            }

            //var_dump($this->servientrega->GenerarGuiaSticker($params));exit;
            
            $resp = $this->servientrega->GenerarGuiaSticker($params);
		     
            

            update_post_meta($order_id, 'guide_servientrega_response_status_response1', ((String)$resp->GenerarGuiaStickerResult));
            
            if($resp->GenerarGuiaStickerResult != 1){
                
                update_post_meta($order_id, 'guide_servientrega_response_status', 'PRIMER INTENTO FALLO');
                
                $resp = $this->servientrega->GenerarGuiaSticker($params);
		          
                update_post_meta($order_id, 'guide_servientrega_response_status_response2', ((String)$resp->GenerarGuiaStickerResult));
                
                if($resp->GenerarGuiaStickerResult != 1){
                        
                    update_post_meta($order_id, 'guide_servientrega_response_status', 'SEGUNDO INTENTO FALLO');

                    return false;

                }else{
                        
                    update_post_meta($order_id, 'guide_servientrega_response_status', 'SEGUNDO INTENTO EXITO');
                }

            }else{
                update_post_meta($order_id, 'guide_servientrega_response_status', 'PRIMER INTENTO EXITO');
            }
            shipping_servientrega_wc_ss()->log($resp);
            
            $pdf_decoded = $resp->bytesReport;
            
            $pdf = fopen ($wp_upload_dir['basedir'] . '/woocommerce-shipping-servientrega/attachments/guide-'.$order_id.'.pdf','w');
            
            fwrite ($pdf,$pdf_decoded);
            
            fclose ($pdf);

            return true;

        }catch (\Exception $exception){
            shipping_servientrega_wc_ss()->log($exception->getMessage());
        }


    }

    public function guide($order)
    {

        $instance = new self();

        $nombre_destinatario = $order->get_shipping_first_name() ? $order->get_shipping_first_name() .
            " " . $order->get_shipping_last_name() : $order->get_billing_first_name() .
            " " . $order->get_billing_last_name();
        $direccion_destinatario = $order->get_shipping_address_1() ? $order->get_shipping_address_1() .
            " " . $order->get_shipping_address_2() : $order->get_billing_address_1() .
            " " . $order->get_billing_address_2();
        $state_code = $order->get_shipping_state() ? $order->get_shipping_state() : $order->get_billing_state();
        $country_code = $order->get_shipping_country() ? $order->get_shipping_country() :  $order->get_billing_country();
        $state_name = self::name_destination($country_code, $state_code);
        $city = $order->get_shipping_city() ? $order->get_shipping_city() : $order->get_billing_city();
        $items = $order->get_items();
        $data_products = self::dimensions_weight($items, true);
        $namesProducts = implode(",",  $data_products['name_products']);
        
        $valor_declarado = ((int)$order->get_total() > 100000) ? $order->get_total() : 30000;

        $params = [
            'Num_Guia' => 0,
            'Num_Sobreporte' => 0,
            'Num_Piezas' => 1,//$this->get_quantity_product($items),
            'Des_TipoTrayecto' => 1, //nacional 2 internacional
            'Ide_Producto' => 2, //mercancia premier
            'Ide_Destinatarios' => '00000000-0000-0000-0000-000000000000',
            'Ide_Manifiesto' => '00000000-0000-0000-0000-000000000000',
            'Des_FormaPago' => 2,//$instance->way_pay, // 2 Crédito 4 contra entrega
            'Des_MedioTransporte' => 1, // terrestre
            'Num_PesoTotal' => 3,//$data_products['weight'],
            'Num_ValorDeclaradoTotal' => $valor_declarado,//$data_products['total_valorization'],
            'Num_VolumenTotal' => 0, // para que se calcule
            'Num_BolsaSeguridad' => 0, //solo para valores, de lo contrario 0
            'Num_Precinto' => 0,
            'Des_TipoDuracionTrayecto' => 1, //1 normal
            'Des_Telefono' => $order->get_billing_phone(),
            'Des_Ciudad' => $city,
            'Des_Direccion' => $direccion_destinatario,
            'Nom_Contacto' => $nombre_destinatario,
            'Num_ValorLiquidado' => 0, //calculado por el sistem 0 para todos los casos
            'Des_DiceContener' => 'COSMETICOS',//$namesProducts, // el contenido del envío
            'Des_TipoGuia' => 1,
            'Num_VlrSobreflete' => 0,
            'Num_VlrFlete' => 0,
            'Num_Descuento' => 0,
            'Num_PesoFacturado' => 0,
            'idePaisOrigen' => 1, // 1 Colombia
            'idePaisDestino' => 1, // 1 Colombia
            'Des_IdArchivoOrigen' => 0, // para tos los casos
            'Des_DireccionRemitente' => '',
            'Est_CanalMayorista' => false,
            'Num_IdentiRemitente' => '',
            'Num_TelefonoRemitente' => '',
            'Num_Alto' => 4,//$data_products['high'],
            'Num_Ancho' => 24,//$data_products['width'],
            'Num_Largo' => 30,//$data_products['weight'],
            'Des_DepartamentoDestino' => $state_name,
            'Des_DepartamentoOrigen' => '',
            'Gen_Cajaporte' => 0,
            'Gen_Sobreporte' => 0,
            'Nom_UnidadEmpaque' => 'MERCANCIA PREMIER',
            'Des_UnidadLongitud' => 'cm',
            'Des_UnidadPeso' => 'kg',
            'Num_ValorDeclaradoSobreTotal' => 0,
            'Num_Factura' => $order->get_id(),
            'Des_CorreoElectronico' => $order->get_billing_email(),
            'Num_Recaudo' => 0,
            'Est_EnviarCorreo' => false
        ];

        $resp = new stdClass;

        try{
            $resp = $this->servientrega->CargueMasivoExterno($params);
            //if ($instance->debug === 'yes') 
            shipping_servientrega_wc_ss()->log('----------ORDEN:'.$order->get_id().' --------');
            shipping_servientrega_wc_ss()->log($resp);
        }catch (\Exception $exception){
            shipping_servientrega_wc_ss()->log($exception->getMessage());
        }

        return $resp;
    }

    public static  function name_destination($country, $state_destination)
    {
        $countries_obj = new WC_Countries();
        $country_states_array = $countries_obj->get_states();

        $name_state_destination = '';

        if(!isset($country_states_array[$country][$state_destination]))
            return $name_state_destination;

        $name_state_destination = $country_states_array[$country][$state_destination];
        $name_state_destination = self::clean_string($name_state_destination);
        return self::short_name_location($name_state_destination);
    }

    public static function short_name_location($name_location)
    {
        if ( 'Valle del Cauca' === $name_location )
            $name_location =  'Valle';
        return $name_location;
    }

    public static function clean_string($string)
    {
        $not_permitted = array ("á","é","í","ó","ú","Á","É","Í",
            "Ó","Ú","ñ","À","Ã","Ì","Ò","Ù","Ã™","Ã ","Ã¨","Ã¬",
            "Ã²","Ã¹","ç","Ç","Ã¢","ê","Ã®","Ã´","Ã»","Ã‚","ÃŠ",
            "ÃŽ","Ã”","Ã›","ü","Ã¶","Ã–","Ã¯","Ã¤","«","Ò","Ã",
            "Ã„","Ã‹");
        $permitted = array ("a","e","i","o","u","A","E","I","O",
            "U","n","N","A","E","I","O","U","a","e","i","o","u",
            "c","C","a","e","i","o","u","A","E","I","O","U","u",
            "o","O","i","a","e","U","I","A","E");
        $text = str_replace($not_permitted, $permitted, $string);
        return $text;
    }

    public function get_quantity_product($items)
    {
        $item_quantity = 0;

        foreach ($items as $item_id => $item_data)

            $item_quantity += $item_data->get_quantity();

        return $item_quantity;
    }

    public static function getDataShipping($id_ciudad_destino)
    {
        global $wpdb;
        $table_name = $wpdb->prefix . 'shipping_servientrega_matriz';

        $result = array();

        if ( $wpdb->get_var( "SHOW TABLES LIKE '$table_name'" ) !== $table_name )
            return $result;

        $query = "SELECT * FROM $table_name WHERE id_ciudad_destino='$id_ciudad_destino'";
        $result = $wpdb->get_row( $query, ARRAY_A );

        return $result;
    }


    public static function dimensions_weight($items, $guide = false)
    {
        $data['total_valorization'] = 0;
        $data['high'] = 0;
        $data['length'] = 0;
        $data['width'] = 0;
        $data['weight'] = 0;
        $data['name_products'] = [];

        foreach ( $items as $item => $values ) {
            $_product_id = $guide ? $values['product_id'] : $values['data']->get_id();
            $_product = wc_get_product( $_product_id );

            if ( !$_product->get_weight() || !$_product->get_length()
                || !$_product->get_width() || !$_product->get_height() )
                break;

            $data['name_products'][] = $_product->get_name();

            $custom_price_product = get_post_meta($_product_id, '_shipping_custom_price_product_smp', true);
            $data['total_valorization'] += $custom_price_product ? $custom_price_product : $_product->get_price();

            $quantity = $values['quantity'];
            $data['total_valorization'] = $data['total_valorization'] * $quantity;

            $data['high'] += $quantity > 1 ? $_product->get_height() * $quantity : $_product->get_height();
            $data['length'] += (int)$_product->get_length();
            $data['width'] += (int)$_product->get_width();
            $data['weight'] += $quantity > 1 ? $_product->get_weight() * $quantity : $_product->get_weight();

        }

        return $data;
    }
}
