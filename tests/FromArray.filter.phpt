<?php

use DataLoader\FromArray;
use Tester\Assert;

require __DIR__ . '/bootstrap.php';

test('Filter callback', function () {
    class FilterCallback
    {
        use FromArray;

        public bool $value = true;
    }

    $results = FilterCallback::fromArray(['value' => true], fn() => false);
    Assert::false($results->value);
});