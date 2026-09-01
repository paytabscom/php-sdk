<?php

declare(strict_types=1);

namespace Paytabs\Sdk\Tests;

use Paytabs\Sdk\Request\Payload\Payloads\Invoice\InvoiceStatus;
use Paytabs\Sdk\Request\Payload\Payloads\Invoice\InvoiceStatusGet;
use Paytabs\Sdk\Request\Payload\PayloadsFactory;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
final class PayloadsFactoryTest extends TestCase
{
    public function testExplicitInvoiceStatusHelpers(): void
    {
        self::assertInstanceOf(InvoiceStatus::class, PayloadsFactory::createInvoiceStatusAsPost());
        self::assertInstanceOf(InvoiceStatusGet::class, PayloadsFactory::createInvoiceStatusAsGet());
    }
}
