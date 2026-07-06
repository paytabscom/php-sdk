<?php

use Paytabs\Sdk\Paytabs;
use Paytabs\Sdk\Request\Payload\PayloadsFactory;
use Paytabs\Sdk\Request\RequestsFactory;
use Psr\Log\LoggerInterface;

/**
 * @var int             $invoiceId
 * @var string          $phoneNumber
 * @var string          $_currency
 * @var Paytabs         $paytabs
 * @var LoggerInterface $logger
 */
if (!isset($paytabs, $invoiceId, $phoneNumber, $_currency, $logger)) {
    throw new RuntimeException('Required variables are not set: $paytabs, $invoiceId, $phoneNumber, $_currency, $logger');
}

$holder = PayloadsFactory::createInvoiceSms();
$holder->buildInvoiceId($invoiceId)
    ->buildInvoiceSmsBody($phoneNumber)
;

$request = RequestsFactory::createInvoiceSms($holder);

$logger->debug('InvoiceSms POST Request: ', [
    $request,
]);

$paytabs->setRequest($request);

$response = $paytabs->submit();

$logger->debug('InvoiceSms POST response: ', [
    $response,
]);

$resMapped = $response->getPayloadMapped();
$logger->debug('InvoiceSms POST response Mapped Data: ', [
    $resMapped,
]);

$logger->error('Missed Data: ', [
    $resMapped->unMappedData(),
]);
