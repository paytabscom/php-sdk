<?php

use Paytabs\Sdk\Enums\TranClass;
use Paytabs\Sdk\Enums\TranType;
use Paytabs\Sdk\Http\Http;
use Paytabs\Sdk\Paytabs;
use Paytabs\Sdk\Profile\Profile;
use Paytabs\Sdk\Request\Payload\Parts\Invoice as InvoicePart;
use Paytabs\Sdk\Request\Payload\Parts\Partials\Invoice\LineItem;
use Paytabs\Sdk\Request\Payload\Parts\Partials\Invoice\LineItems;
use Paytabs\Sdk\Request\Payload\PayloadsFactory;
use Paytabs\Sdk\Request\RequestsFactory;
use Paytabs\Sdk\Response\Payload\Payloads\Invoice\NewInvoice;

/**
 * @var Profile $profile
 * @var Http    $http
 * @var string  $_currency
 */
if (!isset($profile, $http, $_currency)) {
    throw new RuntimeException('Required variables are not set: $profile, $http, $_currency');
}

$holder = PayloadsFactory::invoiceCreate();

$item1 = LineItem::init()
    ->setTitle('sku', 'desc', 'https://test.com')
    ->setPrice(1, 10, 10)
;

$item2 = LineItem::init()
    ->setTitle('item-02')
    ->setPrice(3, 10, 30)
;

$lineItems = new LineItems($item1, $item2);

$invoicePart = new InvoicePart();
// Expiry date after 8 days from now, in ATOM format
$expiryDate = DateTimeImmutable::createFromFormat(DateTimeInterface::ATOM, '+8 days');
$invoicePart
    // ->setCharges(0, 0, 0, 0)
    ->setDates(null, null, $expiryDate)
    ->setLineItems($lineItems)
;

$holder
    ->buildInvoice($invoicePart)
    ->buildTransaction(TranType::Sale, TranClass::Ecom)
    ->buildCart('inv-01', $_currency, 40, 'Invoice test')
    ->buildCustomerReference('customer-01')
    ->buildPaymentMethod('card')
    ->buildPluginInfo('PHP', PHP_VERSION, Paytabs::getVersion())
;

Paytabs::getLogger()->debug(
    'InvoiceNew holder Payload: ',
    $holder->getPayload()->getBody()
);

$request = RequestsFactory::createInvoiceNew($profile, $holder);

$http->setRequest($request);
$http->setDebugMode(true);

$response = $http->submit();

if ($response->isProcessed()) {
    /** @var NewInvoice $invoiceNew */
    $invoiceNew = $response->getPayloadMapped();
    Paytabs::getLogger()->debug('New Invoice created: ', [
        'invoice_id' => $invoiceNew->invoice_id,
        'payment_url' => $invoiceNew->invoice_link,
    ]);
} else {
    Paytabs::getLogger()->debug('InvoiceNew response: ', [
        $response->getPayloadMapped(),
    ]);
}
