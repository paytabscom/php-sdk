<?php

use Paytabs\Sdk\PaymentMethod\AbstractMethod;
use Paytabs\Sdk\PaymentMethod\PaymentMethodsFactory;
use Paytabs\Sdk\Paytabs;
use Paytabs\Sdk\Request\Payload\Parts\PaymentMethods;

$methodCode = 'applepay';
$method = PaymentMethodsFactory::createMethod($methodCode);

logMethod($method);

$methodId = 1;
$method = PaymentMethodsFactory::createMethodById($methodId);

logMethod($method);

$methodPtCode = 'paytabs_sadad';
$method = PaymentMethodsFactory::createMethodByUnique($methodPtCode);
logMethod($method);

$method = PaymentMethodsFactory::createPayTabsAllMethod();
logMethod($method);

$method = PaymentMethodsFactory::createCardMethod();
logMethod($method);

$methods = PaymentMethods::init([PaymentMethodsFactory::createApplePayMethod(), 'card'])
    ->includeMethod(PaymentMethodsFactory::createCardMethod())
    ->includeMethods(['fawry', PaymentMethodsFactory::createSadadMethod()])
    ->excludeMethod('sadad')
    ->excludeMethod(PaymentMethodsFactory::createFawryMethod())
;
Paytabs::getLogger()->info('Payment Methods:', [
    $methods,
    $methods->build(),
]);

function logMethod(AbstractMethod $method): void
{
    Paytabs::getLogger()->debug('Method object', [
        $method,
        $method::PT_CODE,
        $method::CODE,
        $method::supportedCurrencies(),
    ]);
}
