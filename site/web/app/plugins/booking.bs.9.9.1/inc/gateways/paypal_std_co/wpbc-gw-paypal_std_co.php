<?php
/**
 * @package  PayPal Standard Checkout - Integration
 * @category Payment Gateway for Booking Calendar 
 * @author wpdevelop
 * @version 1.0
 * @web-site https://wpbookingcalendar.com/
 * @email info@wpbookingcalendar.com 
 * 
 * @modified 2023-12-05
 *
 * Integration based on PayPal Standard Checkout
 *
 * Based on guide:	https://developer.paypal.com/sdk/js/reference/
 *
 *  	     https://developer.paypal.com/docs/checkout/standard/integrate/
 * 	 video:  https://www.youtube.com/watch?app=desktop&v=Ubl2IT-qZOk
 * PayPal Developer Dashboard: 	https://developer.paypal.com/dashboard/applications/sandbox
 *
 */
//FixIn: 9.8.14.5

/**
 * Testing. Use test card number: 4242 4242 4242 4242,
 * 			any future month and year for the expiration,
 * 			any three-digit number for the CVC, and any random ZIP code.
 */


if ( ! defined( 'ABSPATH' ) ) exit;                                             // Exit if accessed directly
//return;
if ( ! defined( 'WPBC_PAYPAL_STD_CO_GATEWAY_ID' ) )        define( 'WPBC_PAYPAL_STD_CO_GATEWAY_ID', 'paypal_std_co' );


//                                                                              <editor-fold   defaultstate="collapsed"   desc=" Gateway API " >

/** API  for  Payment Gateway  */
class WPBC_Gateway_API_PAYPAL_STD_CO extends WPBC_Gateway_API  {

