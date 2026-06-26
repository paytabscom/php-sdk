<?php

namespace Paytabs\Sdk\Request\Payload;

use Paytabs\Sdk\Request\Payload\Payloads\Followup;
use Paytabs\Sdk\Request\Payload\Payloads\Followup\Refund;
use Paytabs\Sdk\Request\Payload\Payloads\HostedPage;
use Paytabs\Sdk\Request\Payload\Payloads\Invoice\Invoice;
use Paytabs\Sdk\Request\Payload\Payloads\Invoice\InvoiceCancel;
use Paytabs\Sdk\Request\Payload\Payloads\Invoice\InvoiceMarkPaid;
use Paytabs\Sdk\Request\Payload\Payloads\Invoice\InvoiceSms;
use Paytabs\Sdk\Request\Payload\Payloads\Invoice\InvoiceStatus;
use Paytabs\Sdk\Request\Payload\Payloads\Invoice\InvoiceStatusGet;
use Paytabs\Sdk\Request\Payload\Payloads\ManagedForm;
use Paytabs\Sdk\Request\Payload\Payloads\OwnForm;
use Paytabs\Sdk\Request\Payload\Payloads\RecurringPayment;
use Paytabs\Sdk\Request\Payload\Payloads\Token\Token;
use Paytabs\Sdk\Request\Payload\Payloads\TransactionQuery;

class PayloadsFactory
{
    public static function createHostedPage(): HostedPage
    {
        return new HostedPage();
    }

    public static function createOwnForm(): OwnForm
    {
        return new OwnForm();
    }

    public static function createManagedForm(): ManagedForm
    {
        return new ManagedForm();
    }

    public static function createTransactionQuery(): TransactionQuery
    {
        return new TransactionQuery();
    }

    public static function createFollowup(): Followup
    {
        return new Followup();
    }

    public static function createRefund(): Refund
    {
        return new Refund();
    }

    public static function createRecurringPayment(): RecurringPayment
    {
        return new RecurringPayment();
    }

    public static function createInvoice(): Invoice
    {
        return new Invoice();
    }

    public static function createInvoiceStatusAsGet(): InvoiceStatusGet
    {
        return new InvoiceStatusGet();
    }

    public static function createInvoiceStatusAsPost(): InvoiceStatus
    {
        return new InvoiceStatus();
    }

    public static function createInvoiceCancel(): InvoiceCancel
    {
        return new InvoiceCancel();
    }

    public static function createInvoiceMarkPaid(): InvoiceMarkPaid
    {
        return new InvoiceMarkPaid();
    }

    public static function createInvoiceSms(): InvoiceSms
    {
        return new InvoiceSms();
    }

    public static function createToken(): Token
    {
        return new Token();
    }
}
