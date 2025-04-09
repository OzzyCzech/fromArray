<?php

namespace DataLoader;

use ArrayObject;
use ReflectionClass;
use ReflectionException;
use ReflectionNamedType;
use ReflectionProperty;

use function current;
use function var_dump;

/**
 * Metadata class for storing property information
 */
class Metadata extends ArrayObject
{
    private static ?self $instance = null;

    public function __construct(string ...$classes)
    {
        $this->resolve(...$classes);
    }

    /**
     * Resolve the metadata for the classes
     *
     * @param string[] $classes
     * @throws ReflectionException
     */
    public function resolve(string ...$classes): void
    {
        foreach ($classes as $class) {
            $reflection = new ReflectionClass($class);
            $classLoader = $this->getClassLoader($reflection);

            $properties = [];
            foreach ($reflection->getProperties(filter: ReflectionProperty::IS_PUBLIC) as $reflectionProperty) {
                $property = current($reflectionProperty->getAttributes(Property::class));
                $property = $property ? $property->newInstance() : new Property(from: $reflectionProperty->getName());

                // Set the property name and from attributes
                $property->origin = $class;
                $property->name ??= $reflectionProperty->getName();
                $property->from ??= $reflectionProperty->getName();
                $property->type ??= $this->getPropertyType($reflectionProperty);

                // resolve value loader
                $property->loader ??= $this->getPropertyLoader($reflectionProperty) ?? $classLoader;

                // save the property
                $properties[$property->name] = $property;
            }
            $this->offsetSet($reflection->getName(), $properties);
        }
    }

    /**
     * Try to resolve the class loader
     * @param ReflectionClass $reflection
     * @return callable
     */
    private function getClassLoader(ReflectionClass $reflection): callable
    {
        $classLoader = current($reflection->getAttributes(Loader::class));
        return $classLoader ? $classLoader->newInstance() : new BaseLoader();
    }

    /**
     * Try to resolve the property loader
     * @param ReflectionProperty $reflectionProperty
     * @return callable|null
     */
    private function getPropertyLoader(ReflectionProperty $reflectionProperty): ?callable
    {
        if ($filter = current($reflectionProperty->getAttributes(Loader::class))) {
            return $filter->newInstance();
        }
        return null;
    }

    /**
     * Try to resolve the property type
     *
     * @param ReflectionProperty $reflection
     * @return Type|null
     */
    private function getPropertyType(ReflectionProperty $reflection): ?Type
    {
        if ($type = current($reflection->getAttributes(Type::class))) {
            return $type->newInstance();
        }

        if ($reflection->getType() instanceof ReflectionNamedType) {
            $name = $reflection->getType()->getName();
            $allowNull = $reflection->getType()->allowsNull();

            // build in types
            if ($reflection->getType()->isBuiltin()) {
                return new Type(
                    name: Types::tryFrom($name) ?? trigger_error('Invalid type: ' . $name),
                    allowNull: $allowNull,
                );
            }

            // enum
            if (enum_exists($name)) {
                return new Type(name: Types::Enum, allowNull: $allowNull, class: $name);
            }

            // class
            if (class_exists($name)) {
                return new Type(name: Types::Object, allowNull: $allowNull, class: $name);
            }
        }

        return null;
    }


    /**
     * Return the properties for the class
     *
     * @param string $documentClass
     * @return array<Property>
     * @throws ReflectionException
     */
    public static function getProperties(string $documentClass): array
    {
        return self::getInstance()->offsetGet($documentClass) ?: [];
    }


    /**
     * @param mixed $key
     * @return mixed
     * @throws ReflectionException
     */
    public function offsetGet(mixed $key): mixed
    {
        if (!$this->offsetExists($key)) {
            $this->resolve($key);
        }
        return parent::offsetGet($key);
    }

    /**
     * Set the metadata resolver instance (e.g. from cache)
     *
     * @retur self
     * @param self $resolver
     * @return Metadata
     */
    public static function setInstance(self $resolver): self
    {
        return self::$instance = $resolver;
    }


    /**
     * Get the metadata resolver instance
     *
     * @param string ...$classes
     * @return static
     */
    public static function getInstance(string ...$classes): static
    {
        if (!self::$instance) {
            self::$instance = new static(...$classes);
        }
        return self::$instance;
    }

}
