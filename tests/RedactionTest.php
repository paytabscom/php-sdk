<?php

declare(strict_types=1);

namespace Paytabs\Sdk\Tests;

use Paytabs\Sdk\Logger\BrowserLog;
use Paytabs\Sdk\Logger\FileLog;
use Paytabs\Sdk\Logger\Redactor;
use Paytabs\Sdk\PaytabsLogger;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

/**
 * Cardholder data and credentials must not reach a log or the browser.
 *
 * @internal
 */
final class RedactionTest extends TestCase
{
    private const SERVER_KEY = 'AAAAAAAAA9-BBBBBBBBB8-CCCCCCCCC7';
    private const PAN = '4111111111111111';

    public function testPanIsMaskedToFirstSixLastFour(): void
    {
        self::assertSame('411111******1111', Redactor::maskPan(self::PAN));
    }

    public function testAShortPanIsDroppedRatherThanPartiallyExposed(): void
    {
        self::assertSame(Redactor::MASK, Redactor::maskPan('411111'));
    }

    /**
     * Key-based redaction cannot see a PAN interpolated straight into the
     * message text, and the ReadMe promises loggers strip cardholder data.
     */
    #[DataProvider('panInMessageProvider')]
    public function testAPanInAFreeTextMessageIsMasked(string $message, string $pan): void
    {
        $redacted = Redactor::message($message);

        self::assertStringNotContainsString($pan, $redacted);

        // Masked to first-6/last-4, so the middle digits are gone. The number
        // of asterisks varies with PAN length (15-digit Amex vs 16-digit Visa).
        self::assertMatchesRegularExpression('/\d{6}\*+\d{4}/', $redacted);
    }

    /**
     * @return iterable<string, array{string, string}>
     */
    public static function panInMessageProvider(): iterable
    {
        yield 'bare' => ['charging ' . self::PAN, self::PAN];
        yield 'spaced' => ['card 4111 1111 1111 1111 declined', '4111 1111 1111 1111'];
        yield 'hyphenated' => ['card 4111-1111-1111-1111', '4111-1111-1111-1111'];
        yield 'mastercard' => ['pan=5555555555554444', '5555555555554444'];
        yield 'amex 15 digit' => ['amex 378282246310005', '378282246310005'];
    }

    /**
     * The sweep is Luhn-gated so ordinary identifiers survive — a redactor that
     * mangles cart IDs and timestamps gets switched off.
     */
    #[DataProvider('nonPanProvider')]
    public function testOrdinaryIdentifiersAreNotMangled(string $message): void
    {
        self::assertSame($message, Redactor::message($message));
    }

    /**
     * @return iterable<string, array{string}>
     */
    public static function nonPanProvider(): iterable
    {
        yield 'order reference' => ['order 1234567890123'];
        yield 'timestamp' => ['ts 1788242461146'];
        yield 'cart id' => ['cart_id 20260901120000'];
        yield 'tran ref' => ['tran TST2623802990085'];
        yield 'amount' => ['amount 700.00'];
        yield 'invoice id' => ['invoice 5829212'];
    }

    public function testAServerKeyInAMessageHeaderLineIsRedacted(): void
    {
        $redacted = Redactor::message('Authorization: ' . self::SERVER_KEY);

        self::assertStringNotContainsString(self::SERVER_KEY, $redacted);
    }

    /**
     * The key does not always start the line: cURL prefixes verbose output with
     * `> `, and a print_r()'d header array yields `[Authorization] => value`.
     */
    #[DataProvider('authorizationLineProvider')]
    public function testAnAuthorizationValueIsRedactedInAnyLineShape(string $line): void
    {
        $redacted = Redactor::headerLine($line);

        self::assertStringNotContainsString(self::SERVER_KEY, $redacted);
        self::assertStringContainsString(Redactor::MASK, $redacted);
    }

