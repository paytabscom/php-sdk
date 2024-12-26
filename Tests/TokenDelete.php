<?php

use Holder\Builders\Token\Token;
use Request\Requests\TokenDelete;

$tokenHolder = new Token();
$tokenHolder->setToken('2C4654BD67A3E830C6B693FA63827EB0');

$tokenDelReq = new TokenDelete($gateway, $tokenHolder);
$http->setRequest($tokenDelReq);
$response = $http->submit();

var_dump($response);
