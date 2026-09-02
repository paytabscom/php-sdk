<?php

declare(strict_types=1);

use Paytabs\Sdk\Exceptions\InvalidSignatureException;
use Paytabs\Sdk\Profile\Profile;
use Paytabs\Sdk\Response\Responses\Webhook\TransactionResult\BrowserAsGet;
use Paytabs\Sdk\Response\Responses\Webhook\TransactionResult\BrowserAsPost;
use Psr\Log\LoggerInterface;

/**
 * @var Profile         $profile
 * @var LoggerInterface $logger
 */
if (!isset($profile, $logger)) {
    http_response_code(500);

    exit('Invalid sample bootstrap: missing $profile or $logger');
}

// POST data, Format (sample): acquirerMessage=&acquirerRRN=&cartId=CART_ID&customerEmail=CUSTOMER_EMAIL&respCode=RESP_CODE&respMessage=RESP_MESSAGE&respStatus=RESP_STATUS&signature=SIGNATURE&token=TOKEN&tranRef=TRAN_REF
$getResult = 'acquirerMessage=&acquirerRRN=&cartId=cart01&customerEmail=integrations%40paytabs.com&respCode=321&respMessage=Cancelled&respStatus=C&signature=REPLACE_WITH_VALID_SIGNATURE&token=&tranRef=TST_REFERENCE_PLACEHOLDER';
// $getResult = file_get_contents(__DIR__ . '/responses/webhook-browser-success.txt');

$getArray2 = [];
parse_str($getResult, $getArray2);
try {
    $response2 = BrowserAsPost::initWith($getArray2);
} catch (\InvalidArgumentException $e) {
    $logger->error('Failed to initialize BrowserAsPost: ' . $e->getMessage());
    http_response_code(400);
    exit('Invalid browser callback payload: ' . $e->getMessage());
}

$response2->setProfile($profile);

// Fail closed: verify before touching the payload. assertGenuine() throws so the
// check cannot be skipped by forgetting an `if`.
try {
    $response2->assertGenuine();
} catch (InvalidSignatureException $e) {
    $logger->alert('Rejected browser return (As POST): ' . $e->getMessage());
    http_response_code(403);

    exit('Invalid signature');
}

$logger->debug('Return Payload (As POST): ', [
    'Response' => $response2->getPayload()->getMapped(),
]);

// URL query string, Format (sample): APP_URL?cartId=CART_ID$respCode=RESP_CODE&respMessage=RESP_MESSAGE&respStatus=RESP_STATUS&tranRef=TRAN_REF&signature=SIGNATURE
$urlResult = 'result=1&mode=return&cartId=cart01&respCode=G00000&respStatus=A&signature=REPLACE_WITH_VALID_SIGNATURE&tranRef=TST_REFERENCE_PLACEHOLDER';

$getArray1 = [];
parse_str($urlResult, $getArray1);
$response1 = BrowserAsGet::initWith($getArray1, ['mode', 'result']);

$response1->setProfile($profile);

try {
    $response1->assertGenuine();
} catch (InvalidSignatureException $e) {
    $logger->alert('Rejected browser return (As GET): ' . $e->getMessage());
    http_response_code(403);

    exit('Invalid signature');
}

$logger->debug('Return Payload (As GET): ', [
    'Response' => $response1->getPayload()->getMapped(),
]);
