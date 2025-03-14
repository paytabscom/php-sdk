<?php

use Paytabs\Sdk\Enums\TranClass;
use Paytabs\Sdk\Enums\TranType;
use Paytabs\Sdk\Http\Http;
use Paytabs\Sdk\Paytabs;
use Paytabs\Sdk\Request\Payload\PayloadsFactory;
use Paytabs\Sdk\Request\RequestsFactory;

$holder1 = PayloadsFactory::followup();
$holder1
    ->buildTransaction(TranType::Refund(), TranClass::Ecom())
    ->buildTransactionRef($trxRef)
    ->buildCart('refund_01', $configs['currency'], 10, 'Refund test')
    ->buildPluginInfo('PHP', PHP_VERSION, '')
    ->buildURLs(null, $urlCallback)
;

$holder2 = PayloadsFactory::refund();
$holder2
    ->buildTransactionRef($trxRef)
    ->buildCart('refund_01', $configs['currency'], 10, 'Refund test')
    ->buildPluginInfo('PHP', PHP_VERSION, '')
    ->buildURLs(null, $urlCallback)
;

$request = RequestsFactory::paymentRequest($profile, $holder2);

/** @var Http $http */
$http->setRequest($request);

$response = $http->submit();

if ($response->isFailure()) {
    $resClassed = $response->getFailure();
} else {
    $resClassed = $response->getPayload()->getMapped();
}

Paytabs::getLogger()->debug('Refund Response', [$resClassed]);
