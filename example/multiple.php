<?php

use DataLoader\FromArray;

require_once __DIR__ . '/../vendor/autoload.php';

class One
{
    use FromArray;

    public string $value;
}

class Two
{
    use FromArray;

    public string $value;
    public One $nested;
    public DateTime $dateTime;
}

class NestedObject
{
    use FromArray;

    public One $one;
    public Two $two;
}

$nested = NestedObject::fromArray([
    'one' => [
        'value' => 'set value for one',
        'ignore' => 'this will be ignored',
    ],
    'two' => [
        'value' => 'set value for two',
        'dateTime' => '2025-04-09 00:00:00',
        'nested' => [
            'value' => 'another one',
        ],
    ],
]);

var_dump($nested);