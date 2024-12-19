<?php

use Enums\TranClass;
use Enums\TranType;
use Gateway\Endpoints\Ksa;
use Gateway\Endpoints\Uae;
use Gateway\Gateway;
use Holder\Builders\Basic;
use Holder\Builders\HostedPage;
use Holder\Parts\CustomerDetails;
use Holder\Parts\Framed;
use Holder\Parts\ShippingDetails;
use Http\Http;
use Request\Requests\PaymentRequest;

require_once 'Test_req.php';

//

$log = Log::getInstance();

$gateway = new Gateway(Uae::getInstance(), 47170, 'SRJNLKK2Z2-HWRGM6JDZM-MGMGGNW9JZ');

$holder = new HostedPage();
$holder
    ->setCart("c01", "AED", 100.5, "Test")
    ->setTransaction(TranType::Sale, TranClass::Ecom)
    ->setPluginInfo('PHP', phpversion(), '')
    ->setCustomerDetails(
        new CustomerDetails('Wajih', '0522222222', 'wajih@mail.com', 'ARE', 'Dubai', 'Dubai', null, '1.1.1.1', '11111')
    )
    ->setShippingDetails(
        new ShippingDetails('Wajih 2')
    )
    ->setHideShipping(false)
;

$request = new PaymentRequest($gateway, $holder);

$http = new Http();
$http->setLogger($log);
$http->setRequest($request);

$response = $http->submit();

var_dump($holder);
var_dump($response);
