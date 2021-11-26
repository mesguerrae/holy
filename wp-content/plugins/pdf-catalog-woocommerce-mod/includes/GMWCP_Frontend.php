<?php
/**
 * This class is loaded on the back-end since its main job is 
 * to display the Admin to box.
 */

class GMWCP_Frontend {
	
	public function __construct () {
		$gmwcp_enable_single_product = get_option( 'gmwcp_enable_single_product' );
		$gmwcp_single_display_location = get_option( 'gmwcp_single_display_location' );
		if($gmwcp_enable_single_product == 'yes'){
			if($gmwcp_single_display_location == 'before'){
				add_action( 'woocommerce_product_meta_start', array( $this, 'woo_comman_single_button' ), 10, 0 ); 
			}
			if($gmwcp_single_display_location == 'after'){
				add_action( 'woocommerce_single_product_summary', array( $this, 'woo_comman_single_button' ), 15 );
			}
			 
		}
		$gmwcp_shop_enable_product = get_option( 'gmwcp_shop_enable_product' );
		$gmwcp_shop_display_location = get_option( 'gmwcp_shop_display_location' );
		if($gmwcp_shop_enable_product == 'yes'){
			if($gmwcp_shop_display_location == 'before'){
				add_action( 'woocommerce_before_shop_loop', array( $this, 'woo_comman_shop_button' ), 10, 2 ); 
			}
			if($gmwcp_shop_display_location == 'after'){
				add_action( 'woocommerce_after_shop_loop', array( $this, 'woo_comman_shop_button' ), 10, 2 ); 
			}
		}
	}

	
	function woo_comman_single_button(){
		global $post;
	
		$url_custom = get_home_url().'?action=catelog_single&id='.$post->ID;
		?>
		<div class="gmwcp_button">
			<a href="<?php echo $url_custom;?>" class="button"><?php _e('Download Catalog', 'gmwcp'); ?></a>
		</div>
		<?php
	}

	function woo_comman_shop_button(){
		if(is_product_category()){
			global $wp_query;
			$term_id = $wp_query->get_queried_object()->term_id;
			$url_custom = get_home_url().'?action=catelog_shop&id='.$term_id;
			$label = 'Download Catalog';
		}else{
			$url_custom = get_home_url().'?action=catelog_shop&id=full';
			$label = 'Download Full Catalog';
		}
		
		?>
		<div class="gmwcp_button">
			<a href="<?php echo $url_custom;?>" class="button"><?php _e($label, 'gmwcp'); ?></a>
		</div>
		<?php
	}

}


