<?php

use Paytabs\Sdk\Http\Http;
use Paytabs\Sdk\Paytabs;
use Paytabs\Sdk\Request\Payload\PayloadsFactory;
use Paytabs\Sdk\Request\RequestsFactory;

$holder = PayloadsFactory::InvoiceMarkPaid();
$holder->buildInvoiceMarkPaid($invoiceId, $invoiceCurrency, $invoiceTotal, $payMethod, $payDescription);

$request = RequestsFactory::InvoiceMarkPaid($profile, $holder);

/** @var Http $http */
$http->setRequest($request);
$http->setDebugMode(true);

$response = $http->submit();

Paytabs::getLogger()->debug('InvoiceMarkPaid POST response: ', [
    $response->getPayloadMapped(),
]);
