# Changelog

All notable changes to this project will be documented in this file.

The format is based on Keep a Changelog, and this project aims to follow Semantic Versioning.

## [3.3.0] - 2026-09-01

Hardening release following a full SDK audit. It closes several crashes on
ordinary gateway responses, removes two paths that could leak a live server key
into logs or an HTTP response, and makes transaction status honest about the
difference between "declined" and "unknown".

Most integrations upgrade without changes. Read **Breaking changes** first if you
call the status predicates or implement any SDK interface.

### Breaking changes

- **`Completed::isPaymentSuccessful()` / `isPaymentFailed()` are renamed to
  `isTransactionSuccessful()` / `isTransactionFailed()`**, matching `Browser` and
  making the subject explicit: they report the transaction, not the order. Update
  any call site — the old names are gone.
- **Status predicates now return `?bool` instead of `bool`.** Affects
  `PaymentResult::isSuccessful()` / `isFailed()`, and the
  `isTransaction*()` trio on `Completed` and `Browser`. `null` means the gateway
  reported no status, so the outcome is unknown — previously this was a fatal
  error or an unhelpful `false`. Compare against `true` explicitly;
  `if ($completed->isTransactionSuccessful())` still behaves correctly, but
  `!isTransactionFailed()` is now true for pending *and* unknown.
- **`TranStatus::Unknown` is no longer classified as failed.** `isFailed()`
  returns `false` for it, and the new `isUnknown()` reports it. A future gateway
  status code no longer reads as a definite failure, which previously could fire
  a merchant's auto-cancel or auto-refund on a transaction that had succeeded.
- **`PaymentResult::toString()` is now `__toString()`**, so string interpolation
  and PSR-3 message placeholders work. Replace any explicit `->toString()` call
  with a cast or interpolation.
- **Narrowed parameter and return types** on internal plumbing:
  `Helpers::jsonValidate(string)` (was `array|string`),
  `AbstractResponse::setResponse(string)` (was `mixed`),
  `setResponseData(string)` and `getResponseData(): string` (were `array|string`),
  `InvoiceStatus::setTranStatus(?string)` (was `string`),
  `LineItem::setPrice(int, float, ?float)` (`$netTotal` is now optional).
  Only affects callers that passed an array, or classes implementing
  `ResponseInterface` / `PayloadInterface` directly.
- **`Paytabs::setLogger()` gained an optional `bool $debugMode` second argument**,
  which forwards to `Http::setDebugMode()`. Source-compatible for callers; a
  breaking signature change only for subclasses that override it.
  `Http::setLogger()` itself is unchanged and still takes one argument.

### Added

- `PaytabsExceptionInterface`, implemented by every exception the SDK throws, so
  a single `catch (PaytabsExceptionInterface $e)` covers all of them. Each
  exception still extends the same SPL class, so existing
  `catch (\RuntimeException)` blocks keep working.
- `MissingResponseFieldException`, thrown when the gateway omits a field the SDK
  needs, replacing an uninitialised-property `Error`.
- `GatewayFailureException`, `UnexpectedResponseStageException` and
  `UnsupportedPayloadOperationException`, replacing the last bare `\Exception`
  throws in `src/`. Every error the SDK raises is now catchable through
  `PaytabsExceptionInterface`.
- `Completed::isFollowup()`, so a refund, void, release or capture IPN can be
  told apart from an original payment. PayTabs sends an IPN for every
  transaction against a cart, and the status predicates describe the
  transaction — a completed refund reports
  `isTransactionSuccessful() === true`. Check `isFollowup()` before marking an
  order paid, or a refund re-marks it paid. See `docs/usage/Webhooks.md`.
- `AbstractTransactionResult::assertGenuine()` — a fail-closed counterpart to
  `isGenuine()` that throws `InvalidSignatureException` rather than returning
  `false`, so a forged webhook cannot be processed by forgetting a check.
- `AbstractTransactionResult::getConfiguredProfileId()`, the locally configured
  profile ID as distinct from the one echoed by a webhook payload.
