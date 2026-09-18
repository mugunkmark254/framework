<?php

declare(strict_types=1);

namespace Taydence;

use Taydence\Http\MiddlewareInterface;
use Taydence\Http\MiddlewarePipeline;
use Taydence\Http\Request;

final class Application
{
    private Router $router;
    private Container $container;

    /** @var list<MiddlewareInterface|callable> */
    private array $middleware = [];

    public function __construct()
    {
        $this->container = new Container();
        $this->router = new Router($this->container);
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

    public function bind(string $abstract, callable $factory): void
    {
        $this->container->bind($abstract, $factory);
    }

    public function singleton(string $abstract, callable $factory): void
    {
        $this->container->singleton($abstract, $factory);
    }

    public function instance(string $abstract, mixed $instance): void
    {
        $this->container->instance($abstract, $instance);
    }

    public function make(string $class): mixed
    {
        return $this->container->make($class);
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
