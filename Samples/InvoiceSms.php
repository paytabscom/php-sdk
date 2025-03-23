<?php

use Paytabs\Sdk\Http\Http;
use Paytabs\Sdk\Paytabs;
use Paytabs\Sdk\Request\Payload\PayloadsFactory;
use Paytabs\Sdk\Request\RequestsFactory;

$holder = PayloadsFactory::invoiceSms();
$holder->buildInvoiceId($invoiceId)
        ->buildInvoiceSmsBody($configs['profile_id'],'+201072580224');

$request = RequestsFactory::invoiceSms($profile, $holder);

/** @var Http $http */
$http->setRequest($request);
$http->setDebugMode(true);

$response = $http->submit();

Paytabs::getLogger()->debug('InvoiceSms POST response: ', [
    $response->getPayloadMapped(),
]);
