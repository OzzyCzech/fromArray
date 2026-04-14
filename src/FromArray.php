<?php

declare(strict_types=1);

namespace DataLoader;

/**
 * Loading properties values to \stdClass type objects
 *
 * @package DataLoader
 */
trait FromArray
{
    public static function fromArray(array $data = []): static
    {
        $object = new static();
        foreach (Metadata::getProperties(static::class) as $property) {
            $key = $property->from ?? $property->name;

            if (!array_key_exists($key, $data)) {
                continue; // skip missing data
            }

            $object->{$property->name} = ($property->loader)($data[$key], $property);
        }

        return $object;
    }
}
