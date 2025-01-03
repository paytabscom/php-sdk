<?php

namespace Request\Requests;


class TokenDelete extends TokenQuery
{
    protected string $path = 'payment/token/delete';
}
