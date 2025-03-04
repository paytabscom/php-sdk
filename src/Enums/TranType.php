<?php

namespace Paytabs\Sdk\Enums;

class TranType extends EnumString
{
    public const Auth = 'auth';
    public const Register = 'register';
    public const Sale = 'sale';

    public const AuthExt = 'authext';
    // Auth Extension is used to refresh the hold on the funds
    // Followup an Auth transaction

    public const PaymentRequest = 'payment request';

    public const Capture = 'capture';
    public const Void = 'void';
    public const Release = 'release';
    public const Refund = 'refund';

    public const UnKnown = 'unknown';

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

    public static function UnKnown()
    {
        return new self(self::UnKnown);
    }

    /**
     * @throws ValueError If there is no matching case defined
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

        return in_array($this->value, $recurring);
    }

    /** @todo */
    public function isPaymentComplete(object $ipn_data): bool
    {
        if ($ipn_data) {
            $original_trx = @$ipn_data->previous_tran_ref;
            $tran_type = $ipn_data->tran_type;

            // Sale && previous_tran_ref
            if (isset($original_trx) && (TranType::Sale === TranType::get($tran_type))) {
                return true;
            }

            // Or Expired
            $tran_status = @$ipn_data->payment_result->response_status;
            if ('X' === $tran_status) {
                return true;
            }
        }

        return false;
    }
}
