<?php

declare(strict_types=1);

namespace Taydence\Http;

final class MiddlewarePipeline
{
    /** @param list<MiddlewareInterface|callable> $middleware */
    public function __construct(
        private readonly array $middleware = []
    ) {}

    public function handle(Request $request, callable $destination): Response
    {
        $next = $destination;

        foreach (array_reverse($this->middleware) as $middleware) {
            $next = function (Request $request) use ($middleware, $next): Response {
                if ($middleware instanceof MiddlewareInterface) {
                    return $middleware->handle($request, $next);
                }

                $response = $middleware($request, $next);

                return $response instanceof Response
                    ? $response
                    : new Response((string) $response);
            };
        }

        return $next($request);
    }
}
