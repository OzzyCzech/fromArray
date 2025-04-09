<?php

use DataLoader\FromArray;
use DataLoader\Loader;
use DataLoader\Property;

require_once __DIR__ . '/../vendor/autoload.php';

class MyCustomLoader
{
    public function __invoke(mixed $value, Property $property): mixed
    {
        return match ($property->name) {
            'id' => -1,
            'name' => 'John Doe',
            default => $value,
        };
    }
}

function changeAddress3(string $value)
{
    return '456 Elm St';
}

#[Loader(MyCustomLoader::class)]
class ObjectWithLoader
{
    use FromArray;

    public int $id;
    public string $name;
    public string $address;

    #[Property(from: 'address')]
    public string $address2;

    #[Property(from: 'address', loader: 'changeAddress3')]
    public string $address3;
}


$example = ObjectWithLoader::fromArray(
    data: [
        'id' => 'any value will be converted',
        'name' => 'any value will be converted',
        'address' => '123 Main St',
    ],
);

var_dump($example->id); // -1
var_dump($example->name); // John Doe
var_dump($example->address); // 123 Main St
var_dump($example->address2); // 123 Main St
var_dump($example->address3); // 456 Elm St
