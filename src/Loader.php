<?php

declare(strict_types=1);

namespace DataLoader;

use Attribute;

#[Attribute(Attribute::TARGET_PROPERTY | Attribute::TARGET_CLASS)]
final class Loader
{
    /**
     * The callable to be used for loading the property value.
     * @var callable
     */
    private $callback;

    public function __construct(callable|string $callback)
    {
        $this->callback = self::resolveCallback($callback);
    }

    public function __invoke(mixed $value, Property $property): mixed
    {
        return ($this->callback)($value, $property);
    }

    /**
     * Resolve a callable from a callable or class name string.
     */
    public static function resolveCallback(callable|string $callback): callable
    {
        if (is_callable($callback)) {
            return $callback;
        }

        if (is_string($callback) && class_exists($callback)) {
            return new $callback();
        }

        throw new \InvalidArgumentException('Invalid callback: ' . $callback);
    }
}
