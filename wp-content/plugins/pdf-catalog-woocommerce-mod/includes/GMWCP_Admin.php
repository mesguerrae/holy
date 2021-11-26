<?php
/**
* This class is loaded on the back-end since its main job is
* to display the Admin to box.
*/
class GMWCP_Admin {
	
	public function __construct () {
		add_action( 'admin_init', array( $this, 'GMWCP_register_settings' ) );
		add_action( 'admin_menu', array( $this, 'GMWCP_admin_menu' ) );
		add_action('admin_enqueue_scripts', array( $this, 'GMWCP_admin_script' ));
		if ( is_admin() ) {
			return;
		}
		
	}
	public function GMWCP_admin_script () {
		wp_enqueue_script( 'wp-color-picker' ); 
		wp_enqueue_style('gmwcp_admin_css', GMWCP_PLUGINURL.'css/admin-style.css');
		wp_enqueue_script('gmwcp_admin_js', GMWCP_PLUGINURL.'js/admin-script.js');
	}
	
	public function GMWCP_admin_menu () {
		add_menu_page('Woo Catalog PDF', 'Woo Catalog PDF', 'manage_options', 'gmwcp-catalog', array( $this, 'GMWCP_page' ));
		
	}
	public function GMWCP_page() {
		
	?>
	<div class="wrap">
		<div class="headingmc">
			<h1 class="wp-heading-inline"><?php _e('Woo Catalog PDF', 'gmwcp'); ?></h1>
		</div><?php screen_icon(); ?>
		<hr class="wp-header-end">
		
			<div class="postbox">
					
					<div class="inside">
						<?php
						$navarr = array(
							'page=gmwcp-catalog'=>'Category & Shop Page Setting',
							'page=gmwcp-catalog&view=single'=>'Single Product Page Setting',
							'page=gmwcp-catalog&view=layout'=>'Layout',
							
						);
						?>
						<h2 class="nav-tab-wrapper">
							<?php
							foreach ($navarr as $keya => $valuea) {
								$pagexl = explode("=",$keya);
								?>
								<a href="<?php echo admin_url( 'admin.php?'.$keya);?>" class="nav-tab <?php if($pagexl[2]==$_REQUEST['view']){echo 'nav-tab-active';} ?>"><?php echo $valuea;?></a>
								<?php
							}
							?>
						</h2>
						
					   <?php

						if($_REQUEST['view']==''){
							include(GMWCP_PLUGINDIR.'includes/GMWCP_Shop_Admin.php');
						}
						if($_REQUEST['view']=='single'){
							include(GMWCP_PLUGINDIR.'includes/GMWCP_Single_Admin.php');
						}
						if($_REQUEST['view']=='layout'){
							include(GMWCP_PLUGINDIR.'includes/GMWCP_layout.php');
						}
						?>
					</div>
			</div>
			
	</div>

	<?php
	}

	public function GMWCP_register_settings() {
		register_setting( 'gmwcp_shop_options_group', 'gmwcp_shop_enable_product', array( $this, 'gmwqp_callback' )  );
		register_setting( 'gmwcp_shop_options_group', 'gmwcp_shop_display_location', array( $this, 'gmwqp_callback' )  );



		register_setting( 'gmwcp_options_group', 'gmwcp_enable_single_product' , array( $this, 'gmwqp_callback' ) );
		register_setting( 'gmwcp_options_group', 'gmwcp_single_display_location' , array( $this, 'gmwqp_callback' ) );
		

		if($_REQUEST['action'] == 'wp_gmpcp_layout'){
			if(!isset( $_POST['gmpcp_nonce_field_layout'] ) || !wp_verify_nonce( $_POST['gmpcp_nonce_field_layout'], 'gmpcp_nonce_action_layout' ) ){
                print 'Sorry, your nonce did not verify.';
                exit;
            }else{
            	foreach ($_REQUEST['gmpcplayotarr'] as $keya => $valuea) {
            		update_option( $keya, sanitize_text_field($valuea) );
            	}
				wp_redirect( admin_url( 'admin.php?page=gmwcp-catalog&view=layout&msg=layout') );
			}
			exit;
		}
	}
	
	public function gmwqp_accesstoken_callback($option) {
		return $option;
	}
	

}


?>