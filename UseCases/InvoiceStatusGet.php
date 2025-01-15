<?php

use Paytabs\Sdk\Holder\Builders\Invoice\InvoiceStatusGet as PartInvoiceStatusGet;
use Paytabs\Sdk\Http\Http;
use Paytabs\Sdk\Paytabs;
use Paytabs\Sdk\Request\Requests\Invoice\InvoiceStatusGet;

$holder = new PartInvoiceStatusGet();
$holder->buildInvoiceId($invoiceId);

$request = new InvoiceStatusGet($gateway, $holder);

//

/** @var Http $http */
$http->setRequest($request);
$http->setDebugMode(true);

$response = $http->submit();
$resClassed = $response->getResponse();

Paytabs::Logger()->debug('InvoiceStatus GET response: ', [$resClassed]);