	/**
	 * Get payment Form
	 * @param string $output    - other active payment forms
	 * @param array $params     - input params                          array (
																				[id] => 514
																				[days_input_format] => 24.05.2019
																				[days_only_sql] => 2019-05-24
																				[dates_sql] => 2019-05-24 00:00:00
																				[check_in_date_sql] => 2019-05-24 00:00:00
																				[check_out_date_sql] => 2019-05-24 00:00:00
																				[dates] => 05/24/2019
																				[check_in_date] => 05/24/2019
																				[check_out_date] => 05/24/2019
																				[check_out_plus1day] => 05/25/2019
																				[dates_count] => 1
																				[days_count] => 1
																				[nights_count] => 1
																				[check_in_date_hint] => 05/24/2019
																				[check_out_date_hint] => 05/24/2019
																				[start_time_hint] => 00:00
																				[end_time_hint] => 00:00
																				[selected_dates_hint] => 05/24/2019
																				[selected_timedates_hint] => 05/24/2019
																				[selected_short_dates_hint] => 05/24/2019
																				[selected_short_timedates_hint] => 05/24/2019
																				[days_number_hint] => 1
																				[nights_number_hint] => 1
																				[siteurl] => http://beta
																				[resource_title] => Apartment#2
																				[bookingtype] => Apartment#2
																				[remote_ip] => 127.0.0.1
																				[user_agent] => Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:66.0) Gecko/20100101 Firefox/66.0
																				[request_url] => http://beta/resource-id3/
																				[current_date] => 04/24/2019
																				[current_time] => 10:19
																				[cost_hint] => CURRENCY_SYMBOL140.00
																				[name] => John
																				[secondname] => Smith
																				[email] => user@beta.com
																				[phone] => test
																				[visitors] => 1
																				[children] => 0
																				[details] => test
																				[term_and_condition] => I Accept term and conditions
																				[booking_resource_id] => 3
																				[resource_id] => 3
																				[type_id] => 3
																				[type] => 3
																				[resource] => 3
																				[content] =>
																								First Name:John
																								Last Name:Smith
																								Email:user@beta.com
																								Phone:test
																								Adults: 1
																								Details:
																								 test
																				[moderatelink] => http://beta/wp-admin/admin.php?page=wpbc&view_mode=vm_listing&tab=actions&wh_booking_id=514
																				[visitorbookingediturl] => http://beta/edit/?booking_hash=d4e19e315f8ed7903e38d1c8b2210356
																				[visitorbookingslisting] => http://beta/list-customer-bookings/?booking_hash=d4e19e315f8ed7903e38d1c8b2210356
																				[visitorbookingcancelurl] => http://beta/edit/?booking_hash=d4e19e315f8ed7903e38d1c8b2210356&booking_cancel=1
																				[visitorbookingpayurl] => http://beta/edit/?booking_hash=d4e19e315f8ed7903e38d1c8b2210356&booking_pay=1
																				[bookinghash] => d4e19e315f8ed7903e38d1c8b2210356
																				[db_cost] => 140.00
																				[db_cost_hint] => CURRENCY_SYMBOL140.00
																				[modification_date] =>  2019-04-24 10:19:23
																				[modification_year] => 2019
																				[modification_month] => 04
																				[modification_day] => 24
																				[modification_hour] => 10
																				[modification_minutes] => 19
																				[modification_seconds] => 23
																				[__form] => text^selected_short_timedates_hint3^05/24/2019~text^nights_number_hint3^1~text^cost_hint3^CURRENCY_SYMBOL140.00~text^name3^John~text^secondname3^Smith~email^email3^user@beta.com~text^phone3^test~select-one^visitors3^1~select-one^children3^0~textarea^details3^test~checkbox^term_and_condition3[]^I Accept term and conditions
																				[__nonce] => 155609396391.65



																				[__booking_form_type] => standard
																				[additional_description] =>
																				[payment_cost] => 140.00
																				[payment_cost_hint] => CURRENCY_SYMBOL140.00
																				[calc_total_cost] => 140.00
																				[calc_cost_hint] => CURRENCY_SYMBOL140.00

																				[calc_deposit_cost] => 140.00
																				[calc_deposit_hint] => CURRENCY_SYMBOL140.00
																				[calc_deposit_cost_hint] => CURRENCY_SYMBOL140.00
																				[calc_balance_cost] => 0.00
																				[calc_balance_hint] => CURRENCY_SYMBOL0.00
																				[calc_balance_cost_hint] => CURRENCY_SYMBOL0.00
																				[calc_original_cost] => 100.00
																				[calc_original_cost_hint] => CURRENCY_SYMBOL100.00
																				[calc_additional_cost] => 40.00
																				[calc_additional_cost_hint] => CURRENCY_SYMBOL40.00
																				[calc_coupon_discount] => 0.00
																				[calc_coupon_discount_hint] => CURRENCY_SYMBOL0.00
																				[payment_form_target] =>
																				[cost_in_gateway] => 140.00



																			)
	 * @return string        - you must  return  in format: return $output . $your_payment_form_content
	 */
	public function get_payment_form( $output, $params, $gateway_id = '' ) {

		// Check  if currently  is showing this Gateway
		if (
				   (  ( ! empty( $gateway_id ) ) && ( $gateway_id !== $this->get_id() )  )      // Do we need to show this Gateway
				|| ( ! $this->is_gateway_on() )                                                 // Payment Gateway does NOT active
		) return $output ;

		////////////////////////////////////////////////////////////////////////
		// Payment Options
		////////////////////////////////////////////////////////////////////////
		$payment_options = wpbc_paypal_std_co__get_client__id_secret_key();		// array( 'paypal_sandbox' => true, 'paypal_client_id' => 'xx..zz', , 'paypal_client_secret' => 'aa..bb' );

		if ( empty( $payment_options['paypal_client_id'] ) ) { 		return 'Wrong configuration in PayPal Standard Checkout Settings.' . ' <em>Empty: "Client ID" option</em>'; }
		if ( empty( $payment_options['paypal_client_secret'] ) ) { 	return 'Wrong configuration in PayPal Standard Checkout Settings.' . ' <em>Empty: "Secret Key" option</em>'; }
		if ( version_compare( PHP_VERSION, '5.6' ) < 0 ) { 			return 'PayPal Standard Checkout payment require PHP version 5.6 or newer!'; }

		// Product Details
		$payment_options['item_number'] = 'WPBC_PP_' . $params['booking_id'];

		$payment_options['item_name'] = get_bk_option( 'booking_paypal_std_co_subject' );                                // 'Payment for booking %s on these day(s): %s'
		$payment_options['item_name'] = apply_bk_filter( 'wpdev_check_for_active_language', $payment_options['item_name'] );
		$payment_options['item_name'] = wpbc_replace_booking_shortcodes( $payment_options['item_name'], $params );
		// Escape quotes
		$payment_options['item_name'] = str_replace( array( '"', "'" ), '', $payment_options['item_name'] );


		$payment_options['item_price']    = floatval( $params['cost_in_gateway'] );
		$payment_options['item_currency'] = get_bk_option( 'booking_paypal_std_co_currency' );                           // 'USD'

		// check here https://developer.paypal.com/sdk/js/reference/#link-color
		switch ( get_bk_option( 'booking_paypal_std_co_button_type') ) {
		    case 'paypal_yellow_v1':
				$payment_options['button_color'] = 'gold';
				break;
		    case 'paypal_blue_v1':
				$payment_options['button_color'] = 'blue';
				break;
		    case 'paypal_silver_v1':
				$payment_options['button_color'] = 'silver';
				break;
		    case 'paypal_white_v1':
				$payment_options['button_color'] = 'white';
				break;
		    case 'paypal_black_v1':
				$payment_options['button_color'] = 'black';
				break;
		    default:
		       $payment_options['button_color'] = 'gold';
		}

		$payment_options['button_shape'] = 'rect';		// 'rect' | 'pill'		-	https://developer.paypal.com/sdk/js/reference/#link-shape

		$payment_options['button_height'] = intval( get_bk_option( 'booking_paypal_std_co_button_height' ) );           //  from 25 to 55		-	https://developer.paypal.com/sdk/js/reference/#link-size
		if ( empty( $payment_options['button_height'] ) ) {
			$payment_options['button_height'] = 43;
		}

		$payment_options['paypal_tax_fee'] = floatval( get_bk_option( 'booking_paypal_std_co_paypal_tax_fee' ) );

		$payment_options['item_tax'] = 0;
		if ( ( ! empty( $payment_options['paypal_tax_fee'] ) ) && ( floatval( $payment_options['paypal_tax_fee'] ) > 0 ) ) {
			$payment_options['item_tax'] = ( ( floatval( $payment_options['paypal_tax_fee'] ) / 100 ) * floatval( $params['cost_in_gateway'] ) );
		}
		$payment_options['item_price'] = round( $payment_options['item_price'], 2 );
		$payment_options['item_tax']   = round( $payment_options['item_tax'], 2 );

		////////////////////////////////////////////////////////////////////////
		// Step 1. Front-End:  Checkout
		////////////////////////////////////////////////////////////////////////
		/**
			   Important !!! Do  not use in this script,  comments like  "// Some comment",  instead of that  use comments like "/ *  some comment * /"
			   Its because, during payment request,  all  this script become script in one ROW,  and all  JavaScript after // become commented !!!
				//FixIn: 8.5.1.2
			*/

		ob_start();
		?><div class="paypal_std_co_div wpbc-replace-ajax wpbc-payment-form"><?php
			?><div class="wpbc_paypal_std_co">
				<div id="wpbc_paypal_std_co_button_container"></div>
				<?php
					if ( $payment_options['item_tax'] > 0 ) {
						echo "<div class='wpbc_paypal_fee'> ("
								  . __( 'PayPal fee', 'booking' ) . ' '
								  . wpbc_cost_show(  $payment_options['item_tax'] , array(  'currency' => wpbc_get_currency() )  )
							 . ")</div>";
					}
				?>
			</div><?php
			if ( 1 ) {
				?><div style="display:none;"><?php	//FixIn: 8.5.1.2

				$paypal_url = 'https://www.paypal.com/sdk/js?client-id=' . $payment_options['paypal_client_id'];
				$paypal_url .= '&currency=' . $payment_options['item_currency'];

				// https://developer.paypal.com/sdk/js/configuration/#link-intent
				// $paypal_url .= '&intent=' . $payment_options['intent'];					//  'capture' (default)  |  'authorize' 		|other: 'subscription' | 'tokenize' |

				// https://developer.paypal.com/sdk/js/configuration/#link-vault		- Whether the payment information in the transaction will be saved.
				// $paypal_url .= '&vault=true';

				?><ajax_script><?php if(0){ ?><script><?php } ?>
					wpbc__spin_loader__mini__show( 'wpbc_paypal_std_co_button_container', '#0071ce' );
					wpbc_load_js_async( "<?php echo $paypal_url; ?>",  function (){
							paypal.Buttons({
									style: {																			<?php /* Button Styles */ ?>
											color : '<?php echo $payment_options['button_color'];			// gold ?>',
											shape : '<?php echo $payment_options['button_shape'];			// rect ?>',
											label : 'paypal',
											height: <?php echo intval( $payment_options['button_height'] );	// 43 ?>
											/*, layout: 'horizontal' */
									},
    								onInit( data, actions )  {									<?php // call on btn first render - https://developer.paypal.com/sdk/js/reference/#link-oninitonclick  ?>
										wpbc__spin_loader__mini__hide( 'wpbc_paypal_std_co_button_container' );
    								},
									createOrder: function(data, actions){		 										<?php /* Sets up the transaction when a payment button is clicked. - https://developer.paypal.com/docs/api/orders/v2/ */  ?>
										return actions.order.create( {
											"purchase_units": [{														<?php // Doc: https://developer.paypal.com/docs/api/orders/v2/#orders_create!path=purchase_units&t=request ?>
												"custom_id"  : "<?php echo substr( $payment_options['item_number'], 0, 127 ); ?>",
												"description": "<?php echo substr( $payment_options['item_name'], 0, 127 ); ?>",
												"amount"     : {
																"currency_code": "<?php echo $payment_options['item_currency']; ?>",
																"value"        : <?php  echo ( $payment_options['item_price'] + $payment_options['item_tax'] ); ?>,
																"breakdown"    : {
																					"item_total": {
																									"currency_code": "<?php echo $payment_options['item_currency']; ?>",
																									"value"        : <?php echo $payment_options['item_price']; ?>
																								}
																					<?php if ( $payment_options['item_tax'] > 0 ) {		/* https://developer.paypal.com/docs/api/orders/v2/#orders_create!path=purchase_units/amount/breakdown/tax_total&t=request */  ?>
																					, "tax_total": {
																								"currency_code": "<?php echo $payment_options['item_currency']; ?>",
																								"value"        : <?php echo $payment_options['item_tax']; ?>
																							}
																					<?php } ?>
																			     }
															   },
												"items"		 : [
																	{
																		"name"       : "<?php echo substr( $payment_options['item_name'], 0, 127 ); ?>",
																		"description": "<?php echo substr( $payment_options['item_name'], 0, 127 ); ?>",
																		"unit_amount": {
																							"currency_code": "<?php echo $payment_options['item_currency']; ?>",
																							"value"        : <?php echo $payment_options['item_price']; ?>
																						},
																		<?php if ( $payment_options['item_tax'] > 0 ) {  ?>
																		"tax": {
																							"currency_code": "<?php echo $payment_options['item_currency']; ?>",
																							"value"        : <?php echo $payment_options['item_tax']; ?>
																						},
																		<?php } ?>
																		"quantity"   : "1",
																		"category"   : "DIGITAL_GOODS"					<?php /* https://developer.paypal.com/docs/api/orders/v2/#definition-item */ ?>
																	},
															   ]
											}]
										<?php
											//echo ', "intent": "CAPTURE"';			// CAPTURE | AUTHORIZE		// https://developer.paypal.com/docs/api/orders/v2/#orders_create!path=intent&t=request
										?>
										});
									},
									/* Finalize the transaction after payer approval */
									onApprove: function ( data, actions ){
										return actions.order.capture().then( function ( orderData ){
											wpbc_paypal_std_co_set_processing( true , '#73983c' );
											<?php
												$hash_approve = wpbc_get_secret_hash( array( 'payment', WPBC_PAYPAL_STD_CO_GATEWAY_ID, $params['bookinghash'], 'approve' ) );
												$success_url  = wpbc_get_1way_hash_url( $hash_approve );
											?>
											window.location.href = '<?php echo  $success_url ; ?>&paypal_order_check=1&order_id=' + orderData.id;
											<?php
											/*
											var postData = { paypal_order_check: 1, order_id: orderData.id };
											fetch( '<?php echo  $success_url ; ?>', {
												method : 'POST',
												headers: {'Accept': 'application/json'},
												body   : wpbc_paypal_std_co_encode_form_data( postData )
											} )
											.then( function ( response ){
												return  response.json();
											} )
											.then( function ( result ){
												window.location.href = result.url;
														if ( result.status == 1 ){
															window.location.href = "payment-status.php?checkout_ref_id=" + result.ref_id;
														} else {
															var messageContainer = document.querySelector( "#paymentResponse" );
															messageContainer.classList.remove( "hidden" );
															messageContainer.textContent = result.msg;
															setTimeout( function (){
																						messageContainer.classList.add( "hidden" );
																						messageText.textContent = "";
																					}, 5000 );
														}
														wpbc_paypal_std_co_set_processing( false );
											} )
											.catch( function ( error ){
												console.log( 'Error IN wpbc-gw-paypal_std_co.php: ', error );
											} );
											*/
											?>
										} );
									},
									onCancel: function ( data ){
										console.log( 'WPBC PAYPAL_STD_CO :: onCancel :: ',  data );
										return;
										wpbc_paypal_std_co_set_processing( true, '#C60B0B' );
											<?php
												$hash_approve = wpbc_get_secret_hash( array( 'payment', WPBC_PAYPAL_STD_CO_GATEWAY_ID, $params['bookinghash'], 'decline' ) );
												$success_url  = wpbc_get_1way_hash_url( $hash_approve );
											?>
										window.location.href = '<?php echo  $success_url ; ?>&paypal_order_check=2&order_id=' + data.orderID;
										<?php /*  ?>
										console.log( 'onCancel', data );
										var postData = { paypal_order_check: 1, order_id: data.orderID };
										fetch( '<?php echo  $success_url ; ?>', {
												method : 'POST',
												headers: {'Accept': 'application/json'},
												body   : wpbc_paypal_std_co_encode_form_data( postData )
										} )
										.then( function ( response ){
											return response.json();
										} )
										.then( function ( result ){
											window.location.href = result.url;
										} )
										.catch( function ( error ){
											console.log( 'Error IN wpbc-gw-paypal_std_co.php: ', error );
										} );
										<?php */ ?>
									},
									onError: function ( err, actions ){
										console.log( 'WPBC PAYPAL_STD_CO :: onError :: ', err, actions );
								    }
								}).render('#wpbc_paypal_std_co_button_container');										/* PayPal Button Render */
					});																									/* Late Load PayPal button to render, after loading of PayPal Script */
					var wpbc_paypal_std_co_encode_form_data = function ( data ){
								var form_data = new FormData();
								for ( var key in data ){
									form_data.append( key, data[ key ] );
								}
								return form_data;
							};
					/* Show a loader on payment form processing */
					var wpbc_paypal_std_co_set_processing = function ( isProcessing, color ){
								if ( isProcessing ){
									jQuery( '#wpbc_paypal_std_co_button_container .paypal-buttons' ).hide();
									wpbc__spin_loader__mini__show( 'wpbc_paypal_std_co_button_container', color );
								} else {
									jQuery( '#wpbc_paypal_std_co_button_container .paypal-buttons' ).show();
									wpbc__spin_loader__mini__hide( 'wpbc_paypal_std_co_button_container' );
								}
							};
				<?php if(0){ ?></script><?php } ?></ajax_script>
				<?php
				?></div><?php
			}

		?></div><?php

		$payment_form = ob_get_clean();

		return $output . $payment_form;
	}


