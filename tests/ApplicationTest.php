<?php

declare(strict_types=1);

namespace Taydence\Tests;

use PHPUnit\Framework\TestCase;
use Taydence\Application;
use Taydence\Http\Request;

final class ApplicationTest extends TestCase
{
    public function testApplicationCanHandleARequestWithoutAWebServer(): void
    {
        $app = new Application();
        $app->get('/hello/{name}', fn (Request $request, string $name) => "Hello {$name}");

        $response = $app->handle(new Request('GET', '/hello/Mark'));

        $this->assertSame(200, $response->status());
        $this->assertSame('Hello Mark', $response->content());
    }
}
