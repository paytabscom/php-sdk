<?php

namespace Paytabs\Sdk\Profile\Endpoints;

use Paytabs\Sdk\Profile\Endpoint;

final class GlobalPt extends Endpoint
{
    public const CODE = 'GLOBAL';
    protected const TITLE = 'Global';
    protected const URL = 'https://secure-global.paytabs.com';
}