	/** Define settings Fields  */
	public function init_settings_fields() {

		$this->fields = array();

		// On | Off
		$this->fields['is_active'] = array(
									  'type'        => 'checkbox'
									, 'default'     => 'On'
									, 'title'       => __( 'Enable / Disable', 'booking' )
									, 'label'       => __( 'Enable this payment gateway', 'booking')
									, 'description' => ''
									, 'group'       => 'general'
								);

		// Switcher accounts - Test | Live
		$this->fields['account_mode'] = array(
									  'type' 		=> 'radio'
									, 'default' 	=> 'test'
									, 'title' 		=> __( 'Chose payment account', 'booking' )
									, 'description' => ''//__( 'Select TEST for the Test Server and LIVE in the live environment', 'booking' )
									, 'description_tag' => 'span'
									, 'css' 		=> ''
									, 'options' => array(
											 'test' => array( 'title' => __( 'Sandbox', 'booking' ), 'attr' => array( 'id' => 'paypal_std_co_mode_test' ) )
											,'live' => array( 'title' => __( 'Live', 'booking' ),    'attr' => array( 'id' => 'paypal_std_co_mode_live' ) )
										)
									, 'group' 		=> 'general'
		);

		// Client ID
		$this->fields['client_id'] = array(
									  'type'        => 'text'
									, 'default'     => ( wpbc_is_this_demo() ? 'AZ0lAsdIUYmp97nKwmIUpSQKOU7MIi8gAlk7qRMiv4RdfyVg8bBT5psT2xtwE9x72kemovBoh-pjvfK4' : '' )
									//, 'placeholder' => ''
									, 'title'       => __('Client ID', 'booking')
									, 'description' => __('Required', 'booking') . '. 	'
													   . sprintf( __('This parameter have to assigned to you by %s' ,'booking'), 'PayPal Standard Checkout' )
													   . ( ( wpbc_is_this_demo() ) ? wpbc_get_warning_text_in_demo_mode() : '' )
									, 'description_tag' => 'span'
									, 'css'         => ''//'width:100%'
									, 'group'       => 'general'
									, 'tr_class'    => 'wpbc_sub_settings_grayed wpbc_sub_settings_mode_live'
									//, 'validate_as' => array( 'required' )
							);
		// Secret Key
		$this->fields['secret_key'] = array(
									  'type'        => 'text'
									, 'default'     => ( wpbc_is_this_demo() ? 'EH4qu4EmnPQ1SF-_ENqSBudDC7xRrpeJlR3fVj2olfYOF8VcKwGj3XKaLRRGH4cLfT05GD4EAsjGVIBz' : '' )
									//, 'placeholder' => ''
									, 'title'       => __('Secret key', 'booking')
									, 'description' => __('Required', 'booking') . '. '
													   . sprintf( __( 'This parameter have to assigned to you by %s' ,'booking'), 'PayPal Standard Checkout' )
													   . ( ( wpbc_is_this_demo() ) ? wpbc_get_warning_text_in_demo_mode() : '' )
									, 'description_tag' => 'span'
									, 'css'         => ''//'width:100%'
									, 'group'       => 'general'
									, 'tr_class'    => 'wpbc_sub_settings_grayed wpbc_sub_settings_mode_live'
									//, 'validate_as' => array( 'required' )
							);

	  	// Client ID
		$this->fields['client_id_test'] = array(
									  'type'        => 'text'
									, 'default'     => ( wpbc_is_this_demo() ? 'AZ0lAsdIUYmp97nKwmIUpSQKOU7MIi8gAlk7qRMiv4RdfyVg8bBT5psT2xtwE9x72kemovBoh-pjvfK4' : '' )
									//, 'placeholder' => ''
									, 'title'       => __('Client ID', 'booking') . ' (' . __( 'Sandbox', 'booking' ) . ')'
									, 'description' => __('Required', 'booking') . '. '
													   . sprintf( __('This parameter have to assigned to you by %s' ,'booking'), 'PayPal Standard Checkout' )
													   . ( ( wpbc_is_this_demo() ) ? wpbc_get_warning_text_in_demo_mode() : '' )
									, 'description_tag' => 'span'
									, 'css'         => ''//'width:100%'
									, 'group'       => 'general'
									, 'tr_class'    => 'wpbc_sub_settings_grayed wpbc_sub_settings_mode_test'
									//, 'validate_as' => array( 'required' )
							);
		// Secret Key
		$this->fields['secret_key_test'] = array(
									  'type'        => 'text'
									, 'default'     => ( wpbc_is_this_demo() ? 'EH4qu4EmnPQ1SF-_ENqSBudDC7xRrpeJlR3fVj2olfYOF8VcKwGj3XKaLRRGH4cLfT05GD4EAsjGVIBz' : '' )
									//, 'placeholder' => ''
									, 'title'       => __('Secret key', 'booking') . ' (' . __( 'Sandbox', 'booking' ) . ')'
									, 'description' => __('Required', 'booking') . '. '
													   . sprintf( __( 'This parameter have to assigned to you by %s' ,'booking'), 'PayPal Standard Checkout' )
													   . ( ( wpbc_is_this_demo() ) ? wpbc_get_warning_text_in_demo_mode() : '' )
									, 'description_tag' => 'span'
									, 'css'         => ''//'width:100%'
									, 'group'       => 'general'
									, 'tr_class'    => 'wpbc_sub_settings_grayed wpbc_sub_settings_mode_test'
									//, 'validate_as' => array( 'required' )
							);

		// Currency		// more here: https://developer.paypal.com/sdk/js/configuration/#link-currency
		$currency_list = array(
								'USD' => __( 'U.S. Dollars', 'booking' ),
								'EUR' => __( 'Euros', 'booking' ),
								'GBP' => __( 'Pounds Sterling', 'booking' ),
								'CAD' => __( 'Canadian Dollars', 'booking' ),

								'AUD' => 'Australian Dollar',
								'BRL' => 'Brazilian real',
								'CZK' => 'Czech koruna',
								'DKK' => 'Danish krone',
								'HKD' => 'Hong Kong dollar',
								'HUF' => 'Hungarian forint',
								'ILS' => 'Israeli new shekel',
								'JPY' => 'Japanese yen',
								'MYR' => 'Malaysian ringgit',
								'MXN' => 'Mexican peso',
								'TWD' => 'New Taiwan dollar',
								'NZD' => 'New Zealand dollar',
								'NOK' => 'Norwegian krone',
								'PHP' => 'Philippine peso',
								'PLN' => 'Polish złoty',
								'RUB' => 'Russian ruble',
								'SGD' => 'Singapore dollar',
								'SEK' => 'Swedish krona',
								'CHF' => 'Swiss franc',
								'THB' => 'Thai baht'
							);
		$this->fields['currency'] = array(
									  'type'    => 'select'
									, 'default' => 'USD'
									, 'title'   => __('Accepted Currency', 'booking')
									, 'description' => __('The currency code that gateway will process the payment in.', 'booking')
									, 'description_tag' => 'span'
									, 'css' => ''
									, 'options' => $currency_list
									, 'group' => 'general'
							);

        // Payment Button
        $field_options = array(
                                'paypal_yellow_v1' 	=> array(  'title' => '<span class="wpbc_container" id="paypal_button_type_1_custom_yellow"><span class="wpbc_button_light wpbc_button_gw wpbc_button_gw_paypal wpbc_button_gw_paypal_yellow"></span></span>'
                                                            , 'attr' => array( 'id' => 'paypal_button_type_1' ) , 'html' => '' /*'<br/>'*/)
                              , 'paypal_blue_v1' 	=> array(  'title' =>  '<span class="wpbc_container" id="paypal_button_type_2_custom_blue"><span class="wpbc_button_light wpbc_button_gw wpbc_button_gw_paypal wpbc_button_gw_paypal_blue"></span></span>'
                                                            , 'attr' => array( 'id' => 'paypal_button_type_2' , 'style'=>'vertical-align: middle;') )
                              , 'paypal_silver_v1' 	=> array(  'title' =>  '<span class="wpbc_container" id="paypal_button_type_3_custom_silver"><span class="wpbc_button_light wpbc_button_gw wpbc_button_gw_paypal wpbc_button_gw_paypal_silver"></span></span>'
                                                            , 'attr' => array( 'id' => 'paypal_button_type_3' , 'style'=>'vertical-align: middle;') )
                              , 'paypal_white_v1' 	=> array(  'title' =>  '<span class="wpbc_container" id="paypal_button_type_4_custom_white"><span class="wpbc_button_light wpbc_button_gw wpbc_button_gw_paypal wpbc_button_gw_paypal_white"></span></span>'
                                                            , 'attr' => array( 'id' => 'paypal_button_type_4' , 'style'=>'vertical-align: middle;') )
                              , 'paypal_black_v1' 	=> array(  'title' =>  '<span class="wpbc_container" id="paypal_button_type_5_custom_black"><span class="wpbc_button_light wpbc_button_gw wpbc_button_gw_paypal wpbc_button_gw_paypal_black"></span></span>'
                                                            , 'attr' => array( 'id' => 'paypal_button_type_5' , 'style'=>'vertical-align: middle;') )

                            );
        $this->fields['button_type'] = array(
                                    'type'          => 'radio'
                                    , 'default'     => 'paypal_yellow_v1'
        							, 'title' 		=> __( 'Payment button type', 'booking' )
                                    , 'description' => ''
                                    , 'options'     => $field_options
                                    , 'group'       => 'general'
                            );

		// Button Height: 		https://developer.paypal.com/sdk/js/reference/#link-size
	    $field_options = array();
	    foreach ( range( 25, 55, 1) as $value ) {
		    $field_options[ $value ] = ( ( 43 === $value ) ? ( $value . ' (' . strtolower( __( 'Default', 'booking' ) ) . ')' ) : $value );
	    }
        $this->fields['button_height'] = array(
                                    'type'          => 'select'
                                    , 'default'     => 43
                                    , 'title'       => __('Payment button height' ,'booking')
                                    , 'description' => ''
                                    , 'options'     => $field_options
                                    , 'group'       => 'general'
                            );

		//$this->fields['description_hr'] = array( 'type' => 'hr' );

		// Additional settings /////////////////////////////////////////////////
		$this->fields['subject'] = array(
								'type'          => 'textarea'
								, 'default'     => sprintf(__('Payment for booking %s on these day(s): %s'  ,'booking'),'[resource_title]','[dates]')
								, 'placeholder' => sprintf(__('Payment for booking %s on these day(s): %s'  ,'booking'),'[resource_title]','[dates]')
								, 'title'       => __('Payment description at gateway website' ,'booking')
								, 'description' => sprintf(__('Enter the service name or the reason for the payment here.' ,'booking'),'<br/>','</b>')
													. '<br/>' .  __('You can use any shortcodes, which you have used in content of booking fields data form.' ,'booking')
													// . '<div class="wpbc-settings-notice notice-info" style="text-align:left;"><strong>'
													//    . __('Note:' ,'booking') . '</strong> '
													//    . sprintf( __('This field support only up to %s characters by payment system.' ,'booking'), '255' )
													//. '</div>'
								,'description_tag' => 'p'
								, 'css'         => 'width:100%'
								, 'rows' => 2
								, 'group'       => 'general'
								, 'tr_class'    => 'wpbc_sub_settings_is_description_show wpbc_sub_settings_grayedNO'
						);

        $this->fields['paypal_tax_fee'] = array(
                                  'type'          => 'text'
                                , 'default'     => ''
                                , 'placeholder' => 2
                                , 'title'       => __('PayPal Fee', 'booking')
                                , 'description' => '<span style="font-size: 1.1em;font-weight: 600;margin-left: -0.5em;">%</span>'
									. '<p>'
									. sprintf(__('If you need to add %sPayPal tax fee%s payment (only for PayPal payment system), then enter amount of tax fee in percents' ,'booking'),'<strong>','</strong>')
									. '</p>'
                                , 'description_tag' => 'span'
                                , 'css'         => 'width:5em;'
                                , 'tr_class'    => ''
                                //, 'validate_as' => array( 'required' )
                                , 'tr_class'    => ''
                                , 'group'       => 'general'
                        );



		$this->fields['paypal_account_help'] = array(
                                    'type' => 'help'
                                    , 'value' => array()
                                    , 'cols' => 2
                                    , 'group' => 'account_help'
                            );

        $this->fields['paypal_account_help']['value'][] = '<strong>' .   __('Follow these steps to configure it:' ,'booking') . '</strong>';
        $this->fields['paypal_account_help']['value'][] = '1. ' . __('Log in to your PayPal account Dashboard.' ,'booking');
        $this->fields['paypal_account_help']['value'][] = '2. ' . __('Click the "Apps & Credentials".' ,'booking');
        $this->fields['paypal_account_help']['value'][] = '3. ' . __('Click "Create App" button, enter name of your application and click on "Create App" button.' ,'booking');
        $this->fields['paypal_account_help']['value'][] = '4. ' . __('Under "API credentials" copy "Client ID" and "Secret Key" fields.' ,'booking');
        $this->fields['paypal_account_help']['value'][] = '5. ' . __('Paste these values to appropriate fields at this page.' ,'booking');
		$this->fields['paypal_account_help']['value'][] = '<br> ' . sprintf( __('Find more information at %sFAQ page%s.' ,'booking')
																, '<a href="https://wpbookingcalendar.com/faq/setup-paypal/">'
																,'</a>'
															);



		////////////////////////////////////////////////////////////////////
		// Return URL    &   Auto approve | decline
		////////////////////////////////////////////////////////////////////

		//  Success URL
		$this->fields['order_successful_prefix'] = array(
								'type'          => 'pure_html'
								, 'group'       => 'auto_approve_cancel'
								, 'html'        => '<tr valign="top" class="wpbc_tr_paypal_std_co_order_successful">
														<th scope="row">'.
															WPBC_Settings_API::label_static( 'paypal_std_co_order_successful'
																, array(   'title'=> __('Return URL after Successful order' ,'booking'), 'label_css' => '' ) )
														.'</th>
														<td><fieldset>' . '<code style="font-size:14px;">' .  get_option('siteurl') . '</code>'
								, 'tr_class'    => 'relay_response_sub_class'
						);
		$this->fields['order_successful'] = array(
								'type'          => 'text'
								, 'default'     => '/successful'
								, 'placeholder' => '/successful'
								, 'css'         => 'width:75%'
								, 'group'       => 'auto_approve_cancel'
								, 'only_field'  => true
								, 'tr_class'    => 'relay_response_sub_class'
						);
		$this->fields['order_successful_sufix'] = array(
								'type'          => 'pure_html'
								, 'group'       => 'auto_approve_cancel'
								, 'html'        =>    '<p class="description" style="line-height: 1.7em;margin: 0;">'
														. __('The URL where visitor will be redirected after completing payment.' ,'booking')
														. '<br/>' . sprintf( __('For example, a URL to your site that displays a %s"Thank you for the payment"%s.' ,'booking'),'<b>','</b>')
													. '</p>
														   </fieldset>
														</td>
													</tr>'
								, 'tr_class'    => 'relay_response_sub_class'
						);

		//  Failed URL
		$this->fields['order_failed_prefix'] = array(
								'type'          => 'pure_html'
								, 'group'       => 'auto_approve_cancel'
								, 'html'        => '<tr valign="top" class="wpbc_tr_paypal_std_co_order_failed">
														<th scope="row">'.
															WPBC_Settings_API::label_static( 'paypal_std_co_order_failed'
																, array(   'title'=> __('Return URL after Failed order' ,'booking'), 'label_css' => '' ) )
														.'</th>
														<td><fieldset>' . '<code style="font-size:14px;">' .  get_option('siteurl') . '</code>'
								, 'tr_class'    => 'relay_response_sub_class'
						);
		$this->fields['order_failed'] = array(
								'type'          => 'text'
								, 'default'     => '/failed'
								, 'placeholder' => '/failed'
								, 'css'         => 'width:75%'
								, 'group'       => 'auto_approve_cancel'
								, 'only_field'  => true
								, 'tr_class'    => 'relay_response_sub_class'
						);
		$this->fields['order_failed_sufix'] = array(
								'type'          => 'pure_html'
								, 'group'       => 'auto_approve_cancel'
								, 'html'        =>    '<p class="description" style="line-height: 1.7em;margin: 0;">'
														. __('The URL where the visitor will be redirected after completing payment.' ,'booking')
														. '<br/>' . sprintf( __('For example, the URL to your website that displays a %s"Payment Canceled"%s page.' ,'booking'),'<b>','</b>' )
													. '</p>
														   </fieldset>
														</td>
													</tr>'
								, 'tr_class'    => 'relay_response_sub_class'
						);
		// Auto Approve / Cancel
        $this->fields['is_auto_approve_cancell_booking'] = array(
                                      'type'        => 'checkbox'
                                    , 'default'     => 'Off'
                                    , 'title'       => __( 'Automatically approve/cancel booking', 'booking' )
                                    , 'label'       => __('Check this box to automatically approve bookings, when visitor makes a successful payment, or automatically cancel the booking, when visitor makes a payment cancellation.' ,'booking')
                                    , 'description' =>  ''
                                    , 'description_tag' => 'p'
                                    , 'group'       => 'auto_approve_cancel'
							        , 'tr_class'    => 'relay_response_sub_class'
                                );

	}

    
    // Support /////////////////////////////////////////////////////////////////

