<?php

namespace Paytabs\Sdk\Gateway\Endpoints;

use Paytabs\Sdk\Gateway\Endpoint;

final class GlobalPt extends Endpoint
{
    protected const CODE = 'GLOBAL';
    protected const TITLE = 'Global';
    protected const URL = 'https://secure-global.paytabs.com';
}
