<?php

namespace Paytabs\Sdk\Enums;

enum HttpRequestPart
{
    case Header;
    case Body;
    case Query;
    case Path;
}
