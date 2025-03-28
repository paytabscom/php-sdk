<?php

use Paytabs\Sdk\Http\Http;
use Paytabs\Sdk\Paytabs;
use Paytabs\Sdk\Request\Payload\PayloadsFactory;
use Paytabs\Sdk\Request\RequestsFactory;

$holder = PayloadsFactory::invoiceSms();
$holder->buildInvoiceId($invoiceId)
    ->buildInvoiceSmsBody($phoneNumber)
;

$request = RequestsFactory::invoiceSms($profile, $holder);

Paytabs::getLogger()->debug('InvoiceSms POST Request: ', [
    $request,
]);

/*
 * HTTP manager
 * @var Http $http
 * */
$http->setRequest($request);
$http->setDebugMode(true);

$response = $http->submit();

Paytabs::getLogger()->debug('InvoiceSms POST response: ', [
    $response,
]);

$resMapped = $response->getPayloadMapped();
Paytabs::getLogger()->debug('InvoiceSms POST response Mapped Data: ', [
    $resMapped,
]);

Paytabs::getLogger()->error('Missed Data: ', [
    $resMapped->unMappedData(),
]);
