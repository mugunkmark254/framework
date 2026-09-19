<?php

declare(strict_types=1);

namespace Taydence\Tests;

use PDO;
use PHPUnit\Framework\TestCase;
use Taydence\Database\QueryBuilder;

final class QueryBuilderTest extends TestCase
{
    private PDO $pdo;

    protected function setUp(): void
    {
        $this->pdo = new PDO('sqlite::memory:');
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $this->pdo->exec('CREATE TABLE users (id INTEGER PRIMARY KEY AUTOINCREMENT, name TEXT, email TEXT)');
    }

    public function testCrudOperationsWork(): void
    {
        $query = new QueryBuilder($this->pdo, 'users');

        $id = $query->insert([
            'name' => 'Mark',
            'email' => 'mark@example.com',
        ]);

        $row = $query->where('id', '=', $id)->first();

        $this->assertSame('Mark', $row['name']);

        $updated = $query->where('id', '=', $id)->update(['name' => 'Mugun']);
        $this->assertSame(1, $updated);

        $this->assertSame('Mugun', $query->where('id', '=', $id)->first()['name']);

        $deleted = $query->where('id', '=', $id)->delete();
        $this->assertSame(1, $deleted);
        $this->assertNull($query->where('id', '=', $id)->first());
    }
}
