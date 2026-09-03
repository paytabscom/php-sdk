# Webhook Verification

- Version: `1.1.0`
- SDK version: >= `4.0.0`

Always verify webhook and browser callback signatures before processing payloads.

## Production vs sample behavior

- Production endpoints must reject invalid signatures (`403`) and stop processing.
- Every SDK sample that handles a callback (`IndexIpn.php`, `ResultCallback.php`, `ResultBrowser.php`) fails closed and returns `403` on an invalid signature.
- You can still add local debug logging, but only after signature validation has passed.

## Why this is required

PayTabs webhook endpoints are public URLs. You must reject requests with invalid signatures to avoid forged payment events.

## Three notification channels

PayTabs notifies you through three separate mechanisms. They are not
interchangeable:

| Channel | Configured | Transport | Fires |
| --- | --- | --- | --- |
| Browser return (`return`) | On the payment request | Browser redirect | **Once**, when the customer returns. Never re-sent. |
| Callback (`callback`) | On the payment request | Server-to-server | On any change to **that** transaction. Only if the request carried the URL. |
| IPN | **PayTabs merchant portal**, per profile | Server-to-server | On any change to **any** transaction on the profile. Only if configured. |

A transaction change is reported server-to-server whether it came from an API
call of yours, from a merchant acting in the PayTabs portal, or from a partner
network such as SADAD reporting that a customer paid at an agent.

The two are **independent, with no fallback between them**. If the request omits
`callback` *and* the profile has no IPN configured, **the update is lost** —
nothing errors, the transaction simply settles at the gateway while your system
never hears about it. Neither URL can be attached to a transaction after the
fact, so those updates are unrecoverable short of polling `payment/query`.

When both *are* set up, the merchant decides the overlap: an IPN config carries a
**With Callback** flag that either lets the IPN fire alongside `callback` or
skips it for requests that supplied one. So the same integration can receive one
notification or two depending on portal configuration.

Design consequences:

- **Do not build order state on the browser return.** It fires once and depends
  on the customer's session — a closed tab loses it, and PayTabs never sends it
  again. Use it to render a result page; let the server-to-server channels drive
  fulfilment.
- **Set `callback` on every request and configure the IPN.** They cover
  different scopes: `callback` only covers transactions your code created with
  that URL, while the profile-wide IPN also covers ones raised in the portal.
  With neither, updates are silently lost.
- **Do not assume how many deliveries you get.** One or two, depending on the
  IPN config's *With Callback* flag — so the handler must be idempotent, not
  merely tolerant of a known duplicate count.
- **One `cart_id` produces several notifications**, each with its own `tran_ref`.
  Deduplicate on `tran_ref`, never on `cart_id`.

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

## Bind the payload to an order you already have

A valid signature proves the payload came from PayTabs. It does not prove the
payload belongs to an order of yours, for the amount you expect.

```php
$order = $orders->findByReference($mapped->cart_id);

if (null === $order) {
    // In a storefront flow the order already exists, so an unknown cart_id is a
    // stale cart or a probe. Acknowledge and drop.
    http_response_code(200);
    exit;
}

if (0 !== strcasecmp($order->currency(), (string) $mapped->tran_currency)) {
    // Not necessarily an attack: a store can transact in another currency.
    $order->flagForManualReview();
    http_response_code(200);
    exit;
}

// Only an original payment is expected to match the order total. A follow-up
// reports the amount it moved, which for a partial refund is less by design.
$isOriginalPayment = false === $mapped->isFollowup()
    && !$mapped->isAgainstEarlierTransaction();

if ($isOriginalPayment && abs($order->total() - (float) $mapped->tran_total) >= 0.001) {
    $order->flagForManualReview();
    http_response_code(200);
    exit;
}
```

Three rules worth stating plainly:

- **In a storefront flow, do not create an order from a notification.** The order
  exists before the payment starts, so an unknown `cart_id` is not a sale.
  Respond `200` so the gateway stops retrying, and drop it.
- **The exception is a payment that starts outside your system** — an invoice or
  PayLink sent to a buyer directly. It is rare, but there the notification really
  can be the first you hear of the payment. Gate it tightly: accept only the
  integration and `tran_type` you expect, and match the amount against something
  you actually issued.
- **Only match the amount for an original payment.** For a follow-up, `tran_total`
  is the amount to *apply* — a partial refund reports the partial figure, so
  comparing it against the order total rejects every legitimate partial. Under
  **donation mode** the payer picks the amount, so range-check rather than match.

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
    // Not final: wait for a later notification, do not fulfil and do not cancel.
} elseif (true === $completed->isTransactionFailed()) {
    $order->markFailed();
} else {
    // null: no status reported. Query the transaction before acting.
}
```

`Browser` exposes the same trio for browser returns.

### "Pending" covers two different situations

`isTransactionPending()` is `true` for both `H` and `P`, but they need opposite
handling. Read `$completed->payment_result->tranStatus` to tell them apart:

```php
use Paytabs\Sdk\Enums\TranStatus;

