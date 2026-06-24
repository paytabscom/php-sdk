<?php

use Paytabs\Sdk\PaymentMethod\AbstractMethod;
use Paytabs\Sdk\PaymentMethod\MethodsFactory;
use Paytabs\Sdk\Paytabs;
use Paytabs\Sdk\Request\Payload\Parts\PaymentMethods;

$method_code = 'applepay';
$method = MethodsFactory::createMethod($method_code);

logMethod($method);

$method_id = 1;
$method = MethodsFactory::createMethodById($method_id);

logMethod($method);

$method_pt_code = 'paytabs_sadad';
$method = MethodsFactory::createMethodByUnique($method_pt_code);
logMethod($method);

$method = MethodsFactory::createPayTabsAllMethod();
logMethod($method);

$method = MethodsFactory::createCardMethod();
logMethod($method);

$methods = PaymentMethods::init([MethodsFactory::createApplePayMethod(), 'card'])
    ->includeMethod(MethodsFactory::createCardMethod())
    ->includeMethods(['fawry', MethodsFactory::createSadadMethod()])
    ->excludeMethod('sadad')
    ->excludeMethod(MethodsFactory::createFawryMethod());
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
