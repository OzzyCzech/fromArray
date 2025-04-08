<?php

namespace DataLoader;

/**
 * Loading properties values to \stdClass type objects
 *
 * @package DataLoader
 */
trait FromArray {

	public static function fromArray(array $data = [], ?callable $filter = null, array $scheme = [], array $mapping = []): static {
		$class = get_called_class();

		// Allow to define schema in class
		if (defined(constant_name: "$class::SCHEME")) {
			$scheme = array_merge($class::SCHEME, $scheme);
		}

		// Allow map one property to another
		if (defined(constant_name: "$class::MAPPING")) {
			$mapping = array_flip(array_merge($class::MAPPING, $mapping));
		}

		// Hydrate object with values
		foreach (get_object_vars($obj = new $class) as $property => $default) {

			// Resolve data key with mapping array
			$key = array_key_exists($property, $mapping) ? $mapping[$property] : $property;

			// Skip missing data
			if (!array_key_exists($key, $data)) continue;

			// Filter values with callback
			$value = is_callable($filter) ? call_user_func($filter, $data[$key], $property, $default) : $data[$key];

			// Solving scheme prescription...
			if (array_key_exists($property, $scheme)) {

				// 1. Scheme prescription is Class
				if (is_string($scheme[$property]) && class_exists($scheme[$property])) {

					if (method_exists($scheme[$property], __FUNCTION__)) {
						// 1.1 fromArray() method exists
						$obj->{$property} = call_user_func([$scheme[$property], __FUNCTION__], (array) $value, $filter);
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