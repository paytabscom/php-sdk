<?php

declare(strict_types=1);

namespace Paytabs\Sdk\Tests;

use Paytabs\Sdk\Enums\TranStatus;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

/**
 * Locks down transaction-status semantics.
 * A misclassification here decides whether a merchant ships goods for an order
 * that was never paid.
 *
 * @internal
 */
final class TranStatusSemanticsTest extends TestCase
{
    #[DataProvider('semanticsProvider')]
    public function testStatusSemantics(
        TranStatus $status,
        bool $successful,
        bool $notFinal,
        bool $failed
    ): void {
        self::assertSame($successful, $status->isSuccessful(), 'isSuccessful');
        self::assertSame($notFinal, $status->isNotFinal(), 'isNotFinal');
        self::assertSame($failed, $status->isFailed(), 'isFailed');
    }

    /**
     * @return iterable<string, array{TranStatus, bool, bool, bool}>
     */
    public static function semanticsProvider(): iterable
    {
        // status | successful | notFinal | failed
        yield 'A authorised' => [TranStatus::Authorised, true, false, false];

        yield 'H on hold' => [TranStatus::OnHold, false, true, false];

        yield 'P pending' => [TranStatus::Pending, false, true, false];

        yield 'V voided' => [TranStatus::Voided, false, false, true];

        yield 'E error' => [TranStatus::Error, false, false, true];

        yield 'D declined' => [TranStatus::Declined, false, false, true];

        yield 'X expired' => [TranStatus::Expired, false, false, true];

        yield 'C canceled' => [TranStatus::Canceled, false, false, true];
    }

    /**
     * Only `A` is a success. If this ever widens, an unpaid order can be
     * fulfilled.
     */
    public function testOnlyAuthorisedIsSuccessful(): void
    {
        foreach (TranStatus::cases() as $status) {
            if (TranStatus::Authorised === $status) {
                self::assertTrue($status->isSuccessful());

                continue;
            }

            self::assertFalse($status->isSuccessful(), "{$status->name} must not be successful");
        }
    }

    /**
     * The three predicates must never overlap — a status cannot be both
     * successful and failed.
     */
    public function testThePredicatesAreMutuallyExclusive(): void
    {
        foreach (TranStatus::cases() as $status) {
            $true = array_filter([
                $status->isSuccessful(),
                $status->isNotFinal(),
                $status->isFailed(),
                $status->isUnknown(),
            ]);

            self::assertCount(1, $true, "{$status->name} must match exactly one predicate");
        }
    }

    /**
     * Hold and Pending are deliberately neither successful nor failed, so
     * `!isFailed()` does NOT mean paid. This documents the trap.
     */
    public function testHoldAndPendingAreNeitherSuccessfulNorFailed(): void
    {
        foreach ([TranStatus::OnHold, TranStatus::Pending] as $status) {
            self::assertFalse($status->isSuccessful());
            self::assertFalse($status->isFailed());
            self::assertTrue($status->isNotFinal());
        }
    }

    public function testEveryDocumentedGatewayCodeMapsToACase(): void
    {
        // The response_status values PayTabs documents.
        foreach (['A', 'H', 'P', 'V', 'E', 'D', 'X', 'C'] as $code) {
            self::assertNotNull(TranStatus::tryFrom($code), "Gateway code {$code} must map to a case");
        }
    }

    public function testAnUnrecognisedCodeDoesNotMap(): void
    {
        self::assertNull(TranStatus::tryFrom('ZZ'));
    }

    /**
     * `Unknown` must not fall into isFailed(), isSuccessful(), or isNotFinal().
     *
     * The risk it carries: a status code PayTabs adds later reads as a definite
     * state, so a merchant's auto-cancel, auto-approve or auto-refund path
     *  can fire on a transaction that actually has a different status.
     */
    public function testUnknownIsNotTreatedAsSuccessfulOrFailed(): void
    {
        self::assertFalse(TranStatus::Unknown->isSuccessful());
        self::assertFalse(TranStatus::Unknown->isNotFinal());
        self::assertFalse(TranStatus::Unknown->isFailed());
        self::assertTrue(TranStatus::Unknown->isUnknown());
    }
}
