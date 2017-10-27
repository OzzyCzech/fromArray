<?php

use Tester\Assert;

require __DIR__ . '/../vendor/autoload.php';


function changeToFalse() {
	return false;
}

class DefaultSchemeCallback {
	use \DataLoader\FromArray;
	const SCHEME = ['value' => 'changeToFalse'];
	public $value = true;
}

$scheme = DefaultSchemeCallback::fromArray(['value' => true]);
Assert::false($scheme->value);


class SchemeCallback {
	use \DataLoader\FromArray;
	public $value = true;
}

$scheme = SchemeCallback::fromArray(['value' => true], null, ['value' => 'changeToFalse', 'id' => '123456']);
Assert::false($scheme->value);


class IntvalCallback {
	use \DataLoader\FromArray;
	const SCHEME = ['intval' => 'intval', 'strval' => 'strval', 'boolval' => 'boolval'];
	public $intval;
	public $strval;
	public $boolval;
	public $missing;
}

$values = IntvalCallback::fromArray(['intval' => null, 'strval' => null, 'boolval' => null]);
Assert::true(is_integer($values->intval));
Assert::true(is_string($values->strval));
Assert::true(is_bool($values->boolval));
Assert::true(is_null($values->missing));