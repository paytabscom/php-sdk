<?php

declare(strict_types=1);

namespace Paytabs\Sdk\Request;

use Paytabs\Sdk\Profile\Profile;
use Paytabs\Sdk\Request\Payload\Payloads\Invoice\Invoice;
use Paytabs\Sdk\Request\Payload\Payloads\Invoice\InvoiceCancel as BuilderInvoiceCancel;
use Paytabs\Sdk\Request\Payload\Payloads\Invoice\InvoiceMarkPaid as PayloadsInvoiceMarkPaid;
use Paytabs\Sdk\Request\Payload\Payloads\Invoice\InvoiceSms as BuilderInvoiceSms;
use Paytabs\Sdk\Request\Payload\Payloads\Invoice\InvoiceStatus as BuilderInvoiceStatus;
use Paytabs\Sdk\Request\Payload\Payloads\Invoice\InvoiceStatusGet as PayloadsInvoiceStatusGet;
use Paytabs\Sdk\Request\Payload\Payloads\PaymentRequest as PayloadsPaymentRequest;
use Paytabs\Sdk\Request\Payload\Payloads\Token\Token;
use Paytabs\Sdk\Request\Payload\Payloads\TransactionQuery as PayloadsTransactionQuery;
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

class RequestsFactory
{
    public static function createPaymentRequest(
        PayloadsPaymentRequest $holder,
        ?Profile $profile = null,
    ): PaymentRequest {
        return new PaymentRequest($holder, $profile);
    }

    public static function createTokenQuery(
        Token $holder,
        ?Profile $profile = null,
    ): TokenQuery {
        return new TokenQuery($holder, $profile);
    }

    public static function createTokenDelete(
        Token $holder,
        ?Profile $profile = null,
    ): TokenDelete {
        return new TokenDelete($holder, $profile);
    }

    public static function createTransactionQuery(
        PayloadsTransactionQuery $holder,
        ?Profile $profile = null,
    ): TransactionQuery {
        return new TransactionQuery($holder, $profile);
    }

    public static function createNewInvoice(
        Invoice $holder,
        ?Profile $profile = null,
    ): NewInvoice {
        return new NewInvoice($holder, $profile);
    }

    public static function createInvoiceStatusAsPost(
        BuilderInvoiceStatus $holder,
        ?Profile $profile = null,
    ): InvoiceStatus {
        return new InvoiceStatus($holder, $profile);
    }

    public static function createInvoiceStatusAsGet(
        PayloadsInvoiceStatusGet $holder,
        ?Profile $profile = null,
    ): InvoiceStatusGet {
        return new InvoiceStatusGet($holder, $profile);
    }

    public static function createInvoiceCancel(
        BuilderInvoiceCancel $holder,
        ?Profile $profile = null,
    ): InvoiceCancel {
        return new InvoiceCancel($holder, $profile);
    }

    public static function createInvoiceSms(
        BuilderInvoiceSms $holder,
        ?Profile $profile = null,
    ): InvoiceSms {
        return new InvoiceSms($holder, $profile);
    }

    public static function createInvoiceMarkPaid(
        PayloadsInvoiceMarkPaid $holder,
        ?Profile $profile = null,
    ): InvoiceMarkPaid {
        return new InvoiceMarkPaid($holder, $profile);
    }
}
