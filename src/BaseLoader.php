<?php

namespace DataLoader;

use function array_map;
use function call_user_func;
use function is_array;
use function method_exists;

class BaseLoader
{
    public function __invoke(mixed $value, Property $property)
    {
        $type = $property->type ?? null;

        if ($type?->name === Types::Object) {
            // Convert not needed
            if ($type->is_a($value)) {
                return $value;
            }

            // has fromArray method implemented
            if (method_exists($type->class, 'fromArray')) {
                return call_user_func([$type->class, 'fromArray'], $value);
            }
        }

        // Array of Objects
        if ($type?->name === Types::ArrayOfObjects && is_array($value)) {
            return array_map(
                fn($item) => new $type->class($item),
                $value,
            );
        }

        if ($type->allowNull && $value === null) {
            return null;
        }


        // Convert Value to the specified type
        return match ($type->name ?? null) {
            Types::Int => (int) $value,
            Types::Bool => (bool) $value,
            Types::String => (string) $value,
            Types::Float => (float) $value,
            Types::Array => (array) $value,
            Types::Object => new $type->class($value),
            Types::Enum => $type->allowNull ?
                call_user_func([$type->class, 'tryFrom'], $value) :
                call_user_func([$type->class, 'from'], $value),
            default => $value,
        };
    }

}
