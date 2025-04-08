<?php

use DataLoader\FromArray;
use Tester\Assert;

require __DIR__ . '/bootstrap.php';

class DateExample {

	use FromArray;

	const SCHEME = ['date' => DateTime::class];
	public string|DateTime $date = DateTime::class;
}

test('DateTime from array', function () {
	$dateExample = DateExample::fromArray(['date' => '2020-01-01']);
	Assert::true($dateExample->date instanceof DateTime);
});