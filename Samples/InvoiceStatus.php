<?php

use Paytabs\Sdk\Http\Http;
use Paytabs\Sdk\Paytabs;
use Paytabs\Sdk\Request\Payload\PayloadsFactory;
use Paytabs\Sdk\Request\RequestsFactory;

$holder = PayloadsFactory::invoiceStatus();
$holder->buildInvoiceId($invoiceId);

$request = RequestsFactory::invoiceStatus($profile, $holder);

/** 
 * @var Http $http 
 * */
$http->setRequest($request);
$http->setDebugMode(true);

$response = $http->submit();

Paytabs::getLogger()->debug('InvoiceStatus POST response: ', [
    $response->getPayloadMapped(),
]);
