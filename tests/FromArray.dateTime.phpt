<?php

use DataLoader\FromArray;
use Tester\Assert;

require __DIR__ . '/bootstrap.php';

class DateExample
{
    use FromArray;

    public ?DateTime $date = null;
}

test('DateTime from array', function () {
    $dateExample = DateExample::fromArray(['date' => '2020-01-01']);
    Assert::true($dateExample->date instanceof DateTime);
});