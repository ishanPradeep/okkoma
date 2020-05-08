<?php
if (!defined('ABSPATH')) {
    exit;
}
class Eh_Paypal_Express_Hooks
{
    protected $eh_paypal_express_options;
    function __construct() {
        $this->eh_paypal_express_options=get_option('woocommerce_eh_paypal_express_settings');
        add_action('woocommerce_proceed_to_checkout',array($this,'eh_express_checkout_hook'),20);
        add_action('wp', array($this, 'unset_express'));
        add_action('woocommerce_cart_emptied', array($this,'unset_expres_cart_empty'));
    }
    public function unset_express()
    {
        if( ( isset($_REQUEST['cancel_express_checkout']) && ($_REQUEST['cancel_express_checkout'] === 'cancel') ) )
        {
            if(isset(WC()->session->eh_pe_billing)){
                unset(WC()->session->eh_pe_billing);
            }
            if(isset(WC()->session->eh_pe_checkout))
            {
                unset(WC()->session->eh_pe_checkout);
                wc_clear_notices();
                wc_add_notice(__('You have cancelled PayPal Express Checkout. Please try to process your order again.', 'express-checkout-paypal-payment-gateway-for-woocommerce'), 'notice');
            }
        }
    }
    public function unset_expres_cart_empty()
    {
        if(isset(WC()->session->eh_pe_billing)){
            unset(WC()->session->eh_pe_billing);
        }
            if(isset(WC()->session->eh_pe_checkout))
            {
                unset(WC()->session->eh_pe_checkout);
            }
    }
    public function express_run()
    {
        if($this->eh_paypal_express_options['enabled']==="yes")
        {            
            $this->check_express();
        }        
    }
    protected function check_express()
    {
        if($this->eh_paypal_express_options['express_enabled']==='yes')
        {
            if (($this->eh_paypal_express_options['express_on_cart_page']==='yes') &&  is_cart()) 
            {
                $this->checkout_button_include();
                $this->eh_payment_scripts();
            }
        }
    }
    public function eh_express_checkout_hook() 
    {
        if ( apply_filters( 'eh_hide_paypal_express_button_in_cart', false ) ) {
			return;
		}
        require_once (EH_PAYPAL_MAIN_PATH . "includes/functions.php");
        eh_paypal_express_hook_init();
    }
    public function checkout_button_include()
    {
        $page= get_page_link();
        $ex_button_output = '<center><div class="eh_payapal_express_checkout_button"><div><small>-- or --</small></div><div class="eh_paypal_express_description" ><small>-- '.$this->eh_paypal_express_options['express_description'].' --</small></div>';      
        $ex_button_output .= '<a href="' . esc_url(add_query_arg('p', $page, $this->make_express_url('express_start'))) . '" class="single_add_to_cart_button eh_paypal_express_link"><input type="image" src="https://www.paypalobjects.com/webstatic/en_US/i/buttons/checkout-logo-'.$this->eh_paypal_express_options['button_size'].'.png" style="width:auto;height:auto;" class=" single_add_to_cart_button eh_paypal_express_image" alt="' . __('Check out with PayPal', 'express-checkout-paypal-payment-gateway-for-woocommerce') . '" /></a>';
        if($this->eh_paypal_express_options['credit_checkout']==='yes')
        {
            $ex_button_output .= '<a href="' . esc_url(add_query_arg('p', $page, $this->make_express_url('credit_start'))) . '" class="single_add_to_cart_button eh_paypal_express_link"><input type="image" src="https://www.paypalobjects.com/webstatic/en_US/i/buttons/ppcredit-logo-'.$this->eh_paypal_express_options['button_size'].'.png" style="width:auto;height:auto;" class=" single_add_to_cart_button eh_paypal_express_image" alt="' . __('Check out with PayPal Credit', 'express-checkout-paypal-payment-gateway-for-woocommerce') . '" /></a>';
        }
        $ex_button_output .= "</div></center>";
        echo $ex_button_output;
    }
    public function make_express_url($action) {
         
        return add_query_arg('c', $action, WC()->api_request_url('Eh_PayPal_Express_Payment'));
    }
    public function eh_payment_scripts()
    {
        if (is_cart()) 
        {
            wp_register_style('eh-express-style', EH_PAYPAL_MAIN_URL . 'assets/css/eh-express-style.css');
            wp_enqueue_style('eh-express-style');
            wp_register_script('eh-express-js', EH_PAYPAL_MAIN_URL . 'assets/js/eh-express-script.js');
            wp_enqueue_script('eh-express-js');
            wp_localize_script('eh-express-js','eh_express_checkout_params', array('page_name' => 'cart'));
        }
    }
}