<?php

declare(strict_types=1);

namespace Paytabs\Sdk\Tests;

use Paytabs\Sdk\Enums\TranClass;
use Paytabs\Sdk\Enums\TranStatus;
use Paytabs\Sdk\Enums\TranType;
use Paytabs\Sdk\Exceptions\MissingResponseFieldException;
use Paytabs\Sdk\Response\Payload\Payloads\Invoice\InvoiceStatus;
use Paytabs\Sdk\Response\Payload\Payloads\Payment;
use Paytabs\Sdk\Response\Payload\Payloads\Payment\Completed;
use Paytabs\Sdk\Response\Responses\Webhook\TransactionResult\Callback;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Psr\Log\NullLogger;

/**
 * Exercises real JsonMapper mapping of gateway payloads.
 *
 * The pre-existing strict-mode test called the setters directly, so mapping
 * itself — null tolerance, unknown fields, nested parts — was never covered.
 *
 * @internal
 */
final class ResponseMappingTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        Payment::setLogger(new NullLogger());
    }

    protected function tearDown(): void
    {
        Payment::setLogger(null);
        parent::tearDown();
    }

    public function testMapsARealisticIpn(): void
    {
        $mapped = $this->mapIpn();

        self::assertSame('TST2104500076067', $mapped->tran_ref);
        self::assertSame('cart-1001', $mapped->cart_id);
        self::assertSame(TranType::Sale, $mapped->tranType);
        self::assertSame(TranClass::Ecom, $mapped->tranClass);
        self::assertSame(TranStatus::Authorised, $mapped->payment_result->tranStatus);
        self::assertSame('831000', $mapped->payment_result->response_code);
    }

    /**
     * JsonMapper defaults bStrictNullTypes to true, so an explicit null against
     * a non-nullable property aborted the whole mapping — one field PayTabs
     * nulls out would take down a merchant's entire IPN handler.
     */
    #[DataProvider('nullableFieldProvider')]
    public function testAnExplicitNullDoesNotAbortMapping(string $field): void
    {
        $mapped = $this->mapIpn([$field => null]);

        self::assertSame('TST2104500076067', $mapped->tran_ref);
        self::assertNull($mapped->{$field});
    }

    /**
     * @return iterable<string, array{string}>
     */
    public static function nullableFieldProvider(): iterable
    {
        yield 'token' => ['token'];

        yield 'previous_tran_ref' => ['previous_tran_ref'];

        yield 'customer_ref' => ['customer_ref'];

        yield 'tran_currency' => ['tran_currency'];
    }

    public function testAnUnknownFieldIsTolerated(): void
    {
        $mapped = $this->mapIpn(['brand_new_gateway_field' => 'surprise']);

        self::assertSame('TST2104500076067', $mapped->tran_ref);
    }

    public function testAnUnknownEnumValueMapsToUnknownWithoutThrowing(): void
    {
        $mapped = $this->mapIpn(['tran_type' => 'BrandNewType']);

        self::assertSame(TranType::Unknown, $mapped->tranType);
    }

    public function testMissingPaymentResultDoesNotCrashTheStatusHelpers(): void
    {
        $mapped = $this->mapIpn(['payment_result' => null]);

        self::assertNull($mapped->isPaymentSuccessful());
        self::assertNull($mapped->isPaymentFailed());
        self::assertNull($mapped->isPaymentPending());
    }

    // ------------------------------------------------------- lazy mapping

    public function testAccessorsWorkWithoutAnExplicitGetMappedCall(): void
    {
        $callback = new Callback($this->ipnJson(), [], []);

        // No getPayload()->getMapped() first — that undocumented ordering
        // requirement used to be the difference between a value and a fatal.
        self::assertSame('TST2104500076067', $callback->getTranRef());
        self::assertSame('cart-1001', $callback->getCartId());
        self::assertSame(TranStatus::Authorised, $callback->getTranStatus());
    }

    public function testAMissingRequiredFieldRaisesATypedException(): void
    {
        $callback = new Callback($this->ipnJson(['payment_result' => null]), [], []);

        $this->expectException(MissingResponseFieldException::class);
        $this->expectExceptionMessage('payment_result');

        $callback->getTranStatus();
    }

    // ----------------------------------------------------- invoice status

    public function testInvoiceStatusAcceptsANullTranStatus(): void
    {
        $payload = new InvoiceStatus();
        $payload->setResponseData((string) json_encode([
            'invoice_status' => 'unpaid',
            'tran_status' => null,
        ]));

        $mapped = $payload->getMapped();

        self::assertSame('unpaid', $mapped->invoice_status);
        self::assertNull($mapped->tran_status);
        self::assertNull($mapped->tranStatus);
    }

    public function testInvoiceStatusMapsAnUnrecognisedTranStatusToUnknown(): void
    {
        $payload = new InvoiceStatus();
        $payload->setResponseData((string) json_encode([
            'invoice_status' => 'paid',
            'tran_status' => 'ZZ',
        ]));

        $mapped = $payload->getMapped();

        self::assertSame(TranStatus::Unknown, $mapped->tranStatus);
    }

    // ------------------------------------------------------------- utils

    /**
     * @param array<string, mixed> $overrides
     */
    private function mapIpn(array $overrides = []): Completed
    {
        $callback = new Callback($this->ipnJson($overrides), [], []);

        $mapped = $callback->getPayload()->getMapped();

        self::assertInstanceOf(Completed::class, $mapped);

        return $mapped;
    }

    /**
     * @param array<string, mixed> $overrides
     */
    private function ipnJson(array $overrides = []): string
    {
        return (string) json_encode(array_merge([
            'tran_ref' => 'TST2104500076067',
            'tran_type' => 'Sale',
            'tran_class' => 'ECom',
            'cart_id' => 'cart-1001',
            'cart_description' => 'Test order',
            'cart_currency' => 'AED',
            'cart_amount' => '10.00',
            'tran_total' => '10.00',
            'tran_currency' => 'AED',
            'customer_details' => [
                'name' => 'Test Person',
                'email' => 'test@example.com',
                'phone' => '0500000000',
                'street1' => 'Main St 1',
                'city' => 'Dubai',
                'state' => 'Dubai',
                'country' => 'ARE',
                'zip' => '11111',
                'ip' => '1.1.1.1',
            ],
            'shipping_details' => [
                'name' => 'Test Person',
                'street1' => 'Ship St 9',
                'city' => 'Abu Dhabi',
                'country' => 'ARE',
            ],
            'payment_result' => [
                'response_status' => 'A',
                'response_code' => '831000',
                'response_message' => 'Authorised',
                'transaction_time' => '2026-01-01T10:00:00Z',
            ],
            'payment_info' => [
                'payment_method' => 'Visa',
                'card_type' => 'Credit',
                'card_scheme' => 'Visa',
                'payment_description' => '4111 11## #### 1111',
                'expiryMonth' => 12,
                'expiryYear' => 2030,
            ],
        ], $overrides));
    }
}
