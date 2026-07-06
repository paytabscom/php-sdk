<?php

use Paytabs\Sdk\Paytabs;
use Paytabs\Sdk\Request\Payload\PayloadsFactory;
use Paytabs\Sdk\Request\RequestsFactory;
use Paytabs\Sdk\Response\Payload\Payloads\Generic;
use Psr\Log\LoggerInterface;

/**
 * @var string          $trxRef
 * @var Paytabs         $paytabs
 * @var LoggerInterface $logger
 */
$holder = PayloadsFactory::createTransactionQuery();
$holder->buildTransactionRef($trxRef);

$request = RequestsFactory::createTransactionQuery($holder);

$paytabs->setRequest($request);

$response = $paytabs->submit();

$logger->debug('TransactionQuery Response (By Transaction Ref)', [
    'Mapped Auto' => $response->getPayloadMapped(),
    'Generic' => $response->getPayload()->getMappedAs(new Generic()),
]);

$holder2 = PayloadsFactory::createTransactionQuery();
$holder2->buildCartId('cart01');
$request2 = RequestsFactory::createTransactionQuery($holder2);

$paytabs->setRequest($request2);

$response2 = $paytabs->submit();

if ($response2->isFailure()) {
    $logger->debug('TransactionQuery Response (Failure)', [
        $response2->getFailure(),
    ]);
} elseif ($response2->isProcessed()) {
    // $resClassed2 = $response2->getPayload()->getMapped();
    $logger->debug('TransactionQuery Response (Array) (By Cart id)', [
        // 'Manual Map' => $response2->getPayload()->getMappedAs(new CompletedArray()),
        'Mapped Auto' => $response2->getPayloadMapped(),
        // $resClassed2,
    ]);
}
