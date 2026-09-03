<?php

declare(strict_types=1);

namespace Paytabs\Sdk\Tests;

use Paytabs\Sdk\Exceptions\InvalidConfigurationException;
use Paytabs\Sdk\Profile\EndpointsFactory;
use Paytabs\Sdk\Profile\Profile;
use Paytabs\Sdk\Response\Responses\Webhook\TransactionResult\BrowserAsGet;
use Paytabs\Sdk\Response\Responses\Webhook\TransactionResult\BrowserAsPost;
use Paytabs\Sdk\Response\Responses\Webhook\TransactionResult\Callback;
use Paytabs\Sdk\Tests\Doubles\RecordingLogger;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Psr\Log\NullLogger;

/**
 * Signature verification is the SDK's security boundary.
 *
 * @internal
 */
final class SignatureVerificationTest extends TestCase
{
    private const PROFILE_ID = 100001;
    private const SERVER_KEY = 'AAAAAAAAA9-BBBBBBBBB8-CCCCCCCCC7';
    private const OTHER_KEY = 'ZZZZZZZZZ1-YYYYYYYYY2-XXXXXXXXX3';
    private const CART_AMOUNT = 700;

    // ---------------------------------------------------------------- browser

    public function testBrowserAcceptsACorrectlySignedPayload(): void
    {
        $fields = self::browserFields();
        $payload = $fields + ['signature' => self::browserSignature($fields)];

        $result = BrowserAsPost::initWith($payload)->setProfile($this->profile());

        self::assertTrue($result->isGenuine());
    }

    public function testBrowserSignatureIsIndependentOfFieldOrder(): void
    {
        $fields = self::browserFields();
        $signature = self::browserSignature($fields);

        // The canonical algorithm ksort()s, so a reordered payload must verify.
        $reordered = array_reverse($fields, true);

        $result = BrowserAsPost::initWith($reordered + ['signature' => $signature])
            ->setProfile($this->profile())
        ;

        self::assertTrue($result->isGenuine());
    }

    #[DataProvider('tamperedFieldProvider')]
    public function testBrowserRejectsATamperedField(string $field, string $value): void
    {
        $fields = self::browserFields();
        $signature = self::browserSignature($fields);

        $fields[$field] = $value;

        $result = BrowserAsPost::initWith($fields + ['signature' => $signature])
            ->setProfile($this->profile())->setLogger(new NullLogger())
        ;

        self::assertFalse($result->isGenuine(), "Tampering with {$field} must invalidate the signature");
    }

    /**
     * @return iterable<string, array{string, string}>
     */
    public static function tamperedFieldProvider(): iterable
    {
        yield 'amount raised' => ['cartAmount', '999.00'];

        yield 'status flipped to authorised' => ['respStatus', 'A'];

        yield 'cart id swapped' => ['cartId', 'someone-elses-cart'];

        yield 'tran ref swapped' => ['tranRef', 'TST0000000000000'];
    }

    public function testBrowserRejectsAnAddedField(): void
    {
        $fields = self::browserFields();
        $signature = self::browserSignature($fields);

        $result = BrowserAsPost::initWith($fields + ['injected' => 'x', 'signature' => $signature])
            ->setProfile($this->profile())->setLogger(new NullLogger())
        ;

        self::assertFalse($result->isGenuine());
    }

    public function testBrowserRejectsASignatureFromADifferentServerKey(): void
    {
        $fields = self::browserFields();
        $payload = $fields + ['signature' => self::browserSignature($fields, self::OTHER_KEY)];

        $result = BrowserAsPost::initWith($payload)->setProfile($this->profile())->setLogger(new NullLogger());

        self::assertFalse($result->isGenuine());
    }

    /**
     * @param array<string, mixed> $payload
     */
    #[DataProvider('malformedSignatureProvider')]
    public function testBrowserFailsClosedOnAMalformedSignature(array $payload): void
    {
        $result = BrowserAsPost::initWith($payload)->setProfile($this->profile());

        self::assertFalse($result->isGenuine());
    }

    public function testBrowserExceptionClosedOnArraySignature(): void
    {
        // Sendable as `?signature[]=a&signature[]=b`; this used to reach
        // getServerSignature(): string and fatal with a TypeError on a public
        // endpoint.
        $payload = self::browserFields() + ['signature' => ['a', 'b']];

        $this->expectException(\InvalidArgumentException::class);

        BrowserAsPost::initWith($payload)->setProfile($this->profile());
    }

