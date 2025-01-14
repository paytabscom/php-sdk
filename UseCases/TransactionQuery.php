<?php

use Paytabs\Sdk\Holder\Builders\TransactionQuery as BuildersTransactionQuery;
use Paytabs\Sdk\Http\Http;
use Paytabs\Sdk\Request\Requests\TransactionQuery;
use Paytabs\Sdk\Response\Payloads\CompletedArray;
use Paytabs\Sdk\Response\Payloads\Generic;

$tranHolder = new BuildersTransactionQuery();
$tranHolder->buildTransactionRef($trxRef);
$tokenReq = new TransactionQuery($gateway, $tranHolder);

/** @var Http $http */
$http->setRequest($tokenReq);

$response = $http->submit();

print_r($response->getResponse());
print_r($response->getResponse(new Generic()));

//
echo '<hr>';

$tranHolder = new BuildersTransactionQuery();
$tranHolder->buildCartId('c01');
$tokenReq = new TransactionQuery($gateway, $tranHolder);

$http->setRequest($tokenReq);

$response = $http->submit();


print_r($response->getResponse(new CompletedArray()));
