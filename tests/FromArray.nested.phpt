<?php

use DataLoader\FromArray;
use Tester\Assert;

require __DIR__ . '/bootstrap.php';

class A
{
    use FromArray;

    public ?string $value = null;
}

class B
{
    use FromArray;

    public ?string $value = null;
}

class Nested
{
    use FromArray;

    const SCHEME = [
        'a' => A::class,
        'b' => B::class,
    ];

    public ?A $a = null;
    public ?B $b = null;
}

$data = [
    'a' => ['value' => 'value of A'],
    'b' => ['value' => 'value of B'],
];

test('Nested::fromArray() creates nested objects', function () use ($data) {
    $nested = Nested::fromArray($data);

    Assert::type(Nested::class, $nested);
    Assert::type(A::class, $nested->a);
    Assert::type(B::class, $nested->b);
});

test('Filter can change leafs values', function () use ($data) {
    $nested = Nested::fromArray(
        $data,
        function ($value, $property) {
            return ($property === 'value') ? 'Filter can change leafs values' : $value;
        },
    );

    Assert::same('Filter can change leafs values', $nested->a->value);
    Assert::same('Filter can change leafs values', $nested->b->value);
});