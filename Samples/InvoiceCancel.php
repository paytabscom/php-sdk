<?php

use Paytabs\Sdk\Http\Http;
use Paytabs\Sdk\Paytabs;
use Paytabs\Sdk\Request\Payload\PayloadsFactory;
use Paytabs\Sdk\Request\RequestsFactory;

$holder = PayloadsFactory::invoiceCancel();
$holder->buildInvoiceId($invoiceId);

$request = RequestsFactory::invoiceCancel($profile, $holder);

/** @var Http $http */
$http->setRequest($request);
$http->setDebugMode(true);

$response = $http->submit();

Paytabs::getLogger()->debug('InvoiceCancel POST response: ', [
    $response->getPayloadMapped(),
]);