- `Completed::isTransactionPending()`, plus `Browser::isTransactionFailed()` and
  `isTransactionPending()`, so both payloads expose the same three-state view
  instead of a success check alone.
- `Logger\Redactor`: masks PANs to first-6/last-4, strips CVV, `Authorization`
  headers and key-like fields, redacts card data inside an already-serialised
  JSON string, and derives a non-reversible `keyHint()` from a server key.
- `Logger\ErrorLogLogger`, a never-throwing default used for diagnostics raised
  during response mapping.
- `HttpRequestException::unexpectedResponseBody()`, `::transport()` and
  `::payloadNotEncodable()` factories, each naming the failure.
- Luhn validation for card numbers (`CardDetails::passesLuhn()`).
- `Samples/.htaccess` denying dotfile access, `Samples/TransactionQueryByCart.php`,
  and a `BROWSER_LOG` samples flag that defaults to off.

### Changed

- Runtime support stays at PHP `^8.1`, so the SDK keeps working inside
  e-commerce plugins on older merchant hosting. Contributing now requires PHP
  8.2+, because PHPUnit 11 dropped 8.1 — the dev toolchain only, never consumers.
- The PHPUnit constraint is now `^11.5 || ^12.0 || ^13.0` so the dev toolchain
  resolves on every version CI exercises. Previously `^13.2` required
  PHP >= 8.4.1, which prevented `composer install` on 8.1, 8.2 and 8.3.
- CI now runs the suite on 8.2/8.3/8.4 plus a `--prefer-lowest` job on 8.2, a
  `runtime-php81` job exercising only the consumer-facing composer commands on
  8.1, `composer audit`, and a coverage report. PHPUnit fails on warnings, risky
  tests, notices and deprecations.
- Requests now send `Content-Type: application/json` explicitly instead of
  relying on the gateway sniffing the body.
- `CURLOPT_CONNECTTIMEOUT` is set (10s) alongside the existing 30s timeout.
- GET requests no longer build and discard a request body.
- Response DTO properties are nullable with defaults throughout, so a `null` in
  any single field no longer aborts the whole mapping.
- A single shared `JsonMapper` instance is reused instead of one per mapping call.
- `Profile` rejects a non-positive profile ID and trims the server key, so a
  whitespace-only key no longer ships as an empty `Authorization` header.
- Invalid signatures in samples return `403` and stop before the payload is read.
- `Samples/config.php`: `getConfig()` gained an explicit `$hasDefault` parameter,
  so a legitimately `null` default no longer falls through to an exception.
- The test suite grew from 10 files to 17, including the first coverage of
  signature verification, response mapping, transaction-status semantics and log
  redaction. Coverage is now measured and reported in CI.
- Mapping is now tested against captured live gateway responses in
  `tests/fixtures/responses/` rather than invented fixtures. `tests/` is
  `export-ignore`d, so the fixtures do not ship in the Composer package.
- `ext-curl` and `ext-json` are declared in `composer.json`.

### Fixed

- `Browser::isTransactionSuccessful()` raised
  `Error: Call to a member function isSuccessful() on null` when a browser
  callback omitted `respStatus` — trivially triggered by any unauthenticated
  request to the return URL. It now reports `null`.
- `Callback::isSameProfile()` compared the payload's `profile_id` against
  `getProfileId()`, which `Callback` overrides to return that same field, so the
  check was always true and the profile binding never ran. It now compares
  against the configured profile. Signature verification was unaffected, since
  the HMAC is keyed on the configured server key.
- `AbstractTransactionResult::getProfileId()` raised a raw property `Error` when
  no profile was set; it now throws `InvalidConfigurationException`.
- Webhook accessors no longer require `getMapped()` to be called first; mapping
  happens lazily, and the previous ordering requirement was undocumented.
- A non-2xx response with a non-JSON body (a CDN or WAF error page) escaped as a
  bare `\JsonException` from deep in the payload layer, losing the status code.
  It now throws `HttpRequestException::unexpectedResponseBody()` with the status
  and a truncated excerpt.
