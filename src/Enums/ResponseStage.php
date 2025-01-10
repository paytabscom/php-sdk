<?php

namespace Paytabs\Sdk\Enums;

enum ResponseStage
{
    case Redirect;
    case Error;
    case Completed;

    case UnKnown;
}
