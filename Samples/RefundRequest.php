<?php

use Paytabs\Sdk\Enums\TranClass;
use Paytabs\Sdk\Enums\TranType;
use Paytabs\Sdk\Http\Http;
use Paytabs\Sdk\Paytabs;
use Paytabs\Sdk\Profile\Profile;
use Paytabs\Sdk\Request\Payload\PayloadsFactory;
use Paytabs\Sdk\Request\RequestsFactory;

/**
 * @var Profile $profile
 * @var string $trxRef
 * @var Http $http
 * @var string $urlCallback
 * @var string $_currency
 */

if (!isset($profile, $trxRef, $http, $urlCallback, $_currency)) {
    throw new \RuntimeException('Required variables are not set: $profile, $trxRef, $http, $urlCallback, $_currency');
}

//

// Build Refund payload using the generic Followup class
$holder1 = PayloadsFactory::followup();
$holder1
    ->buildTransaction(TranType::Refund, TranClass::Ecom)
    ->buildTransactionRef($trxRef)
    ->buildCart('refund_01', $_currency, 1, 'Refund test')
    ->buildPluginInfo('PHP', PHP_VERSION, Paytabs::getVersion())
    ->buildURLs(null, $urlCallback)
;

// Submit the Refund request using the main Refund class
$holder2 = PayloadsFactory::refund();
$holder2
    ->buildTransactionRef($trxRef)
    ->buildCart('refund_01', $_currency, 2, 'Refund test')
    ->buildPluginInfo('PHP', PHP_VERSION, Paytabs::getVersion())
    ->buildURLs(null, $urlCallback)
;

$request = RequestsFactory::paymentRequest($profile, $holder2);

$http->setRequest($request);

$response = $http->submit();

if ($response->isFailure()) {
    $resClassed = $response->getFailure();
} else {
    $resClassed = $response->getPayload()->getMapped();
}

Paytabs::getLogger()->debug('Refund Response', [$resClassed]);
