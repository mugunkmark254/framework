<?php

declare(strict_types=1);

namespace Taydence;

use RuntimeException;
use stdClass;

final class Config
{
    public function __construct(
        private readonly array $items = []
    ) {}

    public function get(string $key, mixed $default = null): mixed
    {
        $value = $this->items;

        foreach (explode('.', $key) as $segment) {
            if (!is_array($value) || !array_key_exists($segment, $value)) {
                return $default;
            }

            $value = $value[$segment];
        }

        return $value;
    }

    public function has(string $key): bool
    {
        $missing = new stdClass();

        return $this->get($key, $missing) !== $missing;
    }

    public function all(): array
    {
        return $this->items;
    }

    public static function fromFile(string $path): self
    {
        if (!is_file($path)) {
            throw new RuntimeException("Configuration file not found: {$path}");
        }

        $config = require $path;

        if (!is_array($config)) {
            throw new RuntimeException("Configuration file must return an array: {$path}");
        }

        return new self($config);
    }
}
