# PayTabs PHP SDK (v3)

Official PHP SDK for integrating with PayTabs Payment Gateway.

## Requirements

- PHP `^8.1`
- cURL extension enabled

The SDK targets PHP 8.1 so it can be embedded in e-commerce platform plugins that
still run on older merchant hosting. Contributing to the SDK requires PHP 8.2 or
newer — see [CONTRIBUTING.md](CONTRIBUTING.md).

## Before you start

You need a PayTabs merchant account. From the merchant dashboard, take:

- **Profile ID** and **Server Key** — under *Developers → Key management*.
- Your **region**, which decides the endpoint. Credentials are region-scoped: a
  key issued for KSA will not authenticate against the UAE endpoint. Pick the
  matching helper (`createKsaProfile`, `createUaeProfile`, …) or pass an endpoint
  explicitly to `ProfilesFactory::createProfile()`.

Use a test/sandbox profile while integrating, and keep credentials in
environment variables rather than in the repository.

## Install

```bash
composer require paytabs/php-sdk:^3.0
```

## Quick Start

```php
<?php

use Paytabs\Sdk\Enums\TranClass;
use Paytabs\Sdk\Enums\TranType;
use Paytabs\Sdk\Exceptions\PaytabsExceptionInterface;
use Paytabs\Sdk\Paytabs;
use Paytabs\Sdk\PaytabsLogger;
use Paytabs\Sdk\Profile\ProfilesFactory;
use Paytabs\Sdk\Request\Payload\PayloadsFactory;
use Paytabs\Sdk\Request\RequestsFactory;

$profile = ProfilesFactory::createUaeProfile(
	(int) getenv('PAYTABS_PROFILE_ID'),
	(string) getenv('PAYTABS_SERVER_KEY')
);

// OR create any Logger implements LoggerInterface
$logger = PaytabsLogger::getInstance()->logger;

$payload = PayloadsFactory::createHostedPage();
$payload
    ->buildTransaction(TranType::Sale, TranClass::Ecom)
    ->buildCart('order-1001', 'AED', 100.00, 'Order 1001')
    // Where the shopper returns to, and where PayTabs posts the Callback.
    ->buildURLs('https://example.com/return', 'https://example.com/ipn')
    ->buildHideShipping(true);

$request = RequestsFactory::createPaymentRequest($payload);

$paytabs = Paytabs::getInstance($profile, null, $logger);
$paytabs->setRequest($request);

try {
    $response = $paytabs->submit();
} catch (PaytabsExceptionInterface $e) {
    // Every SDK exception implements this interface.
    // Never retry a payment request blindly: there is no idempotency key, so a
    // retry after a timeout can charge the shopper twice. Query first instead.
    error_log('PayTabs request failed: '.$e->getMessage());
    exit('Payment could not be started.');
}

if ($response->isFailure()) {
    $failure = $response->getFailure();
    echo $failure->code.' - '.$failure->message;
    exit;
}

if ($response->isRedirect()) {
    // Hosted payment page: send the shopper to PayTabs to pay.
    $redirect = $response->getRedirect();
    header('Location: '.$redirect->redirect_url);
    exit;
}

// Completed without a redirect (for example a tokenised repeat payment).
$completed = $response->getPayloadMapped();

// These return ?bool, so compare against true: null means "no status reported",
// and a pending transaction is neither successful nor failed.
if (true === $completed->isPaymentSuccessful()) {
    // Paid. Fulfil the order.
} elseif (true === $completed->isPaymentPending()) {
    // Not final yet — wait for the IPN, do not fulfil and do not cancel.
} elseif (true === $completed->isPaymentFailed()) {
    // Declined or failed.
} else {
    // Unknown: query the transaction before acting on it.
}
```

The payment is not finished when the shopper is redirected. Confirm the outcome
in your return and IPN handlers, and verify the signature there before trusting
anything: [docs/usage/Webhooks.md](docs/usage/Webhooks.md).

## Error Handling

Every exception the SDK throws implements `PaytabsExceptionInterface`, so one
catch block covers all of them:

```php
use Paytabs\Sdk\Exceptions\PaytabsExceptionInterface;

try {
    $response = $paytabs->submit();
} catch (PaytabsExceptionInterface $e) {
    // Anything raised by the SDK.
}
```

Catch a specific class when you need to react differently:

| Exception | Raised when | Extends |
| --- | --- | --- |
| `HttpRequestException` | Transport failure (DNS, TLS, timeout), or a non-2xx response with an empty or non-JSON body | `\RuntimeException` |
| `GatewayFailureException` | The gateway refused the request and returned a `code`/`message` — authentication failure, invalid currency, duplicate request | `\RuntimeException` |
| `InvalidConfigurationException` | Required configuration is missing, such as a profile | `\RuntimeException` |
| `InvalidSignatureException` | `assertGenuine()` rejected a webhook or browser callback | `\RuntimeException` |
| `MissingResponseFieldException` | The gateway omitted a field the SDK needs | `\RuntimeException` |
| `UnknownResponseValueException` | Strict mapping mode hit an unrecognised enum value | `\RuntimeException` |
| `EndpointNotFoundException` | An unknown endpoint code was requested | `\InvalidArgumentException` |
| `UnexpectedResponseStageException` | A stage accessor was called out of turn, e.g. `getFailure()` without `isFailure()` | `\LogicException` |
| `UnsupportedPayloadOperationException` | A builder was asked for something its transaction type forbids, e.g. payment methods on an Own Form | `\BadMethodCallException` |

