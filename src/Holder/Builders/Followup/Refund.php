<?php

namespace Paytabs\Sdk\Holder\Builders\Followup;

use Paytabs\Sdk\Enums\TranClass;
use Paytabs\Sdk\Enums\TranType;
use Exception;
use Paytabs\Sdk\Holder\Builders\Followup;

class Refund extends Followup
{
    //

    public function __construct()
    {
        parent::__construct();
        parent::buildTransaction(TranType::Refund(), TranClass::Ecom());
    }

    public function buildTransaction(TranType $tran_type, TranClass $tran_class = TranClass::Ecom)
    {
        throw new Exception('Can not be implemented');
    }
}
