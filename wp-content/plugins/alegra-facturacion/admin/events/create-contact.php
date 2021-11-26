<?php 
namespace admin\events;
use \WP_Queue\Job;
use includes\Api;


class CreateContact extends \WP_Queue\Job
{
    protected $user_id;
    protected $api;

    public function __construct( $user_id )
    {
        $this->user_id = $user_id;
        $this->api = new Api();
    }
    public function handle() {

        if (!$this->user_id) {
            return;
        }

        $user_id = $this->user_id;

        $currentAlegraId = get_user_meta( $user_id, 'alegra_contact_id', true );

        if($currentAlegraId != ''){
            return;
        }
   

        $user = get_user_by( 'id', $user_id );

        $contactData = array();
        $contactData["id"]                  =  $user_id;
        $contactData["name"]                =  $user->display_name;
        //$contactData["identification"]      =  "";
        $contactData["email"]               =  $user->user_email;
        $contactData["phonePrimary"]        =  get_user_meta( $user_id,"billing_phone", true);
        $contactData["type"]                = array("client");
        $contactData["address"]             = array(

            "address"   => get_user_meta( $user_id, 'shipping_address_1', true ),
            "city"      => get_user_meta( $user_id, 'shipping_city', true )

        );
        
        $order = wc_get_customer_last_order($user_id);
        
        if($order){
        	
        	$id_type = array('CC - Cédula de ciudadanía' => 'CC', 'T.I Tarjeta de identidad' => 'TI', 'NIT' => 'NIT', 'PP - Pasaporte' => 'PP', 'CE- Cédula de extranjeria' => 'CE');
        	



                $newIdType = get_post_meta($order->get_id(), 'billing_identification_type_', true);

                $newIdNumber = get_post_meta($order->get_id(), 'billing_identification_number_', true);

                $oldIdType = get_post_meta($order->get_id(), 'identification_type_', true);

                $oldIdNumber = get_post_meta($order->get_id(), 'identification_number_', true);
                 
        	$idType = ($newIdType == '') ? $oldIdType : $newIdType;
        	
        	$idNumber = ($newIdNumber == '') ? $oldIdNumber : $newIdNumber;
        	
        	$identity = array(
        		'type' => $id_type[$idType],
        		'number' => str_replace('.','', $idNumber),
        	);
        	
        	$contactData["identificationObject"]      =  $identity;
        }

        alegra_log('----------send data------------','contact');
        alegra_log($contactData, 'contact');
        $result = $this->api->post('contacts',$contactData);
        alegra_log('----------response data------------','contact');
        alegra_log($result, 'contact');
        $resultjson = json_decode($result);

        if(isset($resultjson->id)){

            add_user_meta( $user_id, 'alegra_contact_id', $resultjson->id);
        }

        return $resultjson->id;

    }
}   