<?php
defined( 'ABSPATH' ) or die( 'Please!' );

//Styles from the admin settings page
$d_stripe_styles = get_option( 'direct_stripe_styles_settings' );
$maincolor = '#' . $d_stripe_styles['direct_stripe_main_color_style'];
$borderradius = $d_stripe_styles['direct_stripe_border_radius'] . 'px';

$custom_css = "
			.stripe-button-el {
				visibility: hidden !important;
				display: none !important;
			}
			.direct-stripe-button {
				background-color: $maincolor;
				border: 1px solid $maincolor;
				-webkit-border-radius: $borderradius;
				-moz-border-radius: $borderradius;
				-o-border-radius: $borderradius;
				border-radius: $borderradius;
			}
			.direct-stripe-button:hover {
				color: $maincolor;
				border-color: $maincolor;
			}
			#directStripe_answer {
			    -webkit-border-radius: $borderradius;
				-moz-border-radius: $borderradius;
				-o-border-radius: $borderradius;
				border-radius: $borderradius;
			}
			.loadingDS:before  {
			    color: $maincolor;
			}
			.ds-element.in-form {
				  background-color: $maincolor;	
			}
			.ds-element.in-form fieldset {
				-webkit-border-radius: $borderradius;
				-moz-border-radius: $borderradius;
				-o-border-radius: $borderradius;
				border-radius: $borderradius;
			}
			.ds-element.in-form fieldset legend {
				background-color: $maincolor;
			}
			.ds-element.in-form button {
				color: $maincolor;
				-webkit-border-radius: $borderradius;
				-moz-border-radius: $borderradius;
				-o-border-radius: $borderradius;
				border-radius: $borderradius;
			}
			.ds-element.in-form button:active,
			.ds-element.in-form button:hover {
				color: #fff;
				background-color: $maincolor;
			}
			.ds-element.in-form option {
				background-color: $maincolor;
			  }
		";