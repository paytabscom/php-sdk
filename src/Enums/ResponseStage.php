<?php

namespace Enums;

enum ResponseStage
{
    case Redirect;
    case Error;
    case Completed;

    case UnKnown;
}
