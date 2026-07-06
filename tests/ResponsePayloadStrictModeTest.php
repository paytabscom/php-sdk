<?php

declare(strict_types=1);

use Paytabs\Sdk\Enums\TranStatus;
use Paytabs\Sdk\Enums\TranType;
use Paytabs\Sdk\Exceptions\UnknownResponseValueException;
use Paytabs\Sdk\Response\Payload\Payloads\Callbacks\Browser;
use Paytabs\Sdk\Response\Payload\Payloads\Payment;
use Paytabs\Sdk\Response\Payload\Payloads\Payment\Completed;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 *
 * @coversNothing
 */
final class ResponsePayloadStrictModeTest extends TestCase
{
    protected function tearDown(): void
    {
        Browser::setStrictMode(false);
        Completed::setStrictMode(false);
        parent::tearDown();
    }

    public function testUnknownValuesDoNotThrowWhenStrictModeIsDisabled(): void
    {
        Browser::setStrictMode(false);
        Completed::setStrictMode(false);

        $browser = new Browser();
        $browser->setRespStatus('NOT_REAL_STATUS');
        self::assertSame(TranStatus::Unknown, $browser->tranStatus);

        $completed = new Completed();
        $completed->setTranClass('not_real_class');
        self::assertSame('not_real_class', $completed->tran_class);

        $payment = new class extends Payment {};
        $payment->setTranType('not_real_type');
        self::assertSame(TranType::Unknown, $payment->tranType);
    }

    public function testUnknownStatusThrowsWhenStrictModeIsEnabled(): void
    {
        Browser::setStrictMode(true);

        $this->expectException(UnknownResponseValueException::class);
        $this->expectExceptionMessage('Unknown transaction status: NOT_REAL_STATUS');

        $browser = new Browser();
        $browser->setRespStatus('NOT_REAL_STATUS');
    }

    public function testUnknownClassThrowsWhenStrictModeIsEnabled(): void
    {
        Completed::setStrictMode(true);

        $this->expectException(UnknownResponseValueException::class);
        $this->expectExceptionMessage('Unknown transaction class: not_real_class');

        $completed = new Completed();
        $completed->setTranClass('not_real_class');
    }

    public function testUnknownTypeThrowsWhenStrictModeIsEnabled(): void
    {
        Completed::setStrictMode(true);

        $this->expectException(UnknownResponseValueException::class);
        $this->expectExceptionMessage('Unknown transaction type: not_real_type');

        $payment = new class extends Payment {};
        $payment->setTranType('not_real_type');
    }
}
