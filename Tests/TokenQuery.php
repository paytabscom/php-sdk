<?php

use Holder\Builders\Token\Token;
use Request\Requests\TokenQuery;
use Response\Response;

$tokenHolder = new Token();
$tokenHolder->setToken($token);

$tokenReq = new TokenQuery($gateway, $tokenHolder);

/** @var Http $http */
$http->setRequest($tokenReq);

$response = new Response();
$http->submit($response);

// var_dump($tokenHolder);
print_r($response);
