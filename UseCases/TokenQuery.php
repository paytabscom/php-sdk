<?php

use Paytabs\Sdk\Holder\Builders\Token\Token;
use Paytabs\Sdk\Http\Http;
use Paytabs\Sdk\Request\Requests\TokenQuery;

$tokenHolder = new Token();
$tokenHolder->buildToken($token);

$tokenReq = new TokenQuery($gateway, $tokenHolder);

/** @var Http $http */
$http->setRequest($tokenReq);

$response = $http->submit();


print_r($response);
