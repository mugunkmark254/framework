<?php

declare(strict_types=1);

namespace Taydence\Auth;

use Taydence\Http\MiddlewareInterface;
use Taydence\Http\Request;
use Taydence\Http\Response;

final class AuthMiddleware implements MiddlewareInterface
{
    public function __construct(
        private readonly Authenticator $auth
    ) {}

    public function handle(Request $request, callable $next): Response
    {
        if (!$this->auth->check()) {
            return Response::json(['message' => 'Unauthenticated.'], 401);
        }

        return $next($request);
    }
}
