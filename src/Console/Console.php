<?php

declare(strict_types=1);

namespace Taydence\Console;

use RuntimeException;

final class Console
{
    public function run(array $arguments): int
    {
        $command = $arguments[1] ?? 'help';

        return match ($command) {
            'help' => $this->help(),
            'make:controller' => $this->makeController($arguments[2] ?? null),
            'make:model' => $this->makeModel($arguments[2] ?? null),
            default => $this->unknown($command),
        };
    }

    private function makeController(?string $name): int
    {
        if ($name === null || !preg_match('/^[A-Z][A-Za-z0-9]*$/', $name)) {
            fwrite(STDERR, "Usage: php bin/taydence make:controller UserController
");
            return 1;
        }

        if (!str_ends_with($name, 'Controller')) {
            $name .= 'Controller';
        }

        $path = dirname(__DIR__) . '/Controllers/' . $name . '.php';
        $content = "<?php\n\ndeclare(strict_types=1);\n\nnamespace Taydence\\Controllers;\n\nuse Taydence\\Http\\Response;\n\nfinal class {$name}\n{\n    public function index(): Response\n    {\n        return new Response('{$name}');\n    }\n}\n";

        return $this->write($path, $content);
    }

    private function makeModel(?string $name): int
    {
        if ($name === null || !preg_match('/^[A-Z][A-Za-z0-9]*$/', $name)) {
            fwrite(STDERR, "Usage: php bin/taydence make:model User
");
            return 1;
        }

        $table = strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', $name));
        $path = dirname(__DIR__) . '/Models/' . $name . '.php';
        $content = "<?php\n\ndeclare(strict_types=1);\n\nnamespace Taydence\\Models;\n\nuse Taydence\\Database\\Model;\n\nfinal class {$name} extends Model\n{\n    protected string \\$table = '{$table}s';\n}\n";

        return $this->write($path, $content);
    }

    private function write(string $path, string $content): int
    {
        if (is_file($path)) {
            fwrite(STDERR, "File already exists: {$path}
");
            return 1;
        }

        if (!is_dir(dirname($path))) {
            mkdir(dirname($path), 0777, true);
        }

        file_put_contents($path, $content);
        fwrite(STDOUT, "Created {$path}
");

        return 0;
    }

    private function help(): int
    {
        fwrite(STDOUT, "Taydence Framework CLI

");
        fwrite(STDOUT, "Commands:
");
        fwrite(STDOUT, "  make:controller Name   Create a controller
");
        fwrite(STDOUT, "  make:model Name        Create a model
");

        return 0;
    }

    private function unknown(string $command): int
    {
        fwrite(STDERR, "Unknown command: {$command}
");
        return 1;
    }
}
