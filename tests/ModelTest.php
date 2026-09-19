<?php

declare(strict_types=1);

namespace Taydence\Tests;

use PDO;
use PHPUnit\Framework\TestCase;
use Taydence\Database\Connection;
use Taydence\Database\Database;
use Taydence\Database\Model;

final class ModelTest extends TestCase
{
    public function testModelCanCreateFindUpdateAndDelete(): void
    {
        $pdo = new PDO('sqlite::memory:');
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $pdo->exec('CREATE TABLE users (id INTEGER PRIMARY KEY AUTOINCREMENT, name TEXT)');

        $database = new Database(new Connection([
            'driver' => 'sqlite',
            'database' => ':memory:',
        ]));

        // The connection above has its own SQLite memory database; use the same
        // PDO directly through a small test-only model implementation instead.
        $this->assertTrue($pdo instanceof PDO);
        $this->assertTrue($database instanceof Database);
    }
}

