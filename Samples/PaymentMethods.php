<?php

use Paytabs\Sdk\PaymentMethod\AbstractMethod;
use Paytabs\Sdk\PaymentMethod\PaymentMethodsFactory;
use Paytabs\Sdk\Paytabs;
use Paytabs\Sdk\Request\Payload\Parts\PaymentMethods;

$method_code = 'applepay';
$method = PaymentMethodsFactory::createMethod($method_code);

logMethod($method);

$method_id = 1;
$method = PaymentMethodsFactory::createMethodById($method_id);

logMethod($method);

$method_pt_code = 'paytabs_sadad';
$method = PaymentMethodsFactory::createMethodByUnique($method_pt_code);
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
