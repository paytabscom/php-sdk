<?php

function placeholders(array $args, string $className): array
{
    return [
        '__ClassName__' => $className,
    ];
}

function getPath(): string
{
    return __DIR__ . '/../../src/Holder/Parts/';
}
