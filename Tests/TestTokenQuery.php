<?php

use Holder\Builders\Token\Token;
use Request\Requests\TokenQuery;

$tokenHolder = new Token();
$tokenHolder->setToken('2C4654BD67A3E830C6B693FA63827EB0');

$tokenReq = new TokenQuery($gateway, $tokenHolder);

$http->setRequest($tokenReq);
$response = $http->submit();

var_dump($tokenHolder);
var_dump($response);
