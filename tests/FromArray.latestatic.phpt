<?php

use Tester\Assert;

require __DIR__ . '/../vendor/autoload.php';

class A {
	use \DataLoader\FromArray;
	public $value;
}

class C extends A {

}

// the "Late Static Binding" class name test

$c = C::fromArray(['value' => 'abc']);

Assert::true($c instanceof C);
Assert::same('abc', $c->value);