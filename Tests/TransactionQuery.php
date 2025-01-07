<?php

use Holder\Builders\TransactionQuery as BuildersTransactionQuery;
use Http\Http;
use Request\Requests\TransactionQuery;
use Response\Payloads\CompletedArray;
use Response\Payloads\Generic;

$tranHolder = new BuildersTransactionQuery();
$tranHolder->setTransactionRef($trxRef);
$tokenReq = new TransactionQuery($gateway, $tranHolder);

/** @var Http $http */
$http->setRequest($tokenReq);

$response = $http->submit();

print_r($response->getResponse());
print_r($response->getResponse(new Generic));

//
echo '<hr>';

$tranHolder = new BuildersTransactionQuery();
$tranHolder->setCartId('c01');
$tokenReq = new TransactionQuery($gateway, $tranHolder);

$http->setRequest($tokenReq);

$response = $http->submit();

// var_dump($tranHolder);
print_r($response->getResponse(new CompletedArray));
