<?php

declare(strict_types=1);

namespace Taydence\Tests;

use PHPUnit\Framework\TestCase;
use Taydence\Container;

final class ContainerTest extends TestCase
{
    public function testSingletonIsShared(): void
    {
        $container = new Container();

        $container->singleton(stdClass::class, fn () => new stdClass());

        $this->assertSame(
            $container->make(stdClass::class),
            $container->make(stdClass::class)
        );
    }
}
