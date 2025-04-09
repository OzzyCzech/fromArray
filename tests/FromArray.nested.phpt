<?php

use DataLoader\FromArray;
use Tester\Assert;

require __DIR__ . '/bootstrap.php';

class A
{
    use FromArray;

    public string $value;
}

class B
{
    use FromArray;

    public string $value;
}

class Nested
{
    use FromArray;

    public A $a;
    public B $b;
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