	/**
	 * Return info about Gateway
	 *
	 * @return array        Example: array(
											'id'      => 'paypal_std_co
										  , 'title'   => 'PayPal Standard Checkout'
										  , 'currency'   => 'USD'
										  , 'enabled' => true
										);
	 */
	public function get_gateway_info() {

		$gateway_info = array(
					  'id'       => $this->get_id()
					, 'title'    => 'PayPal Standard Checkout'
					, 'currency' => get_bk_option(  'booking_' . $this->get_id() . '_' . 'currency' )
					, 'enabled'  => $this->is_gateway_on()
		);
		return $gateway_info;
	}

    
    /**
 	 * Get payment Statuses of gateway
     * 
     * @return array
     */
    public function get_payment_status_array() {
        
        return array(
                          'ok'      => array( 'PayPal_STD_CO:OK' )
                        , 'pending' => array( 'PayPal_STD_CO:Pending' )
                        , 'unknown' => array( 'PayPal_STD_CO:Unknown' )
                        , 'error'   => array(   'PayPal_STD_CO:Failed',
												'PayPal_STD_CO:REJECTED',
												'PayPal_STD_CO:NOTAUTHED',
												'PayPal_STD_CO:MALFORMED',
												'PayPal_STD_CO:INVALID',
												'PayPal_STD_CO:ABORT',
												'PayPal_STD_CO:ERROR' )
                    ); 
    }


}


	/**
	 * Convert cost back from CENTS (in PayPal Standard Checkout)  to  usual plugin cost		5000	=>	50.00
	 *
	 * @param int $paypal_std_co_amount_in_cents
	 * @param string $currency
	 *
	 * @return float
	 */
	function wpbc_paypal_std_co__amount_in_plugin( $paypal_std_co_amount_in_cents, $currency ) {

		return floatval( $paypal_std_co_amount_in_cents  );
	}


	/**
	 * Get PayPal 	init arr: 	[ 'paypal_sandbox' |  'paypal_client_id'  |  'paypal_client_secret'  ]
	 *
	 * @return array -  array( 'paypal_sandbox' => true, 'paypal_client_id' => 'xx..zz', , 'paypal_client_secret' => 'aa..bb' );
	 */
	function wpbc_paypal_std_co__get_client__id_secret_key() {

		$payment_options = array();

		if ( wpbc_is_this_demo() ) {
			$payment_options['paypal_sandbox'] = true;
		} else {
			$payment_options['paypal_sandbox'] = ( 'live' !== get_bk_option( 'booking_paypal_std_co_account_mode' ) );        // 'live' | 'test'		// true = Sandbox | false = Live
		}

		if ( $payment_options['paypal_sandbox'] ) {
			// Sandbox
			$payment_options['paypal_client_id']     = get_bk_option( 'booking_paypal_std_co_client_id_test' );// 'AZ0lAsdIUYmp97nKwmIUpSQKOU7MIi8gAlk7qRMiv4RdfyVg8bBT5psT2xtwE9x72kemovBoh-pjvfK4';
			$payment_options['paypal_client_secret'] = get_bk_option( 'booking_paypal_std_co_secret_key_test' );//'EH4qu4EmnPQ1SF-_ENqSBudDC7xRrpeJlR3fVj2olfYOF8VcKwGj3XKaLRRGH4cLfT05GD4EAsjGVIBz';
		} else {
			// Live
			$payment_options['paypal_client_id']     = get_bk_option( 'booking_paypal_std_co_client_id' );
			$payment_options['paypal_client_secret'] = get_bk_option( 'booking_paypal_std_co_secret_key' );
		}

		return $payment_options;
	}

