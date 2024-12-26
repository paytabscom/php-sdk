<?php

use Enums\ResponseType;
use Enums\TranClass;
use Enums\TranType;
use Holder\Builders\HostedPage;
use Holder\Parts\CustomerDetails;
use Holder\Parts\ShippingDetails;
use Http\Http;
use Request\Requests\PaymentRequest;
use Response\Payload\Failure;
use Response\Payload\Redirect;
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
    ->setHideShipping(false)
    ->setTokenise(true)
    ->setURLs(null, 'https://webhook.site/1ae2a776-cc70-44e5-adf0-d90966843f46')
;

$request = new PaymentRequest($gateway, $holder);

/** @var Http $http */
$http->setRequest($request);
$http->setDebugMode(false);

/** @var Response */
$response = $http->submit();

$responseType = $response->responseType();

$resMapped;
if ($responseType == ResponseType::Error) {
    $resMapped = $response->getResponse(Failure::class);
} elseif ($responseType == ResponseType::Redirect) {
    $resMapped = $response->getResponse(Redirect::class);
} else {
    $resMapped = $response->getResponse();
}

// var_dump($holder);
// var_dump($response);
var_dump($resMapped);
