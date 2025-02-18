<?php

namespace Paytabs\Sdk\Holder\Parts;

class CardFilter extends AbstractPart
{
    private string $cardFilter;
    private string $cardFilterTitle;

    //

    public function __construct(
        string $cardFilter,
        string $cardFilterTitle
    ) {
        $this->cardFilter = $cardFilter;
        $this->cardFilterTitle = $cardFilterTitle;
    }

    public function build(): array
    {
        return [
            'card_filter' => $this->cardFilter,
            'card_filter_title' => $this->cardFilterTitle,
        ];
    }
}
