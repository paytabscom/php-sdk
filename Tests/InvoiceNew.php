<?php

use Enums\TranClass;
use Enums\TranType;
use Holder\Builders\Invoice\Invoice;
use Holder\Parts\Invoice\Invoice as InvoicePart;
use Holder\Parts\Invoice\LineItem;
use Holder\Parts\Invoice\LineItems;
use Http\Http;
use Request\Requests\Invoice\NewInvoice;

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

$invoicePart = new InvoicePart;
$invoicePart
    // ->setCharges(0, 0, 0, 0)
    ->setDates(null, null, '2025-01-27T13:33:00+04:00')
    ->setLineItems($lineItems)
;

$holder
    ->setInvoice($invoicePart)
    ->setTransaction(TranType::Sale, TranClass::Ecom)
    ->setCart('inv-01', 'AED', 20, 'Invoice test')
    ->setPluginInfo('PHP', phpversion(), '')
;

// print_r($holder->getPayload()->getBody());
// die;

$request = new NewInvoice($gateway, $holder);

/** @var Http $http */
$http->setRequest($request);
$http->setDebugMode(true);

$response = $http->submit();
$resClassed = $response->getResponse();

print_r($resClassed);
