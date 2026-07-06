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
if (!isset($paytabs, $invoiceId, $logger)) {
    throw new RuntimeException('Required variables are not set: $paytabs, $invoiceId, $logger');
}

$holder = PayloadsFactory::createInvoiceStatusAsGet();
$holder->buildInvoiceId($invoiceId);

$request = RequestsFactory::createInvoiceStatusAsGet($holder);

$paytabs->setRequest($request);

$response = $paytabs->submit();

$logger->debug('InvoiceStatus GET response: ', [
    $response->getPayloadMapped(),
]);
