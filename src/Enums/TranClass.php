<?php

namespace Paytabs\Sdk\Enums;

enum TranClass: string
{
    case Ecom = 'ecom';
    case Moto = 'moto';
    case Recurring = 'recurring';
}
