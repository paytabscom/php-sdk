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
    ->setCart("c01", "AED", 100.51, "Test")
    ->setTransaction(TranType::Sale, TranClass::Ecom)
    ->setPluginInfo('PHP', phpversion(), '')
    ->setCustomerDetails(
        new CustomerDetails('Wajih', '0522222222', 'wajih@mail.com', 'ARE', 'Dubai', 'Dubai', null, '1.1.1.1', '11111')
    )
    ->setShippingDetails(
        new ShippingDetails('Wajih 2')
    )
    ->setHideShipping(true)
    ->setTokenise(true)
    ->setURLs(null, 'https://webhook.site/1ae2a776-cc70-44e5-adf0-d90966843f46')
;

$request = new PaymentRequest($gateway, $holder);

/** @var Http $http */
$http->setRequest($request);
$http->setDebugMode(false);

/** @var Response */
$response = new Response();
$http->submit($response);

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
var_dump($resClassed);
