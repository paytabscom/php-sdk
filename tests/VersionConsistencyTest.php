<?php

declare(strict_types=1);

namespace Paytabs\Sdk\Tests;

use Paytabs\Sdk\Paytabs;
use PHPUnit\Framework\TestCase;

/**
 * Paytabs::VERSION is shipped to the gateway as plugin_info.plugin_version, so a
 * stale constant misreports the running code. It has drifted from the changelog
 * twice; this pins the two together.
 *
 * @internal
 */
final class VersionConsistencyTest extends TestCase
{
    public function testVersionConstantMatchesTheNewestChangelogEntry(): void
    {
        self::assertSame(
            $this->newestChangelogVersion(),
            Paytabs::VERSION,
            'Paytabs::VERSION and the newest CHANGELOG.md release heading disagree. '
            . 'Bump the constant and add the changelog entry together.'
        );
    }

    public function testVersionConstantIsSemver(): void
    {
        self::assertMatchesRegularExpression('/^\d+\.\d+\.\d+(?:-[0-9A-Za-z.-]+)?$/', Paytabs::VERSION);
    }

    private function newestChangelogVersion(): string
    {
        $path = \dirname(__DIR__) . '/CHANGELOG.md';

        $changelog = file_get_contents($path);
        self::assertIsString($changelog, 'CHANGELOG.md is unreadable.');

        // First "## [x.y.z]" heading, skipping any "## [Unreleased]".
        $matched = preg_match('/^## \[(\d+\.\d+\.\d+(?:-[0-9A-Za-z.-]+)?)\]/m', $changelog, $m);
        self::assertSame(1, $matched, 'No versioned release heading found in CHANGELOG.md.');

        return $m[1];
    }
}
