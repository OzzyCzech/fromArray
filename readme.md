# FromArray data loader

`fromArray` trait allow create objects instances loaded with initial data array:

```php
class Example {
  use \DataLoader\FromArray;
  public $a;
  public $b;
  public $c;
}

$data = [
  'a' => 'value of A',
  'b' => 'value of B', 
  'c' => 'value of C'
];

// return new instance of Example object with $data
$example = Example::fromArray($data); 

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

```composer require om/from-array```

## SCHEME and nesting

Default object scheme is defined with `SCHEME` constant. You can use **callable** functions:

```php
function alwaysFalse() { return false; }

class Example {
  use \DataLoader\FromArray;
  const SCHEME = ['id' => 'intval', 'isFalse' => 'alwaysFalse', 'date' => DateTime::class];
  public $id;  
  public $date;  
  public $isFalse = true;  
}

$data = ['id'=> '12345', 'isFalse' => true, 'date' => '2020-01-01'];
$example = Example::fromArray($data);
echo $example->id; // will return integer 12345
echo $example->isFalse; // will return false
echo $example->date->format('c'); // will return date
```

Or you can use **class names**:

```php
class Nested {
  public $data = [];
  public function __construct($data) {
    $this->data = $data;
  }
}

class Example {
  use \DataLoader\FromArray;
  const SCHEME = ['nested' => Nested::class];
  public $nested;
}
$example = Example::fromArray(['nested' => ['some', 'data', 'here']]);
var_dump($example->nested); // will return instance of Nested class
```

If you are use class that use same trait `object::fromArray()` then `fromArray` function (with same `$filter`) 
will be called instead of class constructor. That allow you to made nested structures and load structured data:  

```php
class A {
  use \DataLoader\FromArray;
  public $value;
}

class B {
  use \DataLoader\FromArray;
  public $value;
}

class Nested {
  use \DataLoader\FromArray;
  const SCHEME = ['a' => A::class, 'b' => B::class];
  /** @var A */
  public $a;
  /** @var B */
  public $b;
}
```

You can also change scheme like that:

```php
$scheme = Nested::fromArray($data, null, ['a' => function($data) { return $data; }]);
```

In this case `$data` in `$a` will remain unchanged...  

## Mapping

```php
class Example {
  use \DataLoader\FromArray;
  const MAPPING = ['anotherId'=>'id'];
  public $id;
}
$example = Example::fromArray(['anotherId' => 1234]);
var_dump($example->id); // will return 1234
```

## Value filter

```php
class Filter {
  /** @var DateTime */
  public $date;
  /** @var string */
  public $notDate;
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
