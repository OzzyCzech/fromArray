<?php

use DataLoader\FromArray;

require_once __DIR__ . '/../src/FromArray.php';

class NestedData
{
    public function __construct(public string $data) {}
}

class NestedExample
{
    use FromArray;

    public const SCHEME = ['nested' => NestedData::class];
    public array $nested = [];
}

$example = NestedExample::fromArray(['nested' => ['some', 'data', 'here']]);
var_dump($example->nested); // will return instance of Nested class

foreach ($example->nested as $item) {
    var_dump($item->data); // will return 'some', 'data', 'here'
}
