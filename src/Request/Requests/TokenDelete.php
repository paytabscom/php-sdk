<?php

declare(strict_types=1);

namespace Paytabs\Sdk\Request\Requests;

class TokenDelete extends TokenQuery
{
    protected string $path = 'payment/token/delete';
}
