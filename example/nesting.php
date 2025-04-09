<?php

use DataLoader\FromArray;
use DataLoader\Property;

require_once __DIR__ . '/../src/FromArray.php';

class NestedData
{
    public string $value;

    public function __construct($value)
    {
        $this->value = $value;
    }
}

class NestedExample
{
    use FromArray;

    #[Property(object: NestedData::class)]
    public array $nested = [];
}

$example = NestedExample::fromArray([
    'nested' => ['some', 'data', 'here'],
]);
var_dump($example->nested); // will return instance of Nested class