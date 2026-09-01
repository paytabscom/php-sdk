<?php

declare(strict_types=1);

namespace Paytabs\Sdk\Logger;

/**
 * Removes cardholder data and credentials from log output.
 *
 * Storing a PAN in a log is a PCI-DSS problem and storing a CVV is a
 * violation outright (PCI-DSS 3.2), so redaction happens here — in one
 * place, before anything reaches a PSR-3 logger.
 */
final class Redactor
{
    public const MASK = '[REDACTED]';

    /**
     * Replaced with the mask wherever they appear as an array key, at any depth.
     * Matched case-insensitively, ignoring `-` and `_`.
     */
    private const SECRET_KEYS = [
        'cvv',
        'cvc',
        'cvv2',
        'authorization',
        'serverkey',
        'password',
        'secret',
        'apikey',
    ];

    /**
     * Masked to first-6 / last-4 (the most PCI-DSS permits).
     */
    private const PAN_KEYS = [
        'pan',
        'cardnumber',
        'ccnumber',
    ];

    /**
     * Recursively redacts secret values in a log context.
     */
    public static function context(array $context): array
    {
        $redacted = [];

        foreach ($context as $key => $value) {
            $normalised = self::normaliseKey((string) $key);

            if (\in_array($normalised, self::SECRET_KEYS, true)) {
                $redacted[$key] = self::MASK;

                continue;
            }

            if (\in_array($normalised, self::PAN_KEYS, true)) {
                $redacted[$key] = \is_string($value) || \is_int($value)
                    ? self::maskPan((string) $value)
                    : self::MASK;

                continue;
            }

            $redacted[$key] = self::value($value);
        }

        return $redacted;
    }

    /**
     * Masks a PAN to first-6 / last-4, e.g. `411111******1111`.
     */
    public static function maskPan(string $pan): string
    {
        $digits = preg_replace('/\D/', '', $pan) ?? '';
        $length = \strlen($digits);

        // Too short to mask meaningfully — drop it entirely rather than leak it.
        if ($length < 12) {
            return self::MASK;
        }

        return substr($digits, 0, 6)
            . str_repeat('*', $length - 10)
            . substr($digits, -4);
    }

    /**
     * Strips CR/LF.
     */
    public static function singleLine(string $message): string
    {
        return str_replace(["\r\n", "\r", "\n"], ' ', $message);
    }

    /**
     * Full cleanup for a free-text log message.
     *
     * Key-based redaction cannot help here — a message has no keys — so a PAN
     * interpolated straight into the text (`"charging 4111111111111111"`) would
     * otherwise be written verbatim.
     */
    public static function message(string $message): string
    {
        return self::singleLine(
            self::headerLine(
                self::embeddedJson(
                    self::maskLoosePans($message)
                )
            )
        );
    }

    /**
     * Masks bare 13-19 digit runs that pass Luhn.
     *
     * The Luhn gate keeps order references, timestamps and amounts untouched:
     * roughly 9 in 10 random digit runs fail it.
     */
    public static function maskLoosePans(string $value): string
    {
        return (string) preg_replace_callback(
            '/(?<![0-9])(?:[0-9][ -]?){12,18}[0-9](?![0-9])/',
            static function (array $m): string {
                $digits = preg_replace('/\D/', '', $m[0]) ?? '';

                if (\strlen($digits) < 13 || \strlen($digits) > 19 || !self::passesLuhn($digits)) {
                    return $m[0];
                }

                return self::maskPan($digits);
            },
            $value
        );
    }

    private static function passesLuhn(string $digits): bool
    {
        $sum = 0;
        $double = false;

        for ($i = \strlen($digits) - 1; $i >= 0; --$i) {
            $digit = (int) $digits[$i];

            if ($double) {
                $digit *= 2;

                if ($digit > 9) {
                    $digit -= 9;
                }
            }

            $sum += $digit;
            $double = !$double;
        }

        return 0 === $sum % 10;
    }

    /**
     * Redacts every `Authorization: <serverKey>` style header line.
     *
     * Tolerates cURL's verbose-trace prefixes (`> `, `< `, `* `) and matches at
     * any line start, not just the start of the string: a verbose trace arrives
     * as one multi-line blob, so an anchor-only pattern redacted nothing.
     */
    public static function headerLine(string $header): string
    {
        // Matches the key anywhere on a line, not just at the start, so it also
        // catches cURL's `> ` verbose prefix and the `[Authorization] => value`
        // shape a print_r()'d header array produces. The separator may be `:`
        // or `=>`, and the key may be bracketed or quoted.
        return (string) preg_replace(
            '/(["\'\[]?authorization["\'\]]?[ \t]*(?::|=>)[ \t]*).*$/im',
            '$1' . self::MASK,
            $header
        );
    }

    /**
     * Non-reversible hint for correlating which key was used, safe to log.
     */
    public static function keyHint(string $secret): string
    {
        if ('' === $secret) {
            return self::MASK;
        }

        return substr(hash('sha256', $secret), 0, 8);
    }

    private static function value(mixed $value): mixed
    {
        if (\is_array($value)) {
            return self::context($value);
        }

        if (\is_string($value)) {
            return self::scalarString($value);
        }

        if ($value instanceof \Stringable) {
            return self::scalarString((string) $value);
        }

        if (\is_object($value)) {
            // Objects are commonly whole request payloads; walk their public
            // state so nested `pan`/`cvv` do not slip through.
            return self::context(get_object_vars($value));
        }

        return $value;
    }

    private static function scalarString(string $value): string
    {
        return self::singleLine(self::headerLine(self::embeddedJson($value)));
    }

    /**
     * Redacts card data inside an already-serialised JSON string.
     *
     * Key-based redaction cannot see into a string, and the whole request body
     * is commonly logged as one — `$logger->debug('...', [$request->getPayload()])`.
     */
    private static function embeddedJson(string $value): string
    {
        // Cheap guard: skip the regex work for the overwhelming majority of
        // strings, which contain no card fields at all.
        if (!preg_match('/"(pan|cvv|cvc|cvv2|card_?number)"\s*:/i', $value)) {
            return $value;
        }

        $value = preg_replace_callback(
            '/"(pan|card_?number)"\s*:\s*"([^"]*)"/i',
            static fn(array $m): string => '"' . $m[1] . '":"' . self::maskPan($m[2]) . '"',
            $value
        ) ?? $value;

        return preg_replace(
            '/"(cvv|cvc|cvv2)"\s*:\s*"?[^",}]*"?/i',
            '"$1":"' . self::MASK . '"',
            $value
        ) ?? $value;
    }

    private static function normaliseKey(string $key): string
    {
        return str_replace(['-', '_', ' '], '', strtolower($key));
    }
}
