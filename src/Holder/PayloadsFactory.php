<?php

namespace Paytabs\Sdk\Holder;

use Paytabs\Sdk\Holder\Payloads\Followup;
use Paytabs\Sdk\Holder\Payloads\Followup\Refund;
use Paytabs\Sdk\Holder\Payloads\HostedPage;
use Paytabs\Sdk\Holder\Payloads\Invoice\Invoice;
use Paytabs\Sdk\Holder\Payloads\Invoice\InvoiceStatus;
use Paytabs\Sdk\Holder\Payloads\Invoice\InvoiceStatusGet;
use Paytabs\Sdk\Holder\Payloads\OwnForm;
use Paytabs\Sdk\Holder\Payloads\RecurringPayment;
use Paytabs\Sdk\Holder\Payloads\Token\Token;
use Paytabs\Sdk\Holder\Payloads\TransactionQuery;

class PayloadsFactory
{
    public static function hostedPage(): HostedPage
    {
        return new HostedPage();
    }

    public static function ownForm(): OwnForm
    {
        return new OwnForm();
    }

    public static function transactionQuery(): TransactionQuery
    {
        return new TransactionQuery();
    }

    public static function followup(): Followup
    {
        return new Followup();
    }

    public static function refund(): Refund
    {
        return new Refund();
    }

    public static function recurringPayment(): RecurringPayment
    {
        return new RecurringPayment();
    }

    public static function invoiceCreate(): Invoice
    {
        return new Invoice();
    }

    public static function invoiceStatus(bool $asGet = false)
    {
        if ($asGet) {
            return new InvoiceStatusGet();
        }

        return new InvoiceStatus();
    }

    public static function token()
    {
        return new Token();
    }
}
