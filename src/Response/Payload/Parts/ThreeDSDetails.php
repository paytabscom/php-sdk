<?php

declare(strict_types=1);

namespace Paytabs\Sdk\Response\Payload\Parts;

class ThreeDSDetails
{
    public ?int $responseLevel = null;
    public ?int $responseStatus = null;

    public ?string $enrolled = null; // "Y" / "N"
    public ?string $paResStatus = null; // "Y" / "N",

    public ?string $eci = null; // "05",
    public ?string $cavv = null;
    public ?string $ucaf = null;

    public ?string $threeDSVersion = null;
}
