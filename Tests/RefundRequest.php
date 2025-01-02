<?php

use Enums\ResponseStage;
use Enums\TranClass;
use Enums\TranType;
use Holder\Builders\Followup;
use Holder\Builders\Followup\Refund;
use Request\Requests\PaymentRequest;

//

$trxRef = 'TST2500202198615';
$urlCallback = 'https://webhook.site/1c481b22-9981-4372-85cc-c79bb0e342cc';

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
$http->setRequest($request);

$response = $http->submit();
$responseType = $response->getResponseStage();

if ($responseType == ResponseStage::Error) {
    $resMapped = $response->asFailure();
} elseif ($responseType == ResponseStage::Completed) {
    $resMapped = $response->getResponse();
}

// var_dump($refundHolder);
// var_dump($response);
var_dump($resMapped);
