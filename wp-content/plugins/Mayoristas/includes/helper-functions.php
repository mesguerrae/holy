<?php


function get_product_phoen_discount(){

    $products = array();

    $args = array(
        'post_type' => 'product',
        'posts_per_page' => -1,
        'order' => 'ASC',
    	'orderby' => 'title',
        'meta_query'     => array(
            'relation' => 'AND',
            array(
                'key'     => 'phoen_woocommerce_discount_mode',
                'compare' => 'EXISTS',
            ),
        )
    );
    $query1 = new WP_Query( $args );

    while ( $query1->have_posts() ) {
        
        $query1->the_post();
        
        global $post;

        $product_id = $post->ID;
    
        
        $product = wc_get_product( $product_id );

        $attachment_id = $product->get_image_id();
        $url = wp_get_attachment_image_url( $attachment_id,'thumbnail' );


        $uploads = wp_upload_dir();
        
        $base_path = str_replace('uploads','',$uploads['basedir']);
        
        $url_without_baseurl = strstr($url, 'uploads');
        //$fullsize_path = strstr($base_path.$url_without_baseurl, '?', true) ;

        $fullsize_path = $base_path.$url_without_baseurl;
        //$fullsize_path = wp_get_attachment_image_url( $attachment_id, 'full' );

        $data = get_post_meta($product_id, 'phoen_woocommerce_discount_mode',true);

        $discounts = array();

        if($data == null){
            continue;
        }

		if ($product->is_type( 'simple' )) {

			$regular_price  =  $product->get_regular_price();
		}
		elseif($product->is_type('variable')){

			$regular_price  =  $product->get_variation_regular_price( 'max', true );
		}

        foreach ($data as $key => $value) {

			

            $discounts[] = array(
                'discount_price'    => $regular_price - $value['discount_price'],
                'label'             => $value['label'],
                'min_price'         => money_format('%.0n',$value['min_price']),
                'max_price'         => money_format('%.0n',$value['max_price']),
                'qty'               => $value['qty']
            );

            //$discountPrice = $product->get_price() - $value['discount_price'];
            
        }

        $product = array(
            'name' => $product->get_name(),
            'image' => $fullsize_path,
            //'price' => money_format('%.0n',$product->get_price()),
            'discounts' => $discounts
        );

        $products[] = $product;
    }  

    return $products;
}


function get_titles_from_products($products){

    $titles = array();

    $discounts = $products[0]['discounts'];

    foreach ($discounts as $key => $discount) {
        
        $titles['prices'][] =  $discount['label'].'( $'. number_format($discount['min_price'],0,",",".").' - $'.number_format($discount['max_price'],0,",",".").' ) MIN - '.$discount['qty'];

    }
    foreach ($discounts as $key => $discount) {
        
        $titles['calculate'][] =  'PRECIO TOTAL '.$discount['label'];

    }

    return $titles;

}
