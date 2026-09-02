<?php

declare(strict_types=1);

use Paytabs\Sdk\Paytabs;
use Paytabs\Sdk\Request\Payload\PayloadsFactory;
use Paytabs\Sdk\Request\RequestsFactory;
use Paytabs\Sdk\Response\Payload\Payloads\Payment\CompletedArray;
use Psr\Log\LoggerInterface;

/**
 * @var string          $cartId
 * @var Paytabs         $paytabs
 * @var LoggerInterface $logger
 */
if (!isset($paytabs, $cartId, $logger)) {
    throw new RuntimeException('Required variables are not set: $paytabs, $cartId, $logger');
}

$cartId = array_key_exists('cart_id', $_GET) ? (string) $_GET['cart_id'] : $cartId;

$holder = PayloadsFactory::createTransactionQuery();
$holder->buildCartId($cartId);
$request = RequestsFactory::createTransactionQuery($holder);

$paytabs->setRequest($request);

$response = $paytabs->submit();

if ($response->isFailure()) {
    $logger->debug('TransactionQuery Response (Failure)', [
        $response->getFailure(),
    ]);
} elseif ($response->isProcessed()) {
    /** @var CompletedArray */
    $mapped = $response->getPayloadMapped();
    $logger->debug('TransactionQuery Response (Array) (By Cart id)', [
        'Total Transactions' => count($mapped->transactions),
        'Mapped Auto' => $mapped,
        // $resClassed,
    ]);
}
