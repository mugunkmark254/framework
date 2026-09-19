<?php

declare(strict_types=1);

namespace Taydence\Http;

use Taydence\Validation\ValidationException;
use Throwable;

final class ErrorHandler
{
    public function __construct(
        private readonly bool $debug = false
    ) {}

    public function render(Throwable $exception, Request $request): Response
    {
        if ($exception instanceof ValidationException) {
            return Response::json([
                'message' => $exception->getMessage(),
                'errors' => $exception->errors(),
            ], 422);
        }

        $status = 500;
        $message = $this->debug
            ? $exception->getMessage()
            : 'Internal Server Error';

        if ($request->expectsJson()) {
            return Response::json([
                'message' => $message,
            ], $status);
        }

        $body = $this->debug
            ? '<h1>Application Error</h1><p>' . htmlspecialchars($message, ENT_QUOTES, 'UTF-8') . '</p>'
            : '<h1>500 Internal Server Error</h1>';

        return new Response($body, $status);
    }
}
