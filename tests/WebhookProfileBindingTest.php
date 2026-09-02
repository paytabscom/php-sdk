<?php

declare(strict_types=1);

namespace Paytabs\Sdk\Tests;

use Paytabs\Sdk\Enums\TranStatus;
use Paytabs\Sdk\Exceptions\InvalidConfigurationException;
use Paytabs\Sdk\Exceptions\InvalidSignatureException;
use Paytabs\Sdk\Exceptions\PaytabsExceptionInterface;
use Paytabs\Sdk\Profile\EndpointsFactory;
use Paytabs\Sdk\Profile\Profile;
use Paytabs\Sdk\Response\Payload\Payloads\Callbacks\Browser;
use Paytabs\Sdk\Response\Responses\Webhook\TransactionResult\BrowserAsPost;
use Paytabs\Sdk\Response\Responses\Webhook\TransactionResult\Callback;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

/**
 * Profile binding, fail-closed verification, and the null-status accessor.
 *
 * Fixtures are real gateway responses captured in tests/fixtures/responses.
 *
 * @internal
 */
final class WebhookProfileBindingTest extends TestCase
{
    /** The profile_id every captured IPN fixture was recorded under. */
    private const FIXTURE_PROFILE_ID = 48214;

    private const OTHER_PROFILE_ID = 99999;

    private const SERVER_KEY = 'AAAAAAAAA9-BBBBBBBBB8-CCCCCCCCC7';

    // ------------------------------------------------- profile binding (IPN)

    public function testCallbackRejectsAnIpnIssuedForAnotherProfile(): void
    {
        $body = self::fixture('webhook-callback-success.json');

        // Signed with the right key, so only the profile check can reject it.
        $result = Callback::initWith($body, ['signature' => self::ipnSignature($body)])
            ->setProfile($this->profile(self::OTHER_PROFILE_ID))
        ;

        self::assertFalse(
            $result->isGenuine(),
            'An IPN whose profile_id differs from the configured profile must be rejected.'
        );
    }

    public function testCallbackAcceptsAnIpnForTheConfiguredProfile(): void
    {
        $body = self::fixture('webhook-callback-success.json');

        $result = Callback::initWith($body, ['signature' => self::ipnSignature($body)])
            ->setProfile($this->profile(self::FIXTURE_PROFILE_ID))
        ;

        self::assertTrue($result->isGenuine());
    }

    public function testPayloadProfileIdAndConfiguredProfileIdAreDistinctValues(): void
    {
        $body = self::fixture('webhook-callback-success.json');

        $result = Callback::initWith($body, ['signature' => self::ipnSignature($body)])
            ->setProfile($this->profile(self::OTHER_PROFILE_ID))
        ;

        // Guards the tautology: these two must not read the same field.
        self::assertNotSame(self::FIXTURE_PROFILE_ID, $result->getConfiguredProfileId());
        self::assertFalse($result->isGenuine());
    }

    // ------------------------------------------------------- fail-closed API

    public function testAssertGenuineThrowsOnAForgedSignature(): void
    {
        $result = Callback::initWith(
            self::fixture('webhook-callback-success.json'),
            ['signature' => 'deadbeef']
        )->setProfile($this->profile(self::FIXTURE_PROFILE_ID));

        $this->expectException(InvalidSignatureException::class);

        $result->assertGenuine();
    }

    public function testAssertGenuineReturnsTheResultOnAValidSignature(): void
    {
        $body = self::fixture('webhook-callback-success.json');

        $result = Callback::initWith($body, ['signature' => self::ipnSignature($body)])
            ->setProfile($this->profile(self::FIXTURE_PROFILE_ID))
        ;

        self::assertSame($result, $result->assertGenuine());
        self::assertSame('TST2623802990085', $result->assertGenuine()->getTranRef());
    }

    public function testAssertGenuineNeverLeaksTheServerKey(): void
    {
        $result = Callback::initWith(
            self::fixture('webhook-callback-success.json'),
            ['signature' => 'deadbeef']
        )->setProfile($this->profile(self::FIXTURE_PROFILE_ID));

        try {
            $result->assertGenuine();
            self::fail('Expected InvalidSignatureException.');
        } catch (InvalidSignatureException $e) {
            self::assertStringNotContainsString(self::SERVER_KEY, $e->getMessage());
            self::assertStringNotContainsString('AAAAAAAAA9', $e->getMessage());
        }
    }

    // ------------------------------------------- null-safe status accessor

