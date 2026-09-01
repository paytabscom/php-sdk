<?php

declare(strict_types=1);

namespace Paytabs\Sdk\Response\Payload\Payloads;

use Paytabs\Sdk\Logger\ErrorLogLogger;
use Paytabs\Sdk\Response\Payload\AbstractPayload;
use Psr\Log\LoggerInterface;

abstract class Paytabs extends AbstractPayload
{
    // Only returned in the Query API flow, not in the Webhook callback (IPN) flow.
    public ?string $trace = null;

    protected static bool $strictMode = false;

    private static ?LoggerInterface $logger = null;

    /**
     * JsonMapper does reflection and doc-block parsing on construction and is
     * stateless for our use, so one shared instance serves every mapping.
     */
    private static ?\JsonMapper $jsonMapper = null;

    public static function setStrictMode(bool $enabled): void
    {
        self::$strictMode = $enabled;
    }

    public static function isStrictMode(): bool
    {
        return self::$strictMode;
    }

    /**
     * Logger for diagnostics raised while mapping (e.g. an unrecognised
     * transaction status). Pass null to restore the default.
     *
     * Mapping happens inside JsonMapper, which constructs these objects itself,
     * so there is no instance to inject into — hence a static.
     */
    public static function setLogger(?LoggerInterface $logger): void
    {
        self::$logger = $logger;
    }

    /**
     * @throws \InvalidArgumentException if the payload is not valid or cannot be mapped.
     * @return static
     */
    public function getMapped(): static
    {
        try {
            return self::jsonMapper()->map($this->getAsJson(), $this);
        } catch (\JsonMapper_Exception $th) {
            throw new \InvalidArgumentException('Failed to map payload', 0, $th);
        } catch (\Throwable $th) {
            throw new \InvalidArgumentException('Failed to map', 0, $th);
        }
    }

    /**
     * Defaults to ErrorLogLogger: a diagnostic during mapping must never throw
     * and must never create a log file, or an un-handled error takes
     * down the merchant's callback handler.
     */
    protected static function logger(): LoggerInterface
    {
        return self::$logger ??= new ErrorLogLogger('PayTabs');
    }

    private static function jsonMapper(): \JsonMapper
    {
        return self::$jsonMapper ??= new \JsonMapper();
    }
}
