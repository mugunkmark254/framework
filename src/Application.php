<?php

declare(strict_types=1);

namespace Taydence;

use Taydence\Http\MiddlewareInterface;
use Taydence\Http\MiddlewarePipeline;
use Taydence\Http\Request;

final class Application
{
    private Router $router;

    /** @var list<MiddlewareInterface|callable> */
    private array $middleware = [];

    public function __construct()
    {
        $this->router = new Router();
    }

    public function get(string $path, callable|array $handler): void
    {
        $this->router->get($path, $handler);
    }

    public function post(string $path, callable|array $handler): void
    {
        $this->router->post($path, $handler);
    }

    public function middleware(MiddlewareInterface|callable $middleware): void
    {
        $this->middleware[] = $middleware;
    }

    public function run(): void
    {
        $request = Request::capture();

        $response = (new MiddlewarePipeline($this->middleware))->handle(
            $request,
            fn (Request $request) => $this->router->dispatch($request)
        );

        $response->send();
    }
}
