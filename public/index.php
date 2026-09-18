<?php

declare(strict_types=1);

require dirname(__DIR__) . '/vendor/autoload.php';

use Taydence\Application;
use Taydence\Config;
use Taydence\Controllers\HomeController;
use Taydence\Http\Request;
use Taydence\Http\Response;
use Taydence\Middleware\PoweredByMiddleware;

$config = Config::fromFile(dirname(__DIR__) . '/config/app.php');
$app = new Application($config);

$app->middleware(new PoweredByMiddleware());

$app->get('/', [HomeController::class, 'index']);
$app->get('/about', fn () => 'A tiny PHP framework built from scratch.');
$app->get('/hello', function (Request $request) {
    return 'Hello ' . ($request->query('name') ?? 'there');
});
$app->get('/hello/{name}', fn (Request $request, string $name) => 'Hello ' . $name);
$app->get('/users/{id}', fn (Request $request, string $id) => Response::json([
    'id' => $id,
]));

$app->run();
