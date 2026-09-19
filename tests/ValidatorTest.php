<?php

declare(strict_types=1);

namespace Taydence\Tests;

use PHPUnit\Framework\TestCase;
use Taydence\Validation\ValidationException;
use Taydence\Validation\Validator;

final class ValidatorTest extends TestCase
{
    public function testValidDataPasses(): void
    {
        $validator = new Validator([
            'email' => 'mark@example.com',
            'age' => 24,
        ]);

        $this->assertSame(
            ['email' => 'mark@example.com', 'age' => 24],
            $validator->validate([
                'email' => 'required|email',
                'age' => 'required|integer|min:18',
            ])
        );
    }

    public function testInvalidDataThrowsValidationException(): void
    {
        $this->expectException(ValidationException::class);

        (new Validator(['email' => 'bad']))->validate([
            'email' => 'required|email',
        ]);
    }
}
