<?php

namespace Response;

use Enums\ResponseStage;

interface ResponseInterface
{
    public function responseStage(): ResponseStage;

    public function getRaw(): string;
    public function getJson();
}
