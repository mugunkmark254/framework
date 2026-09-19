<?php

declare(strict_types=1);

namespace Taydence\Tests;

use PHPUnit\Framework\TestCase;
use Taydence\Database\Connection;
use Taydence\Database\Database;
use Taydence\Database\Model;

final class ModelTest extends TestCase
{
    public function testModelCanCreateFindUpdateAndDelete(): void
    {
        $path = tempnam(sys_get_temp_dir(), 'taydence-db-');
        $database = new Database(new Connection([
            'driver' => 'sqlite',
            'database' => $path,
        ]));

        try {
            $database->pdo()->exec('CREATE TABLE users (id INTEGER PRIMARY KEY AUTOINCREMENT, name TEXT)');

            $user = User::createWith($database, ['name' => 'Mark']);

            $this->assertSame('Mark', $user->toArray()['name']);
            $this->assertNotNull($user->toArray()['id']);

            $found = $user->find($user->toArray()['id']);
            $this->assertNotNull($found);

            $found->fill(['name' => 'Mugun'])->save();
            $this->assertSame('Mugun', $found->toArray()['name']);

            $this->assertTrue($found->delete());
            $this->assertNull($found->find($user->toArray()['id']));
        } finally {
            if (is_file($path)) {
                unlink($path);
            }
        }
    }
}

final class User extends Model
{
    protected string $table = 'users';

    protected array $fillable = ['name'];
}
