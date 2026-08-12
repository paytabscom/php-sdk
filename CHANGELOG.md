# Changelog

All notable changes to this project will be documented in this file.

The format is based on Keep a Changelog, and this project aims to follow Semantic Versioning.

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
