<?php

declare(strict_types=1);

namespace Paytabs\Sdk\Enums;

enum InvoiceStatus: string
{
    case Pending = 'pending';
    case Paid = 'paid';
    case Cancelled = 'cancelled';
    case Expired = 'expired';
    case Overdue = 'overdue';
    case Refunded = 'refunded';
    case Failed = 'failed';

    case Unknown = 'unknown';
}
