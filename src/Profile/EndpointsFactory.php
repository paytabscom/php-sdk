<?php

namespace Paytabs\Sdk\Profile;

use Paytabs\Sdk\Profile\Endpoints\Egypt;
use Paytabs\Sdk\Profile\Endpoints\GlobalPt;
use Paytabs\Sdk\Profile\Endpoints\Iraq;
use Paytabs\Sdk\Profile\Endpoints\Jordan;
use Paytabs\Sdk\Profile\Endpoints\Ksa;
use Paytabs\Sdk\Profile\Endpoints\Kuwait;
use Paytabs\Sdk\Profile\Endpoints\Oman;
use Paytabs\Sdk\Profile\Endpoints\Uae;

final class EndpointsFactory
{
    public static function getUaeEndpoint(): Uae
    {
        return Uae::getInstance();
    }

    public static function getKsaEndpoint(): Ksa
    {
        return Ksa::getInstance();
    }

    public static function getEgyptEndpoint(): Egypt
    {
        return Egypt::getInstance();
    }

    public static function getIraqEndpoint(): Iraq
    {
        return Iraq::getInstance();
    }

    public static function getJordanEndpoint(): Jordan
    {
        return Jordan::getInstance();
    }

    public static function getKuwaitEndpoint(): Kuwait
    {
        return Kuwait::getInstance();
    }

    public static function getOmanEndpoint(): Oman
    {
        return Oman::getInstance();
    }

    public static function getGlobalPtEndpoint(): GlobalPt
    {
        return GlobalPt::getInstance();
    }
}
