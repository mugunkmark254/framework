<?php

declare(strict_types=1);

namespace Taydence\Controllers;

use Taydence\Http\Response;

final class HomeController
{
    public function index(): Response
    {
        return new Response('Welcome to Taydence Framework v1.0');
    }

    public function hello(): Response
    {
        return new Response('Hello from HomeController.');
    }
}
