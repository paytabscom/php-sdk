<?php

namespace Paytabs\Sdk\Enums;

enum InvoicePaidPayMethods: string
{
    case Cash = 'cash';
    case Cheque = 'cheque';
    case Bank = 'bank';
    case POS = 'pos';
}
