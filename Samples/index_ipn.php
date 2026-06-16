<?php

use Paytabs\Sdk\Paytabs;
use Paytabs\Sdk\Response\Responses\Webhook\TransactionResult\BrowserAsGet;
use Paytabs\Sdk\Response\Responses\Webhook\TransactionResult\BrowserAsPost;
use Paytabs\Sdk\Response\Responses\Webhook\TransactionResult\Callback;

$return = 'return' === @$_GET['mode'];

if ($return) {
    $returnAsGet = '1' === @$_GET['get'];

    $localParams = [
        'mode',
        'result',
        'get',
    ];

    if (!$returnAsGet) {
        $response = BrowserAsPost::init();
        $response->setProfile($profile);
    } else {
        $response = BrowserAsGet::init($localParams);
        $response->setProfile($profile);
    }

    $resMapped = $response->getPayload()->getMapped();
    Paytabs::getLogger()->debug('Return Payload: ', [
        'isGenuine' => $response->isGenuine(),
        'Response' => $resMapped,
    ]);
    Paytabs::getLogger()->error('Missed Data: ', [
        $resMapped->unMappedData(),
    ]);
} else {
    $ipnResponse = Callback::init();
    $ipnResponse->setProfile($profile);

    $resMapped = $ipnResponse->getPayload()->getMapped();
    Paytabs::getLogger()->debug('IPN Payload: ', [
        'isGenuine' => $ipnResponse->isGenuine(),
        'Response' => $resMapped,
    ]);
    Paytabs::getLogger()->error('Missed Data: ', [
        $resMapped->unMappedData(),
    ]);
}
