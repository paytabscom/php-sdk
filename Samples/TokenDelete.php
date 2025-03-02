<?php

use Paytabs\Sdk\Holder\Builders\Token\Token;
use Paytabs\Sdk\Http\Http;
use Paytabs\Sdk\Paytabs;
use Paytabs\Sdk\Request\Requests\TokenDelete;

$holder = new Token();
$holder->buildToken($token);

$request = new TokenDelete($gateway, $holder);

// @var Http $http
$http->setRequest($request);

$response = $http->submit();

Paytabs::getLogger()->debug(
    'TokenDelete Response',
    [
        'Response' => $response,
        'Mapped Auto' => $response->getPayloadMapped(),
    ]
);
