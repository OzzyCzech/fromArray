<?php

use Tester\Assert;

require __DIR__ . '/../vendor/autoload.php';

class MappingExample {

	use \DataLoader\FromArray;

	const MAPPING = [
		'anotherId' => 'id',
		'exampleNumber' => 'isNumber',
	];

	const SCHEME = [
		'isNumber' => 'is_integer',
	];

	public $id;
	public $isNumber;
}

$values = MappingExample::fromArray(['anotherId' => 123, 'exampleNumber' => 123]);
Assert::true(is_integer($values->id));
Assert::true($values->isNumber);