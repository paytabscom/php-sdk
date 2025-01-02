<?php

namespace Response;

use Enums\ResponseStage;

interface ResponseInterface
{
    public function getResponseStage(): ResponseStage;

    public function getRaw(): string;
    public function getJson();
}
