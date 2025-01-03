<?php

use Holder\Builders\TransactionQuery as BuildersTransactionQuery;
use Request\Requests\TransactionQuery;

$tranHolder = new BuildersTransactionQuery();
$tranHolder->setTransactionRef('TST2435402180636');
$tokenReq = new TransactionQuery($gateway, $tranHolder);

$http->setRequest($tokenReq);
$response = $http->submit();

var_dump($tranHolder);
var_dump($response);

//


$tranHolder = new BuildersTransactionQuery();
$tranHolder->setCartId('c01');
$tokenReq = new TransactionQuery($gateway, $tranHolder);

$http->setRequest($tokenReq);
$response = $http->submit();

var_dump($tranHolder);
var_dump($response);
