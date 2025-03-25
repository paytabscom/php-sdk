<?php

use Paytabs\Sdk\Http\Http;
use Paytabs\Sdk\Paytabs;
use Paytabs\Sdk\Request\Payload\PayloadsFactory;
use Paytabs\Sdk\Request\RequestsFactory;

$holder = PayloadsFactory::token();
$holder->buildToken($token);

$request = RequestsFactory::tokenQuery($profile, $holder);

Paytabs::getLogger()->debug(
    'TokenQuery holder Payload',
    $holder->getPayload()->getBody()
);

/** 
 * @var Http $http 
 * */
$http->setRequest($request);

$response = $http->submit();

Paytabs::getLogger()->debug(
    'TokenQuery Response',
    [
        // 'Response' => $response,
        'Mapped Auto' => $response->getPayloadMapped(),
    ]
);
