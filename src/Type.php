<?php

namespace DataLoader;

use Attribute;

use function is_a;

#[Attribute(Attribute::TARGET_PROPERTY)]
final class Type
{
    public function __construct(
        public ?Types $name = null,
        public ?bool $allowNull = null,
        public ?string $class = null,
    ) {}

    /**
     * Check if the type is compatible with the given object or class
     *
     * @param mixed $object_or_class
     * @return bool
     */
    public function is_a(mixed $object_or_class): bool
    {
        return $this->name === Types::Object && is_a($object_or_class, $this->class, true);
    }
}

