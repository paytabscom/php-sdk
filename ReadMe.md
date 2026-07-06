# PayTabs PHP SDK (v3)

Official PHP SDK for integrating with PayTabs Payment Gateway.

## Requirements

- PHP `^8.1`
- cURL extension enabled

## Install

```bash
composer require paytabs/php-sdk:^3.0
```

## Quick Start

```php
<?php

use Paytabs\Sdk\Paytabs;
use Paytabs\Sdk\PaytabsLogger;
use Paytabs\Sdk\Profile\ProfilesFactory;
use Paytabs\Sdk\Request\Payload\PayloadsFactory;
use Paytabs\Sdk\Request\RequestsFactory;
use Paytabs\Sdk\Enums\TranClass;
use Paytabs\Sdk\Enums\TranType;

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
	->buildHideShipping(true);

$request = RequestsFactory::createPaymentRequest($payload);

$paytabs = Paytabs::getInstance($profile, null, $logger);

try {
	$response = $paytabs->submit();
} catch (\Paytabs\Sdk\Exceptions\HttpRequestException $e) {
	// Transport failures are raised as exceptions.
	throw $e;
}

if ($response->isFailure()) {
	$failure = $response->getFailure();
	echo $failure->code.' - '.$failure->message;
	exit;
}

if ($response->isRedirect()) {
	$redirect = $response->getRedirect();
	header('Location: '.$redirect->redirect_url);
	exit;
}
```

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
2. Replace placeholder values with sandbox credentials.
3. Run samples locally and expose callback URL when needed.

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
