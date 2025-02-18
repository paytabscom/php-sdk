<?php

function placeholders(array $args, string $className): array
{
    return [
        '__ClassName__' => $className,

        '__Code__' => array_shift($args) ?? '',
        '__Title__' => array_shift($args) ?? '',
        '__Endpoint__' => array_shift($args) ?? '',
    ];
}

function getPath(): string
{
    return __DIR__ . '/../../src/Gateway/Endpoints/';
}
