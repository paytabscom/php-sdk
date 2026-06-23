<?php

use Paytabs\Sdk\Paytabs;
use Paytabs\Sdk\Profile\Profile;
use Paytabs\Sdk\Response\Responses\Webhook\TransactionResult\Callback;

/**
 * @var Profile $profile
 */

if (!isset($profile)) {
    throw new \RuntimeException('Required variable is not set: $profile');
}

// IPN payload, Format (sample): JSON body with 'Signature' header
$payload = '{"tran_ref":"TST2617402946403","previous_tran_ref":"TST2617402946400","merchant_id":2550,"profile_id":48214,"cart_id":"ca-03","cart_description":"Test","cart_currency":"SAR","cart_amount":"700.00","tran_currency":"SAR","tran_total":"700.00","tran_type":"Sale","tran_class":"C/Auth","customer_ref":"customer-ref-1","customer_details":{"name":"Integrations SDK3","email":"integrations@paytabs.com","phone":"0522222222","street1":"nsr st","city":"Dubai","state":"DU","country":"AE"},"payment_result":{"response_status":"A","response_code":"G07062","response_message":"Authorised","acquirer_ref":"TRAN0201.6A3A5256.00109416","cvv_result":" ","avs_result":" ","transaction_time":"2026-06-23T09:31:02Z"},"payment_info":{"payment_method":"Visa","card_type":"Credit","card_scheme":"Visa","payment_description":"4111 11## #### 1111","expiryMonth":11,"expiryYear":2033},"token":"2C4656BF67A3E832C6BE93FC61847DBC","user_defined":{"udf1":"udf_1","udf4":"udf_4","udf8":"udf_8"},"ipn_trace":"IPNS0201.6A3A5256.000136D4","paymentChannel":"PHP SDK"}';

$headers = [
    'Signature' => 'cf9a70586cb63f1f11837196562ecced04bf9e39e88f884a23b9a7aec94865af',
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
