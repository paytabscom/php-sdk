<?php

use Paytabs\Sdk\Http\Http;
use Paytabs\Sdk\Paytabs;
use Paytabs\Sdk\Profile\Profile;
use Paytabs\Sdk\Request\Payload\PayloadsFactory;
use Paytabs\Sdk\Request\RequestsFactory;

/**
 * @var Profile $profile
 * @var int     $invoiceId
 * @var Http    $http
 */
if (!isset($profile, $invoiceId, $http)) {
    throw new RuntimeException('Required variables are not set: $profile, $invoiceId, $http');
}

$holder = PayloadsFactory::createInvoiceCancel();
$holder->buildInvoiceId($invoiceId);

$request = RequestsFactory::createInvoiceCancel($profile, $holder);

$http->setRequest($request);
$http->setDebugMode(true);

$response = $http->submit();

Paytabs::getLogger()->debug('InvoiceCancel POST response: ', [
    $response->getPayloadMapped(),
]);
