jQuery(function ($) {
    $("a.single_add_to_cart_button.eh_paypal_express_link").on('click', function ()
    {
        if (eh_express_checkout_params.page_name === "cart")
        {
            $('div.wc-proceed-to-checkout').block({
                message: null,
                overlayCSS: {
                    background: '#fff',
                    opacity: 0.6
                }
            });
        }
    });
    $("span.edit_eh_pe_address").on('click', function ()
    {
        $(".woocommerce-billing-fields p").removeClass("eh_pe_checkout_fields_hide");
        $(".woocommerce-billing-fields p").removeClass("eh_pe_checkout_fields_fill");
        $(".woocommerce-billing-fields .eh_pe_address").hide();
    });
});