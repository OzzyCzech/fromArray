<?php

use DataLoader\FromArray;
use DataLoader\Type;
use DataLoader\Types;

require_once __DIR__ . '/../vendor/autoload.php';

class ExampleObject
{
    public function __construct(public string $value) {}
}

class NestedExample
{
    use FromArray;

    #[Type(name: Types::ArrayOfObjects, class: ExampleObject::class)]
    public array $objects = [];
}

$example = NestedExample::fromArray([
    'objects' => [
        'some',
        'data',
        'here',
    ],
]);

var_dump($example->objects); // will return instance of Nested class
