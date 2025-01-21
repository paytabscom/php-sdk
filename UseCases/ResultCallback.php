<?php

use Paytabs\Sdk\Paytabs;
use Paytabs\Sdk\Response\Responses\Callback;

$payload = '{"tran_ref":"TST2501402204249","merchant_id":1842,"profile_id":47170,"cart_id":"c01","cart_description":"Test","cart_currency":"AED","cart_amount":"100.51","tran_currency":"AED","tran_total":"100.51","tran_type":"Sale","tran_class":"ECom","customer_details":{"name":"Wajih sdk","email":"wajih@mail.com","phone":"0522222222","street1":"nsr st","city":"Dubai","state":"DU","country":"AE","ip":"2001:8f8:1471:ccd:f90a:9c05:1be9:c1cb"},"shipping_details":{"name":"Wajih 2","street1":"No Address"},"payment_result":{"response_status":"A","response_code":"G61298","response_message":"Authorised","acquirer_ref":"TRAN0002.67866672.0011E2F5","cvv_result":" ","avs_result":" ","transaction_time":"2025-01-14T13:28:18Z"},"payment_info":{"payment_method":"Visa","card_type":"Credit","card_scheme":"Visa","payment_description":"4111 11## #### 1111","expiryMonth":11,"expiryYear":2033},"token":"2C4654BD67A3ED33C6B693FF628674B0","ipn_trace":"IPNS0002.67866672.0000A10E"}';

$headers = [
    'Signature' => '4a0275cb696e5bcd55fc60fe170631299ee4e3e799bf35c440430b16c89b75ec'
];

//

$ipnResponse = Callback::initWith($payload, $headers);
$ipnResponse->setGateway($gateway);

Paytabs::getLogger()->debug('IPN Payload: ', [
    'isValid' => $ipnResponse->isValid(),
    'Response' => $ipnResponse->getResponse()
]);
