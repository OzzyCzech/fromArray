<div align="center">

![Packagist Version](https://img.shields.io/packagist/v/om/from-array?style=for-the-badge)
![Packagist License](https://img.shields.io/packagist/l/om/from-array?style=for-the-badge)
![Packagist Downloads](https://img.shields.io/packagist/dm/om/from-array?style=for-the-badge)

</div>

# `FromArray` Data Loader Trait

## Install

```shell
composer require om/from-array
```

The `fromArray` trait enables the creation of object instances preloaded with an initial data array:

```php
class Example {
  use FromArray;
  public string $a;
  public string $b;
  public string $c;
}

$example = Example::fromArray(
  [
    'a' => 'value of A',
    'b' => 'value of B',
    'c' => 'value of C',
  ],
);

echo json_encode($example, JSON_PRETTY_PRINT);
```

And that will be the result:

```json
{
  "a": "value of A",
  "b": "value of B",
  "c": "value of C"
}
```

The trait **works with public properties only** - private and protected properties will be ignored.

### The `Property`, `Loader` and `Type` attributes

There are few [PHP Attributes](https://www.php.net/manual/en/language.attributes.overview.php) to
configure the `fromArray` behavior:

#### `Property`

The `Property` attribute allows you to define a specific key (`from`) from the data array that will be mapped to the
property.

```php
class Example
{
    use FromArray;
    
    #[Property(from: '_id')]
    public int $id;
}

$example = Example::fromArray(['_id' => 123]);
assert($example->id === 123);
```

You can also customize the `loader` used to load the data:

```php
function addPrefix(mixed $value) {
    return 'PREFIX_' . $value;
}

class Example
{
    use FromArray;
    
    #[Property(loader: 'addPrefix')]
    public string $name;
}

$example = Example::fromArray(['name' => 'name']);
assert($example->id === 'PREFIX_name');
```

Loader can also be the **name of a class**, an **object**, or any type of **callable function**.

See [basic-example.php](/examples/basic-example.php) for more code examples.

#### `Loader`

The `Loader` attribute allows you to define a specific value loader for the property. You can add global loaders to the
class or to the property.

```php
<?php
use DataLoader\Loader;use DataLoader\Property;

class MyLoader {
    public function __invoke($value, Property $property){
        return match ($property->name) {
            'id' => (int) $value,
            'names' => explode(',', $value),
            default => $value,
        }
    }
} 

#[Loader(MyLoader::class)]
class Example
{
    use FromArray;
    public mixed $id = null;
    public array $names = [];
    public string $other = '';
    
}

$example = Example::fromArray([
    'id' => '123',
    'names' => 'John,Doe,Smith'
    'other' => 'value',
]);
```

See [loader-class.php](/examples/loader-class.php) and [loader-property.php](/examples/loader-property.php) for more
examples.

#### `Type`

The `Type` attribute allows you to define a specific type for the property. Types are usually auto detected, but you can
force the type using the `Type` attribute.

```php
class Example
{
    use FromArray;
    
    #[Type(name: Types::Int, allowNull: true, class: null)]
    public mixed $id;
}
```

There is one special case for the `Type`, you can specify that array is array of specific objects:

```php
class ExampleObject {
    public function __construct(public string $name = null) {}
}

class Example
{
    use FromArray;
    
    #[Type(name: Types::ArrayOfObjects, class: ExampleObject::class)]
    public array $objects = [];
}

$example = Example::fromArray([
    'objects' => [
        ['name' => 'John'],
        ['name' => 'Doe'],
    ],
]);
```

See [array-of-objects.php](/examples/array-of-objects.php) for more examples.

## Resources

* [Zend Hydrator class](https://github.com/zendframework/zend-hydrator)
* [Mongo ODM](https://github.com/makasim/yadm)
* [Doctrine Hydrator](https://github.com/doctrine/DoctrineModule/blob/2.1.x/docs/hydrator.md)

