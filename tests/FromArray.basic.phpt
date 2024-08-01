<?php

use DataLoader\FromArray;
use Tester\Assert;

require __DIR__ . '/bootstrap.php';


class Basic {
	use FromArray;

	public ?string $value = null;
	public ?string $default = 'default value';
}

test('Check default values', function () {
	$basic = Basic::fromArray([]);
	Assert::same(null, $basic->value);
	Assert::same('default value', $basic->default);
});

test('Value loading', function () {
	$basic = Basic::fromArray($data = ['value' => 'this was loaded']);
	Assert::same('this was loaded', $basic->value);
	Assert::same('default value', $basic->default);
});

test('Change value with callback', function () {
	$basic = Basic::fromArray(
		$data = [
			'value' => 'something else',
			'default' => 'something default'
		],
		function ($value) {
			return sprintf('CHANGING TO %s', $value);
		}
	);

	Assert::same('CHANGING TO something else', $basic->value);
	Assert::same('CHANGING TO something default', $basic->default);
});