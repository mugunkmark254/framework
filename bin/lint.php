<?php

declare(strict_types=1);

$roots = [
    dirname(__DIR__) . '/src',
    dirname(__DIR__) . '/tests',
];

$files = [];

foreach ($roots as $root) {
    $iterator = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($root, FilesystemIterator::SKIP_DOTS)
    );

    foreach ($iterator as $file) {
        if ($file->isFile() && $file->getExtension() === 'php') {
            $files[] = $file->getPathname();
        }
    }
}

sort($files);

$failed = false;

foreach ($files as $file) {
    $command = PHP_BINARY . ' -l ' . escapeshellarg($file);
    passthru($command, $exitCode);

    if ($exitCode !== 0) {
        $failed = true;
    }
}

exit($failed ? 1 : 0);
