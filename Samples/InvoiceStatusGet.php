<?php

use Paytabs\Sdk\Http\Http;
use Paytabs\Sdk\Paytabs;
use Paytabs\Sdk\Request\Payload\PayloadsFactory;
use Paytabs\Sdk\Request\RequestsFactory;

$invoiceId = $_GET['invoice_id'] ?? $invoiceId;

$holder = PayloadsFactory::invoiceStatus(true);
$holder->buildInvoiceId($invoiceId);

$request = RequestsFactory::invoiceStatusAsGet($profile, $holder);

/** @var Http $http */
$http->setRequest($request);
$http->setDebugMode(true);

$response = $http->submit();

Paytabs::getLogger()->debug('InvoiceStatus GET response: ', [
    $response->getPayloadMapped(),
]);