    /**
     * @return iterable<string, array{array<string, mixed>}>
     */
    public static function malformedSignatureProvider(): iterable
    {
        yield 'missing' => [self::browserFields()];

        yield 'empty string' => [self::browserFields() + ['signature' => '']];

        yield 'integer' => [self::browserFields() + ['signature' => 12345]];

        yield 'null' => [self::browserFields() + ['signature' => null]];
    }

    public function testBrowserPreservesZeroStringValuesWhenHashing(): void
    {
        // "0" is falsy but must NOT be stripped, or the signature drifts from
        // what the gateway computed.
        $fields = self::browserFields() + ['acquirerRRN' => '0'];
        $payload = $fields + ['signature' => self::browserSignature($fields)];

        $result = BrowserAsPost::initWith($payload)->setProfile($this->profile());

        self::assertTrue($result->isGenuine());
    }

    public function testBrowserStripsNullAndEmptyFieldsWhenHashing(): void
    {
        $fields = self::browserFields();
        $signature = self::browserSignature($fields);

        // The gateway omits empty values from the signature base string, so
        // adding them back must not change the outcome.
        $payload = $fields + ['emptyOne' => '', 'nullOne' => null, 'signature' => $signature];

        $result = BrowserAsPost::initWith($payload)->setProfile($this->profile());

        self::assertTrue($result->isGenuine());
    }

    public function testBrowserExcludesLocalParamsFromTheHash(): void
    {
        $fields = self::browserFields();
        $signature = self::browserSignature($fields);

        // Params the merchant appended to its own return URL are not part of
        // what the gateway signed.
        $payload = $fields + ['orderRef' => 'local-123', 'signature' => $signature];

        $result = BrowserAsPost::initWith($payload, ['orderRef'])->setProfile($this->profile());

        self::assertTrue($result->isGenuine());
    }

    public function testBrowserAsGetAndAsPostShareTheSameAlgorithm(): void
    {
        $fields = self::browserFields();
        $payload = $fields + ['signature' => self::browserSignature($fields)];

        self::assertTrue(BrowserAsGet::initWith($payload)->setProfile($this->profile())->isGenuine());
        self::assertTrue(BrowserAsPost::initWith($payload)->setProfile($this->profile())->isGenuine());
    }

    public function testBrowserRejectsAnEmptyPayload(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid browser callback payload: empty request data');

        BrowserAsPost::initWith([]);
    }

    // --------------------------------------------------------------- callback

    public function testCallbackAcceptsACorrectlySignedRawBody(): void
    {
        $body = self::ipnBody();
        $signature = hash_hmac('sha256', $body, self::SERVER_KEY);

        $result = Callback::initWith($body, ['signature' => $signature])->setProfile($this->profile());

        self::assertTrue($result->isGenuine());
    }

    public function testCallbackHashesTheRawBodyByteForByte(): void
    {
        $body = self::ipnBody();
        $signature = hash_hmac('sha256', $body, self::SERVER_KEY);

        // Semantically identical JSON, different bytes: re-encoding the body
        // before hashing would break verification, so this must fail.
        $reserialised = json_encode(json_decode($body, true));
        self::assertIsString($reserialised);
        self::assertNotSame($body, $reserialised);

        $result = Callback::initWith($reserialised, ['signature' => $signature])->setProfile($this->profile())->setLogger(new NullLogger());

        self::assertFalse($result->isGenuine());
    }

    public function testCallbackAcceptsAnUppercaseSignatureHeader(): void
    {
        $body = self::ipnBody();
        $signature = hash_hmac('sha256', $body, self::SERVER_KEY);

        $result = Callback::initWith($body, ['Signature' => $signature])->setProfile($this->profile());

        self::assertTrue($result->isGenuine());
    }

    public function testCallbackRejectsATamperedBody(): void
    {
        $body = self::ipnBody();
        $signature = hash_hmac('sha256', $body, self::SERVER_KEY);

        $tampered = str_replace('"' . self::CART_AMOUNT . '"', '"999.00"', $body);
        self::assertNotSame($body, $tampered);

        $result = Callback::initWith($tampered, ['signature' => $signature])->setProfile($this->profile())->setLogger(new NullLogger());

        self::assertFalse($result->isGenuine());
    }

    public function testCallbackFailsClosedWithoutASignatureHeader(): void
    {
        $result = Callback::initWith(self::ipnBody(), [])->setProfile($this->profile());

        self::assertFalse($result->isGenuine());
    }

    public function testCallbackFailsClosedOnAnArraySignatureHeader(): void
    {
        $result = Callback::initWith(self::ipnBody(), ['signature' => ['a', 'b']])
            ->setProfile($this->profile())
        ;

        self::assertFalse($result->isGenuine());
    }

