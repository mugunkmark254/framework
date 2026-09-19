<?php

declare(strict_types=1);

namespace Taydence\Database;

use PDO;

final class QueryBuilder
{
    private array $columns = ['*'];
    private array $wheres = [];
    private array $bindings = [];
    private ?int $limitValue = null;
    private ?int $offsetValue = null;
    private array $orders = [];

    public function __construct(
        private readonly PDO $pdo,
        private readonly string $table
    ) {}

    public function select(string ...$columns): self
    {
        $this->columns = $columns ?: ['*'];
        return $this;
    }

    public function where(string $column, string $operator, mixed $value): self
    {
        $placeholder = ':w' . count($this->bindings);
        $this->wheres[] = "{$column} {$operator} {$placeholder}";
        $this->bindings[$placeholder] = $value;
        return $this;
    }

    public function orderBy(string $column, string $direction = 'asc'): self
    {
        $direction = strtolower($direction) === 'desc' ? 'DESC' : 'ASC';
        $this->orders[] = "{$column} {$direction}";
        return $this;
    }

    public function limit(int $limit): self
    {
        $this->limitValue = max(0, $limit);
        return $this;
    }

    public function offset(int $offset): self
    {
        $this->offsetValue = max(0, $offset);
        return $this;
    }

    public function get(): array
    {
        [$sql, $bindings] = $this->selectSql();
        $statement = $this->pdo->prepare($sql);
        $statement->execute($bindings);
        return $statement->fetchAll();
    }

    public function first(): ?array
    {
        $this->limit(1);
        $rows = $this->get();
        return $rows[0] ?? null;
    }

    public function insert(array $data): int
    {
        $columns = array_keys($data);
        $placeholders = array_map(
            static fn (string $column) => ':' . $column,
            $columns
        );

        $sql = sprintf(
            'INSERT INTO %s (%s) VALUES (%s)',
            $this->table,
            implode(', ', $columns),
            implode(', ', $placeholders)
        );

        $statement = $this->pdo->prepare($sql);
        $statement->execute($data);

        return (int) $this->pdo->lastInsertId();
    }

    public function update(array $data): int
    {
        $sets = [];
        $bindings = $this->bindings;

        foreach ($data as $column => $value) {
            $placeholder = ':set_' . $column;
            $sets[] = "{$column} = {$placeholder}";
            $bindings[$placeholder] = $value;
        }

        $sql = 'UPDATE ' . $this->table . ' SET ' . implode(', ', $sets) . $this->whereSql();
        $statement = $this->pdo->prepare($sql);
        $statement->execute($bindings);

        return $statement->rowCount();
    }

    public function delete(): int
    {
        $sql = 'DELETE FROM ' . $this->table . $this->whereSql();
        $statement = $this->pdo->prepare($sql);
        $statement->execute($this->bindings);
        return $statement->rowCount();
    }

    private function selectSql(): array
    {
        $sql = 'SELECT ' . implode(', ', $this->columns) . ' FROM ' . $this->table;
        $sql .= $this->whereSql();

        if ($this->orders !== []) {
            $sql .= ' ORDER BY ' . implode(', ', $this->orders);
        }

        if ($this->limitValue !== null) {
            $sql .= ' LIMIT ' . $this->limitValue;
        }

        if ($this->offsetValue !== null) {
            $sql .= ' OFFSET ' . $this->offsetValue;
        }

        return [$sql, $this->bindings];
    }

    private function whereSql(): string
    {
        return $this->wheres === []
            ? ''
            : ' WHERE ' . implode(' AND ', $this->wheres);
    }
}
