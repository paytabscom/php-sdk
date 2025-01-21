<?php

use Paytabs\Sdk\Holder\Builders\Token\Token;
use Paytabs\Sdk\Http\Http;
use Paytabs\Sdk\Paytabs;
use Paytabs\Sdk\Request\Requests\TokenQuery;

$holder = new Token();
$holder->buildToken($token);

$request = new TokenQuery($gateway, $holder);

Paytabs::getLogger()->debug(
    'TokenQuery holder Payload',
    $holder->getPayload()->getBody()
);


/** @var Http $http */
$http->setRequest($request);

$response = $http->submit();


Paytabs::getLogger()->debug(
    'TokenQuery Response',
    [
        $response,
    ]
);
