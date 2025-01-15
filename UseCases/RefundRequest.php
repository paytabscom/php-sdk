<?php

use Paytabs\Sdk\Enums\ResponseStage;
use Paytabs\Sdk\Enums\TranClass;
use Paytabs\Sdk\Enums\TranType;
use Paytabs\Sdk\Holder\Builders\Followup;
use Paytabs\Sdk\Holder\Builders\Followup\Refund;
use Paytabs\Sdk\Http\Http;
use Paytabs\Sdk\Paytabs;
use Paytabs\Sdk\Request\Requests\PaymentRequest;

//

$holder1 = new Followup();
$holder1
    ->buildTransaction(TranType::Refund, TranClass::Ecom)
    ->buildTransactionRef($trxRef)
    ->buildCart('refund_01', 'AED', 10, 'Refund test')
    ->buildPluginInfo('PHP', PHP_VERSION, '')
    ->buildURLs(null, $urlCallback)
;


$holder2 = new Refund();
$holder2
    ->buildTransactionRef($trxRef)
    ->buildCart('refund_01', 'AED', 10, 'Refund test')
    ->buildPluginInfo('PHP', PHP_VERSION, '')
    ->buildURLs(null, $urlCallback)
;


$request = new PaymentRequest($gateway, $holder2);

/** @var Http $http */
$http->setRequest($request);

$response = $http->submit();

$responseType = $response->getResponseStage();

if ($responseType === ResponseStage::Error) {
    $resMapped = $response->asFailure();
} elseif ($responseType === ResponseStage::Completed) {
    $resMapped = $response->getResponse();
}

Paytabs::Logger()->debug('Refund Response', [$resMapped]);
