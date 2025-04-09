<?php

namespace DataLoader;

use function var_dump;

/**
 * Loading properties values to \stdClass type objects
 *
 * @package DataLoader
 */
trait FromArray
{

    public static function fromArray(
        array $data = [],
        ?callable $filter = null,
        array $scheme = [],
        array $mapping = [],
    ): static {
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
            if (!array_key_exists($key, $data)) {
                continue;
            }

            // Filter values with callback
            $value = is_callable($filter) ? call_user_func($filter, $data[$key], $property, $default) : $data[$key];

            // Solving scheme prescription...
            if (array_key_exists($property, $scheme)) {
                // 1. Scheme prescription is Class
                if (is_string($scheme[$property]) && class_exists($scheme[$property])) {
                    $obj->{$property} = self::processNestedValue($value, $scheme[$property], $filter);
                } // 2. Scheme prescription is Callback e.g. strval, intval, function ($value) {}
                elseif (is_callable($scheme[$property])) {
                    $obj->{$property} = call_user_func($scheme[$property], $value);
                } else {
                    throw new \InvalidArgumentException("Invalid SCHEME definition for property {$property}");
                }
            } else {
                $obj->{$property} = $value; // assign value to object
            }
        }

        return $obj;
    }

    private static function processNestedValue(mixed $value, string $class, ?callable $filter): mixed
    {
        if (is_array($value)) {
            if (array_is_list($value)) {
                var_dump($value, $class);

                // Handle array of nested class instances
                return array_map(fn($item) => self::processNestedValue($item, $class, $filter), $value);
            } elseif (method_exists($class, 'fromArray')) {
                // Handle single nested class instance
                return call_user_func([$class, 'fromArray'], $value, $filter);
            }
        }
        // If it's not an array, assume it's a direct instantiation
        return new $class($value);
    }
}