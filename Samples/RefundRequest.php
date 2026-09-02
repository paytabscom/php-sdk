<?php

declare(strict_types=1);

use Paytabs\Sdk\Enums\TranClass;
use Paytabs\Sdk\Enums\TranType;
use Paytabs\Sdk\Paytabs;
use Paytabs\Sdk\Request\Payload\PayloadsFactory;
use Paytabs\Sdk\Request\RequestsFactory;
use Psr\Log\LoggerInterface;

/**
 * @var string          $trxRef
 * @var string          $urlCallback
 * @var string          $_currency
 * @var Paytabs         $paytabs
 * @var LoggerInterface $logger
 */
if (!isset($paytabs, $trxRef, $urlCallback, $_currency, $logger)) {
    throw new RuntimeException('Required variables are not set: $paytabs, $trxRef, $urlCallback, $_currency, $logger');
}

// Submit the Refund request using the main Refund class
$holder2 = PayloadsFactory::createRefund();
$holder2
    ->buildTransactionRef($trxRef)
    ->buildCart('refund_01', $_currency, 4, 'Refund test')
    ->buildURLs(null, $urlCallback)
;

$request = RequestsFactory::createPaymentRequest($holder2);

$paytabs->setRequest($request);

$response = $paytabs->submit();

if ($response->isFailure()) {
    $resClassed = $response->getFailure();
} else {
    $resClassed = $response->getPayload()->getMapped();
}

$logger->debug('Refund Response', [$resClassed]);
