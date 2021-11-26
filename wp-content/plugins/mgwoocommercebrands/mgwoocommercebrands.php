<?php
/* 
Plugin Name: Ultimate WooCommerce Brands PRO
Plugin URI: http://codecanyon.net/item/ultimate-woocommerce-brands-plugin/9433984
Description: Add Brands taxonomy for products from WooCommerce plugin.
Version: 1.6.3
Author: MagniumThemes
Author URI: http://magniumthemes.com/
Copyright MagniumThemes.com. All rights reserved.
*/

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/* Register hook */

if ( ! class_exists( 'mgwoocommercebrands' ) ) {

	require_once WP_PLUGIN_DIR . DIRECTORY_SEPARATOR . "mgwoocommercebrands" . DIRECTORY_SEPARATOR . "mgwoocommercebrands-widget-brands-list.php";
	require_once WP_PLUGIN_DIR . DIRECTORY_SEPARATOR . "mgwoocommercebrands" . DIRECTORY_SEPARATOR . "include" . DIRECTORY_SEPARATOR . "Tax-meta-class" . DIRECTORY_SEPARATOR . "Tax-meta-class.php";
}

class MGWB {

	public function __construct() {
		register_activation_hook( __FILE__, array( $this, 'ob_install' ) );
		register_deactivation_hook( __FILE__, array( $this, 'ob_uninstall' ) );

		/**
		 * add action of plugin
		 */
		add_action( 'init', array( $this, 'register_brand_taxonomy'));
		add_action( 'init', array( $this, 'init_brand_taxonomy_meta'));

		add_action( 'admin_init', array( $this, 'obScriptInit' ) );
		add_action( 'init', array( $this, 'obScriptInitFrontend' ) );

		add_action( 'woocommerce_before_single_product', array( $this, 'single_product' ) );
		add_action( 'woocommerce_before_shop_loop_item', array( $this, 'categories_product' ) );
		add_action( 'widgets_init', array( $this, 'mgwoocommercebrands_register_widgets' ) );

		/*Setting*/
		add_action( 'plugins_loaded', array( $this, 'init_mgwoocommercebrands' ) );

		add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), array( $this, 'plugin_action_links' ) );
		add_filter( 'plugin_row_meta', array( $this, 'plugin_row_meta' ), 10, 2 );

		add_shortcode("shortcode_mg_brands_list", array( $this, 'shortcode_mg_brands_list_wp' ));
		add_shortcode("shortcode_mg_brands_slider", array( $this, 'shortcode_mg_brands_slider_wp' ));
		add_shortcode("shortcode_products_by_brand", array( $this, 'shortcode_products_by_brand_wp' ));
		
		add_action( 'init', array( $this, 'register_VC_items'));
		
	}

	/**
	 * This is an extremely useful function if you need to execute any actions when your plugin is activated.
	 */
	function ob_install() {
		global $wp_version;
		If ( version_compare( $wp_version, "2.9", "<" ) ) {
			deactivate_plugins( basename( __FILE__ ) ); // Deactivate our plugin
			wp_die( "This plugin requires WordPress version 2.9 or higher." );
		}
	}

	/**
	 * This function is called when deactive.
	 */
	function ob_uninstall() {
		//do something
	}

	/**
	 * Function set up include javascript, css.
	 */
	function obScriptInit() {
		wp_enqueue_script( 'mgwb-script-admin', plugin_dir_url( '' ) . basename( dirname( __FILE__ ) ) . '/js/mgwoocommercebrands-admin.js', array(), false, true );
		wp_enqueue_style( 'mgwb-style-admin', plugin_dir_url( '' ) . basename( dirname( __FILE__ ) ) . '/css/mgwoocommercebrands-admin.css' );
	}

	function obScriptInitFrontend() {
		wp_enqueue_script( 'mgwb-script-frontend', plugin_dir_url( '' ) . basename( dirname( __FILE__ ) ) . '/js/mgwoocommercebrands.js', array(), false, true );
		wp_enqueue_style( 'mgwb-style-frontend', plugin_dir_url( '' ) . basename( dirname( __FILE__ ) ) . '/css/mgwoocommercebrands.css' );

		wp_enqueue_style('owl-main', plugin_dir_url('') . basename( dirname( __FILE__ ) ) . '/js/owl-carousel/owl.carousel.css');
		wp_enqueue_style('owl-theme', plugin_dir_url('') . basename( dirname( __FILE__ ) ) . '/js/owl-carousel/owl.theme.css');
		
		wp_enqueue_script('owl-carousel', plugin_dir_url('') . basename( dirname( __FILE__ ) ) . '/js/owl-carousel/owl.carousel.min.js', array(), '1.3.3', true);
	}

	/**
	 * This function register custom Brand taxonomy
	 */
	function register_brand_taxonomy() {

		$labels = array(
			'name' => _x( 'Brands', 'taxonomy general name' ),
			'singular_name' => _x( 'Brand', 'taxonomy singular name' ),
			'search_items' =>  __( 'Search Brands' ),
			'all_items' => __( 'All Brands' ),
			'parent_item' => __( 'Parent Brand' ),
			'parent_item_colon' => __( 'Parent Brands:' ),
			'edit_item' => __( 'Edit Brands' ),
			'update_item' => __( 'Update Brands' ),
			'add_new_item' => __( 'Add New Brand' ),
			'new_item_name' => __( 'New Brand Name' ),
			'menu_name' => __( 'Brands' ),
		);    

	    register_taxonomy("product_brand",
	     array("product"),
	     array(
		     'hierarchical' => true,
		     'labels' => $labels,
		   	 'show_ui' => true,
    		 'query_var' => true,
		     'rewrite' => array( 'slug' => 'brands', 'with_front' => true ),
		     'show_admin_column' => true
	     ));
	}

	/**
	 * This function init custom Brand taxonomy meta fields
	 */
	function init_brand_taxonomy_meta() {

		$prefix = 'mgwb_';

		$config = array(
			'id' => 'mgwb_box',          // meta box id, unique per meta box
			'title' => 'Brands settings',          // meta box title
			'pages' => array('product_brand'),        // taxonomy name, accept categories, post_tag and custom taxonomies
			'context' => 'normal',            // where the meta box appear: normal (default), advanced, side; optional
			'fields' => array(),            // list of meta fields (can be added by field arrays)
			'local_images' => false,          // Use local or hosted images (meta box images for add/remove)
			'use_with_theme' => false          //change path if used with theme set to true, false for a plugin or anything else for a custom path(default false).
		);
		/*
		* Initiate your meta box
		*/
		$mbwb_meta =  new Tax_Meta_Class($config);

		$mbwb_meta->addImage($prefix.'image_brand_thumb',array('name'=> __('Brand Logo image ','mgwoocommercebrands')));

		$mbwb_meta->Finish();

	}
	/**
	 * This function is run when go to product detail
	 */
	function single_product( $post_ID ) {

		global $post;
		global $wp_query;

		$product_id = $post->ID;
		
		@$where_show = get_option( 'mgb_where_show' );
		@$ob_show_image = get_option( 'mgb_show_image' );

		if(isset($_GET['ob_show_image'])) {
			$ob_show_image = intval($_GET['mgb_show_image']);
		}

		@$ob_brand_title = get_option( 'mgb_brand_title' );

		if ( $where_show == 1 ) {
			return;
		}
		if ( is_admin() || ! $wp_query->post->ID ) {
			return;
		}

		$brands_list =  wp_get_object_terms($product_id, 'product_brand');
		
		$brands_list_output = '';
		$brand_image_output = '';
		$brands_list_comma = ', ';
		$i = 0;
		
		foreach ( $brands_list as $brand ) {

			if($ob_show_image == 0) {
				$brands_list_output .= '<a href="'.get_term_link( $brand->slug, 'product_brand' ).'">'.$brand->name.'</a>';

				if($i < count($brands_list) - 1) {
					$brands_list_output .= $brands_list_comma;
				}
				
				$i++;
			}
			if($ob_show_image == 1) {
				$brand_image_src_term = get_tax_meta($brand->term_id, 'mgwb_image_brand_thumb');
				$brand_image_src = $brand_image_src_term['src'];
				$brands_list_output .= '<a href="'.get_term_link( $brand->slug, 'product_brand' ).'"><img src="'.$brand_image_src .'" alt="'.$brand->name.'"/></a>';
			}
			if($ob_show_image == 2) {
				$brands_list_output .= '<a href="'.get_term_link( $brand->slug, 'product_brand' ).'">'.$brand->name.'</a>';
				
				$i++;

				$brand_image_src_term = get_tax_meta($brand->term_id, 'mgwb_image_brand_thumb');
				$brand_image_src = $brand_image_src_term['src'];
				$brands_list_output .= '<a href="'.get_term_link( $brand->slug, 'product_brand' ).'"><img src="'.$brand_image_src .'" alt="'.$brand->name.'"/></a>';
			}
			
		}

		if(count($brands_list) > 0) {

			if($ob_show_image == 0) {
				if($ob_brand_title <> '') {
					$show = '<span class="mg-brand-wrapper mg-brand-wrapper-product"><b>'.$ob_brand_title.'</b> '.$brands_list_output.'</span>';
				}
				else {
					$show = '<span class="mg-brand-wrapper mg-brand-wrapper-product">'.$brands_list_output.'</span>';
				}
			}
			else {
				if($ob_brand_title <> '') {
					$show = '<div class="mg-brand-wrapper mg-brand-wrapper-product mg-brand-image"><h3>'.$ob_brand_title.'</h3> '.$brands_list_output.'</div>';
				} else {
					$show = '<div class="mg-brand-wrapper mg-brand-wrapper-product mg-brand-image">'.$brands_list_output.'</div>';
				}
			}

			@$brand_position = get_option( 'mgb_detail_position', 0 );
			@$brand_position_custom = get_option( 'mgb_detail_position_custom', 0 );

			if(isset($_GET['brand_position'])) {
				$brand_position = intval($_GET['brand_position']);
			}

			if($brand_position_custom <> '') {
				echo "<script type='text/javascript'>
						jQuery(document).ready(function(){
							jQuery('" . ($show) . "').insertAfter('".($brand_position_custom)."');
						});
					</script>
					";
			} else {
				switch ( $brand_position ) {
					case 1:
						echo "<script type='text/javascript'>
							jQuery(document).ready(function(){
								jQuery('" .  ($show) . "').insertAfter('.woocommerce-tabs');
							});
						</script>
						";
						break;
					case 2:
						echo "<script type='text/javascript'>
							jQuery(document).ready(function(){
								jQuery('" .  ($show) . "').insertBefore('div.woocommerce-product-details__short-description');
							});
						</script>
						";
						break;
					case 3:
						echo "<script type='text/javascript'>
							jQuery(document).ready(function(){
								jQuery('" . ($show) . "').insertAfter('div.woocommerce-product-details__short-description');
							});
						</script>
						";
						break;
					case 4:
						echo "<script type='text/javascript'>
							jQuery(document).ready(function(){
								jQuery('" .  ($show) . "').insertBefore('form.cart');
							});
						</script>
						";
						break;
					case 5:
						echo "<script type='text/javascript'>
							jQuery(document).ready(function(){
								jQuery('" .  ($show) . "').insertAfter('form.cart');
							});
						</script>
						";
						break;
					case 6:
						echo "<script type='text/javascript'>
							jQuery(document).ready(function(){
								jQuery('" .  ($show) . "').insertBefore('.product_meta .posted_in');
							});
						</script>
						";
						break;
					case 7:
						echo "<script type='text/javascript'>
							jQuery(document).ready(function(){
								jQuery('" .  ($show) . "').insertAfter('.product_meta .posted_in');
							});
						</script>
						";
						break;

						
					default:
						echo "<script type='text/javascript'>
					jQuery(document).ready(function(){
						jQuery('" .  ($show) . "').insertBefore('.woocommerce-tabs');
					});
				</script>
				";

				}
			}

			
		}
		

	}

	/**
	 * This function is run on categories pages
	 */
	function categories_product() {
		global $post;

		@$where_show = get_option( 'mgb_where_show' );

		if ( $where_show == 2 ) {
			return;
		}
		if ( is_admin() || ! $post->ID ) {
			return;
		}

		$product_id = $post->ID;
		
		$brands_list =  wp_get_object_terms($product_id, 'product_brand');
		
		$brands_list_output = '';
		$brands_list_comma = ', ';
		$i = 0;
		
		foreach ( $brands_list as $brand ) {

			$brands_list_output .= '<a href="'.get_term_link( $brand->slug, 'product_brand' ).'">'.$brand->name.'</a>';

			if($i < count($brands_list) - 1) {
				$brands_list_output .= $brands_list_comma;
			}
			
			$i++;
		}

		if(count($brands_list) > 0) {

			@$ob_brand_title = get_option( 'mgb_brand_title' );

			$show = '<span class="mg-brand-wrapper mg-brand-wrapper-category"><b>'.$ob_brand_title.'</b> '.$brands_list_output.'</span>';

			@$brand_position = get_option( 'mgb_category_position', 0 );
			@$brand_position_custom = get_option( 'mgb_category_position_custom', 0 );

			if(isset($_GET['brand_position'])) {
				$brand_position = intval($_GET['brand_position']);
			}

			if($brand_position_custom <> '') {

				$brand_position_custom = str_replace('{POST-ID}', $post->ID, $brand_position_custom);

				echo "<script type='text/javascript'>
						jQuery(document).ready(function(){
							if(jQuery('".($brand_position_custom)." + .mg-brand-wrapper-category').length < 1){
								jQuery('" . ($show) . "').insertAfter('".($brand_position_custom)."');
							}
						});
					</script>
					";
			} else {
				switch ( $brand_position ) {
					case 1:
						echo "
							<script type='text/javascript'>
								jQuery(document).ready(function(){
									if(jQuery('li.post-" . ($post->ID) . " .mg-brand-wrapper-category').length < 1){
										jQuery('" . ($show) . "').insertBefore('li.post-" . ($post->ID) . " h2');
									}
								});
							</script>
							";
						break;
					case 2:
						echo "
						<script type='text/javascript'>
							jQuery(document).ready(function(){
								if(jQuery('li.post-" . ($post->ID) . " .mg-brand-wrapper-category').length < 1){
									jQuery('" . ($show) . "').insertBefore('li.post-" . ($post->ID) . " a.add_to_cart_button ');
								}
							});
						</script>
						";
						break;
					case 3:
						echo "
						<script type='text/javascript'>
							jQuery(document).ready(function(){
								if(jQuery('li.post-" . ($post->ID) . " .mg-brand-wrapper-category').length < 1){
									jQuery('" . ($show) . "').insertAfter('li.post-" . ($post->ID) . " a.add_to_cart_button');
								}
							});
						</script>
						";
						break;
					case 4:
						echo "
							<script type='text/javascript'>
								jQuery(document).ready(function(){
									if(jQuery('li.post-" . ($post->ID) . " .mg-brand-wrapper-category').length < 1){
										jQuery('" . ($show) . "').insertAfter('li.post-" . ($post->ID) . " h2');
									}
								});
							</script>
							";
						break;
					default :
						echo "
						<script type='text/javascript'>
							jQuery(document).ready(function(){
								if(jQuery('li.post-" . ($post->ID) . " .mg-brand-wrapper-category').length < 1){
									jQuery('" . ($show) . "').insertBefore('li.post-" . ($post->ID) . " span.price');
								}
							});
						</script>
						";
					}
			}
			
			
		}
	}

	/**
	 * Register widget
	 */
	function mgwoocommercebrands_register_widgets() {
		register_widget( 'mgwoocommercebrands_list_widget' );
	}

	/**
	 * Init when plugin load
	 */
	function init_mgwoocommercebrands() {
		load_plugin_textdomain( 'mgwoocommercebrands' );
		$this->load_plugin_textdomain();
		require_once( 'mgwoocommercebrands-admin.php' );
		$init = new mgwoocommercebrandsadmin();
	}

	/*Load Language*/
	function replace_mgwoocommercebrands_default_language_files() {

		$locale = apply_filters( 'plugin_locale', get_locale(), 'mgwoocommercebrands' );

		return WP_PLUGIN_DIR . "/mgwoocommercebrands/languages/mgwoocommercebrands-$locale.mo";

	}

	/**
	 * Function load language
	 */
	public function load_plugin_textdomain() {
		$locale = apply_filters( 'plugin_locale', get_locale(), 'mgwoocommercebrands' );

		// Admin Locale
		if ( is_admin() ) {
			load_textdomain( 'mgwoocommercebrands', WP_PLUGIN_DIR . "/mgwoocommercebrands/languages/mgwoocommercebrands-$locale.mo" );
		}

		// Global + Frontend Locale
		load_textdomain( 'mgwoocommercebrands', WP_PLUGIN_DIR . "/mgwoocommercebrands/languages/mgwoocommercebrands-$locale.mo" );
		load_plugin_textdomain( 'mgwoocommercebrands', false, WP_PLUGIN_DIR . "/mgwoocommercebrands/languages/" );
	}

	/*
	 * Function Setting link in plugin manager
	 */

	public function plugin_action_links( $links ) {
		$action_links = array(
			'settings'	=>	'<a href="admin.php?page=wc-settings&tab=mgwoocommercebrands" title="' . __( 'Settings', 'mgwoocommercebrands' ) . '">' . __( 'Settings', 'mgwoocommercebrands' ) . '</a>',
		);

		return array_merge( $action_links, $links );
	}

	public function plugin_row_meta( $links, $file ) {
		if ( $file == plugin_basename( __FILE__ ) ) {
			$row_meta = array(
				'support'	=>	'<a href="http://support.magniumthemes.com/" title="' . esc_attr( __( 'Visit Premium Customer Support Ticket System', 'mgwoocommercebrands' ) ) . '">' . __( 'Premium Support', 'mgwoocommercebrands' ) . '</a>',
				'about'	=>	'<a href="http://magniumthemes.com/" target="_blank" style="color: red;font-weight:bold;">' . __( 'Premium WordPress themes', 'mgwoocommercebrands' ) . '</a>',
				'updates'	=>	'<a href="http://codecanyon.net/item/ultimate-woocommerce-brands-plugin/9433984#item-description__release-history" target="_blank">' . __( 'Check for updates', 'mgwoocommercebrands' ) . '</a>',
			);

			return array_merge( $links, $row_meta );
		}

		return (array) $links;
	}
	/*
	* ShortCodes
	*/
	// Shortcode [shortcode_mg_brands_list]
	function shortcode_mg_brands_list_wp($atts, $content = null) {
		extract(shortcode_atts(array(
			'show_abc' => 1,
			'group_items' => 1,
			'show_abc_title' => 1,
			'show_images' => 1,
			'hide_empty' => 0,
			'show_count' => 0,
			'show_description' => 0
		), $atts));
		ob_start();

		$brands_list = get_terms( 'product_brand', array(
			'orderby'    => 'name',
			'order'             => 'ASC',
			'hide_empty' => $hide_empty
		));

		if ( !empty( $brands_list ) && !is_wp_error( $brands_list ) ){

			mb_internal_encoding("UTF-8");
			
			foreach ( $brands_list as $brand ) {
				$letter = mb_substr($brand->name, 0, 1,'UTF8');
				$brands_abc[$letter][] = $brand;				
			}

			echo '<div class="mgwoocommercebrands brands-listing">';

			if($show_abc == 1 && $group_items == 1) {
				echo '<div class="brands-abc-filter">';
				foreach ( $brands_abc as $key => $brand ) {
					echo '<a class="brand-letter-filter" href="#brand_'.esc_attr($key).'">'.esc_attr($key).'</a>';
				}
				echo '</div>';
			}
			
			echo "<ul>";

			foreach ( $brands_abc as $key => $brand ) {

				if($group_items == 1) {

					echo '<li class="brand-by-letter">';

					if($show_abc_title == 1) {
						echo '<h3 class="brand-letter" id="brand_'.esc_attr($key).'">'.esc_attr($key).'</h3>';
					}
					
					echo "<ul>";
				}
				
				foreach ( $brand as $brand_item ) {
					
					if($show_images == 0) {
						if($show_count == 1) {
							echo '<li><a href="'.get_term_link( $brand_item->slug, 'product_brand' ).'">'.esc_attr($brand_item->name).'</a> <span class="count">('.esc_attr($brand_item->count).')</span></li>';
						} else {
							echo '<li><a href="'.get_term_link( $brand_item->slug, 'product_brand' ).'">'.esc_attr($brand_item->name).'</a></li>';
						}
					}
					elseif($show_images == 1) {

						if((get_tax_meta($brand_item->term_id, 'mgwb_image_brand_thumb'))) {
							
							$brand_image_src_term = get_tax_meta($brand_item->term_id, 'mgwb_image_brand_thumb');
							$brand_image_src = $brand_image_src_term['src'];

							$brands_image_output = '<a href="'.get_term_link( $brand_item->slug, 'product_brand' ).'"><img src="'.esc_attr($brand_image_src) .'" alt="'.esc_attr($brand_item->name).'"/></a>';
						}
						else {
							if($show_count == 1) {
								$brands_image_output = '<li><a href="'.get_term_link( $brand_item->slug, 'product_brand' ).'">'.esc_attr($brand_item->name).'</a> <span class="count">('.esc_attr($brand_item->count).')</span></li>';
							} else {
								$brands_image_output = '<li><a href="'.get_term_link( $brand_item->slug, 'product_brand' ).'">'.esc_attr($brand_item->name).'</a></li>';
							}
						}

						if($show_description == 1) {
							$brand_description = '<div class="description">'.($brand_item->description).'</div>';
						}
						else {
							$brand_description = '';
						}

						$brands_image_output .= $brand_description;
						
						echo '<li>'.$brands_image_output.'</li>';

						$brands_image_output = '';

					} else {

						if((get_tax_meta($brand_item->term_id, 'mgwb_image_brand_thumb'))) {
							$brand_image_src_term = get_tax_meta($brand_item->term_id, 'mgwb_image_brand_thumb');
							$brand_image_src = $brand_image_src_term['src'];
							$brands_image_output = '<a href="'.get_term_link( $brand_item->slug, 'product_brand' ).'"><img src="'.esc_attr($brand_image_src) .'" alt="'.esc_attr($brand_item->name).'"/></a>';
						}
						else {
							$brands_image_output = '';
						}

						if($show_description == 1) {
							$brand_description = '<div class="description">'.$brand_item->description.'</div>';
						}
						else {
							$brand_description = '';
						}

						if($show_count == 1) {
							echo '<li>'.$brands_image_output.'<a href="'.get_term_link( $brand_item->slug, 'product_brand' ).'">'.esc_attr($brand_item->name).'</a> <span class="count">('.esc_attr($brand_item->count).')</span>'.$brand_description.'</li>';
						} else {
							echo '<li>'.$brands_image_output.'<a href="'.get_term_link( $brand_item->slug, 'product_brand' ).'">'.esc_attr($brand_item->name).'</a>'.$brand_description.'</li>';
						}
					}
					
				}
				if($group_items == 1) {
					echo "</ul>";

					echo '</li>';
				}

				
			}

			echo "</ul>";
			echo '</div>';

		}


		$content = ob_get_contents();
		ob_end_clean();
		return $content;
	}
	// Shortcode [products_wp]
	function shortcode_products_by_brand_wp($atts, $content = null) {
		global $woocommerce_loop;

		extract( shortcode_atts( array(
			'per_page' => '12',
			'title' => '',
			'columns'  => '4',
			'orderby'  => 'title',
			'order'    => 'desc',
			'brand' => '',  // Slugs
			'operator' => 'IN' // Possible values are 'IN', 'NOT IN', 'AND'.
		), $atts ) );

		if ( ! $brand ) {
			return '';
		}

		// Default ordering args
		$ordering_args = WC()->query->get_catalog_ordering_args( $orderby, $order );

		$args = array(
			'post_type'				=> 'product',
			'post_status' 			=> 'publish',
			'ignore_sticky_posts'	=> 1,
			'orderby' 				=> $ordering_args['orderby'],
			'order' 				=> $ordering_args['order'],
			'posts_per_page' 		=> $per_page,
			'meta_query' 			=> array(
				array(
					'key' 			=> '_stock_status',
					'value' => 'instock'
				),
			),
			'tax_query' 			=> array(
				array(
					'taxonomy' 		=> 'product_brand',
					'terms' 		=> array_map( 'sanitize_title', explode( ',', $brand ) ),
					'field' 		=> 'slug',
					'operator' 		=> $operator
				),
				array(
                    'taxonomy' => 'product_visibility',
                    'field'    => 'name',
                    'terms'    => 'exclude-from-catalog',
                    'operator' => 'NOT IN',

	            )
			)
		);

		if ( isset( $ordering_args['meta_key'] ) ) {
			$args['meta_key'] = $ordering_args['meta_key'];
		}

		ob_start();

		$products = new WP_Query( apply_filters( 'woocommerce_shortcode_products_query', $args, $atts ) );

		$woocommerce_loop['columns'] = $columns;

		if ( $products->have_posts() ) : ?>

			<?php if($title !== '') {
				echo '<h2 class="wpb_heading wpb_brands_slider_heading">'.esc_attr($title).'</h2>';
			} 
			?>

			<?php woocommerce_product_loop_start(); ?>

				<?php while ( $products->have_posts() ) : $products->the_post(); ?>

					<?php wc_get_template_part( 'content', 'product' ); ?>

				<?php endwhile; // end of the loop. ?>

			<?php woocommerce_product_loop_end(); ?>

		<?php endif;

		woocommerce_reset_loop();
		wp_reset_postdata();

		$return = '<div class="woocommerce columns-' . $columns . '">' . ob_get_clean() . '</div>';

		// Remove ordering query arguments
		WC()->query->remove_ordering_args();

		return $return;
	}

	// Shortcode [shortcode_mg_brands_slider]
	function shortcode_mg_brands_slider_wp($atts, $content = null) {
		extract(shortcode_atts(array(
			'slider_title' => '',
			'auto_slide' => 1,
			'show_arrows' => 1,
			'show_images' => 1,
			'items_in' => '',
			'show_count' => 0,
			'hide_empty' => 0,
			'slide_items' => 5,
			'items_limit' => 10
		), $atts));
		ob_start();

		$brands_list = get_terms( 'product_brand', array(
			'orderby'    => 'name',
			'order'             => 'ASC',
			'hide_empty' => $hide_empty,
			'number'	=> $items_limit,
			'include'	=> $items_in
		));

		if ( !empty( $brands_list ) && !is_wp_error( $brands_list ) ){
			
			$slider_id = 'brands-slider-'.rand(1, 100000);

			echo '<div class="mgwoocommercebrands brands-slider '.$slider_id.'">';

			if($slider_title !== '') {
				echo '<h2 class="wpb_heading wpb_brands_slider_heading">'.$slider_title.'</h2>';
			}

			echo "<ul>";

			foreach ( $brands_list as $brand_item ) {
				
				if($show_images == 1) {

					if((get_tax_meta($brand_item->term_id, 'mgwb_image_brand_thumb'))) {
						$brand_image_src_term = get_tax_meta($brand_item->term_id, 'mgwb_image_brand_thumb');
						$brand_image_src = $brand_image_src_term['src'];
						$brands_image_output = '<a href="'.get_term_link( $brand_item->slug, 'product_brand' ).'"><img src="'.esc_attr($brand_image_src) .'" alt="'.esc_attr($brand_item->name).'"/></a>';
					}
					else {
						if($show_count == 1) {
							$brands_image_output = '<li><a href="'.get_term_link( $brand_item->slug, 'product_brand' ).'">'.esc_attr($brand_item->name).'</a> ('.esc_attr($brand_item->count).')</li>';
						} else {
							$brands_image_output = '<li><a href="'.get_term_link( $brand_item->slug, 'product_brand' ).'">'.esc_attr($brand_item->name).'</a></li>';
						}
					}
					
					echo '<li>'.$brands_image_output.'</li>';

					$brands_image_output = '';

				} else {

					if((get_tax_meta($brand_item->term_id, 'mgwb_image_brand_thumb'))) {
						$brand_image_src_term = get_tax_meta($brand_item->term_id, 'mgwb_image_brand_thumb');
						$brand_image_src = $brand_image_src_term['src'];
						$brands_image_output = '<a href="'.get_term_link( $brand_item->slug, 'product_brand' ).'"><img src="'.esc_attr($brand_image_src) .'" alt="'.esc_attr($brand_item->name).'"/></a>';
					}
					else {
						$brands_image_output = '';
					}

					if($show_count == 1) {
						echo '<li>'.$brands_image_output.'<a href="'.get_term_link( $brand_item->slug, 'product_brand' ).'">'.esc_attr($brand_item->name).'</a> ('.esc_attr($brand_item->count).')</li>';
					} else {
						echo '<li>'.$brands_image_output.'<a href="'.get_term_link( $brand_item->slug, 'product_brand' ).'">'.esc_attr($brand_item->name).'</a></li>';
					}
				}
				
			}

			echo "</ul>";
			echo '</div>';

			if($auto_slide == 1) {
				$auto_play = 'true';
			}
			else {
				$auto_play = 'false';
			}

			if($show_arrows == 1) {
				$navigation = 'true';
			} else {
				$navigation = 'false';
			}

			echo "
			<script>
			(function($){
			$(document).ready(function() {
				$('.mgwoocommercebrands.brands-slider.".esc_js($slider_id)." ul').owlCarousel({
		            items: ".esc_js($slide_items).",
		            itemsTablet: [770,3],
		            itemsMobile : [480,1],
		            navigation: ".esc_js($navigation).",
		            navigationText : false,
		            pagination: false,
		            autoPlay: ".esc_js($auto_play)."
			    });
	
			});
			})(jQuery);
		    </script>";
		}


		$content = ob_get_contents();
		ob_end_clean();
		return $content;
	}

	function register_VC_items() {
		if (class_exists('WPBakeryVisualComposerAbstract')) {

			// VC Templates
			//vc_set_template_dir(plugin_dir_path( __FILE__ ));

			function product_brands_field($settings, $value) {   
			    $brands = get_terms('product_brand'); 
			    $dependency = vc_generate_dependencies_attributes($settings);
			    $data = '<select name="'.esc_attr($settings['param_name']).'" class="wpb_vc_param_value wpb-input wpb-select '.esc_attr($settings['param_name']).' '.esc_attr($settings['type']).'">';
			    foreach($brands as $brand) {
			        $selected = '';
			        if ($value!=='' && $brand->slug === $value) {
			             $selected = ' selected="selected"';
			        }
			        $data .= '<option class="'.esc_attr($brand->slug).'" value="'.esc_attr($brand->slug).'"'.esc_attr($selected).'>' . esc_attr($brand->name) . ' (' . esc_attr($brand->count) . ' products)</option>';
			    }
			    $data .= '</select>';
			    return $data;
			}
			vc_add_shortcode_param('product_brands' , 'product_brands_field');

			// VC elements
			vc_map(array(
				"name" 			=> __("Products by Brand"),
				"category" 		=> __('Products'),
				"description"	=> __("Display WooCommerce products"),
				"base" 			=> "shortcode_products_by_brand",
				"class" 			=> "",
				"icon" 			=> "shortcode_products_by_brand",

				"params" 	=> array(
					array(
						"type"			=> "textfield",
						"holder"		=> "div",
						"class" 		=> "hide_in_vc_editor",
						"admin_label" 	=> true,
						"heading"		=> __("Title"),
						"param_name"	=> "title",
						"value" => "Title"			
					),
					
					array(
						"type" => "product_brands",
						"holder" => "div",
						"class" 		=> "hide_in_vc_editor",
						"admin_label" 	=> true,
						"heading" => __("Brand"),
						"param_name" => "brand"
					),
					
					array(
						"type"			=> "textfield",
						"holder"		=> "div",
						"class" 		=> "hide_in_vc_editor",
						"admin_label" 	=> true,
						"heading"		=> __("Number of Products"),
						"param_name"	=> "per_page",
						"value"			=> "10",
					),
					
					array(
						"type"			=> "dropdown",
						"holder"		=> "div",
						"class" 		=> "hide_in_vc_editor",
						"admin_label" 	=> true,
						"heading"		=> __("Order By"),
						"description"	=> __("Description for Order By."),
						"param_name"	=> "orderby",
						"value"			=> array(
							"None"	=> "none",
							"ID"	=> "ID",
							"Title"	=> "title",
							"Date"	=> "date",
							"Rand"	=> "rand"
						),
						"std"			=> "date",
					),
					
					array(
						"type"			=> "dropdown",
						"holder"		=> "div",
						"class" 		=> "hide_in_vc_editor",
						"admin_label" 	=> true,
						"heading"		=> __("Order"),
						"description"	=> __("Description for Order."),
						"param_name"	=> "order",
						"value"			=> array(
							"Desc"	=> "desc",
							"Asc"	=> "asc"
						),
						"std"			=> "desc",
					),
				)
			));

			vc_map(array(
				"name" 			=> __("Brands List"),
				"category" 		=> __('Products'),
				"description"	=> __("Display Brands list"),
				"base" 			=> "shortcode_mg_brands_list",
				"class" 			=> "",
				"icon" 			=> "shortcode_mg_brands_list",

				"params" 	=> array(
					array(
						"type"			=> "dropdown",
						"holder"		=> "div",
						"class" 		=> "hide_in_vc_editor",
						"admin_label" 	=> true,
						"heading"		=> __("Display ABC filter (Work with 'Display brands letters = Yes')"),
						"dependency"	=> array(
							"element"	=> "group_items",
							"value"		=> Array("1"),
						),
						"param_name"	=> "show_abc",
						"value"			=> array(
								"Yes"			=> "1",
								"No"			=> "0",
							),
						"std"			=> "1",
					),
					array(
						"type"			=> "dropdown",
						"holder"		=> "div",
						"class" 		=> "hide_in_vc_editor",
						"admin_label" 	=> true,
						"heading"		=> __("Group items by alphabet"),
						"param_name"	=> "group_items",
						"value"			=> array(
								"Yes"			=> "1",
								"No"			=> "0",
							),
						"std"			=> "1",
					),
					array(
						"type"			=> "dropdown",
						"holder"		=> "div",
						"class" 		=> "hide_in_vc_editor",
						"admin_label" 	=> true,
						"dependency"	=> array(
							"element"	=> "group_items",
							"value"		=> Array("1"),
						),
						"heading"		=> __("Display brands letters"),
						"param_name"	=> "show_abc_title",
						"value"			=> array(
								"Yes"			=> "1",
								"No"			=> "0",
							),
						"std"			=> "1",
					),
					array(
						"type"			=> "dropdown",
						"holder"		=> "div",
						"class" 		=> "hide_in_vc_editor",
						"admin_label" 	=> true,
						"heading"		=> __("Display type"),
						"param_name"	=> "show_images",
						"value"			=> array(
								"Brand images"			=> "1",
								"Brand titles"			=> "0",
								"Both"			=> "2"
							),
						"std"			=> "1",
					),
					array(
						"type"			=> "dropdown",
						"holder"		=> "div",
						"class" 		=> "hide_in_vc_editor",
						"admin_label" 	=> true,
						"heading"		=> __("Show brand description"),
						"param_name"	=> "show_description",
						"value"			=> array(
								"Yes"			=> "1",
								"No"			=> "0",
							),
						"std"			=> "0",
					),
					array(
						"type"			=> "dropdown",
						"holder"		=> "div",
						"class" 		=> "hide_in_vc_editor",
						"admin_label" 	=> true,
						"heading"		=> __("Hide empty brands"),
						"param_name"	=> "hide_empty",
						"value"			=> array(
								"Yes"			=> "1",
								"No"			=> "0",
							),
						"std"			=> "0",
					),
					array(
						"type"			=> "dropdown",
						"holder"		=> "div",
						"class" 		=> "hide_in_vc_editor",
						"admin_label" 	=> true,
						"heading"		=> __("Show items count in brand"),
						"param_name"	=> "show_count",
						"value"			=> array(
								"Yes"			=> "1",
								"No"			=> "0",
							),
						"std"			=> "0",
					),
				)
			));
		
			vc_map(array(
				"name" 			=> __("Brands Slider"),
				"category" 		=> __('Products'),
				"description"	=> __("Display Brands Slider"),
				"base" 			=> "shortcode_mg_brands_slider",
				"class" 			=> "",
				"icon" 			=> "shortcode_mg_brands_slider",

				"params" 	=> array(
					array(
						"type"			=> "textfield",
						"holder"		=> "div",
						"class" 		=> "hide_in_vc_editor",
						"admin_label" 	=> true,
						"heading"		=> __("Title"),
						"param_name"	=> "slider_title",
						"std"			=> "",
					),
					array(
						"type"			=> "dropdown",
						"holder"		=> "div",
						"class" 		=> "hide_in_vc_editor",
						"admin_label" 	=> true,
						"heading"		=> __("Slider auto play"),
						"param_name"	=> "auto_slide",
						"value"			=> array(
								"Yes"			=> "1",
								"No"			=> "0"
							),
						"std"			=> "1",
					),
					array(
						"type"			=> "textfield",
						"holder"		=> "div",
						"class" 		=> "hide_in_vc_editor",
						"admin_label" 	=> true,
						"heading"		=> __("Items (columns) in slider per row"),
						"param_name"	=> "slide_items",
						"std"			=> "5",
					),
					array(
						"type"			=> "textfield",
						"holder"		=> "div",
						"class" 		=> "hide_in_vc_editor",
						"admin_label" 	=> true,
						"heading"		=> __("Items in slider (items limit)"),
						"param_name"	=> "items_limit",
						"std"			=> "10",
					),
					array(
						"type"			=> "textfield",
						"holder"		=> "div",
						"class" 		=> "hide_in_vc_editor",
						"admin_label" 	=> true,
						"heading"		=> __("Show only selected brands (brands ID's, separated by comma). Leave empty to show all brands in slider."),
						"param_name"	=> "items_in",
						"std"			=> "",
					),
					array(
						"type"			=> "dropdown",
						"holder"		=> "div",
						"class" 		=> "hide_in_vc_editor",
						"admin_label" 	=> true,
						"heading"		=> __("Show navigation arrows"),
						"param_name"	=> "show_arrows",
						"value"			=> array(
								"Yes"			=> "1",
								"No"			=> "0"
							),
						"std"			=> "1",
					),
					array(
						"type"			=> "dropdown",
						"holder"		=> "div",
						"class" 		=> "hide_in_vc_editor",
						"admin_label" 	=> true,
						"heading"		=> __("Display type"),
						"param_name"	=> "show_images",
						"value"			=> array(
								"Brand images"			=> "1",
								"Brand images + titles"			=> "0"
							),
						"std"			=> "1",
					),
					array(
						"type"			=> "dropdown",
						"holder"		=> "div",
						"class" 		=> "hide_in_vc_editor",
						"admin_label" 	=> true,
						"heading"		=> __("Hide empty brands"),
						"param_name"	=> "hide_empty",
						"value"			=> array(
								"Yes"			=> "1",
								"No"			=> "0",
							),
						"std"			=> "0",
					),
					array(
						"type"			=> "dropdown",
						"holder"		=> "div",
						"class" 		=> "hide_in_vc_editor",
						"admin_label" 	=> true,
						"heading"		=> __("Show items count in brand title"),
						"param_name"	=> "show_count",
						"value"			=> array(
								"Yes"			=> "1",
								"No"			=> "0",
							),
						"std"			=> "0",
					),
				)
			));
		}
	}

}

$mgwoocommercebrands = new MGWB();
?>