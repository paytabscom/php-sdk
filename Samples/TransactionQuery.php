<?php

use Paytabs\Sdk\Http\Http;
use Paytabs\Sdk\Paytabs;
use Paytabs\Sdk\Request\Payload\PayloadsFactory;
use Paytabs\Sdk\Request\RequestsFactory;
use Paytabs\Sdk\Response\Payload\Payloads\Generic;
use Paytabs\Sdk\Response\Payload\Payloads\Payment\CompletedArray;

$holder = PayloadsFactory::transactionQuery();
$holder->buildTransactionRef($trxRef);

$request = RequestsFactory::transactionQuery($profile, $holder);

/*
 * HTTP manager
 * @var Http $http
 * */
$http->setRequest($request);

$response = $http->submit();

Paytabs::getLogger()->debug('TransactionQuery Response', [
    'Mapped Auto' => $response->getPayloadMapped(),
    'Generic' => $response->getPayload()->getMappedAs(new Generic()),
]);

echo '<hr>';

$holder2 = PayloadsFactory::transactionQuery();
$holder2->buildCartId('c01');
$request2 = RequestsFactory::transactionQuery($profile, $holder2);

$http->setRequest($request2);

$response2 = $http->submit();

Paytabs::getLogger()->debug('TransactionQuery Response (Array)', [
    $response2->getPayload()->getMappedAs(new CompletedArray()),
    // $response2->getPayloadMapped()
]);
