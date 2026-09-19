<?php

declare(strict_types=1);

namespace Taydence;

use Taydence\Auth\Authenticator;
use Taydence\Database\Connection;
use Taydence\Database\Database;
use Taydence\Http\MiddlewareInterface;
use Taydence\Http\MiddlewarePipeline;
use Taydence\Http\ErrorHandler;
use Taydence\Http\Request;

final class Application
{
    private Router $router;
    private Container $container;
    private Config $config;
    private Env $env;

    /** @var list<MiddlewareInterface|callable> */
    private array $middleware = [];

    public function __construct(?Config $config = null, ?Env $env = null)
    {
        $this->container = new Container();
        $this->env = $env ?? new Env();
        $this->container->instance(Env::class, $this->env);
        $this->config = $config ?? new Config();
        $this->container->instance(Config::class, $this->config);
        $this->container->singleton(Database::class, function (Container $container): Database {
            $config = $container->make(Config::class);
            return new Database(new Connection($config->get('database', [])));
        });
        $this->router = new Router($this->container);
        $this->container->singleton(Authenticator::class, fn (Container $container): Authenticator => new Authenticator(
            $container->make(Database::class),
            $this->config->get('auth.table', 'users'),
            $this->config->get('auth.identifier', 'email')
        ));
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

    public function config(string $key, mixed $default = null): mixed
    {
        return $this->config->get($key, $default);
    }

    public function env(string $key, mixed $default = null): mixed
    {
        return $this->env->get($key, $default);
    }

    public function validate(array $data, array $rules): array
    {
        return (new \Taydence\Validation\Validator($data))->validate($rules);
    }

    public function auth(): \Taydence\Auth\Authenticator
    {
        return $this->container->make(\Taydence\Auth\Authenticator::class);
    }

    public function handle(Request $request): \Taydence\Http\Response
    {
        try {
            return (new MiddlewarePipeline($this->middleware))->handle(
                $request,
                fn (Request $request) => $this->router->dispatch($request)
            );
        } catch (\Throwable $exception) {
            return (new ErrorHandler((bool) $this->config('debug', false)))->render($exception, $request);
        }
    }

    public function run(): void
    {
        $request = Request::capture();
        $this->handle($request)->send();
    }
}