//                                                                              </editor-fold>



//                                                                              <editor-fold   defaultstate="collapsed"   desc=" Settings  Page " >

/** Settings  Page  */
class WPBC_Settings_Page_Gateway_PAYPAL_STD_CO extends WPBC_Page_Structure {

		public $gateway_api = false;

		/**
		 * Define interface for  Gateway  API
		 *
		 * @param string $selected_email_name - name of Email template
		 * @param array $init_fields_values - array of init form  fields data - this array  can  ovveride "default" fields and loaded data.
		 * @return object Email API
		 */
		public function get_api( $init_fields_values = array() ){

			if ( $this->gateway_api === false ) {
				$this->gateway_api = new WPBC_Gateway_API_PAYPAL_STD_CO( WPBC_PAYPAL_STD_CO_GATEWAY_ID , $init_fields_values );
			}

			return $this->gateway_api;
		}


		public function in_page() {                                                 // P a g e    t a g
			if (
				   ( 'On' == get_bk_option( 'booking_super_admin_receive_regular_user_payments' ) )								//FixIn: 9.2.3.8
				&& ( ! wpbc_is_mu_user_can_be_here( 'only_super_admin' ) )
				// && ( ! wpbc_is_current_user_have_this_role('contributor') )
			){
				return (string) rand( 100000, 1000000 );        // If this User not "super admin",  then  do  not load this page at all
			}

			return 'wpbc-settings';
		}


		public function tabs() {                                                    // T a b s      A r r a y

			$tabs = array();

			$subtabs = array();

			// Checkbox Icon, for showing in toolbar panel does this payment system active
			$is_data_exist = get_bk_option( 'booking_'. WPBC_PAYPAL_STD_CO_GATEWAY_ID .'_is_active' );
			if (  ( ! empty( $is_data_exist ) ) && ( $is_data_exist == 'On' )  )
				$icon = '<i class="menu_icon icon-1x wpbc_icn_check_circle_outline"></i> &nbsp; ';
			else
				$icon = '<i class="menu_icon icon-1x wpbc_icn_radio_button_unchecked"></i> &nbsp; ';

			$subtabs[ WPBC_PAYPAL_STD_CO_GATEWAY_ID ] = array(
								  'type'   => 'subtab'                                  // Required| Possible values:  'subtab' | 'separator' | 'button' | 'goto-link' | 'html'
								, 'title'  => $icon . 'PayPal' .  '<span class="wpbc_new_label" style="float:none;padding:0 0 0 2em;">' . __( 'New', 'booking' ) . '</span> '        // Title of TAB
								, 'page_title' => sprintf( __('%s Settings', 'booking'), 'PayPal Standard Checkout' ) .  ' <sup style="color:#7812bd;"><strong>&#946;eta</strong></sup>' // Title of Page
								, 'hint' => sprintf( __('Integration of %s payment system' ,'booking' ), 'PayPal Standard Checkout' )    // Hint
								, 'link' => ''                                      // link
								, 'position' => ''                                  // 'left'  ||  'right'  ||  ''
								, 'css_classes' => ''                               // CSS class(es)
								//, 'icon' => 'http://.../icon.png'                 // Icon - link to the real PNG img
								//, 'font_icon' => 'wpbc_icn_mail_outline'   // CSS definition of Font Icon
								, 'header_font_icon' => 'wpbc_icn_payment'   // CSS definition of Font Icon			//FixIn: 9.6.1.4
								, 'default' =>  false                                // Is this sub tab activated by default or not: true || false.
								, 'disabled' => false                               // Is this sub tab deactivated: true || false.
								, 'checkbox'  => false                              // or definition array  for specific checkbox: array( 'checked' => true, 'name' => 'feature1_active_status' )   //, 'checkbox'  => array( 'checked' => $is_checked, 'name' => 'enabled_active_status' )
								, 'content' => 'content'                            // Function to load as conten of this TAB
							);

			$tabs[ 'payment' ]['subtabs'] = $subtabs;

			return $tabs;
		}


