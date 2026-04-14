<?php declare(strict_types=1);

use DataLoader\FromArray;
use Tester\Assert;

require __DIR__ . '/bootstrap.php';

class TypeCastingExample
{
    use FromArray;

    public int $intVal = 0;
    public bool $boolVal = false;
    public float $floatVal = 0.0;
    public string $stringVal = '';
    public array $arrayVal = [];
}

test('Type casting from string values', function () {
    $example = TypeCastingExample::fromArray([
        'intVal' => '42',
        'boolVal' => '1',
        'floatVal' => '3.14',
        'stringVal' => 123,
        'arrayVal' => 'value',
    ]);

    Assert::same(42, $example->intVal);
    Assert::same(true, $example->boolVal);
    Assert::same(3.14, $example->floatVal);
    Assert::same('123', $example->stringVal);
    Assert::type('array', $example->arrayVal);
});

test('Nullable object property with null', function () {
    class NullableObjectExample
    {
        use FromArray;

        public ?DateTime $date = null;
    }

    $example = NullableObjectExample::fromArray(['date' => null]);
    Assert::null($example->date);
});

test('Object property receives already-constructed instance', function () {
    class ObjectHolder
    {
        use FromArray;

        public ?DateTime $date = null;
    }

    $dt = new DateTime('2024-01-01');
    $example = ObjectHolder::fromArray(['date' => $dt]);
    Assert::same($dt, $example->date);
});
