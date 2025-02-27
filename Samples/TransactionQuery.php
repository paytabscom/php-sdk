<?php

use Paytabs\Sdk\Holder\Builders\TransactionQuery as BuildersTransactionQuery;
use Paytabs\Sdk\Http\Http;
use Paytabs\Sdk\Paytabs;
use Paytabs\Sdk\Request\Requests\TransactionQuery;
use Paytabs\Sdk\Response\Payloads\Generic;
use Paytabs\Sdk\Response\Payloads\Payment\CompletedArray;

$holder = new BuildersTransactionQuery();
$holder->buildTransactionRef($trxRef);
$request = new TransactionQuery($gateway, $holder);

/** @var Http $http */
$http->setRequest($request);

$response = $http->submit();

Paytabs::getLogger()->debug('TransactionQuery Response', [
    'Mapped Auto' => $response->getPayloadMapped(),
    'Generic' => $response->getPayload()->getMappedAs(new Generic()),
]);

echo '<hr>';

$holder2 = new BuildersTransactionQuery();
$holder2->buildCartId('c01');
$request2 = new TransactionQuery($gateway, $holder2);

$http->setRequest($request2);

$response2 = $http->submit();

Paytabs::getLogger()->debug('TransactionQuery Response (Array)', [
    $response2->getPayload()->getMappedAs(new CompletedArray()),
    // $response2->getPayloadMapped()
]);
