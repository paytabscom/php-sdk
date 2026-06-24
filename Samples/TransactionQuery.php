<?php

use Paytabs\Sdk\Http\Http;
use Paytabs\Sdk\Paytabs;
use Paytabs\Sdk\Profile\Profile;
use Paytabs\Sdk\Request\Payload\PayloadsFactory;
use Paytabs\Sdk\Request\RequestsFactory;
use Paytabs\Sdk\Response\Payload\Payloads\Generic;

/**
 * @var Profile $profile
 * @var string $trxRef
 * @var Http $http
 */

$holder = PayloadsFactory::transactionQuery();
$holder->buildTransactionRef($trxRef);

$request = RequestsFactory::transactionQuery($profile, $holder);

$http->setRequest($request);

$response = $http->submit();

Paytabs::getLogger()->debug('TransactionQuery Response (By Transaction Ref)', [
    'Mapped Auto' => $response->getPayloadMapped(),
    'Generic' => $response->getPayload()->getMappedAs(new Generic()),
]);


$holder2 = PayloadsFactory::transactionQuery();
$holder2->buildCartId('cart01');
$request2 = RequestsFactory::transactionQuery($profile, $holder2);

$http->setRequest($request2);

$response2 = $http->submit();

if ($response2->isFailure()) {
    Paytabs::getLogger()->debug('TransactionQuery Response (Failure)', [
        $response2->getFailure(),
    ]);
} elseif ($response2->isProcessed()) {
    // $resClassed2 = $response2->getPayload()->getMapped();
    Paytabs::getLogger()->debug('TransactionQuery Response (Array) (By Cart id)', [
        // 'Manual Map' => $response2->getPayload()->getMappedAs(new CompletedArray()),
        'Mapped Auto' => $response2->getPayloadMapped(),
        // $resClassed2,
    ]);
}
