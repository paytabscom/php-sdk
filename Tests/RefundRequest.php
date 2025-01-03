<?php

use Enums\ResponseStage;
use Enums\TranClass;
use Enums\TranType;
use Holder\Builders\Followup;
use Holder\Builders\Followup\Refund;
use Http\Http;
use Request\Requests\PaymentRequest;
use Response\Response;

//

$refundHolder1 = new Followup();
$refundHolder1
    ->setTransaction(TranType::Refund, TranClass::Ecom)
    ->setTransactionRef($trxRef)
    ->setCart('refund_01', 'AED', 10, 'Refund test')
    ->setPluginInfo('PHP', phpversion(), '')
    ->setURLs(null, $urlCallback)
;


$refundHolder2 = new Refund();
$refundHolder2
    ->setTransactionRef($trxRef)
    ->setCart('refund_01', 'AED', 10, 'Refund test')
    ->setPluginInfo('PHP', phpversion(), '')
    ->setURLs(null, $urlCallback)
;


$request = new PaymentRequest($gateway, $refundHolder2);

/** @var Http $http */
$http->setRequest($request);

$response = new Response();
$http->submit($response);

$responseType = $response->getResponseStage();

if ($responseType == ResponseStage::Error) {
    $resMapped = $response->asFailure();
} elseif ($responseType == ResponseStage::Completed) {
    $resMapped = $response->getResponse();
}

// var_dump($refundHolder);
// var_dump($response);
print_r($resMapped);
