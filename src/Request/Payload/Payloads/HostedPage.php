<?php

declare(strict_types=1);

namespace Paytabs\Sdk\Request\Payload\Payloads;

use Paytabs\Sdk\Request\Payload\Parts\CardDiscounts;
use Paytabs\Sdk\Request\Payload\Parts\CardFilter;
use Paytabs\Sdk\Request\Payload\Parts\Donation;
use Paytabs\Sdk\Request\Payload\Parts\Framed;

class HostedPage extends PrimaryPayment
{
    public function buildFramedObj(Framed $framed)
    {
        $this->product->buildBody($framed);

        return $this;
    }

    public function buildCardFilter(string $cardFilter, string $cardFilterTitle)
    {
        $this->product->buildBody(
            new CardFilter($cardFilter, $cardFilterTitle)
        );

        return $this;
    }

    public function buildDonationMode(bool $donationMode, float $cartMin, float $cartMax)
    {
        $this->product->buildBody(
            new Donation($donationMode, $cartMin, $cartMax)
        );

        return $this;
    }

    public function buildCardDiscounts(CardDiscounts $cardDiscounts)
    {
        $this->product->buildBody($cardDiscounts);

        return $this;
    }
}
