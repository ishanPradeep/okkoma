<?php

if (!defined('ABSPATH')) {
    exit;
}
if (!defined('ABSPATH')) {
    exit;
}

$file_size=(file_exists(wc_get_log_file_path('eh_paypal_express_log'))?$this->file_size(filesize(wc_get_log_file_path('eh_paypal_express_log'))):'');

return array(
    'enabled' => array(
        'title' => __('PayPal Payment Gateway', 'express-checkout-paypal-payment-gateway-for-woocommerce'),
        'label' => __('Enable', 'express-checkout-paypal-payment-gateway-for-woocommerce'),
        'type' => 'checkbox',
        'description' => __('This option will enable PayPal payment method in checkout page.', 'express-checkout-paypal-payment-gateway-for-woocommerce'),
        'default' => 'no',
        'desc_tip' => true
    ),
    'title' => array(
        'title' => __('Title', 'express-checkout-paypal-payment-gateway-for-woocommerce'),
        'type' => 'text',
        'description' => __('Enter the title of the checkout which the user can see.', 'express-checkout-paypal-payment-gateway-for-woocommerce'),
        'default' => __('PayPal', 'express-checkout-paypal-payment-gateway-for-woocommerce'),
        'desc_tip' => true
    ),
    'description' => array(
        'title' => __('Regular Description', 'express-checkout-paypal-payment-gateway-for-woocommerce'),
        'type' => 'textarea',
        'css' => 'width:25em',
        'description' => __('Description which the user sees during checkout.', 'express-checkout-paypal-payment-gateway-for-woocommerce'),
        'default' => __('Secure payment via PayPal.', 'express-checkout-paypal-payment-gateway-for-woocommerce'),
        'desc_tip' => true
    ),
    'credentials_title' => array(
        'title' => sprintf(__('<span style="text-decoration: underline;color:brown;">PayPal Credentials<span>', 'express-checkout-paypal-payment-gateway-for-woocommerce')),
        'type' => 'title'
    ),
    'environment' => array(
        'title' => __('Environment', 'express-checkout-paypal-payment-gateway-for-woocommerce'),
        'type' => 'select',
        'class' => 'wc-enhanced-select',
        'options' => array(
            'sandbox' => __('Sandbox Mode', 'express-checkout-paypal-payment-gateway-for-woocommerce'),
            'live' => __('Live Mode', 'express-checkout-paypal-payment-gateway-for-woocommerce')
        ),
        'description' => sprintf(__('<div id="environment_alert_desc"></div>', 'express-checkout-paypal-payment-gateway-for-woocommerce')),
        'default' => 'sandbox'
    ),
    'sandbox_username' => array(
        'title' => __('Sandbox API Username', 'express-checkout-paypal-payment-gateway-for-woocommerce'),
        'type' => 'text',
        'default' => ''
    ),
    'sandbox_password' => array(
        'title' => __('Sandbox API Password', 'express-checkout-paypal-payment-gateway-for-woocommerce'),
        'type' => 'password',
        'default' => ''
    ),
    'sandbox_signature' => array(
        'title' => __('Sandbox API Signature', 'express-checkout-paypal-payment-gateway-for-woocommerce'),
        'type' => 'password',
        'default' => ''
    ),
    'live_username' => array(
        'title' => __('Live API Username', 'express-checkout-paypal-payment-gateway-for-woocommerce'),
        'type' => 'text',
        'default' => ''
    ),
    'live_password' => array(
        'title' => __('Live API Password', 'express-checkout-paypal-payment-gateway-for-woocommerce'),
        'type' => 'password',
        'default' => ''
    ),
    'live_signature' => array(
        'title' => __('Live API Signature', 'express-checkout-paypal-payment-gateway-for-woocommerce'),
        'type' => 'password',
        'default' => ''
    ),
    'express_title' => array(
        'title' => sprintf(__('<span style="text-decoration: underline;color:brown;">PayPal Express Checkout<span>', 'express-checkout-paypal-payment-gateway-for-woocommerce')),
        'type' => 'title'
    ),
    'express_enabled' => array(
        'title' => __('PayPal Express', 'express-checkout-paypal-payment-gateway-for-woocommerce'),
        'label' => __('Enable', 'express-checkout-paypal-payment-gateway-for-woocommerce'),
        'type' => 'checkbox',
        'description' => __('Enable PayPal Express Gateway option.', 'express-checkout-paypal-payment-gateway-for-woocommerce'),
        'default' => 'no',
        'desc_tip' => true
    ),
    'credit_checkout' => array(
        'title' => __('Credit Card Checkout', 'express-checkout-paypal-payment-gateway-for-woocommerce'),
        'type' => 'checkbox',
        'label' => __('Enable', 'express-checkout-paypal-payment-gateway-for-woocommerce'),
        'default' => 'no',
        'description' => __('Check to allow customer to pay using their credit card instead of PayPal Account.', 'express-checkout-paypal-payment-gateway-for-woocommerce'),
        'desc_tip' => true
    ),
    'button_size' => array(
        'title' => __('Button Size', 'express-checkout-paypal-payment-gateway-for-woocommerce'),
        'type' => 'select',
        'class' => 'wc-enhanced-select',
        'description' => __('Select the Button size that fits your shop theme.', 'express-checkout-paypal-payment-gateway-for-woocommerce'),
        'default' => 'medium',
        'desc_tip' => true,
        'options' => array(
            'small' => __('Small', 'express-checkout-paypal-payment-gateway-for-woocommerce'),
            'medium' => __('Medium', 'express-checkout-paypal-payment-gateway-for-woocommerce'),
            'large' => __('Large', 'express-checkout-paypal-payment-gateway-for-woocommerce')
        )
    ),
    'express_description' => array(
        'title' => __('Express Description', 'express-checkout-paypal-payment-gateway-for-woocommerce'),
        'type' => 'textarea',
        'css' => 'width:25em',
        'description' => __('Description which the user sees during PayPal Express.', 'express-checkout-paypal-payment-gateway-for-woocommerce'),
        'default' => __('Reduce multiple click by clicking on PayPal', 'express-checkout-paypal-payment-gateway-for-woocommerce'),
        'desc_tip' => true
    ),
    'express_on_cart_page' => array(
        'title' => __('Cart Page Checkout', 'express-checkout-paypal-payment-gateway-for-woocommerce'),
        'type' => 'checkbox',
        'label' => __('Enable Express', 'express-checkout-paypal-payment-gateway-for-woocommerce'),
        'default' => 'no',
        'description' => __('Allows customers to checkout using PayPal directly from a cart page.', 'express-checkout-paypal-payment-gateway-for-woocommerce'),
        'desc_tip' => true
    ),
    'abilities_title' => array(
        'title' => sprintf(__('<span style="text-decoration: underline;color:brown;">PayPal Abilities<span>', 'express-checkout-paypal-payment-gateway-for-woocommerce')),
        'type' => 'title'
    ),
    'business_name' => array(
        'title' => __('Business Name', 'express-checkout-paypal-payment-gateway-for-woocommerce'),
        'type' => 'text',
        'description' => __('Enter the Business name to display in PayPal Checkout Page.', 'express-checkout-paypal-payment-gateway-for-woocommerce'),
        'default' => __(get_bloginfo('name', 'display'), 'express-checkout-paypal-payment-gateway-for-woocommerce'),
        'desc_tip' => true
    ),
    'payment_action' => array(
        'title' => __('Payment Action', 'express-checkout-paypal-payment-gateway-for-woocommerce'),
        'type' => 'select',
        'class' => 'wc-enhanced-select',
        'options' => array(
            'sale' => __('Sale', 'express-checkout-paypal-payment-gateway-for-woocommerce')
        ),
        'description' => sprintf(__('Select whether you want to capture the payment or not.', 'express-checkout-paypal-payment-gateway-for-woocommerce')),
        'default' => 'sale',
        'desc_tip' => true
    ),
    'paypal_allow_override' => array(
        'title' => __('Override Addresses ', 'express-checkout-paypal-payment-gateway-for-woocommerce'),
        'type' => 'checkbox',
        'label' => __('Do you let buyers override their PayPal addresses? ', 'express-checkout-paypal-payment-gateway-for-woocommerce'),
        'default' => 'no',
        'description' => __('Check to allow buyers override their PayPal addresses. - <i>Enabling this will affect express checkout and PayPal will strictly verify the address</i>', 'express-checkout-paypal-payment-gateway-for-woocommerce'),
        'desc_tip' => false
    ),
    'send_shipping'   => array(
		'title'       => __( 'Shipping details', 'express-checkout-paypal-payment-gateway-for-woocommerce' ),
		'type'        => 'checkbox',
		'label'       => __( 'Send shipping details to PayPal instead of billing.', 'express-checkout-paypal-payment-gateway-for-woocommerce' ),
        'default'     => 'no',
        'description' => __('PayPal allows us to send only one among shipping/billing address. We advise you to enable this option to ensure PayPal Seller protection thereby to send shipping details to PayPal', 'express-checkout-paypal-payment-gateway-for-woocommerce'),
        'desc_tip'    => false,
	),
    'paypal_locale' => array(
        'title' => __('PayPal Locale', 'express-checkout-paypal-payment-gateway-for-woocommerce'),
        'type' => 'checkbox',
        'label' => __('Use Store Locale', 'express-checkout-paypal-payment-gateway-for-woocommerce'),
        'default' => 'yes',
        'description' => __('Check to set your store locale code to PayPal page locale.', 'express-checkout-paypal-payment-gateway-for-woocommerce'),
        'desc_tip' => true
    ),
    'landing_page' => array(
        'title' => __('Landing Page', 'express-checkout-paypal-payment-gateway-for-woocommerce'),
        'type' => 'select',
        'class' => 'wc-enhanced-select',
        'options' => array(
            'login' => __('Login', 'express-checkout-paypal-payment-gateway-for-woocommerce'),
            'billing' => __('Billing', 'express-checkout-paypal-payment-gateway-for-woocommerce')
        ),
        'description' => sprintf(__('PayPal Page which is used to display as default.', 'express-checkout-paypal-payment-gateway-for-woocommerce')),
        'default' => 'billing',
        'desc_tip' => true
    ),
    'customer_service' => array(
        'title' => __('Customer Service Number', 'express-checkout-paypal-payment-gateway-for-woocommerce'),
        'type' => 'text',
        'description' => __('Enter the customer service number which will be displayed in PayPal Review Page.', 'express-checkout-paypal-payment-gateway-for-woocommerce'),
        'default' => __('', 'express-checkout-paypal-payment-gateway-for-woocommerce'),
        'desc_tip' => true
    ),
   
    'checkout_logo' => array(
        'title' => __('PayPal Checkout Logo', 'express-checkout-paypal-payment-gateway-for-woocommerce'),
        'type' => 'text',
        'description' => __('Enter URL of the image to be displayed as logo in PayPal Checkout Page. Image URL should be of SSL Host URL. Use <a href="http://www.sslpic.com" targer="_blank"> SSL Host</a>.', 'express-checkout-paypal-payment-gateway-for-woocommerce') . sprintf('<br><br><img src="%s" width="190px" height="90px" style="cursor:pointer" title="PayPal Checkout Logo">', ('' == $this->get_option('checkout_logo')) ? EH_PAYPAL_MAIN_URL . 'assets/img/nopreview.jpg' : $this->get_option('checkout_logo')),
        'placeholder' => 'Image Size ( 190 x 90 )px'
    ),
    'checkout_banner' => array(
        'title' => __('PayPal Checkout Banner', 'express-checkout-paypal-payment-gateway-for-woocommerce'),
        'type' => 'text',
        'description' => __('Enter URL of the image to be displayed as banner in PayPal Checkout Page. Image URL should be of SSL Host URL. Use <a href="http://www.sslpic.com" targer="_blank"> SSL Host</a>.', 'express-checkout-paypal-payment-gateway-for-woocommerce') . sprintf('<br><br><img src="%s" width="750px" height="90px" style="cursor:pointer" title="PayPal Checkout Banner">', ('' == $this->get_option('checkout_banner')) ? EH_PAYPAL_MAIN_URL . 'assets/img/nopreview.jpg' : $this->get_option('checkout_banner')),
        'placeholder' => 'Image Size ( 750 x 90 )px'
    ),
    'review_title' => array(
        'title' => sprintf(__('<span style="text-decoration: underline;color:brown;">Review Page<span>', 'express-checkout-paypal-payment-gateway-for-woocommerce')),
        'type' => 'title'
    ),
    'skip_review' => array(
        'title' => __('Skip Review Page', 'express-checkout-paypal-payment-gateway-for-woocommerce'),
        'type' => 'checkbox',
        'label' => __('Enable', 'express-checkout-paypal-payment-gateway-for-woocommerce'),
        'default' => 'yes',
        'description' => __('Check to skip the review page for the customer to complete order.', 'express-checkout-paypal-payment-gateway-for-woocommerce'),
        'desc_tip' => true
    ),
    'policy_notes' => array(
        'title' => __('Seller Policy', 'express-checkout-paypal-payment-gateway-for-woocommerce'),
        'type' => 'textarea',
        'css' => 'width:25em',
        'description' => __('Enter the seller protection policy or customized text which will be displayed in order review page.', 'express-checkout-paypal-payment-gateway-for-woocommerce'),
        'default' => __('You are Protected by ' . get_bloginfo('name', 'display') . ' Policy', 'express-checkout-paypal-payment-gateway-for-woocommerce'),
        'desc_tip' => true
    ),
    'log_title' => array(
        'title' => sprintf(__('<span style="text-decoration: underline;color:brown;">Developer Settings<span>', 'express-checkout-paypal-payment-gateway-for-woocommerce')),
        'type' => 'title',
        'description' => sprintf(__('Enable Logging to save PayPal payment logs into log file. <a href="' . admin_url("admin.php?page=wc-status&tab=logs") . '" target="_blank"> Check Now </a>', 'express-checkout-paypal-payment-gateway-for-woocommerce'))
    ),
    'paypal_logging' => array(
        'title' => __('Logging', 'express-checkout-paypal-payment-gateway-for-woocommerce'),
        'label' => __('Enable', 'express-checkout-paypal-payment-gateway-for-woocommerce'),
        'type' => 'checkbox',
        'description' => sprintf(__('<span style="color:green">Log File</span>: ' . strstr(wc_get_log_file_path('eh_paypal_express_log'), 'eh_paypal_express_log') . ' ( ' . $file_size . ' ) ', 'express-checkout-paypal-payment-gateway-for-woocommerce')),
        'default' => 'yes'
    ),
    'ipn_url' => array(
        'title' => __('Override IPN URL', 'express-checkout-paypal-payment-gateway-for-woocommerce'),
        'type' => 'text',
        'description' => __('Enter override IPN URL to capture PayPal IPN Response.', 'express-checkout-paypal-payment-gateway-for-woocommerce'),
        'default' => __('', 'express-checkout-paypal-payment-gateway-for-woocommerce'),
        'placeholder' => 'IPN URL',
        'desc_tip' => true
    )
);

