<?php

namespace DataLoader;

use Attribute;

use function class_exists;
use function is_callable;

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
        $this->callback = is_callable($callback) ? $callback : null;
        $this->callback ??= is_string($callback) && class_exists($callback) ? new $callback() : null;
    }

    public function __invoke(mixed $value, Property $property): mixed
    {
        return ($this->callback)($value, $property);
    }
}
