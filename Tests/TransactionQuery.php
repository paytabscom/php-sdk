<?php

use Holder\Builders\TransactionQuery as BuildersTransactionQuery;
use Http\Http;
use Request\Requests\TransactionQuery;
use Response\Response;

$tranHolder = new BuildersTransactionQuery();
$tranHolder->setTransactionRef($trxRef);
$tokenReq = new TransactionQuery($gateway, $tranHolder);

/** @var Http $http */
$http->setRequest($tokenReq);

$response = new Response();
$http->submit($response);

// var_dump($tranHolder);
var_dump($response);

//
echo '<hr>';

$tranHolder = new BuildersTransactionQuery();
$tranHolder->setCartId('c01');
$tokenReq = new TransactionQuery($gateway, $tranHolder);

$http->setRequest($tokenReq);
$http->submit($response);

// var_dump($tranHolder);
var_dump($response);
