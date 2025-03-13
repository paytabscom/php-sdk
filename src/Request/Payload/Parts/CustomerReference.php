<?php

namespace Paytabs\Sdk\Request\Payload\Parts;

class CustomerReference extends AbstractPart
{
    public string $customerReference;

    public function __construct(
        string $customerReference
    ) {
        $this->customerReference = $customerReference;
    }

    public function build(): array
    {
        return [
            'customer_ref' => $this->customerReference,
        ];
    }
}
