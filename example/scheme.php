<?php

require_once __DIR__ . '/../src/FromArray.php';

class SchemeExample {
	use \DataLoader\FromArray;

	const SCHEME = [
		'id' => 'intval',
		'date' => DateTime::class
	];
	public ?int $id = null;
	public ?DateTime $date = null;
	public bool $alwaysFalse = true;
}

$example = SchemeExample::fromArray(
	data: ['id' => '12345', 'alwaysFalse' => true, 'date' => '2020-01-01'],
	scheme: ['alwaysFalse' => fn() => false]
);

var_dump($example->id); // will return integer 12345
var_dump($example->alwaysFalse); // will return false
var_dump($example->date->format('c')); // will return date