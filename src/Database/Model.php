<?php

declare(strict_types=1);

namespace Taydence\Database;

use RuntimeException;

abstract class Model
{
    protected string $table;
    protected string $primaryKey = 'id';

    /** @var list<string> */
    protected array $fillable = [];

    protected array $attributes = [];

    public function __construct(
        protected readonly Database $database
    ) {
        if (!isset($this->table)) {
            throw new RuntimeException('Model table must be defined.');
        }
    }

    public function query(): QueryBuilder
    {
        return $this->database->table($this->table);
    }

    public function all(): array
    {
        return array_map(
            fn (array $row) => $this->newFromRow($row),
            $this->query()->get()
        );
    }

    public function find(int|string $id): ?static
    {
        $row = $this->query()
            ->where($this->primaryKey, '=', $id)
            ->first();

        return $row === null ? null : $this->newFromRow($row);
    }

    public static function createWith(Database $database, array $attributes): static
    {
        $model = new static($database);
        $model->fill($attributes);
        $model->save();

        return $model;
    }

    public function fill(array $attributes): static
    {
        foreach ($attributes as $key => $value) {
            if ($this->fillable === [] || in_array($key, $this->fillable, true)) {
                $this->attributes[$key] = $value;
            }
        }

        return $this;
    }

    public function save(): bool
    {
        if (isset($this->attributes[$this->primaryKey])) {
            $id = $this->attributes[$this->primaryKey];
            $data = $this->attributes;
            unset($data[$this->primaryKey]);

            $this->query()
                ->where($this->primaryKey, '=', $id)
                ->update($data);

            return true;
        }

        $data = $this->attributes;
        $id = $this->query()->insert($data);
        $this->attributes[$this->primaryKey] = $id;

        return true;
    }

    public function delete(): bool
    {
        if (!isset($this->attributes[$this->primaryKey])) {
            return false;
        }

        $this->query()
            ->where($this->primaryKey, '=', $this->attributes[$this->primaryKey])
            ->delete();

        return true;
    }

    public function toArray(): array
    {
        return $this->attributes;
    }

    private function newFromRow(array $row): static
    {
        $model = new static($this->database);
        $model->attributes = $row;
        return $model;
    }
}