    public function testCallbackRejectsAnEmptyBody(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        Callback::initWith('', ['signature' => 'x']);
    }

    // ----------------------------------------------------------------- wiring

    public function testVerificationWithoutAProfileRaisesAConfigurationError(): void
    {
        $fields = self::browserFields();
        $payload = $fields + ['signature' => self::browserSignature($fields)];

        $this->expectException(InvalidConfigurationException::class);

        BrowserAsPost::initWith($payload)->isGenuine();
    }

    public function testAnInvalidSignatureIsReportedToTheInjectedLogger(): void
    {
        $logger = new RecordingLogger();

        BrowserAsPost::initWith(self::browserFields() + ['signature' => 'deadbeef'])
            ->setProfile($this->profile())
            ->setLogger($logger)
            ->isGenuine()
        ;

        self::assertSame(1, $logger->count());

        $record = $logger->first();
        self::assertNotNull($record);
        self::assertSame('alert', $record['level']);
        self::assertSame('Invalid signature', $record['message']);
    }

    public function testTheLoggedKeyHintNeverContainsRawKeyMaterial(): void
    {
        $logger = new RecordingLogger();

        BrowserAsPost::initWith(self::browserFields() + ['signature' => 'deadbeef'])
            ->setProfile($this->profile())
            ->setLogger($logger)
            ->isGenuine()
        ;

        $record = $logger->first();
        self::assertNotNull($record);

        $hint = $record['context']['server_key_hint'];

        self::assertIsString($hint);
        self::assertNotSame('', $hint);

        // The old implementation logged substr($serverKey, 0, 10) — a third of
        // the live secret.
        self::assertStringNotContainsString(substr(self::SERVER_KEY, 0, 10), $hint);
        self::assertStringNotContainsString(self::SERVER_KEY, $hint);
    }

    // ------------------------------------------------------------------ utils

    /**
     * @return array<string, string>
     */
    private static function browserFields(): array
    {
        return [
            'tranRef' => 'TST2104500076067',
            'cartId' => 'cart-1001',
            'respStatus' => 'D',
            'respCode' => 'G12345',
            'respMessage' => 'Declined',
            'cartAmount' => '10.00',
            'cartCurrency' => 'AED',
        ];
    }

    /**
     * The canonical PayTabs browser-callback algorithm, written out
     * independently of the SDK so the test cannot pass by mirroring a bug.
     *
     * @param array<string, mixed> $fields
     */
    private static function browserSignature(array $fields, ?string $key = null): string
    {
        unset($fields['signature']);

        $fields = array_filter(
            $fields,
            static fn($value): bool => null !== $value && '' !== $value
        );

        ksort($fields);

        return hash_hmac('sha256', http_build_query($fields), $key ?? self::SERVER_KEY);
    }

    /**
     * Deliberately formatted with whitespace, the way a real gateway body
     * arrives — re-encoding it produces different bytes, which is what
     * testCallbackHashesTheRawBodyByteForByte relies on.
     */
    private static function ipnBody(): string
    {
        return '{"tran_ref":"TST2623302986567","merchant_id":2550,"profile_id":' . self::PROFILE_ID . ',"cart_id":"cart01","cart_description":"Test","cart_currency":"SAR","cart_amount":"' . self::CART_AMOUNT . '","tran_currency":"SAR","tran_total":"700.00","tran_type":"Sale","tran_class":"ECom","customer_details":{"name":"Integrations SDK3","email":"integrations@paytabs.com","phone":"0522222222","street1":"nsr st","city":"Dubai","state":"DU","country":"AE","ip":"2001:db8:1471:b409::18"},"payment_result":{"response_status":"A","response_code":"G97554","response_message":"Authorised","acquirer_ref":"TRAN0201.6A87FF12.00046DFD","cvv_result":" ","avs_result":" ","transaction_time":"2026-08-21T07:32:34Z"},"payment_info":{"payment_method":"Visa","card_type":"Credit","card_scheme":"Visa","payment_description":"4111 11## #### 1111","expiryMonth":11,"expiryYear":2033},"threeDSDetails":{"responseLevel":1,"responseStatus":1,"enrolled":"N","paResStatus":"","eci":"","cavv":"","ucaf":"","threeDSVersion":"Test/Simulation"},"ipn_trace":"IPNS0201.6A87FF12.000069D2","paymentChannel":"PHP SDK"}';
    }

    private function profile(): Profile
    {
        return new Profile(EndpointsFactory::getUaeEndpoint(), 100001, self::SERVER_KEY);
    }
}
