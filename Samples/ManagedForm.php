<?php

use Paytabs\Sdk\Enums\TranClass;
use Paytabs\Sdk\Enums\TranType;
use Paytabs\Sdk\Http\Http;
use Paytabs\Sdk\Paytabs;
use Paytabs\Sdk\Profile\Profile;
use Paytabs\Sdk\Request\Payload\Parts\CustomerDetails;
use Paytabs\Sdk\Request\Payload\Parts\UserDefined;
use Paytabs\Sdk\Request\Payload\PayloadsFactory;
use Paytabs\Sdk\Request\RequestsFactory;

/**
 * @var Profile $profile
 * @var Http $http
 * @var string $urlReturn
 * @var string $urlCallback
 * @var string $_currency
 * @var string $_paymentToken
 */

if (!isset($profile, $http, $urlReturn, $urlCallback, $_currency, $_paymentToken)) {
    throw new \RuntimeException('Required variables are not set: $profile, $http, $urlReturn, $urlCallback, $returnUsingGet, $_currency, $_paymentToken');
}

//

$holder = PayloadsFactory::managedForm();
$holder
    ->buildCart('managed-form', $_currency, 700, 'Test')
    ->buildTransaction(TranType::Sale, TranClass::Ecom)
    ->buildPluginInfo('PHP-SDK', PHP_VERSION, null)
    ->buildCustomerDetails(
        (new CustomerDetails('Integrations SDK3', '0522222222', 'integrations@paytabs.com'))
            ->setAddress('ARE', 'Dubai', 'Dubai', 'nsr st', '11111')
            ->setIp('1.1.1.1')
    )
    ->buildUserDefined((new UserDefined())
        ->setUDF2('udf_2')
        ->setUDF8('udf_8')
        ->setUDF4('udf_4'))
    ->buildHideShipping(true)
    ->buildURLs($urlReturn, $urlCallback)
    ->buildPaymentToken($_paymentToken)
;

$request = RequestsFactory::paymentRequest($profile, $holder);

Paytabs::getLogger()->debug(
    'ManagedForm holder Payload',
    $holder->getPayload()->getBody()
);
Paytabs::getLogger()->debug(
    'ManagedForm Payload:',
    [$request->getPayload()]
);

$http->setRequest($request);
$http->setDebugMode(false);

$response = $http->submit();

if ($response->isFailure()) {
    $resClassed = $response->getFailure();
} elseif ($response->isRedirect()) {
    $resClassed = $response->getRedirect();
} else {
    $resClassed = $response->getPayload()->getMapped();
}

// case ResponseStage::UnKnown:
// case ResponseStage::Completed:

$resMapped = $response->getPayloadMapped();
Paytabs::getLogger()->debug('ManagedForm Response: ', [
    'Mapped Auto' => $resMapped,
]);
Paytabs::getLogger()->error('ManagedForm Missed Data: ', [
    'Missed Data' => $resMapped->unMappedData(),
]);
