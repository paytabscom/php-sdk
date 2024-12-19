<?php

namespace Holder\Builders\Followup;

use Enums\TranClass;
use Enums\TranType;
use Exception;
use Holder\Builders\Followup;


class Refund extends Followup
{
    public function __construct()
    {
        parent::__construct();
        parent::setTransaction(TranType::Refund, TranClass::Ecom);
    }

    public function setTransaction(TranType $tran_type, TranClass $tran_class = TranClass::Ecom)
    {
        throw new Exception('Can not be implemented');
    }
}
