<?php

use Enums\TranClass;
use Enums\TranType;
use Holder\Builders\Followup;
use Request\Requests\PaymentRequest;

$refundHolder = new Followup();
$refundHolder
    ->setPluginInfo('PHP', phpversion(), '')
    ->setCart('refund_01', 'AED', 10, 'Refund test')
    ->setTransaction(TranType::Refund, TranClass::Ecom)
    ->setTransactionRef('TST2435402180636')
    ->setURLs(null, 'https://webhook.site/1ae2a776-cc70-44e5-adf0-d90966843f46')
;


$request = new PaymentRequest($gateway, $refundHolder);
$http->setRequest($request);

$response = $http->submit();

var_dump($refundHolder);
var_dump($response);

