<?php

declare(strict_types=1);

namespace Taydence;

use Taydence\Http\Request;
use Taydence\Http\Response;

final class Router
{
    /** @var array<string, array<int, array{path: string, regex: string, handler: callable}>> */
    private array $routes = [];

    public function get(string $path, callable|array $handler): void
    {
        $this->add('GET', $path, $handler);
    }

    public function post(string $path, callable|array $handler): void
    {
        $this->add('POST', $path, $handler);
    }

    private function add(string $method, string $path, callable|array $handler): void
    {
        $this->routes[$method][] = [
            'path' => $path,
            'regex' => $this->compile($path),
            'handler' => $handler,
        ];
    }

    public function dispatch(Request $request): Response
    {
        foreach ($this->routes[$request->method()] ?? [] as $route) {
            if (!preg_match($route['regex'], $this->normalize($request->path()), $matches)) {
                continue;
            }

            $parameters = array_filter(
                $matches,
                static fn ($key) => is_string($key),
                ARRAY_FILTER_USE_KEY
            );

            $result = $this->callHandler($route['handler'], $request, $parameters);

            return $result instanceof Response
                ? $result
                : new Response((string) $result);
        }

        return new Response('404 Not Found', 404);
    }

    private function callHandler(callable|array $handler, Request $request, array $parameters): mixed
    {
        if (is_array($handler)) {
            [$controller, $method] = $handler;
            $handler = [new $controller(), $method];
        }

        return $handler($request, ...array_values($parameters));
    }

    private function compile(string $path): string
    {
        $path = $this->normalize($path);

        $pattern = preg_replace_callback(
            '/\{([A-Za-z_][A-Za-z0-9_]*)\}/',
            static fn (array $match) => '(?P<' . $match[1] . '>[^/]+)',
            $path
        );

        return '#^' . $pattern . '/?$#';
    }

    private function normalize(string $path): string
    {
        $path = '/' . trim($path, '/');
        return $path === '//' ? '/' : $path;
    }
}
