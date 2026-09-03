<?php

declare(strict_types=1);

namespace Paytabs\Sdk\Tests\Doubles;

use Paytabs\Sdk\Profile\AbstractEndpoint;

/**
 * PayTabs is white-labelled, so a merchant can be issued a host this SDK does
 * not ship. This stands in for one, to prove such a deployment works without
 * modifying the SDK.
 */
final class WhiteLabelEndpoint extends AbstractEndpoint
{
    public const CODE = 'WLBL';

    protected const TITLE = 'White-label acquirer';

    protected const URL = 'https://pay.some-bank.example';
}
