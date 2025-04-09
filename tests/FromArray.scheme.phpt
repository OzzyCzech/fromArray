<?php

use DataLoader\FromArray;
use Tester\Assert;

require __DIR__ . '/bootstrap.php';

test('Change value with default scheme', function () {
    function changeToFalse()
    {
        return false;
    }

    class DefaultSchemeCallback
    {
        use FromArray;

        const SCHEME = ['value' => 'changeToFalse'];
        public bool $value = true;
    }

    $scheme = DefaultSchemeCallback::fromArray(['value' => true]);
    Assert::false($scheme->value);
});

test('Change value with local scheme', function () {
    class LocalSchemeCallback
    {
        use FromArray;

        public bool $value = true;
    }

    $scheme = LocalSchemeCallback::fromArray(
        data: ['value' => true],
        scheme: [
            'value' => fn() => false,
            'id' => '123456',
        ],
    );

    Assert::false($scheme->value);
});

test('Default php functions for changing values', function () {
    class InvValCallback
    {
        use FromArray;

        const SCHEME = [
            'intval' => 'intval',
            'strval' => 'strval',
            'boolval' => 'boolval',
        ];

        public ?int $intval = null;
        public ?string $strval = null;
        public ?bool $boolval = null;
        public ?int $missing = null;
    }

    $values = InvValCallback::fromArray(['intval' => null, 'strval' => null, 'boolval' => null]);
    Assert::true(is_integer($values->intval));
    Assert::same(0, $values->intval);

    Assert::true(is_string($values->strval));
    Assert::same('', $values->strval);

    Assert::true(is_bool($values->boolval));
    Assert::false($values->boolval);

    Assert::true(is_null($values->missing));
    Assert::null($values->missing);
});


