<?php
/**
 * Plugin Name: WooCommerce 2Checkout Payment Gateway Free
 * Plugin URI: http://www.najeebmedia.com/2checkout-payment-gateway-for-woocommerce/
 * Description: 2Checkout is payment gateway for WooCommerce allowing you to take payments via 2Checkout.
 * Version: 5.0
 * Author: Najeeb Ahmad
 * WC requires at least: 3.0.0
 * WC tested up to: 4.0
 * Author URI: http://www.najeebmedia.com/
 */ 

define( 'TWOCO_PATH', untrailingslashit(plugin_dir_path( __FILE__ )) );
define( 'TWOCO_URL', untrailingslashit(plugin_dir_url( __FILE__ )) );
define('TWOCO_VERSION', '5.0');

include_once $var = TWOCO_PATH.'/inc/rest.php';
include_once $var = TWOCO_PATH.'/classes/deactivate.class.php';

add_action( 'plugins_loaded', 'init_nm_woo_gateway', 0);

function nm_2co_settings( $links ) {
    $settings_link = '<a href="'.admin_url( 'admin.php?page=wc-settings&tab=checkout&section=wc_gateway_nm_twocheckout' ).'">Setup</a>';
  	array_push( $links, $settings_link );
  	return $links;
}
$plugin = plugin_basename( __FILE__ );
add_filter( "plugin_action_links_$plugin", 'nm_2co_settings' );

