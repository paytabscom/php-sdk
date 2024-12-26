<?php

namespace Response;

use Enums\ResponseType;

interface ResponseInterface
{
    public function responseType(): ResponseType;
}
