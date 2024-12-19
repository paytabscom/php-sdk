<?php

namespace Holder\Builders;

use Enums\TranClass;
use Enums\TranType;
use Holder\Parts\Cart;
use Holder\Parts\PluginInfo;
use Holder\Parts\Transaction;
use Holder\Parts\Urls;

class PaymentRequest extends AbstractHolder
{
    public function setTransaction(TranType $tran_type, TranClass $tran_class = TranClass::Ecom)
    {
        $this->product->buildBody(
            new Transaction($tran_type, $tran_class)
        );

        return $this;
    }

    public function setCart(string $cart_id, string $currency, float $amount, string $cart_description)
    {
        $this->product->buildBody(
            new Cart(
                $cart_id,
                $currency,
                $amount,
                $cart_description,
            )
        );

        return $this;
    }

    public function setURLs(?string $return_url, ?string $callback_url)
    {
        $this->product->buildBody(
            new Urls(
                $return_url,
                $callback_url
            )
        );

        return $this;
    }

    public function setPluginInfo(?string $platformName, ?string $platformVersion, ?string $pluginVersion)
    {
        $this->product->buildBody(
            new PluginInfo(
                $platformName,
                $platformVersion,
                $pluginVersion
            )
        );

        return $this;
    }
}
