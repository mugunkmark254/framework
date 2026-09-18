<?php

declare(strict_types=1);

namespace Taydence;

use RuntimeException;

final class Env
{
    public function __construct(
        private readonly array $values = []
    ) {}

    public function get(string $key, mixed $default = null): mixed
    {
        return $this->values[$key] ?? $default;
    }

    public function has(string $key): bool
    {
        return array_key_exists($key, $this->values);
    }

    public static function fromFile(string $path): self
    {
        if (!is_file($path)) {
            throw new RuntimeException("Environment file not found: {$path}");
        }

        $values = [];

        foreach (file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $line) {
            $line = trim($line);

            if ($line === '' || str_starts_with($line, '#')) {
                continue;
            }

            [$key, $value] = array_pad(explode('=', $line, 2), 2, '');
            $key = trim($key);
            $value = trim($value);

            if ($key === '') {
                continue;
            }

            $values[$key] = self::cast($value);
        }

        return new self($values);
    }

    private static function cast(string $value): mixed
    {
        if (
            (str_starts_with($value, '"') && str_ends_with($value, '"')) ||
            (str_starts_with($value, "'") && str_ends_with($value, "'"))
        ) {
            return substr($value, 1, -1);
        }

        return match (strtolower($value)) {
            'true' => true,
            'false' => false,
            'null' => null,
            default => is_numeric($value) ? (str_contains($value, '.') ? (float) $value : (int) $value) : $value,
        };
    }
}
