<?php

declare(strict_types=1);

namespace Taydence\Tests;

use PHPUnit\Framework\TestCase;
use Taydence\Auth\Authenticator;

final class AuthenticationTest extends TestCase
{
    public function testPasswordsAreHashedAndVerified(): void
    {
        $hash = Authenticator::hashPassword('secret-password');

        $this->assertNotSame('secret-password', $hash);
        $this->assertTrue(password_verify('secret-password', $hash));
        $this->assertFalse(password_verify('wrong-password', $hash));
    }
}