	/** Show Content of Settings page */
	public function content() {


		$this->css();

		////////////////////////////////////////////////////////////////////////
		// Checking
		////////////////////////////////////////////////////////////////////////

		do_action( 'wpbc_hook_settings_page_header', 'gateway_settings');       // Define Notices Section and show some static messages, if needed
		do_action( 'wpbc_hook_settings_page_header', 'gateway_settings_' . WPBC_PAYPAL_STD_CO_GATEWAY_ID );

		if ( ! wpbc_is_mu_user_can_be_here('activated_user') ) return false;       // Check if MU user activated, otherwise show Warning message.

		// if ( ! wpbc_is_mu_user_can_be_here('only_super_admin') ) return false;  // User is not Super admin, so exit.  Basically its was already checked at the bottom of the PHP file, just in case.

		////////////////////////////////////////////////////////////////////////
		// Load Data
		////////////////////////////////////////////////////////////////////////

		// $this->check_compatibility_with_older_7_ver();

		$init_fields_values = array();

		$this->get_api( $init_fields_values );


		////////////////////////////////////////////////////////////////////////
		//  S u b m i t   Main Form
		////////////////////////////////////////////////////////////////////////

		$submit_form_name = 'wpbc_gateway_' . WPBC_PAYPAL_STD_CO_GATEWAY_ID;               // Define form name

		$this->get_api()->validated_form_id = $submit_form_name;                // Define ID of Form for ability to  validate fields (like required field) before submit.

		if ( isset( $_POST['is_form_sbmitted_'. $submit_form_name ] ) ) {

			// Nonce checking    {Return false if invalid, 1 if generated between, 0-12 hours ago, 2 if generated between 12-24 hours ago. }
			$nonce_gen_time = check_admin_referer( 'wpbc_settings_page_' . $submit_form_name );  // Its stop show anything on submiting, if its not refear to the original page

			// Save Changes
			$this->update();
		}


		////////////////////////////////////////////////////////////////////////
		// JavaScript: Tooltips, Popover, Datepick (js & css)
		////////////////////////////////////////////////////////////////////////

		echo '<span class="wpdevelop">';

		wpbc_js_for_bookings_page();

		echo '</span>';


		////////////////////////////////////////////////////////////////////////
		// Content
		////////////////////////////////////////////////////////////////////////
		?>
		<div class="clear" style="margin-bottom:10px;"></div>

		<span class="metabox-holder">
			<form  name="<?php echo $submit_form_name; ?>" id="<?php echo $submit_form_name; ?>" action="" method="post" autocomplete="off">
				<?php
				   // N o n c e   field, and key for checking   S u b m i t
				   wp_nonce_field( 'wpbc_settings_page_' . $submit_form_name );
				?><input type="hidden" name="is_form_sbmitted_<?php echo $submit_form_name; ?>" id="is_form_sbmitted_<?php echo $submit_form_name; ?>" value="1" />

					<div class="wpbc-settings-notice notice-info" style="text-align:left;">
						<strong><?php _e('Note!' ,'booking'); ?></strong> <?php
							printf( __('If you have no account on this system, please visit %s to create one.' ,'booking')
								, '<a href="https://developer.paypal.com/dashboard/applications/sandbox"  target="_blank" style="text-decoration:none;">developer.paypal.com</a>');
						?>
					</div>
					<div class="clear" style="height:10px;"></div>
					<?php

					$edit_url_for_visitors = get_bk_option( 'booking_url_bookings_edit_by_visitors');

					if ( site_url() == $edit_url_for_visitors ) {
						$message_type = 'error';
					} else {
						$message_type = 'warning';
					}

					?>
					<div class="wpbc-settings-notice notice-<?php echo $message_type ?>" style="text-align:left;">
						<strong><?php echo ( ( 'error' == $message_type ) ? __('Error' ,'booking') : __('Note' ,'booking') ); ?></strong>! <?php
							echo 'PayPal Standard Checkout ';
							printf( __('require correct  configuration of this option: %sURL to edit bookings%s' ,'booking')
								, '<strong><a href="https://wpbookingcalendar.com/faq/configure-editing-cancel-payment-bookings-for-visitors/#content">', '</a></strong>'
								//, '<strong><a href="'. wpbc_get_settings_url() .'#url_booking_edit">', '</a></strong>'
							);
						?>
					</div>
					<div class="clear" style=""></div>
					<?php

					if ( version_compare( PHP_VERSION, '5.6' ) < 0 ) {
						echo '';
						?>
						<div class="wpbc-settings-notice notice-error" style="text-align:left;">
							<strong><?php _e('Error' ,'booking'); ?></strong>! <?php
								echo 'PayPal Standard Checkout ';
								printf( __('require PHP version %s or newer!' ,'booking'), '<strong>5.6</strong>');
							?>
						</div>
						<div class="clear" style="height:10px;"></div>
						<?php
					}
					if ( ( ! function_exists('curl_init') ) && ( ! wpbc_is_this_demo() ) ){								//FixIn: 8.1.1.1
						?>
						<div class="wpbc-settings-notice notice-error" style="text-align:left;">
							<strong><?php _e('Error' ,'booking'); ?></strong>! <?php
								echo 'PayPal Standard Checkout ';
								printf( 'require CURL library in your PHP!' , '<strong>'.PHP_VERSION.'</strong>');
							?>
						</div>
						<div class="clear" style="height:10px;"></div>
						<?php
					}
					?>
					<div class="clear" style="height:10px;"></div>
				<div class="clear"></div>
				<div class="metabox-holder">

					<div class="wpbc_settings_row wpbc_settings_row_leftNo" >
					<?php
						wpbc_open_meta_box_section( $submit_form_name . 'general', 'PayPal Standard Checkout' );
							$this->get_api()->show( 'general' );
						wpbc_close_meta_box_section();
					?>
					</div>
					<div class="clear"></div>


					<div class="wpbc_settings_row wpbc_settings_row_left" >
					<?php
						wpbc_open_meta_box_section( $submit_form_name . 'auto_approve_cancel', __('Advanced', 'booking')   );
							$this->get_api()->show( 'auto_approve_cancel' );
						wpbc_close_meta_box_section();
					?>
					</div>
					<div class="wpbc_settings_row wpbc_settings_row_right" >
					<?php
						wpbc_open_meta_box_section( $submit_form_name . 'help', 'Help' );
							$this->get_api()->show( 'account_help' );
						wpbc_close_meta_box_section();
					?>
					</div>
					<div class="clear"></div>

				</div>

				<input type="submit" value="<?php _e('Save Changes', 'booking'); ?>" class="button button-primary" />
			</form>
		</span>
		<?php

		$this->enqueue_js();
	}


		/** Update Email template to DB */
		public function update() {

			// Get Validated Email fields
			$validated_fields = $this->get_api()->validate_post();

			$validated_fields = apply_filters( 'wpbc_gateway_paypal_std_co_validate_fields_before_saving', $validated_fields );   //Hook for validated fields.

			$this->get_api()->save_to_db( $validated_fields );

			wpbc_show_message ( __('Settings saved.', 'booking'), 5 );              // Show Save message
		}


	// <editor-fold     defaultstate="collapsed"                        desc=" CSS & JS  "  >

	/** CSS for this page */
	private function css() {
		?>
		<style type="text/css">

            #paypal_button_type_1,
			#paypal_button_type_4{
                vertical-align: middle;
            }
			#paypal_button_type_4_custom_standard,
			#paypal_button_type_1_custom_yellow,
			#paypal_button_type_2_custom_blue,
			#paypal_button_type_3_custom_silver,
			#paypal_button_type_4_custom_white,
			#paypal_button_type_5_custom_black {
				display: inline-flex;
				flex-flow: column wrap;
				justify-content: center;
				align-content: center;
				vertical-align: middle;
			}
			#paypal_button_type_4_custom_standard span,
			#paypal_button_type_1_custom_yellow span,
			#paypal_button_type_2_custom_blue span,
			#paypal_button_type_3_custom_silver span,
			#paypal_button_type_4_custom_white span,
			#paypal_button_type_5_custom_black span {
				min-height: 16px;
				min-width: 100px;
			}
            .wpbc-help-message {
                border:none;
                margin:0 !important;
                padding:0 !important;
            }
		</style>
		<?php
	}


	/**
	 * Add Custon JavaScript - for some specific settings options
	 *      Executed After post content, after initial definition of settings,  and possible definition after POST request.
	 *
	 * @param type $menu_slug
	 */
	private function enqueue_js(){
		$js_script = '';

		//Show|Hide grayed section
		$js_script .= " 
						if ( ! jQuery('#paypal_std_co_mode_test').is(':checked') ) {   
							jQuery('.wpbc_sub_settings_mode_test').addClass('hidden_items'); 
						}
						if ( ! jQuery('#paypal_std_co_mode_live').is(':checked') ) {   
							jQuery('.wpbc_sub_settings_mode_live').addClass('hidden_items'); 
						}
					  ";
		// Hide|Show  on Click    Radio
		$js_script .= " jQuery('input[name=\"paypal_std_co_account_mode\"]').on( 'change', function(){    
								jQuery('.wpbc_sub_settings_mode_test,.wpbc_sub_settings_mode_live').addClass('hidden_items'); 
								if ( jQuery('#paypal_std_co_mode_test').is(':checked') ) {   
									jQuery('.wpbc_sub_settings_mode_test').removeClass('hidden_items');
								} else {
									jQuery('.wpbc_sub_settings_mode_live').removeClass('hidden_items');
								}
							} ); ";

		wpbc_enqueue_js( $js_script );
	}

	// </editor-fold>

}
add_action('wpbc_menu_created',  array( new WPBC_Settings_Page_Gateway_PAYPAL_STD_CO() , '__construct') );    // Executed after creation of Menu



/**
 * Override VALIDATED fields BEFORE saving to DB
 * Description:
 * Check "Return URLs" and "PAYPAL_STD_CO Email"m, etc...
 *
 * @param array $validated_fields
 */
function wpbc_gateway_paypal_std_co_validate_fields_before_saving__all( $validated_fields ) {

	if ( 'On' == $validated_fields['is_active'] ) {
		// Only one instance of PayPal Standard Checkout integration can  be active !
		update_bk_option( 'booking_paypal_std_co_is_active', 'Off');
	}

	$validated_fields['order_successful'] = wpbc_make_link_relative( $validated_fields['order_successful'] );
	$validated_fields['order_failed']     = wpbc_make_link_relative( $validated_fields['order_failed'] );

	if ( wpbc_is_this_demo() ) {
		$validated_fields['account_mode'] 		  = 'test';

		$validated_fields['client_id_test']  = 'AZ0lAsdIUYmp97nKwmIUpSQKOU7MIi8gAlk7qRMiv4RdfyVg8bBT5psT2xtwE9x72kemovBoh-pjvfK4';
		$validated_fields['secret_key_test'] = 'EH4qu4EmnPQ1SF-_ENqSBudDC7xRrpeJlR3fVj2olfYOF8VcKwGj3XKaLRRGH4cLfT05GD4EAsjGVIBz';
		$validated_fields['client_id']  = 'AZ0lAsdIUYmp97nKwmIUpSQKOU7MIi8gAlk7qRMiv4RdfyVg8bBT5psT2xtwE9x72kemovBoh-pjvfK4';
		$validated_fields['secret_key'] = 'EH4qu4EmnPQ1SF-_ENqSBudDC7xRrpeJlR3fVj2olfYOF8VcKwGj3XKaLRRGH4cLfT05GD4EAsjGVIBz';
	}

	return $validated_fields;
}
add_filter( 'wpbc_gateway_paypal_std_co_validate_fields_before_saving', 'wpbc_gateway_paypal_std_co_validate_fields_before_saving__all', 10, 1 );   // Hook for validated fields.

