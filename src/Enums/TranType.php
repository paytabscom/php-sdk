<?php

namespace Enums;

enum TranType: string
{
    case Auth = 'auth';
    case Register = 'register';
    case Sale = 'sale';

    case AuthExt = 'authext';
    // Auth Extension is used to refresh the hold on the funds
    // Followup an Auth transaction

    case PaymentRequest = 'payment request';

    case Capture = 'capture';
    case Void = 'void';
    case Release = 'release';
    case Refund = 'refund';

    //

    /**
     * @throws ValueError If there is no matching case defined
     */
    public static function get(string $value): TranType
    {
        return TranType::from(strtolower($value));
    }

    // @todo
    public function isPaymentComplete(object $ipn_data): bool
    {
        if ($ipn_data) {
            $original_trx = @$ipn_data->previous_tran_ref;
            $tran_type = $ipn_data->tran_type;

            // Sale && previous_tran_ref
            if (isset($original_trx) && (TranType::get($tran_type) == TranType::Sale)) {
                return true;
            }

            // Or Expired
            $tran_status = @$ipn_data->payment_result->response_status;
            if ($tran_status === 'X') {
                return true;
            }
        }

        return false;
    }
}
