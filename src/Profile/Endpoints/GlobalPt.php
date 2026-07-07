<?php

declare(strict_types=1);

namespace Paytabs\Sdk\Profile\Endpoints;

use Paytabs\Sdk\Profile\AbstractEndpoint;

final class GlobalPt extends AbstractEndpoint
{
    public const CODE = 'GLOBAL';
    protected const TITLE = 'Global';
    protected const URL = 'https://secure-global.paytabs.com';
}
