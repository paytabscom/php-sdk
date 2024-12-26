<?php

use Enums\TranClass;
use Enums\TranType;
use Holder\Builders\Followup;
use Holder\Builders\Followup\Refund;
use Request\Requests\PaymentRequest;
use Response\Payload\Completed;

/*
$refundHolder = new Followup();
$refundHolder
    ->setTransaction(TranType::Refund, TranClass::Ecom)
    ->setTransactionRef('TST2435402180636')
    ->setCart('refund_01', 'AED', 10, 'Refund test')
    ->setPluginInfo('PHP', phpversion(), '')
    ->setURLs(null, 'https://webhook.site/1ae2a776-cc70-44e5-adf0-d90966843f46')
;
*/

$refundHolder = new Refund();
$refundHolder
    ->setTransactionRef('TST2435402180636')
    ->setCart('refund_01', 'AED', 10, 'Refund test')
    ->setPluginInfo('PHP', phpversion(), '')
    ->setURLs(null, 'https://webhook.site/1ae2a776-cc70-44e5-adf0-d90966843f46')
;


$request = new PaymentRequest($gateway, $refundHolder);
$http->setRequest($request);

$response = $http->submit();

$res_completed = $jsonMapper->map($response->getJson(), Completed::class);

var_dump($refundHolder);
var_dump($response);
var_dump($res_completed);
