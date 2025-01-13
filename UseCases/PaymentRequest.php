<?php

use Paytabs\Sdk\Enums\ResponseStage;
use Paytabs\Sdk\Enums\TranClass;
use Paytabs\Sdk\Enums\TranType;
use Paytabs\Sdk\Holder\Builders\HostedPage;
use Paytabs\Sdk\Holder\Parts\CustomerDetails;
use Paytabs\Sdk\Holder\Parts\PaymentMethods;
use Paytabs\Sdk\Holder\Parts\ShippingDetails;
use Paytabs\Sdk\Http\Http;
use Paytabs\Sdk\Request\Requests\PaymentRequest;
use Paytabs\Sdk\Response\Response;

$holder = new HostedPage();
$holder
    ->buildCart("c01", "AED", 100.51, "Test")
    ->buildTransaction(TranType::Sale, TranClass::Ecom)
    ->buildPluginInfo('PHP', phpversion(), null)
    ->buildCustomerDetails(
        (new CustomerDetails('Wajih', '0522222222', 'wajih@mail.com'))
            ->setAddress('ARE', 'Dubai', 'Dubai', null, '11111')
            ->setIp('1.1.1.1')
    )
    ->buildShippingDetails(
        new ShippingDetails('Wajih 2')
    )
    ->buildHideShipping(true)
    ->buildTokenise(true)
    ->buildURLs(null, $urlCallback)
    ->buildAltCurrency('USD')
    ->buildPaymentMethods(
        PaymentMethods::init()
            ->includeMethod('card')
            ->nextIf(true)
            ->excludeMethod('tabby')
            ->includeMethods(['card', 'tamara'])
            ->excludeMethods(['applepay', 'samsungpay'])
    )
;

$request = new PaymentRequest($gateway, $holder);

// print_r($holder->getPayload()->getBody());
// echo '<hr>';
print_r($request->getPayload());
die;

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

print_r($resClassed);
