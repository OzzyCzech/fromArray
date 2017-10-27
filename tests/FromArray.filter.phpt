<?php

use Tester\Assert;

require __DIR__ . '/../vendor/autoload.php';


function changeToFalse() {
	return false;
}

class FilterCallback {
	use \DataLoader\FromArray;
	public $value = true;
}

$clb = FilterCallback::fromArray(['value' => true], 'changeToFalse');
Assert::false($clb->value);