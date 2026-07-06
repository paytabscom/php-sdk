<?php

namespace Paytabs\Sdk\Response\Payload\Payloads;

use Paytabs\Sdk\Response\Payload\AbstractPayload;

abstract class Paytabs extends AbstractPayload
{
    public string $trace;
    protected static bool $strictMode = false;

    public static function setStrictMode(bool $enabled): void
    {
        self::$strictMode = $enabled;
    }

    public static function isStrictMode(): bool
    {
        return self::$strictMode;
    }

    public function getMapped(): static
    {
        $jsonMapper = new \JsonMapper();

        return $jsonMapper->map($this->getAsJson(), $this);
    }
}
