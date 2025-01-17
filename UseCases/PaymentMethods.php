<?php

use Paytabs\Sdk\PaymentMethod\MethodsFactory;
use Paytabs\Sdk\Paytabs;

$method_code = 'applepay';
$method = MethodsFactory::createMethod($method_code);

Paytabs::Logger()->debug('Method object', [$method, $method::PT_CODE, $method::CODE]);

//

$method_id = 1;
$method = MethodsFactory::createMethodById($method_id);

Paytabs::Logger()->debug('Method object', [$method, $method::PT_CODE, $method::CODE]);

//

$method_pt_code = 'paytabs_card';
$method = MethodsFactory::createMethodByUnique($method_pt_code);

Paytabs::Logger()->debug('Method object', [$method, $method::PT_CODE, $method::CODE]);
