<?php

use Paytabs\Sdk\PaymentMethod\AbstractMethod;
use Paytabs\Sdk\PaymentMethod\MethodsFactory;
use Paytabs\Sdk\Paytabs;

$method_code = 'applepay';
$method = MethodsFactory::createMethod($method_code);

logMethod($method);

//

$method_id = 1;
$method = MethodsFactory::createMethodById($method_id);

logMethod($method);

//

$method_pt_code = 'paytabs_card';
$method = MethodsFactory::createMethodByUnique($method_pt_code);
logMethod($method);

//

$method_pt_code = 'paytabs_sadad';
$method = MethodsFactory::createMethodByUnique($method_pt_code);
logMethod($method);


function logMethod(AbstractMethod $method)
{
    Paytabs::getLogger()->debug('Method object', [
        $method,
        $method::PT_CODE,
        $method::CODE,
        $method::supportedCurrencies(),
    ]);
}