//                                                                              </editor-fold>



//                                                                              <editor-fold   defaultstate="collapsed"   desc=" Activate | Deactivate " >

////////////////////////////////////////////////////////////////////////////////
// Activate | Deactivate
////////////////////////////////////////////////////////////////////////////////

/**
 * Get previous option from PayPal Standard Checkout v.1 (if exist)
 *
 * @param $option_name
 * @param $default_value
 *
 * @return bool|mixed|void
 */
function wpbc_booking_check_previous_PAYPAL_option_for_PAYPAL_STD_CO( $option_name, $default_value ){

	return $default_value;	//TODO ?

	$op_prefix = 'booking_' . 'paypal_std_co'  . '_';		// WPBC_STRIPE_GATEWAY_ID

	$previos_version_value = get_bk_option( $op_prefix . $option_name );

	if ( false === $previos_version_value ) {
		return $default_value;
	} else {
		return $previos_version_value;
	}

}

/** A c t i v a t e */
function wpbc_booking_activate_PAYPAL_STD_CO() {

	$op_prefix = 'booking_' . WPBC_PAYPAL_STD_CO_GATEWAY_ID . '_';

	add_bk_option( $op_prefix . 'is_active',    		( wpbc_is_this_demo() ? 'On' : wpbc_booking_check_previous_PAYPAL_option_for_PAYPAL_STD_CO( 'is_active', 'Off' ) )  );
	add_bk_option( $op_prefix . 'account_mode',         wpbc_booking_check_previous_PAYPAL_option_for_PAYPAL_STD_CO( 'account_mode', 'test' ) );

	add_bk_option( $op_prefix . 'client_id_test', 		( wpbc_is_this_demo() ? 'AZ0lAsdIUYmp97nKwmIUpSQKOU7MIi8gAlk7qRMiv4RdfyVg8bBT5psT2xtwE9x72kemovBoh-pjvfK4' : wpbc_booking_check_previous_PAYPAL_option_for_PAYPAL_STD_CO( 'client_id_test', '' )  ) );
	add_bk_option( $op_prefix . 'secret_key_test', 		( wpbc_is_this_demo() ? 'EH4qu4EmnPQ1SF-_ENqSBudDC7xRrpeJlR3fVj2olfYOF8VcKwGj3XKaLRRGH4cLfT05GD4EAsjGVIBz' : wpbc_booking_check_previous_PAYPAL_option_for_PAYPAL_STD_CO( 'secret_key_test', '' )  ) );
	add_bk_option( $op_prefix . 'client_id', 			( wpbc_is_this_demo() ? 'AZ0lAsdIUYmp97nKwmIUpSQKOU7MIi8gAlk7qRMiv4RdfyVg8bBT5psT2xtwE9x72kemovBoh-pjvfK4' : wpbc_booking_check_previous_PAYPAL_option_for_PAYPAL_STD_CO( 'client_id', '' )  ) );
	add_bk_option( $op_prefix . 'secret_key', 			( wpbc_is_this_demo() ? 'EH4qu4EmnPQ1SF-_ENqSBudDC7xRrpeJlR3fVj2olfYOF8VcKwGj3XKaLRRGH4cLfT05GD4EAsjGVIBz' : wpbc_booking_check_previous_PAYPAL_option_for_PAYPAL_STD_CO( 'secret_key', '' )  ) );

	add_bk_option( $op_prefix . 'currency',          	wpbc_booking_check_previous_PAYPAL_option_for_PAYPAL_STD_CO( 'currency', 'USD' )  );
	add_bk_option( $op_prefix . 'subject',      		wpbc_booking_check_previous_PAYPAL_option_for_PAYPAL_STD_CO( 'subject', sprintf( __('Payment for booking %s on these day(s): %s'  ,'booking'), '[resource_title]','[dates]') ) );

	add_bk_option( $op_prefix . 'button_type', 		'paypal_yellow_v1' );		//FixIn: 8.8.1.12
	add_bk_option( $op_prefix . 'button_height' , 43 );
	add_bk_option( $op_prefix . 'paypal_tax_fee', '0' );

	add_bk_option( $op_prefix . 'order_successful',     wpbc_booking_check_previous_PAYPAL_option_for_PAYPAL_STD_CO( 'order_successful', '/successful' )  );
	add_bk_option( $op_prefix . 'order_failed',         wpbc_booking_check_previous_PAYPAL_option_for_PAYPAL_STD_CO( 'order_failed', '/failed' ) );
	add_bk_option( $op_prefix . 'is_auto_approve_cancell_booking' , wpbc_booking_check_previous_PAYPAL_option_for_PAYPAL_STD_CO( 'is_auto_approve_cancell_booking', 'Off' ) );
}
add_bk_action( 'wpbc_other_versions_activation',   'wpbc_booking_activate_PAYPAL_STD_CO'   );


/** D e a c t i v a t e */
function wpbc_booking_deactivate_PAYPAL_STD_CO() {

	$op_prefix = 'booking_' . WPBC_PAYPAL_STD_CO_GATEWAY_ID . '_';

	delete_bk_option( $op_prefix . 'is_active' );
	delete_bk_option( $op_prefix . 'account_mode' );

	delete_bk_option( $op_prefix . 'client_id_test' );
	delete_bk_option( $op_prefix . 'secret_key_test' );
	delete_bk_option( $op_prefix . 'client_id' );
	delete_bk_option( $op_prefix . 'secret_key' );

	delete_bk_option( $op_prefix . 'currency' );
	delete_bk_option( $op_prefix . 'subject' );

	delete_bk_option( $op_prefix . 'button_type' );		//FixIn: 8.8.1.12
	delete_bk_option( $op_prefix . 'button_height' );
	delete_bk_option( $op_prefix . 'paypal_tax_fee' );

	delete_bk_option( $op_prefix . 'order_successful' );
	delete_bk_option( $op_prefix . 'order_failed' );
	delete_bk_option( $op_prefix . 'is_auto_approve_cancell_booking' );
}
add_bk_action( 'wpbc_other_versions_deactivation', 'wpbc_booking_deactivate_PAYPAL_STD_CO' );

//                                                                              </editor-fold>


// Hook for getting gateway payment form to  show it after  booking process,  or for "payment request" after  clicking on link in email.
// Note,  here we generate new Object for correctly getting payment fields data of specific WP User  in WPBC MU version. 
add_filter( 'wpbc_get_gateway_payment_form', array( new WPBC_Gateway_API_PAYPAL_STD_CO( WPBC_PAYPAL_STD_CO_GATEWAY_ID ), 'get_payment_form' ), 10, 3 );



////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// RESPONSE
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

	/**
	 *  Update Payment status of booking
	 * @param $booking_id
	 * @param $status
	 *
	 * @return bool
	 */
	function wpbc_paypal_std_co_update_payment_status( $booking_id, $status ){

		global $wpdb;

		// Update payment status
		$update_sql = $wpdb->prepare( "UPDATE {$wpdb->prefix}booking AS bk SET bk.pay_status = %s WHERE bk.booking_id = %d;", $status, $booking_id );

		if ( false === $wpdb->query( $update_sql  ) ){
			return  false;
		}

		return  true;
	}


	/**
	 * Auto cancel booking and redirect
	 * @param $booking_id
	 * @param $paypal_std_co_error_code
	 */
	function wpbc_paypal_std_co_auto_cancel_booking( $booking_id , $paypal_std_co_error_code ){

		// Let's check whether the user wanted auto-approve or cancel
		$auto_approve = get_bk_option( 'booking_paypal_std_co_is_auto_approve_cancell_booking' );
		if ( $auto_approve == 'On' ) {
			wpbc_auto_cancel_booking( $booking_id );
		}

		$paypal_std_co_error_url = get_bk_option( 'booking_paypal_std_co_order_failed' );

		$paypal_std_co_error_url = wpbc_make_link_absolute( $paypal_std_co_error_url );

		wpbc_redirect( $paypal_std_co_error_url . "?error=" . $paypal_std_co_error_code );

	}


	/**
	 * Auto approve booking and redirect
	 *
	 * @param $booking_id
	 */
	function wpbc_paypal_std_co_auto_approve_booking( $booking_id, $paid_amount_in_plugin = '' ){

		// Let's check whether the user wanted auto-approve or cancel
		$auto_approve = get_bk_option( 'booking_paypal_std_co_is_auto_approve_cancell_booking' );
		if ( $auto_approve == 'On' ) {
			wpbc_auto_approve_booking( $booking_id );
		}

		$paypal_std_co_success_url = get_bk_option( 'booking_paypal_std_co_order_successful' );
		if ( empty( $paypal_std_co_success_url ) ) {
			$paypal_std_co_success_url = get_bk_option( 'booking_thank_you_page_URL' );
		}

		$paypal_std_co_success_url = wpbc_make_link_absolute( $paypal_std_co_success_url );

		$paypal_std_co_success_url .= ( ( false === strpos( $paypal_std_co_success_url, '?' ) ) ? '?' : '&' ) . 'paid_amount=' . $paid_amount_in_plugin;

		wpbc_redirect( $paypal_std_co_success_url );
	}




