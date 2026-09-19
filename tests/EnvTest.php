<?php

declare(strict_types=1);

namespace Taydence\Tests;

use PHPUnit\Framework\TestCase;
use Taydence\Env;

final class EnvTest extends TestCase
{
    public function testEnvironmentValuesAreLoadedAndCast(): void
    {
        $path = tempnam(sys_get_temp_dir(), 'taydence-env-');
        file_put_contents(
            $path,
            "APP_NAME=\"Taydence Framework\"\nAPP_DEBUG=true\nPORT=3306\nEMPTY=null\n"
        );

        try {
            $env = Env::fromFile($path);

            $this->assertSame('Taydence Framework', $env->get('APP_NAME'));
            $this->assertTrue($env->get('APP_DEBUG'));
            $this->assertSame(3306, $env->get('PORT'));
            $this->assertNull($env->get('EMPTY'));
        } finally {
            unlink($path);
        }
    }
}
