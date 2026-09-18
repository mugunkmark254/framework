<?php

declare(strict_types=1);

namespace Taydence;

use Taydence\Http\Request;
use Taydence\Http\Response;

final class Router
{
    /** @var array<string, array<string, callable>> */
    private array $routes = [];

    public function get(string $path, callable $handler): void
    {
        $this->add('GET', $path, $handler);
    }

    public function post(string $path, callable $handler): void
    {
        $this->add('POST', $path, $handler);
    }

    private function add(string $method, string $path, callable $handler): void
    {
        $this->routes[$method][$this->normalize($path)] = $handler;
    }

    public function dispatch(Request $request): Response
    {
        $handler = $this->routes[$request->method()][$this->normalize($request->path())] ?? null;

        if ($handler === null) {
            return new Response('404 Not Found', 404);
        }

        $result = $handler($request);

        return $result instanceof Response
            ? $result
            : new Response((string) $result);
    }

    private function normalize(string $path): string
    {
        $path = '/' . trim($path, '/');
        return $path === '//' ? '/' : $path;
    }
}
