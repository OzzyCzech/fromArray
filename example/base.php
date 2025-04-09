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
    '_id' => 'Value of one',
    'date' => '2025-01-01 00:00:00',
    'amount' => '123456789',
]);

echo json_encode($example, JSON_PRETTY_PRINT) . PHP_EOL;
