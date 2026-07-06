<?php

use Paytabs\Sdk\Enums\TranClass;
use Paytabs\Sdk\Enums\TranType;
use Paytabs\Sdk\Paytabs;
use Paytabs\Sdk\Request\Payload\Parts\CustomerDetails;
use Paytabs\Sdk\Request\Payload\Parts\UserDefined;
use Paytabs\Sdk\Request\Payload\PayloadsFactory;
use Paytabs\Sdk\Request\RequestsFactory;
use Psr\Log\LoggerInterface;

/**
 * @var string          $urlReturn
 * @var string          $urlCallback
 * @var string          $_currency
 * @var string          $_paymentToken
 * @var Paytabs         $paytabs
 * @var LoggerInterface $logger
 */
if (!isset($paytabs, $urlReturn, $urlCallback, $_currency, $_paymentToken, $logger)) {
    throw new RuntimeException('Required variables are not set: $paytabs, $urlReturn, $urlCallback, $_currency, $_paymentToken, $logger');
}

$holder = PayloadsFactory::createManagedForm();
$holder
    ->buildCart('managed-form', $_currency, 700, 'Test')
    ->buildTransaction(TranType::Sale, TranClass::Ecom)
    ->buildPluginInfo('PHP-SDK', PHP_VERSION, null)
    ->buildCustomerDetails(
        CustomerDetails::init('Integrations SDK3', '0522222222', 'integrations@paytabs.com')
            ->setAddress('ARE', 'Dubai', 'Dubai', 'nsr st', '11111')
            ->setIp('1.1.1.1')
    )
    ->buildUserDefined(
        UserDefined::init()
            ->setUDF2('udf_2')
            ->setUDF8('udf_8')
            ->setUDF4('udf_4')
    )
    ->buildHideShipping(true)
    ->buildURLs($urlReturn, $urlCallback)
    ->buildPaymentToken($_paymentToken)
;

$request = RequestsFactory::createPaymentRequest($holder);
$paytabs->setRequest($request);

$logger->debug(
    'ManagedForm holder Payload',
    $holder->getPayload()->getBody()
);
$logger->debug(
    'ManagedForm Payload:',
    [$request->getPayload()]
);

$response = $paytabs->submit();

if ($response->isFailure()) {
    $resClassed = $response->getFailure();
} elseif ($response->isRedirect()) {
    $resClassed = $response->getRedirect();
} else {
    $resClassed = $response->getPayload()->getMapped();
}

// case ResponseStage::Unknown:
// case ResponseStage::Completed:

$resMapped = $response->getPayloadMapped();
$logger->debug('ManagedForm Response: ', [
    'Mapped Auto' => $resMapped,
]);
$logger->error('ManagedForm Missed Data: ', [
    'Missed Data' => $resMapped->unMappedData(),
]);