- A payload containing non-UTF-8 bytes — ordinary latin-1 or cp1256 customer
  data — aborted the request with a `TypeError`. Encoding now substitutes
  invalid sequences and reports a named exception if it still fails.
- A sub-object whose fields were all null serialised as a JSON array `[]` where
  the API expects an object.
- An unsubstituted path placeholder was sent to the gateway verbatim; it now
  raises `InvalidConfigurationException`, and path parameters are URL-encoded.
- `InvoiceStatus::setTranStatus()` threw on an unpaid invoice (`tran_status: null`)
  and on any unrecognised status letter.
- `PaymentMethods`, `Invoice` and `TokeniseEnhanced` fataled on uninitialised
  properties on ordinary paths, including the documented `buildTokeniseEnhanced()`
  happy path.
- **`street1` is no longer silently dropped from every webhook.** The response
  layer maps into the request `CustomerDetails` part, whose property is `$street`
  while the gateway sends `street1`.
- `Helpers::responseStage()` now handles array input instead of silently returning
  `Unknown`;
- A CVV of `"0"` skipped validation because `'0'` is falsy in PHP; an already
  expired card in the current year was accepted; the PAN pattern rejected valid
  17–19 digit numbers.
- `PaytabsLogger::getLogFile()` ignored an explicit path argument and wrote to
  the shared temp directory instead.
- `Samples/ResultBrowser.php` verified the signature into a log field and then
  mapped the payload regardless. It now fails closed.
- `Samples/index.php` enabled `BrowserLog` unconditionally, contradicting the
  ReadMe warning against enabling it outside local debugging.

### Security

- **The server key is no longer written to the verbose cURL trace.** Debug mode
  captured the trace and mapped a redaction over it, but the pattern was anchored
  at the start of the string while cURL prefixes header lines with `> `, so it
  never matched. Redaction now applies to every line of a multi-line trace.
- **A PAN interpolated into a log message is now masked.** Only the context array
  was redacted, so `$logger->info("charging 4111111111111111")` was written
  verbatim while the ReadMe promised loggers strip cardholder data. Free-text
  messages now go through a Luhn-gated sweep, which leaves order references,
  timestamps and amounts untouched.
- **A fragment of the live server key is no longer written to logs.** An invalid
  webhook signature logged the first 10 characters of the secret — a third of
  the key, on a branch any unauthenticated caller can trigger repeatedly by
  POSTing a bogus signature. It is now a SHA-256 derived hint. The same fix
  applies to `Profile::getServerKeyPrefix()` and
  `InvalidSignatureException::mismatch()`.
- **`BrowserLog` escaped nothing.** Gateway text and callback fields reached the
  HTTP response unescaped, so a payload containing `</pre><script>` executed;
  the log context was also dumped wholesale, which in the shipped samples
  included card data and the `Authorization` header. Output is now escaped and
  redacted.
- `CURLOPT_VERBOSE` wrote the `Authorization` header to stderr; the trace is now
  captured and redacted before logging.
- Log files are created `0600` and the log directory `0700`; log writes take an
  exclusive lock and fail soft rather than throwing mid-payment.
- CRLF sequences are stripped from log messages, closing log-entry forgery.
- An array-valued `signature`, and an empty request to a return URL, each caused
  an unauthenticated `TypeError`. Both now fail closed.
- Invalid browser returns and IPNs in the samples respond `403` and halt before
  mapping.

## [3.2.6] - 2026-08-12

### Changed

- PHP CS Fixer is now the single coding-style tool. The `.php-cs-fixer.dist.php` config is tracked in the repository so `composer lint` behaves the same locally and in CI.
- Applied the PER-CS style baseline across `src/`, `Samples/`, and `tests/` (whitespace-only, no behavior changes).
- Dropped the `@PhpCsFixer:risky` rule set, keeping `@auto` and `@auto:risky`.

### Removed

- `squizlabs/php_codesniffer` dev dependency; it had no ruleset, no composer script, and no CI step.

## [3.2.5] - 2026-08-11

### Added

- `TranType::isFollowup()` to identify follow-up transaction types (refund, void, release, capture).

### Changed

- Internal SDK version bumped to `3.2.5`.

