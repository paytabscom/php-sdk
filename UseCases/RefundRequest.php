<?php

use Paytabs\Sdk\Enums\ResponseStage;
use Paytabs\Sdk\Enums\TranClass;
use Paytabs\Sdk\Enums\TranType;
use Paytabs\Sdk\Holder\Builders\Followup;
use Paytabs\Sdk\Holder\Builders\Followup\Refund;
use Paytabs\Sdk\Http\Http;
use Paytabs\Sdk\Request\Requests\PaymentRequest;

//

$refundHolder1 = new Followup();
$refundHolder1
    ->buildTransaction(TranType::Refund, TranClass::Ecom)
    ->buildTransactionRef($trxRef)
    ->buildCart('refund_01', 'AED', 10, 'Refund test')
    ->buildPluginInfo('PHP', phpversion(), '')
    ->buildURLs(null, $urlCallback)
;


$refundHolder2 = new Refund();
$refundHolder2
    ->buildTransactionRef($trxRef)
    ->buildCart('refund_01', 'AED', 10, 'Refund test')
    ->buildPluginInfo('PHP', phpversion(), '')
    ->buildURLs(null, $urlCallback)
;


$request = new PaymentRequest($gateway, $refundHolder2);

/** @var Http $http */
$http->setRequest($request);

$response = $http->submit();

$responseType = $response->getResponseStage();

if ($responseType == ResponseStage::Error) {
    $resMapped = $response->asFailure();
} elseif ($responseType == ResponseStage::Completed) {
    $resMapped = $response->getResponse();
}

print_r($resMapped);
