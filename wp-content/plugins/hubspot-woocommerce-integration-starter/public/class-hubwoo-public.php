<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://makewebbetter.com/
 * @since      1.0.0
 *
 * @package    hubspot-woocommerce-integration-starter
 * @subpackage hubspot-woocommerce-integration-starter/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    hubspot-woocommerce-integration-starter
 * @subpackage hubspot-woocommerce-integration-starter/public
 * @author     MakeWebBetter <webmaster@makewebbetter.com>
 */
class Hubwoo_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;
	}

	/**
	 * Update key as soon as user data is updated.
	 *
	 * @since    1.0.0
	 * @param      string    $user_id       User Id.
	 */

	public function hubwoo_woocommerce_save_account_details( $user_id ) {

		update_user_meta( $user_id, 'hubwoo_starter_user_data_change', 'yes' );
	}

	/**
	 * Update key as soon as guest order is done
	 *
	 * @since    1.0.0
	 * @param    string    $order_id       Order Id.
	 */
	public function hubwoo_starter_woocommerce_guest_orders( $order_id ) {

		if( !empty( $order_id ) ) {

			$customer_id = get_post_meta( $order_id, '_customer_user', true );

			if( empty( $customer_id ) ) {
				
				update_post_meta( $order_id, "hubwoo_starter_guest_order", "yes" );
			}
		}
	}

	/**
	 * Update key as soon as order is renewed
	 *
	 * @since    1.0.0
	 * @param      string    $order_id       Order Id.
	 */
	public function hubwoo_starter_save_renewal_orders( $order_id ) {

		if( !empty( $order_id ) ) {

			$user_id = (int)get_post_meta( $order_id, '_customer_user', true );

			if( $user_id != 0 && $user_id > 0 ) {

				update_user_meta( $user_id, 'hubwoo_starter_user_data_change', 'yes' );
			}
		}
	}

	/**
	 * Update key as soon as customer make changes in his/her subscription orders
	 *
	 * @since    1.0.0
	 */
	public function hubwoo_save_changes_in_subs() {

		$user_id = get_current_user_id();

		if( $user_id ) {

			update_user_meta( $user_id, 'hubwoo_starter_user_data_change', 'yes' );
		}
	}

	/**
	 * Update key as soon as customer make changes in his/her subscription orders
	 *
	 * @since    1.0.0
	 */

	public function hubwoo_subscription_switch() {

		if( isset( $_GET['switch-subscription'] ) && isset( $_GET['item'] ) ) {

			$user_id = get_current_user_id();

			if( $user_id ) {

				update_user_meta( $user_id, 'hubwoo_starter_user_data_change', 'yes' );
			}
		}
	}

	/**
	 * Update key as soon as subscriptions order status changes
	 *
	 * @since    1.0.0
	 */

	public function hubwoo_starter_update_subs_changes( $subs ) {

		if( !empty( $subs ) && ( $subs instanceof WC_Subscription ) ) {

			$order_id = $subs->get_id();

			if( !empty( $order_id ) ) {

				$user_id = (int)get_post_meta( $order_id, '_customer_user', true );
				
				if( $user_id != 0 && $user_id > 0 ) {

					update_user_meta( $user_id, 'hubwoo_starter_user_data_change', 'yes' );
				}
			}
		}
	}

	/**
	 * update the list if for the enrollment
	 *
	 * @since    1.0.0
	 */
	public function hubwoo_customer_activity_list_enrollment( $user_id ) {

		if( !empty( $user_id ) ) {

			$action = current_action();
			$customer_actions = get_option( "hubwoo_list_enrollment_customer_actions", array() );
			$lists = get_option( "hubwoo_enrolled_customer_lists", array() );
			$list_id = '';

			if( count( $customer_actions ) ) {

				foreach( $customer_actions as $key => $single_action ) {

					if( $single_action == $action ) {

						$list_id = $lists[$key];
						break;
					}
				}
			}

			update_user_meta( $user_id, 'hubwoo_starter_user_in_list', $list_id );
		}
	}

	/**
	 * Add checkout optin checkbox at woocommerce checkout
	 *
	 * @since    1.0.0
	 */
	public function hubwoo_starter_checkout_field ( $checkout ) {

		if ( $user_id = get_current_user_id() ) {
			$subscribe_status = get_user_meta( $user_id, "hubwoo_checkout_marketing_optin", true );
			$registeration_optin = get_user_meta( $user_id, "hubwoo_registeration_marketing_optin", true );
		}
		if ( !empty( $subscribe_status ) && "yes" == $subscribe_status ) {
			return;
		}
		elseif ( !empty( $registeration_optin )  && "yes" == $registeration_optin ) {
			return;
		}
		$label = get_option( "hubwoo_checkout_optin_label", __( "Subscribe", "hubwoo" ) );
		echo '<div class="form-row form-row-wide hubwoo_checkout_marketing_optin">';
		woocommerce_form_field( 'hubwoo_checkout_marketing_optin', array(
	        'type'      => 'checkbox',
	        'class'     => array( 'hubwoo-input-checkbox', 'woocommerce-form__input', 'woocommerce-form__input-checkbox' ),
	        'label'     => $label,
	    ),  WC()->checkout->get_value( 'hubwoo_checkout_marketing_optin' ) );
	    echo '</div>';
	}

	/*	
		* show checkbox at my-account registeration form
	*/
	public function hubwoo_starter_register_field () {

		$label = get_option( "hubwoo_registeration_optin_label", __( "Subscribe", "hubwoo" ) );
		echo '<div class="form-row form-row-wide hubwoo_registeration_marketing_optin">';
		woocommerce_form_field( 'hubwoo_registeration_marketing_optin', array(
	        'type'      => 'checkbox',
	        'class'     => array( 'hubwoo-input-checkbox', 'woocommerce-form__input', 'woocommerce-form__input-checkbox' ),
	        'label'     => $label,
	        'default'   => 'yes',
	    ), "yes" );
	    echo '</div>';
	}

	/*
		save checkout optin values in order/user meta
	*/
	public function hubwoo_starter_process_checkout_optin ( $order_id ) {

		if ( !empty( $_POST["hubwoo_checkout_marketing_optin"] ) ) {

			if ( !empty( $order_id ) ) {

				if ( is_user_logged_in() ) {

					update_user_meta( get_current_user_id(), "hubwoo_checkout_marketing_optin", "yes" );
				}
				else {
					
					update_post_meta( $order_id, "hubwoo_checkout_marketing_optin", "yes" );
				}
			}
		}
	}

	/*
		save optin values for registeration form in user meta
	*/
	public function hubwoo_save_register_optin ( $user_id ) {

		if ( !empty( $user_id ) ) {

			if ( isset( $_POST['hubwoo_registeration_marketing_optin'] ) ) {

				update_user_meta( $user_id, "hubwoo_registeration_marketing_optin", "yes" );
			}
		}
	}
}
