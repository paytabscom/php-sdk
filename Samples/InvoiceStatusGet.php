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

$holder = PayloadsFactory::invoiceStatusAsGet();
$holder->buildInvoiceId($invoiceId);

$request = RequestsFactory::createInvoiceStatusAsGet($profile, $holder);

$http->setRequest($request);
$http->setDebugMode(true);

$response = $http->submit();

Paytabs::getLogger()->debug('InvoiceStatus GET response: ', [
    $response->getPayloadMapped(),
]);
