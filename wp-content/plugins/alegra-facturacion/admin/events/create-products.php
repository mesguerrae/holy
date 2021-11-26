<?php 
namespace admin\events;
use \WP_Queue\Job;
use includes\Api;


class CreateProductOnAlegra extends \WP_Queue\Job
{
    protected $postId;
    protected $api;

    public function __construct( $postId )
    {
        $this->postId = $postId;
        $this->api = new Api();
    }
    public function handle() {

        $post = get_post( $this->postId );
        if( in_array( get_post_type($post), [ 'product' ])){

            //$logger = new WC_Logger();            
            $uri = 'items';
            //$logger->add('alegra-createproduct', 'ID:'.$this->postId);
            //add_post_meta($this->postId,'entre_cola1', 'entre_cola1');
            $product = wc_get_product($this->postId);
            #verifica si el producto posee variaciones
            $uri = 'items';
            #verifica si el producto posee variaciones
            if ($product->is_type( 'variable' )) {
                                    
                $available_variations = $product->get_available_variations();
                #se recorren las variaciones

                foreach ($available_variations as $key => $variation) {
                    //var_dump($variation);exit;
                    if(get_post_meta($variation['variation_id'], 'alegra_item_id', true) != ''){
                        alegra_log('Producto con variacion:'.$variation['variation_id'].' ya existe.' ,'products');
                        continue;
                    }
                    $productData['id']          =   $variation['variation_id'];
                    $productData['name']        =   $product->get_name();
                    $productData['description'] = $product->get_description();
                    $productData['reference']   = get_variation_data_from_variation_id($variation['variation_id']) .' - '.$variation['variation_id'];
                    $productData['tax']         = array(array('id' => 1));
                    $productData['category']    = [];
                    $productData['price']       = array(array('price' => $variation['display_price']));
                    $productData['inventory']  =   array(
                        'unit'              => 'unit',
                        'unitCost'          =>  $variation['display_price'],
                        'initialQuantity'       =>  (is_null($variation['max_qty']) ? 50 : $variation['max_qty']),
                        //'maxQuantity'       =>  (is_null($variation['max_qty']) ? 50 : $variation['max_qty'])
                    );
                    $productData['customFields'] = array(array('Cantidad reservada' => 0));
                    alegra_log('----------send data------------','products');
                    alegra_log($productData,'products');
                    $productJson = json_encode($productData);
                    #se crea el producto en alegra  
                    $result = $this->api->post($uri, $productData);
                    alegra_log('----------response data------------','products');
                    alegra_log($result,'products');
                    //$logger->add('alegra-createproduct', 'ID:'.$result);
                    $decodedResult =  json_decode($result);
                    
                    #se verifica si el api retorna bien la data
                    if (isset($decodedResult->id)) {
                        #se guarda en el meta del producto el id de alegra
                        update_post_meta($variation['variation_id'],'alegra_item_id', $decodedResult->id);
                    }
                }
                                
            }else{
                if(get_post_meta($product->get_id(), 'alegra_item_id', true) != ''){
                        alegra_log('Producto con id:'.$product->get_id().' ya existe.' ,'products');
                        return;
                }
                $productData['id']          =   $product->get_id();
                $productData['name']        =   $product->get_name();
                $productData['description'] =   $product->get_description();
                $productData['reference']   =   $product->get_name().' - '.$product->get_id();
                $productData['tax']         = array(array('id' => 1));
                $productData['category']    = [];
                $productData['price']       = array(array('price' => $product->get_price()));
                $productData['inventory']  =   array(
                        'unit'              => 'unit',
                        'unitCost'          =>  $product->get_price(),
                        'initialQuantity'   =>  (is_null($product->get_stock_quantity()) ? 50 :  $product->get_stock_quantity()),
                        //'maxQuantity'       =>  (is_null($product->get_stock_quantity()) ? 50 :  $product->get_stock_quantity())
                    );
                $productData['customFields'] = array(array('Cantidad reservada' => 0));
                alegra_log('----------send data------------','products');
                alegra_log($productData,'products');
                #se crea el producto en alegra  
                $result = $this->api->post($uri, $productData);
                alegra_log('----------response data------------','products');
                alegra_log($result,'products');
                $decodedResult =  json_decode($result);

                if (isset($decodedResult->id)) {
                    #se guarda en el meta del producto el id de alegra
                    update_post_meta($product->get_id(),'alegra_item_id', $decodedResult->id);
                }
            }

            
        }
        
        //validar si ya tiene id de alegra asociado

        //si no crear el producto

        return $decodedResult->id;
    }
    
}