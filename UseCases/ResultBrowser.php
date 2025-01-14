<?php

use Paytabs\Sdk\Paytabs;
use Paytabs\Sdk\Response\Responses\BrowserCallback;
use Paytabs\Sdk\Response\Responses\BrowserReturn;

$urlResult = 'mode=return&cartId=c01&respCode=G53384&respStatus=A&signature=edfb82b2db4310c8632428e763b5a2b512967b9cdb94bffb9820a7dead19938e&tranRef=TST2501402204131';

// $response = BrowserCallback::init($localParams);

parse_str($urlResult, $getArray);
$response = BrowserCallback::initWith($getArray);

$response->setGateway($gateway);

Paytabs::Logger()->debug('Return Payload: ', [
    'isValid' => $response->isValid(),
    'Response' => $response->getResponse()
]);

//

$getResult = 'acquirerMessage=&acquirerRRN=&cartId=c01&customerEmail=wajih%40mail.com&respCode=G61298&respMessage=Authorised&respStatus=A&signature=f2f4d28d712d7af5fd971b3c06091d0e9a9f88d5fbff299b63469feda644e735&token=2C4654BD67A3ED33C6B693FF628674B0&tranRef=TST2501402204249';

// $response = BrowserReturn::init();

parse_str($urlResult, $getArray);
$response = BrowserReturn::initWith($getArray, ['mode', 'result']);

$response->setGateway($gateway);

Paytabs::Logger()->debug('Return Payload: ', [
    'isValid' => $response->isValid(),
    'Response' => $response->getResponse()
]);
