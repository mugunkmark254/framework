<?php

declare(strict_types=1);

namespace Taydence\Database;

use PDO;

final class Database
{
    public function __construct(
        private readonly Connection $connection
    ) {}

    public function pdo(): PDO
    {
        return $this->connection->pdo();
    }

    public function table(string $table): QueryBuilder
    {
        return new QueryBuilder($this->pdo(), $table);
    }

    public function transaction(callable $callback): mixed
    {
        $pdo = $this->pdo();
        $pdo->beginTransaction();

        try {
            $result = $callback($this);
            $pdo->commit();
            return $result;
        } catch (\Throwable $exception) {
            if ($pdo->inTransaction()) {
                $pdo->rollBack();
            }

            throw $exception;
        }
    }
}
