<?php

declare(strict_types=1);

namespace Paytabs\Sdk\Tests\Doubles;

use Psr\Log\AbstractLogger;

/**
 * Captures log calls so tests can assert on what the SDK reports.
 *
 * A named class rather than an anonymous one: `new class($args) extends ...`
 */
final class RecordingLogger extends AbstractLogger
{
    /** @var list<array{level: string, message: string, context: array<string, mixed>}> */
    private array $records = [];

    public function log($level, string|\Stringable $message, array $context = []): void
    {
        $this->records[] = [
            'level' => (string) $level,
            'message' => (string) $message,
            'context' => $context,
        ];
    }

    /**
     * @return list<array{level: string, message: string, context: array<string, mixed>}>
     */
    public function records(): array
    {
        return $this->records;
    }

    /**
     * @return null|array{level: string, message: string, context: array<string, mixed>}
     */
    public function first(): ?array
    {
        return $this->records[0] ?? null;
    }

    public function count(): int
    {
        return \count($this->records);
    }
}
