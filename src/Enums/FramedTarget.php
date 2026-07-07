<?php

declare(strict_types=1);

namespace Paytabs\Sdk\Enums;

enum FramedTarget
{
    case NoReturn;
    case ReturnParent;
    case ReturnTop;
}
