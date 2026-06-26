<?php

use Paytabs\Sdk\Enums\TranClass;
use Paytabs\Sdk\Enums\TranType;
use Paytabs\Sdk\Http\Http;
use Paytabs\Sdk\Paytabs;
use Paytabs\Sdk\Profile\Profile;
use Paytabs\Sdk\Request\Payload\Parts\CustomerDetails;
use Paytabs\Sdk\Request\Payload\PayloadsFactory;
use Paytabs\Sdk\Request\RequestsFactory;

/**
 * @var Profile $profile
 * @var Http    $http
 * @var string  $urlReturn
 * @var string  $urlCallback
 * @var bool    $returnUsingGet
 * @var string  $_currency
 */
if (!isset($profile, $http, $urlReturn, $urlCallback, $returnUsingGet, $_currency)) {
    throw new RuntimeException('Required variables are not set: $profile, $http, $urlReturn, $urlCallback, $returnUsingGet, $_currency');
}

$holder = PayloadsFactory::createOwnForm();
$holder
    ->buildCart('own-form', $_currency, 700, 'Test')
    ->buildTransaction(TranType::Sale, TranClass::Ecom)
    ->buildPluginInfo('PHP-SDK', PHP_VERSION, null)
    ->buildCustomerDetails(
        CustomerDetails::init('Integrations SDK3', '0522222222', 'integrations@paytabs.com')
            ->setAddress('ARE', 'Dubai', 'Dubai', 'nsr st', '11111')
            ->setIp('1.1.1.1')
    )
    ->buildHideShipping(true)
    ->buildURLs($urlReturn, $urlCallback, $returnUsingGet)
    // ->buildPaymentMethod('card') // throws Exception
;

$threeDSecure = true;

$card_redirect = '4000 0000 0000 0002';
$card_direct = '4111-1111 1111 1111';
$pan = $threeDSecure ? $card_redirect : $card_direct;

$holder->buildCardDetails($pan, 2030, 12, '123');

$request = RequestsFactory::createPaymentRequest($profile, $holder);

Paytabs::getLogger()->debug(
    'OwnForm holder Payload',
    $holder->getPayload()->getBody()
);
Paytabs::getLogger()->debug(
    'OwnForm Payload:',
    [$request->getPayload()]
);

$http->setRequest($request);
$http->setDebugMode(false);

$response = $http->submit();

if ($response->isFailure()) {
    $resClassed = $response->getFailure();
} elseif ($response->isRedirect()) {
    $resClassed = $response->getRedirect();
} else {
    $resClassed = $response->getPayload()->getMapped();
}

$resMapped = $response->getPayloadMapped();
Paytabs::getLogger()->debug('OwnForm Response: ', [
    'Mapped Auto' => $resMapped,
]);
Paytabs::getLogger()->error('OwnForm Missed Data: ', [
    'Missed Data' => $resMapped->unMappedData(),
]);
