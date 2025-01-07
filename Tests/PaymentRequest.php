<?php

use Enums\ResponseStage;
use Enums\TranClass;
use Enums\TranType;
use Holder\Builders\HostedPage;
use Holder\Parts\CustomerDetails;
use Holder\Parts\ShippingDetails;
use Http\Http;
use Request\Requests\PaymentRequest;
use Response\Response;

$holder = new HostedPage();
$holder
    ->buildCart("c01", "AED", 100.51, "Test")
    ->buildTransaction(TranType::Sale, TranClass::Ecom)
    ->buildPluginInfo('PHP', phpversion(), '')
    ->buildCustomerDetails(
        new CustomerDetails('Wajih', '0522222222', 'wajih@mail.com', 'ARE', 'Dubai', 'Dubai', null, '1.1.1.1', '11111')
    )
    ->buildShippingDetails(
        new ShippingDetails('Wajih 2')
    )
    ->buildHideShipping(true)
    ->buildTokenise(true)
    ->buildURLs(null, $urlCallback)
;

$request = new PaymentRequest($gateway, $holder);

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

// var_dump($holder);
// var_dump($response);
print_r($resClassed);
