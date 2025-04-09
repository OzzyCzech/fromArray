<?php

use DataLoader\FromArray;

require_once __DIR__ . '/../src/FromArray.php';

class One
{
    use FromArray;

    public ?string $value = null;
}

class Two
{
    use FromArray;

    public ?string $value = null;
}

class Multiple
{
    use FromArray;

    public const SCHEME = ['one' => One::class, 'two' => Two::class];
    public ?One $one = null;
    public ?Two $two = null;
}

$nested = Multiple::fromArray(
    [
        'one' => ['value' => 'set value for one'],
        'two' => ['value' => 'set value for two'],
    ],
);

var_dump($nested);
