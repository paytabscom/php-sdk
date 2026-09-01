<?php

declare(strict_types=1);

namespace Paytabs\Sdk\Tests;

use Paytabs\Sdk\Enums\InvoiceStatus as InvoiceStatusEnum;
use Paytabs\Sdk\Enums\ResponseStage;
use Paytabs\Sdk\Enums\TranStatus;
use Paytabs\Sdk\Helpers\Helpers;
use Paytabs\Sdk\Profile\EndpointsFactory;
use Paytabs\Sdk\Response\Payload\Payloads\Callbacks\Ipn;
use Paytabs\Sdk\Response\Payload\Payloads\Failure;
use Paytabs\Sdk\Response\Payload\Payloads\Invoice\InvoiceStatus;
use Paytabs\Sdk\Response\Payload\Payloads\Invoice\NewInvoice;
use Paytabs\Sdk\Response\Payload\Payloads\Payment;
use Paytabs\Sdk\Response\Payload\Payloads\Payment\Completed;
use Paytabs\Sdk\Response\Payload\Payloads\Payment\CompletedArray;
use Paytabs\Sdk\Response\Payload\Payloads\Redirect;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Psr\Log\NullLogger;

/**
 * Maps the captured gateway responses in tests/fixtures/responses.
 *
 * Invented fixtures only prove the SDK agrees with itself. These are real
 * payloads, so they also pin down the gateway quirks the SDK has to absorb:
 * amounts arriving as strings, one endpoint returning a bare array, and
 * invoice_status staying "paid" after a void.
 *
 * @internal
 */
