<?php

use DataLoader\FromArray;
use DataLoader\Property;
use Tester\Assert;

require __DIR__ . '/bootstrap.php';

class MappingExample
{

    use FromArray;

    #[Property(from: 'anotherId')]
    public int $id = 0;
    
    #[Property(from: 'anotherNumber')]
    public int $number = 0;
}

test('Mapping properties to another keys', function () {
    $values = MappingExample::fromArray(
        [
            'anotherId' => 123,
            'anotherNumber' => '345',
        ],
    );

    Assert::same(123, $values->id);
    Assert::same(345, $values->number);
});