<?php

use Paytabs\Sdk\Paytabs;
use Paytabs\Sdk\Profile\Profile;
use Paytabs\Sdk\Response\Responses\Webhook\TransactionResult\BrowserAsGet;
use Paytabs\Sdk\Response\Responses\Webhook\TransactionResult\BrowserAsPost;
use Paytabs\Sdk\Response\Responses\Webhook\TransactionResult\Callback;

/** @var Profile $profile */
$return = 'return' === ($_GET['mode'] ?? null);

if ($return) {
    $returnAsGet = '1' === ($_GET['get'] ?? null);

    $localParams = [
        'mode',
        'result',
        'get',
    ];

    if ($returnAsGet) {
        // Legacy compatibility path. BrowserAsPost is the default and recommended flow.
        $response = BrowserAsGet::init($localParams);
    } else {
        $response = BrowserAsPost::init();
    }

    $response->setProfile($profile);

    $isGenuine = $response->isGenuine();
    if (!$isGenuine) {
        Paytabs::getLogger()->warning('Invalid signature for browser return callback');
        http_response_code(400);

        exit('Invalid signature');
    }

    $resMapped = $response->getPayload()->getMapped();
    Paytabs::getLogger()->debug('Return Payload: ', [
        'isGenuine' => $isGenuine ? 'Yes' : 'No',
        'Response' => $resMapped,
    ]);
    Paytabs::getLogger()->error('Missed Data: ', [
        $resMapped->unMappedData(),
    ]);
} else {
    $ipnResponse = Callback::init();
    $ipnResponse->setProfile($profile);

    $isGenuine = $ipnResponse->isGenuine();
    if (!$isGenuine) {
        Paytabs::getLogger()->warning('Invalid signature for IPN callback');
        http_response_code(400);

        exit('Invalid signature');
    }

    $resMapped = $ipnResponse->getPayload()->getMapped();
    Paytabs::getLogger()->debug('IPN Payload: ', [
        'isGenuine' => $isGenuine ? 'Yes' : 'No',
        'Response' => $resMapped,
    ]);
    Paytabs::getLogger()->error('Missed Data: ', [
        $resMapped->unMappedData(),
    ]);
}