final class LiveResponseFixturesTest extends TestCase
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

    // ------------------------------------------------------- stage detection

    #[DataProvider('stageProvider')]
    public function testResponseStageIsDetectedForEveryCapturedResponse(
        string $fixture,
        ResponseStage $expected
    ): void {
        self::assertSame($expected, Helpers::responseStage(self::json($fixture)));
    }

    /**
     * @return iterable<string, array{string, ResponseStage}>
     */
    public static function stageProvider(): iterable
    {
        yield 'hpp created' => ['hpp-created.json', ResponseStage::Redirect];
        yield 'auth failure' => ['hpp-err-auth.json', ResponseStage::Error];
        yield 'duplicate request' => ['hpp-err-duplicate.json', ResponseStage::Error];
        yield 'invoice not found' => ['invoice-status-not-found.json', ResponseStage::Error];
        yield 'invoice expired' => ['invoice-err-created.json', ResponseStage::Error];
        yield 'ipn success' => ['webhook-callback-success.json', ResponseStage::Completed];
        yield 'ipn declined' => ['webhook-callback-declined.json', ResponseStage::Completed];
        yield 'query by ref' => ['query-trx-by-ref.json', ResponseStage::Completed];
    }

    // --------------------------------------------------------------- failures

    /**
     * An error is a small object carrying code/message/trace in place of the
     * expected fields.
     *
     * These are the codes we have captured, not the full catalogue, so this
     * asserts the shape and the specific values seen rather than treating the
     * set as closed. An unrecognised code must still map, not throw.
     */
    #[DataProvider('failureProvider')]
    public function testMapsAFailureResponse(string $fixture, int $code, string $message): void
    {
        $failure = (new Failure())->setResponseData(self::raw($fixture))->getMapped();

        self::assertSame($code, $failure->code);
        self::assertSame($message, $failure->message);
        self::assertNotNull($failure->trace);
    }

    /**
     * @return iterable<string, array{string, int, string}>
     */
    public static function failureProvider(): iterable
    {
        yield 'auth' => [
            'hpp-err-auth.json',
            1,
            'Authentication failed. Check authentication header.',
        ];
        yield 'duplicate' => ['hpp-err-duplicate.json', 4, 'Duplicate Request'];
        yield 'invoice missing' => ['invoice-status-not-found.json', 2, 'Invoice not found'];
        yield 'invoice expired' => ['invoice-err-created.json', 2, 'Invoice expiry date has passed'];
    }

    /**
     * `code` is not unique per message — 2 covers both "Invoice not found" and
     * "Invoice expiry date has passed" — so a caller cannot identify a condition
     * from the code alone, nor by parsing the message.
     */
    public function testTheSameErrorCodeCarriesDifferentMessages(): void
    {
        $notFound = (new Failure())
            ->setResponseData(self::raw('invoice-status-not-found.json'))
            ->getMapped()
        ;
        $expired = (new Failure())
            ->setResponseData(self::raw('invoice-err-created.json'))
            ->getMapped()
        ;

        self::assertSame($notFound->code, $expired->code);
        self::assertNotSame($notFound->message, $expired->message);
    }

    /**
     * The captured fixtures cover four codes; the gateway has more. An unknown
     * code must map like any other rather than being rejected.
     */
    public function testAnUnrecognisedErrorCodeStillMaps(): void
    {
        $body = json_encode([
            'code' => 9999,
            'message' => 'Some future gateway error',
            'trace' => 'PMNT0101.00000000.00000000',
        ], JSON_THROW_ON_ERROR);

        $failure = (new Failure())->setResponseData($body)->getMapped();

        self::assertSame(9999, $failure->code);
        self::assertSame('Some future gateway error', $failure->message);
        self::assertSame(
            ResponseStage::Error,
            Helpers::responseStage(json_decode($body, false, 512, JSON_THROW_ON_ERROR))
        );
    }

    // --------------------------------------------------------------- redirect

    public function testMapsAHostedPageRedirect(): void
    {
        $redirect = (new Redirect())->setResponseData(self::raw('hpp-created.json'))->getMapped();

        self::assertSame('TST2436002183944', $redirect->tran_ref);
        self::assertStringStartsWith('https://', $redirect->redirect_url);
        self::assertStringContainsString('/payment/', $redirect->redirect_url);
    }

    /**
     * redirect_url normally sits on the same regional host the request went to,
     * but the SDK must not depend on that: PayTabs is white-labelled, so the
     * host can be a bank's own domain that this SDK has never heard of. Mapping
     * therefore treats redirect_url as an opaque string.
     */
    #[DataProvider('redirectHostProvider')]
    public function testARedirectIsMappedForAnyHost(string $host): void
    {
        $body = json_encode([
            'tran_ref' => 'TST0000000000001',
            'cart_id' => 'cart-1',
            'redirect_url' => $host . '/payment/wr/ABCDEF0123456789',
        ], JSON_THROW_ON_ERROR);

        self::assertSame(
            ResponseStage::Redirect,
            Helpers::responseStage(json_decode($body, false, 512, JSON_THROW_ON_ERROR))
        );

        $redirect = (new Redirect())->setResponseData($body)->getMapped();

        self::assertSame($host . '/payment/wr/ABCDEF0123456789', $redirect->redirect_url);
    }

    /**
     * Every host the SDK ships, read from EndpointsFactory so a newly added
     * region is covered without touching this test, plus hosts the factory does
     * not know: white-label deployments and regions added after this release.
     *
     * @return iterable<string, array{string}>
     */
    public static function redirectHostProvider(): iterable
    {
        foreach (EndpointsFactory::getAllEndpoints() as $endpoint) {
            yield $endpoint->getCode() => [$endpoint->getUrl()];
        }

        yield 'white-label bank domain' => ['https://pay.some-bank.example'];
        yield 'unlisted future region' => ['https://secure-elsewhere.paytabs.com'];
    }

    // ----------------------------------------------------------------- IPNs

    #[DataProvider('ipnProvider')]
    public function testMapsCapturedIpns(
        string $fixture,
        string $tranRef,
        TranStatus $status,
        ?bool $successful,
        ?bool $failed
    ): void {
        $mapped = self::completed($fixture);

        self::assertSame($tranRef, $mapped->tran_ref);
        self::assertSame($status, $mapped->payment_result->tranStatus);
        self::assertSame($successful, $mapped->isPaymentSuccessful());
        self::assertSame($failed, $mapped->isPaymentFailed());
        self::assertFalse($mapped->isPaymentPending());
    }

    /**
     * @return iterable<string, array{string, string, TranStatus, ?bool, ?bool}>
     */
    public static function ipnProvider(): iterable
    {
        yield 'authorised' => [
            'webhook-callback-success.json', 'TST2623802990085', TranStatus::Authorised, true, false,
        ];
        yield 'declined' => [
            'webhook-callback-declined.json', 'TST2623802990092', TranStatus::Declined, false, true,
        ];
        yield 'cancelled' => [
            'webhook-callback-cancelled.json', 'TST2623802990091', TranStatus::Canceled, false, true,
        ];
    }

    public function testNestedPartsSurviveMapping(): void
    {
        $mapped = self::completed('webhook-callback-success.json');

        self::assertSame('nsr st', $mapped->customer_details->street1);
        self::assertSame('integrations@paytabs.com', $mapped->customer_details->email);
        self::assertSame('Visa', $mapped->payment_info->payment_method);
        self::assertSame('udf_1', $mapped->user_defined->udf1);
        self::assertSame('2026-08-26T08:31:33Z', $mapped->payment_result->transaction_time);
    }

    /**
     * The gateway sends cart_amount as a JSON string even though the request
     * sends a number, so anything comparing amounts must cast first.
     */
    public function testAmountsArriveAsStringsAndCoerceToFloat(): void
    {
        $raw = json_decode(self::raw('webhook-callback-success.json'), true);
        self::assertIsString($raw['cart_amount']);

        $mapped = self::completed('webhook-callback-success.json');
        self::assertSame(700.00, $mapped->cart_amount);
    }

    /**
     * payment/request answers with profileId, IPNs with profile_id. JsonMapper's
     * fallback match is case-insensitive but does not bridge the underscore, so
     * the snake_case pair only lands because Ipn re-declares it — mapping the
     * same body as Completed silently drops both fields.
     */
    public function testIpnProfileIdOnlyMapsOnTheIpnPayload(): void
    {
        $raw = self::raw('webhook-callback-success.json');

        self::assertArrayHasKey('profile_id', json_decode($raw, true));

        $ipn = (new Ipn())->setResponseData($raw)->getMapped();
        self::assertSame(48214, $ipn->profile_id);
        self::assertSame(2550, $ipn->merchant_id);

        $completed = (new Completed())->setResponseData($raw)->getMapped();
        self::assertNull($completed->profileId);
    }

    // ------------------------------------------------------ transaction query

    public function testQueryByTransactionRefMapsToASingleTransaction(): void
    {
        $mapped = self::completed('query-trx-by-ref.json');

        self::assertSame('TST2530002372060', $mapped->tran_ref);
        self::assertTrue($mapped->isPaymentSuccessful());
        self::assertSame('2C46xxxE67A3E5xxxxB791F560837DB1', $mapped->token);
    }

    /**
     * Querying by cart_id answers with a bare JSON array, not an object
     * wrapping a list, because one cart id can have many attempts. Code written
     * against the by-tran_ref object shape breaks here.
     */
    public function testQueryByCartIdMapsABareArrayOfTransactions(): void
    {
        self::assertTrue(array_is_list(json_decode(self::raw('query-trx-by-cart.json'), true)));

        $mapped = (new CompletedArray())
            ->setResponseData(self::raw('query-trx-by-cart.json'))
            ->getMapped()
        ;

        self::assertCount(20, $mapped->transactions);
        self::assertContainsOnlyInstancesOf(Completed::class, $mapped->transactions);
        self::assertSame('TST2530002372060', $mapped->transactions[0]->tran_ref);
        self::assertTrue($mapped->transactions[0]->isPaymentSuccessful());
    }

    /**
     * A token query carries no payment_result, so the status predicates must
     * report "unknown" rather than claiming a definite outcome.
     */
    public function testTokenQueryHasNoPaymentResultAndReportsUnknown(): void
    {
        $mapped = self::completed('query-token.json');

        self::assertSame('TST2105900091509', $mapped->tran_ref);
        self::assertNull($mapped->payment_result);
        self::assertNull($mapped->isPaymentSuccessful());
        self::assertNull($mapped->isPaymentFailed());
        self::assertNull($mapped->isPaymentPending());
    }

    // ---------------------------------------------------------------- invoices

    public function testMapsInvoiceCreation(): void
    {
        $invoice = (new NewInvoice())->setResponseData(self::raw('invoice-created.json'))->getMapped();

        self::assertSame(5829212, $invoice->invoice_id);
        self::assertStringContainsString('/payment/request/invoice/', $invoice->invoice_link);
    }

    public function testAnUnpaidInvoiceReportsNoTransactionStatus(): void
    {
        $status = self::invoiceStatus('invoice-status-not-paid.json');

        self::assertSame(InvoiceStatusEnum::Pending, $status->invoiceStatus);
        self::assertNull($status->tran_ref);
        self::assertNull($status->tranStatus);
    }

    public function testAPaidInvoiceReportsAnAuthorisedTransaction(): void
    {
        $status = self::invoiceStatus('invoice-status-paid.json');

        self::assertSame(InvoiceStatusEnum::Paid, $status->invoiceStatus);
        self::assertSame('TST2104500076067', $status->tran_ref);
        self::assertSame(TranStatus::Authorised, $status->tranStatus);
    }

    /**
     * The trap: invoice_status stays "paid" after the transaction is voided, so
     * invoice_status alone never means the money is still there. Only
     * tran_status distinguishes the two.
     */
    public function testAVoidedInvoiceIsStillReportedAsPaid(): void
    {
        $voided = self::invoiceStatus('invoice-status-paid-void.json');
        $paid = self::invoiceStatus('invoice-status-paid.json');

        self::assertSame($paid->invoiceStatus, $voided->invoiceStatus);

        self::assertSame(TranStatus::Voided, $voided->tranStatus);
        self::assertFalse($voided->tranStatus->isSuccessful());
        self::assertTrue($paid->tranStatus->isSuccessful());
    }

    // ---------------------------------------------------------------- helpers

    private static function raw(string $fixture): string
    {
        $path = __DIR__ . '/fixtures/responses/' . $fixture;

        $contents = file_get_contents($path);
        self::assertIsString($contents, 'Missing fixture: ' . $fixture);

        return $contents;
    }

    private static function json(string $fixture): array|object
    {
        return json_decode(self::raw($fixture), false, 512, JSON_THROW_ON_ERROR);
    }

    private static function completed(string $fixture): Completed
    {
        return (new Completed())->setResponseData(self::raw($fixture))->getMapped();
    }

    private static function invoiceStatus(string $fixture): InvoiceStatus
    {
        return (new InvoiceStatus())->setResponseData(self::raw($fixture))->getMapped();
    }
}
