<?php

declare(strict_types=1);

namespace Paytabs\Sdk\Profile\Endpoints;

use Paytabs\Sdk\Profile\AbstractEndpoint;

final class Uae extends AbstractEndpoint
{
    public const CODE = 'ARE';
    protected const TITLE = 'United Arab Emirates';
    protected const URL = 'https://secure.paytabs.com';
}
