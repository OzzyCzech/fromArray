<?php

use DataLoader\FromArray;

require_once __DIR__ . '/../src/FromArray.php';

class Example
{
    use FromArray;

    public ?string $a = null;
    public ?string $b = null;
    public ?string $c = null;
}

$example = Example::fromArray(
    [
        'a' => 'value of A',
        'b' => 'value of B',
        'c' => 'value of C',
    ],
);

echo json_encode($example, JSON_PRETTY_PRINT);
