<?php

declare(strict_types=1);

use Paytabs\Sdk\Profile\ProfilesFactory;
use Paytabs\Sdk\Request\Payload\PayloadsFactory;
use Paytabs\Sdk\Request\RequestsFactory;
use Paytabs\Sdk\Request\Requests\Invoice\InvoiceCancel;
use Paytabs\Sdk\Request\Requests\Invoice\InvoiceMarkPaid;
use Paytabs\Sdk\Request\Requests\Invoice\InvoiceSms;
use Paytabs\Sdk\Request\Requests\Invoice\InvoiceStatus;
use Paytabs\Sdk\Request\Requests\Invoice\InvoiceStatusGet;
use Paytabs\Sdk\Request\Requests\Invoice\NewInvoice;
use Paytabs\Sdk\Request\Requests\PaymentRequest;
use Paytabs\Sdk\Request\Requests\TokenDelete;
use Paytabs\Sdk\Request\Requests\TokenQuery;
use Paytabs\Sdk\Request\Requests\TransactionQuery;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 *
 * @coversNothing
 */
final class RequestsFactoryCompatibilityTest extends TestCase
{
    public function testFactoryCreatesRequestsWithoutProfile(): void
    {
        self::assertFalse(RequestsFactory::createPaymentRequest(PayloadsFactory::createHostedPage())->isProfileSet());
        self::assertFalse(RequestsFactory::createTokenQuery(PayloadsFactory::createToken())->isProfileSet());
        self::assertFalse(RequestsFactory::createTokenDelete(PayloadsFactory::createToken())->isProfileSet());
        self::assertFalse(RequestsFactory::createTransactionQuery(PayloadsFactory::createTransactionQuery())->isProfileSet());
        self::assertFalse(RequestsFactory::createNewInvoice(PayloadsFactory::createInvoice())->isProfileSet());
        self::assertFalse(RequestsFactory::createInvoiceStatusAsPost(PayloadsFactory::createInvoiceStatusAsPost())->isProfileSet());
        self::assertFalse(RequestsFactory::createInvoiceStatusAsGet(PayloadsFactory::createInvoiceStatusAsGet())->isProfileSet());
        self::assertFalse(RequestsFactory::createInvoiceCancel(PayloadsFactory::createInvoiceCancel())->isProfileSet());
        self::assertFalse(RequestsFactory::createInvoiceSms(PayloadsFactory::createInvoiceSms())->isProfileSet());
        self::assertFalse(RequestsFactory::createInvoiceMarkPaid(PayloadsFactory::createInvoiceMarkPaid())->isProfileSet());
    }

    public function testFactoryCreatesRequestsWithProfileAndExpectedTypes(): void
    {
        $profile = ProfilesFactory::createUaeProfile(12345, 'SRV_KEY_TEST');

        self::assertInstanceOf(PaymentRequest::class, RequestsFactory::createPaymentRequest(PayloadsFactory::createHostedPage(), $profile));
        self::assertInstanceOf(TokenQuery::class, RequestsFactory::createTokenQuery(PayloadsFactory::createToken(), $profile));
        self::assertInstanceOf(TokenDelete::class, RequestsFactory::createTokenDelete(PayloadsFactory::createToken(), $profile));
        self::assertInstanceOf(TransactionQuery::class, RequestsFactory::createTransactionQuery(PayloadsFactory::createTransactionQuery(), $profile));
        self::assertInstanceOf(NewInvoice::class, RequestsFactory::createNewInvoice(PayloadsFactory::createInvoice(), $profile));
        self::assertInstanceOf(InvoiceStatus::class, RequestsFactory::createInvoiceStatusAsPost(PayloadsFactory::createInvoiceStatusAsPost(), $profile));
        self::assertInstanceOf(InvoiceStatusGet::class, RequestsFactory::createInvoiceStatusAsGet(PayloadsFactory::createInvoiceStatusAsGet(), $profile));
        self::assertInstanceOf(InvoiceCancel::class, RequestsFactory::createInvoiceCancel(PayloadsFactory::createInvoiceCancel(), $profile));
        self::assertInstanceOf(InvoiceSms::class, RequestsFactory::createInvoiceSms(PayloadsFactory::createInvoiceSms(), $profile));
        self::assertInstanceOf(InvoiceMarkPaid::class, RequestsFactory::createInvoiceMarkPaid(PayloadsFactory::createInvoiceMarkPaid(), $profile));
    }
}
