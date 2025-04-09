# `FromArray` Data Loader Trait

## Install

```shell
composer require om/from-array
```

`fromArray` trait allows creating object instances loaded with an initial data array:

```php
class Example {
  use \DataLoader\FromArray;

  public ?string $a = null;
  public ?string $b = null;
  public ?string $c = null;
}

$example = Example::fromArray(
  [
    'a' => 'value of A',
    'b' => 'value of B',
    'c' => 'value of C'
  ]
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

### SCHEME and Nesting

The default object scheme is defined with the `SCHEME` constant. You can use **callable** functions:

```php
<?php

require_once __DIR__ . '/../src/FromArray.php';

class SchemeExample {
  use \DataLoader\FromArray;

  const SCHEME = [
    'id' => 'intval',
    'date' => DateTime::class,
  ];
  
  public ?int $id = null;
  public ?DateTime $date = null;
  public bool $alwaysFalse = true;
}

$example = SchemeExample::fromArray(
  data: [
    'id' => '12345',
    'alwaysFalse' => true,
    'date' => '2020-01-01',
  ],
  scheme: [
    'alwaysFalse' => fn() => false,
  ]
);

var_dump($example->id); // will return integer 12345
var_dump($example->alwaysFalse); // will return false
var_dump($example->date->format('c')); // will return date
```

Or you can use **class names**:

```php
class NestedData {
  public array $data = [];
  public function __construct($data) {
    $this->data = $data;
  }
}

class NestedExample {
  use \DataLoader\FromArray;

  const SCHEME = ['nested' => NestedData::class];
  public ?NestedData $nested = null;
}

$example = NestedExample::fromArray(
  ['nested' => ['some', 'data', 'here']]
);
var_dump($example->nested); // will return instance of NestedData class
```

If you use a class that uses the same trait, `object::fromArray()` will be called instead of the class constructor. This
allows you to create nested structures and load structured data:

```php
class One {
  use \DataLoader\FromArray;
  public ?string $value = null;
}

class Two {
  use \DataLoader\FromArray;
  public ?string $value = null;
}

class Multiple {
  use \DataLoader\FromArray;
  const SCHEME = [
    'one' => One::class,
    'two' => Two::class
  ];
  public ?One $one = null;
  public ?Two $two = null;
}

$nested = Multiple::fromArray([
  'one' => ['value' => 'set value for one'],
  'two' => ['value' => 'set value for two'],
]);
```

You can also change the scheme like this:

```php
$scheme = Nested::fromArray(data: $data, filter: ['a' => function($data) { return $data; }]);
```

In this case, `$data` in `$a` will remain unchanged.

### Mapping

```php
class Example {
  use \DataLoader\FromArray;
  const MAPPING = ['anotherId' => 'id'];
  public ?int $id = null;
}
$example = Example::fromArray(['anotherId' => 1234]);
var_dump($example->id); // will return 1234
```

### Value Filter

```php
class Filter {
  public ?DateTime $date = null;
  public string $notDate = '';
}

$data = [
  'date' => '2017-11-01',
  'notDate' => '2017-11-01'
];
$example = Filter::fromArray($data, function ($value, $property) {
  return ($property === 'date') ? new DateTime($value) : $value;
});

echo $example->notDate; // will return '2017-11-01' string
var_dump($example->date); // will return DateTime object
```

Filters can be useful when you load data from MongoDB:

```php
function ($value, $property) {
  
  // convert to ObjectId
  if ($property === '_id') return new \MongoDB\BSON\ObjectId($value);
  
  // convert to DateTime
  if ($value instanceof \MongoDB\BSON\UTCDateTime) return $value->toDateTime(); 
  return $value;
}
```

### Handling Nested Arrays

To process deeply nested arrays of objects, the trait supports recursive calls:

```php
class ParentClass {
  use \DataLoader\FromArray;

  const SCHEME = ['children' => ChildClass::class];
  public array $children = [];
}

class ChildClass {
  use \DataLoader\FromArray;
  public ?string $name = null;
}

$data = [
  'children' => [
    ['name' => 'Child 1'],
    ['name' => 'Child 2'],
    [
      'name' => 'Child 3',
      'children' => [['name' => 'Grandchild 1']]
    ]
  ]
];

$parent = ParentClass::fromArray($data);
```

This ensures that nested structures are loaded correctly with unlimited depth.

### Testing

```bash
composer install
composer test # will run Nette Tester
```

## Resources

* [Zend Hydrator class](https://github.com/zendframework/zend-hydrator)
* [Mongo ODM](https://github.com/makasim/yadm)
* [Doctrine Hydrator](https://github.com/doctrine/DoctrineModule/blob/2.1.x/docs/hydrator.md)

