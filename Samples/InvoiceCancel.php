<?php

use Paytabs\Sdk\Paytabs;
use Paytabs\Sdk\Request\Payload\PayloadsFactory;
use Paytabs\Sdk\Request\RequestsFactory;
use Psr\Log\LoggerInterface;

/**
 * @var int             $invoiceId
 * @var Paytabs         $paytabs
 * @var LoggerInterface $logger
 */
if (!isset($invoiceId, $paytabs, $logger)) {
    throw new RuntimeException('Required variables are not set: $invoiceId, $paytabs, $logger');
}

$holder = PayloadsFactory::createInvoiceCancel();
$holder->buildInvoiceId($invoiceId);

$request = RequestsFactory::createInvoiceCancel($holder);

$paytabs->setRequest($request);

$response = $paytabs->submit();

$logger->debug('InvoiceCancel POST response: ', [
    $response->getPayloadMapped(),
]);
