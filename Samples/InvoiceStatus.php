<?php

use Paytabs\Sdk\Paytabs;
use Paytabs\Sdk\Request\Payload\PayloadsFactory;
use Paytabs\Sdk\Request\RequestsFactory;
use Paytabs\Sdk\Response\Payload\Payloads\Invoice\InvoiceStatus;
use Psr\Log\LoggerInterface;

/**
 * @var int             $invoiceId
 * @var Paytabs         $paytabs
 * @var LoggerInterface $logger
 */
if (!isset($paytabs, $invoiceId, $logger)) {
    throw new RuntimeException('Required variables are not set: $paytabs, $invoiceId, $logger');
}

$holder = PayloadsFactory::createInvoiceStatusAsPost();
$holder->buildInvoiceId($invoiceId);

$request = RequestsFactory::createInvoiceStatusAsPost($holder);

$paytabs->setRequest($request);

$response = $paytabs->submit();

if ($response->isProcessed()) {
    /** @var InvoiceStatus $invoiceStatus */
    $invoiceStatus = $response->getPayloadMapped();
    $logger->debug('InvoiceStatus POST response: ', [
        $invoiceStatus,
    ]);
} else {
    $logger->debug('InvoiceStatus processing failed: ', [
        $response->getPayloadMapped(),
    ]);
}
