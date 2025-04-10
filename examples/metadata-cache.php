<?php

use DataLoader\FromArray;
use DataLoader\Metadata;

require_once __DIR__ . '/../vendor/autoload.php';

class RestoredObject
{
    use FromArray;

    public string $id;
    public string $name;
    public string $address;
}

class AnotherObject
{
    use FromArray;

    public string $id;
    public string $name;
    public string $address;
}

$metadata = new Metadata();
$metadata->resolve(RestoredObject::class);
assert($metadata->count() === 1, 'Metadata count should be 1');

$metadata->resolve(AnotherObject::class);
assert($metadata->count() === 2, 'Metadata count should be 2');
$cache = $metadata->getArrayCopy();

// singleton instance is not set yet
assert(Metadata::getInstance()->count() === 0, 'Singleton already set');

// now set the metadata to the singleton instance
Metadata::fromCache($cache);

// singleton instance should be restored
assert(Metadata::getInstance()->count() === 2, 'Singleton should be set');

$object = AnotherObject::fromArray([
    'id' => 'any value will be converted',
    'name' => 'any value will be converted',
    'address' => '123 Main St',
]);

var_dump($object);
