<?php

namespace Paytabs\Sdk\Holder\Parts;


class ConfigId extends AbstractPart
{
    public int $configId;


    public function __construct(
        int $configId
    ) {
        $this->configId = $configId;
    }


    public function build(): array
    {
        return [
            'config_id' => $this->configId,
        ];
    }

    
}