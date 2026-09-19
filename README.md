# Taydence Framework

Taydence Framework is a small PHP web framework built from scratch to understand how frameworks such as Laravel work internally.

It is a learning and experimentation project, not a replacement for Laravel.

## Current milestone: v1.0

The framework has progressed from raw HTTP handling to a small, coherent application stack:

**Request → Middleware → Router → Container → Controller → Validation/Database → Response**

### Included

- HTTP request and response objects
- GET and POST routing
- Dynamic route parameters
- Controller resolution through dependency injection
- Application middleware and middleware pipelines
- Configuration with nested dot notation
- .env environment variables
- PDO database connections
- Database transactions
- Parameterized query builder
- Active Record-style model foundation
- Validation rules
- Central exception handling
- Password hashing and session authentication
- Authentication middleware
- CLI code generators
- PHPUnit tests
- GitHub Actions CI for PHP 8.2, 8.3 and 8.4

## Requirements

- PHP 8.2+
- Composer
- PDO for the database driver you use

## Quick start

```bash
composer install
cp .env.example .env
php -S localhost:8000 -t public
```

On Windows PowerShell:

```powershell
Copy-Item .env.example .env
```

## Environment

.env is intentionally ignored by Git.

```text
APP_NAME="Taydence Framework"
APP_ENV=local
APP_DEBUG=true

DB_CONNECTION=sqlite
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=taydence
DB_USERNAME=
DB_PASSWORD=
```

Read values with:

```php
env('APP_NAME');
env('APP_DEBUG', false);
```

## Database

The container exposes the database service:

```php
use Taydence\Database\Database;

$database = $app->make(Database::class);
```

Use the query builder:

```php
$users = $database->table('users')
    ->where('active', '=', 1)
    ->orderBy('name')
    ->limit(20)
    ->get();
```

Transactions are supported:

```php
$database->transaction(function (Database $database) {
    // multiple database operations
});
```

Values are parameterized and SQL identifiers are validated.

## Models

```php
use Taydence\Database\Model;

final class User extends Model
{
    protected string $table = 'users';

    protected array $fillable = [
        'name',
        'email',
    ];
}
```

Create and persist a model:

```php
$user = User::createWith($database, [
    'name' => 'Mark',
    'email' => 'mark@example.com',
]);
```

This is intentionally a small Active Record foundation rather than a full ORM.

## Validation

```php
$validated = $app->validate($request->input(), [
    'name' => 'required|string|min:3|max:100',
    'email' => 'required|email',
    'age' => 'nullable|integer|min:18',
]);
```

Current rules include required, nullable, string, integer, numeric, email, min, max and in.

Validation failures produce HTTP 422 responses.

## Authentication

```php
$auth = $app->auth();

if ($auth->attempt($request->input('email'), $request->input('password'))) {
    // authenticated
}
```

Password hashing:

```php
Authenticator::hashPassword($password);
```

Protect routes with AuthMiddleware.

Authentication is deliberately minimal in v1.0. Production applications still need features such as CSRF protection, password reset, rate limiting and stronger session policies.

## CLI

```bash
php bin/taydence help
php bin/taydence make:controller UserController
php bin/taydence make:model User
```

## Testing

```bash
composer test
composer lint
```

GitHub Actions runs tests and syntax checks across PHP 8.2, 8.3 and 8.4.

## Example routes

- /
- /about
- /hello?name=Mark
- /hello/Mark
- /users/42

## Architecture

```text
HTTP Request
     ↓
Application
     ↓
Middleware Pipeline
     ↓
Router
     ↓
Container
 ┌───┴───────────────┐
Controller       Database
                    ↓
              Query Builder
                    ↓
                  Model
     ↓
Validation / Errors
     ↓
HTTP Response
```

## Roadmap

### Completed

- [x] HTTP foundation
- [x] Routing
- [x] Route parameters
- [x] Controllers
- [x] Middleware
- [x] Dependency injection
- [x] Configuration
- [x] Environment variables
- [x] Database connection
- [x] Query builder
- [x] Model foundation
- [x] Validation
- [x] Error handling
- [x] Authentication foundation
- [x] CLI generators
- [x] Testing utilities
- [x] CI

### Future beyond v1.0

- Full ORM relationships and eager loading
- Database migrations and schema builder
- CSRF protection
- Advanced authentication and authorization
- Sessions abstraction
- Queues and jobs
- Event system
- Caching
- Production-grade CLI
- Package ecosystem

The goal remains to understand framework internals by building them from first principles.

## License

MIT
