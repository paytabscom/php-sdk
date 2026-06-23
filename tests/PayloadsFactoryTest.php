<?php

declare(strict_types=1);

use Paytabs\Sdk\Request\Payload\Payloads\Invoice\InvoiceStatus;
use Paytabs\Sdk\Request\Payload\Payloads\Invoice\InvoiceStatusGet;
use Paytabs\Sdk\Request\Payload\PayloadsFactory;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 *
 * @coversNothing
 */
final class PayloadsFactoryTest extends TestCase
{
    public function testExplicitInvoiceStatusHelpers(): void
    {
        self::assertInstanceOf(InvoiceStatus::class, PayloadsFactory::invoiceStatusAsPost());
        self::assertInstanceOf(InvoiceStatusGet::class, PayloadsFactory::invoiceStatusAsGet());
    }
}