    /**
     * @return iterable<string, array{string}>
     */
    public static function authorizationLineProvider(): iterable
    {
        yield 'plain header' => ['Authorization: ' . self::SERVER_KEY];
        yield 'curl request prefix' => ['> Authorization: ' . self::SERVER_KEY];
        yield 'curl response prefix' => ['< Authorization: ' . self::SERVER_KEY];
        yield 'open bracket' => ['[Authorization: ' . self::SERVER_KEY];
        yield 'print_r array entry' => ['[Authorization] => ' . self::SERVER_KEY];
        yield 'print_r lowercase' => ['    [authorization] => ' . self::SERVER_KEY];
        yield 'print_r uppercase' => ['[AUTHORIZATION] => ' . self::SERVER_KEY];
        yield 'numeric key then header' => ['[0] => Authorization: ' . self::SERVER_KEY];
        yield 'json' => ['"authorization": "' . self::SERVER_KEY . '"'];
        yield 'var_export' => ["'authorization' => '" . self::SERVER_KEY . "',"];
    }

    public function testEveryAuthorizationLineInAMultiLineDumpIsRedacted(): void
    {
        $dump = "Array\n(\n    [Authorization] => " . self::SERVER_KEY . "\n"
            . "    [Accept] => application/json\n"
            . '    [X-Retry] => Authorization: ' . self::SERVER_KEY . "\n)";

        $redacted = Redactor::headerLine($dump);

        self::assertStringNotContainsString(self::SERVER_KEY, $redacted);
        self::assertSame(2, substr_count($redacted, Redactor::MASK));

        // Unrelated headers survive.
        self::assertStringContainsString('[Accept] => application/json', $redacted);
    }

    /** Broadening the pattern must not swallow ordinary text. */
    #[DataProvider('nonAuthorizationLineProvider')]
    public function testOrdinaryLinesAreLeftAlone(string $line): void
    {
        self::assertSame($line, Redactor::headerLine($line));
    }

    /**
     * @return iterable<string, array{string}>
     */
    public static function nonAuthorizationLineProvider(): iterable
    {
        yield 'other header' => ['Content-Type: application/json'];
        yield 'other array entry' => ['[Accept] => application/json'];
        yield 'similar key' => ['authorization_required: false'];
        yield 'negated key' => ['unauthorized: true'];
        yield 'prose' => ['the authorization flow completed'];
        yield 'request line' => ['> POST /payment/request HTTP/1.1'];
    }

    #[DataProvider('secretKeyProvider')]
    public function testSecretKeysAreRedacted(string $key): void
    {
        $redacted = Redactor::context([$key => 'super-secret-value']);

        self::assertSame(Redactor::MASK, $redacted[$key]);
    }

    /**
     * @return iterable<string, array{string}>
     */
    public static function secretKeyProvider(): iterable
    {
        yield 'cvv' => ['cvv'];

        yield 'CVV uppercase' => ['CVV'];

        yield 'cvc' => ['cvc'];

        yield 'cvv2' => ['cvv2'];

        yield 'authorization' => ['authorization'];

        yield 'server_key underscore' => ['server_key'];

        yield 'server-key hyphen' => ['server-key'];

        yield 'password' => ['password'];
    }

    public function testRedactionRecursesIntoNestedArrays(): void
    {
        $redacted = Redactor::context([
            'card_details' => ['pan' => self::PAN, 'cvv' => '123'],
        ]);

        self::assertSame('411111******1111', $redacted['card_details']['pan']);
        self::assertSame(Redactor::MASK, $redacted['card_details']['cvv']);
    }

    public function testAuthorizationHeaderLinesAreRedacted(): void
    {
        $redacted = Redactor::context(['headers' => ['Authorization: ' . self::SERVER_KEY]]);

        self::assertStringNotContainsString(self::SERVER_KEY, (string) json_encode($redacted));
    }

