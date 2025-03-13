<?php

namespace Paytabs\Sdk\Request;

use Paytabs\Sdk\Gateway\Gateway;
use Paytabs\Sdk\Request\Payload\Payloads\Invoice\Invoice;
use Paytabs\Sdk\Request\Payload\Payloads\Invoice\InvoiceStatus as BuilderInvoiceStatus;
use Paytabs\Sdk\Request\Payload\Payloads\Invoice\InvoiceStatusGet as PayloadsInvoiceStatusGet;
use Paytabs\Sdk\Request\Payload\Payloads\PaymentRequest as PayloadsPaymentRequest;
use Paytabs\Sdk\Request\Payload\Payloads\Token\Token;
use Paytabs\Sdk\Request\Payload\Payloads\TransactionQuery as PayloadsTransactionQuery;
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
        Gateway $gateway,
        PayloadsPaymentRequest $holder
    ): PaymentRequest {
        return new PaymentRequest($gateway, $holder);
    }

    public static function tokenQuery(
        Gateway $gateway,
        Token $holder
    ): TokenQuery {
        return new TokenQuery($gateway, $holder);
    }

    public static function tokenDelete(
        Gateway $gateway,
        Token $holder
    ): TokenQuery {
        return new TokenDelete($gateway, $holder);
    }

    public static function transactionQuery(
        Gateway $gateway,
        PayloadsTransactionQuery $holder
    ) {
        return new TransactionQuery($gateway, $holder);
    }

    public static function invoiceNew(
        Gateway $gateway,
        Invoice $holder
    ) {
        return new NewInvoice($gateway, $holder);
    }

    public static function invoiceStatus(
        Gateway $gateway,
        BuilderInvoiceStatus $holder
    ) {
        return new InvoiceStatus($gateway, $holder);
    }

    public static function invoiceStatusAsGet(
        Gateway $gateway,
        PayloadsInvoiceStatusGet $holder
    ) {
        return new InvoiceStatusGet($gateway, $holder);
    }
}
