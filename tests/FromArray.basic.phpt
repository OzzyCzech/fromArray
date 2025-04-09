<?php

use DataLoader\FromArray;
use Tester\Assert;

require __DIR__ . '/bootstrap.php';


class Basic
{
    use FromArray;

    public ?int $nullable = 5;
    public string $value = 'default value';
    public ?string $default = 'default value';
}

test('Check default values', function () {
    $basic = Basic::fromArray([]);
    Assert::same(5, $basic->nullable);
    Assert::same('default value', $basic->value);
    Assert::same('default value', $basic->default);
});

test('Value loading', function () {
    $basic = Basic::fromArray(['value' => 'this was loaded']);
    Assert::same('this was loaded', $basic->value);
    Assert::same('default value', $basic->default);
});

test('Skip properties, if not set', function () {
    $basic = Basic::fromArray([
        'value' => 'this was loaded',
        'thisNotSet' => 'this was not loaded',
        'thisNotSetAlso' => 'this was not loaded',
    ]);

    Assert::same('this was loaded', $basic->value);
});

test('Nullable values should be allowed', function () {
    $values = Basic::fromArray(['nullable' => null,]);
    Assert::null($values->nullable);
});

