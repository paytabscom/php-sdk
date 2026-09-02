<?php

declare(strict_types=1);

namespace Paytabs\Sdk\Tests;

use Paytabs\Sdk\Enums\TranType;
use Paytabs\Sdk\Response\Payload\Payloads\Payment\Completed;
use Paytabs\Sdk\Response\Payload\Payloads\Paytabs as ResponsePayload;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Psr\Log\NullLogger;

/**
 * The status predicates report the *transaction*, not the order.
 *
 * PayTabs sends an IPN for every transaction against a cart, follow-ups
 * included, so a completed refund arrives as `tran_type: refund` with
 * `response_status: A` and reports isTransactionSuccessful() === true. That is
 * correct — the refund did succeed — but it does not mean the cart was paid.
 * isFollowup() is what separates the two, and anything marking an order paid
 * must consult it.
 *
 * @internal
 */
final class FollowupSemanticsTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        ResponsePayload::setLogger(new NullLogger());
    }

    protected function tearDown(): void
    {
        ResponsePayload::setLogger(null);
        parent::tearDown();
    }

    #[DataProvider('followupProvider')]
    public function testAFollowupIsIdentifiedAsSuch(string $tranType): void
    {
        self::assertTrue(self::map($tranType, 'A')->isFollowup());
    }

    /**
     * Pins the meaning: a successful refund is a successful *transaction*, so a
     * merchant must gate on isFollowup() before marking an order paid.
     */
    #[DataProvider('followupProvider')]
    public function testASuccessfulFollowupReportsTheTransactionAsSuccessful(string $tranType): void
    {
        $completed = self::map($tranType, 'A');

        self::assertTrue($completed->isTransactionSuccessful());
        self::assertFalse($completed->isTransactionFailed());
        self::assertFalse($completed->isTransactionPending());
    }

    #[DataProvider('followupProvider')]
    public function testAFailedFollowupReportsTheTransactionAsFailed(string $tranType): void
    {
        $completed = self::map($tranType, 'D');

        self::assertFalse($completed->isTransactionSuccessful());
        self::assertTrue($completed->isTransactionFailed());
        self::assertTrue($completed->isFollowup());
    }

    /**
     * @return iterable<string, array{string}>
     */
    public static function followupProvider(): iterable
    {
        yield 'refund' => ['refund'];
        yield 'void' => ['void'];
        yield 'release' => ['release'];
        yield 'capture' => ['capture'];
    }

    #[DataProvider('paymentProvider')]
    public function testAPaymentIsNotAFollowup(string $status, ?bool $paid, ?bool $failed, ?bool $pending): void
    {
        $completed = self::map('sale', $status);

        self::assertSame($paid, $completed->isTransactionSuccessful());
        self::assertSame($failed, $completed->isTransactionFailed());
        self::assertSame($pending, $completed->isTransactionPending());

        self::assertFalse($completed->isFollowup());
    }

    /**
     * @return iterable<string, array{string, ?bool, ?bool, ?bool}>
     */
    public static function paymentProvider(): iterable
    {
        yield 'authorised' => ['A', true, false, false];
        yield 'declined' => ['D', false, true, false];
        yield 'pending' => ['P', false, false, true];
        yield 'on hold' => ['H', false, false, true];
    }

    /** Every known transaction type must classify without throwing. */
    #[DataProvider('allTranTypeProvider')]
    public function testEveryTranTypeClassifies(string $tranType): void
    {
        $completed = self::map($tranType, 'A');

        self::assertIsBool($completed->isFollowup());
        self::assertTrue($completed->isTransactionSuccessful());
    }

    /**
     * @return iterable<string, array{string}>
     */
    public static function allTranTypeProvider(): iterable
    {
        foreach (TranType::cases() as $case) {
            if (TranType::Unknown === $case) {
                continue;
            }

            yield $case->value => [$case->value];
        }
    }

    /**
     * A capture or tokenised repeat charge reports `tran_type: Sale`, because a
     * sale against a stored authorisation or token is what the gateway
     * performed. isFollowup() therefore says false, and `previous_tran_ref` is
     * the only reliable marker that this ran against an earlier transaction.
     */
    #[DataProvider('repeatChargeStatusProvider')]
    public function testASaleAgainstAnEarlierTransactionIsNotFlaggedByTranType(string $status): void
    {
        $completed = self::mapWith([
            'tran_type' => 'sale',
            'previous_tran_ref' => 'TST1',
            'payment_result' => ['response_status' => $status],
        ]);

        self::assertFalse($completed->isFollowup(), 'tran_type alone cannot see this');
        self::assertTrue($completed->isAgainstEarlierTransaction());
        self::assertSame('TST1', $completed->previous_tran_ref);
    }

    /**
     * @return iterable<string, array{string}>
     */
    public static function repeatChargeStatusProvider(): iterable
    {
        yield 'authorised' => ['A'];
        yield 'expired' => ['X'];
    }

    /** A pending refund is a normal outcome, not a failure to retry. */
    public function testAPendingRefundIsNeitherSuccessfulNorFailed(): void
    {
        $completed = self::map('refund', 'P');

        self::assertTrue($completed->isTransactionPending());
        self::assertFalse($completed->isTransactionSuccessful());
        self::assertFalse($completed->isTransactionFailed());
        self::assertTrue($completed->isFollowup());
    }

    // ------------------------------------------- deferred payment resolution

    /**
     * A pending offline payment (SADAD, Aman, Fawry) is not resolved by a status
     * change on the original transaction. It is resolved by a second IPN — the
     * settling sale, or an expiry.
     */
    public function testTheOriginalPendingSaleIsNotYetResolved(): void
    {
        $completed = self::map('sale', 'P');

        self::assertFalse($completed->isDeferredPaymentResolved());
        self::assertFalse($completed->isAgainstEarlierTransaction());
    }

    public function testTheSettlingSaleResolvesTheDeferredPayment(): void
    {
        $completed = self::mapWith([
            'tran_type' => 'sale',
            'previous_tran_ref' => 'TST1',
            'payment_result' => ['response_status' => 'A'],
        ]);

        self::assertTrue($completed->isDeferredPaymentResolved());
        self::assertTrue($completed->isTransactionSuccessful());
    }

    /** Expiry is the other terminal outcome: the customer never paid. */
    public function testAnExpiredTransactionResolvesTheDeferredPayment(): void
    {
        $completed = self::map('sale', 'X');

        self::assertTrue($completed->isDeferredPaymentResolved());
        self::assertTrue($completed->isTransactionFailed());
    }

    /** An ordinary immediate sale is not a deferred-payment resolution. */
    public function testAPlainSaleIsNotADeferredResolution(): void
    {
        self::assertFalse(self::map('sale', 'A')->isDeferredPaymentResolved());
    }

    public function testARefundIsNotADeferredPaymentResolution(): void
    {
        $completed = self::mapWith([
            'tran_type' => 'refund',
            'previous_tran_ref' => 'TST1',
            'payment_result' => ['response_status' => 'A'],
        ]);

        self::assertFalse($completed->isDeferredPaymentResolved());
        self::assertTrue($completed->isAgainstEarlierTransaction());
    }

    public function testAnEmptyPreviousTranRefIsNotAnEarlierTransaction(): void
    {
        $completed = self::mapWith([
            'tran_type' => 'sale',
            'previous_tran_ref' => '',
            'payment_result' => ['response_status' => 'A'],
        ]);

        self::assertFalse($completed->isAgainstEarlierTransaction());
        self::assertFalse($completed->isDeferredPaymentResolved());
    }

    /** With no tran_type reported, follow-up status is unknown rather than false. */
    public function testAnAbsentTranTypeReportsAnUnknownFollowupStatus(): void
    {
        $body = json_encode([
            'tran_ref' => 'TST1',
            'cart_id' => 'cart-1',
            'payment_result' => ['response_status' => 'A'],
        ], JSON_THROW_ON_ERROR);

        $completed = (new Completed())->setResponseData($body)->getMapped();

        self::assertNull($completed->isFollowup());
        self::assertTrue($completed->isTransactionSuccessful());
    }

    private static function map(string $tranType, string $responseStatus): Completed
    {
        return self::mapWith([
            'tran_type' => $tranType,
            'payment_result' => ['response_status' => $responseStatus],
        ]);
    }

    /**
     * @param array<string, mixed> $overrides
     */
    private static function mapWith(array $overrides): Completed
    {
        $body = json_encode(
            ['tran_ref' => 'TST2', 'cart_id' => 'cart-1'] + $overrides,
            JSON_THROW_ON_ERROR
        );

        return (new Completed())->setResponseData($body)->getMapped();
    }
}