/**
 * Parse 1 way secret HASH, usually  after  redirection from payment system
 * and make approve / decline specific booking.
 *
 * @param $parsed_response  Array
									(
										[0] => payment
										[1] => paypal_std_co
										[2] => ec1f2c35728603edee9bde65ff3ba665
										[3] => approve
									)
 */
function wpbc_payment_response__paypal_std_co( $parsed_response ) {

	// 	  'payment',	  'paypal_std_co',  '872...',	   'approve'
	list( $response_type, $response_source, $booking_hash, $response_action ) = $parsed_response;

	// Check if its response from PayPal Standard Checkout
	if ( ( 'payment' !== $response_type ) || ( WPBC_PAYPAL_STD_CO_GATEWAY_ID !== $response_source ) ) {
		return;
	}

	// Get Booking ID and Resource ID
	$booking_id          = false;
	$booking_resource_id = false;

	// -----------------------------------------------------------------------------------------------------------------
	// Get Booking ID and resource ID
	// -----------------------------------------------------------------------------------------------------------------
	if ( ! empty( $booking_hash ) ) {
		// [ '775', '4' ]
		$current__booking_id__resource_id = wpbc_hash__get_booking_id__resource_id( $booking_hash );

		if ( ! empty( $current__booking_id__resource_id ) ) {

			list( $booking_id, $booking_resource_id ) = $current__booking_id__resource_id;

			// In MultiUser version, check if this booking relative to the booking resource, from  the "regular user"	//FixIn: 8.7.9.3
			if ( class_exists( 'wpdev_bk_multiuser' ) ) {
				$user_id = apply_bk_filter( 'get_user_of_this_bk_resource', false, $booking_resource_id );

				$is_booking_resource_user_super_admin = apply_bk_filter( 'is_user_super_admin', $user_id );

				if ( 'On' == get_bk_option( 'booking_super_admin_receive_regular_user_payments' ) ) {                   //FixIn: 9.7.3.5
					$is_booking_resource_user_super_admin = true;
					make_bk_action( 'make_force_using_this_user', - 999 );                                              // '-999' - This ID "by default" is the ID of super booking admin user
				}

				if ( ! $is_booking_resource_user_super_admin ) {
					make_bk_action( 'check_multiuser_params_for_client_side_by_user_id', $user_id );                    // Reactivate data for "regular  user
				}
			}
		}
	}


	$payment_options = wpbc_paypal_std_co__get_client__id_secret_key();        // array( 'paypal_sandbox' => true, 'paypal_client_id' => 'xx..zz', , 'paypal_client_secret' => 'aa..bb' );


	// -----------------------------------------------------------------------------------------------------------------
	// Check errors in settings
	// -----------------------------------------------------------------------------------------------------------------
	// Check whether secret key was assigned,  Otherwise -- ERROR
	if ( ( empty( $payment_options['paypal_client_secret'] ) ) || ( empty( $payment_options['paypal_client_id'] ) ) ) {
		echo 'Wrong configuration in gateway settings.' . ' <em>Empty: "Secret key" or "Client ID" option</em>';
		return;
	}
	if ( empty( $booking_id ) || empty( $booking_resource_id ) ) {
		echo __( 'Oops!', 'booking' ) . ' ' . __( 'We could not find your booking. The link you used may be incorrect or has expired. If you need assistance, please contact our support team.', 'booking' );
		return;
	}
	if ( version_compare( PHP_VERSION, '5.6' ) < 0 ) {
		echo 'PayPal Standard Checkout  payment require PHP version 5.6 or newer!';
		return;
	}
	// -----------------------------------------------------------------------------------------------------------------


	// PayPal Validate Class
	require_once( dirname( __FILE__ )  . '/wpbc_paypal_std_co_class.php' );

	$paypal = new WPBC_VALIDATE_PAYPAL_STD_CO( $payment_options['paypal_sandbox'], $payment_options['paypal_client_id'], $payment_options['paypal_client_secret'] );


	// -----------------------------------------------------------------------------------------------------------------
	// If VALID
	// -----------------------------------------------------------------------------------------------------------------
	if ( ! empty( $_REQUEST['paypal_order_check'] ) && ! empty( $_REQUEST['order_id'] ) ) {

		// Validate and get order details with PayPal API
		try {
			$order = $paypal->validate( $_REQUEST['order_id'] );
		} catch ( Exception $e ) {
			$api_error = $e->getMessage();
		}

		if ( ( ! empty( $order ) ) && ( 'decline' != $response_action ) ) {

			$order_id     = $order['id'];
			$intent       = $order['intent'];
			$order_status = $order['status'];
			$order_time   = date( "Y-m-d H:i:s", strtotime( $order['create_time'] ) );

			if ( ( ! empty( $order['purchase_units'] ) ) && ( ! empty( $order['purchase_units'][0] ) ) ) {

				$purchase_unit = $order['purchase_units'][0];

				$item_number = $purchase_unit['custom_id'];
				$item_name   = $purchase_unit['description'];

				if ( ! empty( $purchase_unit['amount'] ) ) {
					$currency_code = $purchase_unit['amount']['currency_code'];
					$amount_value  = $purchase_unit['amount']['value'];
				}

				if ( ! empty( $purchase_unit['payments'] ) ) {
					if ( ! empty( $purchase_unit['payments']['captures'][0] ) ) {
						$payment_capture = $purchase_unit['payments']['captures'][0];
						$transaction_id  = $payment_capture['id'];
						$payment_status  = $payment_capture['status'];
					}
				}

				if ( ! empty( $purchase_unit['payee'] ) ) {
					$payee               = $purchase_unit['payee'];
					$payee_email_address = $payee['email_address'];
					$merchant_id         = $payee['merchant_id'];
				}
			}

			$payment_source = '';
			if ( ! empty( $order['payment_source'] ) ) {
				foreach ( $order['payment_source'] as $key => $value ) {
					$payment_source = $key;
				}
			}

			if ( ! empty( $order['payer'] ) ) {
				$payer            = $order['payer'];
				$payer_id         = $payer['payer_id'];
				$payer_name       = $payer['name'];
				$payer_given_name = ! empty( $payer_name['given_name'] ) ? $payer_name['given_name'] : '';
				$payer_surname    = ! empty( $payer_name['surname'] ) ? $payer_name['surname'] : '';
				$payer_full_name  = trim( $payer_given_name . ' ' . $payer_surname );
				//$payer_full_name = filter_var($payer_full_name, FILTER_SANITIZE_STRING,FILTER_FLAG_STRIP_HIGH);

				$payer_email_address = $payer['email_address'];
				$payer_address       = $payer['address'];
				$payer_country_code  = ! empty( $payer_address['country_code'] ) ? $payer_address['country_code'] : '';
			}

			if ( ! empty( $order_id ) && $order_status == 'COMPLETED' ) {

				// Update booking notes
				if ( 1 ) {

					// '100.0'													// 10000			 , 	'eur'
					$paid_amount_in_plugin = wpbc_paypal_std_co__amount_in_plugin( $amount_value, $currency_code );

					// 'eur 100.0'
					$paid_sum_with_currency = trim( html_entity_decode( wpbc_formate_cost_hint__no_html( $paid_amount_in_plugin, ' ' . $currency_code . ' ' ) ) );

					$text_paid_amount = 'Total' . ': ' . strtoupper( $paid_sum_with_currency );

					// 'paid'
					$text_payment_status = 'Status' . ': ' . $payment_status;

					wpbc_db__add_log_info( explode( ',', $booking_id )
										 , 'Payment system response.'
											. ' -- PayPal Standard Checkout -- | '
											. $order_status . ' | '
											. 'ORDER ID: '.$order_id  . ' | ' 		// 'paid'
											. $order_time  . ' |'			// 'complete'

											. $text_paid_amount  . ' | '
											. $text_payment_status  . ' | ' 		// 'paid'


											. 'Payer: '.$payer_full_name  . ' | ' . $payer_email_address . '|'	. $payer_country_code . '|'	// 'paid'
										);
				}

				wpbc_paypal_std_co_update_payment_status( $booking_id , 'PayPal_STD_CO:OK');

				wpbc_paypal_std_co_auto_approve_booking( $booking_id , $amount_value );
			}

		} else {

			$response_msg = 'Transaction failed or canceled!';
			if ( ! empty( $api_error ) ) {
				$response_msg = $api_error;
			}

			// Update booking notes
			wpbc_db__add_log_info( explode( ',', $booking_id )
								 , 'Payment system response.'
									. ' -- PayPal Standard Checkout -- | '
									. $response_msg . ' | '
								);

			wpbc_paypal_std_co_update_payment_status( $booking_id, 'PayPal_STD_CO:ERROR' );

			wpbc_paypal_std_co_auto_cancel_booking( $booking_id, "PayPal_Standard_Checkout_payment_failed." );
		}

	}

}
add_bk_action( 'wpbc_payment_response', 'wpbc_payment_response__paypal_std_co' );
