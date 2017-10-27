<?php

use Tester\Assert;

require __DIR__ . '/../vendor/autoload.php';


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

	const SCHEME = [
		'a' => A::class,
		'b' => B::class
	];

	/** @var A */
	public $a;
	/** @var B */
	public $b;
}

$data = [
	'a' => ['value' => 'this is value of A'],
	'b' => ['value' => 'this is value of B']
];


$nested = Nested::fromArray($data);

Assert::true($nested->a instanceof A);
Assert::true($nested->b instanceof B);
Assert::same('this is value of A', $nested->a->value);
Assert::same('this is value of B', $nested->b->value);

$nested = Nested::fromArray(
	$data,
	function ($value, $property) {
		return ($property === 'value') ? 'Filter can change leafs values' : $value;
	}
);

Assert::true($nested->a instanceof A);
Assert::true($nested->b instanceof B);
Assert::same('Filter can change leafs values', $nested->a->value);
Assert::same('Filter can change leafs values', $nested->b->value);