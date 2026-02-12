<?php

use Paytabs\Sdk\Enums\CardDiscountType;
use Paytabs\Sdk\Enums\TokenPaymentFrequency;
use Paytabs\Sdk\Enums\TokenType;
use Paytabs\Sdk\Enums\TranClass;
use Paytabs\Sdk\Enums\TranType;
use Paytabs\Sdk\Enums\Language;
use Paytabs\Sdk\Http\Http;
use Paytabs\Sdk\PaymentMethod\Methods\Card;
use Paytabs\Sdk\Paytabs;
use Paytabs\Sdk\Request\Payload\Parts\CardDiscounts;
use Paytabs\Sdk\Request\Payload\Parts\CustomerDetails;
use Paytabs\Sdk\Request\Payload\Parts\Invoice as InvoicePart;
use Paytabs\Sdk\Request\Payload\Parts\Partials\CardDiscount;
use Paytabs\Sdk\Request\Payload\Parts\Partials\Invoice\LineItem;
use Paytabs\Sdk\Request\Payload\Parts\Partials\Invoice\LineItems;
use Paytabs\Sdk\Request\Payload\Parts\PaymentMethods;
use Paytabs\Sdk\Request\Payload\Parts\ShippingDetails;
use Paytabs\Sdk\Request\Payload\Parts\TokeniseEnhanced;
use Paytabs\Sdk\Request\Payload\Parts\UserDefined;
use Paytabs\Sdk\Request\Payload\PayloadsFactory;
use Paytabs\Sdk\Request\RequestsFactory;

$holder = PayloadsFactory::hostedPage();
$holder
    ->buildCart('cart01', $configs['currency'], 700, 'Test')
    ->buildTransaction(TranType::Sale, TranClass::Ecom)
    ->buildPluginInfo('PHP-SDK', PHP_VERSION, null)
    ->buildCustomerDetails(
        (new CustomerDetails('Integrations SDK3', '0522222222', 'integrations@paytabs.com'))
            ->setAddress('ARE', 'Dubai', 'Dubai', 'nsr st', '11111')
    )
    ->buildUserDefined((new UserDefined())
        ->setUDF1('udf_1')
        ->setUDF8('udf_8')
        ->setUDF4('udf_4'))
    ->buildShippingDetails(
        new ShippingDetails('Integrations 2')
    )
    ->buildHideShipping(true)
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
    // ->buildPaymentMethod('test')
    ->buildCustomerReference('customer-ref-1')
    ->buildPaypageLang(Language::English)
;

$tokenise = true;
$tokeniseEnhanced = false;
if ($tokenise) {
    if ($tokeniseEnhanced) {
        $holder
            ->buildTokeniseEnhancedObj(
                (new TokeniseEnhanced(
                    TokenType::RecurringFixed,
                    2,
                    true,
                )
                )->setPaymentInfo(
                    TokenPaymentFrequency::Monthly,
                    10,
                    null,
                    1,
                    '30-MAR-2025',
                    null
                )->setCounter(1, 10)
            )
        ;
    } else {
        $holder->buildTokenise(true);
    }
}

// Add Card Filter
$holder->buildCardFilter('4111,4000', 'only accept cards starting with 4111 or 4000');

// Card Discounts
$cardDiscounts = new CardDiscounts(
    new CardDiscount(CardDiscountType::Fixed, 10.0, '4111', '10 Fixed Discount on Cards starting with 4111'),
    new CardDiscount(CardDiscountType::Percent, 5.0, '40000,5123', '5% Discount applied to Cards starting with 4000 or 5123')
);
$cardDiscounts->includeDiscount(
    new CardDiscount(CardDiscountType::Fixed, 15.0, '4111,40000', '15 Fixed Discount on Cards starting with 4111 or 40000')
);
/*$cardDiscounts->includeDiscount(
    new CardDiscount(CardDiscountType::Fixed, 10, 'AA', 'Invalid Pattern')
);*/

$holder->buildCardDiscounts($cardDiscounts);

// Add Donation Mode
// $holder->buildDonationMode(true, 10.5, 100.8);

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

$request = RequestsFactory::paymentRequest($profile, $holder);

Paytabs::getLogger()->debug(
    'PaymentRequest holder Payload',
    $holder->getPayload()->getBody()
);

echo '<hr>';

Paytabs::getLogger()->debug(
    'PaymentRequest Payload:',
    [$request->getPayload()]
);

echo '<hr>';

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

echo '<hr>';

$resMapped = $response->getPayloadMapped();
Paytabs::getLogger()->debug('PaymentRequest Response: ', [
    'Mapped Auto' => $resMapped,
]);

echo '<hr>';

Paytabs::getLogger()->error('Missed Data: ', [
    $resMapped->unMappedData(),
]);
