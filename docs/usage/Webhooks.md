# Webhook Verification

- Version: `1.1.0`
- SDK version: >= `3.0.0`

Always verify webhook and browser callback signatures before processing payloads.

## Production vs sample behavior

- Production endpoints must reject invalid signatures (`403`) and stop processing.
- Every SDK sample that handles a callback (`IndexIpn.php`, `ResultCallback.php`, `ResultBrowser.php`) fails closed and returns `403` on an invalid signature.
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
    http_response_code(403);
    exit('Invalid signature');
}

$mapped = $callback->getPayload()->getMapped();

// Process business logic only after the signature passes.
// See "Handling repeated callbacks" below before marking an order paid.
```

### Fail-closed alternative

`isGenuine()` returns a boolean, so a forgotten `if` silently processes a forged
payload. `assertGenuine()` throws instead, which makes that mistake impossible:

```php
<?php

use Paytabs\Sdk\Exceptions\InvalidSignatureException;
use Paytabs\Sdk\Response\Responses\Webhook\TransactionResult\Callback;

try {
    $mapped = Callback::init()
        ->setProfile($profile)
        ->assertGenuine()
        ->getPayload()
        ->getMapped()
    ;
} catch (InvalidSignatureException $e) {
    http_response_code(403);
    exit('Invalid signature');
}
```

The exception message carries only a non-reversible hint of the server key, never
the key itself, so it is safe to log.

## Browser return example

Production-safe flow:

```php
<?php

use Paytabs\Sdk\Response\Responses\Webhook\TransactionResult\BrowserAsPost;

$response = BrowserAsPost::init();
$response->setProfile($profile);

if (!$response->isGenuine()) {
    http_response_code(403);
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

## Reading the transaction outcome

The status predicates are **three-state** and return `?bool`:

| Return | Meaning |
| --- | --- |
| `true` | The status is known and matches |
| `false` | The status is known and does not match |
| `null` | No status was reported, so the outcome is unknown |

A transaction is also neither successful nor failed while it is pending or on
hold (`P` / `H`). So both of these are unsafe:

```php
// WRONG: true for a decline, for pending, and for an unknown status.
if (!$completed->isTransactionFailed()) {
    $order->markPaid();
}

// WRONG: null is falsy, so an unknown status takes the "declined" branch.
if ($browser->isTransactionSuccessful()) { /* ... */ } else { $order->cancel(); }
```

Test for `true` explicitly and handle the remaining states apart:

```php
if (true === $completed->isTransactionSuccessful()) {
    $order->markPaid();
} elseif (true === $completed->isTransactionPending()) {
    // Not final: wait for a later IPN, do not fulfil and do not cancel.
} elseif (true === $completed->isTransactionFailed()) {
    $order->markFailed();
} else {
    // null: no status reported. Query the transaction before acting.
}
```

`Browser` exposes the same trio for browser returns.

## Refunds, voids and captures arrive on the same endpoint

PayTabs sends an IPN for **every** transaction against a cart, not just the
original payment. A completed refund arrives with the same shape as a sale:

```json
{
  "tran_type": "refund",
  "cart_id": "cart-1001",
  "payment_result": { "response_status": "A", "response_message": "Authorised" }
}
```

**The predicates describe the transaction, not the order.** So a completed refund
reports `isTransactionSuccessful() === true` — correct, the refund did succeed —
and the block above would re-mark the order paid at the moment it was refunded.

Gate on `isFollowup()` before treating a success as payment:

```php
<?php

if (true === $completed->isFollowup()) {
    // refund, void, release, capture or auth extension.
    if (true === $completed->isTransactionSuccessful()) {
        // Inspect $completed->tranType to decide which, then adjust the order.
    }

    return;
}

// Only an original payment reaches here.
if (true === $completed->isTransactionSuccessful()) {
    $order->markPaid();
}
```

`isFollowup()` returns `null` when the gateway reported no transaction type —
treat that as "unknown" and query the transaction rather than assuming a sale.

## Handling repeated callbacks

PayTabs may deliver the same IPN more than once, and a captured callback can be
replayed. The SDK does **not** deduplicate for you, and the gateway does not
offer an idempotency key.

`cart_id` is your own reference and the gateway does not enforce it as unique, so
it cannot serve as a deduplication key on its own. Use `tran_ref`, which is
assigned by the gateway and identifies one transaction:

```php
<?php

$tranRef = $callback->getTranRef();

// Record tranRef under a UNIQUE constraint and ignore a repeat delivery.
if (!$orders->markCallbackSeen($tranRef)) {
    http_response_code(200); // Already handled; acknowledge so PayTabs stops retrying.
    exit;
}
```

Acknowledge duplicates with `200`. Returning an error makes the gateway retry.

## Debug-oriented sample flow

The files [../../Samples/IndexIpn.php](../../Samples/IndexIpn.php), [../../Samples/ResultCallback.php](../../Samples/ResultCallback.php) and [../../Samples/ResultBrowser.php](../../Samples/ResultBrowser.php) return `403` when signature validation fails.
After the signature is valid, you can log limited mapped fields for local troubleshooting.

For production, keep logs redacted and stop processing on invalid signatures.

### Why `localParams` matters

Use `localParams` only when your app injects extra local query/body fields (for example `mode`, `result`, `get`) that are not part of the PG-signed callback data.
Those fields should be excluded from hash input before signature comparison.

For simple browser POST callbacks where the payload comes directly from PG and no local fields are mixed into the callback payload, `BrowserAsPost::init()` is enough.

## Operational checklist

- Return `403` on invalid signature.
- Never trust client-side status without signature validation.
- Do not log full signatures, full tokens, PAN, or CVV.
- Deduplicate repeated deliveries on `tran_ref`, and acknowledge duplicates with `200`.
- Treat a transaction as paid only when the status is successful; `!isTransactionFailed()` also covers pending and unknown.
- Check `isFollowup()` before marking an order paid — a successful refund is a successful transaction.
