<?php

use Paytabs\Sdk\Enums\TranClass;
use Paytabs\Sdk\Enums\TranType;
use Paytabs\Sdk\Http\Http;
use Paytabs\Sdk\Paytabs;
use Paytabs\Sdk\Request\Payload\Parts\Invoice as InvoicePart;
use Paytabs\Sdk\Request\Payload\Parts\Partials\Invoice\LineItem;
use Paytabs\Sdk\Request\Payload\Parts\Partials\Invoice\LineItems;
use Paytabs\Sdk\Request\Payload\Parts\UserDefined;
use Paytabs\Sdk\Request\Payload\PayloadsFactory;
use Paytabs\Sdk\Request\RequestsFactory;

$holder = PayloadsFactory::invoiceCreate();

$lineItem1 = LineItem::init()
    ->setTitle('sku', 'desc', 'https://test.com')
    ->setPrice(1, 10, 10)
;

$item2 = LineItem::init()
    ->setTitle('item-02')
    ->setPrice(1, 10, 10)
;

$lineItems = new LineItems();
$lineItems->addLineItem($lineItem1);
$lineItems->addLineItem($item2);

$invoicePart = new InvoicePart();
$invoicePart
    // ->setCharges(0, 0, 0, 0)
    ->setDates(null, null, '2026-01-27T13:33:00+04:00')
    ->setLineItems($lineItems)
;

$holder
    ->buildInvoice($invoicePart)
    ->buildTransaction(TranType::Sale, TranClass::Ecom)
    ->buildCart('inv-01', $configs['currency'], 20, 'Invoice test')
    ->buildPluginInfo('PHP', PHP_VERSION, '')
    ->buildUserDefined((new UserDefined())
        ->setUDF1('udf_1')
        ->setUDF8(null)
        ->setUDF4('udf_4'))
    ->buildURLs($urlReturn, $urlCallback)
    ->buildPaymentMethod('card')
;

Paytabs::getLogger()->debug(
    'InvoiceNew holder Payload: ',
    $holder->getPayload()->getBody()
);

$request = RequestsFactory::invoiceNew($profile, $holder);

/** @var Http $http */
$http->setRequest($request);
$http->setDebugMode(true);

$response = $http->submit();

Paytabs::getLogger()->debug('InvoiceNew response: ', [
    $response->getPayloadMapped(),
]);
