<?php

use Paytabs\Sdk\Holder\Builders\TransactionQuery as BuildersTransactionQuery;
use Paytabs\Sdk\Http\Http;
use Paytabs\Sdk\Paytabs;
use Paytabs\Sdk\Request\Requests\TransactionQuery;
use Paytabs\Sdk\Response\Payloads\CompletedArray;
use Paytabs\Sdk\Response\Payloads\Generic;

$holder = new BuildersTransactionQuery();
$holder->buildTransactionRef($trxRef);
$request = new TransactionQuery($gateway, $holder);

/** @var Http $http */
$http->setRequest($request);

$response = $http->submit();

Paytabs::getLogger()->debug('TokenQuery Response', [
    'Classed' => $response->getResponse(),
    'Generic' => $response->getResponse(new Generic()),
]);

//
echo '<hr>';

$holder2 = new BuildersTransactionQuery();
$holder2->buildCartId('c01');
$request2 = new TransactionQuery($gateway, $holder2);

$http->setRequest($request2);

$response2 = $http->submit();

Paytabs::getLogger()->debug('TokenQuery Response (Array)', [
    $response2->getResponse(new CompletedArray())
]);
