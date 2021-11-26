<?php
/**
 * Register a custom menu page.
 */
function woo_import_wholesales_menu() {

    add_menu_page(
        'Wholesales masive',
        'Wholesales masive',
        'manage_options',
        __DIR__.'/wholesales-import-do.php'
    );
}

add_action( 'admin_menu', 'woo_import_wholesales_menu' );

add_action( 'woocommerce_product_options_general_product_data', 'add_suggested_price_fields' );

add_action( 'woocommerce_process_product_meta', 'add_suggested_price_save' );

function add_suggested_price_fields() {

        global $woocommerce, $post;

        $current_user = wp_get_current_user();
            
        $allowed_roles = array('administrator');

        if( array_intersect($allowed_roles, $current_user->roles ) ) {  

            echo '<div class="options_group">';
        
            woocommerce_wp_text_input( 
                array( 
                    'id'          => '_suggested_price', 
                    'label'       => __( 'Precio sugerido', 'woocommerce' ), 
                    'placeholder' => '$',
                    'desc_tip'    => 'true',
                    'description' => __( 'Ingrese el precio sugerido.', 'woocommerce' ) 
                )
            );
            
            echo '</div>';
        }
          
    }

function add_suggested_price_save($post_id){

    $real_price = $_POST['_suggested_price'];
    
    if( !empty( $real_price ) )
        
        update_post_meta( $post_id, '_suggested_price', esc_attr( $real_price ) );
    
} 
