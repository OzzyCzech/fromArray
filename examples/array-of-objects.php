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

assert($example instanceof NestedExample);
assert($example->objects[0] instanceof ExampleObject);
assert($example->objects[0]->value === 'some');
assert($example->objects[1] instanceof ExampleObject);
assert($example->objects[1]->value === 'data');
assert($example->objects[2] instanceof ExampleObject);
assert($example->objects[2]->value === 'here');

var_dump($example->objects); // will return instance of Nested class
