<?php

use Paytabs\Sdk\Enums\TranClass;
use Paytabs\Sdk\Enums\TranType;
use Paytabs\Sdk\Http\Http;
use Paytabs\Sdk\Paytabs;
use Paytabs\Sdk\Request\Payload\Parts\CustomerDetails;
use Paytabs\Sdk\Request\Payload\PayloadsFactory;
use Paytabs\Sdk\Request\RequestsFactory;
use Paytabs\Sdk\Response\Payload\Payloads\Redirect;

$holder = PayloadsFactory::hostedPage();
$holder
    ->buildCart('cart01', $configs['currency'], 700, 'Test')
    ->buildTransaction(TranType::Sale, TranClass::Ecom)
    ->buildPluginInfo('PHP-SDK', PHP_VERSION, Paytabs::getVersion())
    ->buildCustomerDetails(
        (new CustomerDetails('Integrations SDK3', '0522222222', 'integrations@paytabs.com'))
            ->setAddress('ARE', 'Dubai', 'Dubai', 'nsr st', '11111')
    )
    ->buildHideShipping(true)
    ->buildURLs($urlReturn, $urlCallback)
;

$request = RequestsFactory::paymentRequest($profile, $holder);

Paytabs::getLogger()->debug(
    'PaymentRequest Payload:',
    [$request->getPayload()]
);

/** @var Http $http */
$http->setRequest($request);
$http->setDebugMode(true);

$response = $http->submit();

if ($response->isFailure()) {
    $resClassed = $response->getFailure();
} elseif ($response->isRedirect()) {
    /** @var Redirect $resClassed */
    $resClassed = $response->getRedirect();
    Paytabs::getLogger()->info('Redirect URL: ' . $resClassed->redirect_url);
} else {
    $resClassed = $response->getPayload()->getMapped();
}

// case ResponseStage::UnKnown:
// case ResponseStage::Completed:

$resMapped = $response->getPayloadMapped();
Paytabs::getLogger()->debug('PaymentRequest Response: ', [
    'Mapped Auto' => $resMapped,
]);

Paytabs::getLogger()->error('Missed Data: ', [
    $resMapped->unMappedData(),
]);
