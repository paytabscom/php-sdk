<?php

declare(strict_types=1);

namespace Paytabs\Sdk\Profile\Endpoints;

use Paytabs\Sdk\Profile\AbstractEndpoint;

final class Ksa extends AbstractEndpoint
{
    public const CODE = 'SAU';
    protected const TITLE = 'Saudi Arabia';
    protected const URL = 'https://secure.paytabs.sa';
}
