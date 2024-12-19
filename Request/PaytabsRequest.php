<?php

namespace Request;

class PaytabsRequest extends AbstractRequest
{
    public function getHeaders(): array
    {
        return $this->environment->getHeaders();
    }
}
