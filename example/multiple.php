<?php

require_once __DIR__ . '/../src/FromArray.php';

class One {
	use \DataLoader\FromArray;
	public ?string $value = null;
}

class Two {
	use \DataLoader\FromArray;
	public ?string $value = null;
}

class Multiple {
	use \DataLoader\FromArray;
	const SCHEME = ['one' => One::class, 'two' => Two::class];
	public ?One $one = null;
	public ?Two $two = null;
}

$nested = Multiple::fromArray(
	[
		'one' => ['value' => 'set value for one'],
		'two' => ['value' => 'set value for two']
	]
);

var_dump($nested);