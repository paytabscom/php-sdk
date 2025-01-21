<?php

use Paytabs\Sdk\Holder\Builders\Invoice\InvoiceStatus as PartInvoiceStatus;
use Paytabs\Sdk\Http\Http;
use Paytabs\Sdk\Paytabs;
use Paytabs\Sdk\Request\Requests\Invoice\InvoiceStatus;

$holder = new PartInvoiceStatus();
$holder->buildInvoiceId($invoiceId);

$request = new InvoiceStatus($gateway, $holder);

//

/** @var Http $http */
$http->setRequest($request);
$http->setDebugMode(true);

$response = $http->submit();
$resClassed = $response->getResponse();

Paytabs::getLogger()->debug('InvoiceStatus POST response: ', [$resClassed]);
