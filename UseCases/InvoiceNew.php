<?php

use Paytabs\Sdk\Enums\TranClass;
use Paytabs\Sdk\Enums\TranType;
use Paytabs\Sdk\Holder\Builders\Invoice\Invoice;
use Paytabs\Sdk\Holder\Parts\Invoice\Invoice as InvoicePart;
use Paytabs\Sdk\Holder\Parts\Invoice\LineItem;
use Paytabs\Sdk\Holder\Parts\Invoice\LineItems;
use Paytabs\Sdk\Http\Http;
use Paytabs\Sdk\Paytabs;
use Paytabs\Sdk\Request\Requests\Invoice\NewInvoice;

$holder = new Invoice();

$lineItem1 = LineItem::init()
    ->setTitle('sku', 'desc', 'https://test.com')
    ->setPrice(1, 10, 10);

$item2 = LineItem::init()
    ->setTitle('item-02')
    ->setPrice(1, 10, 10);

$lineItems = new LineItems();
$lineItems->addLineItem($lineItem1);
$lineItems->addLineItem($item2);

$invoicePart = new InvoicePart();
$invoicePart
    // ->setCharges(0, 0, 0, 0)
    ->setDates(null, null, '2025-01-27T13:33:00+04:00')
    ->setLineItems($lineItems)
;

$holder
    ->buildInvoice($invoicePart)
    ->buildTransaction(TranType::Sale, TranClass::Ecom)
    ->buildCart('inv-01', 'AED', 20, 'Invoice test')
    ->buildPluginInfo('PHP', PHP_VERSION, '')
;

Paytabs::getLogger()->debug(
    'InvoiceNew holder Payload: ',
    $holder->getPayload()->getBody()
);

$request = new NewInvoice($gateway, $holder);

/** @var Http $http */
$http->setRequest($request);
$http->setDebugMode(true);

$response = $http->submit();
$resClassed = $response->getResponse();

Paytabs::getLogger()->debug('InvoiceNew response: ', [$resClassed]);
