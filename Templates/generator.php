<?php

$args = $argv;

// First param = generator.php file
array_shift($args);

$template = array_shift($args);
echo 'Template: ' . $template . PHP_EOL;

$className = array_shift($args);
echo 'Class Name: ' . $className . PHP_EOL;

if (!$template || !$className) {
    die("Template/Class name is required." . PHP_EOL);
}

$params = $args;

//

$templateFile = $template . '/template.php';
$placeholdersFile = $template . '/placeholders.php';

if (!file_exists($templateFile) || !file_exists($placeholdersFile)) {
    die("Template/Placeholder file does not exist." . PHP_EOL);
}

//

include_once $template . '/placeholders.php';

$outputFile = getPath() . $className . '.php';
echo 'Output File: ' . $outputFile . PHP_EOL;

if (file_exists($outputFile)) {
    die("File already exists." . PHP_EOL);
}

$placeholders = placeholders($params, $className);
var_dump($placeholders);

$code = generateCode($templateFile, $placeholders);

$result = writeCode($outputFile, $code);

if (!$result) {
    die("Error writing file." . PHP_EOL);
}

echo "File generated successfully." . PHP_EOL;

//

function generateCode(string $templateFile, array $values): string
{
    $template = file_get_contents($templateFile);

    $code = str_replace(array_keys($values), array_values($values), $template);

    return $code;
}

function writeCode(string $outputFile, string $code): bool
{
    return file_put_contents($outputFile, $code) !== false;
}
