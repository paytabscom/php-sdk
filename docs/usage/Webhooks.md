# Webhook Verification

- Version: `1.0.0`
- SDK version: `3.0.0`

Always verify webhook and browser callback signatures before processing payloads.

## Production vs sample behavior

- Production endpoints must reject invalid signatures (`400`) and stop processing.
- SDK sample scripts now follow fail-closed behavior by default and return `400` on invalid signatures.
- You can still add local debug logging, but only after signature validation has passed.

## Why this is required

PayTabs webhook endpoints are public URLs. You must reject requests with invalid signatures to avoid forged payment events.

## Callback (IPN) example

Production-safe flow:

```php
<?php

use Paytabs\Sdk\Response\Responses\Webhook\TransactionResult\Callback;

$callback = Callback::init();
$callback->setProfile($profile);

if (!$callback->isGenuine()) {
    http_response_code(400);
    exit('Invalid signature');
}

$mapped = $callback->getPayload()->getMapped();

// Use your own idempotency key strategy with tranRef.
// Process business logic only after signature passes.
```

## Browser return example

Production-safe flow:

```php
<?php

use Paytabs\Sdk\Response\Responses\Webhook\TransactionResult\BrowserAsPost;

$response = BrowserAsPost::init();
$response->setProfile($profile);

if (!$response->isGenuine()) {
    http_response_code(400);
    exit('Invalid signature');
}

$mapped = $response->getPayload()->getMapped();
```

`BrowserAsGet` is supported, but `BrowserAsPost` is the recommended default.

## Strict payload mapping mode

Webhook/browser mapping is tolerant by default. Unknown transaction enum values are mapped to `Unknown` and logged.

If your integration requires fail-fast behavior, enable strict mode before mapping:

```php
<?php

use Paytabs\Sdk\Exceptions\UnknownResponseValueException;
use Paytabs\Sdk\Response\Payload\Payloads\Paytabs as ResponsePayload;

ResponsePayload::setStrictMode(true);

try {
    $mapped = $response->getPayload()->getMapped();
} catch (UnknownResponseValueException $e) {
    http_response_code(422);
    exit('Unknown response enum value');
}

ResponsePayload::setStrictMode(false);
```

## Debug-oriented sample flow

The files [../../Samples/IndexIpn.php](../../Samples/IndexIpn.php) and [../../Samples/ResultCallback.php](../../Samples/ResultCallback.php) return `400` when signature validation fails.
After the signature is valid, you can log limited mapped fields for local troubleshooting:

- `isGenuine` result
- Mapped response payload fields needed for debugging

For production, keep logs redacted and stop processing on invalid signatures.

### Why `localParams` matters

Use `localParams` only when your app injects extra local query/body fields (for example `mode`, `result`, `get`) that are not part of the PG-signed callback data.
Those fields should be excluded from hash input before signature comparison.

For simple browser POST callbacks where the payload comes directly from PG and no local fields are mixed into the callback payload, `BrowserAsPost::init()` is enough.

## Operational checklist

- Return `400` on invalid signature.
- Never trust client-side status without signature validation.
- Do not log full signatures, full tokens, PAN, or CVV.
- Add idempotency handling using transaction reference.
