<?php

declare(strict_types=1);

namespace Taydence\Tests;

use PHPUnit\Framework\TestCase;
use Taydence\Http\Request;
use Taydence\Router;

final class RouterTest extends TestCase
{
    public function testStaticRegexCharactersAreMatchedLiterally(): void
    {
        $router = new Router();
        $router->get('/docs/v1.0', fn () => 'ok');

        $response = $router->dispatch(new Request('GET', '/docs/v1.0'));

        $this->assertSame(200, $response->status());
        $this->assertSame('ok', $response->content());
    }

    public function testRouteParametersAreResolved(): void
    {
        $router = new Router();
        $router->get('/users/{id}', fn (Request $request, string $id) => $id);

        $response = $router->dispatch(new Request('GET', '/users/42'));

        $this->assertSame('42', $response->content());
    }
}