    /**
     * The Own-Form sample logs the whole serialised request body, so key-based
     * redaction alone is not enough.
     */
    public function testCardDataInsideASerialisedJsonStringIsRedacted(): void
    {
        $body = (string) json_encode([
            'profile_id' => 1,
            'card_details' => ['pan' => self::PAN, 'cvv' => '0'],
        ]);

        $redacted = Redactor::context([$body]);
        $encoded = (string) json_encode($redacted);

        self::assertStringNotContainsString(self::PAN, $encoded);
        self::assertStringNotContainsString('"cvv":"0"', $encoded);
    }

    public function testNewlinesAreStrippedToPreventLogEntryForgery(): void
    {
        $redacted = Redactor::context(['respMessage' => "ok\nFAKE ENTRY: authorised"]);

        self::assertStringNotContainsString("\n", (string) $redacted['respMessage']);
    }

    public function testKeyHintIsNotReversibleAndLeaksNoPrefix(): void
    {
        $hint = Redactor::keyHint(self::SERVER_KEY);

        self::assertNotSame('', $hint);
        self::assertStringNotContainsString(substr(self::SERVER_KEY, 0, 10), $hint);
        self::assertStringNotContainsString(self::SERVER_KEY, $hint);
        self::assertSame($hint, Redactor::keyHint(self::SERVER_KEY), 'must be stable');
        self::assertNotSame($hint, Redactor::keyHint('OTHER-KEY-VALUE'));
    }

    // ------------------------------------------------------------- FileLog

    public function testFileLogRedactsAndSetsOwnerOnlyPermissions(): void
    {
        $path = sys_get_temp_dir() . '/pt-redaction-' . bin2hex(random_bytes(6)) . '.log';

        try {
            (new FileLog('PayTabs', $path))->debug('Payload', [
                'card_details' => ['pan' => self::PAN, 'cvv' => '123'],
                'headers' => ['Authorization: ' . self::SERVER_KEY],
            ]);

            self::assertFileExists($path);

            $contents = (string) file_get_contents($path);

            self::assertStringNotContainsString(self::PAN, $contents);
            self::assertStringNotContainsString(self::SERVER_KEY, $contents);
            self::assertStringNotContainsString('"123"', $contents);
            self::assertStringContainsString('411111******1111', $contents);

            self::assertSame(0o600, fileperms($path) & 0o777);
        } finally {
            @unlink($path);
        }
    }

    /**
     * A logger must never break the payment flow it observes: an unwritable log
     * directory is an operations problem, not a reason to fail a transaction.
     */
    public function testFileLogFailsSoftOnAnUnwritablePath(): void
    {
        $logger = new FileLog('PayTabs', '/proc/pt-not-writable/x.log');

        $logger->error('must not throw');

        $this->expectNotToPerformAssertions();
    }

    // ---------------------------------------------------------- BrowserLog

    public function testBrowserLogEscapesHtmlAndRedactsSecrets(): void
    {
        ob_start();

        try {
            (new BrowserLog('PayTabs'))->error('</pre><script>alert(1)</script>', [
                'card_details' => ['pan' => self::PAN, 'cvv' => '123'],
                'headers' => ['[Authorization: ' . self::SERVER_KEY . ']'],
            ]);
            $output = (string) ob_get_clean();
        } catch (\Throwable $e) {
            ob_end_clean();

            throw $e;
        }

        self::assertStringNotContainsString('<script>', $output);
        self::assertStringContainsString('&lt;script&gt;', $output);
        self::assertStringNotContainsString(self::PAN, $output);
        self::assertStringNotContainsString(self::SERVER_KEY, $output);
    }

    // --------------------------------------------------------- log location

    /**
     * The inverted condition here sent logs to a shared temp directory at the
     * exact moment a caller asked for a private one.
     */
    public function testGetLogFileHonoursAnExplicitPath(): void
    {
        $dir = sys_get_temp_dir() . '/pt-logpath-' . bin2hex(random_bytes(6));

        try {
            $file = PaytabsLogger::getLogFile($dir);

            self::assertStringStartsWith($dir . \DIRECTORY_SEPARATOR, $file);
        } finally {
            @rmdir($dir);
        }
    }
}
