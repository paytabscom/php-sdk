<?php

namespace Response;

interface PayloadInterface
{
    public function init(): self;

    public function fromJson($jsonResponse): self;
}
