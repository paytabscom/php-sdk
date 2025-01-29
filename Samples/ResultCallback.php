<?php

use Paytabs\Sdk\Paytabs;
use Paytabs\Sdk\Response\Responses\Webhook\TransactionResult\Callback;

$payload = '{"tran_ref":"TST2502902213573","merchant_id":1842,"profile_id":47170,"cart_id":"c01","cart_description":"Test","cart_currency":"AED","cart_amount":"101.51","tran_currency":"AED","tran_total":"101.51","tran_type":"Sale","tran_class":"ECom","customer_details":{"name":"Wajih SDK3","email":"wajih@mail.com","phone":"0522222222","street1":"nsr st","city":"Dubai","state":"DU","country":"AE","ip":"2001:8f8:1471:ccd:4c61:ab3:210b:c367"},"payment_result":{"response_status":"A","response_code":"G42967","response_message":"Authorised","acquirer_ref":"TRAN0001.6799F4F6.000101C9","cvv_result":" ","avs_result":" ","transaction_time":"2025-01-29T09:29:27Z"},"payment_info":{"payment_method":"Visa","card_type":"Credit","card_scheme":"Visa","payment_description":"4000 00## #### 0002","expiryMonth":11,"expiryYear":2033},"token":"2C4655BE67A3E537C6B593FF60817AB9","threeDSDetails":{"responseLevel":2,"responseStatus":4,"enrolled":"Y","paResStatus":"Y","eci":"05","cavv":"","ucaf":"VFNUMjUwMjkwMjIxMzU3MzI3YTc=","threeDSVersion":"Test/Simulation"},"ipn_trace":"IPNS0001.6799F4F7.00000950","paymentChannel":"PHP SDK"}';

$headers = [
    'Signature' => '5cb373805f3ac2b1bc55b4ea41e0ea1b585e31f970977780370e2a5f06a7bd2c'
];

//

$ipnResponse = Callback::initWith($payload, $headers);
$ipnResponse->setGateway($gateway);

Paytabs::getLogger()->debug('IPN Payload: ', [
    'isGenuine' => $ipnResponse->isGenuine(),
    'Response' => $ipnResponse->getPayload()->getMapped(),
]);
