<?php

declare(strict_types=1);

namespace Paytabs\Sdk\Response\Payload\Parts;

class ThreeDSDetails
{
    public int $responseLevel;
    public int $responseStatus;

    public string $enrolled; // "Y" / "N"
    public string $paResStatus; // "Y" / "N",

    public string $eci; // "05",
    public string $cavv;
    public string $ucaf;

    public string $threeDSVersion;
}
