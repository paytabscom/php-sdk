<?php

use Paytabs\Sdk\Paytabs;
use Paytabs\Sdk\Profile\Profile;
use Paytabs\Sdk\Response\Responses\Webhook\TransactionResult\BrowserAsGet;
use Paytabs\Sdk\Response\Responses\Webhook\TransactionResult\BrowserAsPost;

/**
 * @var Profile $profile
 */

if (!isset($profile)) {
    throw new \RuntimeException('Required variable is not set: $profile');
}

//

// URL query string, Format (sample): APP_URL?cartId=CART_ID$respCode=RESP_CODE&respMessage=RESP_MESSAGE&respStatus=RESP_STATUS&tranRef=TRAN_REF&signature=SIGNATURE
$urlResult = 'result=1&mode=return&cartId=cart01&respCode=G07626&respStatus=A&signature=d404cc5e9f50ca7e5fff316f7b1ac5e6af12803302e2cbac555ddb6f7b4447b9&tranRef=TST2617402946412';

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
$getResult = 'acquirerMessage=&acquirerRRN=&cartId=cart01&customerEmail=integrations%40paytabs.com&respCode=321&respMessage=Cancelled&respStatus=C&signature=cfac02a6b6d9081b4da2a8e2e46bde5ad42b00ef09dd856985b18851f771e8c5&token=&tranRef=TST2617402946419';

$getArray2 = [];
parse_str($getResult, $getArray2);
$response2 = BrowserAsPost::initWith($getArray2);

$response2->setProfile($profile);

Paytabs::getLogger()->debug('Return Payload (As POST): ', [
    'isGenuine' => $response2->isGenuine() ? 'Yes' : 'No',
    'Response' => $response2->getPayload()->getMapped(),
]);
