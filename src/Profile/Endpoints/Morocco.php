<?php

declare(strict_types=1);

namespace Paytabs\Sdk\Profile\Endpoints;

use Paytabs\Sdk\Profile\AbstractEndpoint;

final class Morocco extends AbstractEndpoint
{
    public const CODE = 'MAR';
    protected const TITLE = 'Morocco';
    protected const URL = 'https://secure-morocco.paytabs.com';
}
