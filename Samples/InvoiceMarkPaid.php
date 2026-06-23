<?php

use Paytabs\Sdk\Enums\InvoiceExternalPayMethod;
use Paytabs\Sdk\Http\Http;
use Paytabs\Sdk\Paytabs;
use Paytabs\Sdk\Profile\Profile;
use Paytabs\Sdk\Request\Payload\Parts\InvoiceMarkPaid;
use Paytabs\Sdk\Request\Payload\PayloadsFactory;
use Paytabs\Sdk\Request\RequestsFactory;

/**
 * @var Profile $profile
 * @var int $invoiceId
 * @var Http $http
 * @var string $_currency
 */

if (!isset($profile, $invoiceId, $http, $_currency)) {
    throw new \RuntimeException('Required variables are not set: $profile, $invoiceId, $http, $_currency');
}

//

$invoiceCurrency = $_currency;
$invoiceTotal = 40.00;
$payMethod = InvoiceExternalPayMethod::Bank;
$payDescription = 'test description';

$holder = PayloadsFactory::invoiceMarkPaid();
$holder->buildInvoiceMarkPaid(
    new InvoiceMarkPaid($invoiceId, $invoiceCurrency, $invoiceTotal, $payMethod, $payDescription)
);

$request = RequestsFactory::invoiceMarkPaid($profile, $holder);

Paytabs::getLogger()->debug('InvoiceMarkPaid POST Request: ', [
    $holder,
]);

$http->setRequest($request);
$http->setDebugMode(true);

$response = $http->submit();

Paytabs::getLogger()->debug('InvoiceMarkPaid POST response: ', [
    $response,
]);

if ($response->isFailure()) {
    // Handle the Failure response
    $resClassed = $response->getFailure();
}

$resMapped = $response->getPayloadMapped();
Paytabs::getLogger()->debug('InvoiceMarkPaid POST response Mapped Data: ', [
    $resMapped,
]);

Paytabs::getLogger()->error('InvoiceMarkPaid Missed Data: ', [
    $resMapped->unMappedData(),
]);
