<?php

namespace DataLoader;

use Attribute;

#[Attribute(Attribute::TARGET_PROPERTY)]
final class Property
{
    public string $name;
    public ?Type $type = null;

    public function __construct(
        public ?string $from = null,
        public bool $isPrimary = false,
    ) {}
}