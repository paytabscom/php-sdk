<?php

namespace Paytabs\Sdk\Request;

use Paytabs\Sdk\Gateway\Gateway;
use Paytabs\Sdk\Holder\Builders\Invoice\Invoice;
use Paytabs\Sdk\Holder\Builders\PaymentRequest as BuildersPaymentRequest;
use Paytabs\Sdk\Holder\Builders\Token\Token;
use Paytabs\Sdk\Holder\Builders\TransactionQuery as BuildersTransactionQuery;
use Paytabs\Sdk\Request\Requests\Invoice\NewInvoice;
use Paytabs\Sdk\Request\Requests\PaymentRequest;
use Paytabs\Sdk\Request\Requests\TokenQuery;
use Paytabs\Sdk\Request\Requests\TransactionQuery;
use Paytabs\Sdk\Holder\Builders\Invoice\InvoiceStatus as BuilderInvoiceStatus;
use Paytabs\Sdk\Holder\Builders\Invoice\InvoiceStatusGet as BuildersInvoiceStatusGet;
use Paytabs\Sdk\Request\Requests\Invoice\InvoiceStatus;
use Paytabs\Sdk\Request\Requests\Invoice\InvoiceStatusGet;
use Paytabs\Sdk\Request\Requests\TokenDelete;

class RequestsFactory
{
    public static function paymentRequest(
        Gateway $gateway,
        BuildersPaymentRequest $holder
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
        BuildersTransactionQuery $holder
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
        BuildersInvoiceStatusGet $holder
    ) {
        return new InvoiceStatusGet($gateway, $holder);
    }
}
