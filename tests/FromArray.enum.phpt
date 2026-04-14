<?php declare(strict_types=1);

use DataLoader\FromArray;
use Tester\Assert;

require __DIR__ . '/bootstrap.php';

enum Color: string
{
    case Red = 'red';
    case Green = 'green';
    case Blue = 'blue';
}

class EnumExample
{
    use FromArray;

    public Color $color;
    public ?Color $nullableColor = null;
}

test('Enum from array', function () {
    $example = EnumExample::fromArray(['color' => 'red']);
    Assert::same(Color::Red, $example->color);
});

test('Nullable enum from array with value', function () {
    $example = EnumExample::fromArray(['color' => 'green', 'nullableColor' => 'blue']);
    Assert::same(Color::Green, $example->color);
    Assert::same(Color::Blue, $example->nullableColor);
});

test('Nullable enum from array with null', function () {
    $example = EnumExample::fromArray(['color' => 'red', 'nullableColor' => null]);
    Assert::same(Color::Red, $example->color);
    Assert::null($example->nullableColor);
});

test('Nullable enum with invalid value uses tryFrom', function () {
    $example = EnumExample::fromArray(['color' => 'red', 'nullableColor' => 'invalid']);
    Assert::null($example->nullableColor);
});
