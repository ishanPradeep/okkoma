<?php
/**
 * REST Calls
 *
 **/

function twoco_register_rest()
{
    // http://nmdevteam.com/ppom/wp-json/twoco/v1/ipn
    register_rest_route('twoco/v1', '/ipn/', array(
        'methods' => 'POST',
        'callback' => 'process_payment'
    ));
}

function process_payment() {

    // twoco_wc_logger($_POST);
    
    $message_type	= isset($_POST['message_type']) ? $_POST['message_type'] : '';
	$sale_id		= isset($_POST['sale_id']) ? $_POST['sale_id'] : '';
	$invoice_id		= isset($_POST['invoice_id']) ? $_POST['invoice_id'] : '';
	$fraud_status	= isset($_POST['fraud_status']) ? $_POST['fraud_status'] : '';
	$order_id       = isset($_POST['vendor_order_id']) ? $_POST['vendor_order_id'] : '';
	
	$order = new WC_Order( $order_id );
	
	if( $order->get_status() == 'processing' ) {
	    twoco_wc_logger(__(" IPN Recieved {$order_id}. But order is already completed with HASH", 'twoco') );
	    exit;
	}
	
    switch( $message_type ) {
			
		case 'ORDER_CREATED':
		    
		    $order->payment_complete();
			$order->add_order_note( sprintf(__('Payment Status Completed with Invoice ID: %d and Sale ID: %d', 'twoco'), $invoice_id, $sale_id) );
			twoco_wc_logger(sprintf(__('Payment Status Completed with Invoice ID: %d', 'twoco'), $invoice_id));
			add_action('twoco_order_completed', $order, $sale_id, $invoice_id);
		break;
		
		case 'FRAUD_STATUS_CHANGED':
			if( $fraud_status == 'fail' ) {
				
				$order->update_status('failed');
				$order->add_order_note(  __("Payment Decliented", 'twoco') );
				twoco_wc_logger( __("Payment Decliented", 'twoco') );
			}
			
		break;
	}
	
	exit;
}

function twoco_wc_logger($message) {
    
    $wc_logger = new WC_Logger();
    $message = is_array($message) ? json_encode($message) : $message;
    $wc_logger->add( 'twocheckout', $message );
}


// AWS SNS HTTP Listner
add_action( 'rest_api_init', 'twoco_register_rest'); // endpoint url