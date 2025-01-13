<?php

use Paytabs\Sdk\Holder\Builders\Token\Token;
use Paytabs\Sdk\Http\Http;
use Paytabs\Sdk\Request\Requests\TokenDelete;

$tokenHolder = new Token();
$tokenHolder->buildToken($token);

$tokenDelReq = new TokenDelete($gateway, $tokenHolder);

/** @var Http $http */
$http->setRequest($tokenDelReq);

$response = $http->submit();

print_r($response);
