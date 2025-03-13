<?php

use Paytabs\Sdk\Enums\CardDiscountType;
use Paytabs\Sdk\Enums\FramedTarget;
use Paytabs\Sdk\Enums\TokenType;
use Paytabs\Sdk\Enums\TranClass;
use Paytabs\Sdk\Enums\TranType;
use Paytabs\Sdk\Request\Payload\Parts\CardDiscounts;
use Paytabs\Sdk\Request\Payload\Parts\CustomerDetails;
use Paytabs\Sdk\Request\Payload\Parts\Framed;
use Paytabs\Sdk\Request\Payload\Parts\Invoice as InvoicePart;
use Paytabs\Sdk\Request\Payload\Parts\Partials\CardDiscount;
use Paytabs\Sdk\Request\Payload\Parts\Partials\Invoice\LineItem;
use Paytabs\Sdk\Request\Payload\Parts\Partials\Invoice\LineItems;
use Paytabs\Sdk\Request\Payload\Parts\PaymentMethods;
use Paytabs\Sdk\Request\Payload\Parts\Token;
use Paytabs\Sdk\Request\Payload\Parts\TokenEnhanced;
use Paytabs\Sdk\Request\Payload\Parts\UserDefined;
use Paytabs\Sdk\Request\Payload\PayloadsFactory;
use Paytabs\Sdk\Http\Http;
use Paytabs\Sdk\PaymentMethod\Methods\Card;
use Paytabs\Sdk\Paytabs;
use Paytabs\Sdk\Request\RequestsFactory;

$holder = PayloadsFactory::recurringPayment();
$holder
    ->buildCart('ca-03', $configs['currency'], 700, 'Test')
    ->buildTransaction(TranType::Sale, TranClass::Recurring)
    ->buildPluginInfo('PHP-SDK', PHP_VERSION, null)
    ->buildCustomerDetails(
        (new CustomerDetails('Integrations SDK3', '0522222222', 'integrations@paytabs.com'))
            ->setAddress('ARE', 'Dubai', 'Dubai', 'nsr st', '11111')
    )
    ->buildUserDefined((new UserDefined())
        ->setUDF1('udf_1')
        ->setUDF8('udf_8')
        ->setUDF4('udf_4'))
    // ->buildTokenise(true)
    ->buildFramedObj(new Framed(true, FramedTarget::ReturnTop))
    ->buildURLs($urlReturn, $urlCallback, $returnUsingGet)
    ->buildAltCurrency('USD')
    ->buildConfigId($configs['config_id'])
    ->buildPaymentMethods(
        PaymentMethods::init()
            ->includeMethod(Card::CODE)
            ->nextIf(true)
            ->excludeMethod('tabby')
            ->includeMethods(['card', 'tamara'])
            ->excludeMethods(['applepay', 'samsungpay'])
    )
    ->buildCustomerReference('customer-ref-3')
    ->buildAirlineData('pnr-code-02')
    ->buildPaypageLang('ar')
;

$enableToken = true;
$enableTokenEnhanced = false;
if ($enableToken) {
    if ($enableTokenEnhanced) {
        $holder
            ->buildTokenEnhanced(new TokenEnhanced($token_enhanced, TokenType::RecurringFixed))
        ;
    } else {
        $holder
            ->buildToken(new Token($token))
        ;
    }
}

// Card Discounts
$cardDiscounts = new CardDiscounts(
    new CardDiscount(CardDiscountType::Fixed, 10.0, '4111', '10 Fixed Discount on Cards starting with 4111'),
    new CardDiscount(CardDiscountType::Percent, 5.0, '40000,5123', '5% Discount applied to Cards starting with 4000 or 5123')
);
$cardDiscounts->includeDiscount(
    new CardDiscount(CardDiscountType::Fixed, 15.0, '4111,40000', '15 Fixed Discount on Cards starting with 4111 or 40000')
);

// $holder->buildCardDiscounts($cardDiscounts);

// Add Donation Mode
// $holder->buildDonationMode(true, 10.5, 100.8);

// Invoice Object
$addInvoiceObject = false;
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

$request = RequestsFactory::paymentRequest($profile, $holder);

Paytabs::getLogger()->debug(
    'RecurringPayment holder Payload',
    $holder->getPayload()->getBody()
);
Paytabs::getLogger()->debug(
    'RecurringPayment Payload:',
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

Paytabs::getLogger()->debug('RecurringPayment Response: ', [
    'Mapped Auto' => $response->getPayloadMapped(),
]);
