<?php

use DataLoader\FromArray;
use DataLoader\Loader;
use Tester\Assert;

require __DIR__ . '/bootstrap.php';

test('Change value with property Loader', function () {
    function changeToFalse(): bool
    {
        return false;
    }

    class DefaultSchemeCallback
    {
        use FromArray;

        #[Loader('changeToFalse')]
        public bool $value = true;
    }

    $scheme = DefaultSchemeCallback::fromArray(['value' => true]);
    Assert::false($scheme->value);
});

test('Change value class loader', function () {
    class MyCustomLoader
    {
        public function __invoke(): bool
        {
            return false;
        }

    }

    #[Loader(MyCustomLoader::class)]
    class LocalSchemeCallback
    {
        use FromArray;

        public bool $value = true;
    }

    $scheme = LocalSchemeCallback::fromArray(data: ['value' => true]);

    Assert::false($scheme->value);
});


