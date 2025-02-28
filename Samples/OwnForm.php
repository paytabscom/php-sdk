<?php

use Paytabs\Sdk\Enums\TranClass;
use Paytabs\Sdk\Enums\TranType;
use Paytabs\Sdk\Holder\Builders\OwnForm;
use Paytabs\Sdk\Holder\Parts\CustomerDetails;
use Paytabs\Sdk\Holder\Parts\Invoice as InvoicePart;
use Paytabs\Sdk\Holder\Parts\Partials\Invoice\LineItem;
use Paytabs\Sdk\Holder\Parts\Partials\Invoice\LineItems;
use Paytabs\Sdk\Holder\Parts\UserDefined;
use Paytabs\Sdk\Http\Http;
use Paytabs\Sdk\Paytabs;
use Paytabs\Sdk\Request\Requests\PaymentRequest;

$holder = new OwnForm();
$holder
    ->buildCart('own-form', $configs['currency'], 700, 'Test')
    ->buildTransaction(TranType::Sale, TranClass::Ecom)
    ->buildPluginInfo('PHP-SDK', PHP_VERSION, null)
    ->buildCustomerDetails(
        (new CustomerDetails('Integrations SDK3', '0522222222', 'integrations@paytabs.com'))
            ->setAddress('ARE', 'Dubai', 'Dubai', 'nsr st', '11111')
            ->setIp('1.1.1.1')
    )
    ->buildUserDefined((new UserDefined())
        ->setUDF2('udf_2')
        ->setUDF8('udf_8')
        ->setUDF4('udf_4'))
    ->buildHideShipping(true)
    ->buildTokenise(true)
    ->buildURLs($urlReturn, $urlCallback, $returnUsingGet)
    ->buildCustomerReference('customer-ref-2')
    // ->buildPaymentMethod('card') // throws Exception
;

$direct = true;

$card_redirect = '4000 0000 0000 0002';
$card_direct = '4111-1111 1111 1111';
$pan = $direct ? $card_direct : $card_redirect;

$holder->buildCardDetails($pan, 2030, 12, '123');

// Invoice Object
$addInvoiceObject = true;
$lineItem1 = LineItem::init()
    ->setTitle('sku', 'desc', 'https://test.com')
    ->setPrice(1, 100, 100)
;

$item2 = LineItem::init()
    ->setTitle('item-02')
    ->setPrice(2, 300, 600)
;

$lineItems = new LineItems($lineItem1, $item2);

$invoicePart = new InvoicePart();
$invoicePart
    // ->setCharges(0, 0, 0, 0)
    ->setDates(null, null, '2026-01-27T13:33:00+04:00')
    ->setLineItems($lineItems)
;

if ($addInvoiceObject) {
    $holder->buildInvoice($invoicePart);
}

$request = new PaymentRequest($gateway, $holder);

Paytabs::getLogger()->debug(
    'OwnForm holder Payload',
    $holder->getPayload()->getBody()
);
Paytabs::getLogger()->debug(
    'OwnForm Payload:',
    [$request->getPayload()]
);

/** @var Http $http */
$http->setRequest($request);
$http->setDebugMode(false);

$response = $http->submit();

if ($response->isFailure()) {
    $resClassed = $response->getFailure();
} elseif ($response->isRedirect()) {
    $resClassed = $response->getRedirect();
} else {
    $resClassed = $response->getPayload()->getMapped();
}

// case ResponseStage::UnKnown:
// case ResponseStage::Completed:

$resMapped = $response->getPayloadMapped();
Paytabs::getLogger()->debug('OwnForm Response: ', [
    'Mapped Auto' => $resMapped,
]);
