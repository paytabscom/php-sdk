<?php

declare(strict_types=1);

namespace Paytabs\Sdk\Enums;

enum ResponseStage
{
    case Redirect;
    case Error;
    case Completed;

    case Unknown;
}
