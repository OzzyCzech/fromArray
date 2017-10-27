<?php

namespace DataLoader;

/**
 * Loading properties values to \stdClass type objects
 *
 * @package DataLoader
 */
trait FromArray {

	/**
	 * @param array $data
	 * @param callable|null $filter
	 * @param array $scheme
	 * @return static
	 */
	public static function fromArray(array $data = [], callable $filter = null, array $scheme = []) {
		// Late Static Binding class
		$class = get_called_class();

		// merge $scheme with default $class::SCHEME
		if (defined($class . '::SCHEME')) $scheme = array_merge($class::SCHEME, $scheme);

		// Hydrate object with values
		foreach (get_object_vars($obj = new $class) as $property => $default) {

			// Skip missing data
			if (!array_key_exists($property, $data)) continue;

			// Filter values with callback
			$value = is_callable($filter) ? call_user_func($filter, $data[$property], $property, $default) : $data[$property];

			// Solving scheme prescription...
			if (array_key_exists($property, $scheme)) {

				// 1. Scheme prescription is Class
				if (is_string($scheme[$property]) && class_exists($scheme[$property])) {

					if (method_exists($scheme[$property], __FUNCTION__)) {
						// 1.1 fromArray() method exists
						$obj->{$property} = call_user_func([$scheme[$property], __FUNCTION__], (array)$value, $filter);
					} else {
						// 1.2 Create object with constructor
						$obj->{$property} = new $scheme[$property]($value);
					}
				}

				// 2. Scheme prescription is Callback e.g. strval, intval, function ($value) {}
				if (is_callable($scheme[$property])) {
					$obj->{$property} = call_user_func($scheme[$property], $value);
				}

			} else {
				$obj->{$property} = $value; // assign value to object
			}
		}
		return $obj;
	}
}