function init_nm_woo_gateway(){

	if ( ! class_exists( 'WC_Payment_Gateway' ) ) return;

	class WC_Gateway_NM_TwoCheckout extends WC_Payment_Gateway {
		
		// Logging
        public static $log_enabled = false;
        public static $log = false;

		var $seller_id;
		var $demo;
		var $plugin_url;

		public function __construct(){
			
			global $woocommerce;

			$this -> plugin_url = WP_PLUGIN_URL . DIRECTORY_SEPARATOR . 'woocommerce-2checkout-payment';
			
			$this->id 					= 'nmwoo_2co';
			$this->has_fields   		= false;
			$this->checkout_url     	= 'https://www.2checkout.com/checkout/purchase';
			$this->checkout_url_sandbox	= 'https://sandbox.2checkout.com/checkout/purchase';
			$this->icon 				= $this -> plugin_url.'/images/2co_logo.png';
			$this->method_title 		= '2Checkout';
			$this->method_description 	= 'This plugin add 2checkout payment gateway with Woocommerce based shop. Make sure you have set your 2co account according <a href="https://najeebmedia.com/blog/woocommerce-2checkout-payment-gateway-setup-guide/" target="_blank">these setting</a>';
				
			$this->title 				= $this->get_option( 'title' );
			$this->description 			= $this->get_option( 'description' );
			$this->seller_id			= $this->get_option( 'seller_id' );
			$this->secret_word			= trim($this->get_option( 'secret_word' ));
			$this -> demo 				= $this -> get_option('demo');
			$this->debug 				= $this->get_option('debug');
			$this -> pay_method 		= $this -> get_option('pay_method'); 
			$this -> xchange_rate 		= $this -> get_option('xchange_rate');
				
				
			$this->init_form_fields();
			$this->init_settings();
			
			self::$log_enabled = $this->debug;
				
			// Save options
			add_action( 'woocommerce_update_options_payment_gateways_' . $this->id, array( $this, 'process_admin_options' ) );
			// add_action('process_2co_ipn_request', array( $this, 'successful_request' ), 1 );
			
			// Payment listener/API hook
			add_action( 'woocommerce_api_wc_gateway_nm_twocheckout', array( $this, 'twocheckout_response' ) );
				
		}


		function init_form_fields(){

			$this->form_fields = array(
					'enabled' => array(
							'title' => __( 'Enable', 'woocommerce' ),
							'type' => 'checkbox',
							'label' => __( 'Yes', 'woocommerce' ),
							'default' => 'yes'
					),
					
					'inline_checkout' => array(
							'title' => __( 'Inline Checkout', 'woocommerce' ),
							'type' => 'checkbox',
							'label' => __( 'Yes - It is PRO Feature get <a href="http://www.najeebmedia.com/2checkout-payment-gateway-for-woocommerce/" target="_blank">Pro Version</a> - <a target="_blank" href="https://vimeo.com/330336799">Video</a>', 'woocommerce' ),
							'default' => 'yes',
							'description'=> __("Accept payment on your Site. Accept Credit/Debit Cards & PayPal"),
							'desc_tip' => true,
					),
					
					'paypal_direct' => array(
							'title' => __( 'PayPal Direct', 'woocommerce' ),
							'type' => 'checkbox',
							'description' => __( 'As long as all of the required parameters are passed, the user will be redirected directly to PayPal to complete their payment.', 'woocommerce' ),
							'label' => __( 'Yes - It is PRO Feature get <a href="http://www.najeebmedia.com/2checkout-payment-gateway-for-woocommerce/" target="_blank">Pro Version</a> - <a target="_blank" href="https://vimeo.com/283880739">Video</a>', 'woocommerce' ),
							'default'     => 'no',
							'desc_tip'      => true,
					),
					
					'xchange_rate' => array(
							'title' => __( 'Currency Converter', 'woocommerce' ),
							'type' => 'checkbox',
							'label' => __( 'Yes - It is PRO Feature get <a href="http://www.najeebmedia.com/2checkout-payment-gateway-for-woocommerce/" target="_blank">Pro Version</a>', 'woocommerce' ),
							'default' => 'no',
							'description' => __( 'If your currency is not supported by 2CO, then use this option. It will automatically convert your currency to USD using Yahoo Finance API', 'woocommerce' ),
							'desc_tip'      => false,
					),
					
					'seller_id' => array(
							'title' => __( '2CO Account #', 'woocommerce' ),
							'type' => 'text',
							'description' => __( 'This Seller ID issued by 2Checkout', 'woocommerce' ),
							'default' => '',
							'desc_tip'      => true,
					),
				
					'title' => array(
							'title' => __( 'Title', 'woocommerce' ),
							'type' => 'text',
							'description' => __( 'This controls the title which the user sees during checkout.', 'woocommerce' ),
							'default' => __( '2Checkout Payment', 'woocommerce' ),
							'desc_tip'      => true,
					),
					'description' => array(
							'title' => __( 'Customer Message', 'woocommerce' ),
							'type' => 'textarea',
							'default' => ''
					),
					'demo' => array(
							'title' => __( 'Enable Demo Mode', 'woocommerce' ),
							'type' => 'checkbox',
							'label' => __( 'Yes', 'woocommerce' ),
							'default' => 'yes'
					),
					'secret_word' => array(
							'title' => __( 'Secret Word', 'twoco' ),
							'type' => 'text',
							'description' => __( 'Secret Word must be same as given in 2Checkout Settings..', 'twoco' ),
							'default' => __( '', 'twoco' ),
							'desc_tip'      => true,
					),
					'debug' => array(
                        'title'       => __( 'Debug Log', 'woocommerce' ),
                        'type'        => 'checkbox',
                        'label'       => __( 'Enable logging', 'woocommerce' ),
                        'default'     => 'no',
                        'description' => sprintf( __( 'Debug Information <em>%s</em>', 'woocommerce' ), wc_get_log_file_path( 'twocheckout' ) )
                    ),
					// 'pay_method' => array(
					// 		'title' => __( 'Payment Method', 'woocommerce' ),
					// 		'type' => 'text',
					// 		'description' => __( 'CC for Credit Card, PPI for PayPal. This will set the default selection on the payment method step during the checkout process.', 'woocommerce' ),
					// 		'default' => __( 'CC', 'woocommerce' ),
					// 		'desc_tip'      => true,
					// ),
			);
		}
		
		/**
        * Logging method
        * @param  string $message
        */
        public static function log( $message ) {
            if ( self::$log_enabled ) {
                if ( empty( self::$log ) ) {
                    self::$log = new WC_Logger();
                }
                
                $message = is_array($message) ? json_encode($message) : $message;
                self::$log->add( 'twocheckout', $message );
            }
        }


		/**
		 * Process the payment and return the result
		 *
		 * @access public
		 * @param int $order_id
		 * @return array
		 */
		function process_payment( $order_id ) {

			$order = new WC_Order( $order_id );


			$twoco_args = $this->get_twoco_args( $order );
			/*echo '<pre>';
			 print_r($twoco_args);
			echo '</pre>';
			exit;*/
			
			
			$twoco_args = http_build_query( $twoco_args, '', '&' );
			$this->log("========== Payment Procesing Started: args =========");
			$this->log($twoco_args);
			
			//if demo is enabled
			$checkout_url = '';
			if ($this -> demo == 'yes'){
				$checkout_url =	$this->checkout_url_sandbox;
			}else{
				$checkout_url =	$this->checkout_url;
			}
			
			// var_dump($checkout_url.'?'.$twoco_args); exit;
			
			return array(
					'result' 	=> 'success',
					'redirect'	=> $checkout_url.'?'.$twoco_args
			);


		}


		/**
		 * Get 2Checkout Args for passing to PP
		 *
		 * @access public
		 * @param mixed $order
		 * @return array
		 */
		function get_twoco_args( $order ) {
			global $woocommerce;

			$order_id = $order->get_id();

			// 2Checkout Args
			$twoco_args = array(
					'sid' 					=> $this->seller_id,
					'mode' 					=> '2CO',
					'merchant_order_id'		=> $order_id,
					'currency_code'			=> $curr_code,
						
					// Billing Address info
					'first_name'			=> $order->get_billing_first_name(),
					'last_name'				=> $order->get_billing_last_name(),
					'street_address'		=> $order->get_billing_address_1(),
					'street_address2'		=> $order->get_billing_address_2(),
					'city'					=> $order->get_billing_city(),
					'state'					=> $order->get_billing_state(),
					'zip'					=> $order->get_billing_postcode(),
					'country'				=> $order->get_billing_country(),
					'email'					=> $order->get_billing_email(),
					'phone'					=> $order->get_billing_phone(),
			);

			// Shipping
			
			if ($order->needs_shipping_address()) {

				$twoco_args['ship_name']			= $order->get_shipping_first_name().' '.$order->get_shipping_last_name();
				$twoco_args['company']				= $order->get_shipping_company();
				$twoco_args['ship_street_address']	= $order->get_shipping_address_1();
				$twoco_args['ship_street_address2']	= $order->get_shipping_address_2();
				$twoco_args['ship_city']			= $order->get_shipping_city();
				$twoco_args['ship_state']			= $order->get_shipping_state();
				$twoco_args['ship_zip']				= $order->get_shipping_postcode();
				$twoco_args['ship_country']			= $order->get_shipping_country();
			}
			
			$twoco_args['x_receipt_link_url'] 	= $this->get_return_url( $order );
			$twoco_args['return_url']			= str_replace('https', 'http', $order->get_cancel_order_url());
			
			
			//setting payment method
			if ($this -> pay_method)
				$twoco_args['pay_method'] = $this -> pay_method;
			
			
			//if demo is enabled
			if ($this -> demo == 'yes'){
				$twoco_args['demo'] =	'Y';
			}

			$item_names = array();

			if ( sizeof( $order->get_items() ) > 0 ){
				
				$twoco_product_index = 0;
				
				foreach ( $order->get_items() as $item ){
					if ( $item['qty'] )
						$item_names[] = $item['name'] . ' x ' . $item['qty'];
				
					/*echo '<pre>';
					print_r($item);
					echo '</pre>';
					exit;*/
					
					
					/**
					 * since version 1.6
					 * adding support for both WC Versions
					 */
					$_sku = '';
					if ( function_exists( 'get_product' ) ) {
							
						// Version 2.0
						$product = $order->get_product_from_item($item);
							
						// Get SKU or product id
						if ( $product->get_sku() ) {
							$_sku = $product->get_sku();
						} else {
							$_sku = $product->get_id();
						}
							
					} else {
							
						// Version 1.6.6
						$product = new WC_Product( $item['id'] );
							
						// Get SKU or product id
						if ( $product->get_sku() ) {
							$_sku = $product->get_sku();
						} else {
							$_sku = $item['id'];
						}	
					}
					
					$tangible = "N";
					
					$item_formatted_name 	= $item['name'] . ' (Product SKU: '.$item['product_id'].')';
				
					$twoco_args['li_'.$twoco_product_index.'_type'] 	= 'product';
					$twoco_args['li_'.$twoco_product_index.'_name'] 	= sprintf( __( 'Order %s' , 'woocommerce'), $order->get_order_number() ) . " - " . $item_formatted_name;
					$twoco_args['li_'.$twoco_product_index.'_quantity'] = $item['qty'];
					$twoco_args['li_'.$twoco_product_index.'_price'] 	= $this -> get_price($order->get_item_total( $item, false ));
					$twoco_args['li_'.$twoco_product_index.'_product_id'] = $_sku;
					$twoco_args['li_'.$twoco_product_index.'_tangible'] = $tangible;
					
					$twoco_product_index++;
				}
				
				//getting extra fees since version 2.0+
				$extrafee = $order -> get_fees();
				if($extrafee){
				
					
					$fee_index = 1;
					foreach ( $order -> get_fees() as $item ) {
						
						$twoco_args['li_'.$twoco_product_index.'_type'] 	= 'product';
						$twoco_args['li_'.$twoco_product_index.'_name'] 	= sprintf( __( 'Other Fee %s' , 'woocommerce'), $item['name'] );
						$twoco_args['li_'.$twoco_product_index.'_quantity'] = 1;
						$twoco_args['li_'.$twoco_product_index.'_price'] 	= $this->get_price( $item['line_total'] );

						$fee_index++;
						$twoco_product_index++;
	 				}	
				}
				
				// Shipping Cost
				if ( $order -> get_total_shipping() > 0 ) {
					
					
					$twoco_args['li_'.$twoco_product_index.'_type'] 		= 'shipping';
					$twoco_args['li_'.$twoco_product_index.'_name'] 		= __( 'Shipping charges', 'woocommerce' );
					$twoco_args['li_'.$twoco_product_index.'_quantity'] 	= 1;
					$twoco_args['li_'.$twoco_product_index.'_price'] 		= $this->get_price( $order -> get_total_shipping() );
					$twoco_args['li_'.$twoco_product_index.'_tangible'] = 'Y';
					
					$twoco_product_index++;
				}
				
				// Taxes (shipping tax too)
				if ( $order -> get_total_tax() > 0 ) {
				
					$twoco_args['li_'.$twoco_product_index.'_type'] 		= 'tax';
					$twoco_args['li_'.$twoco_product_index.'_name'] 		= __( 'Tax', 'woocommerce' );
					$twoco_args['li_'.$twoco_product_index.'_quantity'] 	= 1;
					$twoco_args['li_'.$twoco_product_index.'_price'] 		= $this->get_price( $order->get_total_tax() );
					
					$twoco_product_index++;
				}

				
			}

			
			
			$twoco_args = apply_filters( 'woocommerce_twoco_args', $twoco_args );
			
			return $twoco_args;
		}
		
		/**
		 * this function is return product object for two
		 * differetn version of WC
		 */
		function get_product_object(){
			
			return $product;
		}
		
		
			/**
	 * Check for 2Checkout IPN Response
	 *
	 * @access public
	 * @return void
	 */
	function twocheckout_response() {
	
		/**
		 * source code: https://github.com/craigchristenson/woocommerce-2checkout-api
		 * Thanks to: https://github.com/craigchristenson
		 */
		global $woocommerce;
		
		// twoco_log($_REQUEST);
		
		$mode = $this->demo ? 'Demo' : 'Live';
		$this->log(__("== INS Response Received - Standard Checkout Method ({$mode}) == ", "2checkout") );
		$this->log( $_REQUEST );
		
		$wc_order_id = '';
		if( !isset($_REQUEST['merchant_order_id']) ) {
			if( !isset($_REQUEST['vendor_order_id']) ) {
				$this->log( '===== NO ORDER NUMBER FOUND =====' );
				exit;
			} else {
				$wc_order_id = $_REQUEST['vendor_order_id'];
			}
		} else {
			
			$wc_order_id = $_REQUEST['merchant_order_id'];
		}
		
		$this->log(" ==== ORDER -> {$wc_order_id} ====");
		
		// echo $wc_order_id;
		$wc_order_id = apply_filters('twoco_order_no_received', $wc_order_id, $_REQUEST);
		
		$wc_order 		= new WC_Order( absint( $wc_order_id ) );
		
		$this->verify_order_by_hash($wc_order_id);
		
		$order_redirect = add_query_arg('twoco','processed', $this->get_return_url( $wc_order ));
		wp_redirect( $order_redirect );
		exit;
	}
	
	
	function verify_order_by_hash($wc_order_id) {

		/**
		 * source code: https://github.com/craigchristenson/woocommerce-2checkout-api
		 * Thanks to: https://github.com/craigchristenson
		 */
		global $woocommerce;
		
		@ob_clean();
		
		$twoco_order_no	= $_REQUEST['order_number'];
		$wc_order 		= wc_get_order( $wc_order_id );
		// $order_total	= isset($_REQUEST['total']) ? $_REQUEST['total'] : '';
		$order_total	= $wc_order->get_total();
		
		if ( isset($_REQUEST['demo']) && $_REQUEST['demo'] == 'Y' ){
			$compare_string = $this->secret_word . $this->seller_id . "1" . $order_total;
		}else{
			$compare_string = $this->secret_word . $this->seller_id . $twoco_order_no . $order_total;
		}
		$compare_hash1 = strtoupper(md5($compare_string));
		
		$compare_hash2 = $_REQUEST['key'];
		if ($compare_hash1 != $compare_hash2) {
			$this->log(" HASH VERIFCAITON FAILED .. {$compare_hash1} == {$compare_hash2}. DON'T WORRY. IPN IS COMING");
		} else {
			$wc_order->add_order_note( sprintf(__('Payment completed via 2Checkout Order Number %d', 'twoco'), $twoco_order_no) );
			// Mark order complete
			$wc_order->payment_complete();
			// Empty cart and clear session
			$woocommerce->cart->empty_cart();
			$this->log( __("HASH matches", 'twoco') );
			$this->log( __("Payment Completed via Standard Checkout ==> {$wc_order_id}", 'twoco') );
		}
	}
		
		
	function get_price($price){
		
		$price = wc_format_decimal($price, 2);
		
		return apply_filters('nm_get_price', $price);
	}
		
	}
	
}


function add_nm_payment_gateway( $methods ) {
	$methods[] = 'WC_Gateway_NM_TwoCheckout';
	return $methods;
}
add_filter( 'woocommerce_payment_gateways', 'add_nm_payment_gateway' );

function twoco_log( $log ) {
	
	if ( true === WP_DEBUG ) {
      if ( is_array( $log ) || is_object( $log ) ) {
          $resp = error_log( print_r( $log, true ), 3, plugin_dir_path(__FILE__).'twoco.log' );
      } else {
          $resp = error_log( $log, 3, plugin_dir_path(__FILE__).'twoco.log' );
      }
      
      var_dump($resp);
  }
}