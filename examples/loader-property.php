<?php

use DataLoader\FromArray;
use DataLoader\Loader;
use DataLoader\Property;

require_once __DIR__ . '/../vendor/autoload.php';


class CustomLoader
{
    public function __invoke(mixed $value, Property $property): int
    {
        return $value === 'some value' ? -1 : 0;
    }
}

function alwaysFalse(): bool
{
    return false;
}

class DateTimeLoader
{
    public function __invoke(string $value, Property $property): string
    {
        return DateTime::createFromFormat('Y-m-d', $value)->format('j. n. Y');
    }
}

class ObjectWithPropertyLoaders
{
    use FromArray;

    public string $name;

    #[Property(from: '_id', loader: CustomLoader::class)]
    public int $id;

    #[Loader('alwaysFalse')]
    public bool $alwaysFalse = true;

    #[Loader(new DateTimeLoader())]
    public string $date;
}

$example = ObjectWithPropertyLoaders::fromArray(
    data: [
        '_id' => 'some value',
        'name' => 'John Doe',
        'alwaysFalse' => true,
        'date' => '2025-01-01',
    ],
);

assert($example instanceof ObjectWithPropertyLoaders);
assert($example->id === -1);
assert($example->name === 'John Doe');
assert($example->date === '1. 1. 2025');
assert($example->alwaysFalse === false);

var_dump($example); // -1
