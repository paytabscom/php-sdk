<?php

use Paytabs\Sdk\Paytabs;
use Paytabs\Sdk\Profile\Profile;
use Paytabs\Sdk\Response\Responses\Webhook\TransactionResult\Callback;

/**
 * @var Profile $profile
 */

if (!isset($profile)) {
    http_response_code(500);
    exit('Invalid sample bootstrap: missing $profile');
}

// IPN payload, Format (sample): JSON body with 'Signature' header
$payload = '{"tran_ref":"TST_REFERENCE_PLACEHOLDER","previous_tran_ref":"TST_PREVIOUS_REFERENCE_PLACEHOLDER","merchant_id":100000,"profile_id":100001,"cart_id":"cart-01","cart_description":"Sample callback payload","cart_currency":"AED","cart_amount":"100.00","tran_currency":"AED","tran_total":"100.00","tran_type":"Sale","tran_class":"ECom","customer_ref":"customer-ref-1","customer_details":{"name":"Sample Customer","email":"customer@example.test","phone":"+971500000000","street1":"Sample Street","city":"Dubai","state":"DU","country":"AE"},"payment_result":{"response_status":"A","response_code":"G00000","response_message":"Authorised","acquirer_ref":"TRAN_REFERENCE_PLACEHOLDER","cvv_result":" ","avs_result":" ","transaction_time":"2026-01-01T00:00:00Z"},"payment_info":{"payment_method":"Visa","card_type":"Credit","card_scheme":"Visa","payment_description":"4111 11## #### 1111","expiryMonth":12,"expiryYear":2030},"token":"TOKEN_PLACEHOLDER","user_defined":{"udf1":"udf_1","udf4":"udf_4","udf8":"udf_8"},"ipn_trace":"IPN_TRACE_PLACEHOLDER","paymentChannel":"PHP SDK"}';

$headers = [
    'Signature' => 'REPLACE_WITH_VALID_SIGNATURE',
];

$ipnResponse = Callback::initWith($payload, $headers);
$ipnResponse->setProfile($profile);

$isGenuine = $ipnResponse->isGenuine();
if (!$isGenuine) {
    http_response_code(400);
    exit('Invalid signature');
}

$mapped = $ipnResponse->getPayload()->getMapped();

Paytabs::getLogger()->debug('IPN Payload: ', [
    'isGenuine' => $isGenuine ? 'Yes' : 'No',
    'Response' => $mapped,
]);