## [3.2.4] - 2026-08-10

### Added

- Profile ID retrieval on transaction results, with validation that a response belongs to the configured profile.

### Fixed

- Corrected the internal SDK version constant.

## [3.2.3] - 2026-07-29

### Added

- `isPaymentFailed()` on the `Completed` response payload, complementing `isPaymentSuccessful()`.

### Changed

- Improved transaction result retrieval (query/verify) handling.
- `composer.lock` is no longer tracked in the repository.

## [3.2.2] - 2026-07-28

### Added

- `InvalidSignatureException` with a `mismatch()` factory for failed webhook/callback signature checks.
- `InvalidConfigurationException` for invalid or incomplete SDK configuration.
- `Profile::getServerKeyPrefix()` helper.
- Transaction success check helper in the `Browser` response class.
- PHP_CodeSniffer as a development dependency for coding-standards checks.
- DeepWiki badge in the documentation section of the ReadMe.

### Changed

- `Urls` constructor now defaults `callbackUrl` to `null` and applies stricter URL validation.
- Several method signatures now provide `null` defaults for optional arguments.
- `PluginInfo` initialization in `PaytabsBuilder` improved.
- Live gateway tests (which create real payments) no longer run unless explicitly enabled.
- Code formatting cleanups across the codebase.

### Fixed

- Corrected sample usage in the ReadMe.

## [3.2.1] - 2026-07-07

### Added

- `InvoiceStatus` enum.
- `declare(strict_types=1)` across payload classes and sample files.

### Changed

- `InvoiceMarkPaid` now handles unknown external payment methods gracefully.
- Refactored variable typing in sample files.

### Fixed

- Corrected the invoice external payment method enum values.

## [3.2.0] - 2026-07-06

### Changed

- Dependency refresh (`composer update`).

## [3.0.0] - 2026-06-25

First stable release of the v3 SDK. This is a full rewrite of the v2 SDK with a new object-oriented architecture and is **not backward compatible**.

### Added

- `Paytabs` facade wrapper with `getProfile()` and consolidated entry points for building requests.
- Logging framework: `AbstractLogger`, `FileLog`, and `BrowserLog`, including error handling for log directory creation and logging failures.
- Strict response mapping mode plus `UnknownResponseValueException` for unmapped gateway values.
- Transaction reference, cart ID, and transaction status accessors on `AbstractTransactionResult` and its subclasses.
- `isPaymentSuccessful()` on the `Completed` response payload and `__toString()` on `PaymentResult`.
- `Http::create()` static factory; `EndpointsFactory::getAllEndpointsAsList()`; support for passing a string or `AbstractEndpoint` to `ProfilesFactory`.
- Helper to retrieve all supported currencies from payment methods, and a helper to retrieve all endpoint instances.
- Payment methods and endpoints ported over from v2 to reach feature parity, with new factory methods for method instances.
- Architecture documentation with detailed parts mapping, payment parts reference, and diagrams.
- Public release governance docs: LICENSE, SECURITY policy, CONTRIBUTING guide, and changelog baseline.
- GitHub issue routing config and repository support policy documentation.
- Live gateway integration test execution is now opt-in via `PAYTABS_RUN_LIVE_TESTS=1`.
- Added release guidance for handling large legacy formatting baselines via separate style-only PRs.

### Changed

- Consistent naming across factories and abstractions (requests factory, payloads factory, payment methods factory, `AbstractEndpoint`, `AbstractBrowserResult`, `AbstractTransactionResult`).
- Request constructors now accept a nullable `Profile`, with `RequestsFactory` updated accordingly.
- Payment request handling consolidated around a single configuration parameter set.
- Samples, tests, and ReadMe updated for the new `Paytabs` wrapper and logger initialization.

### Security

- Webhook documentation emphasizes fail-closed signature validation.
- Vulnerability reporting guidance now explicitly routes to the PayTabs Bug Bounty Program.
- Added explicit bug bounty reporting URL: https://ai.paytabs.com/en/paytabs-bug-bounty/

### Removed

- Deprecated classes superseded by the consolidated payment request configuration.
