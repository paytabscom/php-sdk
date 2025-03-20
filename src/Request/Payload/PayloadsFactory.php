<?php

namespace Paytabs\Sdk\Request\Payload;

use Paytabs\Sdk\Request\Payload\Payloads\Followup;
use Paytabs\Sdk\Request\Payload\Payloads\Followup\Refund;
use Paytabs\Sdk\Request\Payload\Payloads\HostedPage;
use Paytabs\Sdk\Request\Payload\Payloads\Invoice\Invoice;
use Paytabs\Sdk\Request\Payload\Payloads\Invoice\InvoiceStatus;
use Paytabs\Sdk\Request\Payload\Payloads\Invoice\InvoiceStatusGet;
use Paytabs\Sdk\Request\Payload\Payloads\ManagedForm;
use Paytabs\Sdk\Request\Payload\Payloads\OwnForm;
use Paytabs\Sdk\Request\Payload\Payloads\RecurringPayment;
use Paytabs\Sdk\Request\Payload\Payloads\Token\Token;
use Paytabs\Sdk\Request\Payload\Payloads\TransactionQuery;

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

    public static function managedForm(): ManagedForm
    {
        return new ManagedForm();
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
