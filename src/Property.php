<?php

namespace DataLoader;

use Attribute;

use function class_exists;
use function is_callable;
use function is_string;

#[Attribute(Attribute::TARGET_PROPERTY)]
final class Property
{
    /**
     * Class name of property origin.
     * @var string
     */
    public string $origin;

    /**
     * Original name of the property.
     * @var string
     */
    public string $name;

    /**
     * Property type.
     * @var Type|null
     */
    public ?Type $type = null;

    /**
     * Value loader.
     * @var callable|BaseLoader
     */
    public $loader = null;

    /**
     * @param string|null $from - Name of the key in data array.
     */
    public function __construct(
        public ?string $from = null,
        callable|string|null $loader = null,
    ) {
        $this->loader = is_callable($loader) ? $loader : null;
        $this->loader ??= is_string($loader) && class_exists($loader) ? new $loader() : null;
    }
}
