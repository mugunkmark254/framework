<?php

declare(strict_types=1);

namespace Taydence\Middleware;

use Taydence\Http\MiddlewareInterface;
use Taydence\Http\Request;
use Taydence\Http\Response;

final class PoweredByMiddleware implements MiddlewareInterface
{
    public function handle(Request $request, callable $next): Response
    {
        return $next($request)->withHeader('X-Powered-By', 'Taydence Framework');
    }
}
