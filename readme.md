
# FromArray data loader

`fromArray` trait allow to create objects instances loaded with initial data array:

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

And that will be results...

```json
{
  "a": "value of A",
  "b": "value of B",
  "c": "value of C"
}
```

## Install

```shell
composer require om/from-array
```

## SCHEME and nesting

Default object scheme is defined with `SCHEME` constant. You can use **callable** functions:

```php
<?php

require_once __DIR__ . '/../src/FromArray.php';

class SchemeExample {
  use \DataLoader\FromArray;

  const SCHEME = [
    'id' => 'intval',
    'date' => DateTime::class
  ];
  public ?int $id = null;
  public ?DateTime $date = null;
  public bool $alwaysFalse = true;
}

$example = SchemeExample::fromArray(
  data: ['id' => '12345', 'alwaysFalse' => true, 'date' => '2020-01-01'],
  scheme: ['alwaysFalse' => fn() => false]
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

$example = NestedExample::fromArray(['nested' => ['some', 'data', 'here']]);
var_dump($example->nested); // will return instance of Nested class
```

If you are use class that use same trait `object::fromArray()` then `fromArray` function (with same `$filter`)
will be called instead of class constructor. That allow you to made nested structures and load structured data:

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
  const SCHEME = ['one' => One::class, 'two' => Two::class];
  public ?One $one = null;
  public ?Two $two = null;
}

$nested = Multiple::fromArray(
  [
    'one' => ['value' => 'set value for one'],
    'two' => ['value' => 'set value for two']
  ]
);
```

You can also change scheme like that:

```php
$scheme = Nested::fromArray(data: $data, filter: ['a' => function($data) { return $data; }]);
```

In this case `$data` in `$a` will remain unchanged...

## Mapping

```php
class Example {
  use \DataLoader\FromArray;
  const MAPPING = ['anotherId'=>'id'];
  public ?int $id = null;
}
$example = Example::fromArray(['anotherId' => 1234]);
var_dump($example->id); // will return 1234
```

## Value filter

```php
class Filter {
  public ?DateTime $date = null;
  public string $notDate = '';
}

$data = ['date'=> '2017-11-01', 'notDate'=> '2017-11-01'];
$example = Filter::fromArray($data, function ($value, $property) {
  return ($property === 'date') ? new DateTime($value) : $value;
});

echo $example->notDate; // will return '2017-11-01' string
var_dump($example->date); // will return DateTime object
```

Filter can be useful when you for example load data from MongoDb:

```php
function ($value, $property) {
  if ($property === '_id') return new \MongoId((string)$value);
  if ($value instanceof \MongoDate) return new \DateTime('@' . $value->sec);
  return $value;
}
```

## Testing

```bash
composer install
composer test # will run Nette Tester
```

## Resources

* https://github.com/zendframework/zend-hydrator - Zend Hydrator class
* https://github.com/makasim/yadm - Mongo ODM
* https://github.com/doctrine/DoctrineModule/blob/master/docs/hydrator.md - Doctrine Hydrator
