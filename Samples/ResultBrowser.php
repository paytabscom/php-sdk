<?php

use Paytabs\Sdk\Paytabs;
use Paytabs\Sdk\Profile\Profile;
use Paytabs\Sdk\Response\Responses\Webhook\TransactionResult\BrowserAsGet;
use Paytabs\Sdk\Response\Responses\Webhook\TransactionResult\BrowserAsPost;

/**
 * @var Profile $profile
 */

if (!isset($profile)) {
    http_response_code(500);
    exit('Invalid sample bootstrap: missing $profile');
}

//

// URL query string, Format (sample): APP_URL?cartId=CART_ID$respCode=RESP_CODE&respMessage=RESP_MESSAGE&respStatus=RESP_STATUS&tranRef=TRAN_REF&signature=SIGNATURE
$urlResult = 'result=1&mode=return&cartId=cart01&respCode=G00000&respStatus=A&signature=REPLACE_WITH_VALID_SIGNATURE&tranRef=TST_REFERENCE_PLACEHOLDER';

$getArray1 = [];
parse_str($urlResult, $getArray1);
$response1 = BrowserAsGet::initWith($getArray1, ['mode', 'result']);

$response1->setProfile($profile);

Paytabs::getLogger()->debug('Return Payload (As GET): ', [
    'isGenuine' => $response1->isGenuine() ? 'Yes' : 'No',
    'Response' => $response1->getPayload()->getMapped(),
]);

//

// POST data, Format (sample): acquirerMessage=&acquirerRRN=&cartId=CART_ID&customerEmail=CUSTOMER_EMAIL&respCode=RESP_CODE&respMessage=RESP_MESSAGE&respStatus=RESP_STATUS&signature=SIGNATURE&token=TOKEN&tranRef=TRAN_REF
$getResult = 'acquirerMessage=&acquirerRRN=&cartId=cart01&customerEmail=integrations%40paytabs.com&respCode=321&respMessage=Cancelled&respStatus=C&signature=REPLACE_WITH_VALID_SIGNATURE&token=&tranRef=TST_REFERENCE_PLACEHOLDER';

$getArray2 = [];
parse_str($getResult, $getArray2);
$response2 = BrowserAsPost::initWith($getArray2);

$response2->setProfile($profile);

Paytabs::getLogger()->debug('Return Payload (As POST): ', [
    'isGenuine' => $response2->isGenuine() ? 'Yes' : 'No',
    'Response' => $response2->getPayload()->getMapped(),
]);
