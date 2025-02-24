<?php

use Paytabs\Sdk\Paytabs;
use Paytabs\Sdk\Response\Responses\Webhook\TransactionResult\BrowserCallback;
use Paytabs\Sdk\Response\Responses\Webhook\TransactionResult\BrowserReturn;
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
        $response = BrowserReturn::init();
        $response->setGateway($gateway);
    } else {
        $response = BrowserCallback::init($localParams);
        $response->setGateway($gateway);
    }

    Paytabs::getLogger()->debug('Return Payload: ', [
        'isGenuine' => $response->isGenuine(),
        'Response' => $response->getPayload()->getMapped(),
    ]);
} else {
    $ipnResponse = Callback::init();
    $ipnResponse->setGateway($gateway);

    Paytabs::getLogger()->debug('IPN Payload: ', [
        'isGenuine' => $ipnResponse->isGenuine(),
        'Response' => $ipnResponse->getPayload()->getMapped(),
    ]);
}
