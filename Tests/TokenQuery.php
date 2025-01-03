<?php

use Holder\Builders\Token\Token;
use Request\Requests\TokenQuery;
use Response\Response;

$tokenHolder = new Token();
$tokenHolder->setToken('2C4654BD67A3E830C6B693FA63827EB0');

$tokenReq = new TokenQuery($gateway, $tokenHolder);

/** @var Http $http */
$http->setRequest($tokenReq);

$response = new Response();
$http->submit($response);

var_dump($tokenHolder);
var_dump($response);
