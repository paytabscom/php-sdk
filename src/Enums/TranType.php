<?php

declare(strict_types=1);

namespace Paytabs\Sdk\Enums;

enum TranType: string
{
    case Auth = 'auth';

    /**
     * Card verification, normally to tokenise it: the gateway authorises then
     * voids, so nothing is collected, and the hosted page shows "Verify Card"
     * instead of "Pay Now". Never treat a successful `register` as a payment.
     */
    case Register = 'register';

    case Sale = 'sale';

    /**
     * Refreshes the hold on the funds, as a follow-up to an Auth. Needed because
     * the acquirer drops an uncaptured authorization after roughly 15-30 days.
     */
    case AuthExt = 'authext';

    /**
     * The deferred-payment placeholder holding the reference number the buyer
     * takes to an agent (Aman, SADAD, Fawry). Reports `P`; nothing is collected
     * until a separate `Sale` arrives against it.
     */
    case PaymentRequest = 'payment request';

    case Capture = 'capture';
    case Void = 'void';
    case Release = 'release';
    case Refund = 'refund';

    case Unknown = 'unknown';

    /**
     * @throws \ValueError If there is no matching case defined
     */
    public static function get(string $value): TranType
    {
        return TranType::from(strtolower($value));
    }

    public function supportRecurring(): bool
    {
        $recurring = [
            TranType::Auth,
            TranType::Sale,
        ];

        return \in_array($this, $recurring, true);
    }

    public function isFollowup(): bool
    {
        $followup = [
            TranType::AuthExt,
            TranType::Capture,
            TranType::Void,
            TranType::Release,
            TranType::Refund,
        ];

        return \in_array($this, $followup, true);
    }

}
