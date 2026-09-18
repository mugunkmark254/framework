<?php

declare(strict_types=1);

use Taydence\Env;

if (!function_exists('env')) {
    function env(string $key, mixed $default = null): mixed
    {
        static $environment;

        if ($environment === null) {
            $path = dirname(__DIR__) . '/.env';

            $environment = is_file($path)
                ? Env::fromFile($path)
                : new Env();
        }

        return $environment->get($key, $default);
    }
}
