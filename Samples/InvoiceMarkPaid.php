<?php

declare(strict_types=1);

use Paytabs\Sdk\Enums\InvoiceExternalPayMethod;
use Paytabs\Sdk\Paytabs;
use Paytabs\Sdk\Request\Payload\Parts\InvoiceMarkPaid;
use Paytabs\Sdk\Request\Payload\PayloadsFactory;
use Paytabs\Sdk\Request\RequestsFactory;
use Psr\Log\LoggerInterface;

/**
 * @var int             $invoiceId
 * @var string          $_currency
 * @var Paytabs         $paytabs
 * @var LoggerInterface $logger
 */
if (!isset($paytabs, $invoiceId, $_currency, $logger)) {
    throw new RuntimeException('Required variables are not set: $paytabs, $invoiceId, $_currency, $logger');
}

$invoiceCurrency = $_currency;
$invoiceTotal = 40.00;
$payMethod = InvoiceExternalPayMethod::Bank;
$payDescription = 'test description';

$holder = PayloadsFactory::createInvoiceMarkPaid();
$holder->buildInvoiceMarkPaid(
    new InvoiceMarkPaid($invoiceId, $invoiceCurrency, $invoiceTotal, $payMethod, $payDescription)
);

$request = RequestsFactory::createInvoiceMarkPaid($holder);

$logger->debug('InvoiceMarkPaid POST Request: ', [
    $holder,
]);

$paytabs->setRequest($request);

$response = $paytabs->submit();

$logger->debug('InvoiceMarkPaid POST response: ', [
    $response,
]);

if ($response->isFailure()) {
    // Handle the Failure response
    $resClassed = $response->getFailure();
}

$resMapped = $response->getPayloadMapped();
$logger->debug('InvoiceMarkPaid POST response Mapped Data: ', [
    $resMapped,
]);

$logger->error('InvoiceMarkPaid Missed Data: ', [
    $resMapped->unMappedData(),
]);
