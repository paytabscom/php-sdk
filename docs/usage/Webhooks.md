# Webhook Verification

- Version: `1.0.0`
- SDK version: `3.0.0`

Always verify webhook and browser callback signatures before processing payloads.

## Production vs sample behavior

- Production endpoints must reject invalid signatures (`400`) and stop processing.
- SDK sample scripts are debugging-oriented: they show signature check results and mapped payload content to help integration troubleshooting.
- Do not copy sample debug behavior into production callback/IPN endpoints.

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

$response = BrowserAsPost::init(;
$response->setProfile($profile);

if (!$response->isGenuine()) {
    http_response_code(400);
    exit('Invalid signature');
}

$mapped = $response->getPayload()->getMapped();
```

`BrowserAsGet` is supported, but `BrowserAsPost` is the recommended default.

## Debug-oriented sample flow

The files [Samples/index_ipn.php](Samples/index_ipn.php) and [Samples/ResultCallback.php](Samples/ResultCallback.php) intentionally continue execution even when signature validation fails, then log:

- `isGenuine` result
- Full mapped response payload

This is useful during local integration/debugging. For production, use the strict flow above and stop on invalid signatures.

### Why `localParams` matters

Use `localParams` only when your app injects extra local query/body fields (for example `mode`, `result`, `get`) that are not part of the PG-signed callback data.
Those fields should be excluded from hash input before signature comparison.

For simple browser POST callbacks where the payload comes directly from PG and no local fields are mixed into the callback payload, `BrowserAsPost::init()` is enough.

## Operational checklist

- Return `400` on invalid signature.
- Never trust client-side status without signature validation.
- Do not log full signatures, full tokens, PAN, or CVV.
- Add idempotency handling using transaction reference.