    public function testIsTransactionSuccessfulIsNullWhenRespStatusIsAbsent(): void
    {
        // A cancelled or aborted return can omit respStatus entirely. All three
        // predicates must report null rather than a definite answer, so callers
        // relying on !isTransactionFailed() cannot read "unknown" as "paid".
        $payload = new Browser();

        self::assertNull($payload->isTransactionSuccessful());
        self::assertNull($payload->isTransactionFailed());
        self::assertNull($payload->isTransactionPending());
    }

    public function testBrowserPredicatesPartitionAKnownStatus(): void
    {
        $payload = new Browser();
        $payload->setRespStatus('A');

        self::assertTrue($payload->isTransactionSuccessful());
        self::assertFalse($payload->isTransactionFailed());
        self::assertFalse($payload->isTransactionPending());
    }

    public function testBrowserPendingStatusIsNeitherSuccessfulNorFailed(): void
    {
        $payload = new Browser();
        $payload->setRespStatus('P');

        self::assertFalse($payload->isTransactionSuccessful());
        self::assertFalse($payload->isTransactionFailed());
        self::assertTrue($payload->isTransactionPending());
    }

    #[DataProvider('browserStatusProvider')]
    public function testIsTransactionSuccessfulMatchesTheGatewayStatus(
        string $fixture,
        TranStatus $expected,
        bool $successful
    ): void {
        $fields = self::browserFields($fixture);

        $result = BrowserAsPost::initWith($fields)
            ->setProfile($this->profile(self::FIXTURE_PROFILE_ID))
        ;

        self::assertSame($expected, $result->getTranStatus());
        self::assertSame($successful, $result->getTranStatus()->isSuccessful());
    }

    public static function browserStatusProvider(): array
    {
        return [
            'authorised' => ['webhook-browser-success.txt', TranStatus::Authorised, true],
            'declined' => ['webhook-browser-declined.txt', TranStatus::Declined, false],
            'cancelled' => ['webhook-browser-cancelled.txt', TranStatus::Canceled, false],
        ];
    }

    /**
     * A deferred payment sends the customer back with `P` and the agent
     * reference in respCode — the browser return is not a payment confirmation.
     */
    public function testTheBrowserReturnOfADeferredPaymentIsPending(): void
    {
        $fields = self::browserFields('webhook-browser-pending.txt');

        self::assertSame('P', $fields['respStatus']);
        self::assertSame('234320012565', $fields['respCode']);

        $payload = new Browser();
        $payload->setRespStatus($fields['respStatus']);

        self::assertTrue($payload->isTransactionPending());
        self::assertFalse($payload->isTransactionSuccessful());
        self::assertFalse($payload->isTransactionFailed());
    }

    // ------------------------------------------------- exception contract

    public function testEverySdkExceptionIsCatchableThroughOneInterface(): void
    {
        $result = Callback::initWith(
            self::fixture('webhook-callback-success.json'),
            ['signature' => 'deadbeef']
        )->setProfile($this->profile(self::FIXTURE_PROFILE_ID));

        try {
            $result->assertGenuine();
            self::fail('Expected an SDK exception.');
        } catch (PaytabsExceptionInterface $e) {
            self::assertInstanceOf(InvalidSignatureException::class, $e);
        }
    }

    public function testAConfigurationErrorIsAlsoCatchableThroughTheInterface(): void
    {
        $result = Callback::initWith(
            self::fixture('webhook-callback-success.json'),
            ['signature' => 'x']
        );

        $this->expectException(PaytabsExceptionInterface::class);

        // No setProfile() call: the profile is required to verify.
        $result->isGenuine();
    }

    public function testSdkExceptionsRemainCatchableAsSplTypes(): void
    {
        $this->expectException(\RuntimeException::class);

        throw InvalidConfigurationException::missing('profile');
    }

    // ----------------------------------------------------------- fixtures

    private function profile(int $profileId): Profile
    {
        return new Profile(
            EndpointsFactory::getKsaEndpoint(),
            $profileId,
            self::SERVER_KEY
        );
    }

    private static function fixture(string $name): string
    {
        $path = __DIR__ . '/fixtures/responses/' . $name;

        $contents = file_get_contents($path);
        self::assertIsString($contents, 'Missing fixture: ' . $name);

        return $contents;
    }

    /** @return array<string, string> */
    private static function browserFields(string $name): array
    {
        parse_str(trim(self::fixture($name)), $fields);

        /** @var array<string, string> $fields */
        return $fields;
    }

    private static function ipnSignature(string $body): string
    {
        return hash_hmac('sha256', $body, self::SERVER_KEY);
    }
}
