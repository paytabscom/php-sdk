<?php

declare(strict_types=1);

namespace Paytabs\Sdk\Enums;

enum InvoiceExternalPayMethod: string
{
    case Cash = 'cash';
    case Cheque = 'cheque';
    case Bank = 'banktransfer';
    case PoS = 'pointofsale';

    case Unknown = 'unknown';
}
