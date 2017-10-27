<?php

use Tester\Assert;

require __DIR__ . '/../vendor/autoload.php';


class A {
	use \DataLoader\FromArray;
	public $value;
	public $default = 'default value';
}

// Loader test

$a = A::fromArray($data = ['value' => 'this was loaded']);

Assert::same('this was loaded', $a->value);
Assert::same('default value', $a->default);


$a = A::fromArray(
	$data = ['value' => 'NOT THIS', 'default' => 'NOT THIS'],
	function ($value) {
		return 'CALLBACK';
	}
);

Assert::same('CALLBACK', $a->value);
Assert::same('CALLBACK', $a->default);