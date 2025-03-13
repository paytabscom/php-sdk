<?php

use Paytabs\Sdk\Request\Payload\PayloadsFactory;
use Paytabs\Sdk\Http\Http;
use Paytabs\Sdk\Paytabs;
use Paytabs\Sdk\Request\RequestsFactory;

$holder = PayloadsFactory::token();
$holder->buildToken($token);

$request = RequestsFactory::tokenDelete($gateway, $holder);

/** @var Http $http */
$http->setRequest($request);

$response = $http->submit();

Paytabs::getLogger()->debug(
    'TokenDelete Response',
    [
        'Response' => $response,
        'Mapped Auto' => $response->getPayloadMapped(),
    ]
);
