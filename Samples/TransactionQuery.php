<?php

declare(strict_types=1);

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
]);

$logger->debug('TransactionQuery Response (By Transaction Ref)', [
    'Generic' => $response->getPayload()->getMappedAs(new Generic()),
]);
