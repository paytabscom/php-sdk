<?php

namespace Paytabs\Sdk\Enums;

class TranType extends EnumString
{
    const Auth = 'auth';
    const Register = 'register';
    const Sale = 'sale';

    const AuthExt = 'authext';
    // Auth Extension is used to refresh the hold on the funds
    // Followup an Auth transaction

    const PaymentRequest = 'payment request';

    const Capture = 'capture';
    const Void = 'void';
    const Release = 'release';
    const Refund = 'refund';

    //

    public static function Auth()
    {
        return new self(self::Auth);
    }

    public static function Register()
    {
        return new self(self::Register);
    }

    public static function Sale()
    {
        return new self(self::Sale);
    }

    public static function AuthExt()
    {
        return new self(self::AuthExt);
    }

    public static function PaymentRequest()
    {
        return new self(self::PaymentRequest);
    }

    public static function Capture()
    {
        return new self(self::Capture);
    }

    public static function Void()
    {
        return new self(self::Void);
    }

    public static function Release()
    {
        return new self(self::Release);
    }

    public static function Refund()
    {
        return new self(self::Refund);
    }

    //

    /**
     * @throws ValueError If there is no matching case defined
     */
    public static function get(string $value): TranType
    {
        return TranType::from(strtolower($value));
    }

    /** @todo */
    public function isPaymentComplete(object $ipn_data): bool
    {
        if ($ipn_data) {
            $original_trx = @$ipn_data->previous_tran_ref;
            $tran_type = $ipn_data->tran_type;

            // Sale && previous_tran_ref
            if (isset($original_trx) && (TranType::get($tran_type) === TranType::Sale)) {
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
