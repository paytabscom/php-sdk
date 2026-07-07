<?php

declare(strict_types=1);

namespace Paytabs\Sdk\Enums;

enum HttpRequestPart
{
    case Header;
    case Body;
    case Query;
    case Path;
}
