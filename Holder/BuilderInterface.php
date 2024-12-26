<?php

namespace Holder;

interface BuilderInterface
{

    public function getPayload(): PayloadInterface;

    public function expectedResponses(): array;
    public function getResponseClass(): string;
}
