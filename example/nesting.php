<?php

require_once __DIR__ . '/../src/FromArray.php';

class NestedData {
	public array $data = [];

	public function __construct($data) {
		$this->data = $data;
	}
}

class NestedExample {
	use \DataLoader\FromArray;

	const SCHEME = ['nested' => NestedData::class];
	public ?NestedData $nested = null;
}

$example = NestedExample::fromArray(['nested' => ['some', 'data', 'here']]);
var_dump($example->nested); // will return instance of Nested class