<?php

use DataLoader\FromArray;
use DataLoader\Property;

require_once __DIR__ . '/../vendor/autoload.php';

class Example
{
    use FromArray;

    #[Property(from: '_id')]
    public string $id;
    public DateTime $date;
    public int $amount;
}

$example = Example::fromArray([
    // Will be stored in $id property
    '_id' => 'Value of one',

    // will be converted to DateTime
    'date' => '2025-04-09 00:00:00',

    // will be converted to int
    'amount' => '123456789',
]);

assert($example instanceof Example);
assert($example->id === 'Value of one');
assert($example->date instanceof DateTime);
assert($example->date->format('j. n. Y') === '9. 4. 2025');
var_dump($example);
