<?php

declare(strict_types=1);

namespace Paytabs\Sdk\Tests;

use Paytabs\Sdk\Enums\InvoiceStatus as InvoiceStatusEnum;
use Paytabs\Sdk\Enums\ResponseStage;
use Paytabs\Sdk\Enums\TranStatus;
use Paytabs\Sdk\Enums\TranType;
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
        self::assertSame($successful, $mapped->isTransactionSuccessful());
        self::assertSame($failed, $mapped->isTransactionFailed());
        self::assertFalse($mapped->isTransactionPending());
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
        yield 'expired' => [
            'webhook-callback-expired.json', 'TST2624302306928', TranStatus::Expired, false, true,
        ];
        yield 'gateway error' => [
            'webhook-callback-error.json', 'PTE7897897897897', TranStatus::Error, false, true,
        ];
    }

    // ------------------------------------------------- Deferred payments

    /**
     * An offline method (Aman/SADAD/Fawry) produces two transactions, not one
     * status change: a `Payment Request` holding the reference number, then a
     * separate `Sale` once the customer pays at an agent.
     */
    public function testAPendingOfflinePaymentIsAPaymentRequestNotASale(): void
    {
        $pending = self::completed('webhook-callback-pending.json');

        self::assertSame(TranType::PaymentRequest, $pending->tranType);
        self::assertSame(TranStatus::Pending, $pending->payment_result->tranStatus);
        self::assertTrue($pending->isTransactionPending());
        self::assertFalse($pending->isTransactionSuccessful());
        self::assertFalse($pending->isTransactionFailed());

        // Nothing has been collected yet, so it must not count as resolved.
        self::assertFalse($pending->isDeferredPaymentResolved());
        self::assertNull($pending->previous_tran_ref);
    }

    /**
     * On a deferred payment response_code carries the reference number the buyer
     * quotes at the agent, and both transactions repeat it.
     */
    public function testResponseCodeCarriesTheOfflineReferenceNumber(): void
    {
        $pending = self::completed('webhook-callback-pending.json');
        $paid = self::completed('webhook-callback-pending-completed.json');

        self::assertSame('12624479397955', $pending->payment_result->response_code);
        self::assertSame(
            $pending->payment_result->response_code,
            $paid->payment_result->response_code
        );
    }

    /**
     * Not an Aman quirk: SADAD reports the same way, which is what makes the
     * reference-in-response_code rule safe to apply to deferred methods.
     */
    public function testSadadReportsTheReferenceTheSameWayAsAman(): void
    {
        $sadad = self::completed('webhook-callback-pending-sadad.json');

        self::assertSame(TranType::PaymentRequest, $sadad->tranType);
        self::assertSame(TranStatus::Pending, $sadad->payment_result->tranStatus);
        self::assertSame('234320012565', $sadad->payment_result->response_code);
        self::assertSame('SADAD (IFS)', $sadad->payment_info->payment_method);
    }

    /**
     * The direct SADAD API returns the reference in the body instead of a
     * payment page, but creates the same transaction — so it maps as a normal
     * pending payment request on query.
     */
    public function testTheDirectSadadApiCreatesAnOrdinaryPaymentRequest(): void
    {
        $queried = self::completed('query-sadad-api-success.json');

        self::assertSame(TranType::PaymentRequest, $queried->tranType);
        self::assertSame(TranType::Sale, $queried->originalTranType);
        self::assertSame(TranStatus::Pending, $queried->payment_result->tranStatus);
        self::assertSame('Transaction API', $queried->paymentChannel);
    }

    /**
     * The create call is not a payment_result envelope: no response_status, and
     * the reference is sadad_number rather than payment_result.response_code.
     */
    public function testTheDirectSadadCreateResponseHasItsOwnShape(): void
    {
        $created = self::json('api-sadad-create-payment.json');

        self::assertObjectHasProperty('sadad_number', $created);
        self::assertObjectHasProperty('expire_date', $created);
        self::assertObjectNotHasProperty('payment_result', $created);
        self::assertSame('TST2624502994619', $created->tran_ref);
    }

    /**
     * The paying transaction reports `Sale`/`A` — there is no distinct
     * settlement type — so `previous_tran_ref` is the only reliable marker.
     */
    public function testTheAgentPaymentArrivesAsAnAuthorisedSaleAgainstTheRequest(): void
    {
        $paid = self::completed('webhook-callback-pending-completed.json');
        $pending = self::completed('webhook-callback-pending.json');

        self::assertSame(TranType::Sale, $paid->tranType);
        self::assertSame(TranStatus::Authorised, $paid->payment_result->tranStatus);
        self::assertSame($pending->tran_ref, $paid->previous_tran_ref);
        self::assertSame($pending->cart_id, $paid->cart_id);

        self::assertTrue($paid->isAgainstEarlierTransaction());
        self::assertTrue($paid->isDeferredPaymentResolved());
        self::assertTrue($paid->isTransactionSuccessful());

        // A capture/void/refund is a follow-up; this is a genuine sale.
        self::assertFalse($paid->isFollowup());
    }

    /**
     * Expiry is normal attrition and can arrive without previous_tran_ref, so
     * detecting it must not depend on that field.
     */
    public function testAnExpiryResolvesTheDeferredPaymentWithoutAPreviousRef(): void
    {
        $expired = self::completed('webhook-callback-expired.json');

        self::assertSame(TranStatus::Expired, $expired->payment_result->tranStatus);
        self::assertNull($expired->previous_tran_ref);
        self::assertFalse($expired->isAgainstEarlierTransaction());

        self::assertTrue($expired->isDeferredPaymentResolved());
        self::assertTrue($expired->isTransactionFailed());
        self::assertFalse($expired->isTransactionPending());
    }

    // -------------------------------------------------------- Capture

    /**
     * A hold released in the PayTabs dashboard reaches the merchant as an
     * ordinary Capture notification: nothing in the merchant's own code
     * triggered it, so only callback/IPN carries it.
     */
    public function testAHoldReleasedInTheDashboardArrivesAsACapture(): void
    {
        $capture = self::completed('webhook-callback-capture-onhold.json');

        self::assertSame(TranType::Capture, $capture->tranType);
        self::assertSame(TranStatus::Authorised, $capture->payment_result->tranStatus);
        self::assertSame('Dashboard', $capture->paymentChannel);

        // A follow-up against the held transaction, not a second payment.
        self::assertTrue($capture->isFollowup());
        self::assertTrue($capture->isAgainstEarlierTransaction());
        self::assertSame('TST2624502784914', $capture->previous_tran_ref);
    }

    // ----------------------------------------------------------- Void

    public function testASuccessfulVoidIsAFollowupNotAPayment(): void
    {
        $void = self::completed('void-success.json');

        self::assertSame(TranType::Void, $void->tranType);
        self::assertSame(TranStatus::Authorised, $void->payment_result->tranStatus);
        self::assertTrue($void->isFollowup());
        self::assertTrue($void->isAgainstEarlierTransaction());

        // Reports the transaction, not the order — the money was released.
        self::assertTrue($void->isTransactionSuccessful());
    }

    /**
     * A held transaction cannot be cleared through the API: the void comes back
     * inside a normal response as `E`/120, not as an error object.
     */
    public function testVoidingAHeldTransactionIsRefusedWithCode120(): void
    {
        $refused = self::completed('void-declined-onhold.json');
        $held = self::completed('webhook-callback-success-onhold.json');

        self::assertSame($held->tran_ref, $refused->previous_tran_ref);
        self::assertSame(TranType::Void, $refused->tranType);
        self::assertSame(TranStatus::Error, $refused->payment_result->tranStatus);
        self::assertSame('120', $refused->payment_result->response_code);

        self::assertTrue($refused->isTransactionFailed());
        self::assertFalse($refused->isTransactionSuccessful());
    }

    // ------------------------------------------- Changed transaction types

    /**
     * A `sale` that hits hold-on-reject is performed as an `auth`. Only the
     * query response reveals what was originally requested.
     */
    public function testQueryRevealsASaleWasDowngradedToAnAuth(): void
    {
        $queried = self::completed('query-trx-by-ref-onhold.json');

        self::assertSame(TranType::Auth, $queried->tranType);
        self::assertSame(TranType::Sale, $queried->originalTranType);
        self::assertTrue($queried->isTranTypeChanged());
        self::assertSame(TranStatus::OnHold, $queried->payment_result->tranStatus);
    }

    public function testQueryRevealsAVoidWasPerformedAsARelease(): void
    {
        $queried = self::completed('query-trx-by-ref-void-release.json');

        self::assertSame(TranType::Release, $queried->tranType);
        self::assertSame(TranType::Void, $queried->originalTranType);
        self::assertTrue($queried->isTranTypeChanged());
    }

    /**
     * The webhook omits original_tran_type entirely, so the same transaction
     * that reports a downgrade on query reports "unknown" on the callback.
     */
    public function testTheWebhookCannotRevealAChangedTranType(): void
    {
        $webhook = self::completed('webhook-callback-success-onhold.json');
        $queried = self::completed('query-trx-by-ref-onhold.json');

        self::assertSame($queried->tran_ref, $webhook->tran_ref);
        self::assertSame(TranType::Auth, $webhook->tranType);

        self::assertNull($webhook->original_tran_type);
        self::assertNull($webhook->originalTranType);
        self::assertNull($webhook->isTranTypeChanged());
    }

    /**
     * paymentChannel reports the medium the request arrived through, so it is
     * informational only and must not be read as transaction state.
     */
    public function testPaymentChannelIsMappedAsAnOpaqueString(): void
    {
        self::assertSame('Mobile SDK', self::completed('webhook-callback-error.json')->paymentChannel);
        self::assertSame('Payment Page', self::completed('webhook-callback-expired.json')->paymentChannel);
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
        self::assertTrue($mapped->isTransactionSuccessful());
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
        self::assertTrue($mapped->transactions[0]->isTransactionSuccessful());
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
        self::assertNull($mapped->isTransactionSuccessful());
        self::assertNull($mapped->isTransactionFailed());
        self::assertNull($mapped->isTransactionPending());
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
