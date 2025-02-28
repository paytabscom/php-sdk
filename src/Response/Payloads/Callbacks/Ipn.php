<?php

namespace Paytabs\Sdk\Response\Payloads\Callbacks;

use Paytabs\Sdk\Response\Payloads\Payment\Completed;

class Ipn extends Completed
{
    public int $merchant_id;
    public int $profile_id;
}
