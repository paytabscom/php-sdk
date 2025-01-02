<?php

namespace Holder;

interface BuilderInterface
{

    public function getPayload(): PayloadInterface;

    public function getResponseClass(): string;
}
