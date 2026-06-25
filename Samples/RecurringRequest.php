<?php

use Paytabs\Sdk\Enums\FramedTarget;
use Paytabs\Sdk\Enums\TokenType;
use Paytabs\Sdk\Enums\TranClass;
use Paytabs\Sdk\Enums\TranType;
use Paytabs\Sdk\Http\Http;
use Paytabs\Sdk\Paytabs;
use Paytabs\Sdk\Profile\Profile;
use Paytabs\Sdk\Request\Payload\Parts\CustomerDetails;
use Paytabs\Sdk\Request\Payload\Parts\Framed;
use Paytabs\Sdk\Request\Payload\Parts\Token;
use Paytabs\Sdk\Request\Payload\Parts\TokenEnhanced;
use Paytabs\Sdk\Request\Payload\Parts\UserDefined;
use Paytabs\Sdk\Request\Payload\PayloadsFactory;
use Paytabs\Sdk\Request\RequestsFactory;

/**
 * @var Profile $profile
 * @var Http    $http
 * @var string  $urlReturn
 * @var string  $urlCallback
 * @var string  $_currency
 * @var string  $_themeId
 * @var string  $_token
 * @var string  $_tokenEnhanced
 */
$holder = PayloadsFactory::recurringPayment();
$holder
    ->buildCart('ca-03', $_currency, 700, 'Test')
    ->buildTransaction(TranType::Sale, TranClass::Recurring)
    ->buildPluginInfo('PHP-SDK', PHP_VERSION, null)
    ->buildCustomerDetails(
        CustomerDetails::init('Integrations SDK3', '0522222222', 'integrations@paytabs.com')
            ->setAddress('ARE', 'Dubai', 'Dubai', 'nsr st', '11111')
    )
    ->buildUserDefined(
        UserDefined::init()
            ->setUDF1('udf_1')
            ->setUDF8('udf_8')
            ->setUDF4('udf_4')
    )
    ->buildFramedObj(new Framed(true, FramedTarget::ReturnTop))
    ->buildURLs($urlReturn, $urlCallback)
    ->buildAltCurrency('USD')
    ->buildConfigId($_themeId)
    ->buildCustomerReference('customer-ref-3')
    ->buildAirlineData('pnr-code-02')
    ->buildPaypageLang('ar')
;

$enableToken = true;
$enableTokenEnhanced = false;
if ($enableToken) {
    if ($enableTokenEnhanced) {
        $holder
            ->buildTokenEnhanced(TokenEnhanced::init($_tokenEnhanced, TokenType::RecurringFixed))
        ;
    } else {
        $holder
            ->buildToken(new Token($_token))
        ;
    }
}

$request = RequestsFactory::paymentRequest($profile, $holder);

Paytabs::getLogger()->debug(
    'RecurringPayment holder Payload',
    $holder->getPayload()->getBody()
);
Paytabs::getLogger()->debug(
    'RecurringPayment Payload:',
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

Paytabs::getLogger()->debug('RecurringPayment Response: ', [
    'Mapped Auto' => $response->getPayloadMapped(),
]);
