<?php

namespace Paytabs\Sdk\Response\Payload\Payloads\Callbacks;

use Paytabs\Sdk\Response\Payload\Payloads\Payment\Completed;

class Ipn extends Completed
{
    public int $merchant_id;
    public int $profile_id;
}
