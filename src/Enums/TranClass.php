<?php

namespace Paytabs\Sdk\Enums;

enum TranClass: string
{
    case Ecom = 'ecom';
    case Moto = 'moto';
    case Recurring = 'recurring';
    case EcomToken = 'ecomtoken';
    case CAuth = 'c/auth';
    case NFC = 'nfc';

    case UnKnown = 'unknown';
}
