<?php

declare(strict_types=1);

namespace Taydence\Testing;

use RuntimeException;
use Taydence\Http\Response;

final class TestResponse
{
    public function __construct(
        private readonly Response $response
    ) {}

    public function assertStatus(int $status): self
    {
        if ($this->response->status() !== $status) {
            throw new RuntimeException("Expected status {$status}, got {$this->response->status()}.");
        }

        return $this;
    }

    public function assertSee(string $text): self
    {
        if (!str_contains($this->response->content(), $text)) {
            throw new RuntimeException("Expected response to contain [{$text}].");
        }

        return $this;
    }

    public function json(): array
    {
        $decoded = json_decode($this->response->content(), true);

        if (!is_array($decoded)) {
            throw new RuntimeException('Response does not contain a JSON object.');
        }

        return $decoded;
    }

    public function response(): Response
    {
        return $this->response;
    }
}
