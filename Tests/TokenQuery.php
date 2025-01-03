<?php

use Holder\Builders\Token\Token;
use Http\Http;
use Request\Requests\TokenQuery;

$tokenHolder = new Token();
$tokenHolder->setToken($token);

$tokenReq = new TokenQuery($gateway, $tokenHolder);

/** @var Http $http */
$http->setRequest($tokenReq);

$response = $http->submit();

// var_dump($tokenHolder);
print_r($response);
