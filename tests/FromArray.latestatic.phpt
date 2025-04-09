<?php

use DataLoader\FromArray;
use Tester\Assert;

require __DIR__ . '/bootstrap.php';

class Base
{
    use FromArray;

    public ?string $value = null;
}

// inheritance test
class Custom extends Base {}

test(
    'LateStaticBinding class name test',
    function () {
        $c = Custom::fromArray(['value' => 'abc']);

        Assert::type(Custom::class, $c);
        Assert::same('abc', $c->value);
    },
);