<?php

declare(strict_types=1);

namespace Taydence\Http;

final class Request
{
    public function __construct(
        private readonly string $method,
        private readonly string $path,
        private readonly array $query = [],
        private readonly array $body = [],
    ) {}

    public static function capture(): self
    {
        $uri = $_SERVER['REQUEST_URI'] ?? '/';

        return new self(
            strtoupper($_SERVER['REQUEST_METHOD'] ?? 'GET'),
            parse_url($uri, PHP_URL_PATH) ?: '/',
            $_GET,
            $_POST
        );
    }

    public function method(): string
    {
        return $this->method;
    }

    public function path(): string
    {
        return $this->path;
    }

    public function query(?string $key = null): mixed
    {
        return $key === null ? $this->query : ($this->query[$key] ?? null);
    }

    public function input(?string $key = null): mixed
    {
        return $key === null ? $this->body : ($this->body[$key] ?? null);
    }
}

