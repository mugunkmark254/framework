<?php

declare(strict_types=1);

namespace Taydence;

use ReflectionClass;
use ReflectionException;
use ReflectionNamedType;
use ReflectionParameter;
use RuntimeException;

final class Container
{
    /** @var array<string, mixed> */
    private array $instances = [];

    /** @var array<string, callable> */
    private array $bindings = [];

    public function bind(string $abstract, callable $factory): void
    {
        $this->bindings[$abstract] = $factory;
    }

    public function singleton(string $abstract, callable $factory): void
    {
        $this->bindings[$abstract] = function () use ($abstract, $factory): mixed {
            if (!array_key_exists($abstract, $this->instances)) {
                $this->instances[$abstract] = $factory($this);
            }

            return $this->instances[$abstract];
        };
    }

    public function instance(string $abstract, mixed $instance): void
    {
        $this->instances[$abstract] = $instance;
    }

    public function make(string $class): mixed
    {
        if (array_key_exists($class, $this->instances)) {
            return $this->instances[$class];
        }

        if (isset($this->bindings[$class])) {
            return ($this->bindings[$class])($this);
        }

        try {
            $reflection = new ReflectionClass($class);
        } catch (ReflectionException $exception) {
            throw new RuntimeException("Cannot resolve [{$class}].", 0, $exception);
        }

        if (!$reflection->isInstantiable()) {
            throw new RuntimeException("Class [{$class}] is not instantiable.");
        }

        $constructor = $reflection->getConstructor();

        if ($constructor === null) {
            return new $class();
        }

        $arguments = [];

        foreach ($constructor->getParameters() as $parameter) {
            $arguments[] = $this->resolveParameter($parameter);
        }

        return $reflection->newInstanceArgs($arguments);
    }

    private function resolveParameter(ReflectionParameter $parameter): mixed
    {
        $type = $parameter->getType();

        if ($type instanceof ReflectionNamedType && !$type->isBuiltin()) {
            return $this->make($type->getName());
        }

        if ($parameter->isDefaultValueAvailable()) {
            return $parameter->getDefaultValue();
        }

        throw new RuntimeException(
            "Unable to resolve parameter [${$parameter->getName()}]."
        );
    }
}
