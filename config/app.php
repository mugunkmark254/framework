<?php

declare(strict_types=1);

return [
    'name' => env('APP_NAME', 'Taydence Framework'),
    'env' => env('APP_ENV', 'production'),
    'debug' => env('APP_DEBUG', false),
    'auth' => [
        'table' => env('AUTH_TABLE', 'users'),
        'identifier' => env('AUTH_IDENTIFIER', 'email'),
    ],
    'database' => [
        'driver' => env('DB_CONNECTION', 'mysql'),
        'host' => env('DB_HOST', 'localhost'),
        'port' => env('DB_PORT', 3306),
        'database' => env('DB_DATABASE', ''),
        'username' => env('DB_USERNAME', ''),
        'password' => env('DB_PASSWORD', ''),
        'charset' => env('DB_CHARSET', 'utf8mb4'),
    ],
];
