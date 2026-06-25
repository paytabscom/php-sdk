<?php

use Paytabs\Sdk\Http\Http;
use Paytabs\Sdk\Paytabs;
use Paytabs\Sdk\Profile\Profile;
use Paytabs\Sdk\Request\Payload\PayloadsFactory;
use Paytabs\Sdk\Request\RequestsFactory;
use Paytabs\Sdk\Response\Payload\Payloads\Invoice\InvoiceStatus;

/**
 * @var Profile $profile
 * @var int     $invoiceId
 * @var Http    $http
 */
if (!isset($profile, $invoiceId, $http)) {
    throw new RuntimeException('Required variables are not set: $profile, $invoiceId, $http');
}

$holder = PayloadsFactory::invoiceStatusAsPost();
$holder->buildInvoiceId($invoiceId);

$request = RequestsFactory::invoiceStatus($profile, $holder);

$http->setRequest($request);
$http->setDebugMode(true);

$response = $http->submit();

if ($response->isProcessed()) {
    /** @var InvoiceStatus $invoiceStatus */
    $invoiceStatus = $response->getPayloadMapped();
    Paytabs::getLogger()->debug('InvoiceStatus POST response: ', [
        $invoiceStatus,
    ]);
} else {
    Paytabs::getLogger()->debug('InvoiceStatus processing failed: ', [
        $response->getPayloadMapped(),
    ]);
}
