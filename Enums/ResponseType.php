<?php

namespace Enums;

enum ResponseType
{
    case Redirect;
    case Error;
    case Completed;

    case UnKnown;
}