Because each class still extends the same SPL type as before, existing
`catch (\RuntimeException)` code keeps working unchanged.

A declined payment is **not** an exception. It arrives as a normal response:
check `$response->isFailure()`, then `getFailure()->code` and `->message`.

> **Retries.** PayTabs has no idempotency-key header, and `cart_id` is your own
> reference which the gateway does not enforce as unique. Retrying a timed-out
> payment request can therefore charge the shopper twice, so the SDK never
> retries automatically. After a timeout, query the transaction before resending.

## Security Notes

- Do not log card data, CVV, full tokens, or full webhook signatures.
- Store credentials in environment variables, not in repository files.
- Always verify webhook signatures and reject invalid requests.

See webhook verification guide: [docs/usage/Webhooks.md](docs/usage/Webhooks.md)

## Logging Configuration

The SDK provides a default file logger helper:

```php
use Paytabs\Sdk\PaytabsLogger;

$fileLogger = PaytabsLogger::getInstance()->logger;
$browserLogger = PaytabsLogger::getInstance(null, true)->logger;
```

You can also inject any custom `Psr\Log\LoggerInterface` instance via `Http::setLogger()` or `Paytabs::setLogger()`.

**Default log location.** With no argument, `PaytabsLogger` writes a daily file to
`/var/log/paytabs-sdk/`. Pass an explicit path to change it:

```php
$logger = new Paytabs\Sdk\Logger\FileLog('PayTabs', PaytabsLogger::getLogFile('/var/log/my-app/'));
```

Log files are created `0600` and the directory `0700`, because gateway payloads
pass through them.

**Redaction.** All SDK loggers strip cardholder data and credentials before
writing: PANs are masked to first-6/last-4, and CVV, `Authorization` headers and
key-like fields are removed — including inside an already-serialised JSON body.
If you inject your own logger it receives the same redacted context. Do not
re-serialise the raw request payload yourself and log it through a different
channel.

**`BrowserLog` is for local debugging only.** It writes into the HTTP response;
never enable it in production.

**Diagnostics during response mapping** (for example an unrecognised transaction
status) go through a separate logger that defaults to `error_log()`, so a full
disk or an unwritable path can never fail a payment:

```php
use Paytabs\Sdk\Response\Payload\Payloads\Paytabs as ResponsePayload;

ResponsePayload::setLogger($myPsrLogger);
```

## Strict Response Mapping Mode

By default, response payload mapping is tolerant. Unknown enum values (for example unseen transaction status/class/type) are mapped to `Unknown` and logged.

You can enable strict mode to throw a dedicated exception instead:

```php
use Paytabs\Sdk\Exceptions\UnknownResponseValueException;
use Paytabs\Sdk\Response\Payload\Payloads\Paytabs as ResponsePayload;

ResponsePayload::setStrictMode(true);

try {
	$mapped = $response->getPayload()->getMapped();
} catch (UnknownResponseValueException $e) {
	// Unknown transaction type/class/status encountered.
}

// Optional: restore default tolerant mode.
ResponsePayload::setStrictMode(false);
```

## Documentation

[![Ask DeepWiki](https://deepwiki.com/badge.svg)](https://deepwiki.com/paytabscom/php-sdk)
- Architecture: [ARCHITECTURE.md](ARCHITECTURE.md)
- Diagrams: [docs/diagrams](docs/diagrams)
- Payment Request guide: [docs/usage/PaymentRequest.md](docs/usage/PaymentRequest.md)
- Invoices guide: [docs/usage/Invoices.md](docs/usage/Invoices.md)
- Webhooks guide: [docs/usage/Webhooks.md](docs/usage/Webhooks.md)
- Samples: [Samples](Samples)

## Project Governance

- License: [LICENSE](LICENSE)
- Security policy: [SECURITY.md](SECURITY.md)
- Contribution guide: [CONTRIBUTING.md](CONTRIBUTING.md)
- Changelog: [CHANGELOG.md](CHANGELOG.md)
- Release checklist: [docs/release-checklist.md](docs/release-checklist.md)
- Support policy: [SUPPORT.md](SUPPORT.md)

## Samples Setup

1. Copy `Samples/.env.sample` to `Samples/.env`.
2. Replace placeholder values with **sandbox** credentials.
3. Run samples locally and expose callback URL when needed.

> **Keep the working copy out of your web docroot.**
> `Samples/.env` holds a profile ID and server key in plaintext and is read with
> `parse_ini_file()`. If the checkout sits under a docroot (for example
> `/var/www/html/…`), a request to `…/Samples/.env` is served as plaintext by any
> server without a dotfile deny rule — no PHP execution required. Clone outside
> the docroot, or deny dotfiles:
>
> ```apache
> # Apache
> <FilesMatch "^\.">
>     Require all denied
> </FilesMatch>
> ```
>
> ```nginx
> # nginx
> location ~ /\. { deny all; }
> ```
>
> `Samples/.env` is gitignored and must never be committed. If a real key has
> been exposed, rotate it in the PayTabs merchant dashboard.

## Development Commands

```bash
composer lint
composer test
```

Live gateway tests are opt-in only:

```bash
PAYTABS_RUN_LIVE_TESTS=1 composer test
```

## Versioning

This SDK follows semantic versioning. Breaking changes are introduced only in major versions.

## Support

For integration support, use PayTabs official support channels.
For security vulnerabilities, report via the [PayTabs Bug Bounty Program](https://ai.paytabs.com/en/paytabs-bug-bounty/) and follow [SECURITY.md](SECURITY.md).
Public issues are intended for reproducible SDK bugs and enhancement requests.
