<?php

use Enums\TranClass;
use Enums\TranType;
use Holder\Builders\HostedPage;
use Holder\Parts\CustomerDetails;
use Holder\Parts\ShippingDetails;
use Request\Requests\PaymentRequest;

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
$http->setRequest($request);

$response = $http->submit();

var_dump($holder);
var_dump($response);
