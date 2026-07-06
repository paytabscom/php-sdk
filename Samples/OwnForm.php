<?php

use Paytabs\Sdk\Enums\TranClass;
use Paytabs\Sdk\Enums\TranType;
use Paytabs\Sdk\Paytabs;
use Paytabs\Sdk\Request\Payload\Parts\CustomerDetails;
use Paytabs\Sdk\Request\Payload\PayloadsFactory;
use Paytabs\Sdk\Request\RequestsFactory;
use Psr\Log\LoggerInterface;

/**
 * @var string          $urlReturn
 * @var string          $urlCallback
 * @var bool            $returnUsingGet
 * @var string          $_currency
 * @var Paytabs         $paytabs
 * @var LoggerInterface $logger
 */
if (!isset($paytabs, $logger, $urlReturn, $urlCallback, $returnUsingGet, $_currency)) {
    throw new RuntimeException('Required variables are not set: $paytabs, $logger, $urlReturn, $urlCallback, $returnUsingGet, $_currency');
}

$holder = PayloadsFactory::createOwnForm();
$holder
    ->buildCart('own-form', $_currency, 700, 'Test')
    ->buildTransaction(TranType::Sale, TranClass::Ecom)
    ->buildPluginInfo('PHP-SDK', PHP_VERSION, null)
    ->buildCustomerDetails(
        CustomerDetails::init('Integrations SDK3', '0522222222', 'integrations@paytabs.com')
            ->setAddress('ARE', 'Dubai', 'Dubai', 'nsr st', '11111')
            ->setIp('1.1.1.1')
    )
    ->buildHideShipping(true)
    ->buildURLs($urlReturn, $urlCallback, $returnUsingGet)
    // ->buildPaymentMethod('card') // throws Exception
;

$threeDSecure = true;

$card_redirect = '4000 0000 0000 0002';
$card_direct = '4111-1111 1111 1111';
$pan = $threeDSecure ? $card_redirect : $card_direct;

$holder->buildCardDetails($pan, 2030, 12, '123');

$request = RequestsFactory::createPaymentRequest($holder);
$paytabs->setRequest($request);

$logger->debug(
    'OwnForm holder Payload',
    $holder->getPayload()->getBody()
);
$logger->debug(
    'OwnForm Payload:',
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

$resMapped = $response->getPayloadMapped();
$logger->debug('OwnForm Response: ', [
    'Mapped Auto' => $resMapped,
]);
$logger->error('OwnForm Missed Data: ', [
    'Missed Data' => $resMapped->unMappedData(),
]);
