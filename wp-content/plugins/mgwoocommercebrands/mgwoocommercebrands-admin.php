<?php
/**
 * Admin setting class
 *
 * @author  MagniumThemes
 * @package magniumthemes.com
 */


if ( ! class_exists( 'mgwoocommercebrandsadmin' ) ) {
	/**
	 * Admin class.
	 * The class manage all the admin behaviors.
	 *
	 * @since 1.0.0
	 */
	class mgwoocommercebrandsadmin {

		public function __construct() {

			//Actions
			add_action( 'init', array( $this, 'init' ) );

			add_action( 'woocommerce_settings_tabs_mgwoocommercebrands', array( $this, 'print_plugin_options' ) );
			add_action( 'woocommerce_update_options_mgwoocommercebrands', array( $this, 'update_options' ) );

			//Filters
			add_filter( 'woocommerce_settings_tabs_array', array( $this, 'add_tab_woocommerce' ), 30 );

			// Updates
			add_action( 'admin_notices', array( $this, 'mgtwb_show_admin_notice_update') );
			add_action( 'admin_init', array( $this, 'mgtwb_update_message_dismiss' ) );

		}

		/**
		 * Init method:
		 *  - default options
		 *
		 * @access public
		 * @since  1.0.0
		 */
		public function init() {
			$this->options = $this->_initOptions();
			//$this->_default_options();
		}


		/**
		 * Update plugin options.
		 *
		 * @return void
		 * @since 1.0.0
		 */
		public function update_options() {
			foreach ( $this->options as $option ) {
				woocommerce_update_options( $option );
			}
		}


		/**
		 * Add Magnifier's tab to Woocommerce -> Settings page
		 *
		 * @access public
		 *
		 * @param array $tabs
		 *
		 * @return array
		 */
		public function add_tab_woocommerce( $tabs ) {
			$tabs['mgwoocommercebrands'] = __( 'Brands settings', 'mgwoocommercebrands' );

			return $tabs;
		}


		/**
		 * Print all plugin options.
		 *
		 * @return void
		 * @since 1.0.0
		 */
		public function print_plugin_options() {
			?>
			<div class="subsubsub_section">

				<?php foreach ( $this->options as $id => $tab ) : ?>
					<!-- tab #<?php echo $id ?> -->
					<div class="section" id="mgwoocommercebrands_<?php echo $id ?>">
						<?php woocommerce_admin_fields( $this->options[$id] ) ?>
					</div>
				<?php endforeach ?>
			</div>
		<?php
		}


		/**
		 * Initialize the options
		 *
		 * @access protected
		 * @return array
		 * @since  1.0.0
		 */
		protected function _initOptions() {
			$options = array(
				'general' => array(
					array(
				          'title' => __( 'Thanks for choosing our Premium plugin!', 'mgwoocommercebrands' ),
				          'type'   => 'title',
				          'desc'   => 'First of all we recommend you to <a href="http://magniumthemes.com/go/uwb-docs/" target="blank">read Plugin Documentation</a> to understand how to use it. If you get any problems or questions please <a href="http://support.magniumthemes.com/" target="_blank">let us know</a> and we will help you!<br>
							
							<a href="http://magniumthemes.com/go/uwb-docs/" target="blank" class="button button-secondary">Read Documentation</a> <a href="http://support.magniumthemes.com/" target="blank" class="button button-secondary">Get Premium support</a> <a href="http://themeforest.net/user/dedalx/portfolio/" target="blank" class="button button-secondary">Our Premium Themes</a> <a href="http://codecanyon.net/collections/5208381-premium-wordpress-plugins" target="blank" class="button button-secondary">Our other Ultimate Plugins</a> <a href="http://magniumthemes.com/how-to-rate-items-on-themeforest/" target="blank" class="button button-primary">Please Rate our plugin</a><br><br>
				          ',
				    ),
					array( 'title' => __( 'General Options', 'mgwoocommercebrands' ),
						   'type'  => 'title',
						   'desc'  => '',
						   'id'    => 'mgwoocommercebrands_options' ),
					array(
						'title'    => __( 'Brand name show on', 'mgwoocommercebrands' ),
						'id'       => 'mgb_where_show',
						'default'  => '0',
						'type'     => 'radio',
						'desc_tip' => __( 'Please select where you want show Brand name.', 'mgwoocommercebrands' ),
						'options'  => array(
							'0' => __( 'Both categories and product detail page', 'mgwoocommercebrands' ),
							'1' => __( 'Only categories ', 'mgwoocommercebrands' ),
							'2' => __( 'Only product detail', 'mgwoocommercebrands' )
						),
					),
					array(
						'title'    => __( 'Brand title', 'mgwoocommercebrands' ),
						'id'       => 'mgb_brand_title',
						'default'  => 'Brand:',
						'type'     => 'text',
						'desc_tip' => __( 'Leave empty if you dont want to show brand title before brand name(s)', 'mgwoocommercebrands' ),
						
					),
					
					array(
						'title'    => __( 'Brand display type on product detail page', 'mgwoocommercebrands' ),
						'id'       => 'mgb_show_image',
						'default'  => '0',
						'type'     => 'radio',
						'desc_tip' => __( 'Please check if you want to see brand image instead of title', 'mgwoocommercebrands' ),
						'options'  => array(
							'0' => __( 'Show as brand(s) title', 'mgwoocommercebrands' ),
							'1' => __( 'Show as brand(s) image', 'mgwoocommercebrands' ),
							'2' => __( 'Show both brand title and image', 'mgwoocommercebrands' )
						),
					),
					
					array( 'type' => 'sectionend', 'id' => 'mgwoocommercebrands_options' ),

					array( 'title' => __( 'Product Details Page', 'mgwoocommercebrands' ),
						   'type'  => 'title',
						   'desc'  => __( 'Predefined brand display positions will work only if your theme does not changed default WooCommerce templates structure. If position does not work for you - you need to use Custom CSS selector for custom position, suitable for your theme (ask theme support for help).', 'mgwoocommercebrands' ),
						   'id'    => 'mgwoocommercebrands_detail_product' ),
					array(
						'title'    => __( 'Brand display position', 'mgwoocommercebrands' ),
						'id'       => 'mgb_detail_position',
						'default'  => '0',
						'type'     => 'radio',
						'desc_tip' => __( 'Please choose position where brand show on product details page.', 'mgwoocommercebrands' ),
						'options'  => array(
							'0' => __( 'Above tabs area', 'mgwoocommercebrands' ),
							'1' => __( 'Below tabs area', 'mgwoocommercebrands' ),
							'2' => __( 'Above short description', 'mgwoocommercebrands' ),
							'3' => __( 'Below short description', 'mgwoocommercebrands' ),
							'4' => __( 'Above Add to cart', 'mgwoocommercebrands' ),
							'5' => __( 'Below Add to cart', 'mgwoocommercebrands' ),
							'6' => __( 'Above Categories list', 'mgwoocommercebrands' ),
							'7' => __( 'Below Categories list', 'mgwoocommercebrands' )
						),
						

					),
					array(
						'title'    => __( 'Custom position (JQuery CSS selector of element to show brand after it).', 'mgwoocommercebrands' ),
						'id'       => 'mgb_detail_position_custom',
						'default'  => '',
						'type'     => 'text',
						'desc' => __( '<br>Example: .div.product .product_title<br>Leave empty if you dont want to show brand in custom position', 'mgwoocommercebrands' ),
			
					),

					array( 'type' => 'sectionend', 'id' => 'mgwoocommercebrands_detail_product' ),

					array( 'title' => __( 'Product Category', 'mgwoocommercebrands' ),
						   'type'  => 'title',
						   'desc'  => __( 'Predefined brand display positions will work only if your theme does not changed default WooCommerce templates structure. If position does not work for you - you need to use Custom CSS selector for custom position, suitable for your theme (ask theme support for help).', 'mgwoocommercebrands' ),
						   'id'    => 'mgwoocommercebrands_product_category' ),
					array(
						'title'    => __( 'Brand display position on category', 'mgwoocommercebrands' ),
						'id'       => 'mgb_category_position',
						'default'  => '0',
						'type'     => 'radio',
						'desc_tip' => __( 'Please choose position where brand show on category products.', 'mgwoocommercebrands' ),
						'options'  => array(
							'0' => __( 'Above price', 'mgwoocommercebrands' ),
							'1' => __( 'Above title', 'mgwoocommercebrands' ),
							'4' => __( 'Below title', 'mgwoocommercebrands' ),
							'2' => __( 'Above Add to Cart', 'mgwoocommercebrands' ),
							'3' => __( 'Below Add to Cart', 'mgwoocommercebrands' )
							
						),
					),

					array(
						'title'    => __( 'Custom position (JQuery CSS selector of element to show brand after it).', 'mgwoocommercebrands' ),
						'id'       => 'mgb_category_position_custom',
						'default'  => '',
						'type'     => 'text',
						'desc' => __( '<br>Example: li.post-{POST-ID} .product_title<br>{POST-ID} can be used to get current Product ID in selector. Leave empty if you dont want to show brand in custom position', 'mgwoocommercebrands' ),
			
					),

					array( 'type' => 'sectionend', 'id' => 'mgwoocommercebrands_product_category' )

				)
			);

			return apply_filters( 'mgwoocommercebrands_tab_options', $options );
		}

		public function mgtwb_show_admin_notice_update() {
			global $current_user;
			$user_id = $current_user->ID;

			if ( ! get_user_meta($user_id, 'mgtwb_hind_update_message_ignore') && ( current_user_can( 'install_plugins' ) ) ):
		    ?>
		    <div class="updated below-h2">
				<a href="<?php echo esc_url( add_query_arg( 'mgtwb_update_message_dismiss', '0' ) ); ?>" style="float: right;padding-top: 9px;">(never show this message again)&nbsp;&nbsp;<b>X</b></a><p style="display: inline-block;">Hi! Would you like to receive Ultimate WooCommerce Brands updates news & get premium support? Subscribe to email notifications: </p>
				<form style="display: inline-block;" action="//magniumthemes.us8.list-manage.com/subscribe/post?u=6ff051d919df7a7fc1c84e4ad&amp;id=9285b358e7" method="post" id="mc-embedded-subscribe-form" name="mc-embedded-subscribe-form" class="validate" target="_blank" novalidate>
				   <input type="email" value="" name="EMAIL" class="required email" id="mce-EMAIL" placeholder="Your email">
				   <input type="submit" value="Subscribe" name="subscribe" id="mc-embedded-subscribe" class="button">
				</form>
				
		    </div>
		    <?php
			endif;
		}
		

		public function mgtwb_update_message_dismiss() {
			global $current_user;
		    $user_id = $current_user->ID;
		    /* If user clicks to ignore the notice, add that to their user meta */
		    if ( isset($_GET['mgtwb_update_message_dismiss']) && '0' == $_GET['mgtwb_update_message_dismiss'] ) {
			    add_user_meta($user_id, 'mgtwb_hind_update_message_ignore', 'true', true);
			}
		}

	}
}
