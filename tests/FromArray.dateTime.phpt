<?php

use Tester\Assert;

require __DIR__ . '/../vendor/autoload.php';

class DateExample {

	use \DataLoader\FromArray;

	const SCHEME = ['date' => DateTime::class];

	/** @var DateTime */
	public $date = DateTime::class;
}

$dateExample = DateExample::fromArray(['date' => '2020-01-01']);
Assert::true($dateExample->date instanceof DateTime);