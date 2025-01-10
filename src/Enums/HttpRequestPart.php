<?php

namespace Enums;

enum HttpRequestPart
{
    case Header;
    case Body;
    case Query;
}
