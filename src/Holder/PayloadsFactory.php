<?php

namespace Paytabs\Sdk\Holder;

use Paytabs\Sdk\Holder\Builders\Followup;
use Paytabs\Sdk\Holder\Builders\Followup\Refund;
use Paytabs\Sdk\Holder\Builders\HostedPage;
use Paytabs\Sdk\Holder\Builders\Invoice\Invoice;
use Paytabs\Sdk\Holder\Builders\Invoice\InvoiceStatus;
use Paytabs\Sdk\Holder\Builders\Invoice\InvoiceStatusGet;
use Paytabs\Sdk\Holder\Builders\OwnForm;
use Paytabs\Sdk\Holder\Builders\RecurringPayment;
use Paytabs\Sdk\Holder\Builders\Token\Token;
use Paytabs\Sdk\Holder\Builders\TransactionQuery;

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
