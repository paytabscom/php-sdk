<?php

use Paytabs\Sdk\Enums\ResponseStage;
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
use Paytabs\Sdk\Response\Response;

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
    ->buildPaymentMethods(
        PaymentMethods::init()
            ->includeMethod(Card::CODE)
            ->nextIf(true)
            ->excludeMethod('tabby')
            ->includeMethods(['card', 'tamara'])
            ->excludeMethods(['applepay', 'samsungpay'])
    )
;

$request = new PaymentRequest($gateway, $holder);

Paytabs::Logger()->debug('PaymentRequest holder Payload', $holder->getPayload()->getBody());
Paytabs::Logger()->debug('PaymentRequest Payload:', [$request->getPayload()]);

/** @var Http $http */
$http->setRequest($request);
$http->setDebugMode(false);

/** @var Response */
$response = $http->submit();

$responseStage = $response->getResponseStage();

switch ($responseStage) {
    case ResponseStage::Error:
        $resClassed = $response->asFailure();
        $resClassed->code;
        $resClassed->message;
        break;

    case ResponseStage::Redirect:
        $resClassed = $response->asRedirect();
        $resClassed->redirect_url;
        break;

    case ResponseStage::UnKnown:
    case ResponseStage::Completed:
    default:
        $resClassed = $response->getResponse();

        break;
}

Paytabs::Logger()->debug('PaymentRequest response: ', [$resClassed]);