$status = $completed->payment_result?->tranStatus;

if (TranStatus::OnHold === $status) {
    // 'H' — hold on reject. The transaction WAS authorized, but risk screening
    // stopped the capture. A sale in this state has become an auth: the funds
    // are held and nothing was taken.
    //
    // The first move must come from the PayTabs dashboard, on the merchant's own
    // liability — while held, a void sent to the API is refused with code 120.
    // The release arrives here as a Capture carrying previous_tran_ref, which may
    // be partial; further capture/release calls then work through the API.
    $order->flagForManualReview();
} elseif (TranStatus::Pending === $status) {
    // 'P' — the customer pays through another medium: SADAD, Aman, Fawry.
    // Settlement takes hours to days; if they never pay it ends as Expired.
    // This one is a polling / reconciliation case.
    //
    // On a deferred payment response_code is the buyer's agent reference, not a
    // status code — they cannot pay without it.
    $order->awaitOfflinePayment($completed->payment_result->response_code);
}
```

Neither state means paid. Do not fulfil, and do not cancel.

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
        // A capture/void/release only makes sense against an order you
        // authorised — ignore it otherwise rather than mutating an unrelated one.
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

### `isFollowup()` is necessary but not sufficient

It reads `tran_type`, and the gateway does not always report the follow-up type.
A **capture** or a **tokenised repeat charge** commonly arrives as
`tran_type: Sale` with a status of `A` or `X`, because what was performed *was* a
sale — against a stored authorisation or token. `isFollowup()` returns `false`
for those.

The dependable signal is `previous_tran_ref`: when present, this transaction was
performed against an earlier one.

```php
<?php

$isAgainstAnEarlierTransaction = true === $completed->isFollowup()
    || null !== $completed->previous_tran_ref;

if ($isAgainstAnEarlierTransaction) {
    // Reconcile against $completed->previous_tran_ref, do not treat as a new sale.
    return;
}
```

`isAgainstEarlierTransaction()` wraps that check:

```php
if (true === $completed->isFollowup() || $completed->isAgainstEarlierTransaction()) {
    // Reconcile against $completed->previous_tran_ref, not as a new sale.
    return;
}
```

Deduplicating on `tran_ref` (below) covers the same ground from the other
direction: an incoming `Sale`/`A` for a `cart_id` you already marked paid is far
more likely to be a capture or repeat charge than a second payment.

### Resolving a pending offline payment

Offline methods — SADAD, Aman, Fawry — produce **two transactions**. The first is
`tran_type: Payment Request` with `P`: the customer has a reference number and
has paid nothing yet. It is **not** resolved by a status change on itself, but by
a **second server-to-server notification carrying a new `tran_ref`**, and there
are exactly two outcomes:

| Second notification | Meaning |
| --- | --- |
| `tran_type: Sale`, `A`, **with** `previous_tran_ref` | The customer paid at the agent. This is the payment. |
| `response_status: X` | The window lapsed; they never paid. Terminal. |

The paying transaction arrives as `A` (Authorised) and is ready for settlement —
the cash was already collected at the agent, so there is no capture step to wait
for.

This two-transaction shape belongs to **deferred methods only**. Synchronous ones
— cards, InstaPay, most wallets — settle within the customer's session and
produce a single transaction ending `A`, `D` or `X`. Their expiry therefore
carries no `previous_tran_ref`, which is why `isDeferredPaymentResolved()` treats
any `X` as terminal rather than requiring an earlier reference.

`isDeferredPaymentResolved()` reports either:

```php
if ($completed->isDeferredPaymentResolved()) {
    if (true === $completed->isTransactionSuccessful()) {
        $order->markPaid();          // the offline payment landed
    } else {
        $order->releaseStock();      // expired — normal attrition, not an error
    }
}
```

The trigger comes from the partner network, so this arrives on your
server-to-server handler — the browser return fired once at checkout and is never
re-sent.

Note the contrast with `H`: a pending sale resolves on its own and notifies you
either way. A held (`H`) sale waits for a merchant decision — and if none comes,
the authorization is dropped by the acquirer after ~15–30 days with no
notification, so track those yourself rather than waiting to be told.

### A pending refund is not a failed refund

`response_status: P` on a refund is a normal, expected outcome — the refund is
**awaiting approval by the PayTabs team**, a manual review step rather than
automated processing. `isTransactionPending()` returns `true`.

Do **not** re-submit it. There is no idempotency key, so a resubmission can
refund twice. Record the refund's own `tran_ref` and query that instead.

## Handling repeated callbacks

PayTabs may deliver the same IPN more than once, and a captured callback can be
replayed. The SDK does **not** deduplicate for you, and the gateway does not
offer an idempotency key.

Distinguish the two cases: a **redelivery** repeats the same `tran_ref` and should
be ignored, whereas a **later notification** for the same cart — a resolved
pending sale, a capture, a refund — carries a *new* `tran_ref` and must be
processed. Keying on `cart_id` would discard the second kind.

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
