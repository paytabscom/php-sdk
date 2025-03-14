<?php

use Paytabs\Sdk\Paytabs;
use Paytabs\Sdk\Response\Responses\Webhook\TransactionResult\BrowserCallback;
use Paytabs\Sdk\Response\Responses\Webhook\TransactionResult\BrowserReturn;

$urlResult = 'mode=return&cartId=c01&respCode=G53384&respStatus=A&signature=edfb82b2db4310c8632428e763b5a2b512967b9cdb94bffb9820a7dead19938e&tranRef=TST2501402204131';

// $response = BrowserCallback::init($localParams);

parse_str($urlResult, $getArray1);
$response1 = BrowserCallback::initWith($getArray1);

$response1->setProfile($profile);

Paytabs::getLogger()->debug('Return Payload: ', [
    'isGenuine' => $response1->isGenuine(),
    'Response' => $response1->getPayload()->getMapped(),
]);

$getResult = 'acquirerMessage=&acquirerRRN=&cartId=c01&customerEmail=wajih%40mail.com&respCode=G42967&respMessage=Authorised&respStatus=A&signature=7328ebcbd1708a14ed9ec34731b017b569dec45170fd2aa6abc113018defe911&token=2C4655BE67A3E537C6B593FF60817AB9&tranRef=TST2502902213573';

// $response = BrowserReturn::init();

parse_str($getResult, $getArray2);
$response2 = BrowserReturn::initWith($getArray2, ['mode', 'result']);

$response2->setProfile($profile);

Paytabs::getLogger()->debug('Return Payload: ', [
    'isGenuine' => $response2->isGenuine(),
    'Response' => $response2->getPayload()->getMapped(),
]);
