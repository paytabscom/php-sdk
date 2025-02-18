<?php

namespace Paytabs\Sdk\Holder\Builders;

use Paytabs\Sdk\Enums\TranClass;
use Paytabs\Sdk\Enums\TranType;
use Paytabs\Sdk\Holder\Parts\Cart;
use Paytabs\Sdk\Holder\Parts\PluginInfo;
use Paytabs\Sdk\Holder\Parts\Transaction;
use Paytabs\Sdk\Holder\Parts\Urls;

class PaymentRequest extends AbstractHolder
{
    public function buildTransaction(TranType $tran_type, TranClass $tran_class = TranClass::Ecom)
    {
        $this->product->buildBody(
            new Transaction($tran_type, $tran_class)
        );

        return $this;
    }

    public function buildCart(string $cart_id, string $currency, float $amount, string $cart_description)
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

    public function buildURLs(?string $return_url, ?string $callback_url, bool $returnUsingGet = false)
    {
        $urls = new Urls(
            $return_url,
            $callback_url
        );

        if ($returnUsingGet) {
            $urls->setReturnUsingGet(true);
        }

        $this->product->buildBody(
            $urls
        );

        return $this;
    }

    public function buildPluginInfo(?string $platformName, ?string $platformVersion, ?string $pluginVersion)
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
