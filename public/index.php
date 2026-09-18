<?php

declare(strict_types=1);

require dirname(__DIR__) . '/vendor/autoload.php';

use Taydence\Application;
use Taydence\Http\Request;

$app = new Application();

$app->get('/', fn () => 'Hello from Taydence Framework!');
$app->get('/about', fn () => 'A tiny PHP framework built from scratch.');
$app->get('/hello', function (Request $request) {
    return 'Hello ' . ($request->query('name') ?? 'there');
});

$app->run();
