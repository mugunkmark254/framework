<?php

declare(strict_types=1);

namespace Taydence\Tests;

use PHPUnit\Framework\TestCase;
use Taydence\Http\ErrorHandler;
use Taydence\Http\Request;
use Taydence\Validation\ValidationException;
use RuntimeException;

final class ErrorHandlerTest extends TestCase
{
    public function testValidationErrorsReturn422Json(): void
    {
        $request = new Request('POST', '/users');
        $exception = new ValidationException(['email' => ['Invalid email.']]);

        $response = (new ErrorHandler())->render($exception, $request);

        $this->assertSame(422, $response->status());
        $this->assertStringContainsString('Invalid email.', $response->content());
    }

    public function testProductionErrorsHideExceptionMessage(): void
    {
        $request = new Request('GET', '/');
        $exception = new RuntimeException('secret internal detail');

        $response = (new ErrorHandler(false))->render($exception, $request);

        $this->assertSame(500, $response->status());
        $this->assertStringNotContainsString('secret internal detail', $response->content());
    }
}
