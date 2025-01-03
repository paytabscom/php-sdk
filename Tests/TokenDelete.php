<?php

use Holder\Builders\Token\Token;
use Http\Http;
use Request\Requests\TokenDelete;
use Response\Response;

$tokenHolder = new Token();
$tokenHolder->setToken('2C4654BD67A3E830C6B693FA63827EB0');

$tokenDelReq = new TokenDelete($gateway, $tokenHolder);

/** @var Http $http */
$http->setRequest($tokenDelReq);

$response = new Response();
$http->submit($response);

print_r($response);
