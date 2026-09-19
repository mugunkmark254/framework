<?php

declare(strict_types=1);

namespace Taydence\Database;

use PDO;
use PDOException;
use RuntimeException;

final class Connection
{
    private ?PDO $pdo = null;

    public function __construct(
        private readonly array $config
    ) {}

    public function pdo(): PDO
    {
        if ($this->pdo !== null) {
            return $this->pdo;
        }

        $driver = $this->config['driver'] ?? 'mysql';

        if ($driver === 'sqlite') {
            $database = $this->config['database'] ?? ':memory:';
            $dsn = 'sqlite:' . $database;
        } else {
            $host = $this->config['host'] ?? '127.0.0.1';
            $port = $this->config['port'] ?? 3306;
            $database = $this->config['database'] ?? '';
            $charset = $this->config['charset'] ?? 'utf8mb4';
            $dsn = "mysql:host={$host};port={$port};dbname={$database};charset={$charset}";
        }

        try {
            $this->pdo = new PDO(
                $dsn,
                $this->config['username'] ?? null,
                $this->config['password'] ?? null,
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false,
                ]
            );
        } catch (PDOException $exception) {
            throw new RuntimeException('Database connection failed.', 0, $exception);
        }

        return $this->pdo;
    }
}
