<?php

add_filter( 'gform_confirmation_3', 'redirect_to_payment', 10, 4 );
function redirect_to_payment( $confirmation, $form, $entry, $ajax ) {
    BS_Log::info( "IN redirect_to_worldpay ----------------" );
    BS_Log::info( "ENTRY: ", $entry );


    $today = date("Ymd");
    $uniqueid = substr(sha1(time()), 0, 5);

    $buyerEmail   = $entry['18'];
	$paymentType = $entry['45'];


	if($paymentType == 'Debit Card'){
		$confirmation = redirect_to_worldpay( $entry, $today, $uniqueid, $buyerEmail );
	}else{
		$redirectUrl = SITE_URL . '/bottomline-payment/';
		$confirmation = array( 'redirect' => $redirectUrl );
	}

	return $confirmation;
}

/**
 * @param $entry
 * @param string $today
 * @param string $uniqueid
 * @param mixed $buyerEmail
 *
 * @return string[]
 */
function redirect_to_worldpay( $entry, string $today, string $uniqueid, string $buyerEmail ): array {
//Mandatory Fields
	$instID   = "REMOVED FOR SECURITY PURPOSES"; //Instalation ID for Select Junior - WILLEN HOSPICE VENTURES LTD - Select Junior FuturePay 
	$currency = 'GBP';
	$amount   = $entry['77'];
	$cartid   = $today . "-" . $uniqueid;


	//Determine frequency intervals
	$frequency = $entry['141'];
	if ( $frequency == 1 ) {
		$final_frequency = 1;
		$finalInterval   = 12;
	} elseif ( $frequency == 3 ) {
		$final_frequency = 3;
		$finalInterval   = 4;
	} else {
		$final_frequency = 12;
		$finalInterval   = 1;
	}


	//Recurring Payment Parmeters
	$futurePayType  = "regular";
	$option         = 1;
	$startDelayMult = 3;
	$startDelayUnit = 2;
	$noOfPayments   = 0;
	$intervalMult   = $final_frequency;
	$intervalUnit   = 3;
	$normalAmount   = $amount;
	$initialAmount  = $amount;

	//Payer information
	$name = $entry[''];




	//Enable this and include it in the URL query to specify that it is a test payment
	$testMode = 100;

	//Where the user will be redirected to after attempting to complete payment
	$successURL = SITE_URL ."/success?email=$buyerEmail";
	$failureURL = SITE_URL ."/failed";
	$cancelURL  = SITE_URL ."/cancelled";


	$worldPayURL = "https://secure-test.worldpay.com/wcc/purchase?instId=$instID&amount=$amount&currency=$currency&cartId=$cartid&startDelayMult=$final_frequency&startDelayUnit=1&futurePayType=regular&option=1&noOfPayments=$finalInterval&intervalMult=$final_frequency&intervalUnit=3&normalAmount=$amount&initialAmount=$amount&testMode=$testMode&MC_callback=$successURL";
	BS_Log::info( "WORLDPAY URL: ", $worldPayURL );

	$confirmation = array( 'redirect' => $worldPayURL );

	return $confirmation;
}
