<?php

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
    public static function paymentRequest(
        Profile $profile,
        PayloadsPaymentRequest $holder
    ): PaymentRequest {
        return new PaymentRequest($profile, $holder);
    }

    public static function tokenQuery(
        Profile $profile,
        Token $holder
    ): TokenQuery {
        return new TokenQuery($profile, $holder);
    }

    public static function tokenDelete(
        Profile $profile,
        Token $holder
    ): TokenDelete {
        return new TokenDelete($profile, $holder);
    }

    public static function transactionQuery(
        Profile $profile,
        PayloadsTransactionQuery $holder
    ): TransactionQuery {
        return new TransactionQuery($profile, $holder);
    }

    public static function invoiceNew(
        Profile $profile,
        Invoice $holder
    ): NewInvoice {
        return new NewInvoice($profile, $holder);
    }

    public static function invoiceStatus(
        Profile $profile,
        BuilderInvoiceStatus $holder
    ): InvoiceStatus {
        return new InvoiceStatus($profile, $holder);
    }

    public static function invoiceCancel(
        Profile $profile,
        BuilderInvoiceCancel $holder
    ): InvoiceCancel {
        return new InvoiceCancel($profile, $holder);
    }

    public static function invoiceSms(
        Profile $profile,
        BuilderInvoiceSms $holder
    ): InvoiceSms {
        return new InvoiceSms($profile, $holder);
    }

    public static function invoiceStatusAsGet(
        Profile $profile,
        PayloadsInvoiceStatusGet $holder
    ): InvoiceStatusGet {
        return new InvoiceStatusGet($profile, $holder);
    }

    public static function invoiceMarkPaid(
        Profile $profile,
        PayloadsInvoiceMarkPaid $holder
    ): InvoiceMarkPaid {
        return new InvoiceMarkPaid($profile, $holder);
    }
}
