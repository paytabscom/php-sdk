<?php

use Paytabs\Sdk\Enums\TranClass;
use Paytabs\Sdk\Enums\TranType;
use Paytabs\Sdk\Holder\Builders\HostedPage;
use Paytabs\Sdk\Holder\Parts\CustomerDetails;
use Paytabs\Sdk\Holder\Parts\PaymentMethods;
use Paytabs\Sdk\Holder\Parts\ShippingDetails;
use Paytabs\Sdk\Http\Http;
use Paytabs\Sdk\PaymentMethod\Methods\Card;
use Paytabs\Sdk\Paytabs;
use Paytabs\Sdk\Request\Requests\PaymentRequest;

$holder = new HostedPage();
$holder
    ->buildCart("c01", "AED", 100.51, "Test")
    ->buildTransaction(TranType::Sale, TranClass::Ecom)
    ->buildPluginInfo('PHP-SDK', PHP_VERSION, null)
    ->buildCustomerDetails(
        (new CustomerDetails('Wajih SDK3', '0522222222', 'wajih@mail.com'))
            ->setAddress('ARE', 'Dubai', 'Dubai', 'nsr st', '11111')
            ->setIp('1.1.1.1')
    )
    ->buildShippingDetails(
        new ShippingDetails('Wajih 2')
    )
    ->buildHideShipping(true)
    ->buildTokenise(true)
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
    ->buildPaymentMethod('test')
    ->buildCustomerReference('customer-ref-1')
;

// Add Card Filter
$holder->buildCardFilter('4111,4000', 'only accept cards starting with 4111 or 4000');

$request = new PaymentRequest($gateway, $holder);

Paytabs::getLogger()->debug(
    'PaymentRequest holder Payload',
    $holder->getPayload()->getBody()
);
Paytabs::getLogger()->debug(
    'PaymentRequest Payload:',
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

Paytabs::getLogger()->debug('PaymentRequest Response: ', [
    'Mapped Auto' => $response->getPayloadMapped(),
]);
