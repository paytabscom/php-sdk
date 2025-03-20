<?php

use Paytabs\Sdk\Profile\Endpoints\Uae;

function readConfigs(): array
{
    return [
        'endpoint' => Uae::getInstance(),
        'profile_id' => 47170,
        'server_key' => 'SRJNLKK2Z2-xxxx-MGMGGNW9JZ',

        'base_url' => 'https://9f0d-5-11-2-186.ngrok-free.app',

        'trx_ref' => 'TST2502902213426',

        'token' => '2C4654BD67Axxxx93FF628674B0',
        'token_enhanced' => '2C4655BE67A3Exxxx3F465837EBB',

        'invoice_id' => 1234,

        'config_id' => 2188,

        'currency' => 'AED',

        'payment_token' => '',
    ];
}
