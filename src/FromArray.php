<?php

namespace DataLoader;

use function call_user_func;
use function is_callable;
use function var_dump;

/**
 * Loading properties values to \stdClass type objects
 *
 * @package DataLoader
 */
trait FromArray
{
    private static function load(mixed $value, Property $property)
    {
        $type = $property->type ?? null;

        if ($type?->name === Types::Object) {
            if ($type->is_a($value)) {
                return $value;
            }
        }

        // Convert to the specified type
        return match ($property->type->name ?? null) {
            Types::Int => (int)$value,
            Types::Bool => (bool)$value,
            Types::String => (string)$value,
            Types::Float => (float)$value,
            Types::Array => (array)$value,
            Types::Object => new $property->type->class($value),
            Types::Enum => $property->type->allowNull ?
                call_user_func([$property->type->class, 'tryFrom'], $value) :
                call_user_func([$property->type->class, 'from'], $value),
            default => $value,
        };
    }

    public static function fromArray(array $data = [], ?callable $filter = null): static
    {
        $class = get_called_class();


        $object = new $class;
        foreach (Metadata::getProperties($class) as $property) {
            /** @var Property $property */
            $key = $property->from ?? $property->name;

            if (!array_key_exists($key, $data)) {
                continue; // skip missing data
            }

            $object->{$property->name} = is_callable($filter) ?
                call_user_func($filter, $data[$key], $property) :
                self::load($data[$key], $property);
        }

        var_dump($object);

        die('here');

        // Hydrate object with values
        foreach (get_object_vars($object = new $class) as $property => $default) {
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
                if (is_string($scheme[$property]) && class_exists($scheme[$property])) {
                    // 1. Scheme prescription is Class
                    $object->{$property} = self::processNestedValue($value, $scheme[$property], $filter);
                } elseif (is_callable($scheme[$property])) {
                    // 2. Scheme prescription is Callback e.g. strval, intval, function ($value) {}
                    $object->{$property} = call_user_func($scheme[$property], $value);
                } else {
                    throw new InvalidArgumentException("Invalid SCHEME definition for property {$property}");
                }
            } else {
                $object->{$property} = $value; // assign value to object
            }
        }

        return $object;
    }

    private static function processNestedValue(mixed $value, string $class, ?callable $filter = null): mixed
    {
        if (is_array($value)) {
            if (array_is_list($value)) {
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