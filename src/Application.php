<?php

declare(strict_types=1);

namespace Taydence;

use Taydence\Http\Request;
use Taydence\Http\Response;

final class Application
{
    private Router $router;

    public function __construct()
    {
        $this->router = new Router();
    }

    public function get(string $path, callable $handler): void
    {
        $this->router->get($path, $handler);
    }

    public function post(string $path, callable $handler): void
    {
        $this->router->post($path, $handler);
    }

    public function run(): void
    {
        $response = $this->router->dispatch(Request::capture());
        $response->send();
    }
}
