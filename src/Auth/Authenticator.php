<?php

declare(strict_types=1);

namespace Taydence\Auth;

use Taydence\Database\Database;

final class Authenticator
{
    private ?array $user = null;

    public function __construct(
        private readonly Database $database,
        private readonly string $table = 'users',
        private readonly string $identifier = 'email'
    ) {}

    public function attempt(string $identifier, string $password): bool
    {
        $this->startSession();
        $user = $this->database->table($this->table)
            ->where($this->identifier, '=', $identifier)
            ->first();

        if ($user === null || !isset($user['password']) || !password_verify($password, $user['password'])) {
            $this->user = null;
            return false;
        }

        $this->user = $user;
        $_SESSION['taydence_user_id'] = $user['id'] ?? null;

        return true;
    }

    public function check(): bool
    {
        return $this->user() !== null;
    }

    public function user(): ?array
    {
        $this->startSession();

        if ($this->user !== null) {
            return $this->user;
        }

        $id = $_SESSION['taydence_user_id'] ?? null;

        if ($id === null) {
            return null;
        }

        return $this->user = $this->database->table($this->table)
            ->where('id', '=', $id)
            ->first();
    }

    public function logout(): void
    {
        $this->startSession();
        $this->user = null;
        unset($_SESSION['taydence_user_id']);
    }

    private function startSession(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    public static function hashPassword(string $password): string
    {
        return password_hash($password, PASSWORD_DEFAULT);
    }
}
