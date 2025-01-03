<?php

use Holder\Builders\Token\Token;
use Http\Http;
use Request\Requests\TokenDelete;

$tokenHolder = new Token();
$tokenHolder->setToken($token);

$tokenDelReq = new TokenDelete($gateway, $tokenHolder);

/** @var Http $http */
$http->setRequest($tokenDelReq);

$response = $http->submit();

print_r($response);
