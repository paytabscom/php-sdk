<?php

use Paytabs\Sdk\Http\Http;
use Paytabs\Sdk\Paytabs;
use Paytabs\Sdk\Profile\Profile;
use Paytabs\Sdk\Request\Payload\PayloadsFactory;
use Paytabs\Sdk\Request\RequestsFactory;

/**
 * @var Profile $profile
 * @var int     $invoiceId
 * @var string  $phoneNumber
 * @var Http    $http
 * @var string  $_currency
 */
if (!isset($profile, $invoiceId, $phoneNumber, $http, $_currency)) {
    throw new RuntimeException('Required variables are not set: $profile, $invoiceId, $phoneNumber, $http, $_currency');
}

$holder = PayloadsFactory::invoiceSms();
$holder->buildInvoiceId($invoiceId)
    ->buildInvoiceSmsBody($phoneNumber)
;

$request = RequestsFactory::invoiceSms($profile, $holder);

Paytabs::getLogger()->debug('InvoiceSms POST Request: ', [
    $request,
]);

$http->setRequest($request);
$http->setDebugMode(true);

$response = $http->submit();

Paytabs::getLogger()->debug('InvoiceSms POST response: ', [
    $response,
]);

$resMapped = $response->getPayloadMapped();
Paytabs::getLogger()->debug('InvoiceSms POST response Mapped Data: ', [
    $resMapped,
]);

Paytabs::getLogger()->error('Missed Data: ', [
    $resMapped->unMappedData(),
]);
