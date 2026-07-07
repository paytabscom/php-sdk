<?php

declare(strict_types=1);

use Paytabs\Sdk\Enums\TranClass;
use Paytabs\Sdk\Enums\TranType;
use Paytabs\Sdk\Paytabs;
use Paytabs\Sdk\Request\Payload\Parts\CustomerDetails;
use Paytabs\Sdk\Request\Payload\PayloadsFactory;
use Paytabs\Sdk\Request\RequestsFactory;
use Paytabs\Sdk\Response\Payload\Payloads\Redirect;
use Psr\Log\LoggerInterface;

/**
 * @var string          $urlReturn
 * @var string          $urlCallback
 * @var string          $_currency
 * @var Paytabs         $paytabs
 * @var LoggerInterface $logger
 */
if (!isset($paytabs, $urlReturn, $urlCallback, $_currency, $logger)) {
    throw new RuntimeException('Required variables are not set: $paytabs, $urlReturn, $urlCallback, $_currency, $logger');
}

$holder = PayloadsFactory::createHostedPage();
$holder
    ->buildCart('cart01', $_currency, 700, 'Test')
    ->buildTransaction(TranType::Sale, TranClass::Ecom)
    ->buildPluginInfo('PHP-SDK', PHP_VERSION, Paytabs::getVersion())
    ->buildCustomerDetails(
        CustomerDetails::init('Integrations SDK3', '0522222222', 'integrations@paytabs.com')
            ->setAddress('ARE', 'Dubai', 'Dubai', 'nsr st', '11111')
    )
    ->buildHideShipping(true)
    ->buildURLs($urlReturn, $urlCallback)
;

$request = RequestsFactory::createPaymentRequest($holder);

$paytabs->setRequest($request);

$logger->debug(
    'PaymentRequest Payload:',
    [$request->getPayload()]
);

$response = $paytabs->submit();

if ($response->isFailure()) {
    $resClassed = $response->getFailure();
} elseif ($response->isRedirect()) {
    /** @var Redirect $resClassed */
    $resClassed = $response->getRedirect();
    $logger->info('Redirect URL: '.$resClassed->redirect_url);
} else {
    $resClassed = $response->getPayload()->getMapped();
}

// case ResponseStage::Unknown:
// case ResponseStage::Completed:

$resMapped = $response->getPayloadMapped();
$logger->debug('PaymentRequest Response: ', [
    'Mapped Auto' => $resMapped,
]);

$logger->error('Missed Data: ', [
    $resMapped->unMappedData(),
]);
